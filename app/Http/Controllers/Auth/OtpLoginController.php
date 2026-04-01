<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\Sms\SmsSender;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

class OtpLoginController extends Controller
{
    public function create(): View
    {
        return view('auth.login-otp');
    }

    public function send(Request $request, SmsSender $sms): RedirectResponse
    {
        $data = $request->validate([
            'phone' => ['required', 'string', 'max:20'],
        ]);

        $normalized = preg_replace('/\D+/', '', $data['phone']) ?? '';
        $user = User::query()
            ->where('phone', $data['phone'])
            ->orWhere('phone', $normalized)
            ->orWhere('phone', 'like', '%'.$normalized.'%')
            ->first();

        if (! $user) {
            return back()->withErrors(['phone' => 'No account found with this phone. Register first or use email login.'])->onlyInput('phone');
        }

        $code = (string) random_int(100000, 999999);
        Cache::put($this->cacheKey($data['phone']), $code, now()->addMinutes(10));
        $request->session()->put('otp_phone', $data['phone']);

        $msg = 'Your Jet Fly OTP is '.$code.'. Valid for 10 minutes.';
        if (config('jetfly.sms.driver') === 'log') {
            Log::info('[OTP dev] '.$data['phone'].' => '.$code);
        }
        $sms->send($data['phone'], $msg);

        return redirect()->route('login.otp.verify')->with('status', 'We sent a code to your phone. (Check logs if SMS driver is "log".)');
    }

    public function verifyForm(Request $request): View|RedirectResponse
    {
        if (! $request->session()->has('otp_phone')) {
            return redirect()->route('login.otp')->with('error', 'Enter your phone first.');
        }

        return view('auth.login-otp-verify');
    }

    public function verify(Request $request): RedirectResponse
    {
        $phone = $request->session()->get('otp_phone');
        if (! $phone) {
            return redirect()->route('login.otp');
        }

        $data = $request->validate([
            'code' => ['required', 'string', 'size:6'],
        ]);

        $expected = Cache::get($this->cacheKey($phone));
        if (! $expected || $expected !== $data['code']) {
            return back()->withErrors(['code' => 'Invalid or expired code.'])->onlyInput('code');
        }

        $digits = preg_replace('/\D+/', '', $phone) ?? '';
        $user = User::query()
            ->where(function ($q) use ($phone, $digits) {
                $q->where('phone', $phone);
                if ($digits !== '') {
                    $q->orWhere('phone', $digits)->orWhere('phone', 'like', '%'.$digits.'%');
                }
            })
            ->first();
        if (! $user) {
            return redirect()->route('login.otp')->with('error', 'User not found.');
        }

        Cache::forget($this->cacheKey($phone));
        $request->session()->forget('otp_phone');

        Auth::login($user, true);
        $request->session()->regenerate();

        if ($user->isAdmin()) {
            return redirect()->intended(route('admin.dashboard'));
        }

        return redirect()->intended(route('account.dashboard'));
    }

    private function cacheKey(string $phone): string
    {
        return 'login_otp:'.sha1($phone);
    }
}
