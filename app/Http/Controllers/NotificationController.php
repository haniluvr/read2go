<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    /**
     * Display a listing of notifications
     */
    public function index()
    {
        $notifications = Auth::user()->notifications()
            ->with('bookLoan.book')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        // Mark as read
        Auth::user()->notifications()->where('is_read', false)->update(['is_read' => true]);

        return view('notifications.index', compact('notifications'));
    }
}
