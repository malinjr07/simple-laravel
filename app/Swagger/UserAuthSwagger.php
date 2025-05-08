<?php

namespace App\Swagger;

/**
 * @OA\Tag(
 *     name="Authentication",
 *     description="API Endpoints for user authentication"
 * )
 * 
 */
class UserAuthSwagger
{
    /**
     * @OA\Post(
     *     path="/api/sign-in",
     *     summary="Sign in a user",
     *     description="Authenticates a user and returns an access token",
     *     operationId="signIn",
     *     tags={"Authentication"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"emailAddress","password"},
     *             @OA\Property(property="emailAddress", type="string", format="email", example="user@example.com"),
     *             @OA\Property(property="password", type="string", format="password", example="password123")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful authentication",
     *         @OA\JsonContent(
     *             @OA\Property(property="user", type="object", 
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="name", type="string", example="John Doe"),
     *                 @OA\Property(property="email", type="string", example="user@example.com"),
     *                 @OA\Property(property="created_at", type="string", format="date-time"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time")
     *             ),
     *             @OA\Property(property="token", type="string", example="1|abcdefghijklmnopqrstuvwxyz123456789")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error or invalid credentials",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="The provided credentials are incorrect."),
     *             @OA\Property(property="errors", type="object", 
     *                 @OA\Property(property="email", type="array", @OA\Items(type="string", example="Invalid credentials."))
     *             )
     *         )
     *     )
     * )
     */
    public function signIn()
    {
    }

    /**
     * @OA\Post(
     *     path="/api/sign-up",
     *     summary="Register a new user",
     *     description="Creates a new user account and returns an access token",
     *     operationId="signUp",
     *     tags={"Authentication"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"firstName","emailAddress","password","password_confirmation"},
     *             @OA\Property(property="firstName", type="string", example="John"),
     *             @OA\Property(property="lastName", type="string", example="Doe"),
     *             @OA\Property(property="emailAddress", type="string", format="email", example="user@example.com"),
     *             @OA\Property(property="password", type="string", format="password", example="password123"),
     *             @OA\Property(property="password_confirmation", type="string", format="password", example="password123")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="User registered successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="User signed up successfully"),
     *             @OA\Property(property="user", type="object", 
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="name", type="string", example="John Doe"),
     *                 @OA\Property(property="email", type="string", example="user@example.com"),
     *                 @OA\Property(property="created_at", type="string", format="date-time"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time")
     *             ),
     *             @OA\Property(property="token", type="string", example="1|abcdefghijklmnopqrstuvwxyz123456789")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="error"),
     *             @OA\Property(property="errors", type="object", 
     *                 @OA\Property(property="emailAddress", type="array", @OA\Items(type="string", example="The email address has already been taken.")),
     *                 @OA\Property(property="password", type="array", @OA\Items(type="string", example="The password confirmation does not match."))
     *             )
     *         )
     *     )
     * )
     */
    public function signUp()
    {
    }

    /**
     * @OA\Post(
     *     path="/api/logout",
     *     summary="Sign out the authenticated user",
     *     description="Revokes all access tokens for the authenticated user",
     *     operationId="logout",
     *     tags={"Authentication"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Successfully logged out",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Logged out")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthenticated",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Unauthenticated")
     *         )
     *     )
     * )
     */
    public function logout()
    {
    }
}