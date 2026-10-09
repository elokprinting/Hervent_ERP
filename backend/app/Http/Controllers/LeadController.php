<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreLeadRequest;
use App\Http\Requests\UpdateLeadRequest;
use App\Models\Lead;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class LeadController extends Controller
{
    public function index(): JsonResponse
    {
        $leads = Lead::query()
            ->with(['items', 'pic:id,name,email', 'creator:id,name,email'])
            ->withCount('followUps')
            ->latest()
            ->paginate(15);

        return response()->json($leads);
    }

    public function store(StoreLeadRequest $request): JsonResponse
    {
        $lead = DB::transaction(function () use ($request): Lead {
            $data = $request->validated();
            $lead = Lead::create([
                ...Arr::except($data, ['items']),
                'created_by_user_id' => $request->user()->getKey(),
            ]);

            $lead->replaceItems($data['items']);

            return $lead;
        });

        return response()->json([
            'data' => $lead->load(['items', 'pic:id,name,email', 'creator:id,name,email']),
        ], 201);
    }

    public function show(Lead $lead): JsonResponse
    {
        return response()->json([
            'data' => $lead->load([
                'items',
                'pic:id,name,email',
                'creator:id,name,email',
                'followUps' => fn ($query) => $query
                    ->with('user:id,name,email')
                    ->orderBy('followed_up_at')
                    ->orderBy('id'),
            ]),
        ]);
    }

    public function update(UpdateLeadRequest $request, Lead $lead): JsonResponse
    {
        $lead = DB::transaction(function () use ($request, $lead): Lead {
            $data = $request->validated();
            $lead->fill(Arr::except($data, ['items']))->save();

            if (array_key_exists('items', $data)) {
                $lead->replaceItems($data['items']);
            }

            return $lead;
        });

        return response()->json([
            'data' => $lead->load(['items', 'pic:id,name,email', 'creator:id,name,email']),
        ]);
    }
}
