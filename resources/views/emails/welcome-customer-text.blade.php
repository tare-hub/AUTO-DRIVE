Welcome to AutoDrive, {{ $user->name }}!
==================================================

Thank you for registering with AutoDrive. Your customer account is active and ready to use.

ACCOUNT DETAILS:
- Full Name: {{ $user->name }}
- Email Address: {{ $user->email }}
- Phone: {{ $user->phone }}
- Driver's License: {{ $user->driver_license_number ?? 'Not provided yet' }}
- Account Status: Active Customer

WHAT YOU CAN DO NOW:
1. Browse & Reserve Rentals: {{ route('vehicles.rent') }}
2. Explore Vehicles For Sale: {{ route('vehicles.buy') }}
3. Go to Your Dashboard: {{ route('customer.dashboard') }}
4. Customer Support: {{ route('customer.tickets.index') }}

If you did not create this account on AutoDrive, please contact our support team immediately at {{ route('contact') }}.

Best regards,
The AutoDrive Team
{{ config('app.url') }}
