<?php

namespace Tests\Feature;

use App\Models\Lead;
use App\Models\Quotation;
use App\Models\User;
use App\UserRole;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class QuotationWorkflowTest extends TestCase
{
    use RefreshDatabase;

    public static function nonSalesRoles(): array
    {
        $roles = array_filter(
            UserRole::cases(),
            fn (UserRole $role): bool => $role !== UserRole::Sales,
        );

        $cases = [];

        foreach ($roles as $role) {
            $cases[$role->value] = [$role];
        }

        return $cases;
    }

    public function test_guest_receives_401_when_listing_quotations(): void
    {
        $this->getJson('/api/quotations')->assertUnauthorized();
    }

    #[DataProvider('nonSalesRoles')]
    public function test_non_sales_roles_receive_403_when_listing_quotations(UserRole $role): void
    {
        $user = User::factory()->create();
        $user->forceFill(['role' => $role])->save();

        $this->actingAs($user)->getJson('/api/quotations')->assertForbidden();
    }

    public function test_system_admin_can_access_quotations_without_an_application_role(): void
    {
        $user = User::factory()->create();
        $user->forceFill(['is_system_admin' => true])->save();

        $this->actingAs($user)->getJson('/api/quotations')->assertOk();
    }

    public function test_non_sales_user_cannot_create_or_send_a_quotation(): void
    {
        $sales = $this->createSalesUser();
        $otherUser = User::factory()->create();
        $otherUser->forceFill(['role' => UserRole::Finance])->save();
        $lead = Lead::factory()->create();
        $quotation = $this->createQuotation($sales, $lead);

        $this->actingAs($otherUser)->postJson("/api/leads/{$lead->id}/quotations", [
            'items' => [['product_name' => 'Produk', 'quantity' => 1, 'unit_price' => '10.00']],
        ])->assertForbidden();

        $this->actingAs($otherUser)->postJson("/api/quotations/{$quotation->id}/send", [
            'sent_to_email' => 'buyer@example.test',
        ])->assertForbidden();

        $this->assertDatabaseHas('quotations', [
            'id' => $quotation->id,
            'status' => 'draft',
        ]);
    }

    public function test_sales_can_create_quotation_with_calculated_item_and_total_amounts(): void
    {
        $sales = $this->createSalesUser();
        $lead = Lead::factory()->create([
            'customer_name' => 'PT Snapshot',
            'customer_email' => 'buyer@example.test',
        ]);

        $response = $this->actingAs($sales)->postJson("/api/leads/{$lead->id}/quotations", [
            'items' => [
                ['product_name' => 'Kotak A', 'quantity' => 4, 'unit_price' => '12.50'],
                ['product_name' => 'Label B', 'quantity' => 3, 'unit_price' => '1.20'],
            ],
        ]);

        $response->assertCreated()
            ->assertJsonPath('data.quote_number', sprintf('QT-%05d-001', $lead->id))
            ->assertJsonPath('data.customer_name', 'PT Snapshot')
            ->assertJsonPath('data.customer_email', 'buyer@example.test')
            ->assertJsonPath('data.status', 'draft')
            ->assertJsonPath('data.subtotal', '53.60')
            ->assertJsonPath('data.total', '53.60')
            ->assertJsonPath('data.items.0.line_total', '50.00')
            ->assertJsonPath('data.items.1.line_total', '3.60');

        $this->assertDatabaseHas('quotations', [
            'lead_id' => $lead->id,
            'revision' => 1,
            'created_by_user_id' => $sales->id,
            'total' => '53.60',
        ]);
    }

    public function test_sales_cannot_create_another_initial_quotation_for_a_lead(): void
    {
        $sales = $this->createSalesUser();
        $lead = Lead::factory()->create();

        $this->actingAs($sales)->postJson("/api/leads/{$lead->id}/quotations", [
            'items' => [['product_name' => 'Kotak', 'quantity' => 1, 'unit_price' => '10.00']],
        ])->assertCreated();

        $this->actingAs($sales)->postJson("/api/leads/{$lead->id}/quotations", [
            'items' => [['product_name' => 'Kotak', 'quantity' => 1, 'unit_price' => '10.00']],
        ])->assertStatus(409);

        $this->assertDatabaseCount('quotations', 1);
    }

    public function test_quotation_creation_rejects_empty_or_overflowing_items(): void
    {
        $sales = $this->createSalesUser();
        $lead = Lead::factory()->create();

        $this->actingAs($sales)->postJson("/api/leads/{$lead->id}/quotations", [
            'items' => [],
        ])->assertUnprocessable()
            ->assertJsonValidationErrors('items');

        $this->actingAs($sales)->postJson("/api/leads/{$lead->id}/quotations", [
            'items' => [[
                'product_name' => 'Jumlah terlalu besar',
                'quantity' => 2,
                'unit_price' => '9999999999999.99',
            ]],
        ])->assertUnprocessable()
            ->assertJsonValidationErrors('items');

        $this->assertDatabaseCount('quotations', 0);
    }

    public function test_sent_quotation_is_locked_and_send_details_are_recorded(): void
    {
        $sales = $this->createSalesUser();
        $lead = Lead::factory()->create();
        $quotation = $this->createQuotation($sales, $lead);

        $response = $this->actingAs($sales)->postJson("/api/quotations/{$quotation->id}/send", [
            'sent_to_email' => 'buyer@example.test',
        ]);
        $response->assertOk()
            ->assertJsonPath('data.status', 'sent')
            ->assertJsonPath('data.sent_to_email', 'buyer@example.test')
            ->assertJsonPath('data.sent_by_user_id', $sales->id);
        $this->assertIsString($response->json('data.sent_at'));

        $this->assertDatabaseHas('quotations', [
            'id' => $quotation->id,
            'status' => 'sent',
            'sent_to_email' => 'buyer@example.test',
            'sent_by_user_id' => $sales->id,
        ]);

        $this->actingAs($sales)->patchJson("/api/quotations/{$quotation->id}", [
            'customer_name' => 'Nama setelah kirim',
        ])->assertStatus(409);
    }

    public function test_invalid_recipient_does_not_mark_quotation_as_sent(): void
    {
        $sales = $this->createSalesUser();
        $quotation = $this->createQuotation($sales, Lead::factory()->create());

        $this->actingAs($sales)->postJson("/api/quotations/{$quotation->id}/send", [
            'sent_to_email' => 'not-an-email',
        ])->assertUnprocessable()
            ->assertJsonValidationErrors('sent_to_email');

        $this->assertDatabaseHas('quotations', [
            'id' => $quotation->id,
            'status' => 'draft',
            'sent_at' => null,
        ]);
    }

    public function test_sales_can_create_revision_from_latest_sent_quotation_and_keep_prior_values(): void
    {
        $sales = $this->createSalesUser();
        $lead = Lead::factory()->create();
        $first = $this->createQuotation($sales, $lead);

        $this->actingAs($sales)->postJson("/api/quotations/{$first->id}/send", [
            'sent_to_email' => 'first@example.test',
        ])->assertOk();

        $revisionResponse = $this->actingAs($sales)->postJson("/api/quotations/{$first->id}/revisions");
        $revisionResponse->assertCreated()
            ->assertJsonPath('data.revision', 2)
            ->assertJsonPath('data.previous_quotation_id', $first->id)
            ->assertJsonPath('data.status', 'draft')
            ->assertJsonPath('data.items.0.unit_price', '10.00');

        $revision = Quotation::query()->findOrFail($revisionResponse->json('data.id'));

        $this->actingAs($sales)->patchJson("/api/quotations/{$revision->id}", [
            'items' => [['product_name' => 'Produk revisi', 'quantity' => 2, 'unit_price' => '15.00']],
        ])->assertOk()
            ->assertJsonPath('data.total', '30.00');

        $this->assertDatabaseHas('quotations', [
            'id' => $first->id,
            'revision' => 1,
            'status' => 'sent',
            'total' => '10.00',
        ]);
        $this->assertDatabaseHas('quotations', [
            'id' => $revision->id,
            'revision' => 2,
            'status' => 'draft',
            'total' => '30.00',
        ]);
    }

    public function test_revision_cannot_be_created_from_a_draft_quotation(): void
    {
        $sales = $this->createSalesUser();
        $quotation = $this->createQuotation($sales, Lead::factory()->create());

        $this->actingAs($sales)->postJson("/api/quotations/{$quotation->id}/revisions")
            ->assertStatus(409);

        $this->assertDatabaseCount('quotations', 1);
    }

    public function test_only_latest_sent_revision_can_be_revised(): void
    {
        $sales = $this->createSalesUser();
        $first = $this->createQuotation($sales, Lead::factory()->create());

        $this->actingAs($sales)->postJson("/api/quotations/{$first->id}/send", [
            'sent_to_email' => 'first@example.test',
        ])->assertOk();

        $second = $this->actingAs($sales)
            ->postJson("/api/quotations/{$first->id}/revisions")
            ->assertCreated();
        $secondId = $second->json('data.id');

        $this->actingAs($sales)->postJson("/api/quotations/{$secondId}/send", [
            'sent_to_email' => 'second@example.test',
        ])->assertOk();

        $this->actingAs($sales)->postJson("/api/quotations/{$first->id}/revisions")
            ->assertStatus(409);
        $this->actingAs($sales)->postJson("/api/quotations/{$secondId}/revisions")
            ->assertCreated()
            ->assertJsonPath('data.revision', 3);

        $this->assertDatabaseCount('quotations', 3);
    }

    public function test_role_command_assigns_a_role_after_confirmation(): void
    {
        $user = User::factory()->create();

        $this->artisan('admin:user-role', ['email' => $user->email, 'role' => 'sales'])
            ->expectsConfirmation("Change {$user->email} role from none to sales?", 'yes')
            ->expectsOutput('User role updated.')
            ->assertExitCode(0);

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'role' => UserRole::Sales->value,
        ]);
    }

    public function test_role_command_rejects_unknown_roles(): void
    {
        $this->artisan('admin:user-role', ['email' => 'unused@example.test', 'role' => 'unknown'])
            ->expectsOutputToContain('Unknown role.')
            ->assertExitCode(1);
    }

    private function createSalesUser(): User
    {
        $user = User::factory()->create();
        $user->forceFill(['role' => UserRole::Sales])->save();

        return $user;
    }

    private function createQuotation(User $sales, Lead $lead): Quotation
    {
        $response = $this->actingAs($sales)->postJson("/api/leads/{$lead->id}/quotations", [
            'items' => [['product_name' => 'Produk', 'quantity' => 1, 'unit_price' => '10.00']],
        ]);

        $response->assertCreated();

        return Quotation::query()->findOrFail($response->json('data.id'));
    }
}
