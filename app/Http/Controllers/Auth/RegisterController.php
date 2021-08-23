<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\auth\RegisterRequest;
use App\Models\User;

class RegisterController extends Controller
{
    public function store(RegisterRequest $registerRequest)
    {
        $user = User::create($registerRequest->validated());

        return response()->json([
            'message' => "User registered successfully",
            'data' => $user
        ], 201);
    }
}
