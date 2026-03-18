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
    public function adminCanViewAnyOrders(): void
    {
        $this->assertTrue($this->policy->viewAny($this->admin));
    }

    #[Test]
    public function demandeurCanViewAnyOrders(): void
    {
        $this->assertTrue($this->policy->viewAny($this->demandeur));
    }

    #[Test]
    public function adminCanViewAnyOrder(): void
    {
        $order = Order::factory()->sent()->create();

        $this->assertTrue($this->policy->view($this->admin, $order));
    }

    #[Test]
    public function demandeurCanViewOwnOrder(): void
    {
        $order = Order::factory()->sent()->create(['user_id' => $this->demandeur->id]);

        $this->assertTrue($this->policy->view($this->demandeur, $order));
    }

    #[Test]
    public function demandeurCannotViewOthersOrder(): void
    {
        $order = Order::factory()->sent()->create();

        $this->assertFalse($this->policy->view($this->demandeur, $order));
    }

    #[Test]
    public function adminCanCreateOrders(): void
    {
        $this->assertTrue($this->policy->create($this->admin));
    }

    #[Test]
    public function demandeurCanCreateOrders(): void
    {
        $this->assertTrue($this->policy->create($this->demandeur));
    }

    #[Test]
    public function adminCanUpdateAnyOrder(): void
    {
        $order = Order::factory()->processing()->create();

        $this->assertTrue($this->policy->update($this->admin, $order));
    }

    #[Test]
    public function demandeurCanUpdateOwnOrderInSentStatus(): void
    {
        $order = Order::factory()->sent()->create(['user_id' => $this->demandeur->id]);

        $this->assertTrue($this->policy->update($this->demandeur, $order));
    }

    #[Test]
    public function demandeurCannotUpdateOwnOrderInProcessingStatus(): void
    {
        $order = Order::factory()->processing()->create(['user_id' => $this->demandeur->id]);

        $this->assertFalse($this->policy->update($this->demandeur, $order));
    }

    #[Test]
    public function demandeurCannotUpdateOthersOrder(): void
    {
        $order = Order::factory()->sent()->create();

        $this->assertFalse($this->policy->update($this->demandeur, $order));
    }

    #[Test]
    public function adminCanCancelNonTerminalOrder(): void
    {
        foreach ([OrderStatus::Sent, OrderStatus::Processing, OrderStatus::Ordered, OrderStatus::Received] as $status) {
            $order = Order::factory()->create(['status' => $status]);

            $this->assertTrue($this->policy->cancel($this->admin, $order), "Admin should be able to cancel {$status->name} order");
        }
    }

    #[Test]
    public function adminCannotCancelTerminalOrder(): void
    {
        foreach ([OrderStatus::Closed, OrderStatus::Cancelled] as $status) {
            $order = Order::factory()->create(['status' => $status]);

            $this->assertFalse($this->policy->cancel($this->admin, $order), "Admin should not be able to cancel {$status->name} order");
        }
    }

    #[Test]
    public function demandeurCanCancelOwnSentOrder(): void
    {
        $order = Order::factory()->sent()->create(['user_id' => $this->demandeur->id]);

        $this->assertTrue($this->policy->cancel($this->demandeur, $order));
    }

    #[Test]
    public function demandeurCannotCancelOwnProcessingOrder(): void
    {
        $order = Order::factory()->processing()->create(['user_id' => $this->demandeur->id]);

        $this->assertFalse($this->policy->cancel($this->demandeur, $order));
    }

    #[Test]
    public function demandeurCannotCancelOthersOrder(): void
    {
        $order = Order::factory()->sent()->create();

        $this->assertFalse($this->policy->cancel($this->demandeur, $order));
    }

    #[Test]
    public function adminCanProcessSentOrder(): void
    {
        $order = Order::factory()->sent()->create();

        $this->assertTrue($this->policy->processOrder($this->admin, $order));
    }

    #[Test]
    public function adminCannotProcessNonSentOrder(): void
    {
        $order = Order::factory()->processing()->create();

        $this->assertFalse($this->policy->processOrder($this->admin, $order));
    }

    #[Test]
    public function demandeurCannotProcessOrder(): void
    {
        $order = Order::factory()->sent()->create(['user_id' => $this->demandeur->id]);

        $this->assertFalse($this->policy->processOrder($this->demandeur, $order));
    }

    #[Test]
    public function adminCanMarkProcessingOrderAsOrdered(): void
    {
        $order = Order::factory()->processing()->create();

        $this->assertTrue($this->policy->markOrdered($this->admin, $order));
    }

    #[Test]
    public function adminCannotMarkSentOrderAsOrdered(): void
    {
        $order = Order::factory()->sent()->create();

        $this->assertFalse($this->policy->markOrdered($this->admin, $order));
    }

    #[Test]
    public function demandeurCannotMarkOrdered(): void
    {
        $order = Order::factory()->processing()->create(['user_id' => $this->demandeur->id]);

        $this->assertFalse($this->policy->markOrdered($this->demandeur, $order));
    }

    #[Test]
    public function adminCanMarkOrderedAsReceived(): void
    {
        $order = Order::factory()->ordered()->create();

        $this->assertTrue($this->policy->markReceived($this->admin, $order));
    }

    #[Test]
    public function adminCannotMarkSentAsReceived(): void
    {
        $order = Order::factory()->sent()->create();

        $this->assertFalse($this->policy->markReceived($this->admin, $order));
    }

    #[Test]
    public function demandeurCannotMarkReceived(): void
    {
        $order = Order::factory()->ordered()->create(['user_id' => $this->demandeur->id]);

        $this->assertFalse($this->policy->markReceived($this->demandeur, $order));
    }

    #[Test]
    public function adminCanCloseReceivedOrder(): void
    {
        $order = Order::factory()->received()->create();

        $this->assertTrue($this->policy->closeOrder($this->admin, $order));
    }

    #[Test]
    public function adminCannotCloseNonReceivedOrder(): void
    {
        $order = Order::factory()->ordered()->create();

        $this->assertFalse($this->policy->closeOrder($this->admin, $order));
    }

    #[Test]
    public function demandeurCannotCloseOrder(): void
    {
        $order = Order::factory()->received()->create(['user_id' => $this->demandeur->id]);

        $this->assertFalse($this->policy->closeOrder($this->demandeur, $order));
    }
}
