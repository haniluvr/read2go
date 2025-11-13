<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DeliverySchedule;
use Illuminate\Http\Request;

class DeliveryController extends Controller
{
    public function __construct()
    {
        $this->middleware('admin');
    }

    /**
     * Display a listing of delivery schedules
     */
    public function index(Request $request)
    {
        $status = $request->get('status');
        $pickupType = $request->get('pickup_type');

        $schedules = DeliverySchedule::with(['loan.user', 'loan.book', 'rider'])
            ->when($status, function ($q) use ($status) {
                $q->where('status', $status);
            })
            ->when($pickupType, function ($q) use ($pickupType) {
                $q->where('pickup_type', $pickupType);
            })
            ->orderBy('scheduled_at', 'asc')
            ->paginate(20);

        return view('admin.deliveries.index', compact('schedules', 'status', 'pickupType'));
    }

    /**
     * Update delivery schedule status
     */
    public function updateStatus(DeliverySchedule $schedule, Request $request)
    {
        $request->validate([
            'status' => 'required|in:pending,in_progress,completed,cancelled',
        ]);

        $schedule->update(['status' => $request->status]);

        // If completed and it's a return pickup, mark loan as returned
        if ($request->status === 'completed' && $schedule->pickup_type === 'return') {
            $loan = $schedule->loan;
            if (!$loan->returned_at) {
                $loan->update([
                    'returned_at' => now(),
                    'return_method' => 'pickup',
                    'status' => 'returned',
                ]);
                $loan->book->update(['status' => 'available']);
            }
        }

        return redirect()->back()->with('success', 'Delivery status updated successfully.');
    }
}
