<?php

namespace Tests\Feature\Filament\Suppliers;

use App\Filament\App\Resources\Suppliers\Pages\CreateSupplier;
use App\Filament\App\Resources\Suppliers\Pages\ListSuppliers;
use App\Filament\App\Resources\Suppliers\SupplierResource;
use App\Models\Supplier;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use PHPUnit\Framework\Attributes\Test;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class SupplierResourceTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    private User $demandeur;

    protected function setUp(): void
    {
        parent::setUp();

        $supplierPermissions = [
            'ViewAny:Supplier', 'View:Supplier', 'Create:Supplier',
            'Update:Supplier', 'Delete:Supplier',
        ];

        $orderPermissions = [
            'ViewAny:Order', 'View:Order', 'Create:Order', 'Update:Order',
        ];

        foreach (array_merge($supplierPermissions, $orderPermissions) as $perm) {
            Permission::findOrCreate($perm, 'web');
        }

        $adminRole = Role::findOrCreate('super_admin', 'web');
        $adminRole->syncPermissions(array_merge($supplierPermissions, $orderPermissions));

        $demandeurRole = Role::findOrCreate('demandeurs', 'web');
        $demandeurRole->syncPermissions($orderPermissions);

        $this->admin = User::factory()->create();
        $this->admin->assignRole('super_admin');

        $this->demandeur = User::factory()->create();
        $this->demandeur->assignRole('demandeurs');
    }

    #[Test]
    public function admin_can_list_suppliers(): void
    {
        $this->actingAs($this->admin);

        $suppliers = Supplier::factory()->count(3)->create();

        Livewire::test(ListSuppliers::class)
            ->assertCanSeeTableRecords($suppliers);
    }

    #[Test]
    public function admin_can_create_supplier(): void
    {
        $this->actingAs($this->admin);

        Livewire::test(CreateSupplier::class)
            ->fillForm([
                'name' => 'LDLC',
                'email' => 'contact@ldlc.com',
                'phone' => '0472000000',
                'website_url' => 'https://www.ldlc.com',
                'siret' => '403 554 181 00015',
            ])
            ->call('create')
            ->assertHasNoFormErrors()
            ->assertRedirect(SupplierResource::getUrl('index'));

        $this->assertTrue(Supplier::where('name', 'LDLC')->exists());
    }

    #[Test]
    public function demandeur_cannot_access_supplier_list(): void
    {
        $this->actingAs($this->demandeur);

        $this->get(SupplierResource::getUrl('index'))
            ->assertForbidden();
    }

    #[Test]
    public function demandeur_cannot_create_supplier(): void
    {
        $this->actingAs($this->demandeur);

        $this->get(SupplierResource::getUrl('create'))
            ->assertForbidden();
    }
}
