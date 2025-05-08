<?php

namespace App\Http\Controllers;

use App\Models\Media;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MediaController extends Controller
{
    public function upload(Request $request)
    {
        $disk = "local";

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

        $file = $request->file("file");
        $mimeType = $file->getMimeType();
        // Validate file is valid
        if (!$file->isValid()) {
            return response()->json([
                'status' => 'error',
                'errors' => "File upload failed. Invalid File Uploaded."
            ], 422);
        }

        // Handle non-image files (missing else branch)
        if (strpos($mimeType, 'image/') === 0) {
            $path = $file->store("uploads/images", $disk);


            // Fix URL construction (missing / after storage)
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
}
