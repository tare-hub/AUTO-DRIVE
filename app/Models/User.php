<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    public const ROLE_ADMIN = 'admin';

    public const ROLE_RENTAL_MANAGER = 'rental_manager';

    public const ROLE_SALES_MANAGER = 'sales_manager';

    public const ROLE_SUPPORT = 'customer_support';

    public const ROLE_CUSTOMER = 'customer';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'phone',
        'address',
        'driver_license_number',
        'avatar',
        'status',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // Role checks
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isRentalManager(): bool
    {
        return $this->role === 'rental_manager' || $this->role === 'admin';
    }

    public function isSalesManager(): bool
    {
        return $this->role === 'sales_manager' || $this->role === 'admin';
    }

    public function isCustomerSupport(): bool
    {
        return $this->role === 'customer_support' || $this->role === 'admin';
    }

    public function isCustomer(): bool
    {
        return $this->role === 'customer';
    }

    public function hasRole(string|array $roles): bool
    {
        if (is_array($roles)) {
            return in_array($this->role, $roles, true);
        }

        return $this->role === $roles;
    }

    // Relationships
    public function rentalBookings(): HasMany
    {
        return $this->hasMany(RentalBooking::class, 'user_id');
    }

    public function purchaseRequests(): HasMany
    {
        return $this->hasMany(PurchaseRequest::class, 'user_id');
    }

    public function salesTransactions(): HasMany
    {
        return $this->hasMany(SalesTransaction::class, 'customer_id');
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class, 'user_id');
    }

    public function supportTickets(): HasMany
    {
        return $this->hasMany(SupportTicket::class, 'user_id');
    }

    public function assignedTickets(): HasMany
    {
        return $this->hasMany(SupportTicket::class, 'assigned_to');
    }

    public function activityLogs(): HasMany
    {
        return $this->hasMany(ActivityLog::class, 'user_id');
    }
}
