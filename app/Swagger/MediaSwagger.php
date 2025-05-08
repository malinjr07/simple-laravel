<?php

namespace App\Swagger;

/**
 * @OA\Tag(
 *     name="Media",
 *     description="API Endpoints for media file uploads"
 * )
 */
class MediaSwagger
{
    /**
     * @OA\Post(
     *     path="/api/upload",
     *     summary="Upload an image file",
     *     description="Upload and store an image file",
     *     operationId="uploadImage",
     *     tags={"Media"},
     *     @OA\RequestBody(
     *         required=true,
     *         description="Image file to upload",
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     description="File to upload",
     *                     property="file",
     *                     type="string",
     *                     format="binary",
     *                 ),
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Image uploaded successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="url", type="string", example="http://localhost:8000/storage/uploads/images/abcdefg.jpg"),
     *                 @OA\Property(property="path", type="string", example="uploads/images/abcdefg.jpg"),
     *                 @OA\Property(property="mime_type", type="string", example="image/jpeg"),
     *                 @OA\Property(property="extension", type="string", example="jpg"),
     *                 @OA\Property(property="disk", type="string", example="local"),
     *                 @OA\Property(property="size", type="integer", example=12345),
     *                 @OA\Property(property="name", type="string", example="profile.jpg")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="error"),
     *             @OA\Property(property="errors", type="string", example="No file was uploaded.")
     *         )
     *     )
     * )
     */
    public function uploadImage()
    {
    }

    /**
     * @OA\Post(
     *     path="/api/upload-video",
     *     summary="Upload a video file",
     *     description="Upload and store a video file",
     *     operationId="uploadVideo",
     *     tags={"Media"},
     *     @OA\RequestBody(
     *         required=true,
     *         description="Video file to upload",
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     description="File to upload",
     *                     property="file",
     *                     type="string",
     *                     format="binary",
     *                 ),
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Video uploaded successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="url", type="string", example="http://localhost:8000/storage/uploads/videos/abcdefg.mp4"),
     *                 @OA\Property(property="path", type="string", example="uploads/videos/abcdefg.mp4"),
     *                 @OA\Property(property="mime_type", type="string", example="video/mp4"),
     *                 @OA\Property(property="extension", type="string", example="mp4"),
     *                 @OA\Property(property="disk", type="string", example="local"),
     *                 @OA\Property(property="size", type="integer", example=5000000),
     *                 @OA\Property(property="name", type="string", example="tutorial.mp4")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="error"),
     *             @OA\Property(property="errors", type="string", example="Only video files are allowed.")
     *         )
     *     )
     * )
     */
    public function uploadVideo()
    {
    }
}