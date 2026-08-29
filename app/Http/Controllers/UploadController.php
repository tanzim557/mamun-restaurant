<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;

class UploadController extends Controller
{
    public function image(Request $request)
    {
        if (!$request->hasFile('file')) {
            return response()->json(['error' => 'No file part in request'], 400);
        }

        $file = $request->file('file');
        if (!$file->isValid()) {
            return response()->json(['error' => 'Invalid file'], 400);
        }

        $ext = $file->getClientOriginalExtension() ?: 'jpg';
        $allowedExts = ['png', 'jpg', 'jpeg', 'gif', 'webp', 'heic', 'heif', 'bmp', 'tiff', 'svg'];

        if (!in_array(strtolower($ext), $allowedExts) && !str_starts_with($file->getMimeType(), 'image/')) {
            return response()->json(['error' => 'File type not allowed'], 400);
        }

        $filename = Str::uuid()->toString() . '.' . strtolower($ext);
        $file->move(public_path('uploads'), $filename);

        return response()->json(['success' => true, 'url' => '/uploads/' . $filename], 200);
    }
}
