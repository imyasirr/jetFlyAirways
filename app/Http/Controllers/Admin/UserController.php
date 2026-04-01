<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\View\View;

class UserController extends Controller
{
    public function index(): View
    {
        $users = User::query()
            ->withCount('bookings')
            ->orderByDesc('id')
            ->paginate(20);

        return view('admin.users.index', compact('users'));
    }

    public function show(User $user): View
    {
        $user->load([
            'bookings' => fn ($q) => $q->orderByDesc('id')->limit(50),
            'referrer',
        ]);
        $user->loadCount('referredUsers');

        return view('admin.users.show', compact('user'));
    }
}
