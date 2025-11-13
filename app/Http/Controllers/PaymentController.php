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

        return view('payments.index', compact('payments'));
    }

    /**
     * Create payment for delivery fee
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
            $loan->delivery_fee,
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
            $penalty->amount,
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
        return view('payments.success');
    }

    /**
     * Handle payment failure callback
     */
    public function failure(Request $request)
    {
        return view('payments.failure');
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
