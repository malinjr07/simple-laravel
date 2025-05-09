<?php

namespace App\Http\Controllers;

use App\Models\Media;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MediaController extends Controller
{
    /**
     * The storage disk to use for media files.
     *
     * @var string
     */
    protected $disk;

    /**
     * MediaController constructor.
     */
    public function __construct()
    {
        // Configure disk based on environment or config
        /* $this->disk = config('app.env') === 'production'
            ? 's3'
            : 'public'; */
        $this->disk = 'public';
    }

    /**
     * Upload an image file
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function uploadImage(Request $request)
    {
        // Validate request has Content-Type header
        if (!$request->isMethod('post') || !$request->hasHeader('Content-Type') || strpos($request->header('Content-Type'), 'multipart/form-data') === false) {
            return response()->json([
                'status' => 'error',
                'errors' => "The Content-Type header must be multipart/form-data."
            ], 422);
        }

        // Check if file was uploaded
        if (!$request->hasFile('file')) {
            return response()->json([
                'status' => 'error',
                'errors' => "No file was uploaded."
            ], 422);
        }

        $file = $request->file("file");

        // Check if file is valid
        if (!$file->isValid()) {
            return response()->json([
                'status' => 'error',
                'errors' => "File upload failed. Invalid File Uploaded."
            ], 422);
        }

        // Validate file type
        $mimeType = $file->getMimeType();
        if (strpos($mimeType, 'image/') !== 0) {
            return response()->json([
                'status' => 'error',
                'errors' => "Only image files are allowed."
            ], 422);
        }

        // Store file
        $path = $file->store("uploads/images", $this->disk);

        // Create database record
        $media = Media::create([
            'url' => asset('storage/' . $path),
            'fileName' => pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME),
            'extension' => $file->getClientOriginalExtension(),
            'mime_type' => $mimeType,
            'disk' => $this->disk,
            'path' => $path,
            'size' => $file->getSize(),
            'is_primary' => $request->boolean('is_primary', false),
            'product_id' => $request->input('product_id') // Optional product association
        ]);

        // Set as primary if requested and product_id provided
        if ($request->boolean('is_primary') && $request->input('product_id')) {
            $media->setPrimary(); // This uses the method we defined in the model
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Image uploaded successfully',
            'data' => $media
        ], 201);
    }

    /**
     * Upload a video file
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function uploadVideo(Request $request)
    {
        // Validate request has Content-Type header
        if (!$request->isMethod('post') || !$request->hasHeader('Content-Type') || strpos($request->header('Content-Type'), 'multipart/form-data') === false) {
            return response()->json([
                'status' => 'error',
                'errors' => "The Content-Type header must be multipart/form-data."
            ], 422);
        }

        // Check if file was uploaded
        if (!$request->hasFile('file')) {
            return response()->json([
                'status' => 'error',
                'errors' => "No file was uploaded."
            ], 422);
        }

        $file = $request->file("file");

        // Check if file is valid
        if (!$file->isValid()) {
            return response()->json([
                'status' => 'error',
                'errors' => "File upload failed. Invalid File Uploaded."
            ], 422);
        }

        // Validate file type
        $mimeType = $file->getMimeType();
        $videoMimeTypes = ['video/mp4', 'video/quicktime', 'video/x-msvideo', 'video/x-flv', 'video/webm'];

        if (!in_array($mimeType, $videoMimeTypes)) {
            return response()->json([
                'status' => 'error',
                'errors' => "Only video files are allowed."
            ], 422);
        }

        // Store file
        $path = $file->store("uploads/videos", $this->disk);

        // Create database record
        $media = Media::create([
            'url' => asset('storage/' . $path),
            'fileName' => pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME),
            'extension' => $file->getClientOriginalExtension(),
            'mime_type' => $mimeType,
            'disk' => $this->disk,
            'path' => $path,
            'size' => $file->getSize(),
            'is_primary' => $request->boolean('is_primary', false),
            'product_id' => $request->input('product_id') // Optional product association
        ]);

        // Set as primary if requested and product_id provided
        if ($request->boolean('is_primary') && $request->input('product_id')) {
            $media->setPrimary(); // This uses the method we defined in the model
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Video uploaded successfully',
            'data' => $media
        ], 201);
    }

    /**
     * Delete a media file
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        $media = Media::find($id);

        if (!$media) {
            return response()->json([
                'status' => 'error',
                'message' => 'Media not found'
            ], 404);
        }

        // Delete file from storage
        if ($media->disk && $media->path) {
            Storage::disk($media->disk)->delete($media->path);
        }

        // Delete database record
        $media->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Media deleted successfully'
        ]);
    }

    /**
     * Set a media as primary
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function setPrimary($id)
    {
        $media = Media::find($id);

        if (!$media) {
            return response()->json([
                'status' => 'error',
                'message' => 'Media not found'
            ], 404);
        }

        if (!$media->product_id) {
            return response()->json([
                'status' => 'error',
                'message' => 'Cannot set as primary: media not associated with any product'
            ], 422);
        }

        $media->setPrimary();

        return response()->json([
            'status' => 'success',
            'message' => 'Media set as primary',
            'data' => $media
        ]);
    }
}