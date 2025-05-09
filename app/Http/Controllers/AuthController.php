<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class AuthController extends Controller
{
    public function __construct()
    {
        // Protect all routes except 'login'
        $this->middleware('auth:api', ['except' => ['login']]);
    }

    // Issue a JWT given valid credentials
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');
        if (!$token = auth()->attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        // Return token and metadata (type, expiration)
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60
        ]);
    }

    // Return authenticated user
    public function me()
    {
        return response()->json(auth()->user());
    }

    // Invalidate (logout) the token
    public function logout()
    {
        auth()->logout();
        return response()->json(['message' => 'Successfully logged out']);
    }

    // Refresh the JWT (returns a new token)
    public function refresh()
    {
        return response()->json([
            'access_token' => auth()->refresh(),
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60
        ]);
    }
}

