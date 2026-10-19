<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Area Anggota') - Paroki Santo Petrus Tuban</title>

    {{-- Alpine sudah di-start oleh resources/js/app.js, jadi tidak perlu CDN lagi di sini --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>[x-cloak] { display: none !important; }</style>
</head>
<body class="bg-slate-50 text-slate-800 antialiased font-sans flex flex-col min-h-screen">

@php
    $menu = [
        ['route' => 'member.absensi', 'label' => 'Absensi'],
        ['route' => 'member.jadwal',  'label' => 'Jadwal Kegiatan'],
        ['route' => 'member.renungan', 'label' => 'Renungan Harian'],
    ];
@endphp

    <!-- NAVBAR ANGGOTA -->
    <nav x-data="{ open: false }" class="bg-white/90 backdrop-blur-md border-b border-slate-200 fixed w-full top-0 z-50">
        <div class="max-w-7xl mx-auto px-2 sm:px-4 lg:px-6">
            <div class="flex justify-between h-20 items-center">

                <a href="{{ route('member.absensi') }}" class="flex items-center gap-3">
                    <img src="{{ asset('assets/images/logo.jpg') }}" alt="Santo Petrus Logo" class="w-10 h-10 rounded-full object-cover shadow-sm">
                    <span class="font-bold text-lg md:text-xl text-slate-900 tracking-tight">Area Anggota</span>
                </a>

                <!-- Desktop -->
                <div class="hidden md:flex items-center space-x-8 font-medium text-sm">
                    @foreach ($menu as $item)
                        <a href="{{ route($item['route']) }}"
                           class="transition {{ request()->routeIs($item['route']) ? 'text-indigo-600 font-semibold border-b-2 border-indigo-600 pb-1' : 'text-slate-600 hover:text-indigo-600' }}">
                            {{ $item['label'] }}
                        </a>
                    @endforeach

                    <span class="text-slate-300">|</span>
                    <span class="text-slate-500">{{ auth()->user()->name }}</span>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="text-red-600 hover:text-red-800 transition">Logout</button>
                    </form>
                </div>

                <!-- Hamburger (mobile) -->
                <div class="md:hidden flex items-center">
                    <button @click="open = !open" type="button" class="text-slate-600 hover:text-slate-900 focus:outline-none p-2 rounded-lg hover:bg-slate-100 transition" aria-label="Menu">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path x-show="!open" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                            <path x-show="open" x-cloak stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile dropdown -->
        <div x-show="open" x-cloak @click.away="open = false"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 -translate-y-4"
             x-transition:enter-end="opacity-100 translate-y-0"
             class="md:hidden bg-white border-b border-slate-200 shadow-xl px-4 pt-2 pb-6 space-y-2">
            @foreach ($menu as $item)
                <a href="{{ route($item['route']) }}"
                   class="block px-3 py-2.5 rounded-lg text-base font-medium transition {{ request()->routeIs($item['route']) ? 'text-indigo-600 bg-indigo-50' : 'text-slate-700 hover:text-indigo-600 hover:bg-indigo-50' }}">
                    {{ $item['label'] }}
                </a>
            @endforeach

            <div class="border-t border-slate-200 pt-3 mt-3 px-3 text-sm text-slate-500">{{ auth()->user()->name }}</div>
            <form method="POST" action="{{ route('logout') }}" class="px-3">
                @csrf
                <button type="submit" class="text-red-600 hover:text-red-800 font-medium">Logout</button>
            </form>
        </div>
    </nav>

    <!-- KONTEN -->
    <main class="flex-grow pt-28 pb-16">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">

            @if (session('success'))
                <div class="mb-6 p-4 bg-emerald-100 border-l-4 border-emerald-500 text-emerald-800 rounded shadow-sm text-sm">
                    {{ session('success') }}
                </div>
            @endif
            @if (session('error'))
                <div class="mb-6 p-4 bg-red-100 border-l-4 border-red-500 text-red-800 rounded shadow-sm text-sm">
                    {{ session('error') }}
                </div>
            @endif

            @yield('content')
        </div>
    </main>

    <footer class="bg-slate-900 text-slate-500 py-6 text-center text-xs">
        &copy; {{ date('Y') }} Paroki Santo Petrus Tuban
    </footer>
</body>
</html>
