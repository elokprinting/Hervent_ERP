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

    public function update(
        UpdateQuotationRequest $request,
        Quotation $quotation,
        QuotationAmounts $amounts,
    ): JsonResponse {
        $data = $request->validated();

        $quotation = DB::transaction(function () use ($data, $quotation, $amounts): Quotation {
            $quotation = Quotation::query()->whereKey($quotation->getKey())->lockForUpdate()->firstOrFail();

            abort_unless($quotation->status === 'draft', 409, 'Sent quotations cannot be edited. Create a new revision.');

            $quotation->fill(Arr::except($data, ['items']))->save();

            if (array_key_exists('items', $data)) {
                $quotation->replaceItems($data['items'], $amounts);
            }

            return $quotation;
        });

        return response()->json([
            'data' => $quotation->load(['lead:id,customer_name', 'items']),
        ]);
    }

    public function send(SendQuotationRequest $request, Quotation $quotation): JsonResponse
    {
        $quotation = DB::transaction(function () use ($request, $quotation): Quotation {
            $quotation = Quotation::query()->whereKey($quotation->getKey())->lockForUpdate()->firstOrFail();

            abort_unless($quotation->status === 'draft', 409, 'Only draft quotations can be sent.');

            $quotation->forceFill([
                'status' => 'sent',
                'sent_to_email' => $request->validated('sent_to_email'),
                'sent_at' => now(),
                'sent_by_user_id' => $request->user()->getKey(),
            ])->save();

            return $quotation;
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
