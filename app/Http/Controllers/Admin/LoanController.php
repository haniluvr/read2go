<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BookLoan;
use Illuminate\Http\Request;

class LoanController extends Controller
{
    public function __construct()
    {
        $this->middleware('admin');
    }

    /**
     * Display a listing of loans
     */
    public function index(Request $request)
    {
        $status = $request->get('status');
        
        $loans = BookLoan::with(['user', 'book', 'library', 'penalties'])
            ->when($status, function ($q) use ($status) {
                $q->where('status', $status);
            })
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('admin.loans.index', compact('loans', 'status'));
    }

    /**
     * Display the specified loan
     */
    public function show(BookLoan $loan)
    {
        $loan->load(['user', 'book', 'library', 'penalties', 'deliverySchedules']);
        return view('admin.loans.show', compact('loan'));
    }
}
