<?php

namespace App\Http\Controllers;

use App\Models\Media;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MediaController extends Controller
{
    public function uploadImage(Request $request)
    {
        $disk = "local";

        // Validate request has Content-Type header
        if (!$request->isMethod('post') || !$request->hasHeader('Content-Type') || strpos($request->header('Content-Type'), 'multipart/form-data') === false) {
            return response()->json([
                'status' => 'error',
                'errors' => "The Content-Type header must be multipart/form-data."
            ], 422);
        }

        if (!$request->hasFile('file')) {
            return response()->json([
                'status' => 'error',
                'errors' => "No file was uploaded."
            ], 422);
        }

        $file = $request->file("file");
        $mimeType = $file->getMimeType();

        if (!$file->isValid()) {
            return response()->json([
                'status' => 'error',
                'errors' => "File upload failed. Invalid File Uploaded."
            ], 422);
        }


        if (strpos($mimeType, 'image/') !== 0) {
            return response()->json([
                'status' => 'error',
                'errors' => "Only image files are allowed."
            ], 422);
        }
        $path = $file->store("uploads/images", $disk);



        $url = asset('storage/' . $path);
        $mediaFile = [
            'url' => $url,
            "path" => $path,
            "mime_type" => $mimeType,
            "extension" => $file->getClientOriginalExtension(),
            "disk" => $disk,
            "size" => $file->getSize(),
            "name" => $file->getClientOriginalName(),
        ];

        $image = Media::create($mediaFile);

        if (!$image) {
            return response()->json([
                'status' => 'error',
                'errors' => "Failed to upload image."
            ], 422);
        }

        return response()->json([
            'status' => 'success',
            'data' => array_merge(['id' => $image->id], $mediaFile)

        ], 201);
    }

    public function uploadVideo(Request $request)
    {
        $disk = "local";
        $file = $request->file('file');

        // Validate request has Content-Type header
        if (!$request->isMethod('post') || !$request->hasHeader('Content-Type') || strpos($request->header('Content-Type'), 'multipart/form-data') === false) {
            return response()->json([
                'status' => 'error',
                'errors' => "The Content-Type header must be multipart/form-data."
            ], 422);
        }

        // Validate file presence
        if (!$request->hasFile('file')) {
            return response()->json([
                'status' => 'error',
                'errors' => "No file was uploaded."
            ], 422);
        }
        $mimeType = $file->getMimeType();
        // Validate file is valid
        if (!$file->isValid()) {
            return response()->json([
                'status' => 'error',
                'errors' => "File upload failed. Invalid File Uploaded."
            ], 422);
        }

        // Handle non-video files (missing else branch)
        if (strpos($mimeType, 'video/') !== 0) {
            return response()->json([
                'status' => 'error',
                'errors' => "Only video files are allowed."
            ], 422);
        }
        $path = $file->store("uploads/videos", $disk);

        $url = asset('storage/' . $path);
        $mediaFile = [
            'url' => $url,
            "path" => $path,
            "mime_type" => $mimeType,
            "extension" => $file->getClientOriginalExtension(),
            "disk" => $disk,
            "size" => $file->getSize(),
            "name" => $file->getClientOriginalName(),
        ];

        $image = Media::create($mediaFile);

        if (!$image) {
            return response()->json([
                'status' => 'error',
                'errors' => "Failed to upload image."
            ], 422);
        }

        return response()->json([
            'status' => 'success',
            'data' => array_merge(['id' => $image->id], $mediaFile)

        ], 201);
    }

}
