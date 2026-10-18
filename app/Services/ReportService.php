<?php

namespace App\Services;

use App\Models\Payment;
use App\Models\PurchaseRequest;
use App\Models\RentalBooking;
use App\Models\SalesTransaction;
use App\Models\User;
use App\Models\Vehicle;
use App\Models\VehicleCategory;
use Illuminate\Support\Facades\DB;

class ReportService
{
    /**
     * Get aggregate executive business metrics.
     */
    public function getExecutiveMetrics(): array
    {
        $totalVehicles = Vehicle::count();
        $availableRental = Vehicle::availableForRental()->count();
        $currentlyRented = Vehicle::where('status', 'rented')->count();
        $underMaintenance = Vehicle::where('status', 'maintenance')->count();

        $availableSale = Vehicle::whereIn('listing_type', ['sale', 'both'])->where('status', 'available')->count();
        $reservedVehicles = Vehicle::where('status', 'reserved')->count();
        $soldVehicles = Vehicle::where('status', 'sold')->count();

        $pendingRentals = RentalBooking::where('status', 'pending')->count();
        $activeRentals = RentalBooking::where('status', 'active')->count();
        $completedRentals = RentalBooking::where('status', 'completed')->count();

        $pendingPurchases = PurchaseRequest::where('status', 'pending')->count();

        // Revenue calculations
        $totalRentalRevenue = (float) RentalBooking::whereIn('status', ['confirmed', 'active', 'completed'])->sum('total_amount');
        $totalSalesRevenue = (float) SalesTransaction::sum('total_amount');

        $verifiedRentalPayments = (float) Payment::where('payable_type', RentalBooking::class)->where('payment_status', 'paid')->sum('amount');
        $verifiedSalesPayments = (float) Payment::where('payable_type', SalesTransaction::class)->where('payment_status', 'paid')->sum('amount');
        $totalVerifiedRevenue = $verifiedRentalPayments + $verifiedSalesPayments;

        $totalCustomers = User::where('role', 'customer')->count();
        $totalStaff = User::where('role', '!=', 'customer')->count();

        return [
            'total_vehicles' => $totalVehicles,
            'available_rental' => $availableRental,
            'currently_rented' => $currentlyRented,
            'under_maintenance' => $underMaintenance,
            'available_sale' => $availableSale,
            'reserved_vehicles' => $reservedVehicles,
            'sold_vehicles' => $soldVehicles,
            'pending_rentals' => $pendingRentals,
            'active_rentals' => $activeRentals,
            'completed_rentals' => $completedRentals,
            'pending_purchases' => $pendingPurchases,
            'total_rental_revenue' => $totalRentalRevenue,
            'total_sales_revenue' => $totalSalesRevenue,
            'verified_rental_payments' => $verifiedRentalPayments,
            'verified_sales_payments' => $verifiedSalesPayments,
            'total_verified_revenue' => $totalVerifiedRevenue,
            'total_customers' => $totalCustomers,
            'total_staff' => $totalStaff,
        ];
    }

    /**
     * Get inventory distribution by category.
     */
    public function getInventoryByCategory(): array
    {
        return VehicleCategory::withCount('vehicles')->get()->map(function ($cat) {
            return [
                'name' => $cat->name,
                'count' => $cat->vehicles_count,
            ];
        })->toArray();
    }

    /**
     * Get booking status distribution.
     */
    public function getBookingStatusDistribution(): array
    {
        return RentalBooking::select('status', DB::raw('count(*) as count'))
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();
    }

    /**
     * Generate CSV export for rentals, sales, or inventory.
     */
    public function exportCsv(string $type): string
    {
        $output = fopen('php://temp', 'r+');

        if ($type === 'rentals') {
            fputcsv($output, ['Booking #', 'Customer', 'Vehicle', 'Start Date', 'End Date', 'Days', 'Daily Rate', 'Total Amount', 'Paid Amount', 'Status']);
            $bookings = RentalBooking::with(['user', 'vehicle'])->latest()->get();
            foreach ($bookings as $b) {
                fputcsv($output, [
                    $b->booking_number,
                    $b->user?->name,
                    $b->vehicle?->full_name,
                    $b->start_date->format('Y-m-d'),
                    $b->end_date->format('Y-m-d'),
                    $b->total_days,
                    $b->daily_rate,
                    $b->total_amount,
                    $b->paid_amount,
                    strtoupper($b->status),
                ]);
            }
        } elseif ($type === 'sales') {
            fputcsv($output, ['Transaction #', 'Invoice #', 'Customer', 'Vehicle', 'Sale Date', 'Sale Price', 'Tax', 'Fees', 'Total Amount', 'Paid Amount', 'Payment Status']);
            $sales = SalesTransaction::with(['customer', 'vehicle'])->latest()->get();
            foreach ($sales as $s) {
                fputcsv($output, [
                    $s->transaction_number,
                    $s->invoice_number,
                    $s->customer?->name,
                    $s->vehicle?->full_name,
                    $s->sale_date->format('Y-m-d'),
                    $s->sale_price,
                    $s->tax_amount,
                    $s->fees_amount,
                    $s->total_amount,
                    $s->paid_amount,
                    strtoupper($s->payment_status),
                ]);
            }
        } else {
            fputcsv($output, ['ID', 'Brand', 'Model', 'Year', 'VIN', 'Plate', 'Category', 'Listing Type', 'Status', 'Daily Rate', 'Selling Price', 'Mileage']);
            $vehicles = Vehicle::with('category')->latest()->get();
            foreach ($vehicles as $v) {
                fputcsv($output, [
                    $v->id,
                    $v->brand,
                    $v->model,
                    $v->year,
                    $v->vin,
                    $v->license_plate,
                    $v->category?->name,
                    $v->listing_type,
                    $v->status,
                    $v->daily_rate,
                    $v->selling_price,
                    $v->mileage,
                ]);
            }
        }

        rewind($output);
        $csv = stream_get_contents($output);
        fclose($output);

        return $csv;
    }
}
