<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', config('app.name', 'Gereja'))</title>

    {{-- Font untuk landing page. Dimuat di sini (bukan @import di dalam <style>)
         supaya teks tidak berkedip ganti font saat halaman dibuka. --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,500;0,600;0,700;1,500;1,600&family=Nunito+Sans:wght@400;600;700&display=swap">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    {{-- Alpine di-start oleh resources/js/app.js, jadi TIDAK perlu CDN lagi di sini.
         Kalau dropdown mobile tidak jalan setelah ini, berarti app.js kamu belum
         men-start Alpine — baru tambahkan kembali script CDN-nya. --}}

    <style>
        [x-cloak] { display: none !important; }

        /* ===== NAVBAR =====
           Di atas hero navbar dibuat transparan supaya foto hero terlihat penuh,
           lalu menjadi putih solid begitu halaman di-scroll. Pada halaman tanpa
           hero (misal halaman biasa), navbar langsung solid. */
        .site-nav {
            --gold: #b98a3e;
            --gold-soft: #e6cf9b;
            --ink: #16233f;
            transition: background-color .35s ease, border-color .35s ease, box-shadow .35s ease;
        }
        .site-nav.is-solid {
            background-color: rgba(255, 255, 255, .92);
            backdrop-filter: blur(12px);
            border-bottom-color: #e2e8f0;
            box-shadow: 0 10px 30px -24px rgba(22, 35, 63, .55);
        }
        .site-nav.is-ghost {
            background-color: transparent;
            border-bottom-color: transparent;
        }

        .site-nav .brand-name {
            font-family: 'Cormorant Garamond', Georgia, serif;
            font-weight: 700;
            letter-spacing: .01em;
            transition: color .35s ease;
        }
        .site-nav.is-solid .brand-name { color: var(--ink); }
        .site-nav.is-ghost .brand-name { color: #fff; text-shadow: 0 2px 16px rgba(0,0,0,.35); }

        .site-nav .nav-link { position: relative; transition: color .25s ease; }
        .site-nav .nav-link::after {
            content: ''; position: absolute; left: 0; right: 0; bottom: -.4rem; height: 2px;
            background: var(--gold); transform: scaleX(0); transform-origin: left;
            transition: transform .3s cubic-bezier(.2,.7,.2,1);
        }
        .site-nav .nav-link:hover::after,
        .site-nav .nav-link.is-current::after { transform: scaleX(1); }

        .site-nav.is-solid .nav-link { color: #475569; }
        .site-nav.is-solid .nav-link:hover,
        .site-nav.is-solid .nav-link.is-current { color: var(--gold); }
        .site-nav.is-ghost .nav-link { color: rgba(255,255,255,.88); }
        .site-nav.is-ghost .nav-link:hover,
        .site-nav.is-ghost .nav-link.is-current { color: var(--gold-soft); }
        .site-nav.is-ghost .nav-link::after { background: var(--gold-soft); }

        .site-nav .nav-cta {
            background: linear-gradient(135deg, #c79646, var(--gold) 55%, #a87c33);
            color: #fff;
            box-shadow: 0 10px 22px -12px rgba(185, 138, 62, .9);
            transition: filter .25s ease, transform .25s ease;
        }
        .site-nav .nav-cta:hover { filter: brightness(1.07); transform: translateY(-1px); }
        .site-nav .nav-cta:active { transform: translateY(0); }

        .site-nav .burger { transition: color .35s ease, background-color .25s ease; }
        .site-nav.is-solid .burger { color: #475569; }
        .site-nav.is-ghost .burger { color: #fff; }

        .site-nav a:focus-visible, .site-nav button:focus-visible {
            outline: 3px solid var(--gold-soft); outline-offset: 3px;
        }

        @media (prefers-reduced-motion: reduce) {
            .site-nav, .site-nav .brand-name, .site-nav .nav-link,
            .site-nav .nav-link::after, .site-nav .nav-cta, .site-nav .burger { transition: none; }
        }
    </style>
</head>
<body class="bg-slate-50 text-slate-800 antialiased font-sans flex flex-col min-h-screen">

    <!-- TOP NAVBAR -->
    <nav x-data="{ open: false, solid: true }"
         x-init="
            const hero = document.querySelector('[data-hero]');
            const sync = () => solid = !hero || window.scrollY > 60;
            sync();
            window.addEventListener('scroll', sync, { passive: true });
         "
         :class="solid || open ? 'is-solid' : 'is-ghost'"
         class="site-nav is-solid border-b fixed w-full top-0 z-50">
        <div class="max-w-7xl mx-auto px-2 sm:px-4 lg:px-6">
            <div class="flex justify-between h-20 items-center gap-6">

                <!-- Logo / Brand -->
                <a href="#hero" class="flex items-center gap-3 flex-none">
                    <img src="{{ asset('assets/images/logo.jpg') }}" alt="Santo Petrus Logo" class="w-10 h-10 rounded-full object-cover shadow-sm">
                    <span class="brand-name text-lg lg:text-xl xl:text-2xl whitespace-nowrap">Paroki Santo Petrus Tuban</span>
                </a>

                <!-- Desktop Navigation Links -->
                <div class="hidden lg:flex items-center gap-x-5 xl:gap-x-8 font-semibold text-sm whitespace-nowrap">
                    <a href="#hero" class="nav-link" data-spy>Beranda</a>
                    <a href="#about" class="nav-link" data-spy>Tentang Kami</a>
                    <a href="#vision-mission" class="nav-link" data-spy>Visi &amp; Misi</a>
                    <a href="#legio" class="nav-link" data-spy>Legio Maria</a>
                    <a href="#schedule" class="nav-link" data-spy>Jadwal Misa</a>
                    <a href="#documentation" class="nav-link" data-spy>Dokumentasi</a>

                    @auth
                        <a href="{{ route('dashboard') }}" class="nav-cta px-5 py-2.5 rounded-full">
                            {{ auth()->user()->isAdmin() ? 'Dashboard Admin' : 'Area Anggota' }}
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="nav-cta px-5 py-2.5 rounded-full">Login</a>
                    @endauth
                </div>

                <!-- Hamburger Button (Mobile Only) -->
                <div class="lg:hidden flex items-center">
                    <button @click="open = !open" type="button" class="burger focus:outline-none p-2 rounded-lg hover:bg-slate-100/60 transition" aria-label="Menu">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path x-show="!open" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                            <path x-show="open" x-cloak stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile Dropdown Menu -->
        <div x-show="open"
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 -translate-y-4"
            x-transition:enter-end="opacity-100 translate-y-0"
            x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100 translate-y-0"
            x-transition:leave-end="opacity-0 -translate-y-4"
            x-cloak
            @click.away="open = false"
            class="lg:hidden bg-white border-b border-slate-200 shadow-xl px-4 pt-2 pb-6 space-y-1">

            <a @click="open = false" href="#hero" class="block px-3 py-2.5 rounded-lg text-base font-medium text-slate-700 hover:text-[#b98a3e] hover:bg-amber-50 transition">Beranda</a>
            <a @click="open = false" href="#about" class="block px-3 py-2.5 rounded-lg text-base font-medium text-slate-700 hover:text-[#b98a3e] hover:bg-amber-50 transition">Tentang Kami</a>
            <a @click="open = false" href="#vision-mission" class="block px-3 py-2.5 rounded-lg text-base font-medium text-slate-700 hover:text-[#b98a3e] hover:bg-amber-50 transition">Visi &amp; Misi</a>
            <a @click="open = false" href="#legio" class="block px-3 py-2.5 rounded-lg text-base font-medium text-slate-700 hover:text-[#b98a3e] hover:bg-amber-50 transition">Legio Maria</a>
            <a @click="open = false" href="#schedule" class="block px-3 py-2.5 rounded-lg text-base font-medium text-slate-700 hover:text-[#b98a3e] hover:bg-amber-50 transition">Jadwal Misa</a>
            <a @click="open = false" href="#documentation" class="block px-3 py-2.5 rounded-lg text-base font-medium text-slate-700 hover:text-[#b98a3e] hover:bg-amber-50 transition">Dokumentasi</a>

            <div class="pt-3">
                @auth
                    <a href="{{ route('dashboard') }}" class="nav-cta block px-3 py-2.5 rounded-full text-base font-semibold text-center">
                        {{ auth()->user()->isAdmin() ? 'Dashboard Admin' : 'Area Anggota' }}
                    </a>
                @else
                    <a href="{{ route('login') }}" class="nav-cta block px-3 py-2.5 rounded-full text-base font-semibold text-center">Login</a>
                @endauth
            </div>
        </div>
    </nav>

    <!-- CONTENT WRAPPER -->
    <main class="flex-grow">
        @yield('content')
    </main>

    <!-- FOOTER -->
    <footer class="bg-[#16233f] text-slate-400 py-14 relative overflow-hidden">
        {{-- garis emas tipis sebagai pemisah dari konten di atasnya --}}
        <div class="absolute inset-x-0 top-0 h-px bg-gradient-to-r from-transparent via-[#b98a3e] to-transparent"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-10 mb-10">
                <div>
                    <h3 class="text-white text-2xl mb-3" style="font-family: 'Cormorant Garamond', Georgia, serif; font-weight: 700;">
                        Paroki Santo Petrus Tuban
                    </h3>
                    <p class="text-sm leading-relaxed text-slate-400 max-w-xs">
                        Wadah pelayanan dan komunitas umat dalam bertumbuh bersama.
                    </p>
                </div>
                <div>
                    <h4 class="text-[#e6cf9b] font-bold text-xs uppercase tracking-[.2em] mb-4">Navigasi Cepat</h4>
                    <ul class="space-y-2.5 text-sm">
                        <li><a href="#about" class="hover:text-white transition">Tentang Kami</a></li>
                        <li><a href="#vision-mission" class="hover:text-white transition">Visi &amp; Misi</a></li>
                        <li><a href="#legio" class="hover:text-white transition">Legio Maria</a></li>
                        <li><a href="#schedule" class="hover:text-white transition">Jadwal Misa</a></li>
                        <li><a href="#documentation" class="hover:text-white transition">Dokumentasi</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-[#e6cf9b] font-bold text-xs uppercase tracking-[.2em] mb-4">Kontak</h4>
                    <address class="text-sm text-slate-400 not-italic leading-relaxed space-y-2">
                        <p>{{ $settings['address'] ?? 'Jl. Panglima Sudirman 159, Kel. Sidomulyo RT. 02 / RW. 03, Kec. Tuban - 62312' }}</p>
                        <p>
                            <a href="mailto:{{ $settings['email'] ?? 'parokistpetrustuban@gmail.com' }}" class="hover:text-white transition">
                                {{ $settings['email'] ?? 'parokistpetrustuban@gmail.com' }}
                            </a>
                        </p>
                        <p>
                            <a href="tel:{{ preg_replace('/\D/', '', $settings['phone'] ?? '085231835270') }}" class="hover:text-white transition">
                                {{ $settings['phone'] ?? '085231835270' }}
                            </a>
                        </p>
                    </address>
                </div>
            </div>
            <div class="border-t border-white/10 pt-6 text-center text-xs text-slate-500">
                &copy; {{ date('Y') }} Paroki Santo Petrus Tuban. All rights reserved.
            </div>
        </div>
    </footer>

    <script>
    // Menandai menu navbar sesuai section yang sedang dilihat.
    (function () {
        var links = Array.prototype.slice.call(document.querySelectorAll('.site-nav [data-spy]'));
        if (!links.length || !('IntersectionObserver' in window)) return;

        var map = {};
        links.forEach(function (a) {
            var el = document.querySelector(a.getAttribute('href'));
            if (el) { map[el.id] = a; }
        });

        var ids = Object.keys(map);
        if (!ids.length) return;

        var io = new IntersectionObserver(function (entries) {
            entries.forEach(function (e) {
                if (!e.isIntersecting) return;
                links.forEach(function (a) { a.classList.remove('is-current'); });
                map[e.target.id].classList.add('is-current');
            });
        }, { rootMargin: '-45% 0px -50% 0px' });

        ids.forEach(function (id) { io.observe(document.getElementById(id)); });
    })();
    </script>

</body>
</html>