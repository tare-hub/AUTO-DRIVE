<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\RentalBooking;
use App\Models\SalesTransaction;
use App\Services\ReportService;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __construct(
        protected ReportService $reportService
    ) {}

    public function index(): View
    {
        $metrics = $this->reportService->getExecutiveMetrics();
        $inventoryByCategory = $this->reportService->getInventoryByCategory();
        $bookingDistribution = $this->reportService->getBookingStatusDistribution();

        $recentBookings = RentalBooking::with(['user', 'vehicle'])
            ->latest()
            ->take(5)
            ->get();

        $recentSales = SalesTransaction::with(['customer', 'vehicle'])
            ->latest()
            ->take(5)
            ->get();

        $recentActivities = ActivityLog::with('user')
            ->latest()
            ->take(8)
            ->get();

        return view('admin.dashboard', compact(
            'metrics',
            'inventoryByCategory',
            'bookingDistribution',
            'recentBookings',
            'recentSales',
            'recentActivities'
        ));
    }
}
