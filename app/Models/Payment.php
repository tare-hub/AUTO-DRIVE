<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'payment_number',
        'payable_type',
        'payable_id',
        'user_id',
        'amount',
        'payment_method',
        'payment_status',
        'transaction_reference',
        'notes',
        'paid_at',
        'verified_by',
        'verified_at',
    ];

    protected function casts(): array
    {
        return [
            'amount' => 'decimal:2',
            'paid_at' => 'datetime',
            'verified_at' => 'datetime',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (Payment $payment) {
            if (empty($payment->payment_number)) {
                $payment->payment_number = 'PAY-'.date('Ymd').'-'.strtoupper(substr(uniqid(), -5));
            }
            if (empty($payment->transaction_reference)) {
                $payment->transaction_reference = 'REF-'.strtoupper(bin2hex(random_bytes(6)));
            }
        });
    }

    public function payable(): MorphTo
    {
        return $this->morphTo();
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function verifier(): BelongsTo
    {
        return $this->belongsTo(User::class, 'verified_by');
    }
}
