<?php

namespace App\Services;

use App\Models\ActivityLog;
use App\Models\Payment;
use App\Models\RentalBooking;
use App\Models\SalesTransaction;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;

class PaymentService
{
    /**
     * Record a verified or simulated payment against a booking or sales transaction.
     */
    public function recordPayment(Model $payable, User $user, float $amount, string $paymentMethod, ?string $notes = null, ?User $staffVerifier = null): Payment
    {
        return DB::transaction(function () use ($payable, $user, $amount, $paymentMethod, $notes, $staffVerifier) {
            if ($amount <= 0) {
                throw new InvalidArgumentException('Payment amount must be greater than zero.');
            }

            $currentPaid = (float) $payable->paid_amount;
            $totalAmount = (float) $payable->total_amount;
            $newPaid = round($currentPaid + $amount, 2);

            $payment = Payment::create([
                'payable_type' => get_class($payable),
                'payable_id' => $payable->getKey(),
                'user_id' => $user->id,
                'amount' => $amount,
                'payment_method' => $paymentMethod,
                'payment_status' => 'paid',
                'transaction_reference' => 'TXN-'.strtoupper(substr(class_basename($payable), 0, 3)).'-'.strtoupper(bin2hex(random_bytes(5))),
                'notes' => $notes ?? 'Payment recorded successfully.',
                'paid_at' => Carbon::now(),
                'verified_by' => $staffVerifier?->id,
                'verified_at' => $staffVerifier ? Carbon::now() : null,
            ]);

            // Update payable paid_amount and status
            if ($payable instanceof RentalBooking) {
                $payable->update([
                    'paid_amount' => $newPaid,
                ]);
            } elseif ($payable instanceof SalesTransaction) {
                $payableStatus = $newPaid >= $totalAmount ? 'paid' : 'partially_paid';
                $payable->update([
                    'paid_amount' => $newPaid,
                    'payment_status' => $payableStatus,
                ]);
            }

            ActivityLog::log('PAYMENT_RECORDED', "Payment of \${$amount} recorded for ".class_basename($payable)." #{$payable->getKey()}.", $payment, $user);

            return $payment;
        });
    }
}
