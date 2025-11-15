<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Services\XenditService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PaymentController extends Controller
{
    protected $xenditService;

    public function __construct(XenditService $xenditService)
    {
        $this->xenditService = $xenditService;
    }

    /**
     * Display a listing of user's payments
     */
    public function index()
    {
        $payments = Payment::where('user_id', Auth::id())
            ->with(['loan', 'penalty'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('payments.index', compact('payments'))
            ->with('title', 'Payments');
    }

    /**
     * Create payment for loan (new borrowing flow)
     */
    public function createForLoan(Request $request, $loanId)
    {
        $loan = \App\Models\BookLoan::where('user_id', Auth::id())
            ->where('status', 'pending_payment')
            ->findOrFail($loanId);

        if ($loan->delivery_fee <= 0) {
            return redirect()->back()->with('error', 'No payment required.');
        }

        $payment = $this->xenditService->createInvoice(
            Auth::user(),
            (float) $loan->delivery_fee,
            "Book loan #{$loan->id} - {$loan->book->title}",
            $loan->id
        );

        if (!$payment) {
            return redirect()->back()->with('error', 'Failed to create payment. Please try again.');
        }

        // Get invoice URL from Xendit
        $invoice = $this->xenditService->getInvoice($payment->xendit_payment_id);

        return redirect($invoice['invoice_url'] ?? route('loans.details', $loan))
            ->with('success', 'Payment invoice created. Please complete the payment.');
    }

    /**
     * Confirm free loan (no payment required)
     */
    public function confirmFreeLoan(Request $request, $loanId)
    {
        $loan = \App\Models\BookLoan::where('user_id', Auth::id())
            ->where('status', 'pending_payment')
            ->findOrFail($loanId);

        if ($loan->delivery_fee > 0) {
            return redirect()->back()->with('error', 'Payment required for this order.');
        }

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

        return redirect()->route('loans.confirmed', $loan)
            ->with('success', 'Order confirmed! Your book will be delivered soon.');
    }

    /**
     * Create payment for delivery fee (old method - kept for backward compatibility)
     */
    public function createForDelivery(Request $request, $loanId)
    {
        $loan = \App\Models\BookLoan::where('user_id', Auth::id())
            ->findOrFail($loanId);

        if ($loan->delivery_fee <= 0) {
            return redirect()->back()->with('error', 'No delivery fee to pay.');
        }

        $payment = $this->xenditService->createInvoice(
            Auth::user(),
            (float) $loan->delivery_fee,
            "Delivery fee for loan #{$loan->id}",
            $loan->id
        );

        if (!$payment) {
            return redirect()->back()->with('error', 'Failed to create payment. Please try again.');
        }

        // Get invoice URL from Xendit
        $invoice = $this->xenditService->getInvoice($payment->xendit_payment_id);

        return redirect($invoice['invoice_url'] ?? route('payments.index'))
            ->with('success', 'Payment invoice created. Please complete the payment.');
    }

    /**
     * Create payment for penalty
     */
    public function createForPenalty(Request $request, $penaltyId)
    {
        $penalty = \App\Models\Penalty::where('user_id', Auth::id())
            ->findOrFail($penaltyId);

        if ($penalty->is_paid) {
            return redirect()->back()->with('error', 'This penalty is already paid.');
        }

        $payment = $this->xenditService->createInvoice(
            Auth::user(),
            (float) $penalty->amount,
            "Penalty payment: {$penalty->reason}",
            $penalty->loan_id,
            $penalty->id
        );

        if (!$payment) {
            return redirect()->back()->with('error', 'Failed to create payment. Please try again.');
        }

        // Get invoice URL from Xendit
        $invoice = $this->xenditService->getInvoice($payment->xendit_payment_id);

        return redirect($invoice['invoice_url'] ?? route('payments.index'))
            ->with('success', 'Payment invoice created. Please complete the payment.');
    }

    /**
     * Handle payment success callback
     */
    public function success(Request $request)
    {
        // Check for recent payment with loan_id (within last 10 minutes)
        $recentPayment = Payment::where('user_id', Auth::id())
            ->whereNotNull('loan_id')
            ->where('created_at', '>=', now()->subMinutes(10))
            ->orderBy('created_at', 'desc')
            ->first();
        
        if ($recentPayment && $recentPayment->loan) {
            $loan = $recentPayment->loan;
            
            // Check payment status from Xendit API if still pending
            if ($recentPayment->status === 'pending' && $recentPayment->xendit_payment_id) {
                $invoice = $this->xenditService->getInvoice($recentPayment->xendit_payment_id);
                
                if ($invoice && isset($invoice['status'])) {
                    // Update payment status based on Xendit response
                    $paymentStatus = match(strtoupper($invoice['status'])) {
                        'PAID' => 'paid',
                        'SETTLED' => 'paid',
                        'EXPIRED', 'CANCELLED' => 'failed',
                        default => 'pending',
                    };
                    
                    $recentPayment->update(['status' => $paymentStatus]);
                    
                    // If payment is now paid, activate the loan
                    if ($paymentStatus === 'paid' && $loan->status === 'pending_payment') {
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
                                'scheduled_at' => now()->addHours(rand(1, 3)),
                                'status' => 'pending',
                            ]);
                        }
                    }
                }
            }
            
            // If loan is now active, redirect to confirmation page
            $loan->refresh();
            if ($loan->status === 'active') {
                return redirect()->route('loans.confirmed', $loan);
            }
        }

        return view('payments.success')->with('title', 'Payment Success');
    }

    /**
     * Check payment status manually
     */
    public function checkStatus(Request $request, $paymentId)
    {
        $payment = Payment::where('user_id', Auth::id())
            ->findOrFail($paymentId);
        
        if ($payment->status === 'pending' && $payment->xendit_payment_id) {
            $invoice = $this->xenditService->getInvoice($payment->xendit_payment_id);
            
            if ($invoice && isset($invoice['status'])) {
                $paymentStatus = match(strtoupper($invoice['status'])) {
                    'PAID' => 'paid',
                    'SETTLED' => 'paid',
                    'EXPIRED', 'CANCELLED' => 'failed',
                    default => 'pending',
                };
                
                $payment->update(['status' => $paymentStatus]);
                
                // If payment is now paid and has a loan, activate it
                if ($paymentStatus === 'paid' && $payment->loan_id) {
                    $loan = $payment->loan;
                    
                    if ($loan->status === 'pending_payment') {
                        $loan->update([
                            'status' => 'active',
                            'borrowed_at' => now(),
                            'due_date' => \Carbon\Carbon::now()->addDays(7),
                        ]);
                        
                        $loan->book->update(['status' => 'loaned']);
                        
                        if ($loan->delivery_type === 'home') {
                            \App\Models\DeliverySchedule::create([
                                'loan_id' => $loan->id,
                                'pickup_type' => 'delivery',
                                'scheduled_at' => now()->addHours(rand(1, 3)),
                                'status' => 'pending',
                            ]);
                        }
                        
                        return redirect()->route('loans.confirmed', $loan)
                            ->with('success', 'Payment confirmed! Your loan is now active.');
                    }
                }
                
                if ($paymentStatus === 'paid') {
                    return redirect()->back()->with('success', 'Payment confirmed!');
                }
            }
        }
        
        return redirect()->back()->with('info', 'Payment status checked.');
    }

    /**
     * Handle payment failure callback
     */
    public function failure(Request $request)
    {
        return view('payments.failure')->with('title', 'Payment Failed');
    }

    /**
     * Handle Xendit webhook
     */
    public function webhook(Request $request)
    {
        $token = $request->header('x-callback-token');

        // Verify webhook token
        if (!$this->xenditService->verifyWebhookSignature($token)) {
            abort(403, 'Invalid webhook token');
        }

        $data = $request->all();
        $this->xenditService->handleWebhook($data);

        return response()->json(['status' => 'ok']);
    }
}
