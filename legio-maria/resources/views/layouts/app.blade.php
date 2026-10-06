<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Dashboard') — Legio Maria Ratu Rosari</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Baloo+2:wght@600;700&family=Nunito+Sans:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/bubble-theme.css') }}">
</head>
<body>
<div class="lm-shell">
    <aside class="lm-sidebar">
        <div class="lm-brand">
            <div class="bubble-dot">🌊</div>
            <div>
                <span class="title">Legio Maria</span>
                <span class="sub">Presidium "Ratu Rosari"</span>
            </div>
        </div>
        <nav class="lm-nav">
            <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'active' : '' }}"><span class="dot"></span> Dashboard</a>
            <a href="{{ route('members.index') }}" class="{{ request()->routeIs('members.*') ? 'active' : '' }}"><span class="dot"></span> Anggota</a>
            <a href="{{ route('attendance.index') }}" class="{{ request()->routeIs('attendance.*') ? 'active' : '' }}"><span class="dot"></span> Presensi</a>
            <a href="{{ route('visits.index') }}" class="{{ request()->routeIs('visits.*') ? 'active' : '' }}"><span class="dot"></span> Kunjungan</a>
            <a href="{{ route('cash.index') }}" class="{{ request()->routeIs('cash.*') ? 'active' : '' }}"><span class="dot"></span> Kas</a>
            <a href="{{ route('announcements.index') }}" class="{{ request()->routeIs('announcements.*') ? 'active' : '' }}"><span class="dot"></span> Pengumuman</a>
        </nav>
    </aside>

    <main class="lm-main">
        <div class="lm-topbar">
            <div>
                <h1>@yield('title', 'Dashboard')</h1>
                <p>@yield('subtitle', 'Sistem informasi Legio Maria Presidium "Ratu Rosari"')</p>
            </div>
        </div>

        @if (session('success'))
            <div class="lm-alert lm-alert-success">{{ session('success') }}</div>
        @endif
        @if ($errors->any())
            <div class="lm-alert lm-alert-error">
                @foreach ($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        @endif

        @yield('content')
    </main>
</div>
</body>
</html>
