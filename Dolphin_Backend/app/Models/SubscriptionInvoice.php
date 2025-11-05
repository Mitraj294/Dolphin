<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubscriptionInvoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'subscription_id',
        'stripe_invoice_id',
        'amount_due',
        'amount_paid',
        'currency',
        'status',
        'due_date',
        'paid_at',
        'hosted_invoice_url',
    ];

    protected $casts = [
        'due_date' => 'date',
        'paid_at' => 'datetime',
    ];

    /**
     * Get the subscription that owns the invoice.
     */
    public function subscription()
    {
        return $this->belongsTo(Subscription::class);
    }

    /**
     * Check if invoice is paid.
     */
    public function isPaid(): bool
    {
        return $this->status === 'paid';
    }

    /**
     * Check if invoice is open.
     */
    public function isOpen(): bool
    {
        return $this->status === 'open';
    }
}
