<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

class AuthController extends Controller
{
    public function __construct()
    {
        // Protect all routes except 'login'
        $this->middleware('auth:jwt', ['except' => ['login']]);
        $this->middleware('jwt.refresh', ['except' => ['login', 'refresh', 'logout']]);
    }

    // Issue a JWT given valid credentials
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');
        if (!$token = auth('jwt')->attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        // Return token and metadata (type, expiration)
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth('jwt')->factory()->getTTL() * 60
        ]);
    }

    // Return authenticated user
    public function me()
    {
        return response()->json(auth('jwt')->user());
    }

    // Invalidate (logout) the token
    public function logout()
    {
        auth('jwt')->logout();
        return response()->json(['message' => 'Successfully logged out']);
    }

    // Refresh the JWT (returns a new token)
    public function refresh()
    {

        try {
            /**
             * Explanation:
             * Uses the JWTAuth facade directly
             * Explicitly retrieves the current token using JWTAuth::getToken()
             * Refreshes it with JWTAuth::refresh()
             * Has explicit error handling with try/catch
             * Returns only the new token in the response
             * Uses 'token' as the key name
             * Advantages:
             * 
             * More explicit error handling (catches JWTException)
             * Direct access to the JWT functionality
             * Simpler response structure

             */

            // $newToken = JWTAuth::refresh(JWTAuth::getToken());
            // return response()->json(['token' => $newToken]); 

            /**
             * Explanation:
             * 
             * Uses Laravel's auth helper which wraps JWT functionality
             * Implicitly gets and refreshes the token with auth('jwt')->refresh()
             * Returns comprehensive token information:
             * The new token as 'access_token'
             * The token type as 'bearer'
             * Expiration time in seconds
             * Format matches OAuth 2.0 token response standard
             * Advantages:
             * 
             * Consistent with your login method response format
             * Provides more information to the client
             * Follows OAuth 2.0 standards for token responses
             * Uses Laravel's authentication abstraction layer
             * 
             */
            return response()->json([
                'access_token' => auth('jwt')->refresh(),
                'token_type' => 'bearer',
                'expires_in' => auth('jwt')->factory()->getTTL() * 60
            ]);
        } catch (JWTException $e) {
            return response()->json(['error' => 'Token refresh failed'], 401);
        }

        /*   */
    }
}

