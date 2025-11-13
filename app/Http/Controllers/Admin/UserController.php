<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('admin');
    }

    /**
     * Display a listing of users
     */
    public function index(Request $request)
    {
        $search = $request->get('search');
        $suspended = $request->get('suspended');

        $users = User::withCount(['bookLoans', 'penalties'])
            ->when($search, function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            })
            ->when($suspended !== null, function ($q) use ($suspended) {
                $q->where('is_suspended', $suspended);
            })
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('admin.users.index', compact('users', 'search', 'suspended'));
    }

    /**
     * Display the specified user
     */
    public function show(User $user)
    {
        $user->load(['bookLoans.book', 'penalties', 'payments']);
        return view('admin.users.show', compact('user'));
    }

    /**
     * Suspend user
     */
    public function suspend(User $user)
    {
        $user->update(['is_suspended' => true]);
        return redirect()->back()->with('success', 'User suspended successfully.');
    }

    /**
     * Unsuspend user
     */
    public function unsuspend(User $user)
    {
        $user->update(['is_suspended' => false]);
        return redirect()->back()->with('success', 'User unsuspended successfully.');
    }
}
