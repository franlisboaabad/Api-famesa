<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Contact extends Model
{
    protected $fillable = [
        'contact_id',
        'first_name',
        'last_name',
        'full_name',
        'email',
        'phone',
        'tags',
        'address1',
        'city',
        'state',
        'country',
        'timezone',
        'date_created',
        'contact_source',
        'full_address',
        'contact_type',
        'location_id',
        'invoice_id',
        'workflow_id',
        'custom_data',
        'attribution_source'
    ];

    protected $casts = [
        'date_created' => 'datetime',
        'custom_data' => 'array',
        'attribution_source' => 'array'
    ];

    /**
     * Relación con Location
     */
    public function location(): BelongsTo
    {
        return $this->belongsTo(Location::class, 'location_id', 'location_id');
    }

    /**
     * Relación con Invoice
     */
    public function invoice(): BelongsTo
    {
        return $this->belongsTo(Invoice::class, 'invoice_id', 'invoice_id');
    }

    /**
     * Relación con Workflow
     */
    public function workflow(): BelongsTo
    {
        return $this->belongsTo(Workflow::class, 'workflow_id', 'workflow_id');
    }
}
