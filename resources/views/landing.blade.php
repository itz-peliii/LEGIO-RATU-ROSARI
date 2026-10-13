@extends('layouts.app')

@section('content')

<<<<<<< HEAD
<!-- 1. HERO SECTION (at least 1 screen height) -->
<section id="hero" class="min-h-screen relative flex items-center justify-center bg-cover bg-center text-white" 
         style="background-image: linear-gradient(rgba(15, 23, 42, 0.65), rgba(15, 23, 42, 0.75)), url('{{ asset('assets/images/hero.jpg') }}');">
    <div class="max-w-4xl mx-auto px-4 text-center space-y-6 pt-16">
        <h1 class="text-4xl sm:text-6xl font-extrabold tracking-tight leading-tight">
            {{ $settings['hero_title'] ?? 'Selamat Datang di Website Kami' }}
        </h1>
        <div class="text-lg sm:text-xl text-slate-200 max-w-2xl mx-auto prose prose-invert">
            {!! $settings['hero_subtitle'] ?? '<p>Tempat pertumbuhan rohani dan pelayanan kasih bagi sesama.</p>' !!}
        </div>
        <div class="pt-4">
            <a href="#about" class="inline-block px-8 py-3.5 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-full shadow-lg hover:shadow-indigo-500/30 transition duration-300">
                Pelajari Lebih Lanjut
            </a>
        </div>
    </div>
</section>

<!-- 2. ABOUT US SECTION (at least 1 screen height) -->
<section id="about" class="min-h-screen flex items-center bg-white py-20 border-b border-slate-100">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 w-full">
        <div class="text-center max-w-3xl mx-auto space-y-4 mb-12">
            <span class="text-indigo-600 font-semibold text-sm tracking-wider uppercase">Mengenal Kami</span>
            <h2 class="text-3xl sm:text-4xl font-bold text-slate-900">Tentang Kami</h2>
            <div class="w-16 h-1 bg-indigo-600 mx-auto rounded-full"></div>
        </div>
        
        <div class="bg-slate-50 border border-slate-200/80 rounded-2xl p-8 sm:p-12 shadow-sm prose prose-slate max-w-none text-slate-600 text-lg leading-relaxed">
            {!! $settings['about_us'] ?? '<p class="text-center text-slate-400">Gereja Katolik Paroki Santo Petrus Tuban merupakan bagian dari Keuskupan Surabaya yang berdedikasi menjadi wadah pertumbuhan rohani, persaudaraan, dan pelayanan kasih bagi seluruh umat Katolik di wilayah Tuban dan sekitarnya. Melalui berbagai kegiatan liturgi, pembinaan iman, serta pelayanan sosial komunitas seperti Legio Maria Presidium "Ratu Rosari", kami terus berkomitmen menyebarkan nilai-nilai Injil dan menghadirkan kepedulian nyata bagi sesama.</p>' !!}
        </div>
    </div>
</section>

<!-- 3. VISI & MISI SECTION -->
<section id="vision-mission" class="min-h-screen flex items-center bg-slate-50 py-20 border-b border-slate-200">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 w-full">
        <div class="text-center max-w-3xl mx-auto space-y-4 mb-12">
            <span class="text-indigo-600 font-semibold text-sm tracking-wider uppercase">Arah & Semangat Pelayanan</span>
            <h2 class="text-3xl sm:text-4xl font-bold text-slate-900">Visi & Misi</h2>
            <div class="w-16 h-1 bg-indigo-600 mx-auto rounded-full"></div>
        </div>

        @if(isset($settings['visi_misi']) && !empty($settings['visi_misi']))
            <div class="bg-white border border-slate-200 rounded-2xl p-8 sm:p-12 shadow-sm prose prose-slate max-w-none text-slate-700 leading-relaxed">
                {!! $settings['visi_misi'] !!}
            </div>
        @else
            <!-- Fallback Tampilan Default Jika Database Belum Diisi Admin -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <!-- VISI -->
                <div class="bg-white border border-slate-200 rounded-2xl p-8 shadow-sm hover:shadow-md transition">
                    <h3 class="text-2xl font-bold text-slate-900 mb-4">Visi Komunitas</h3>
                    <p class="text-slate-600 leading-relaxed">
                        Menjadi wadah pelayanan awam Katolik yang setia, berbelaskasih, dan tangguh dalam mengamalkan kasih Kristus melalui teladan kerendahan hati dan kesetiaan BUNDA MARIA di Paroki Santo Petrus Tuban.
                    </p>
                </div>

                <!-- MISI -->
                <div class="bg-white border border-slate-200 rounded-2xl p-8 shadow-sm hover:shadow-md transition">
                    <h3 class="text-2xl font-bold text-slate-900 mb-4">Misi Pelayanan</h3>
                    <ul class="space-y-3 text-slate-600 list-disc list-inside leading-relaxed">
                        <li>Memperdalam spiritualitas anggota melalui doa bersama dan perjumpaan rutin mingguan.</li>
                        <li>Melaksanakan perlawatan kasih kepada Umat Katolik yang sakit, lansia, dan membutuhkan penguatan rohani.</li>
                        <li>Mewujudkan tata kelola komunitas yang tertib, transparan, dan terkoordinasi secara digital.</li>
                    </ul>
                </div>
            </div>
        @endif
    </div>
</section>

<!-- 4. DOKUMENTASI SECTION (at least 1 screen height) -->
<section id="documentation" class="min-h-screen flex items-center bg-white py-20">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 w-full">
        <div class="text-center max-w-3xl mx-auto space-y-4 mb-12">
            <span class="text-indigo-600 font-semibold text-sm tracking-wider uppercase">Galeri Kegiatan</span>
            <h2 class="text-3xl sm:text-4xl font-bold text-slate-900">Dokumentasi Kegiatan</h2>
            <div class="w-16 h-1 bg-indigo-600 mx-auto rounded-full mb-6"></div>
            
            @if(!empty($settings['doc_description']))
                <div class="text-slate-600 prose prose-slate mx-auto">
                    {!! $settings['doc_description'] !!}
                </div>
            @endif
        </div>

        @if($documentations->isEmpty())
            <div class="text-center py-16 bg-slate-50 rounded-2xl border border-dashed border-slate-300">
                <p class="text-slate-400 font-medium">Belum ada foto dokumentasi kegiatan yang ditampilkan.</p>
            </div>
        @else
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                @foreach($documentations as $doc)
                    <div class="bg-slate-50 border border-slate-200 rounded-xl overflow-hidden shadow-sm hover:shadow-md transition duration-300 group flex flex-col">
                        <div class="aspect-video w-full overflow-hidden bg-slate-200">
                            <img src="{{ asset('storage/' . $doc->image_path) }}" 
                                 alt="{{ $doc->title ?? 'Dokumentasi' }}" 
                                 class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
                        </div>
                        @if($doc->title)
                            <div class="p-4 bg-white flex-grow flex items-center border-t border-slate-100">
                                <p class="text-sm font-semibold text-slate-800 line-clamp-2">
                                    {{ $doc->title }}
                                </p>
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</section>
=======
@php
    // Slide hero: foto utama dulu, lalu sampai 4 foto dokumentasi terbaru
    $heroSlides = collect([['src' => asset('assets/images/hero.jpg'), 'title' => null]]);
    foreach ($documentations->take(4) as $d) {
        $heroSlides->push(['src' => asset('storage/' . $d->image_path), 'title' => $d->title]);
    }
    $aboutImage = $heroSlides[1]['src'] ?? $heroSlides[0]['src'];

    // Foto profil gereja: taruh file di public/assets/images/profil/
    $profil = [
        'depan' => ['file' => 'gereja-depan.png',  'caption' => 'Gereja Santo Petrus – Tuban'],
        'dekat' => ['file' => 'gereja-dekat.png',  'caption' => 'Gereja Santo Petrus – Tuban'],
        'goa'   => ['file' => 'goa-maria.png',     'caption' => 'Goa Maria Santo Petrus – Tuban'],
        'balai' => ['file' => 'balai-paroki.png',  'caption' => 'Balai Paroki'],
    ];

    // Jadwal misa paroki. "days" = nomor hari (0 = Minggu ... 6 = Sabtu), dipakai untuk menandai "Hari ini".
    $jadwalMisa = [
        ['label' => 'Senin – Kamis & Sabtu (Harian)', 'time' => '05.30', 'days' => [1, 2, 3, 4, 6]],
        ['label' => 'Jumat',  'time' => '18.00', 'days' => [5]],
        ['label' => 'Sabtu',  'time' => '18.00', 'days' => [6]],
        ['label' => 'Minggu', 'time' => '07.00', 'days' => [0]],
    ];

    $misiDefault = [
        'Memperdalam spiritualitas anggota melalui doa bersama dan perjumpaan rutin mingguan.',
        'Melaksanakan perlawatan kasih kepada umat Katolik yang sakit, lansia, dan membutuhkan penguatan rohani.',
        'Mewujudkan tata kelola komunitas yang tertib, transparan, dan terkoordinasi secara digital.',
    ];
@endphp

<style>
@import url('https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,500;0,600;0,700;1,500;1,600&family=Nunito+Sans:wght@400;600;700&display=swap');

.parish {
    --ink: #16233f;
    --ink-soft: #22345c;
    --gold: #b98a3e;
    --gold-soft: #e6cf9b;
    --paper: #f3f6fa;
    --text: #2b3550;
    --muted: #66718c;
    --display: 'Cormorant Garamond', Georgia, serif;
    --body: 'Nunito Sans', system-ui, sans-serif;
    font-family: var(--body);
    color: var(--text);
}
.parish *, .parish *::before, .parish *::after { box-sizing: border-box; }
.parish .wrap { max-width: 80rem; margin: 0 auto; padding: 0 1.5rem; width: 100%; }
@media (min-width: 1024px) { .parish .wrap { padding: 0 3rem; } }

.parish .h-display {
    font-family: var(--display);
    font-weight: 600;
    line-height: 1.04;
    letter-spacing: -0.01em;
    font-size: clamp(2.6rem, 4.8vw, 4rem);
    text-wrap: balance;
}
.parish .cross { width: 14px; height: auto; fill: currentColor; flex: none; }

.parish .sect { min-height: 100svh; display: flex; align-items: center; padding: 6rem 0; }

/* ---------- Rich text dari admin ---------- */
.parish .rich { font-size: 1.1rem; line-height: 1.85; }
.parish .rich p { margin: 0 0 1.15em; }
.parish .rich ul { list-style: disc; padding-left: 1.3em; margin: 0 0 1.15em; }
.parish .rich a { color: var(--gold); text-decoration: underline; }
.parish .rich strong { font-weight: 700; }
.parish .rich-dark { color: rgba(255,255,255,.86); }

/* ---------- Hero ---------- */
.parish .hero { position: relative; min-height: 100svh; overflow: hidden; display: flex; align-items: flex-end; color: #fff; background: var(--ink); }
.parish .hero-slide, .parish .stage-slide img {
    background-size: cover; background-position: center;
}
.parish .hero-slide {
    position: absolute; inset: 0; opacity: 0; transform: scale(1.09);
    transition: opacity 1.6s ease;
}
.parish .hero-slide.is-active { opacity: 1; animation: drift 12s ease-out forwards; }
@keyframes drift { from { transform: scale(1); } to { transform: scale(1.09); } }
.parish .hero-shade {
    position: absolute; inset: 0;
    background:
        linear-gradient(to top, rgba(12,21,43,.92) 0%, rgba(12,21,43,.55) 38%, rgba(12,21,43,.15) 75%),
        linear-gradient(to bottom, rgba(12,21,43,.55) 0%, rgba(12,21,43,0) 22%);
}
.parish .hero-body { position: relative; width: 100%; padding-bottom: 5.5rem; padding-top: 9rem; }
.parish .hero-title {
    font-family: var(--display); font-weight: 600; line-height: 1;
    font-size: clamp(3rem, 8vw, 6.6rem); max-width: 14ch; text-wrap: balance;
    text-shadow: 0 2px 30px rgba(0,0,0,.35);
}
.parish .hero-sub { margin-top: 1.5rem; max-width: 36rem; font-size: 1.15rem; line-height: 1.7; color: rgba(255,255,255,.86); }
.parish .hero-sub p { margin: 0; }
.parish .hero-actions { margin-top: 2.25rem; display: flex; flex-wrap: wrap; gap: .75rem 1.5rem; align-items: center; }
.parish .btn-gold {
    display: inline-block; padding: .9rem 2rem; background: var(--gold); color: #fff;
    font-weight: 700; border-radius: 999px; text-decoration: none; transition: background .25s;
}
.parish .btn-gold:hover { background: #a07632; }
.parish .link-quiet { color: #fff; font-weight: 600; text-decoration: underline; text-underline-offset: 6px; text-decoration-color: rgba(255,255,255,.4); }
.parish .link-quiet:hover { text-decoration-color: var(--gold-soft); }

.parish .hero-controls {
    position: absolute; right: 1.5rem; bottom: 2.25rem; display: flex; align-items: center; gap: .75rem;
}
@media (min-width: 1024px) { .parish .hero-controls { right: 3rem; bottom: 3.5rem; } }
.parish .counter { font-family: var(--display); font-size: 1.25rem; letter-spacing: .05em; margin-right: .5rem; }
.parish .round-btn {
    width: 2.9rem; height: 2.9rem; border-radius: 999px; display: grid; place-items: center;
    border: 1px solid rgba(255,255,255,.5); color: #fff; background: rgba(255,255,255,.08);
    backdrop-filter: blur(6px); cursor: pointer; transition: background .2s, border-color .2s;
}
.parish .round-btn:hover { background: rgba(255,255,255,.22); border-color: #fff; }
.parish .round-btn svg { width: 1.1rem; height: 1.1rem; }
.parish .bar { position: absolute; left: 0; bottom: 0; height: 3px; width: 100%; background: rgba(255,255,255,.15); }
.parish .bar i { display: block; height: 100%; width: 0; background: var(--gold); }
.parish .bar i.run { animation: fill var(--interval, 6000ms) linear forwards; }
@keyframes fill { to { width: 100%; } }

/* Satu rangkaian animasi saat halaman dibuka */
.parish .rise { opacity: 0; transform: translateY(18px); animation: rise .9s cubic-bezier(.2,.7,.2,1) forwards; }
.parish .rise.d1 { animation-delay: .25s; } .parish .rise.d2 { animation-delay: .5s; } .parish .rise.d3 { animation-delay: .75s; }
@keyframes rise { to { opacity: 1; transform: none; } }

/* ---------- Tentang ---------- */
.parish .about { background: #fff; }
.parish .about-grid { display: grid; gap: 3.5rem; align-items: center; }
@media (min-width: 1024px) { .parish .about-grid { grid-template-columns: 5fr 7fr; gap: 6rem; } }
.parish .portrait { position: relative; margin: 0 1.25rem 1.25rem 0; }
.parish .portrait::before {
    content: ''; position: absolute; inset: 1.25rem -1.25rem -1.25rem 1.25rem;
    border: 2px solid var(--gold); z-index: 0;
}
.parish .portrait img { position: relative; z-index: 1; display: block; width: 100%; aspect-ratio: 4 / 5; object-fit: cover; }
.parish .about h2 { color: var(--ink); display: flex; align-items: center; gap: 1rem; margin-bottom: 2rem; }
.parish .about h2 .cross { color: var(--gold); width: 18px; }

/* ---------- Visi & Misi ---------- */
.parish .vm { background: var(--ink); color: #fff; position: relative; overflow: hidden; }
.parish .vm::after {
    content: ''; position: absolute; right: -6rem; top: 50%; width: 30rem; height: 40rem; transform: translateY(-50%);
    background: no-repeat center / contain url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 32'%3E%3Cpath fill='white' fill-opacity='.04' d='M10 0h4v9h8v4h-8v19h-4V13H2V9h8z'/%3E%3C/svg%3E");
    pointer-events: none;
}
.parish .vm-grid { position: relative; z-index: 1; display: grid; gap: 4rem; margin-top: 3.5rem; }
@media (min-width: 1024px) { .parish .vm-grid { grid-template-columns: 5fr 6fr; gap: 6rem; } }
.parish .vm h3 { font-family: var(--display); font-size: 1.6rem; font-weight: 600; color: var(--gold-soft); margin-bottom: 1.25rem; }
.parish .visi-text { font-family: var(--display); font-style: italic; font-weight: 500; font-size: clamp(1.7rem, 2.8vw, 2.4rem); line-height: 1.35; }
.parish .misi-item { display: flex; gap: 1.1rem; padding: 1.4rem 0; border-top: 1px solid rgba(255,255,255,.16); font-size: 1.1rem; line-height: 1.7; color: rgba(255,255,255,.88); }
.parish .misi-item:last-child { border-bottom: 1px solid rgba(255,255,255,.16); }
.parish .misi-item .cross { color: var(--gold); margin-top: .45rem; width: 12px; }

/* ---------- Dokumentasi ---------- */
.parish .docs { background: var(--paper); }
.parish .docs-head { display: grid; gap: 1.5rem; margin-bottom: 2.5rem; align-items: end; }
@media (min-width: 1024px) { .parish .docs-head { grid-template-columns: 1fr 1fr; gap: 4rem; } }
.parish .docs-head h2 { color: var(--ink); }
.parish .stage { position: relative; aspect-ratio: 16 / 9; max-height: 82svh; width: 100%; overflow: hidden; background: var(--ink); touch-action: pan-y; }
.parish .stage-slide { position: absolute; inset: 0; margin: 0; opacity: 0; transition: opacity 1s ease; }
.parish .stage-slide.is-active { opacity: 1; z-index: 1; }
.parish .stage-slide button { display: block; width: 100%; height: 100%; padding: 0; border: 0; cursor: zoom-in; background: none; }
.parish .stage-slide img { width: 100%; height: 100%; object-fit: cover; display: block; transform: scale(1.04); transition: transform 7s ease-out; }
.parish .stage-slide.is-active img { transform: scale(1); }
.parish .stage-cap {
    position: absolute; left: 0; right: 0; bottom: 0; z-index: 2; pointer-events: none;
    padding: 4rem 1.5rem 1.5rem; color: #fff; font-family: var(--display); font-size: clamp(1.2rem, 2.2vw, 1.8rem); font-weight: 600;
    background: linear-gradient(to top, rgba(12,21,43,.8), transparent);
}
.parish .stage .round-btn { position: absolute; top: 50%; transform: translateY(-50%); z-index: 3; }
.parish .stage [data-prev] { left: 1rem; } .parish .stage [data-next] { right: 1rem; }
.parish .thumbs { display: flex; gap: .6rem; margin-top: .75rem; overflow-x: auto; padding-bottom: .4rem; scrollbar-width: thin; }
.parish .thumb { flex: none; width: 7.5rem; aspect-ratio: 16 / 10; border: 0; padding: 0; cursor: pointer; opacity: .55; outline: 2px solid transparent; outline-offset: 2px; transition: opacity .25s, outline-color .25s; }
.parish .thumb img { width: 100%; height: 100%; object-fit: cover; display: block; }
.parish .thumb:hover { opacity: .85; }
.parish .thumb[aria-current="true"] { opacity: 1; outline-color: var(--gold); }
.parish .is-single [data-prev], .parish .is-single [data-next], .parish .is-single .thumbs { display: none; }
.parish .empty-docs { padding: 4rem 1.5rem; text-align: center; border: 1px dashed #b8c1d4; color: var(--muted); }

.parish dialog.lightbox { border: 0; padding: 0; background: transparent; max-width: 94vw; max-height: 92vh; overflow: visible; }
.parish dialog.lightbox::backdrop { background: rgba(10,16,32,.92); }
.parish .lightbox img { display: block; max-width: 94vw; max-height: 82vh; margin: 0 auto; object-fit: contain; }
.parish .lightbox p { color: #fff; text-align: center; margin-top: .9rem; font-family: var(--display); font-size: 1.3rem; }
.parish .lightbox .close { position: absolute; top: -3rem; right: 0; }

/* ---------- Profil Gereja ---------- */
.parish .profil { background: var(--paper); }
.parish .profil h2 { color: var(--ink); max-width: 18ch; }
.parish .profil-grid { display: grid; gap: 2.5rem 2rem; margin-top: 3.5rem; }
@media (min-width: 768px) {
    .parish .profil-grid { grid-template-columns: repeat(12, 1fr); gap: 3.5rem 2rem; }
    .parish .p-wide { grid-column: span 7; }
    .parish .p-narrow { grid-column: span 5; }
    .parish .p-narrow.offset { margin-top: 3.5rem; }
}
.parish .p-item { margin: 0; }
.parish .p-photo { overflow: hidden; background: var(--ink); }
.parish .p-photo img { display: block; width: 100%; aspect-ratio: 4 / 3; object-fit: cover; transition: transform .8s ease; }
.parish .p-wide .p-photo img { aspect-ratio: 16 / 11; }
.parish .p-item:hover .p-photo img { transform: scale(1.03); }
.parish .p-item figcaption {
    margin-top: 1rem; padding-top: .8rem; border-top: 2px solid var(--gold); width: fit-content; min-width: 8rem;
    font-family: var(--display); font-size: 1.45rem; font-weight: 600; color: var(--ink);
}

/* ---------- Jadwal Misa ---------- */
.parish .jadwal { background: #fff; }
.parish .jadwal-grid { display: grid; gap: 3rem; align-items: center; }
@media (min-width: 1024px) { .parish .jadwal-grid { grid-template-columns: 4fr 7fr; gap: 6rem; } }
.parish .jadwal h2 { color: var(--ink); margin-bottom: 1.25rem; }
.parish .jadwal-note { color: var(--muted); font-size: 1.05rem; line-height: 1.7; max-width: 24rem; }
.parish .jadwal-list { margin: 0; padding: 0; list-style: none; border-top: 2px solid var(--ink); }
.parish .jadwal-row {
    display: flex; align-items: baseline; justify-content: space-between; gap: 1.5rem;
    padding: 1.6rem .75rem; border-bottom: 1px solid #d5dbe7; transition: background .3s;
}
.parish .jadwal-day { font-family: var(--display); font-weight: 600; font-size: clamp(1.4rem, 2.6vw, 2rem); color: var(--ink); line-height: 1.2; }
.parish .jadwal-time { font-family: var(--display); font-weight: 700; font-size: clamp(1.9rem, 3.6vw, 2.9rem); color: var(--gold); line-height: 1; white-space: nowrap; font-variant-numeric: lining-nums tabular-nums; }
.parish .today-tag { display: none; margin-left: .75rem; font-family: var(--body); font-size: .8rem; font-weight: 700; color: #fff; background: var(--ink); padding: .2rem .6rem; border-radius: 999px; vertical-align: middle; }
.parish .jadwal-row.is-today { background: #eef2f9; }
.parish .jadwal-row.is-today .today-tag { display: inline-block; }

.parish a:focus-visible, .parish button:focus-visible { outline: 3px solid var(--gold-soft); outline-offset: 3px; }

@media (prefers-reduced-motion: reduce) {
    .parish .hero-slide.is-active, .parish .rise { animation: none; opacity: 1; transform: none; }
    .parish .hero-slide, .parish .stage-slide, .parish .stage-slide img { transition: none; }
    .parish .bar { display: none; }
}
</style>

<div class="parish">

    <!-- 1. HERO: slideshow layar penuh -->
    <section id="hero" class="hero" data-slider data-interval="6500" aria-roledescription="carousel" aria-label="Foto kegiatan paroki">
        @foreach($heroSlides as $i => $slide)
            <div class="hero-slide {{ $i === 0 ? 'is-active' : '' }}" data-slide
                 data-title="{{ $slide['title'] }}"
                 style="background-image: url('{{ $slide['src'] }}');"></div>
        @endforeach
        <div class="hero-shade"></div>

        <div class="hero-body">
            <div class="wrap">
                <h1 class="hero-title rise d1">{{ $settings['hero_title'] ?? 'Paroki Santo Petrus Tuban' }}</h1>
                <div class="hero-sub rise d2">
                    {!! $settings['hero_subtitle'] ?? '<p>Tempat pertumbuhan rohani dan pelayanan kasih bagi sesama.</p>' !!}
                </div>
                <div class="hero-actions rise d3">
                    <a href="#about" class="btn-gold">Kenali kami</a>
                    <a href="#schedule" class="link-quiet">Lihat jadwal misa</a>
                </div>
            </div>
        </div>

        <div class="hero-controls">
            <span class="counter" data-count aria-live="off">01 / {{ str_pad($heroSlides->count(), 2, '0', STR_PAD_LEFT) }}</span>
            <button type="button" class="round-btn" data-prev aria-label="Foto sebelumnya">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M15 5l-7 7 7 7"/></svg>
            </button>
            <button type="button" class="round-btn" data-next aria-label="Foto berikutnya">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 5l7 7-7 7"/></svg>
            </button>
        </div>
        <div class="bar"><i data-bar></i></div>
    </section>

    <!-- 2. TENTANG KAMI -->
    <section id="about" class="sect about">
        <div class="wrap">
            <div class="about-grid">
                <figure class="portrait">
                    <img src="{{ $aboutImage }}" alt="Kegiatan umat Paroki Santo Petrus Tuban" loading="lazy">
                </figure>

                <div>
                    <h2 class="h-display">
                        <svg class="cross" viewBox="0 0 24 32" aria-hidden="true"><path d="M10 0h4v9h8v4h-8v19h-4V13H2V9h8z"/></svg>
                        Tentang Kami
                    </h2>
                    <div class="rich" style="max-width: 40rem;">
                        {!! $settings['about_us'] ?? '<p>Gereja Katolik Paroki Santo Petrus Tuban merupakan bagian dari Keuskupan Surabaya yang berdedikasi menjadi wadah pertumbuhan rohani, persaudaraan, dan pelayanan kasih bagi seluruh umat Katolik di wilayah Tuban dan sekitarnya.</p><p>Melalui berbagai kegiatan liturgi, pembinaan iman, serta pelayanan sosial komunitas seperti Legio Maria Presidium "Ratu Rosari", kami terus berkomitmen menyebarkan nilai-nilai Injil dan menghadirkan kepedulian nyata bagi sesama.</p>' !!}
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- 3. PROFIL GEREJA -->
    <section id="profile" class="sect profil">
        <div class="wrap">
            <h2 class="h-display">Profil Gereja</h2>

            <div class="profil-grid">
                <figure class="p-item p-wide">
                    <div class="p-photo"><img src="{{ asset('assets/images/profil/' . $profil['depan']['file']) }}" alt="{{ $profil['depan']['caption'] }}" loading="lazy"></div>
                    <figcaption>{{ $profil['depan']['caption'] }}</figcaption>
                </figure>
                <figure class="p-item p-narrow offset">
                    <div class="p-photo"><img src="{{ asset('assets/images/profil/' . $profil['goa']['file']) }}" alt="{{ $profil['goa']['caption'] }}" loading="lazy"></div>
                    <figcaption>{{ $profil['goa']['caption'] }}</figcaption>
                </figure>
                <figure class="p-item p-narrow">
                    <div class="p-photo"><img src="{{ asset('assets/images/profil/' . $profil['balai']['file']) }}" alt="{{ $profil['balai']['caption'] }}" loading="lazy"></div>
                    <figcaption>{{ $profil['balai']['caption'] }}</figcaption>
                </figure>
                <figure class="p-item p-wide">
                    <div class="p-photo"><img src="{{ asset('assets/images/profil/' . $profil['dekat']['file']) }}" alt="{{ $profil['dekat']['caption'] }}" loading="lazy"></div>
                    <figcaption>{{ $profil['dekat']['caption'] }}</figcaption>
                </figure>
            </div>
        </div>
    </section>

    <!-- 4. JADWAL MISA -->
    <section id="schedule" class="sect jadwal">
        <div class="wrap">
            <div class="jadwal-grid">
                <div>
                    <h2 class="h-display">Jadwal Misa Paroki</h2>
                    <p class="jadwal-note">Misa di Gereja Santo Petrus, Tuban. Hari ini ditandai otomatis pada daftar.</p>
                </div>

                <ul class="jadwal-list">
                    @foreach($jadwalMisa as $misa)
                        <li class="jadwal-row" data-days="{{ implode(',', $misa['days']) }}">
                            <span class="jadwal-day">{{ $misa['label'] }}<span class="today-tag">Hari ini</span></span>
                            <span class="jadwal-time">{{ $misa['time'] }}</span>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </section>

    <!-- 5. VISI & MISI -->
    <section id="vision-mission" class="sect vm">
        <div class="wrap">
            <h2 class="h-display">Visi &amp; Misi</h2>

            @if(!empty($settings['visi_misi']))
                <div class="rich rich-dark" style="max-width: 46rem; margin-top: 3rem; position: relative; z-index: 1;">
                    {!! $settings['visi_misi'] !!}
                </div>
            @else
                <div class="vm-grid">
                    <div>
                        <h3>Visi</h3>
                        <p class="visi-text">
                            Menjadi wadah pelayanan awam Katolik yang setia, berbelaskasih, dan tangguh dalam mengamalkan kasih Kristus, meneladani kerendahan hati dan kesetiaan Bunda Maria di Paroki Santo Petrus Tuban.
                        </p>
                    </div>
                    <div>
                        <h3>Misi</h3>
                        @foreach($misiDefault as $misi)
                            <div class="misi-item">
                                <svg class="cross" viewBox="0 0 24 32" aria-hidden="true"><path d="M10 0h4v9h8v4h-8v19h-4V13H2V9h8z"/></svg>
                                <p style="margin:0;">{{ $misi }}</p>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </section>

    <!-- 4. DOKUMENTASI: slideshow besar + thumbnail -->
    <section id="documentation" class="sect docs">
        <div class="wrap">
            <div class="docs-head">
                <h2 class="h-display">Dokumentasi Kegiatan</h2>
                @if(!empty($settings['doc_description']))
                    <div class="rich" style="color: var(--muted); font-size: 1.05rem;">{!! $settings['doc_description'] !!}</div>
                @endif
            </div>

            @if($documentations->isEmpty())
                <div class="empty-docs">Belum ada foto dokumentasi. Foto yang ditambahkan admin akan tampil di sini.</div>
            @else
                <div data-slider data-interval="5500" data-pause-hover class="{{ $documentations->count() < 2 ? 'is-single' : '' }}">
                    <div class="stage" data-stage aria-roledescription="carousel" aria-label="Galeri dokumentasi kegiatan">
                        @foreach($documentations as $i => $doc)
                            <figure class="stage-slide {{ $i === 0 ? 'is-active' : '' }}" data-slide data-title="{{ $doc->title }}">
                                <button type="button" data-open aria-label="Perbesar foto{{ $doc->title ? ': ' . $doc->title : '' }}">
                                    <img src="{{ asset('storage/' . $doc->image_path) }}" alt="{{ $doc->title ?? 'Dokumentasi kegiatan' }}" loading="{{ $i === 0 ? 'eager' : 'lazy' }}">
                                </button>
                            </figure>
                        @endforeach

                        <div class="stage-cap" data-caption></div>

                        <button type="button" class="round-btn" data-prev aria-label="Foto sebelumnya">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M15 5l-7 7 7 7"/></svg>
                        </button>
                        <button type="button" class="round-btn" data-next aria-label="Foto berikutnya">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 5l7 7-7 7"/></svg>
                        </button>
                    </div>

                    <div class="thumbs">
                        @foreach($documentations as $i => $doc)
                            <button type="button" class="thumb" data-thumb aria-label="Tampilkan foto {{ $i + 1 }}" aria-current="{{ $i === 0 ? 'true' : 'false' }}">
                                <img src="{{ asset('storage/' . $doc->image_path) }}" alt="" loading="lazy">
                            </button>
                        @endforeach
                    </div>
                </div>

                <dialog class="lightbox" id="lightbox" aria-label="Foto diperbesar">
                    <button type="button" class="round-btn close" id="lightbox-close" aria-label="Tutup">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M6 6l12 12M18 6L6 18"/></svg>
                    </button>
                    <img src="" alt="">
                    <p></p>
                </dialog>
            @endif
        </div>
    </section>

</div>

<script>
(function () {
    var reduce = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
    var pad = function (v) { return String(v).padStart(2, '0'); };

    function Slider(root) {
        var slides = Array.prototype.slice.call(root.querySelectorAll('[data-slide]'));
        var thumbs = Array.prototype.slice.call(root.querySelectorAll('[data-thumb]'));
        var counters = root.querySelectorAll('[data-count]');
        var caption = root.querySelector('[data-caption]');
        var bar = root.querySelector('[data-bar]');
        var interval = parseInt(root.getAttribute('data-interval'), 10) || 6000;
        var n = slides.length, i = 0, timer = null, hold = false, visible = true;

        if (n < 2) { root.classList.add('is-single'); }
        if (bar) { bar.style.setProperty('--interval', interval + 'ms'); }

        function restartBar() {
            if (!bar || reduce) return;
            bar.classList.remove('run');
            void bar.offsetWidth;
            bar.classList.add('run');
        }
        function schedule() {
            clearTimeout(timer);
            if (reduce || n < 2 || hold || !visible) return;
            timer = setTimeout(function () { go(i + 1); }, interval);
        }
        function go(k) {
            i = (k + n) % n;
            slides.forEach(function (s, idx) { s.classList.toggle('is-active', idx === i); });
            thumbs.forEach(function (t, idx) {
                t.setAttribute('aria-current', idx === i ? 'true' : 'false');
                if (idx === i && t.scrollIntoView) {
                    t.parentNode.scrollTo({ left: t.offsetLeft - 16, behavior: reduce ? 'auto' : 'smooth' });
                }
            });
            counters.forEach(function (c) { c.textContent = pad(i + 1) + ' / ' + pad(n); });
            if (caption) { caption.textContent = slides[i].getAttribute('data-title') || ''; caption.style.display = caption.textContent ? '' : 'none'; }
            restartBar();
            schedule();
        }

        var prev = root.querySelector('[data-prev]'), next = root.querySelector('[data-next]');
        if (prev) prev.addEventListener('click', function () { go(i - 1); });
        if (next) next.addEventListener('click', function () { go(i + 1); });
        thumbs.forEach(function (t, idx) { t.addEventListener('click', function () { go(idx); }); });

        if (root.hasAttribute('data-pause-hover')) {
            root.addEventListener('mouseenter', function () { hold = true; clearTimeout(timer); });
            root.addEventListener('mouseleave', function () { hold = false; schedule(); });
        }

        // Geser jari di layar sentuh
        var startX = null;
        root.addEventListener('touchstart', function (e) { startX = e.touches[0].clientX; }, { passive: true });
        root.addEventListener('touchend', function (e) {
            if (startX === null) return;
            var dx = e.changedTouches[0].clientX - startX;
            if (Math.abs(dx) > 50) go(dx < 0 ? i + 1 : i - 1);
            startX = null;
        });

        // Berhenti otomatis saat slideshow tidak terlihat
        if ('IntersectionObserver' in window) {
            new IntersectionObserver(function (entries) {
                visible = entries[0].isIntersecting;
                if (visible) { restartBar(); schedule(); } else { clearTimeout(timer); }
            }, { threshold: 0.25 }).observe(root);
        }

        go(0);
    }

    document.querySelectorAll('.parish [data-slider]').forEach(Slider);

    // Tandai jadwal misa untuk hari ini
    var today = String(new Date().getDay());
    document.querySelectorAll('.parish .jadwal-row').forEach(function (row) {
        if (row.getAttribute('data-days').split(',').indexOf(today) !== -1) { row.classList.add('is-today'); }
    });

    // Lightbox foto dokumentasi
    var dlg = document.getElementById('lightbox');
    if (dlg) {
        var img = dlg.querySelector('img'), cap = dlg.querySelector('p');
        document.querySelectorAll('.parish [data-open]').forEach(function (btn) {
            btn.addEventListener('click', function () {
                var src = btn.querySelector('img');
                img.src = src.src;
                img.alt = src.alt;
                cap.textContent = btn.closest('[data-slide]').getAttribute('data-title') || '';
                dlg.showModal();
            });
        });
        document.getElementById('lightbox-close').addEventListener('click', function () { dlg.close(); });
        dlg.addEventListener('click', function (e) { if (e.target === dlg) dlg.close(); });
    }
})();
</script>
>>>>>>> b77901e93068e081459190214a0156a4e29a6c8b

@endsection