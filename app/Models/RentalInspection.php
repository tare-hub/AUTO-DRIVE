<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RentalInspection extends Model
{
    use HasFactory;

    protected $fillable = [
        'rental_booking_id',
        'vehicle_id',
        'inspector_id',
        'type',
        'odometer_reading',
        'fuel_level',
        'exterior_condition',
        'interior_condition',
        'damages_notes',
        'damage_charge',
        'late_days',
        'late_fee',
        'inspection_date',
        'photos',
    ];

    protected function casts(): array
    {
        return [
            'odometer_reading' => 'integer',
            'damage_charge' => 'decimal:2',
            'late_days' => 'integer',
            'late_fee' => 'decimal:2',
            'inspection_date' => 'datetime',
            'photos' => 'array',
        ];
    }

    public function booking(): BelongsTo
    {
        return $this->belongsTo(RentalBooking::class, 'rental_booking_id');
    }

    public function vehicle(): BelongsTo
    {
        return $this->belongsTo(Vehicle::class, 'vehicle_id');
    }

    public function inspector(): BelongsTo
    {
        return $this->belongsTo(User::class, 'inspector_id');
    }
}
