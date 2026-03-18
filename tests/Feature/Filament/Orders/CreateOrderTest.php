<?php

namespace Tests\Feature\Filament\Orders;

use App\Enums\OrderStatus;
use App\Filament\App\Resources\Orders\Pages\CreateOrder;
use App\Models\Order;
use App\Models\Service;
use App\Models\Supplier;
use App\Models\User;
use App\Notifications\OrderCreated;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Livewire\Livewire;
use PHPUnit\Framework\Attributes\Test;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class CreateOrderTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    private User $demandeur;

    private Service $service;

    private Supplier $supplier;

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

        $this->service = Service::factory()->create();
        $this->supplier = Supplier::factory()->create();

        $this->admin = User::factory()->create();
        $this->admin->assignRole('super_admin');

        $this->demandeur = User::factory()->create();
        $this->demandeur->assignRole('demandeurs');
        $this->demandeur->services()->attach($this->service);
    }

    #[Test]
    public function demandeur_can_load_create_page(): void
    {
        $this->actingAs($this->demandeur);

        Livewire::test(CreateOrder::class)
            ->assertOk();
    }

    #[Test]
    public function demandeur_can_create_order_with_lines(): void
    {
        Notification::fake();

        $this->actingAs($this->demandeur);

        Livewire::test(CreateOrder::class)
            ->fillForm([
                'service_id' => $this->service->id,
                'supplier_id' => $this->supplier->id,
                'description' => 'Test description',
                'lines' => [
                    [
                        'designation' => 'Stylos bleus',
                        'quantity' => 10,
                    ],
                ],
            ])
            ->call('create')
            ->assertHasNoFormErrors();

        $order = Order::query()->latest()->first();

        $this->assertNotNull($order);
        $this->assertSame($this->demandeur->id, $order->user_id);
        $this->assertSame(OrderStatus::Sent, $order->status);
        $this->assertSame($this->service->id, $order->service_id);
        $this->assertSame($this->supplier->id, $order->supplier_id);
        $this->assertCount(1, $order->lines);
        $this->assertSame('Stylos bleus', $order->lines->first()->designation);
    }

    #[Test]
    public function demandeur_user_id_is_auto_filled(): void
    {
        Notification::fake();

        $this->actingAs($this->demandeur);

        Livewire::test(CreateOrder::class)
            ->fillForm([
                'service_id' => $this->service->id,
                'supplier_id' => $this->supplier->id,
                'lines' => [
                    [
                        'designation' => 'Test item',
                        'quantity' => 1,
                    ],
                ],
            ])
            ->call('create')
            ->assertHasNoFormErrors();

        $order = Order::query()->latest()->first();

        $this->assertSame($this->demandeur->id, $order->user_id);
    }

    #[Test]
    public function status_defaults_to_sent(): void
    {
        Notification::fake();

        $this->actingAs($this->demandeur);

        Livewire::test(CreateOrder::class)
            ->fillForm([
                'service_id' => $this->service->id,
                'supplier_id' => $this->supplier->id,
                'lines' => [
                    [
                        'designation' => 'Test item',
                        'quantity' => 1,
                    ],
                ],
            ])
            ->call('create')
            ->assertHasNoFormErrors();

        $order = Order::query()->latest()->first();

        $this->assertSame(OrderStatus::Sent, $order->status);
    }

    #[Test]
    public function admin_is_notified_when_demandeur_creates_order(): void
    {
        Notification::fake();

        $this->actingAs($this->demandeur);

        Livewire::test(CreateOrder::class)
            ->fillForm([
                'service_id' => $this->service->id,
                'supplier_id' => $this->supplier->id,
                'lines' => [
                    [
                        'designation' => 'Test item',
                        'quantity' => 1,
                    ],
                ],
            ])
            ->call('create')
            ->assertHasNoFormErrors();

        Notification::assertSentTo($this->admin, OrderCreated::class);
    }

    #[Test]
    public function supplier_is_required(): void
    {
        $this->actingAs($this->demandeur);

        Livewire::test(CreateOrder::class)
            ->fillForm([
                'service_id' => $this->service->id,
                'supplier_id' => null,
                'lines' => [
                    [
                        'designation' => 'Test item',
                        'quantity' => 1,
                    ],
                ],
            ])
            ->call('create')
            ->assertHasFormErrors(['supplier_id' => 'required']);
    }

    #[Test]
    public function designation_is_required(): void
    {
        $this->actingAs($this->demandeur);

        Livewire::test(CreateOrder::class)
            ->fillForm([
                'service_id' => $this->service->id,
                'supplier_id' => $this->supplier->id,
                'lines' => [
                    [
                        'designation' => null,
                        'quantity' => 1,
                    ],
                ],
            ])
            ->call('create')
            ->assertHasFormErrors(['lines.0.designation' => 'required']);
    }
}
