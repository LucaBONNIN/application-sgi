<?php

namespace App\Models;

use Database\Factories\SupplierFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use OwenIt\Auditing\Contracts\Auditable;

class Supplier extends Model implements Auditable
{
    /** @use HasFactory<SupplierFactory> */
    use HasFactory;

    use \OwenIt\Auditing\Auditable;

    protected $fillable = [
        'name',
        'website_url',
        'email',
        'phone',
        'address',
        'siret',
    ];

    public function orders(): HasMany
    {
        // Orders made to this supplier
        return $this->hasMany(Order::class);
    }
}
