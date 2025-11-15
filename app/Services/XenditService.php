<?php

namespace App\Services;

use App\Models\Payment;
use App\Models\User;
use Xendit\Configuration;
use Xendit\Invoice\InvoiceApi;
use Xendit\Invoice\CreateInvoiceRequest;
use Illuminate\Support\Facades\Log;

class XenditService
{
    protected $secretKey;
    protected $invoiceApi;

    public function __construct()
    {
        $this->secretKey = config('services.xendit.secret_key', env('XENDIT_SECRET_KEY'));
        
        // Check if API key is set
        if (empty($this->secretKey)) {
            Log::warning('Xendit API key is not configured. Please set XENDIT_SECRET_KEY in your .env file.');
        } else {
            Configuration::setXenditKey($this->secretKey);
        }
        
        // Create Guzzle client with SSL verification disabled for local development
        $client = new \GuzzleHttp\Client([
            'verify' => false, // Disable SSL verification for local development (XAMPP)
        ]);
        
        $this->invoiceApi = new InvoiceApi($client);
    }

    /**
     * Create a payment invoice
     */
    public function createInvoice(User $user, float $amount, string $description, ?int $loanId = null, ?int $penaltyId = null): ?Payment
    {
        try {
            // Check if API key is set
            if (empty($this->secretKey)) {
                Log::error('Xendit API key is not configured');
                return null;
            }

            $createInvoiceRequest = new CreateInvoiceRequest([
                'external_id' => 'read2go_' . uniqid(),
                'payer_email' => $user->email,
                'description' => $description,
                'amount' => $amount,
                'currency' => 'PHP',
                'success_redirect_url' => route('payments.success', absolute: true),
                'failure_redirect_url' => route('payments.failure', absolute: true),
            ]);

            $invoice = $this->invoiceApi->createInvoice($createInvoiceRequest);

            // Create payment record
            $payment = Payment::create([
                'user_id' => $user->id,
                'loan_id' => $loanId,
                'penalty_id' => $penaltyId,
                'amount' => $amount,
                'currency' => 'PHP',
                'xendit_payment_id' => $invoice->getId(),
                'status' => 'pending',
                'description' => $description,
            ]);

            return $payment;
        } catch (\Xendit\XenditSdkException $e) {
            $fullError = method_exists($e, 'getFullError') ? $e->getFullError() : null;
            Log::error('Xendit SDK Error: ' . $e->getMessage());
            if ($fullError) {
                Log::error('Xendit Full Error: ' . json_encode($fullError));
            }
            Log::error('Xendit Error Code: ' . $e->getCode());
            Log::error('Xendit Error File: ' . $e->getFile() . ':' . $e->getLine());
            return null;
        } catch (\Exception $e) {
            Log::error('Xendit Invoice Creation Error: ' . $e->getMessage());
            Log::error('Xendit Error Class: ' . get_class($e));
            Log::error('Xendit Error Code: ' . $e->getCode());
            Log::error('Xendit Error File: ' . $e->getFile() . ':' . $e->getLine());
            if ($e->getPrevious()) {
                Log::error('Xendit Previous Error: ' . $e->getPrevious()->getMessage());
            }
            return null;
        }
    }

    /**
     * Get invoice by ID
     */
    public function getInvoice(string $invoiceId)
    {
        try {
            $invoice = $this->invoiceApi->getInvoiceById($invoiceId);
            // Convert to array format for backward compatibility
            $status = $invoice->getStatus();
            $currency = $invoice->getCurrency();
            
            return [
                'id' => $invoice->getId(),
                'status' => $status ? (string) $status : null,
                'invoice_url' => $invoice->getInvoiceUrl(),
                'amount' => $invoice->getAmount(),
                'currency' => $currency ? (string) $currency : null,
            ];
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

            // If payment is for a loan (new borrowing flow)
            if ($payment->loan_id && $paymentStatus === 'paid') {
                $loan = $payment->loan;
                
                // Only activate if loan is still pending_payment
                if ($loan && $loan->status === 'pending_payment') {
                    // Activate the loan
                    $loan->update([
                        'status' => 'active',
                        'borrowed_at' => now(),
                        'due_date' => \Carbon\Carbon::now()->addDays(7),
                    ]);

                    // Mark book as loaned
                    $loan->book->update(['status' => 'loaned']);

                    // Create delivery schedule if home delivery
                    if ($loan->delivery_type === 'home') {
                        \App\Models\DeliverySchedule::create([
                            'loan_id' => $loan->id,
                            'pickup_type' => 'delivery',
                            'scheduled_at' => now()->addHours(rand(1, 3)), // 1-3 hours
                            'status' => 'pending',
                        ]);
                    }
                }
            }

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

