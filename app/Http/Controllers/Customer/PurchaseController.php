<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Vehicle;
use App\Services\SalesService;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PurchaseController extends Controller
{
    public function __construct(
        protected SalesService $salesService
    ) {}

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'vehicle_id' => ['required', 'exists:vehicles,id'],
            'offering_price' => ['nullable', 'numeric', 'min:1'],
            'customer_notes' => ['nullable', 'string', 'max:1000'],
        ]);

        $vehicle = Vehicle::findOrFail($validated['vehicle_id']);

        try {
            $purchaseRequest = $this->salesService->createPurchaseRequest(Auth::user(), $vehicle, $validated);

            return redirect()->route('customer.purchases')
                ->with('success', "Purchase request #{$purchaseRequest->request_number} recorded! A sales representative will contact you with purchase documentation.");
        } catch (Exception $e) {
            return back()->withInput()->withErrors(['purchase_error' => $e->getMessage()]);
        }
    }
}
