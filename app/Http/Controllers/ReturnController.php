<?php

namespace App\Http\Controllers;

use App\Models\BookLoan;
use App\Models\DeliverySchedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReturnController extends Controller
{
    /**
     * Show return form
     */
    public function create(BookLoan $loan)
    {
        // Ensure user owns this loan
        if ($loan->user_id !== Auth::id()) {
            abort(403);
        }

        // Check if loan is active
        if ($loan->status !== 'active' && $loan->status !== 'overdue') {
            return redirect()->route('loans.show', $loan)
                ->with('error', 'This loan cannot be returned.');
        }

        return view('returns.create', compact('loan'))
            ->with('title', 'Return Book');
    }

    /**
     * Process book return
     */
    public function store(Request $request, BookLoan $loan)
    {
        // Ensure user owns this loan
        if ($loan->user_id !== Auth::id()) {
            abort(403);
        }

        // Check if loan is active
        if ($loan->status !== 'active' && $loan->status !== 'overdue') {
            return redirect()->route('loans.show', $loan)
                ->with('error', 'This loan cannot be returned.');
        }

        $request->validate([
            'return_method' => 'required|in:pickup,dropoff',
        ]);

        $returnMethod = $request->return_method;

        // If pickup, create delivery schedule
        if ($returnMethod === 'pickup') {
            DeliverySchedule::create([
                'loan_id' => $loan->id,
                'pickup_type' => 'return',
                'scheduled_at' => $request->scheduled_at ?? now()->addDay(),
                'status' => 'pending',
            ]);

            return redirect()->route('loans.show', $loan)
                ->with('success', 'Return pickup scheduled. A rider will collect the book soon.');
        }

        // If dropoff, mark as returned immediately
        $loan->update([
            'returned_at' => now(),
            'return_method' => 'dropoff',
            'status' => 'returned',
        ]);

        // Update book status
        $loan->book->update(['status' => 'available']);

        return redirect()->route('loans.show', $loan)
            ->with('success', 'Book returned successfully!');
    }

    /**
     * Confirm return (for admin/rider)
     */
    public function confirm(BookLoan $loan)
    {
        if ($loan->returned_at) {
            return redirect()->back()->with('error', 'Book already returned.');
        }

        $loan->update([
            'returned_at' => now(),
            'return_method' => 'pickup',
            'status' => 'returned',
        ]);

        // Update book status
        $loan->book->update(['status' => 'available']);

        // Update delivery schedule if exists
        $deliverySchedule = DeliverySchedule::where('loan_id', $loan->id)
            ->where('pickup_type', 'return')
            ->where('status', 'pending')
            ->first();

        if ($deliverySchedule) {
            $deliverySchedule->update(['status' => 'completed']);
        }

        return redirect()->back()->with('success', 'Return confirmed successfully.');
    }
}
