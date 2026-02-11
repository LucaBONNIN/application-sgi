<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class Category extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

    /** @use HasFactory<\Database\Factories\CategoryFactory> */
    use HasFactory;

    public function orderLines(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        // Order lines belonging to this category
        return $this->hasMany(OrderLine::class);
    }
}
