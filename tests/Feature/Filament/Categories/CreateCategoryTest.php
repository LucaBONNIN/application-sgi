<?php

namespace Tests\Feature\Filament\Categories;

use App\Filament\App\Resources\Categories\CategoryResource;
use App\Filament\App\Resources\Categories\Pages\CreateCategory;
use App\Models\Category;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use PHPUnit\Framework\Attributes\Test;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class CreateCategoryTest extends TestCase
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
    public function can_create_category_with_auto_slug(): void
    {
        $this->actingAs($this->admin);

        Livewire::test(CreateCategory::class)
            ->fillForm([
                'name' => 'Fournitures de bureau',
                'slug' => 'fournitures-de-bureau',
            ])
            ->call('create')
            ->assertHasNoFormErrors();

        $category = Category::query()->latest()->first();

        $this->assertNotNull($category);
        $this->assertSame('Fournitures de bureau', $category->name);
        $this->assertSame('fournitures-de-bureau', $category->slug);
    }

    #[Test]
    public function cannot_create_category_with_duplicate_slug(): void
    {
        $this->actingAs($this->admin);

        Category::factory()->create(['slug' => 'informatique']);

        Livewire::test(CreateCategory::class)
            ->fillForm([
                'name' => 'Informatique',
                'slug' => 'informatique',
            ])
            ->call('create')
            ->assertHasFormErrors(['slug' => 'unique']);
    }

    #[Test]
    public function slug_is_required(): void
    {
        $this->actingAs($this->admin);

        Livewire::test(CreateCategory::class)
            ->fillForm([
                'name' => 'Test',
                'slug' => '',
            ])
            ->call('create')
            ->assertHasFormErrors(['slug' => 'required']);
    }

    #[Test]
    public function generate_unique_slug_appends_suffix_on_collision(): void
    {
        Category::factory()->create(['slug' => 'informatique']);

        $this->assertSame('informatique-2', Category::generateUniqueSlug('Informatique'));
    }

    #[Test]
    public function generate_unique_slug_increments_suffix_on_multiple_collisions(): void
    {
        Category::factory()->create(['slug' => 'informatique']);
        Category::factory()->create(['slug' => 'informatique-2']);

        $this->assertSame('informatique-3', Category::generateUniqueSlug('Informatique'));
    }

    #[Test]
    public function generate_unique_slug_returns_base_slug_when_no_collision(): void
    {
        $this->assertSame('fournitures', Category::generateUniqueSlug('Fournitures'));
    }

    #[Test]
    public function create_redirects_to_index(): void
    {
        $this->actingAs($this->admin);

        Livewire::test(CreateCategory::class)
            ->fillForm([
                'name' => 'Test',
                'slug' => 'test',
            ])
            ->call('create')
            ->assertRedirect(CategoryResource::getUrl('index'));
    }
}
