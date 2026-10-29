<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Area Anggota') - Paroki Santo Petrus Tuban</title>

    {{-- Font yang sama dengan landing page, supaya area anggota tidak terasa
         seperti website yang berbeda. --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@600;700&family=Nunito+Sans:wght@400;600;700&display=swap">

    {{-- Alpine sudah di-start oleh resources/js/app.js, jadi tidak perlu CDN lagi di sini --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        [x-cloak] { display: none !important; }

        :root {
            --ink: #16233f;
            --gold: #b98a3e;
            --gold-soft: #e6cf9b;
        }

        body { font-family: 'Nunito Sans', system-ui, sans-serif; }
        .serif { font-family: 'Cormorant Garamond', Georgia, serif; }

        /* ===== NAVBAR ANGGOTA ===== */
        .member-nav .nav-link { position: relative; color: #475569; transition: color .25s ease; }
        .member-nav .nav-link::after {
            content: ''; position: absolute; left: 0; right: 0; bottom: -.55rem; height: 2px;
            background: var(--gold); transform: scaleX(0); transform-origin: left;
            transition: transform .3s cubic-bezier(.2,.7,.2,1);
        }
        .member-nav .nav-link:hover { color: var(--gold); }
        .member-nav .nav-link:hover::after { transform: scaleX(1); }
        .member-nav .nav-link.is-active { color: var(--gold); font-weight: 700; }
        .member-nav .nav-link.is-active::after { transform: scaleX(1); }

        /* tombol Keluar: bentuknya sama dengan tombol Login di landing page */
        .member-nav .nav-cta {
            background: linear-gradient(135deg, #c79646, var(--gold) 55%, #a87c33);
            color: #fff;
            box-shadow: 0 10px 22px -12px rgba(185, 138, 62, .9);
            transition: filter .25s ease, transform .25s ease;
        }
        .member-nav .nav-cta:hover { filter: brightness(1.07); transform: translateY(-1px); }
        .member-nav .nav-cta:active { transform: translateY(0); }

        .member-nav a:focus-visible, .member-nav button:focus-visible,
        .member-main a:focus-visible, .member-main button:focus-visible {
            outline: 3px solid var(--gold-soft); outline-offset: 3px;
        }

        @media (prefers-reduced-motion: reduce) {
            .member-nav .nav-link, .member-nav .nav-link::after { transition: none; }
        }
    </style>
</head>
<body class="bg-slate-50 text-slate-800 antialiased flex flex-col min-h-screen">

@php
    $menu = [
        ['route' => 'member.absensi',            'label' => 'Absensi'],
        ['route' => 'member.jadwal',             'label' => 'Jadwal Kegiatan'],
        ['route' => 'member.renungan',           'label' => 'Renungan Saya'],
        ['route' => 'member.renungan.komunitas', 'label' => 'Renungan Anggota'],
    ];

    $user = auth()->user();
@endphp

    <!-- NAVBAR ANGGOTA -->
    <nav x-data="{ open: false }" class="member-nav bg-white/92 backdrop-blur-md border-b border-slate-200 fixed w-full top-0 z-50 shadow-[0_10px_30px_-26px_rgba(22,35,63,.6)]">
        <div class="max-w-7xl mx-auto px-2 sm:px-4 lg:px-6">
            <div class="flex justify-between h-20 items-center gap-4">

                <a href="{{ route('member.absensi') }}" class="flex items-center gap-3 flex-none">
                    <img src="{{ asset('assets/images/logo.jpg') }}" alt="Santo Petrus Logo" class="w-10 h-10 rounded-full object-cover shadow-sm">
                    <span class="serif text-xl md:text-2xl font-bold text-[#16233f] leading-none">Area Anggota</span>
                </a>

                <!-- Desktop -->
                <div class="hidden md:flex items-center gap-7 font-semibold text-sm">
                    @foreach ($menu as $item)
                        <a href="{{ route($item['route']) }}"
                           class="nav-link {{ request()->routeIs($item['route']) ? 'is-active' : '' }}"
                           @if (request()->routeIs($item['route'])) aria-current="page" @endif>
                            {{ $item['label'] }}
                        </a>
                    @endforeach

                    <div class="flex items-center gap-3 pl-5 ml-1 border-l border-slate-200">
                        <span class="text-slate-600 font-semibold max-w-[10rem] truncate" title="{{ $user->name }}">{{ $user->name }}</span>

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="nav-cta px-5 py-2.5 rounded-full text-sm font-semibold">Keluar</button>
                        </form>
                    </div>
                </div>

                <!-- Hamburger (mobile) -->
                <div class="md:hidden flex items-center">
                    <button @click="open = !open" type="button" class="text-slate-600 hover:text-[#16233f] focus:outline-none p-2 rounded-lg hover:bg-slate-100 transition" aria-label="Menu">
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
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100 translate-y-0"
             x-transition:leave-end="opacity-0 -translate-y-4"
             class="md:hidden bg-white border-b border-slate-200 shadow-xl px-4 pt-2 pb-6 space-y-1">
            @foreach ($menu as $item)
                <a href="{{ route($item['route']) }}"
                   class="block px-3 py-2.5 rounded-lg text-base font-medium transition {{ request()->routeIs($item['route']) ? 'text-[#b98a3e] bg-amber-50 font-semibold' : 'text-slate-700 hover:text-[#b98a3e] hover:bg-amber-50' }}">
                    {{ $item['label'] }}
                </a>
            @endforeach

            <div class="border-t border-slate-200 pt-4 mt-3 px-3">
                <span class="text-sm font-semibold text-slate-600 truncate">{{ $user->name }}</span>
            </div>
            <form method="POST" action="{{ route('logout') }}" class="px-3 pt-3">
                @csrf
                <button type="submit" class="nav-cta block w-full px-3 py-2.5 rounded-full text-base font-semibold text-center">Keluar</button>
            </form>
        </div>
    </nav>

    <!-- KONTEN -->
    <main class="member-main flex-grow pt-28 pb-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            @if (session('success'))
                <div class="mb-6 flex items-start gap-3 p-4 bg-emerald-50 border border-emerald-200 border-l-4 border-l-emerald-500 text-emerald-800 rounded-lg text-sm">
                    <svg class="w-5 h-5 flex-none mt-px" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            @if (session('error'))
                <div class="mb-6 flex items-start gap-3 p-4 bg-red-50 border border-red-200 border-l-4 border-l-red-500 text-red-800 rounded-lg text-sm">
                    <svg class="w-5 h-5 flex-none mt-px" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span>{{ session('error') }}</span>
                </div>
            @endif

            @if ($errors->any())
                <div class="mb-6 p-4 bg-red-50 border border-red-200 border-l-4 border-l-red-500 text-red-800 rounded-lg text-sm">
                    <p class="font-semibold mb-1">Periksa kembali isian berikut:</p>
                    <ul class="list-disc pl-5 space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @yield('content')
        </div>
    </main>

    <footer class="bg-[#16233f] text-slate-400 py-8 text-center text-xs relative">
        <div class="absolute inset-x-0 top-0 h-px bg-gradient-to-r from-transparent via-[#b98a3e] to-transparent"></div>
        <p class="serif text-[#e6cf9b] text-lg font-bold mb-1">Paroki Santo Petrus Tuban</p>
        &copy; {{ date('Y') }} Paroki Santo Petrus Tuban
    </footer>
</body>
</html>
