<?php

namespace App\Services;

use App\Models\Payment;
use App\Models\User;
use Xendit\Xendit;
use Illuminate\Support\Facades\Log;

class XenditService
{
    protected $secretKey;

    public function __construct()
    {
        $this->secretKey = config('services.xendit.secret_key', env('XENDIT_SECRET_KEY'));
        Xendit::setApiKey($this->secretKey);
    }

    /**
     * Create a payment invoice
     */
    public function createInvoice(User $user, float $amount, string $description, ?int $loanId = null, ?int $penaltyId = null): ?Payment
    {
        try {
            $invoiceParams = [
                'external_id' => 'read2go_' . uniqid(),
                'payer_email' => $user->email,
                'description' => $description,
                'amount' => $amount,
                'currency' => 'PHP',
                'success_redirect_url' => route('payments.success'),
                'failure_redirect_url' => route('payments.failure'),
            ];

            $invoice = \Xendit\Invoice::create($invoiceParams);

            // Create payment record
            $payment = Payment::create([
                'user_id' => $user->id,
                'loan_id' => $loanId,
                'penalty_id' => $penaltyId,
                'amount' => $amount,
                'currency' => 'PHP',
                'xendit_payment_id' => $invoice['id'],
                'status' => 'pending',
                'description' => $description,
            ]);

            return $payment;
        } catch (\Exception $e) {
            Log::error('Xendit Invoice Creation Error: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Get invoice by ID
     */
    public function getInvoice(string $invoiceId)
    {
        try {
            return \Xendit\Invoice::retrieve($invoiceId);
        } catch (\Exception $e) {
            Log::error('Xendit Get Invoice Error: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Handle webhook callback
     */
    public function handleWebhook(array $data): bool
    {
        try {
            $invoiceId = $data['id'] ?? null;
            $status = $data['status'] ?? null;

            if (!$invoiceId || !$status) {
                return false;
            }

            $payment = Payment::where('xendit_payment_id', $invoiceId)->first();
            if (!$payment) {
                return false;
            }

            // Update payment status
            $paymentStatus = match($status) {
                'PAID' => 'paid',
                'EXPIRED', 'CANCELLED' => 'failed',
                default => 'pending',
            };

            $payment->update(['status' => $paymentStatus]);

            // If payment is for a penalty, mark penalty as paid
            if ($payment->penalty_id && $paymentStatus === 'paid') {
                $payment->penalty->update(['is_paid' => true]);
            }

            return true;
        } catch (\Exception $e) {
            Log::error('Xendit Webhook Error: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Verify webhook signature
     * Xendit uses x-callback-token header for webhook verification
     */
    public function verifyWebhookSignature(string $token): bool
    {
        // Xendit sends the callback token in x-callback-token header
        // You should configure this token in Xendit dashboard
        $expectedToken = config('services.xendit.webhook_token', env('XENDIT_WEBHOOK_TOKEN'));
        
        if (!$expectedToken) {
            // If no token configured, skip verification (not recommended for production)
            return true;
        }

        return hash_equals($expectedToken, $token);
    }
}

