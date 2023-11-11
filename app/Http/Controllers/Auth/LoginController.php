<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function __invoke(Request $request)
    {
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            auth()->user()->tokens()->delete();

            return response()->json([
                'access_token' => auth()->user()->createToken(
                    'client',
                    expiresAt: now()->addDays((config('sanctum.dates_to_expiration')))
                )->plainTextToken,
            ], 200);
        }

        return response()->json([
            'message' => 'invalid credentials'
        ], 401);
    }
}
