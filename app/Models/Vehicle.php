<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Vehicle extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'brand',
        'model',
        'year',
        'vin',
        'license_plate',
        'color',
        'transmission',
        'fuel_type',
        'seating_capacity',
        'mileage',
        'engine_capacity',
        'daily_rate',
        'selling_price',
        'listing_type',
        'status',
        'condition',
        'description',
        'features',
        'main_image',
        'is_featured',
    ];

    protected function casts(): array
    {
        return [
            'year' => 'integer',
            'seating_capacity' => 'integer',
            'mileage' => 'integer',
            'daily_rate' => 'decimal:2',
            'selling_price' => 'decimal:2',
            'features' => 'array',
            'is_featured' => 'boolean',
        ];
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(VehicleCategory::class, 'category_id');
    }

    public function images(): HasMany
    {
        return $this->hasMany(VehicleImage::class, 'vehicle_id');
    }

    public function rentalBookings(): HasMany
    {
        return $this->hasMany(RentalBooking::class, 'vehicle_id');
    }

    public function purchaseRequests(): HasMany
    {
        return $this->hasMany(PurchaseRequest::class, 'vehicle_id');
    }

    public function salesTransactions(): HasMany
    {
        return $this->hasMany(SalesTransaction::class, 'vehicle_id');
    }

    public function inspections(): HasMany
    {
        return $this->hasMany(RentalInspection::class, 'vehicle_id');
    }

    // Full display name accessor
    public function getFullNameAttribute(): string
    {
        return "{$this->year} {$this->brand} {$this->model}";
    }

    // Scopes
    public function scopeAvailableForRental(Builder $query): Builder
    {
        return $query->whereIn('listing_type', ['rental', 'both'])
            ->where('status', 'available');
    }

    public function scopeAvailableForSale(Builder $query): Builder
    {
        return $query->whereIn('listing_type', ['sale', 'both'])
            ->whereIn('status', ['available', 'reserved']);
    }

    public function scopeFeatured(Builder $query): Builder
    {
        return $query->where('is_featured', true);
    }

    public function isAvailableForRentalOnDates(string $startDate, string $endDate, ?int $excludeBookingId = null): bool
    {
        if ($this->status !== 'available' || ! in_array($this->listing_type, ['rental', 'both'])) {
            return false;
        }

        $query = $this->rentalBookings()
            ->whereIn('status', ['pending', 'confirmed', 'active'])
            ->where(function ($q) use ($startDate, $endDate) {
                $q->whereBetween('start_date', [$startDate, $endDate])
                    ->orWhereBetween('end_date', [$startDate, $endDate])
                    ->orWhere(function ($sub) use ($startDate, $endDate) {
                        $sub->where('start_date', '<=', $startDate)
                            ->where('end_date', '>=', $endDate);
                    });
            });

        if ($excludeBookingId) {
            $query->where('id', '!=', $excludeBookingId);
        }

        return $query->count() === 0;
    }
}
