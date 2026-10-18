<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class RentalBooking extends Model
{
    use HasFactory;

    protected $fillable = [
        'booking_number',
        'user_id',
        'vehicle_id',
        'start_date',
        'end_date',
        'total_days',
        'daily_rate',
        'subtotal',
        'insurance_fee',
        'tax_amount',
        'late_fee',
        'damage_fee',
        'total_amount',
        'paid_amount',
        'status',
        'pickup_location',
        'return_location',
        'notes',
        'cancellation_reason',
        'approved_by',
        'approved_at',
        'cancelled_at',
    ];

    protected function casts(): array
    {
        return [
            'start_date' => 'date',
            'end_date' => 'date',
            'total_days' => 'integer',
            'daily_rate' => 'decimal:2',
            'subtotal' => 'decimal:2',
            'insurance_fee' => 'decimal:2',
            'tax_amount' => 'decimal:2',
            'late_fee' => 'decimal:2',
            'damage_fee' => 'decimal:2',
            'total_amount' => 'decimal:2',
            'paid_amount' => 'decimal:2',
            'approved_at' => 'datetime',
            'cancelled_at' => 'datetime',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (RentalBooking $booking) {
            if (empty($booking->booking_number)) {
                $booking->booking_number = 'BK-'.date('Ymd').'-'.strtoupper(substr(uniqid(), -5));
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

    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function inspections(): HasMany
    {
        return $this->hasMany(RentalInspection::class, 'rental_booking_id');
    }

    public function payments(): MorphMany
    {
        return $this->morphMany(Payment::class, 'payable');
    }

    public function isCancellableByCustomer(): bool
    {
        if (! in_array($this->status, ['pending', 'confirmed'])) {
            return false;
        }

        // Cancellable if start date is strictly in the future or pending
        return Carbon::parse($this->start_date)->isFuture() || $this->status === 'pending';
    }

    public function getBalanceDueAttribute(): float
    {
        return max(0, (float) $this->total_amount - (float) $this->paid_amount);
    }

    public function getPaymentStatusAttribute(): string
    {
        if ($this->paid_amount >= $this->total_amount && $this->total_amount > 0) {
            return 'paid';
        }
        if ($this->paid_amount > 0) {
            return 'partially_paid';
        }

        return 'unpaid';
    }
}
