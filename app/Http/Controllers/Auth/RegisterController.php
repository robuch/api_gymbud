<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

class RegisterController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    { {
            $request->validate([
                'name' => 'required|string',
                'email' => 'required|email|unique:users',
                'password' => 'required|string',
                'role_id' => 'required|exists:roles,id',
                'phone' => 'required|string',
                'gender' => 'required|integer|between:1,2',
            ]);

            $user = User::create($request->all());

            $token = $user->createToken('auth_token')->plainTextToken;

            return response()->json([
                'message' => 'User created successfully',
                'token' => $token,
                'user' => $user->only(['id', 'name', 'email',]),
                201
            ]);
        }
    }
}
