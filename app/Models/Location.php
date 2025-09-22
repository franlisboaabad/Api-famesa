<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Location extends Model
{
    protected $fillable = [
        'location_id',
        'name',
        'address',
        'city',
        'state',
        'country',
        'postal_code',
        'full_address'
    ];

    /**
     * Relación con Contacts
     */
    public function contacts(): HasMany
    {
        return $this->hasMany(Contact::class, 'location_id', 'location_id');
    }
}
