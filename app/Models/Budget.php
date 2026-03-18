<?php

namespace App\Models;

use Database\Factories\BudgetFactory;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use OwenIt\Auditing\Contracts\Auditable;

class Budget extends Model implements Auditable
{
    /** @use HasFactory<BudgetFactory> */
    use HasFactory;

    use \OwenIt\Auditing\Auditable;

    protected function budgetName(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->service?->name.' '.$this->year,
        );
    }

    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class);
    }

    public function orders(): HasMany
    {
        // Orders funded by this budget
        return $this->hasMany(Order::class);
    }
}
