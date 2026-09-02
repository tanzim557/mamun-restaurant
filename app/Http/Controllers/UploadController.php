<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;

class UploadController extends Controller
{
    public function image(Request $request)
    {
        $allowedExts = ['jpg', 'jpeg', 'png', 'webp', 'gif'];

        // 1. Check if multipart file is uploaded (file or image)
        $file = $request->file('file') ?: $request->file('image');
        if ($file && $file->isValid()) {
            $ext = strtolower($file->getClientOriginalExtension() ?: 'jpg');
            if (!in_array($ext, $allowedExts)) {
                $ext = 'jpg';
            }
            $filename = Str::uuid()->toString() . '.' . $ext;
            $file->move(public_path('uploads'), $filename);
            return response()->json([
                'success' => true,
                'url' => '/uploads/' . $filename,
                'full_url' => url('/uploads/' . $filename)
            ], 200);
        }

        // 2. Check if Base64 encoded image string is passed
        $base64 = $request->input('image_base64') ?: $request->input('base64');
        if (!empty($base64)) {
            if (preg_match('/^data:image\/(\w+);base64,/', $base64, $type)) {
                $base64 = substr($base64, strpos($base64, ',') + 1);
                $ext = strtolower($type[1]);
                if (!in_array($ext, $allowedExts)) {
                    $ext = 'jpg';
                }
            } else {
                $ext = 'jpg';
            }

            $data = base64_decode($base64);
            if ($data !== false) {
                $filename = Str::uuid()->toString() . '.' . $ext;
                file_put_contents(public_path('uploads/' . $filename), $data);
                return response()->json([
                    'success' => true,
                    'url' => '/uploads/' . $filename,
                    'full_url' => url('/uploads/' . $filename)
                ], 200);
            }
        }

        // 3. Check if external Image URL is passed
        $url = $request->input('image_url') ?: $request->input('url');
        if (!empty($url)) {
            return response()->json([
                'success' => true,
                'url' => $url,
                'full_url' => $url
            ], 200);
        }

        return response()->json(['error' => 'No valid image provided'], 400);
    }
}
