<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;
use Throwable;

class SocialAuthController extends Controller
{
    public function redirectToGoogle(): RedirectResponse
    {
        if (! config('services.google.client_id')) {
            return redirect()->route('login')->with('error', 'Google sign-in is not configured.');
        }

        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback(): RedirectResponse
    {
        if (! config('services.google.client_id')) {
            return redirect()->route('login')->with('error', 'Google sign-in is not configured.');
        }

        try {
            $googleUser = Socialite::driver('google')->user();
        } catch (Throwable) {
            return redirect()->route('login')->with('error', 'Google sign-in was cancelled or expired. Try again.');
        }

        $email = $googleUser->getEmail();
        if (! $email) {
            return redirect()->route('login')->with('error', 'Google did not return an email for this account.');
        }

        $user = User::query()->where('google_id', $googleUser->getId())->first()
            ?? User::query()->where('email', $email)->first();

        if ($user) {
            if (! $user->google_id) {
                $user->update(['google_id' => $googleUser->getId()]);
            }
        } else {
            $user = User::query()->create([
                'name' => $googleUser->getName() ?: 'Traveller',
                'email' => $email,
                'password' => Str::password(32),
                'google_id' => $googleUser->getId(),
                'is_admin' => false,
            ]);
        }

        Auth::login($user, true);
        request()->session()->regenerate();

        if ($user->isAdmin()) {
            return redirect()->intended(route('admin.dashboard'));
        }

        return redirect()->intended(route('account.dashboard'));
    }
}
