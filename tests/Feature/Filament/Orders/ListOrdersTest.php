<?php

namespace Tests\Feature\Filament\Orders;

use App\Enums\OrderStatus;
use App\Filament\App\Resources\Orders\Pages\ListOrders;
use App\Models\Order;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use PHPUnit\Framework\Attributes\Test;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class ListOrdersTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    private User $demandeur;

    private User $otherDemandeur;

    protected function setUp(): void
    {
        parent::setUp();

        $permissions = [
            'ViewAny:Order', 'View:Order', 'Create:Order', 'Update:Order',
            'Delete:Order', 'BypassOwnership:Order',
        ];

        foreach ($permissions as $perm) {
            Permission::findOrCreate($perm, 'web');
        }

        $adminRole = Role::findOrCreate('super_admin', 'web');
        $adminRole->syncPermissions($permissions);

        $demandeurRole = Role::findOrCreate('demandeurs', 'web');
        $demandeurRole->syncPermissions(['ViewAny:Order', 'View:Order', 'Create:Order', 'Update:Order']);

        $this->admin = User::factory()->create();
        $this->admin->assignRole('super_admin');

        $this->demandeur = User::factory()->create();
        $this->demandeur->assignRole('demandeurs');

        $this->otherDemandeur = User::factory()->create();
        $this->otherDemandeur->assignRole('demandeurs');
    }

    #[Test]
    public function admin_can_load_list_page(): void
    {
        $this->actingAs($this->admin);

        Livewire::test(ListOrders::class)
            ->assertOk();
    }

    #[Test]
    public function demandeur_can_load_list_page(): void
    {
        $this->actingAs($this->demandeur);

        Livewire::test(ListOrders::class)
            ->assertOk();
    }

    #[Test]
    public function admin_sees_all_orders(): void
    {
        $ownOrder = Order::factory()->sent()->create(['user_id' => $this->admin->id]);
        $demandeurOrder = Order::factory()->sent()->create(['user_id' => $this->demandeur->id]);
        $otherOrder = Order::factory()->sent()->create(['user_id' => $this->otherDemandeur->id]);

        $this->actingAs($this->admin);

        Livewire::test(ListOrders::class)
            ->assertCanSeeTableRecords([$ownOrder, $demandeurOrder, $otherOrder]);
    }

    #[Test]
    public function demandeur_sees_only_own_orders(): void
    {
        $ownOrder = Order::factory()->sent()->create(['user_id' => $this->demandeur->id]);
        $otherOrder = Order::factory()->sent()->create(['user_id' => $this->otherDemandeur->id]);

        $this->actingAs($this->demandeur);

        Livewire::test(ListOrders::class)
            ->assertCanSeeTableRecords([$ownOrder])
            ->assertCanNotSeeTableRecords([$otherOrder]);
    }

    #[Test]
    public function can_filter_by_status(): void
    {
        $sentOrder = Order::factory()->sent()->create(['user_id' => $this->admin->id]);
        $processingOrder = Order::factory()->processing()->create(['user_id' => $this->admin->id]);
        $closedOrder = Order::factory()->closed()->create(['user_id' => $this->admin->id]);

        $this->actingAs($this->admin);

        Livewire::test(ListOrders::class)
            ->assertCanSeeTableRecords([$sentOrder, $processingOrder, $closedOrder])
            ->filterTable('status', [OrderStatus::Sent->value])
            ->assertCanSeeTableRecords([$sentOrder])
            ->assertCanNotSeeTableRecords([$processingOrder, $closedOrder]);
    }
}
