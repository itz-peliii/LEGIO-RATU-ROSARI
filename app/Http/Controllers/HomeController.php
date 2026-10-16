<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Setting;
use App\Models\Documentation;
use App\Models\Legio;

class HomeController extends Controller
{
    //
    public function index()
    {
        // Ambil semua setting sebagai Key-Value array agar mudah dipanggil di Blade
        $settings = Setting::pluck('value', 'key')->all();
        
        // Ambil daftar foto dokumentasi terbaru
        $documentations = Documentation::latest()->get();

        // Ambil daftar legio (terurut)
        $legios = Legio::orderBy('sort_order')->orderBy('id')->get();

        return view('landing', compact('settings', 'documentations', 'legios'));
    }
}
