<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\Sms\SmsSender;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function register(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'max:255', 'unique:'.User::class],
            'phone' => ['nullable', 'string', 'max:20'],
            'password' => ['required', 'confirmed', Password::defaults()],
            'referral' => ['nullable', 'string', 'max:16'],
        ]);

        $referrer = null;
        if (! empty($validated['referral'])) {
            $referrer = User::query()
                ->where('referral_code', strtoupper(trim($validated['referral'])))
                ->where('email', '!=', $validated['email'])
                ->first();
        }

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,
            'password' => $validated['password'],
            'is_admin' => false,
            'referred_by_user_id' => $referrer?->id,
        ]);

        $token = $user->createToken('mobile-app')->plainTextToken;

        return response()->json([
            'message' => 'Registration successful.',
            'token' => $token,
            'user' => $this->userPayload($user),
        ], 201);
    }

    public function login(Request $request): JsonResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        $user = User::query()->where('email', $credentials['email'])->first();

        if (! $user || ! Hash::check($credentials['password'], $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['These credentials do not match our records.'],
            ]);
        }

        if ($user->isAdmin()) {
            return response()->json(['message' => 'Admin accounts cannot use the mobile app.'], 403);
        }

        $token = $user->createToken('mobile-app')->plainTextToken;

        return response()->json([
            'message' => 'Login successful.',
            'token' => $token,
            'user' => $this->userPayload($user),
        ]);
    }

    public function logout(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Logged out successfully.']);
    }

    public function user(Request $request): JsonResponse
    {
        return response()->json(['user' => $this->userPayload($request->user())]);
    }

    public function sendOtp(Request $request, SmsSender $sms): JsonResponse
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
            return response()->json([
                'message' => 'No account found with this phone. Register first or use email login.',
            ], 422);
        }

        $code = (string) random_int(100000, 999999);
        Cache::put($this->otpCacheKey($data['phone']), $code, now()->addMinutes(10));

        $msg = 'Your Jet Fly OTP is '.$code.'. Valid for 10 minutes.';
        if (config('jetfly.sms.driver') === 'log') {
            Log::info('[OTP dev] '.$data['phone'].' => '.$code);
        }
        $sms->send($data['phone'], $msg);

        return response()->json([
            'message' => 'OTP sent successfully.',
            'phone' => $data['phone'],
        ]);
    }

    public function verifyOtp(Request $request): JsonResponse
    {
        $data = $request->validate([
            'phone' => ['required', 'string', 'max:20'],
            'code' => ['required', 'string', 'size:6'],
        ]);

        $expected = Cache::get($this->otpCacheKey($data['phone']));
        if (! $expected || $expected !== $data['code']) {
            return response()->json(['message' => 'Invalid or expired OTP.'], 422);
        }

        $digits = preg_replace('/\D+/', '', $data['phone']) ?? '';
        $user = User::query()
            ->where(function ($q) use ($data, $digits) {
                $q->where('phone', $data['phone']);
                if ($digits !== '') {
                    $q->orWhere('phone', $digits)->orWhere('phone', 'like', '%'.$digits.'%');
                }
            })
            ->first();

        if (! $user) {
            return response()->json(['message' => 'User not found.'], 404);
        }

        Cache::forget($this->otpCacheKey($data['phone']));

        $token = $user->createToken('mobile-app')->plainTextToken;

        return response()->json([
            'message' => 'Login successful.',
            'token' => $token,
            'user' => $this->userPayload($user),
        ]);
    }

    /**
     * @return array<string, mixed>
     */
    public function userPayload(User $user): array
    {
        return [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'phone' => $user->phone,
            'avatar_url' => $user->avatarUrl(),
            'referral_code' => $user->referral_code,
            'referrals_count' => $user->referredUsers()->count(),
            'date_of_birth' => $user->date_of_birth?->format('Y-m-d'),
            'gender' => $user->gender,
            'nationality' => $user->nationality,
        ];
    }

    private function otpCacheKey(string $phone): string
    {
        return 'login_otp:'.sha1($phone);
    }
}
