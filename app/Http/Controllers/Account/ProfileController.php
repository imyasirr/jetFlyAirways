<?php

namespace App\Http\Controllers\Account;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Support\PublicImageStorage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function edit(): View
    {
        $user = auth()->user();

        return view('account.profile.edit', [
            'user' => $user,
            'stats' => [
                'bookings' => $user->bookings()->count(),
                'wishlist' => $user->wishlistItems()->count(),
                'travellers' => $user->savedTravellers()->count(),
                'referrals' => $user->referredUsers()->count(),
            ],
            'referralShareUrl' => $user->referral_code
                ? route('register', ['ref' => $user->referral_code], absolute: true)
                : null,
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $user = $request->user();

        if (! filled($request->input('gender'))) {
            $request->merge(['gender' => null]);
        }
        if (! filled($request->input('nationality'))) {
            $request->merge(['nationality' => null]);
        }
        if (! filled($request->input('date_of_birth'))) {
            $request->merge(['date_of_birth' => null]);
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'max:255', Rule::unique(User::class)->ignore($user->id)],
            'phone' => ['nullable', 'string', 'max:20'],
            'date_of_birth' => ['nullable', 'date', 'before:today'],
            'gender' => ['nullable', 'string', Rule::in(['male', 'female', 'other', 'prefer_not_to_say'])],
            'nationality' => ['nullable', 'string', 'max:80'],
            'avatar_file' => ['nullable', 'image', 'mimes:jpeg,png,webp,gif', 'max:4096'],
            'clear_avatar' => ['boolean'],
        ]);

        if ($request->boolean('clear_avatar')) {
            PublicImageStorage::deleteIfExists($user->avatar);
            $user->avatar = null;
        }

        if ($request->hasFile('avatar_file')) {
            $path = PublicImageStorage::storeUpload($request->file('avatar_file'), 'avatars', $user->avatar);
            abort_if($path === null, 500, 'Profile photo upload failed.');
            $user->avatar = $path;
        }

        $user->fill(collect($validated)->only([
            'name',
            'email',
            'phone',
            'date_of_birth',
            'gender',
            'nationality',
        ])->map(fn ($value, $key) => in_array($key, ['gender', 'nationality', 'date_of_birth'], true) && ! filled($value) ? null : $value)->all());
        $user->save();

        return redirect()->route('account.profile.edit')->with('status', 'Profile updated successfully.');
    }

    public function editPassword(): View
    {
        return view('account.profile.password');
    }

    public function updatePassword(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        $request->user()->update([
            'password' => $validated['password'],
        ]);

        return redirect()->route('account.password.edit')->with('status', 'Password updated.');
    }
}
