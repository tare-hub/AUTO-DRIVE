<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\RentalBooking;
use App\Models\SalesTransaction;
use App\Services\ReportService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\View\View;

class ReportController extends Controller
{
    public function __construct(
        protected ReportService $reportService
    ) {}

    public function index(Request $request): View
    {
        $metrics = $this->reportService->getExecutiveMetrics();
        $inventoryByCategory = $this->reportService->getInventoryByCategory();
        $bookingDistribution = $this->reportService->getBookingStatusDistribution();

        $rentalBookings = RentalBooking::with(['user', 'vehicle'])
            ->latest()
            ->paginate(10, ['*'], 'rentals_page');

        $salesTransactions = SalesTransaction::with(['customer', 'vehicle'])
            ->latest()
            ->paginate(10, ['*'], 'sales_page');

        return view('admin.reports.index', compact(
            'metrics',
            'inventoryByCategory',
            'bookingDistribution',
            'rentalBookings',
            'salesTransactions'
        ));
    }

    public function export(Request $request): Response
    {
        $type = $request->input('type', 'rentals');
        $csvContent = $this->reportService->exportCsv($type);
        $filename = "autodrive-{$type}-report-".date('Y-m-d').'.csv';

        return response($csvContent, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ]);
    }
}
