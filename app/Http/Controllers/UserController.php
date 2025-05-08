<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use OpenApi\Annotations as OA;

/**
 * @OA\Tag(
 *     name="Users",
 *     description="API Endpoints for managing users"
 * )
 * 
 * @OA\SecurityScheme(
 *     type="http",
 *     description="Login with email and password via /api/sign-in to get the authentication token. Then enter 'Bearer {token}' below.",
 *     name="Bearer Token",
 *     in="header",
 *     scheme="bearer",
 *     bearerFormat="JWT",
 *     securityScheme="bearerAuth",
 * )
 * 
 */
class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     * 
     * @OA\Get(
     *     path="/api/users",
     *     summary="Get all users",
     *     description="Returns a list of all users",
     *     tags={"Users"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(
     *                 type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="name", type="string", example="John Doe"),
     *                 @OA\Property(property="email", type="string", example="john@example.com"),
     *                 @OA\Property(property="created_at", type="string", format="date-time"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized - Missing or invalid authentication token"
     *     )
     * )
     */
    public function index()
    {
        return response()->json(User::all());
    }

    /**
     * Display the specified resource.
     * 
     * @OA\Get(
     *     path="/api/users/{id}",
     *     summary="Get user by ID",
     *     description="Returns a specific user by ID",
     *     tags={"Users"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="User ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="User found",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="id", type="integer", example=1),
     *             @OA\Property(property="name", type="string", example="John Doe"),
     *             @OA\Property(property="email", type="string", example="john@example.com"),
     *             @OA\Property(property="created_at", type="string", format="date-time"),
     *             @OA\Property(property="updated_at", type="string", format="date-time")
     *         )
     *     ),
     *     @OA\Response(response=401, description="Unauthorized"),
     *     @OA\Response(response=404, description="User not found")
     * )
     */
    public function show(User $user)
    {
        return response()->json($user);
    }

    /**
     * Remove the specified resource from storage.
     * 
     * @OA\Delete(
     *     path="/api/users/{id}",
     *     summary="Delete a user",
     *     description="Deletes a user by ID",
     *     tags={"Users"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="User ID to delete",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="User deleted successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="User deleted")
     *         )
     *     ),
     *     @OA\Response(response=401, description="Unauthorized"),
     *     @OA\Response(response=404, description="User not found")
     * )
     */
    public function destroy(User $user)
    {
        $user->delete();
        return response()->json(['message' => 'User deleted']);
    }
}
