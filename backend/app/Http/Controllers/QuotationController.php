<?php

namespace App\Http\Controllers;

use App\Http\Requests\SendQuotationRequest;
use App\Http\Requests\StoreQuotationRequest;
use App\Http\Requests\UpdateQuotationRequest;
use App\Models\Lead;
use App\Models\Quotation;
use App\Support\QuotationAmounts;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class QuotationController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json(
            Quotation::query()
                ->with(['lead:id,customer_name', 'items'])
                ->latest()
                ->paginate(15),
        );
    }

    public function store(StoreQuotationRequest $request, Lead $lead, QuotationAmounts $amounts): JsonResponse
    {
        $data = $request->validated();

        $quotation = DB::transaction(function () use ($request, $lead, $data, $amounts): Quotation {
            $lockedLead = Lead::query()->whereKey($lead->getKey())->lockForUpdate()->firstOrFail();

            if ($lockedLead->quotations()->exists()) {
                abort(409, 'A quotation already exists for this lead. Create a new revision instead.');
            }

            $quotation = $lockedLead->quotations()->create([
                'revision' => 1,
                'status' => 'draft',
                'currency' => 'IDR',
                'customer_name' => $lockedLead->customer_name,
                'customer_company' => $lockedLead->customer_company,
                'customer_contact' => $lockedLead->customer_contact,
                'customer_email' => $lockedLead->customer_email,
                'customer_address' => $lockedLead->customer_address,
                'created_by_user_id' => $request->user()->getKey(),
            ]);

            $quotation->replaceItems($data['items'], $amounts);

            return $quotation;
        });

        return response()->json([
            'data' => $quotation->load(['lead:id,customer_name', 'items', 'creator:id,name,email']),
        ], 201);
    }

    public function show(Quotation $quotation): JsonResponse
    {
        return response()->json([
            'data' => $quotation->load([
                'lead:id,customer_name',
                'items',
                'creator:id,name,email',
                'sender:id,name,email',
                'previousQuotation:id,revision,status',
                'revisions:id,previous_quotation_id,revision,status',
            ]),
        ]);
    }

    public function history(Quotation $quotation): JsonResponse
    {
        return response()->json([
            'data' => Quotation::query()
                ->where('lead_id', $quotation->lead_id)
                ->with(['items', 'creator:id,name,email', 'sender:id,name,email'])
                ->orderBy('revision')
                ->get(),
        ]);
    }

    public function update(
        UpdateQuotationRequest $request,
        Quotation $quotation,
        QuotationAmounts $amounts,
    ): JsonResponse {
        $data = $request->validated();

        [$quotation, $createdRevision] = DB::transaction(function () use ($request, $data, $quotation, $amounts): array {
            $lead = Lead::query()->whereKey($quotation->lead_id)->lockForUpdate()->firstOrFail();
            $latest = $lead->quotations()->lockForUpdate()->orderByDesc('revision')->firstOrFail();

            abort_unless(
                $latest->is($quotation) && $latest->status === 'draft',
                409,
                'Only the latest draft quotation can be edited.',
            );

            $attributes = Arr::except($data, ['items']);
            $pricingChanged = array_key_exists('items', $data)
                && $amounts->hasPricingChanges($latest->items()->get(), $data['items']);

            if ($pricingChanged) {
                $revision = $lead->quotations()->create([
                    ...$latest->only([
                        'customer_name',
                        'customer_company',
                        'customer_contact',
                        'customer_email',
                        'customer_address',
                        'currency',
                    ]),
                    ...$attributes,
                    'previous_quotation_id' => $latest->getKey(),
                    'revision' => $latest->revision + 1,
                    'status' => 'draft',
                    'created_by_user_id' => $request->user()->getKey(),
                ]);

                $latest->forceFill(['status' => 'superseded'])->save();
                $revision->replaceItems($data['items'], $amounts);

                return [$revision, true];
            }

            $latest->fill($attributes)->save();

            if (array_key_exists('items', $data)) {
                $latest->replaceItems($data['items'], $amounts);
            }

            return [$latest, false];
        });

        return response()->json([
            'data' => $quotation->load(['lead:id,customer_name', 'items']),
        ], $createdRevision ? 201 : 200);
    }

    public function send(SendQuotationRequest $request, Quotation $quotation): JsonResponse
    {
        $quotation = DB::transaction(function () use ($request, $quotation): Quotation {
            $lead = Lead::query()->whereKey($quotation->lead_id)->lockForUpdate()->firstOrFail();
            $latest = $lead->quotations()->lockForUpdate()->orderByDesc('revision')->firstOrFail();

            abort_unless(
                $latest->is($quotation) && $latest->status === 'draft',
                409,
                'Only the latest draft quotation can be sent.',
            );

            $latest->forceFill([
                'status' => 'sent',
                'sent_to_email' => $request->validated('sent_to_email'),
                'sent_at' => now(),
                'sent_by_user_id' => $request->user()->getKey(),
            ])->save();

            return $latest;
        });

        return response()->json([
            'data' => $quotation->load(['lead:id,customer_name', 'items', 'sender:id,name,email']),
        ]);
    }

    public function revise(Request $request, Quotation $quotation): JsonResponse
    {
        $revision = DB::transaction(function () use ($request, $quotation): Quotation {
            $lead = Lead::query()->whereKey($quotation->lead_id)->lockForUpdate()->firstOrFail();
            $latest = $lead->quotations()->lockForUpdate()->orderByDesc('revision')->first();

            abort_unless(
                $latest !== null
                    && $latest->is($quotation)
                    && $latest->status === 'sent',
                409,
                'Only the latest sent quotation can be revised.',
            );

            $quotation = $latest;
            $quotation->loadMissing('items');
            $revision = $lead->quotations()->create([
                'previous_quotation_id' => $quotation->getKey(),
                'revision' => $quotation->revision + 1,
                'status' => 'draft',
                'currency' => $quotation->currency,
                'customer_name' => $quotation->customer_name,
                'customer_company' => $quotation->customer_company,
                'customer_contact' => $quotation->customer_contact,
                'customer_email' => $quotation->customer_email,
                'customer_address' => $quotation->customer_address,
                'created_by_user_id' => $request->user()->getKey(),
                'subtotal' => $quotation->subtotal,
                'total' => $quotation->total,
            ]);

            $revision->items()->createMany($quotation->items->map(fn ($item): array => [
                'product_name' => $item->product_name,
                'details' => $item->details,
                'quantity' => $item->quantity,
                'unit_price' => $item->unit_price,
                'line_total' => $item->line_total,
                'sort_order' => $item->sort_order,
            ])->all());

            return $revision;
        });

        return response()->json([
            'data' => $revision->load(['lead:id,customer_name', 'items', 'creator:id,name,email']),
        ], 201);
    }
}
