<?php

namespace App\Swagger;

/**
 * @OA\Info(
 *     version="1.0.0",
 *     title="Book Library API",
 *     description="API documentation for the Book Library",
 *     @OA\Contact(
 *         email="malinjr07@gmail.com"
 *     ),
 *     @OA\License(
 *         name="MIT",
 *         url="https://opensource.org/licenses/MIT"
 *     )
 * )
 * 
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
 */
class OpenApiDefinitions
{
}