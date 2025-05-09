<?php

namespace App\Http\Middleware;

use Closure;
use Exception;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

class RefreshToken
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        try {
            // Check token validity
            $user = JWTAuth::parseToken()->authenticate();
        } catch (Exception $e) {
            if ($e instanceof TokenExpiredException) {
                // Token has expired - attempt to refresh
                try {
                    $token = JWTAuth::getToken();
                    $newToken = JWTAuth::refresh($token);

                    // Set the user for this request
                    $user = JWTAuth::setToken($newToken)->toUser();
                    auth('jwt')->login($user);

                    // Continue with the request
                    $response = $next($request);

                    // Add the new token to the response headers
                    return $this->setAuthenticationHeader($response, $newToken);
                } catch (JWTException $e) {
                    return response()->json([
                        'error' => 'Token has expired and cannot be refreshed',
                        'message' => 'Please log in again to continue'
                    ], 401);
                }
            } else if ($e instanceof TokenInvalidException) {
                return response()->json(['error' => 'Token is invalid'], 401);
            } else {
                return response()->json(['error' => 'Authorization token not found'], 401);
            }
        }

        return $next($request);
    }

    /**
     * Set the authentication header for the given response.
     *
     * @param \Illuminate\Http\Response $response
     * @param string $token
     * @return \Illuminate\Http\Response
     */
    protected function setAuthenticationHeader($response, $token)
    {
        // Add new token to response headers
        $response->headers->set('Authorization', 'Bearer ' . $token);

        return $response;
    }
}