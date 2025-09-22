<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Invoice extends Model
{
    protected $fillable = [
        'invoice_id',
        'alt_id',
        'alt_type',
        'company_id',
        'name',
        'invoice_number',
        'currency',
        'status',
        'amount_paid',
        'total',
        'invoice_total',
        'amount_due',
        'title',
        'issue_date',
        'due_date',
        'terms_notes',
        'live_mode',
        'deleted',
        'business_details',
        'contact_details',
        'discount',
        'payment_methods',
        'configuration',
        'late_fees_configuration',
        'reminders_configuration',
        'meta',
        'total_summary',
        'invoice_url',
        'sender_name',
        'sender_email',
        'sent_at',
        'sent_by',
        'sent_from',
        'sent_to',
        'updated_by'
    ];

    protected $casts = [
        'issue_date' => 'datetime',
        'due_date' => 'datetime',
        'sent_at' => 'datetime',
        'live_mode' => 'boolean',
        'deleted' => 'boolean',
        'business_details' => 'array',
        'contact_details' => 'array',
        'discount' => 'array',
        'payment_methods' => 'array',
        'configuration' => 'array',
        'late_fees_configuration' => 'array',
        'reminders_configuration' => 'array',
        'meta' => 'array',
        'total_summary' => 'array',
        'sent_from' => 'array',
        'sent_to' => 'array',
        'amount_paid' => 'decimal:4',
        'total' => 'decimal:4',
        'invoice_total' => 'decimal:4',
        'amount_due' => 'decimal:4'
    ];

    /**
     * Relación con Contacts
     */
    public function contacts(): HasMany
    {
        return $this->hasMany(Contact::class, 'invoice_id', 'invoice_id');
    }

    /**
     * Relación con InvoiceItems
     */
    public function invoiceItems(): HasMany
    {
        return $this->hasMany(InvoiceItem::class, 'invoice_id', 'invoice_id');
    }
}
