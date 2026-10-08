<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Website Gereja</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- Trix Editor CDN (Bisa disesuaikan dengan Quill / Summernote / TinyMCE) -->
    <link rel="stylesheet" type="text/css" href="https://unpkg.com/trix@2.0.8/dist/trix.css">
    <script type="text/javascript" src="https://unpkg.com/trix@2.0.8/dist/trix.umd.min.js"></script>
    <style>
        trix-toolbar [data-trix-button-group="file-tools"] { display: none; }
    </style>
</head>
<body class="bg-gray-100 font-sans antialiased">
    <div class="min-h-screen flex">
        
        <!-- SIDEBAR -->
        <aside class="w-64 bg-slate-900 text-white flex-shrink-0">
            <div class="p-6 border-b border-slate-800">
                <h1 class="text-xl font-bold tracking-wide">CMS Gereja</h1>
                <p class="text-xs text-slate-400 mt-1">Panel Administrasi</p>
            </div>
            <nav class="mt-6 px-4">
                <a href="{{ route('admin.dashboard') }}" 
                   class="flex items-center gap-3 px-4 py-3 rounded-lg text-sm font-medium transition bg-indigo-600 text-white shadow-md">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                              d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                    Edit Website
                </a>
            </nav>
        </aside>

        <!-- MAIN CONTENT AREA -->
        <main class="flex-1 flex flex-col overflow-y-auto">
            <!-- Top Navbar Header -->
            <header class="bg-white border-b border-gray-200 px-8 py-4 flex justify-between items-center">
                <h2 class="text-lg font-semibold text-gray-800">Edit Konten Website</h2>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="text-sm font-medium text-red-600 hover:text-red-800 transition">
                        Logout
                    </button>
                </form>
            </header>

            <!-- Main Workspace -->
            <div class="p-8 space-y-8">
                @if(session('success'))
                    <div class="p-4 bg-emerald-100 border-l-4 border-emerald-500 text-emerald-800 rounded shadow-sm text-sm">
                        {{ session('success') }}
                    </div>
                @endif

                @yield('content')
            </div>
        </main>
    </div>
</body>
</html>