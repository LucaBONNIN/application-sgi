<?php

namespace Tests\Feature\Filament\Budgets;

use App\Filament\App\Resources\Budgets\BudgetResource;
use App\Filament\App\Resources\Budgets\Pages\CreateBudget;
use App\Models\Budget;
use App\Models\Service;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use PHPUnit\Framework\Attributes\Test;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class CreateBudgetTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    private Service $service;

    protected function setUp(): void
    {
        parent::setUp();

        $permissions = [
            'ViewAny:Budget', 'View:Budget', 'Create:Budget', 'Update:Budget', 'Delete:Budget',
        ];

        foreach ($permissions as $perm) {
            Permission::findOrCreate($perm, 'web');
        }

        $adminRole = Role::findOrCreate('super_admin', 'web');
        $adminRole->syncPermissions($permissions);

        $this->service = Service::factory()->create();

        $this->admin = User::factory()->create();
        $this->admin->assignRole('super_admin');
    }

    #[Test]
    public function amount_entered_in_euros_is_stored_as_centimes(): void
    {
        $this->actingAs($this->admin);

        Livewire::test(CreateBudget::class)
            ->fillForm([
                'service_id' => $this->service->id,
                'year' => 2026,
                'amount' => 1500.50,
            ])
            ->call('create')
            ->assertHasNoFormErrors();

        $budget = Budget::query()->latest()->first();

        $this->assertNotNull($budget);
        $this->assertSame(150050, (int) $budget->amount);
        $this->assertSame(2026, (int) $budget->year);
    }

    #[Test]
    public function cannot_create_duplicate_budget_for_same_service_and_year(): void
    {
        $this->actingAs($this->admin);

        Budget::factory()->create([
            'service_id' => $this->service->id,
            'year' => 2026,
        ]);

        Livewire::test(CreateBudget::class)
            ->fillForm([
                'service_id' => $this->service->id,
                'year' => 2026,
                'amount' => 500.00,
            ])
            ->call('create')
            ->assertHasFormErrors(['year' => 'unique']);
    }

    #[Test]
    public function can_create_budget_for_different_service_same_year(): void
    {
        $this->actingAs($this->admin);

        Budget::factory()->create([
            'service_id' => $this->service->id,
            'year' => 2026,
        ]);

        $otherService = Service::factory()->create();

        Livewire::test(CreateBudget::class)
            ->fillForm([
                'service_id' => $otherService->id,
                'year' => 2026,
                'amount' => 800.00,
            ])
            ->call('create')
            ->assertHasNoFormErrors();

        $this->assertSame(2, Budget::where('year', 2026)->count());
    }

    #[Test]
    public function create_redirects_to_index(): void
    {
        $this->actingAs($this->admin);

        Livewire::test(CreateBudget::class)
            ->fillForm([
                'service_id' => $this->service->id,
                'year' => 2026,
                'amount' => 100.00,
            ])
            ->call('create')
            ->assertRedirect(BudgetResource::getUrl('index'));
    }
}
