<?php

namespace Tests\Feature;

use App\Models\Lead;
use App\Models\LeadFollowUp;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LeadWorkflowTest extends TestCase
{
    use RefreshDatabase;

    public function test_unauthenticated_request_cannot_list_leads(): void
    {
        $this->getJson('/api/leads')->assertUnauthorized();
    }

    public function test_authenticated_user_can_create_lead_with_multiple_items(): void
    {
        $creator = User::factory()->create();
        $pic = User::factory()->create();

        $response = $this->actingAs($creator)->postJson('/api/leads', [
            'customer_name' => 'PT Contoh',
            'customer_company' => 'Contoh Group',
            'customer_contact' => '08123456789',
            'customer_email' => 'buyer@example.test',
            'customer_address' => 'Jakarta',
            'deadline' => '2026-11-30',
            'pic_user_id' => $pic->id,
            'items' => [
                ['product_name' => 'Dus kemasan', 'quantity' => 200],
                ['product_name' => 'Label produk', 'details' => 'Ukuran 10 x 5 cm', 'quantity' => 500],
            ],
        ]);

        $response->assertCreated()
            ->assertJsonPath('data.customer_name', 'PT Contoh')
            ->assertJsonPath('data.created_by_user_id', $creator->id)
            ->assertJsonPath('data.pic_user_id', $pic->id)
            ->assertJsonCount(2, 'data.items');

        $this->assertDatabaseHas('leads', [
            'id' => $response->json('data.id'),
            'customer_email' => 'buyer@example.test',
        ]);
        $this->assertDatabaseHas('lead_items', [
            'lead_id' => $response->json('data.id'),
            'product_name' => 'Label produk',
            'quantity' => 500,
        ]);
    }

    public function test_lead_cannot_be_created_without_required_customer_item_deadline_or_pic_data(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)->postJson('/api/leads', [
            'customer_name' => 'PT Contoh',
            'items' => [],
        ])->assertUnprocessable()
            ->assertJsonValidationErrors(['deadline', 'pic_user_id', 'items']);

        $this->assertDatabaseCount('leads', 0);
    }

    public function test_authenticated_user_can_update_lead_details_and_replace_items(): void
    {
        $creator = User::factory()->create();
        $pic = User::factory()->create();
        $lead = Lead::factory()->create([
            'created_by_user_id' => $creator->id,
            'pic_user_id' => $pic->id,
        ]);
        $lead->items()->create([
            'product_name' => 'Produk lama',
            'quantity' => 1,
            'sort_order' => 0,
        ]);

        $this->actingAs($creator)->patchJson("/api/leads/{$lead->id}", [
            'customer_contact' => '08111111111',
            'items' => [
                ['product_name' => 'Produk baru', 'quantity' => 8],
            ],
        ])->assertOk()
            ->assertJsonPath('data.customer_contact', '08111111111')
            ->assertJsonPath('data.items.0.product_name', 'Produk baru');

        $this->assertDatabaseMissing('lead_items', [
            'lead_id' => $lead->id,
            'product_name' => 'Produk lama',
        ]);
        $this->assertDatabaseHas('lead_items', [
            'lead_id' => $lead->id,
            'product_name' => 'Produk baru',
            'quantity' => 8,
        ]);
    }

    public function test_follow_up_records_sales_user_and_changes_to_lead(): void
    {
        $salesUser = User::factory()->create();
        $pic = User::factory()->create();
        $lead = Lead::factory()->create([
            'customer_name' => 'PT Sebelum',
            'pic_user_id' => $pic->id,
        ]);
        $lead->items()->create([
            'product_name' => 'Box A',
            'quantity' => 10,
            'sort_order' => 0,
        ]);

        $response = $this->actingAs($salesUser)->postJson("/api/leads/{$lead->id}/follow-ups", [
            'notes' => 'Pelanggan meminta revisi jumlah.',
            'customer_contact' => '08123456789',
            'items' => [
                ['product_name' => 'Box A', 'quantity' => 20],
            ],
        ]);

        $response->assertCreated()
            ->assertJsonPath('data.user_id', $salesUser->id)
            ->assertJsonPath('data.user_name', $salesUser->name)
            ->assertJsonPath('data.changes.customer_contact.after', '08123456789')
            ->assertJsonPath('data.changes.items.before.0.quantity', 10)
            ->assertJsonPath('data.changes.items.after.0.quantity', 20);

        $this->assertDatabaseHas('leads', [
            'id' => $lead->id,
            'customer_contact' => '08123456789',
        ]);
        $this->assertDatabaseHas('lead_items', [
            'lead_id' => $lead->id,
            'product_name' => 'Box A',
            'quantity' => 20,
        ]);
        $this->assertSame(1, LeadFollowUp::query()->whereBelongsTo($lead)->count());
    }

    public function test_follow_up_history_is_available_from_lead_detail(): void
    {
        $user = User::factory()->create();
        $lead = Lead::factory()->create();
        $lead->followUps()->create([
            'user_id' => $user->id,
            'user_name' => $user->name,
            'notes' => 'Menunggu konfirmasi desain.',
            'followed_up_at' => '2026-10-09 10:00:00',
        ]);

        $this->actingAs($user)->getJson("/api/leads/{$lead->id}")
            ->assertOk()
            ->assertJsonPath('data.follow_ups.0.notes', 'Menunggu konfirmasi desain.')
            ->assertJsonPath('data.follow_ups.0.user_name', $user->name);
    }

    public function test_follow_up_requires_notes_and_does_not_change_lead_on_validation_failure(): void
    {
        $user = User::factory()->create();
        $lead = Lead::factory()->create(['customer_name' => 'PT Awal']);

        $this->actingAs($user)->postJson("/api/leads/{$lead->id}/follow-ups", [
            'customer_name' => 'PT Berubah',
        ])->assertUnprocessable()
            ->assertJsonValidationErrors('notes');

        $this->assertDatabaseHas('leads', [
            'id' => $lead->id,
            'customer_name' => 'PT Awal',
        ]);
        $this->assertDatabaseCount('lead_follow_ups', 0);
    }
}
