<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\auth\LoginRequest;
use Auth;
use Illuminate\Http\JsonResponse;

class LoginController extends Controller
{
    public function store(LoginRequest $request): JsonResponse
    {
        $attributes = $request->validated();

        if (!Auth::attempt($attributes)) {
            return response()->json([
                'message' => "Login failed! Your provided credentials couldn't be verified.",
            ], 401);
        }

        return response()->json([
            'message' => 'Logged in successfully',
            'token' => auth()->user()->api_token
        ]);
    }
}
