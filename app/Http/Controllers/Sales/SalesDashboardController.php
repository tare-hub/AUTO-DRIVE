<?php

namespace App\Http\Controllers\Sales;

use App\Http\Controllers\Controller;
use App\Models\PurchaseRequest;
use App\Models\SalesTransaction;
use App\Models\Vehicle;
use App\Services\SalesService;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class SalesDashboardController extends Controller
{
    public function __construct(
        protected SalesService $salesService
    ) {}

    public function index(Request $request): View
    {
        $status = $request->input('status', 'all');

        $requestsQuery = PurchaseRequest::with(['user', 'vehicle', 'salesTransaction']);
        if ($status !== 'all') {
            $requestsQuery->where('status', $status);
        }

        $purchaseRequests = $requestsQuery->latest()->paginate(8, ['*'], 'requests_page');

        $salesTransactions = SalesTransaction::with(['customer', 'vehicle', 'payments'])
            ->latest()
            ->paginate(8, ['*'], 'transactions_page');

        $stats = [
            'pending_requests' => PurchaseRequest::where('status', 'pending')->count(),
            'under_review' => PurchaseRequest::where('status', 'under_review')->count(),
            'approved_requests' => PurchaseRequest::where('status', 'approved')->count(),
            'completed_sales' => SalesTransaction::count(),
            'vehicles_for_sale' => Vehicle::whereIn('listing_type', ['sale', 'both'])->where('status', 'available')->count(),
            'vehicles_reserved' => Vehicle::where('status', 'reserved')->count(),
            'vehicles_sold' => Vehicle::where('status', 'sold')->count(),
            'total_sales_revenue' => (float) SalesTransaction::sum('total_amount'),
        ];

        return view('sales.dashboard', compact('purchaseRequests', 'salesTransactions', 'stats', 'status'));
    }

    public function review(PurchaseRequest $request, Request $httpRequest): RedirectResponse
    {
        $validated = $httpRequest->validate([
            'status' => ['required', 'in:under_review,approved,rejected'],
            'staff_notes' => ['nullable', 'string', 'max:1000'],
        ]);

        try {
            $this->salesService->reviewPurchaseRequest($request, Auth::user(), $validated['status'], $validated['staff_notes'] ?? null);

            return back()->with('success', "Purchase request #{$request->request_number} marked as {$validated['status']}.");
        } catch (Exception $e) {
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function showFinalize(PurchaseRequest $request): View
    {
        $request->load(['vehicle', 'user']);

        return view('sales.finalize', compact('request'));
    }

    public function finalize(PurchaseRequest $request, Request $httpRequest): RedirectResponse
    {
        $validated = $httpRequest->validate([
            'sale_price' => ['required', 'numeric', 'min:1'],
            'paid_amount' => ['required', 'numeric', 'min:0'],
            'payment_method' => ['required', 'in:credit_card,bank_transfer,cash,simulated_card'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ]);

        try {
            $transaction = $this->salesService->finalizeSale($request, Auth::user(), $validated);

            return redirect()->route('sales.dashboard')
                ->with('success', "Sale completed! Transaction #{$transaction->transaction_number} created and vehicle is permanently marked as SOLD.");
        } catch (Exception $e) {
            return back()->withInput()->withErrors(['error' => $e->getMessage()]);
        }
    }
}
