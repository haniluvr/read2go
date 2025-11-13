<?php

namespace App\Http\Controllers;

use App\Models\Penalty;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PenaltyController extends Controller
{
    /**
     * Display a listing of user's penalties
     */
    public function index()
    {
        $penalties = Penalty::where('user_id', Auth::id())
            ->with(['loan.book'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        $totalUnpaid = $penalties->where('is_paid', false)->sum('amount');

        return view('penalties.index', compact('penalties', 'totalUnpaid'));
    }

    /**
     * Display the specified penalty
     */
    public function show(Penalty $penalty)
    {
        // Ensure user owns this penalty
        if ($penalty->user_id !== Auth::id()) {
            abort(403);
        }

        $penalty->load(['loan.book', 'loan.library']);

        return view('penalties.show', compact('penalty'));
    }
}
