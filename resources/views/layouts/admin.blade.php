<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Website Gereja</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- Trix Editor CDN -->
    <link rel="stylesheet" type="text/css" href="https://unpkg.com/trix@2.0.8/dist/trix.css">
    <script type="text/javascript" src="https://unpkg.com/trix@2.0.8/dist/trix.umd.min.js"></script>
    <style>
        trix-toolbar [data-trix-button-group="file-tools"] { display: none; }
    </style>
</head>
<body class="bg-gray-100 font-sans antialiased">
@php
    // Jumlah renungan yang menunggu persetujuan, untuk lencana di sidebar.
    $renunganMenunggu = \App\Models\Renungan::where('status', 'pending')->count();

    $judulHalaman = match (true) {
        request()->routeIs('admin.preview')     => 'Preview Website',
        request()->routeIs('admin.renungan.*')  => 'Moderasi Renungan',
        default                                 => 'Edit Konten Website',
    };
@endphp

    <div class="min-h-screen flex">
        
        <!-- SIDEBAR -->
        <aside class="w-64 bg-slate-900 text-white flex-shrink-0">
            <div class="p-6 border-b border-slate-800">
                <h1 class="text-xl font-bold tracking-wide">CMS Gereja</h1>
                <p class="text-xs text-slate-400 mt-1">Panel Administrasi</p>
            </div>
            
            <nav class="mt-6 px-4 space-y-2">
                <!-- Menu 1: Edit Website -->
                <a href="{{ route('admin.dashboard') }}" 
                   class="flex items-center gap-3 px-4 py-3 rounded-lg text-sm font-medium transition {{ request()->routeIs('admin.dashboard') ? 'bg-indigo-600 text-white shadow-md' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                              d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                    Edit Website
                </a>

                <!-- Menu 2: Moderasi Renungan -->
                <a href="{{ route('admin.renungan.index') }}" 
                   class="flex items-center gap-3 px-4 py-3 rounded-lg text-sm font-medium transition {{ request()->routeIs('admin.renungan.*') ? 'bg-indigo-600 text-white shadow-md' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                    <svg class="w-5 h-5 flex-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                              d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                    </svg>
                    <span class="flex-1">Moderasi Renungan</span>
                    @if ($renunganMenunggu > 0)
                        <span class="flex-none text-[11px] font-bold px-2 py-0.5 rounded-full bg-amber-400 text-slate-900">
                            {{ $renunganMenunggu }}
                        </span>
                    @endif
                </a>

                <!-- Menu 3: Lihat Website (Preview) -->
                <a href="{{ route('admin.preview') }}" 
                   class="flex items-center gap-3 px-4 py-3 rounded-lg text-sm font-medium transition {{ request()->routeIs('admin.preview') ? 'bg-indigo-600 text-white shadow-md' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                              d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                              d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                    </svg>
                    Lihat Website
                </a>
            </nav>
        </aside>

        <!-- MAIN CONTENT AREA -->
        <main class="flex-1 flex flex-col h-screen overflow-hidden">
            <!-- Top Navbar Header -->
            <header class="bg-white border-b border-gray-200 px-8 py-4 flex justify-between items-center flex-shrink-0">
                <div class="flex items-center gap-4">
                    <h2 class="text-lg font-semibold text-gray-800">{{ $judulHalaman }}</h2>
                    
                    <!-- Tombol Buka Website Publik di Tab Baru -->
                    <a href="{{ route('home') }}" target="_blank" 
                       class="inline-flex items-center gap-1.5 text-xs text-indigo-600 hover:text-indigo-800 font-medium bg-indigo-50 px-3 py-1.5 rounded-lg border border-indigo-100 transition">
                        <span>Buka Website Utama</span>
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                        </svg>
                    </a>
                </div>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="text-sm font-medium text-red-600 hover:text-red-800 transition">
                        Logout
                    </button>
                </form>
            </header>

            <!-- Main Workspace Area -->
            <div class="flex-1 overflow-y-auto p-8">
                @if(session('success'))
                    <div class="mb-6 p-4 bg-emerald-100 border-l-4 border-emerald-500 text-emerald-800 rounded shadow-sm text-sm">
                        {{ session('success') }}
                    </div>
                @endif

                @yield('content')
            </div>
        </main>
    </div>
</body>
</html>