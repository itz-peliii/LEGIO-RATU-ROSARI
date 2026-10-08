<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Setting;
use App\Models\Documentation;

class HomeController extends Controller
{
    //
    public function index()
    {
        // Ambil semua setting sebagai Key-Value array agar mudah dipanggil di Blade
        $settings = Setting::pluck('value', 'key')->all();
        
        // Ambil daftar foto dokumentasi terbaru
        $documentations = Documentation::latest()->get();

        return view('landing', compact('settings', 'documentations'));
    }
}
