<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\RentalBooking;
use App\Models\SalesTransaction;
use App\Services\PaymentService;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class PaymentController extends Controller
{
    public function __construct(
        protected PaymentService $paymentService
    ) {}

    public function payBooking(RentalBooking $booking, Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'amount' => ['required', 'numeric', 'min:1', 'max:'.max(1, $booking->balance_due)],
            'payment_method' => ['required', 'in:simulated_card,credit_card,bank_transfer,cash'],
            'notes' => ['nullable', 'string', 'max:500'],
        ]);

        try {
            $staffVerifier = (Auth::user()->isAdmin() || Auth::user()->isRentalManager()) ? Auth::user() : null;

            $payment = $this->paymentService->recordPayment(
                $booking,
                Auth::user(),
                (float) $validated['amount'],
                $validated['payment_method'],
                $validated['notes'] ?? 'Rental payment via '.$validated['payment_method'],
                $staffVerifier
            );

            return back()->with('success', "Payment of \${$payment->amount} recorded successfully (Ref: {$payment->transaction_reference}).");
        } catch (Exception $e) {
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function payTransaction(SalesTransaction $transaction, Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'amount' => ['required', 'numeric', 'min:1', 'max:'.max(1, $transaction->balance_due)],
            'payment_method' => ['required', 'in:simulated_card,credit_card,bank_transfer,cash'],
            'notes' => ['nullable', 'string', 'max:500'],
        ]);

        try {
            $staffVerifier = (Auth::user()->isAdmin() || Auth::user()->isSalesManager()) ? Auth::user() : null;

            $payment = $this->paymentService->recordPayment(
                $transaction,
                Auth::user(),
                (float) $validated['amount'],
                $validated['payment_method'],
                $validated['notes'] ?? 'Vehicle purchase payment via '.$validated['payment_method'],
                $staffVerifier
            );

            return back()->with('success', "Payment of \${$payment->amount} recorded successfully (Ref: {$payment->transaction_reference}).");
        } catch (Exception $e) {
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function showReceipt(Payment $payment): View
    {
        // Check authorization: owner or staff
        $user = Auth::user();
        if ($payment->user_id !== $user->id && ! $user->hasRole(['admin', 'rental_manager', 'sales_manager', 'customer_support'])) {
            abort(403);
        }

        $payment->load(['payable', 'user', 'verifier']);

        return view('payments.receipt', compact('payment'));
    }
}
