<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Penalty;
use Illuminate\Http\Request;

class PenaltyController extends Controller
{
    public function __construct()
    {
        $this->middleware('admin');
    }

    /**
     * Display a listing of penalties
     */
    public function index(Request $request)
    {
        $paid = $request->get('paid');
        $type = $request->get('type');

        $penalties = Penalty::with(['user', 'loan.book'])
            ->when($paid !== null, function ($q) use ($paid) {
                $q->where('is_paid', $paid);
            })
            ->when($type, function ($q) use ($type) {
                $q->where('type', $type);
            })
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('admin.penalties.index', compact('penalties', 'paid', 'type'));
    }

    /**
     * Display the specified penalty
     */
    public function show(Penalty $penalty)
    {
        $penalty->load(['user', 'loan.book', 'loan.library']);
        return view('admin.penalties.show', compact('penalty'));
    }
}
