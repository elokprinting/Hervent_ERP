<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreLeadFollowUpRequest;
use App\Models\Lead;
use App\Models\LeadFollowUp;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class LeadFollowUpController extends Controller
{
    public function index(Lead $lead): JsonResponse
    {
        return response()->json([
            'data' => $lead->followUps()
                ->with('user:id,name,email')
                ->orderBy('followed_up_at')
                ->orderBy('id')
                ->get(),
        ]);
    }

    public function store(StoreLeadFollowUpRequest $request, Lead $lead): JsonResponse
    {
        $followUp = DB::transaction(function () use ($request, $lead): LeadFollowUp {
            $data = $request->validated();
            $attributes = Arr::only($data, [
                'customer_name',
                'customer_company',
                'customer_contact',
                'customer_email',
                'customer_address',
                'deadline',
                'pic_user_id',
            ]);
            $changes = [];

            if ($attributes !== []) {
                $before = $lead->only(array_keys($attributes));
                $lead->fill($attributes)->save();
                $after = $lead->only(array_keys($attributes));

                foreach ($attributes as $field => $value) {
                    $changes[$field] = [
                        'before' => $this->serializeValue($before[$field]),
                        'after' => $this->serializeValue($after[$field]),
                    ];
                }
            }

            if (array_key_exists('items', $data)) {
                $beforeItems = $lead->items()
                    ->get(['product_name', 'details', 'quantity', 'sort_order'])
                    ->toArray();

                $lead->replaceItems($data['items']);
                $afterItems = $lead->items()
                    ->get(['product_name', 'details', 'quantity', 'sort_order'])
                    ->toArray();

                $changes['items'] = [
                    'before' => $beforeItems,
                    'after' => $afterItems,
                ];
            }

            $user = $request->user();

            return $lead->followUps()->create([
                'user_id' => $user->getKey(),
                'user_name' => $user->name,
                'notes' => $data['notes'],
                'followed_up_at' => $data['followed_up_at'] ?? now(),
                'changes' => $changes === [] ? null : $changes,
            ]);
        });

        return response()->json([
            'data' => $followUp->load('user:id,name,email'),
        ], 201);
    }

    private function serializeValue(mixed $value): mixed
    {
        if ($value instanceof \DateTimeInterface) {
            return $value->format('Y-m-d');
        }

        return $value;
    }
}
