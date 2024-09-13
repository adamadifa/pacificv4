<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ScreenController extends Controller
{
    public function store(Request $request)
    {
        // Tangkap resolusi layar dari request
        $width = $request->input('width');
        $height = $request->input('height');

        // Lakukan sesuatu dengan resolusi layar, misalnya simpan ke database atau session
        // Contoh: Simpan ke session
        session(['screen_width' => $width, 'screen_height' => $height]);

        return response()->json(['message' => 'Screen resolution saved successfully']);
    }
}
