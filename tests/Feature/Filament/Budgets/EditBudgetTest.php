<?php

namespace Tests\Feature\Filament\Budgets;

use App\Filament\App\Resources\Budgets\BudgetResource;
use App\Filament\App\Resources\Budgets\Pages\EditBudget;
use App\Models\Budget;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use PHPUnit\Framework\Attributes\Test;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class EditBudgetTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

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

        $this->admin = User::factory()->create();
        $this->admin->assignRole('super_admin');
    }

    #[Test]
    public function edit_redirects_to_index_after_save(): void
    {
        $this->actingAs($this->admin);

        $budget = Budget::factory()->create();

        Livewire::test(EditBudget::class, ['record' => $budget->id])
            ->fillForm([
                'amount' => 2000.00,
            ])
            ->call('save')
            ->assertHasNoFormErrors()
            ->assertRedirect(BudgetResource::getUrl('index'));
    }
}
