<?php

namespace App\Http\Controllers;

use GuzzleHttp\Psr7\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ImageController extends Controller
{
    public function getImage($path)
    {
        $file = storage_path('app/public/images/' . $path);

        if (file_exists($file)) {

            $headers = [
                'Content-Type' => 'image/jpeg'
            ];

            return response()->file($file, $headers);
        } else {
            return response()->json([
                "message" => "Image not found",
                "error" => true
            ], 404);
        }
    }
}
