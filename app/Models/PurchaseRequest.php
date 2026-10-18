<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class PurchaseRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'request_number',
        'user_id',
        'vehicle_id',
        'offering_price',
        'status',
        'customer_notes',
        'staff_notes',
        'reviewed_by',
        'reviewed_at',
    ];

    protected function casts(): array
    {
        return [
            'offering_price' => 'decimal:2',
            'reviewed_at' => 'datetime',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (PurchaseRequest $request) {
            if (empty($request->request_number)) {
                $request->request_number = 'PR-'.date('Ymd').'-'.strtoupper(substr(uniqid(), -5));
            }
        });
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function vehicle(): BelongsTo
    {
        return $this->belongsTo(Vehicle::class, 'vehicle_id');
    }

    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    public function salesTransaction(): HasOne
    {
        return $this->hasOne(SalesTransaction::class, 'purchase_request_id');
    }
}
