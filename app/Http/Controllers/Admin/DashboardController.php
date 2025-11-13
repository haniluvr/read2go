<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BookLoan;
use App\Models\Penalty;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('admin');
    }

    /**
     * Display admin dashboard
     */
    public function index()
    {
        $stats = [
            'total_users' => User::count(),
            'active_loans' => BookLoan::where('status', 'active')->count(),
            'overdue_loans' => BookLoan::where('status', 'overdue')->count(),
            'pending_payments' => \App\Models\Payment::where('status', 'pending')->count(),
            'unpaid_penalties' => Penalty::where('is_paid', false)->count(),
            'suspended_users' => User::where('is_suspended', true)->count(),
        ];

        $recentLoans = BookLoan::with(['user', 'book', 'library'])
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        return view('admin.dashboard', compact('stats', 'recentLoans'));
    }
}
