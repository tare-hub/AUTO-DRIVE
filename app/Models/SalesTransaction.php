<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class SalesTransaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'transaction_number',
        'purchase_request_id',
        'vehicle_id',
        'customer_id',
        'sales_manager_id',
        'sale_price',
        'tax_amount',
        'fees_amount',
        'total_amount',
        'paid_amount',
        'payment_status',
        'sale_date',
        'invoice_number',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'sale_price' => 'decimal:2',
            'tax_amount' => 'decimal:2',
            'fees_amount' => 'decimal:2',
            'total_amount' => 'decimal:2',
            'paid_amount' => 'decimal:2',
            'sale_date' => 'date',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (SalesTransaction $tx) {
            if (empty($tx->transaction_number)) {
                $tx->transaction_number = 'TX-'.date('Ymd').'-'.strtoupper(substr(uniqid(), -5));
            }
            if (empty($tx->invoice_number)) {
                $tx->invoice_number = 'INV-'.date('Y').'-'.strtoupper(substr(uniqid(), -6));
            }
        });
    }

    public function purchaseRequest(): BelongsTo
    {
        return $this->belongsTo(PurchaseRequest::class, 'purchase_request_id');
    }

    public function vehicle(): BelongsTo
    {
        return $this->belongsTo(Vehicle::class, 'vehicle_id');
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'customer_id');
    }

    public function salesManager(): BelongsTo
    {
        return $this->belongsTo(User::class, 'sales_manager_id');
    }

    public function payments(): MorphMany
    {
        return $this->morphMany(Payment::class, 'payable');
    }

    public function getBalanceDueAttribute(): float
    {
        return max(0, (float) $this->total_amount - (float) $this->paid_amount);
    }
}
