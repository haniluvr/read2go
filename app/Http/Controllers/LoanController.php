<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\BookLoan;
use App\Services\AddressValidationService;
use App\Services\DeliveryFeeService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoanController extends Controller
{
    protected $addressValidationService;
    protected $deliveryFeeService;

    public function __construct(
        AddressValidationService $addressValidationService,
        DeliveryFeeService $deliveryFeeService
    ) {
        $this->addressValidationService = $addressValidationService;
        $this->deliveryFeeService = $deliveryFeeService;
    }

    /**
     * Display a listing of user's loans
     */
    public function index()
    {
        $loans = BookLoan::where('user_id', Auth::id())
            ->with(['book', 'library', 'penalties'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('loans.index', compact('loans'))
            ->with('title', 'My Shelf');
    }

    /**
     * Show the form for creating a new loan
     */
    public function create(Book $book)
    {
        $user = Auth::user();

        // Check if user is suspended
        if ($user->is_suspended) {
            return redirect()->back()->with('error', 'Your account is suspended. Please contact support.');
        }

        // Check if book is available
        if ($book->status !== 'available') {
            return redirect()->back()->with('error', 'This book is not available for loan.');
        }

        $barangays = $this->addressValidationService->getBarangays();

        return view('loans.create', compact('book', 'barangays'))
            ->with('title', 'Borrow');
    }

    /**
     * Store a newly created loan
     */
    public function store(Request $request, Book $book)
    {
        $user = Auth::user();

        // Check if user is suspended
        if ($user->is_suspended) {
            return redirect()->back()->with('error', 'Your account is suspended.');
        }

        // Check if book is available
        if ($book->status !== 'available') {
            return redirect()->back()->with('error', 'This book is not available for loan.');
        }

        $request->validate([
            'delivery_type' => 'required|in:home,pickup',
            'delivery_address' => 'required_if:delivery_type,home|nullable|string',
            'barangay' => 'required_if:delivery_type,home|nullable|string',
        ]);

        // Validate address if home delivery
        if ($request->delivery_type === 'home') {
            $isValid = $this->addressValidationService->validateQuezonCityAddress(
                $request->delivery_address,
                $request->barangay
            );

            if (!$isValid) {
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'Please provide a valid Quezon City address.');
            }

            // Update user address if provided
            if ($request->delivery_address) {
                $user->update([
                    'address' => $request->delivery_address,
                    'barangay' => $request->barangay,
                ]);
            }
        }

        // Calculate delivery fee
        $deliveryFee = 0;
        if ($request->delivery_type === 'home') {
            $deliveryFee = $this->deliveryFeeService->calculateDeliveryFee(
                $book->library,
                $user
            );
        }

        // Create loan with pending_payment status (don't mark book as loaned yet)
        $loan = BookLoan::create([
            'user_id' => $user->id,
            'book_id' => $book->id,
            'library_id' => $book->library_id,
            'delivery_type' => $request->delivery_type,
            'delivery_address' => $request->delivery_type === 'home' ? $request->delivery_address : null,
            'delivery_fee' => $deliveryFee,
            'borrowed_at' => null, // Will be set after payment
            'due_date' => Carbon::now()->addDays(7), // Will be recalculated after payment
            'status' => 'pending_payment',
        ]);

        // Don't update book status yet - wait for payment confirmation

        // Redirect to details page to show summary and proceed to payment
        return redirect()->route('loans.details', $loan);
    }

    /**
     * Display the specified loan
     */
    public function show(BookLoan $loan)
    {
        // Ensure user owns this loan
        if ($loan->user_id !== Auth::id()) {
            abort(403);
        }

        $loan->load(['book', 'library', 'penalties', 'deliverySchedules']);

        return view('loans.show', compact('loan'))
            ->with('title', 'Loan Details');
    }

    /**
     * Show loan details with delivery fee summary (before payment)
     */
    public function details(BookLoan $loan)
    {
        // Ensure user owns this loan
        if ($loan->user_id !== Auth::id()) {
            abort(403);
        }

        // Only show details for pending_payment loans
        if ($loan->status !== 'pending_payment') {
            return redirect()->route('loans.show', $loan);
        }

        $loan->load(['book', 'library']);

        return view('loans.details', compact('loan'))
            ->with('title', 'Confirm Order');
    }

    /**
     * Show delivery confirmation page (after payment)
     */
    public function confirmed(BookLoan $loan)
    {
        // Ensure user owns this loan
        if ($loan->user_id !== Auth::id()) {
            abort(403);
        }

        // Only show for active loans
        if ($loan->status !== 'active') {
            return redirect()->route('loans.show', $loan);
        }

        $loan->load(['book', 'library', 'deliverySchedules']);

        // Get estimated delivery time
        $deliverySchedule = $loan->deliverySchedules()
            ->where('pickup_type', 'delivery')
            ->where('status', 'pending')
            ->first();

        $estimatedDelivery = $deliverySchedule ? $deliverySchedule->scheduled_at : now()->addHours(2);

        return view('loans.confirmed', compact('loan', 'estimatedDelivery'))
            ->with('title', 'Order Confirmed');
    }
}
