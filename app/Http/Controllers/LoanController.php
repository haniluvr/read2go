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

        return view('loans.index', compact('loans'));
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

        return view('loans.create', compact('book', 'barangays'));
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

        // Create loan
        $loan = BookLoan::create([
            'user_id' => $user->id,
            'book_id' => $book->id,
            'library_id' => $book->library_id,
            'delivery_type' => $request->delivery_type,
            'delivery_address' => $request->delivery_type === 'home' ? $request->delivery_address : null,
            'delivery_fee' => $deliveryFee,
            'borrowed_at' => now(),
            'due_date' => Carbon::now()->addDays(7),
            'status' => 'active',
        ]);

        // Update book status
        $book->update(['status' => 'loaned']);

        return redirect()->route('loans.show', $loan)
            ->with('success', 'Book loaned successfully!');
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

        return view('loans.show', compact('loan'));
    }
}
