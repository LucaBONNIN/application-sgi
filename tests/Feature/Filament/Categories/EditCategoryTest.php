<?php

namespace Tests\Feature\Filament\Categories;

use App\Filament\App\Resources\Categories\CategoryResource;
use App\Filament\App\Resources\Categories\Pages\EditCategory;
use App\Models\Category;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use PHPUnit\Framework\Attributes\Test;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class EditCategoryTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $permissions = [
            'ViewAny:Category', 'View:Category', 'Create:Category', 'Update:Category', 'Delete:Category',
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

        $category = Category::factory()->create();

        Livewire::test(EditCategory::class, ['record' => $category->id])
            ->fillForm([
                'name' => 'Updated Name',
                'slug' => 'updated-name',
            ])
            ->call('save')
            ->assertHasNoFormErrors()
            ->assertRedirect(CategoryResource::getUrl('index'));
    }
}
