<?php

namespace App\Models;

use Database\Factories\CategoryFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;
use OwenIt\Auditing\Contracts\Auditable;

class Category extends Model implements Auditable
{
    /** @use HasFactory<CategoryFactory> */
    use HasFactory;

    use \OwenIt\Auditing\Auditable;

    protected $fillable = [
        'name',
        'slug',
    ];

    /**
     * Generate a unique slug from the given name.
     *
     * Appends an incrementing suffix (-2, -3, …) if the base slug already exists.
     */
    public static function generateUniqueSlug(string $name): string
    {
        $slug = Str::slug($name);

        if ($slug === '') {
            return '';
        }

        $originalSlug = $slug;
        $counter = 2;

        while (static::where('slug', $slug)->exists()) {
            $slug = $originalSlug.'-'.$counter;
            $counter++;
        }

        return $slug;
    }

    public function orderLines(): HasMany
    {
        // Order lines belonging to this category
        return $this->hasMany(OrderLine::class);
    }
}
