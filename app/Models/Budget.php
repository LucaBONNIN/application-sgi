<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Budget extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

    /** @use HasFactory<\Database\Factories\BudgetFactory> */
    use HasFactory;

    protected function budgetName(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->service?->name . ' ' . $this->year,
        );
    }

    public function service(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Service::class);
    }

    public function orders(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        // Orders funded by this budget
        return $this->hasMany(Order::class);
    }
}
