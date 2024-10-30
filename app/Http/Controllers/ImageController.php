<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ImageController extends Controller
{
    public function convertImage(Request $request)
    {
        // Validate the request
        $request->validate([
            'url' => 'required|url',
        ]);

        $url = $request->input('url');
        $filePath = str_replace(url('/storage'), '', $url); // Adjust the URL to get the relative path
        
        // Check if the file exists on the public disk
        if (Storage::disk('public')->exists($filePath)) {
            $fileContents = Storage::disk('public')->get($filePath);
            $imageData = base64_encode($fileContents);
            $imageSize = getimagesizefromstring($fileContents);
            $imageSrc = 'data:' . $imageSize['mime'] . ';base64,' . $imageData;

            return response()->json(['base64' => $imageSrc]);
        } else {
            return response()->json(['error' => 'File not found.'], 404);
        }
    }
}
