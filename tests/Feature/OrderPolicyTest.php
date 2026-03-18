<?php

namespace Tests\Feature;

use App\Enums\OrderStatus;
use App\Models\Order;
use App\Models\User;
use App\Policies\OrderPolicy;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class OrderPolicyTest extends TestCase
{
    use RefreshDatabase;

    private OrderPolicy $policy;

    private User $admin;

    private User $demandeur;

    protected function setUp(): void
    {
        parent::setUp();

        $this->policy = new OrderPolicy;

        $permissions = [
            'ViewAny:Order', 'View:Order', 'Create:Order', 'Update:Order',
            'Delete:Order', 'Restore:Order', 'ForceDelete:Order',
            'BypassOwnership:Order',
        ];

        foreach ($permissions as $perm) {
            Permission::findOrCreate($perm, 'web');
        }

        $adminRole = Role::findOrCreate('super_admin', 'web');
        $adminRole->syncPermissions([
            'ViewAny:Order', 'View:Order', 'Create:Order', 'Update:Order',
            'Delete:Order', 'Restore:Order', 'ForceDelete:Order',
            'BypassOwnership:Order',
        ]);

        $demandeurRole = Role::findOrCreate('demandeurs', 'web');
        $demandeurRole->syncPermissions([
            'ViewAny:Order', 'View:Order', 'Create:Order', 'Update:Order',
        ]);

        $this->admin = User::factory()->create();
        $this->admin->assignRole('super_admin');

        $this->demandeur = User::factory()->create();
        $this->demandeur->assignRole('demandeurs');
    }

    #[Test]
    public function admin_can_view_any_orders(): void
    {
        $this->assertTrue($this->policy->viewAny($this->admin));
    }

    #[Test]
    public function demandeur_can_view_any_orders(): void
    {
        $this->assertTrue($this->policy->viewAny($this->demandeur));
    }

    #[Test]
    public function admin_can_view_any_order(): void
    {
        $order = Order::factory()->sent()->create();

        $this->assertTrue($this->policy->view($this->admin, $order));
    }

    #[Test]
    public function demandeur_can_view_own_order(): void
    {
        $order = Order::factory()->sent()->create(['user_id' => $this->demandeur->id]);

        $this->assertTrue($this->policy->view($this->demandeur, $order));
    }

    #[Test]
    public function demandeur_cannot_view_others_order(): void
    {
        $order = Order::factory()->sent()->create();

        $this->assertFalse($this->policy->view($this->demandeur, $order));
    }

    #[Test]
    public function admin_can_create_orders(): void
    {
        $this->assertTrue($this->policy->create($this->admin));
    }

    #[Test]
    public function demandeur_can_create_orders(): void
    {
        $this->assertTrue($this->policy->create($this->demandeur));
    }

    #[Test]
    public function admin_can_update_any_order(): void
    {
        $order = Order::factory()->processing()->create();

        $this->assertTrue($this->policy->update($this->admin, $order));
    }

    #[Test]
    public function demandeur_can_update_own_order_in_sent_status(): void
    {
        $order = Order::factory()->sent()->create(['user_id' => $this->demandeur->id]);

        $this->assertTrue($this->policy->update($this->demandeur, $order));
    }

    #[Test]
    public function demandeur_cannot_update_own_order_in_processing_status(): void
    {
        $order = Order::factory()->processing()->create(['user_id' => $this->demandeur->id]);

        $this->assertFalse($this->policy->update($this->demandeur, $order));
    }

    #[Test]
    public function demandeur_cannot_update_others_order(): void
    {
        $order = Order::factory()->sent()->create();

        $this->assertFalse($this->policy->update($this->demandeur, $order));
    }

    #[Test]
    public function admin_can_cancel_non_terminal_order(): void
    {
        foreach ([OrderStatus::Sent, OrderStatus::Processing, OrderStatus::Ordered, OrderStatus::Received] as $status) {
            $order = Order::factory()->create(['status' => $status]);

            $this->assertTrue($this->policy->cancel($this->admin, $order), "Admin should be able to cancel {$status->name} order");
        }
    }

    #[Test]
    public function admin_cannot_cancel_terminal_order(): void
    {
        foreach ([OrderStatus::Closed, OrderStatus::Cancelled] as $status) {
            $order = Order::factory()->create(['status' => $status]);

            $this->assertFalse($this->policy->cancel($this->admin, $order), "Admin should not be able to cancel {$status->name} order");
        }
    }

    #[Test]
    public function demandeur_can_cancel_own_sent_order(): void
    {
        $order = Order::factory()->sent()->create(['user_id' => $this->demandeur->id]);

        $this->assertTrue($this->policy->cancel($this->demandeur, $order));
    }

    #[Test]
    public function demandeur_cannot_cancel_own_processing_order(): void
    {
        $order = Order::factory()->processing()->create(['user_id' => $this->demandeur->id]);

        $this->assertFalse($this->policy->cancel($this->demandeur, $order));
    }

    #[Test]
    public function demandeur_cannot_cancel_others_order(): void
    {
        $order = Order::factory()->sent()->create();

        $this->assertFalse($this->policy->cancel($this->demandeur, $order));
    }

    #[Test]
    public function admin_can_process_sent_order(): void
    {
        $order = Order::factory()->sent()->create();

        $this->assertTrue($this->policy->processOrder($this->admin, $order));
    }

    #[Test]
    public function admin_cannot_process_non_sent_order(): void
    {
        $order = Order::factory()->processing()->create();

        $this->assertFalse($this->policy->processOrder($this->admin, $order));
    }

    #[Test]
    public function demandeur_cannot_process_order(): void
    {
        $order = Order::factory()->sent()->create(['user_id' => $this->demandeur->id]);

        $this->assertFalse($this->policy->processOrder($this->demandeur, $order));
    }

    #[Test]
    public function admin_can_mark_processing_order_as_ordered(): void
    {
        $order = Order::factory()->processing()->create();

        $this->assertTrue($this->policy->markOrdered($this->admin, $order));
    }

    #[Test]
    public function admin_cannot_mark_sent_order_as_ordered(): void
    {
        $order = Order::factory()->sent()->create();

        $this->assertFalse($this->policy->markOrdered($this->admin, $order));
    }

    #[Test]
    public function demandeur_cannot_mark_ordered(): void
    {
        $order = Order::factory()->processing()->create(['user_id' => $this->demandeur->id]);

        $this->assertFalse($this->policy->markOrdered($this->demandeur, $order));
    }

    #[Test]
    public function admin_can_mark_ordered_as_received(): void
    {
        $order = Order::factory()->ordered()->create();

        $this->assertTrue($this->policy->markReceived($this->admin, $order));
    }

    #[Test]
    public function admin_cannot_mark_sent_as_received(): void
    {
        $order = Order::factory()->sent()->create();

        $this->assertFalse($this->policy->markReceived($this->admin, $order));
    }

    #[Test]
    public function demandeur_cannot_mark_received(): void
    {
        $order = Order::factory()->ordered()->create(['user_id' => $this->demandeur->id]);

        $this->assertFalse($this->policy->markReceived($this->demandeur, $order));
    }

    #[Test]
    public function admin_can_close_received_order(): void
    {
        $order = Order::factory()->received()->create();

        $this->assertTrue($this->policy->closeOrder($this->admin, $order));
    }

    #[Test]
    public function admin_cannot_close_non_received_order(): void
    {
        $order = Order::factory()->ordered()->create();

        $this->assertFalse($this->policy->closeOrder($this->admin, $order));
    }

    #[Test]
    public function demandeur_cannot_close_order(): void
    {
        $order = Order::factory()->received()->create(['user_id' => $this->demandeur->id]);

        $this->assertFalse($this->policy->closeOrder($this->demandeur, $order));
    }
}
