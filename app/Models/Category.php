<?php

namespace App\Models;

use Database\Factories\CategoryFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use OwenIt\Auditing\Contracts\Auditable;

class Category extends Model implements Auditable
{
    /** @use HasFactory<CategoryFactory> */
    use HasFactory;

    use \OwenIt\Auditing\Auditable;

    public function orderLines(): HasMany
    {
        // Order lines belonging to this category
        return $this->hasMany(OrderLine::class);
    }
}
