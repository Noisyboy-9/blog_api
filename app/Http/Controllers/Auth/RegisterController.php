<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\auth\RegisterRequest;
use App\Models\User;
use Str;

class RegisterController extends Controller
{
    public function store(RegisterRequest $registerRequest)
    {
        $attributes = $registerRequest->validated();
        $attributes['api_token'] = Str::random(60);
        
        $user = User::create($attributes);

        return response()->json([
            'message' => "User registered successfully",
            'data' => $user
        ], 201);
    }
}
