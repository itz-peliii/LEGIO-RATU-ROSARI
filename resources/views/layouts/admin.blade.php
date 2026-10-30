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

    <!-- Font senada dengan website depan -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@600;700&family=Nunito+Sans:wght@400;600;700;800&display=swap">

    <style>
        trix-toolbar [data-trix-button-group="file-tools"] {
            display: none;
        }

        :root {
            --ink: #16233f;
            --ink-2: #1b2c4e;
            --ink-3: #24375f;
            --gold: #b98a3e;
            --gold-soft: #e6cf9b;
            --gold-bg: #fbf6ea;
            --paper: #eef2f8;
            --line: #e4e9f2;
            --line-strong: #d3dae7;
            --text: #2b3550;
            --muted: #6b7793;
            --display: 'Cormorant Garamond', Georgia, serif;
            --body: 'Nunito Sans', system-ui, sans-serif;
        }

        body.cms {
            margin: 0;
            font-family: var(--body);
            color: var(--text);
            background: var(--paper);
        }

        .cms *,
        .cms *::before,
        .cms *::after {
            box-sizing: border-box;
        }

        /* ---------- Kerangka ---------- */
        .cms-shell {
            min-height: 100vh;
            display: flex;
        }

        .cms-main {
            flex: 1;
            display: flex;
            flex-direction: column;
            height: 100vh;
            overflow: hidden;
        }

        /* ---------- Sidebar ---------- */
        .cms-aside {
            width: 16rem;
            flex: none;
            position: relative;
            color: #fff;
            background: linear-gradient(185deg, var(--ink) 0%, #101a30 100%);
        }

        .cms-aside::before {
            /* garis emas tipis di tepi kanan */
            content: '';
            position: absolute;
            top: 0;
            bottom: 0;
            right: 0;
            width: 1px;
            background: linear-gradient(to bottom, transparent, rgba(230, 207, 155, .5), transparent);
        }

        .cms-aside::after {
            /* watermark salib samar di bawah */
            content: '';
            position: absolute;
            right: -1.5rem;
            bottom: -1rem;
            width: 11rem;
            height: 14rem;
            background: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 32'%3E%3Cpath fill='%23e6cf9b' d='M10 0h4v9h8v4h-8v19h-4V13H2V9h8z'/%3E%3C/svg%3E") no-repeat center/contain;
            opacity: .05;
            pointer-events: none;
        }

        .cms-brand {
            display: flex;
            align-items: center;
            gap: .85rem;
            padding: 1.5rem 1.4rem;
            border-bottom: 1px solid rgba(255, 255, 255, .08);
        }

        .cms-brand .emblem {
            width: 2.6rem;
            height: 2.6rem;
            border-radius: 12px;
            flex: none;
            display: grid;
            place-items: center;
            background: rgba(230, 207, 155, .12);
            border: 1px solid rgba(230, 207, 155, .5);
            color: var(--gold-soft);
        }

        .cms-brand .emblem svg {
            width: 1.1rem;
        }

        .cms-brand h1 {
            font-family: var(--display);
            font-size: 1.5rem;
            font-weight: 700;
            line-height: 1;
            letter-spacing: .01em;
        }

        .cms-brand p {
            font-size: .66rem;
            letter-spacing: .2em;
            text-transform: uppercase;
            color: var(--gold-soft);
            margin-top: .3rem;
            opacity: .9;
        }

        .cms-nav {
            padding: 1.25rem .85rem;
            display: flex;
            flex-direction: column;
            gap: .35rem;
            position: relative;
            z-index: 1;
        }

        .cms-navlabel {
            font-size: .64rem;
            letter-spacing: .2em;
            text-transform: uppercase;
            color: rgba(255, 255, 255, .35);
            padding: .4rem 1rem .5rem;
            font-weight: 800;
        }

        .cms-link {
            position: relative;
            display: flex;
            align-items: center;
            gap: .85rem;
            padding: .78rem 1rem;
            border-radius: 12px;
            text-decoration: none;
            font-size: .9rem;
            font-weight: 600;
            color: rgba(255, 255, 255, .72);
            transition: background .2s, color .2s;
        }

        .cms-link svg {
            width: 1.25rem;
            height: 1.25rem;
            flex: none;
        }

        .cms-link:hover {
            background: rgba(255, 255, 255, .07);
            color: #fff;
        }

        .cms-link.active {
            background: rgba(230, 207, 155, .14);
            color: var(--gold-soft);
        }

        .cms-link.active::before {
            content: '';
            position: absolute;
            left: 0;
            top: .6rem;
            bottom: .6rem;
            width: 3px;
            border-radius: 0 3px 3px 0;
            background: var(--gold);
        }

        .cms-link .label {
            flex: 1;
        }

        .cms-badge {
            flex: none;
            font-size: 11px;
            font-weight: 800;
            padding: .12rem .5rem;
            border-radius: 999px;
            background: var(--gold);
            color: var(--ink);
        }

        /* ---------- Header ---------- */
        .cms-header {
            position: relative;
            flex: none;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
            background: #fff;
            border-bottom: 1px solid var(--line);
            padding: .95rem 2rem;
        }

        .cms-header::after {
            content: '';
            position: absolute;
            left: 0;
            bottom: -1px;
            width: 12rem;
            height: 2px;
            background: linear-gradient(90deg, var(--gold), transparent);
        }

        .cms-head-left {
            display: flex;
            align-items: center;
            gap: 1rem;
            min-width: 0;
        }

        .cms-title {
            font-family: var(--display);
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--ink);
            line-height: 1;
        }

        .cms-open {
            display: inline-flex;
            align-items: center;
            gap: .4rem;
            white-space: nowrap;
            font-size: .78rem;
            font-weight: 700;
            color: var(--ink);
            text-decoration: none;
            background: var(--gold-bg);
            border: 1px solid var(--gold-soft);
            padding: .45rem .85rem;
            border-radius: 999px;
            transition: background .2s, border-color .2s;
        }

        .cms-open:hover {
            background: #f5ead0;
            border-color: var(--gold);
        }

        .cms-open svg {
            width: .85rem;
            height: .85rem;
        }

        .cms-logout {
            font-family: var(--body);
            font-size: .82rem;
            font-weight: 700;
            color: #b23b32;
            background: none;
            border: 0;
            cursor: pointer;
            padding: .4rem .2rem;
            transition: color .2s;
        }

        .cms-logout:hover {
            color: #8e2a20;
        }

        /* ---------- Workspace (latar bertekstur) ---------- */
        .cms-workspace {
            flex: 1;
            overflow-y: auto;
            padding: 2rem;
            position: relative;
            background-color: var(--paper);
            background-image:
                radial-gradient(rgba(22, 35, 63, .045) 1px, transparent 1.6px),
                radial-gradient(1100px 520px at 100% -8%, rgba(230, 207, 155, .14), transparent 60%),
                radial-gradient(900px 540px at -8% 108%, rgba(22, 35, 63, .05), transparent 60%),
                url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 32'%3E%3Cpath fill='%2316233f' fill-opacity='0.04' d='M10 0h4v9h8v4h-8v19h-4V13H2V9h8z'/%3E%3C/svg%3E");
            background-repeat: repeat, no-repeat, no-repeat, no-repeat;
            background-position: 0 0, 0 0, 0 0, right 4% top 7%;
            background-size: 26px 26px, 100% 100%, 100% 100%, 13rem auto;
            background-attachment: local, fixed, fixed, fixed;
        }

        .cms-content {
            position: relative;
            z-index: 1;
        }

        .cms-flash {
            display: flex;
            align-items: center;
            gap: .65rem;
            margin-bottom: 1.5rem;
            padding: .95rem 1.2rem;
            border-radius: 12px;
            background: #e9f7ef;
            border-left: 4px solid #2f8a5b;
            color: #1e6440;
            font-size: .88rem;
            font-weight: 600;
            box-shadow: 0 10px 26px -20px rgba(22, 35, 63, .4);
        }

        .cms-flash svg {
            width: 1.15rem;
            height: 1.15rem;
            flex: none;
        }

        @media (max-width:860px) {
            .cms-aside {
                width: 5rem;
            }

            .cms-brand {
                flex-direction: column;
                text-align: center;
                gap: .5rem;
                padding: 1.1rem .5rem;
            }

            .cms-brand .brandtext {
                display: none;
            }

            .cms-link {
                justify-content: center;
                padding: .8rem;
            }

            .cms-link .label,
            .cms-navlabel,
            .cms-link .cms-badge {
                display: none;
            }

            .cms-workspace {
                padding: 1.25rem;
            }
        }

        @media (prefers-reduced-motion:reduce) {
            .cms * {
                transition: none !important;
            }
        }
    </style>
</head>

<body class="cms">
    @php
    // Jumlah renungan yang menunggu persetujuan, untuk lencana di sidebar.
    $renunganMenunggu = \App\Models\Renungan::where('status', 'pending')->count();
    
    // Menentukan judul halaman berdasarkan route aktif
    $judulHalaman = match (true) {
        request()->routeIs('admin.preview')    => 'Preview Website',
        request()->routeIs('admin.renungan.*') => 'Moderasi Renungan',
        request()->routeIs('admin.bacaan.*')   => 'Bacaan Mingguan',
        default                                => 'Edit Konten Website',
    };
    @endphp

    <div class="cms-shell">

        <!-- SIDEBAR -->
        <aside class="cms-aside">
            <div class="cms-brand">
                <span class="emblem">
                    <svg viewBox="0 0 24 32" aria-hidden="true">
                        <path fill="currentColor" d="M10 0h4v9h8v4h-8v19h-4V13H2V9h8z" />
                    </svg>
                </span>
                <div class="brandtext">
                    <h1>CMS Gereja</h1>
                    <p>Panel Administrasi</p>
                </div>
            </div>

            <nav class="cms-nav">
                <span class="cms-navlabel">Menu</span>

                <!-- Menu 1: Edit Website -->
                <a href="{{ route('admin.dashboard') }}"
                    class="cms-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                    <span class="label">Edit Website</span>
                </a>

                <!-- Menu 2: Moderasi Renungan -->
                <a href="{{ route('admin.renungan.index') }}"
                    class="cms-link {{ request()->routeIs('admin.renungan.*') ? 'active' : '' }}">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                    </svg>
                    <span class="label">Moderasi Renungan</span>
                    @if ($renunganMenunggu > 0)
                    <span class="cms-badge">{{ $renunganMenunggu }}</span>
                    @endif
                </a>

                <!-- Menu 3: Bacaan Mingguan -->
                <a href="{{ route('admin.bacaan.index') }}"
                    class="cms-link {{ request()->routeIs('admin.bacaan.*') ? 'active' : '' }}">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 6.5C10.8 5.7 9.2 5.3 7.5 5.3S4.2 5.7 3 6.5v13c1.2-.8 2.8-1.2 4.5-1.2s3.3.4 4.5 1.2m0-13c1.2-.8 2.8-1.2 4.5-1.2s3.3.4 4.5 1.2v13c-1.2-.8-2.8-1.2-4.5-1.2s-3.3.4-4.5 1.2m0-13v13" />
                    </svg>
                    <span class="label">Bacaan Mingguan</span>
                </a>

                <!-- Menu 4: Lihat Website (Preview) -->
                <a href="{{ route('admin.preview') }}"
                    class="cms-link {{ request()->routeIs('admin.preview') ? 'active' : '' }}">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                    </svg>
                    <span class="label">Lihat Website</span>
                </a>
            </nav>
        </aside>

        <!-- MAIN CONTENT AREA -->
        <main class="cms-main">
            <!-- Header -->
            <header class="cms-header">
                <div class="cms-head-left">
                    <h2 class="cms-title">{{ $judulHalaman }}</h2>
                    <a href="{{ route('home') }}" target="_blank" class="cms-open">
                        <span>Buka Website Utama</span>
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                        </svg>
                    </a>
                </div>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="cms-logout">Logout</button>
                </form>
            </header>

            <!-- Workspace -->
            <div class="cms-workspace">
                <div class="cms-content">
                    @if(session('success'))
                    <div class="cms-flash">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        {{ session('success') }}
                    </div>
                    @endif

                    @yield('content')
                </div>
            </div>
        </main>
    </div>
</body>

</html>