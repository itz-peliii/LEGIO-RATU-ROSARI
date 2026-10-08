<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'Gereja') }}</title>
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- Alpine.js untuk Hamburger Menu Mobile Dropdown -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="bg-slate-50 text-slate-800 antialiased font-sans flex flex-col min-h-screen">

    <!-- TOP NAVBAR -->
    <nav x-data="{ open: false }" class="bg-white/90 backdrop-blur-md border-b border-slate-200 fixed w-full top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-20 items-center">
                
                <!-- Logo / Brand -->
                <a href="#hero" class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-indigo-600 flex items-center justify-center text-white font-bold text-xl shadow-md">
                        G
                    </div>
                    <span class="font-bold text-xl text-slate-900 tracking-tight">Gereja Website</span>
                </a>

                <!-- Desktop Navigation Links -->
                <div class="hidden md:flex items-center space-x-8 font-medium text-sm text-slate-600">
                    <a href="#hero" class="hover:text-indigo-600 transition">Beranda</a>
                    <a href="#about" class="hover:text-indigo-600 transition">Tentang Kami</a>
                    <a href="#vision-mission" class="hover:text-indigo-600 transition">Visi & Misi</a>
                    <a href="#documentation" class="hover:text-indigo-600 transition">Dokumentasi</a>
                </div>

                <!-- Hamburger Button (Mobile Only) -->
                <div class="md:hidden flex items-center">
                    <button @click="open = !open" type="button" class="text-slate-600 hover:text-slate-900 focus:outline-none p-2 rounded-lg hover:bg-slate-100 transition">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path x-show="!open" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                            <path x-show="open" x-cloak stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile Dropdown Menu (Top-Down Animation) -->
        <div x-show="open" 
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 -translate-y-4"
             x-transition:enter-end="opacity-100 translate-y-0"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100 translate-y-0"
             x-transition:leave-end="opacity-0 -translate-y-4"
             x-cloak
             @click.away="open = false" 
             class="md:hidden bg-white border-b border-slate-200 shadow-xl px-4 pt-2 pb-6 space-y-3">
            
            <a @click="open = false" href="#hero" class="block px-3 py-2.5 rounded-lg text-base font-medium text-slate-700 hover:text-indigo-600 hover:bg-indigo-50 transition">Beranda</a>
            <a @click="open = false" href="#about" class="block px-3 py-2.5 rounded-lg text-base font-medium text-slate-700 hover:text-indigo-600 hover:bg-indigo-50 transition">Tentang Kami</a>
            <a @click="open = false" href="#vision-mission" class="block px-3 py-2.5 rounded-lg text-base font-medium text-slate-700 hover:text-indigo-600 hover:bg-indigo-50 transition">Visi & Misi</a>
            <a @click="open = false" href="#documentation" class="block px-3 py-2.5 rounded-lg text-base font-medium text-slate-700 hover:text-indigo-600 hover:bg-indigo-50 transition">Dokumentasi</a>
        </div>
    </nav>

    <!-- CONTENT WRAPPER -->
    <main class="flex-grow">
        @yield('content')
    </main>

    <!-- FOOTER -->
    <footer class="bg-slate-900 text-slate-400 py-12 border-t border-slate-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-8">
                <div>
                    <h3 class="text-white text-lg font-bold mb-3">Gereja Website</h3>
                    <p class="text-sm leading-relaxed text-slate-400">
                        Wadah pelayanan dan komunitas umat dalam bertumbuh bersama.
                    </p>
                </div>
                <div>
                    <h4 class="text-white font-semibold mb-3">Navigasi Cepat</h4>
                    <ul class="space-y-2 text-sm">
                        <li><a href="#about" class="hover:text-white transition">Tentang Kami</a></li>
                        <li><a href="#vision-mission" class="hover:text-white transition">Visi & Misi</a></li>
                        <li><a href="#documentation" class="hover:text-white transition">Dokumentasi</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-white font-semibold mb-3">Kontak</h4>
                    <p class="text-sm text-slate-400">
                        {{ $settings['address'] ?? 'Jl. Gereja No. 123' }}<br>
                        Email: {{ $settings['email'] ?? 'info@gereja.com' }}<br>
                        Telepon: {{ $settings['phone'] ?? '+62 812-3456-7890' }}
                    </p>
                </div>
            </div>
            <div class="border-t border-slate-800 pt-6 text-center text-xs text-slate-500">
                &copy; {{ date('Y') }} Website Organisasi Gereja. All rights reserved.
            </div>
        </div>
    </footer>

</body>
</html>