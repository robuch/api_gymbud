<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
class LoginController extends Controller
{
    /**
     * Handle the incoming request for user login.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function __invoke(Request $request)
    {
        // Validate the request data
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // Attempt to authenticate the user
        if (!auth()->attempt($credentials)) {
            // Return error response if authentication fails
            return response()->json(['message' => 'Email or password is incorrect'], 401);
        }

        // Get the authenticated user
        $user = auth()->user();

        // Create a token for the user and return it as a JSON response
        $token = $user->createToken('auth_token')->plainTextToken;

        // Create a token for the user and return it as a JSON response
        return response()->json([
            'message' => 'Login successful',
            'user' => ['id' => $user->id, 'name' => $user->name, 'email' => $user->email],
            'token' => $token,
            200
        ]);
    }
}
