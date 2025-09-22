<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InvoiceItem extends Model
{
    protected $fillable = [
        'invoice_id',
        'item_id',
        'product_id',
        'price_id',
        'currency',
        'name',
        'qty',
        'amount',
        'description',
        'tax_inclusive',
        'taxes'
    ];

    protected $casts = [
        'tax_inclusive' => 'boolean',
        'taxes' => 'array',
        'amount' => 'decimal:4'
    ];

    /**
     * Relación con Invoice
     */
    public function invoice(): BelongsTo
    {
        return $this->belongsTo(Invoice::class, 'invoice_id', 'invoice_id');
    }
}
