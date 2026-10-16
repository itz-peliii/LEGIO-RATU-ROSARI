@extends('layouts.app')

@section('content')

@php
    // Ambil foto dari database. Jika database masih kosong, baru pakai foto hero default dari assets.
    if ($documentations->isNotEmpty()) {
        $heroSlides = $documentations->map(function ($d) {
            return [
                'src'   => asset('storage/' . $d->image_path),
                'title' => $d->title
            ];
        });
    } else {
        $heroSlides = collect([
            ['src' => asset('assets/images/hero.jpg'), 'title' => null]
        ]);
    }

    // Gambar untuk section "Tentang Kami" (memakai foto ke-2 jika ada, atau foto ke-1)
    $aboutImage = isset($heroSlides[1]) ? $heroSlides[1]['src'] : $heroSlides[0]['src'];

    // Profil Foto Gereja (Statis)
    $profil = [
        'depan' => ['file' => 'gereja-depan.png',  'caption' => 'Gereja Santo Petrus – Tuban'],
        'interior' => ['file' => 'interior.jpeg',  'caption' => 'Gereja Santo Petrus – Tuban'],
        'goa'   => ['file' => 'goa-maria.png',      'caption' => 'Goa Maria Santo Petrus – Tuban'],
        'balai' => ['file' => 'balai-paroki.png',  'caption' => 'Balai Paroki'],
    ];

    // Jadwal Misa Paroki (0 = Minggu, 1 = Senin, ..., 6 = Sabtu)
    $jadwalMisa = [
        ['label' => 'Senin – Kamis & Sabtu (Harian)', 'time' => '05.30', 'days' => [1, 2, 3, 4, 6]],
        ['label' => 'Jumat',  'time' => '18.00', 'days' => [5]],
        ['label' => 'Sabtu',  'time' => '18.00', 'days' => [6]],
        ['label' => 'Minggu', 'time' => '07.00', 'days' => [0]],
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

.parish .rich { font-size: 1.1rem; line-height: 1.85; }
.parish .rich p { margin: 0 0 1.15em; }
.parish .rich ul { list-style: disc; padding-left: 1.3em; margin: 0 0 1.15em; }
.parish .rich a { color: var(--gold); text-decoration: underline; }
.parish .rich strong { font-weight: 700; }
.parish .rich-dark { color: rgba(255,255,255,.86); }

.parish .hero { position: relative; min-height: 100svh; overflow: hidden; display: flex; align-items: flex-end; color: #fff; background: var(--ink); }
.parish .hero-slide, .parish .stage-slide img { background-size: cover; background-position: center; }
.parish .hero-slide { position: absolute; inset: 0; opacity: 0; transform: scale(1.09); transition: opacity 1.6s ease; }
.parish .hero-slide.is-active { opacity: 1; animation: drift 12s ease-out forwards; }
@keyframes drift { from { transform: scale(1); } to { transform: scale(1.09); } }
.parish .hero-shade {
    position: absolute; inset: 0;
    background: linear-gradient(to top, rgba(12,21,43,.92) 0%, rgba(12,21,43,.55) 38%, rgba(12,21,43,.15) 75%),
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
.parish .btn-gold { display: inline-block; padding: .9rem 2rem; background: var(--gold); color: #fff; font-weight: 700; border-radius: 999px; text-decoration: none; transition: background .25s; }
.parish .btn-gold:hover { background: #a07632; }
.parish .link-quiet { color: #fff; font-weight: 600; text-decoration: underline; text-underline-offset: 6px; text-decoration-color: rgba(255,255,255,.4); }
.parish .link-quiet:hover { text-decoration-color: var(--gold-soft); }

.parish .hero-controls { position: absolute; right: 1.5rem; bottom: 2.25rem; display: flex; align-items: center; gap: .75rem; }
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

.parish .rise { opacity: 0; transform: translateY(18px); animation: rise .9s cubic-bezier(.2,.7,.2,1) forwards; }
.parish .rise.d1 { animation-delay: .25s; } .parish .rise.d2 { animation-delay: .5s; } .parish .rise.d3 { animation-delay: .75s; }
@keyframes rise { to { opacity: 1; transform: none; } }

.parish .about { background: #fff; }
.parish .about-grid { display: grid; gap: 3.5rem; align-items: center; }
@media (min-width: 1024px) { .parish .about-grid { grid-template-columns: 5fr 7fr; gap: 6rem; } }
.parish .portrait { position: relative; margin: 0 1.25rem 1.25rem 0; }
.parish .portrait::before { content: ''; position: absolute; inset: 1.25rem -1.25rem -1.25rem 1.25rem; border: 2px solid var(--gold); z-index: 0; }
.parish .portrait img { position: relative; z-index: 1; display: block; width: 100%; aspect-ratio: 4 / 5; object-fit: cover; }
.parish .about h2 { color: var(--ink); display: flex; align-items: center; gap: 1rem; margin-bottom: 2rem; }
.parish .about h2 .cross { color: var(--gold); width: 18px; }

.parish .vm { background: var(--ink); color: #fff; position: relative; overflow: hidden; }
.parish .vm::after {
    content: ''; position: absolute; right: -6rem; top: 50%; width: 30rem; height: 40rem; transform: translateY(-50%);
    background: no-repeat center / contain url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 32'%3E%3Cpath fill='white' fill-opacity='.04' d='M10 0h4v9h8v4h-8v19h-4V13H2V9h8z'/%3E%3C/svg%3E");
    pointer-events: none;
}

.parish .docs { background: var(--paper); }
.parish .docs-head { 
    display: flex; 
    flex-direction: column; 
    align-items: center; 
    text-align: center; 
    gap: 1rem; 
    margin-bottom: 2.5rem; 
}
.parish .docs-head h2 { 
    color: var(--ink); 
    text-align: center; 
    width: 100%; 
}
.parish .stage { position: relative; aspect-ratio: 16 / 9; max-height: 82svh; width: 100%; overflow: hidden; background: var(--ink); touch-action: pan-y; }
.parish .stage-slide { position: absolute; inset: 0; margin: 0; opacity: 0; transition: opacity 1s ease; }
.parish .stage-slide.is-active { opacity: 1; z-index: 1; }
.parish .stage-slide button { display: block; width: 100%; height: 100%; padding: 0; border: 0; cursor: zoom-in; background: none; }
.parish .stage-slide img { width: 100%; height: 100%; object-fit: cover; display: block; transform: scale(1.04); transition: transform 7s ease-out; }
.parish .stage-slide.is-active img { transform: scale(1); }
.parish .stage-cap { position: absolute; left: 0; right: 0; bottom: 0; z-index: 2; pointer-events: none; padding: 4rem 1.5rem 1.5rem; color: #fff; font-family: var(--display); font-size: clamp(1.2rem, 2.2vw, 1.8rem); font-weight: 600; background: linear-gradient(to top, rgba(12,21,43,.8), transparent); }
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

/* CSS Profil Asli */
.parish .profil { background: var(--paper); }
.parish .profil h2 { 
    color: var(--ink); 
    margin-bottom: 2.5rem; 
    text-align: center; /* Membuat teks Profil Gereja berada di tengah */
    max-width: 100%;    /* Memastikan teks bisa memenuhi area tengah */
}.parish .profil-grid { display: grid; gap: 2.5rem 2rem; margin-top: 3.5rem; }
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
.parish .p-item figcaption { margin-top: 1rem; padding-top: .8rem; border-top: 2px solid var(--gold); width: fit-content; min-width: 8rem; font-family: var(--display); font-size: 1.45rem; font-weight: 600; color: var(--ink); }

/* Penyesuaian Carousel Profil agar Sangat Besar & Mengisi Container (100% Full Width Wrap) */
/* Style Carousel Profil Modern & Pas Foto */
.parish .profil-carousel-wrapper { 
    position: relative; 
    width: 100%; 
    max-width: 960px; 
    margin: 0 auto; 
}
.parish .profil-stage-large { 
    position: relative; 
    width: 100%; 
    min-height: 480px;
    background: #fff; 
    border-radius: 16px; 
    overflow: hidden;
    box-shadow: 0 10px 30px rgba(0,0,0,0.08);
    touch-action: pan-y; 
}
.parish .profil-stage-large .profil-slide { 
    position: absolute; 
    inset: 0; 
    margin: 0; 
    opacity: 0; 
    transition: opacity 0.6s ease-in-out; 
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    background: #f8fafc;
}
.parish .profil-stage-large .profil-slide.is-active { 
    opacity: 1; 
    z-index: 1; 
}
.parish .profil-stage-large img { 
    max-width: 100%; 
    max-height: 420px; 
    width: auto;
    height: auto;
    object-fit: contain; 
    display: block; 
    border-radius: 8px;
}
.parish .profil-stage-large .profil-cap { 
    position: absolute; 
    left: 0; 
    right: 0; 
    bottom: 0; 
    z-index: 2; 
    pointer-events: none; 
    padding: 1.2rem 2rem; 
    color: var(--ink); 
    font-family: var(--display); 
    font-size: clamp(1.2rem, 2vw, 1.6rem); 
    font-weight: 600; 
    background: rgba(255, 255, 255, 0.92);
    backdrop-filter: blur(4px);
    text-align: center;
    border-top: 1px solid rgba(0,0,0,0.05);
}
.parish .profil-stage-large .round-btn { 
    position: absolute; 
    top: 45%; 
    transform: translateY(-50%); 
    z-index: 3; 
    background: rgba(22, 35, 63, 0.8);
    color: #fff;
    border: none;
    width: 3rem;
    height: 3rem;
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
}
.parish .profil-stage-large .round-btn:hover {
    background: var(--gold);
}
.parish .profil-stage-large [data-prev] { left: 1.2rem; } 
.parish .profil-stage-large [data-next] { right: 1.2rem; }

.parish .jadwal { background: #fff; }
.parish .jadwal-grid { display: grid; gap: 3rem; align-items: center; }
@media (min-width: 1024px) { .parish .jadwal-grid { grid-template-columns: 4fr 7fr; gap: 6rem; } }
.parish .jadwal h2 { color: var(--ink); margin-bottom: 1.25rem; }
.parish .jadwal-note { color: var(--muted); font-size: 1.05rem; line-height: 1.7; max-width: 24rem; }
.parish .jadwal-list { margin: 0; padding: 0; list-style: none; border-top: 2px solid var(--ink); }
.parish .jadwal-row { display: flex; align-items: baseline; justify-content: space-between; gap: 1.5rem; padding: 1.6rem .75rem; border-bottom: 1px solid #d5dbe7; transition: background .3s; }
.parish .jadwal-day { font-family: var(--display); font-weight: 600; font-size: clamp(1.4rem, 2.6vw, 2rem); color: var(--ink); line-height: 1.2; }
.parish .jadwal-time { font-family: var(--display); font-weight: 700; font-size: clamp(1.9rem, 3.6vw, 2.9rem); color: var(--gold); line-height: 1; white-space: nowrap; font-variant-numeric: lining-nums tabular-nums; }
.parish .today-tag { display: none; margin-left: .75rem; font-family: var(--body); font-size: .8rem; font-weight: 700; color: #fff; background: var(--ink); padding: .2rem .6rem; border-radius: 999px; vertical-align: middle; }
.parish .jadwal-row.is-today { background: #eef2f9; }
.parish .jadwal-row.is-today .today-tag { display: inline-block; }

.parish a:focus-visible, .parish button:focus-visible { outline: 3px solid var(--gold-soft); outline-offset: 3px; }

@media (prefers-reduced-motion: reduce) {
    .parish .hero-slide.is-active, .parish .rise { animation: none; opacity: 1; transform: none; }
    .parish .hero-slide, .parish .stage-slide, .parish .stage-slide img, .parish .profil-slide { transition: none; }
    .parish .bar { display: none; }
}

/* ===== LEGIO MARIA ===== */
.parish .legio { background: #fff; }
.parish .legio-head { text-align: center; margin: 0 auto 3.5rem; }
.parish .legio-head h2 { color: var(--ink); }
.parish .legio-grid { display: grid; gap: 2.5rem; }
@media (min-width: 1024px) { .parish .legio-grid { grid-template-columns: 1fr 1fr; gap: 3rem; } }
.parish .legio-card { background: var(--paper); border-top: 3px solid var(--gold); display: flex; flex-direction: column; }
.parish .legio-photo { aspect-ratio: 16 / 10; background: var(--ink); overflow: hidden; display: grid; place-items: center; }
.parish .legio-photo img { width: 100%; height: 100%; object-fit: cover; display: block; }
.parish .legio-photo .cross { width: 44px; color: rgba(255,255,255,.18); }
.parish .legio-body { padding: 2rem; }
.parish .legio-body h3 { font-family: var(--display); font-weight: 600; font-size: clamp(1.6rem, 2.4vw, 2.1rem); line-height: 1.15; color: var(--ink); margin-bottom: 1.25rem; }
.parish .legio-body .rich { font-size: 1.05rem; }
.parish .legio-grid.is-single { grid-template-columns: 1fr; max-width: 62rem; margin: 0 auto; }
@media (min-width: 900px) {
    .parish .legio-grid.is-single .legio-card { flex-direction: row; }
    .parish .legio-grid.is-single .legio-photo { flex: 0 0 42%; aspect-ratio: auto; min-height: 20rem; }
}
</style>

<div class="parish">

    <!-- HERO SECTION -->
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

    <!-- TENTANG KAMI -->
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
                        {!! $settings['about_us'] ?? '<p>Informasi tentang kami belum diisi oleh admin.</p>' !!}
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- VISI & MISI -->
    <section id="vision-mission" class="sect vm">
        <div class="wrap">
            <h2 class="h-display">Visi &amp; Misi</h2>

            <div class="rich rich-dark" style="max-width: 46rem; margin-top: 3rem; position: relative; z-index: 1;">
                {!! $settings['visi_misi'] ?? '<p>Visi & Misi belum diisi oleh admin.</p>' !!}
            </div>
        </div>
    </section>

        <!-- LEGIO MARIA -->
    @if($legios->isNotEmpty())
    <section id="legio" class="sect legio">
        <div class="wrap">
            <div class="legio-head">
                <h2 class="h-display">Legio Maria</h2>
            </div>

            <div class="legio-grid {{ $legios->count() === 1 ? 'is-single' : '' }}">
                @foreach($legios as $legio)
                    <article class="legio-card">
                        <div class="legio-photo">
                            @if($legio->photo_path)
                                <img src="{{ asset('storage/' . $legio->photo_path) }}" alt="{{ $legio->name }}" loading="lazy">
                            @else
                                <svg class="cross" viewBox="0 0 24 32" aria-hidden="true"><path d="M10 0h4v9h8v4h-8v19h-4V13H2V9h8z" fill="currentColor"/></svg>
                            @endif
                        </div>
                        <div class="legio-body">
                            <h3>{{ $legio->name }}</h3>
                            <div class="rich">{!! $legio->description !!}</div>
                        </div>
                    </article>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    <!-- PROFIL GEREJA (CAROUSEL AUTOMATIC BESAR) -->
    <section id="profile" class="sect profil">
        <div class="wrap">
            <h2 class="h-display">Profil Gereja</h2>

            <div class="profil-carousel-wrapper" data-slider data-interval="5000" data-pause-hover>
                <div class="profil-stage-large" aria-roledescription="carousel" aria-label="Profil Gereja Santo Petrus">
                    @foreach($profil as $i => $p)
                        <figure class="profil-slide {{ $loop->first ? 'is-active' : '' }}" data-slide data-title="{{ $p['caption'] }}">
                            <img src="{{ asset('assets/images/profil/' . $p['file']) }}" alt="{{ $p['caption'] }}" loading="{{ $loop->first ? 'eager' : 'lazy' }}">
                        </figure>
                    @endforeach

                    <div class="profil-cap" data-caption></div>

                    <button type="button" class="round-btn" data-prev aria-label="Foto profil sebelumnya">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M15 5l-7 7 7 7"/></svg>
                    </button>
                    <button type="button" class="round-btn" data-next aria-label="Foto profil berikutnya">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 5l7 7-7 7"/></svg>
                    </button>
                </div>
            </div>
        </div>
    </section>

    <!-- JADWAL MISA -->
    <section id="schedule" class="sect jadwal">
        <div class="wrap">
            <div class="jadwal-grid">
                <div>
                    <h2 class="h-display">Jadwal Misa Paroki</h2>
                    <p class="jadwal-note">{{ $settings['schedule_note'] ?? 'Misa di Gereja Santo Petrus, Tuban. Hari ini ditandai otomatis pada daftar.' }}</p>
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

    <!-- DOKUMENTASI KEGIATAN -->
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

        var startX = null;
        root.addEventListener('touchstart', function (e) { startX = e.touches[0].clientX; }, { passive: true });
        root.addEventListener('touchend', function (e) {
            if (startX === null) return;
            var dx = e.changedTouches[0].clientX - startX;
            if (Math.abs(dx) > 50) go(dx < 0 ? i + 1 : i - 1);
            startX = null;
        });

        if ('IntersectionObserver' in window) {
            new IntersectionObserver(function (entries) {
                visible = entries[0].isIntersecting;
                if (visible) { restartBar(); schedule(); } else { clearTimeout(timer); }
            }, { threshold: 0.25 }).observe(root);
        }

        go(0);
    }

    document.querySelectorAll('.parish [data-slider]').forEach(Slider);

    var today = String(new Date().getDay());
    document.querySelectorAll('.parish .jadwal-row').forEach(function (row) {
        if (row.getAttribute('data-days').split(',').indexOf(today) !== -1) { row.classList.add('is-today'); }
    });

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

@endsection