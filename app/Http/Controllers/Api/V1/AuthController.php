<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function sendOtp(Request $request)
    {
        $request->validate(['phone' => 'required|string|max:20']);

        $phone = $request->input('phone');
        $otp = random_int(100000, 999999);

        // Store in cache for 5 minutes
        Cache::put("otp:{$phone}", $otp, now()->addMinutes(5));

        // TODO: Send SMS via Africa's Talking or similar
        Log::info("OTP for {$phone}: {$otp}");

        return response()->json(['message' => 'OTP sent'], 200);
    }

    public function verifyOtp(Request $request)
    {
        $request->validate([
            'phone' => 'required|string|max:20',
            'otp' => 'required|digits:6',
        ]);

        $phone = $request->input('phone');
        $code = $request->input('otp');

        $cached = Cache::get("otp:{$phone}");

        if (!$cached || $cached != $code) {
            throw ValidationException::withMessages([
                'otp' => ['Invalid or expired OTP.'],
            ]);
        }

        // Clear OTP
        Cache::forget("otp:{$phone}");

        // First‑or‑create user (no password)
        $user = User::firstOrCreate(
            ['phone' => $phone],
            ['name' => 'User ' . Str::substr($phone, -4), 'password' => null]
        );

        // Create token with abilities (scopes)
        $token = $user->createToken('mobile-app', ['mobile'])->plainTextToken;

        return response()->json([
            'message' => 'Authenticated',
            'token' => $token,
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'phone' => $user->phone,
            ],
        ]);
    }
}
