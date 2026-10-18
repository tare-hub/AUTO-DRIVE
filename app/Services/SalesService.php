<?php

namespace App\Services;

use App\Models\ActivityLog;
use App\Models\Payment;
use App\Models\PurchaseRequest;
use App\Models\SalesTransaction;
use App\Models\User;
use App\Models\Vehicle;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;

class SalesService
{
    /**
     * Submit a customer purchase request.
     */
    public function createPurchaseRequest(User $customer, Vehicle $vehicle, array $data): PurchaseRequest
    {
        return DB::transaction(function () use ($customer, $vehicle, $data) {
            $lockedVehicle = Vehicle::where('id', $vehicle->id)->lockForUpdate()->firstOrFail();

            if (! in_array($lockedVehicle->listing_type, ['sale', 'both'])) {
                throw new InvalidArgumentException('This vehicle is not listed for purchase.');
            }

            if ($lockedVehicle->status === 'sold') {
                throw new InvalidArgumentException('This vehicle has already been sold and is no longer available.');
            }

            $offeringPrice = isset($data['offering_price']) && (float) $data['offering_price'] > 0
                ? (float) $data['offering_price']
                : (float) $lockedVehicle->selling_price;

            $request = PurchaseRequest::create([
                'user_id' => $customer->id,
                'vehicle_id' => $lockedVehicle->id,
                'offering_price' => $offeringPrice,
                'status' => 'pending',
                'customer_notes' => $data['customer_notes'] ?? null,
            ]);

            ActivityLog::log('PURCHASE_REQUEST_CREATED', "Purchase request {$request->request_number} submitted for {$lockedVehicle->full_name}.", $request, $customer);

            return $request;
        });
    }

    /**
     * Review purchase request by Sales Manager, optionally reserving the vehicle.
     */
    public function reviewPurchaseRequest(PurchaseRequest $request, User $staff, string $status, ?string $staffNotes = null): PurchaseRequest
    {
        return DB::transaction(function () use ($request, $staff, $status, $staffNotes) {
            $vehicle = $request->vehicle;

            if ($status === 'approved') {
                // Reserve vehicle so others cannot purchase it while finalizing
                $vehicle->update(['status' => 'reserved']);
            } elseif ($status === 'rejected' && $vehicle->status === 'reserved') {
                // Return to available if was previously reserved for this request
                $vehicle->update(['status' => 'available']);
            }

            $request->update([
                'status' => $status,
                'staff_notes' => $staffNotes,
                'reviewed_by' => $staff->id,
                'reviewed_at' => Carbon::now(),
            ]);

            ActivityLog::log('PURCHASE_REQUEST_REVIEWED', "Purchase request {$request->request_number} status updated to {$status} by {$staff->name}.", $request, $staff);

            return $request;
        });
    }

    /**
     * Finalize sale transaction and mark vehicle permanently as sold.
     */
    public function finalizeSale(PurchaseRequest $request, User $salesManager, array $data): SalesTransaction
    {
        return DB::transaction(function () use ($request, $salesManager, $data) {
            $vehicle = Vehicle::where('id', $request->vehicle_id)->lockForUpdate()->firstOrFail();

            if ($vehicle->status === 'sold') {
                throw new InvalidArgumentException('This vehicle has already been marked as sold.');
            }

            $salePrice = (float) ($data['sale_price'] ?? $request->offering_price);
            $taxAmount = round($salePrice * 0.07, 2); // 7% sales tax
            $feesAmount = 1500.00; // Registration, title & documentation fees
            $totalAmount = $salePrice + $taxAmount + $feesAmount;
            $paidAmount = (float) ($data['paid_amount'] ?? $totalAmount);

            $paymentStatus = $paidAmount >= $totalAmount ? 'paid' : ($paidAmount > 0 ? 'partially_paid' : 'pending');

            $transaction = SalesTransaction::create([
                'purchase_request_id' => $request->id,
                'vehicle_id' => $vehicle->id,
                'customer_id' => $request->user_id,
                'sales_manager_id' => $salesManager->id,
                'sale_price' => $salePrice,
                'tax_amount' => $taxAmount,
                'fees_amount' => $feesAmount,
                'total_amount' => $totalAmount,
                'paid_amount' => $paidAmount,
                'payment_status' => $paymentStatus,
                'sale_date' => Carbon::now()->format('Y-m-d'),
                'notes' => $data['notes'] ?? 'Vehicle bill of sale executed. Title transferred.',
            ]);

            // Mark vehicle permanently as sold
            $vehicle->update(['status' => 'sold']);

            // Complete the purchase request
            $request->update(['status' => 'completed']);

            // Create verified payment record if funds were recorded
            if ($paidAmount > 0) {
                Payment::create([
                    'payable_type' => SalesTransaction::class,
                    'payable_id' => $transaction->id,
                    'user_id' => $request->user_id,
                    'amount' => $paidAmount,
                    'payment_method' => $data['payment_method'] ?? 'bank_transfer',
                    'payment_status' => 'paid',
                    'transaction_reference' => 'TXN-SLS-'.strtoupper(bin2hex(random_bytes(6))),
                    'notes' => 'Sale transaction payment verified by sales manager.',
                    'paid_at' => Carbon::now(),
                    'verified_by' => $salesManager->id,
                    'verified_at' => Carbon::now(),
                ]);
            }

            ActivityLog::log('SALE_COMPLETED', "Sales transaction {$transaction->transaction_number} completed for {$vehicle->full_name}. Total: \${$totalAmount}", $transaction, $salesManager);

            return $transaction;
        });
    }
}
