@extends('layouts.app')

{{--
    CATATAN: font Cormorant Garamond & Nunito Sans TIDAK lagi di-@import dari sini.
    Tambahkan baris berikut ke <head> pada resources/views/layouts/app.blade.php:

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,500;0,600;0,700;1,500;1,600&family=Nunito+Sans:wght@400;600;700&display=swap">
--}}

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
    // 'note' = keterangan kecil di bawah nama hari, boleh null
    $jadwalMisa = [
        ['label' => 'Senin – Kamis & Sabtu', 'note' => 'Misa harian',   'time' => '05.30', 'days' => [1, 2, 3, 4, 6]],
        ['label' => 'Jumat',                 'note' => 'Misa sore',     'time' => '18.00', 'days' => [5]],
        ['label' => 'Sabtu',                 'note' => 'Misa sore',     'time' => '18.00', 'days' => [6]],
        ['label' => 'Minggu',                'note' => 'Misa mingguan', 'time' => '07.00', 'days' => [0]],
    ];
@endphp

<style>
/* =========================================================
   TOKENS & BASE
   ========================================================= */
.parish {
    --ink: #16233f;
    --ink-soft: #22345c;
    --gold: #b98a3e;
    --gold-soft: #e6cf9b;
    --paper: #f3f6fa;
    --line: #d9dfeb;
    --text: #2b3550;
    --muted: #66718c;
    --shadow: 0 22px 44px -22px rgba(22, 35, 63, .38);
    --display: 'Cormorant Garamond', Georgia, serif;
    --body: 'Nunito Sans', system-ui, sans-serif;
    --ease: cubic-bezier(.2, .7, .2, 1);
    --r: 18px;   /* radius kartu, dipakai seragam di semua section */
    --nav: 5rem; /* tinggi navbar (h-20 = 5rem). Ubah kalau tinggi navbar berubah */
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
    font-size: clamp(2.5rem, 4.6vw, 3.8rem);
    text-wrap: balance;
}
.parish .cross { width: 14px; height: auto; fill: currentColor; flex: none; }

/* Section punya tinggi minimum supaya tidak kempis, tapi TIDAK dipaksa
   setinggi layar — jadi teks panjang dari admin tidak pernah terpotong. */
.parish .sect {
    min-height: min(100svh - var(--nav), 44rem);
    display: flex;
    align-items: center;
    padding: clamp(4.5rem, 9vh, 8rem) 0;
    scroll-margin-top: var(--nav);
}

.parish ::selection { background: var(--gold-soft); color: var(--ink); }

/* =========================================================
   JUDUL SECTION (eyebrow + judul + garis emas)
   ========================================================= */
.parish .eyebrow {
    display: inline-flex; align-items: center; gap: .75rem;
    font-size: .78rem; font-weight: 700; letter-spacing: .22em; text-transform: uppercase;
    color: var(--gold); margin-bottom: 1rem;
}
.parish .eyebrow::before { content: ''; width: 2.25rem; height: 1px; background: currentColor; }
.parish .sec-head { margin-bottom: 1.75rem; }
.parish .sec-head h2 { color: var(--ink); }
.parish .sec-head h2::after { content: ''; display: block; width: 3.5rem; height: 3px; background: var(--gold); margin-top: 1.15rem; }

.parish .sec-head.center { text-align: center; margin-bottom: clamp(2rem, 5vh, 3.5rem); }
.parish .sec-head.center .eyebrow { justify-content: center; }
.parish .sec-head.center .eyebrow::after { content: ''; width: 2.25rem; height: 1px; background: currentColor; }
.parish .sec-head.center h2::after { margin-left: auto; margin-right: auto; }
.parish .sec-head.center .rich { margin-inline: auto; }

.parish .sec-head.on-dark h2 { color: #fff; }
.parish .sec-head.on-dark .eyebrow { color: var(--gold-soft); }

/* =========================================================
   TEKS DARI ADMIN (Trix)
   Trix menghasilkan <div>, <h1>, <ul>, <ol>, <blockquote>, bukan hanya <p>,
   jadi semuanya diberi style supaya hasil edit admin tetap rapi.
   ========================================================= */
.parish .rich { font-size: 1.1rem; line-height: 1.85; max-width: 42rem; }
.parish .rich p, .parish .rich div { margin: 0 0 1.15em; }
.parish .rich p, .parish .rich div, .parish .rich li { text-wrap: pretty; }
.parish .rich > :last-child { margin-bottom: 0; }
.parish .rich h1, .parish .rich h2, .parish .rich h3 {
    font-family: var(--display); font-weight: 600; line-height: 1.15;
    font-size: 1.8rem; color: var(--ink); margin: 1.6em 0 .55em;
}
.parish .rich > h1:first-child, .parish .rich > h2:first-child, .parish .rich > h3:first-child { margin-top: 0; }
.parish .rich ul { list-style: disc; padding-left: 1.3em; margin: 0 0 1.15em; }
.parish .rich ol { list-style: decimal; padding-left: 1.3em; margin: 0 0 1.15em; }
.parish .rich li { margin-bottom: .4em; }
.parish .rich blockquote { border-left: 3px solid var(--gold); padding-left: 1.2em; margin: 0 0 1.15em; font-style: italic; }
.parish .rich a { color: var(--gold); text-decoration: underline; text-underline-offset: 3px; }
.parish .rich strong { font-weight: 700; }
.parish .rich em { font-style: italic; }
.parish .rich-dark { color: rgba(255,255,255,.86); }

/* =========================================================
   HERO
   ========================================================= */
.parish .hero { position: relative; min-height: 100svh; overflow: hidden; display: flex; align-items: flex-end; color: #fff; background: var(--ink); }
.parish .hero-slide, .parish .stage-slide img { background-size: cover; background-position: center; }
.parish .hero-slide { position: absolute; inset: 0; opacity: 0; transform: scale(1.09); transition: opacity 1.6s ease; }
.parish .hero-slide.is-active { opacity: 1; animation: drift 12s ease-out forwards; }
@keyframes drift { from { transform: scale(1); } to { transform: scale(1.09); } }
.parish .hero-shade {
    position: absolute; inset: 0;
    background: linear-gradient(to top, rgba(12,21,43,.94) 0%, rgba(12,21,43,.55) 40%, rgba(12,21,43,.18) 75%),
                linear-gradient(to bottom, rgba(12,21,43,.6) 0%, rgba(12,21,43,0) 24%),
                radial-gradient(ellipse at 50% 60%, rgba(12,21,43,0) 40%, rgba(12,21,43,.4) 100%);
}
.parish .hero-body { position: relative; width: 100%; padding-bottom: 6.5rem; padding-top: 9rem; }
.parish .hero-title {
    font-family: var(--display); font-weight: 600; line-height: 1;
    font-size: clamp(3rem, 8vw, 6.6rem); max-width: 14ch; text-wrap: balance;
    text-shadow: 0 2px 30px rgba(0,0,0,.35);
}
.parish .hero-sub { margin-top: 1.5rem; max-width: 36rem; font-size: 1.15rem; line-height: 1.7; color: rgba(255,255,255,.88); }
.parish .hero-sub p, .parish .hero-sub div { margin: 0; }
.parish .hero-actions { margin-top: 2.25rem; display: flex; flex-wrap: wrap; gap: .75rem 1rem; align-items: center; }

.parish .hero-chip {
    display: inline-flex; align-items: center; gap: .65rem;
    padding: .45rem 1.1rem; margin-bottom: 1.5rem;
    border: 1px solid rgba(255,255,255,.28); border-radius: 999px;
    background: rgba(255,255,255,.1); backdrop-filter: blur(8px);
    font-size: .92rem; font-weight: 600; letter-spacing: .02em;
}
.parish .hero-chip[hidden] { display: none; }
.parish .pulse { width: .5rem; height: .5rem; border-radius: 50%; background: var(--gold-soft); animation: pulse 2.2s infinite; }
@keyframes pulse {
    0%   { box-shadow: 0 0 0 0 rgba(230,207,155,.6); }
    70%  { box-shadow: 0 0 0 .65rem rgba(230,207,155,0); }
    100% { box-shadow: 0 0 0 0 rgba(230,207,155,0); }
}

.parish .btn-gold {
    display: inline-block; padding: .95rem 2.1rem; color: #fff; font-weight: 700;
    background: linear-gradient(135deg, #c79646, var(--gold) 55%, #a87c33);
    border-radius: 999px; text-decoration: none; letter-spacing: .01em;
    box-shadow: 0 10px 24px -10px rgba(185,138,62,.8);
    transition: filter .25s, transform .25s, box-shadow .25s;
}
.parish .btn-gold:hover { filter: brightness(1.07); transform: translateY(-2px); box-shadow: 0 16px 30px -12px rgba(185,138,62,.9); }
.parish .btn-ghost {
    display: inline-block; padding: .95rem 2.1rem; color: #fff; font-weight: 700; text-decoration: none;
    border: 1px solid rgba(255,255,255,.55); border-radius: 999px; background: rgba(255,255,255,.06);
    backdrop-filter: blur(6px); transition: background .25s, border-color .25s, transform .25s;
}
.parish .btn-ghost:hover { background: rgba(255,255,255,.18); border-color: #fff; transform: translateY(-2px); }
.parish .btn-gold:active, .parish .btn-ghost:active { transform: translateY(0); }

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

/* Petunjuk scroll: memberi tahu bahwa masih ada isi di bawah hero */
.parish .scroll-cue {
    position: absolute; left: 50%; bottom: 2.25rem; transform: translateX(-50%);
    z-index: 3; width: 2.6rem; height: 2.6rem; display: grid; place-items: center;
    color: rgba(255,255,255,.8); border: 1px solid rgba(255,255,255,.35);
    border-radius: 999px; background: rgba(255,255,255,.07); backdrop-filter: blur(6px);
    animation: cue 2.4s var(--ease) infinite;
    transition: background .25s, color .25s;
}
.parish .scroll-cue:hover { background: rgba(255,255,255,.2); color: #fff; }
.parish .scroll-cue svg { width: 1.05rem; height: 1.05rem; }
@keyframes cue {
    0%, 100% { transform: translate(-50%, 0); }
    50%      { transform: translate(-50%, 7px); }
}
@media (max-width: 640px) { .parish .scroll-cue { display: none; } }

.parish .bar { position: absolute; left: 0; bottom: 0; height: 3px; width: 100%; background: rgba(255,255,255,.15); }
.parish .bar i { display: block; height: 100%; width: 0; background: var(--gold); }
.parish .bar i.run { animation: fill var(--interval, 6000ms) linear forwards; }
@keyframes fill { to { width: 100%; } }

.parish .rise { opacity: 0; transform: translateY(18px); animation: rise .9s var(--ease) forwards; }
.parish .rise.d1 { animation-delay: .25s; } .parish .rise.d2 { animation-delay: .5s; } .parish .rise.d3 { animation-delay: .75s; }
@keyframes rise { to { opacity: 1; transform: none; } }

/* Muncul halus saat di-scroll (hanya aktif jika JavaScript jalan, jadi konten tidak pernah hilang) */
.parish.js-on .reveal {
    opacity: 0; transform: translateY(26px);
    transition: opacity .9s var(--ease), transform .9s var(--ease);
    transition-delay: var(--d, 0s);
}
.parish.js-on .reveal.in { opacity: 1; transform: none; }

/* =========================================================
   TENTANG KAMI
   ========================================================= */
.parish .about { background: #fff; }
.parish .about-grid { display: grid; gap: 3.5rem; align-items: center; }
@media (min-width: 1024px) { .parish .about-grid { grid-template-columns: 5fr 7fr; gap: 6rem; } }
.parish .portrait { position: relative; margin: 0 1.25rem 1.25rem 0; }
.parish .portrait::before { content: ''; position: absolute; inset: 1.25rem -1.25rem -1.25rem 1.25rem; border: 2px solid var(--gold); z-index: 0; }
.parish .portrait img {
    position: relative; z-index: 1; display: block; width: 100%;
    aspect-ratio: 4 / 5; max-height: 36rem; object-fit: cover;
    border-radius: 4px; box-shadow: var(--shadow);
}
/* paragraf pembuka dibuat sebagai lead */
.parish .about .rich > :first-child { font-size: 1.3rem; line-height: 1.65; color: var(--ink); }

/* =========================================================
   VISI & MISI
   ========================================================= */
.parish .vm { background: var(--ink); color: #fff; position: relative; overflow: hidden; }
.parish .vm::after {
    content: ''; position: absolute; right: -6rem; top: 50%; width: 30rem; height: 40rem; transform: translateY(-50%);
    background: no-repeat center / contain url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 32'%3E%3Cpath fill='white' fill-opacity='.04' d='M10 0h4v9h8v4h-8v19h-4V13H2V9h8z'/%3E%3C/svg%3E");
    pointer-events: none;
}
.parish .vm .rich { max-width: 46rem; }
.parish .vm .rich h1, .parish .vm .rich h2, .parish .vm .rich h3 {
    font-size: clamp(1.9rem, 3vw, 2.5rem); color: var(--gold-soft); margin: 2.75rem 0 1rem;
}
.parish .vm .rich > h1:first-child, .parish .vm .rich > h2:first-child, .parish .vm .rich > h3:first-child { margin-top: 0; }
.parish .vm .rich ul { list-style: none; padding: 0; counter-reset: misi; display: grid; gap: 1.1rem; }
.parish .vm .rich ul li { counter-increment: misi; position: relative; padding-left: 3.4rem; margin: 0; }
.parish .vm .rich ul li::before {
    content: counter(misi, decimal-leading-zero); position: absolute; left: 0; top: -.15rem;
    font-family: var(--display); font-size: 1.6rem; font-weight: 700; color: var(--gold);
}

/* =========================================================
   LEGIO MARIA
   ========================================================= */
.parish .legio { background: var(--paper); }
.parish .legio-grid { display: grid; gap: 2.5rem; }
/* auto-fit: 3 kartu tidak lagi menyisakan satu kartu yatim di baris bawah */
@media (min-width: 1024px) { .parish .legio-grid { grid-template-columns: repeat(auto-fit, minmax(23rem, 1fr)); gap: 2.5rem; } }
.parish .legio-item { display: flex; }
.parish .legio-card {
    position: relative; flex: 1; width: 100%; display: flex; flex-direction: column; overflow: hidden;
    background: #fff; border: 1px solid var(--line); border-radius: var(--r);
    box-shadow: var(--shadow); transition: transform .4s var(--ease), box-shadow .4s var(--ease);
}
/* garis emas atas dibuat dengan ::before supaya ikut lekuk radius */
.parish .legio-card::before {
    content: ''; position: absolute; left: 0; right: 0; top: 0; height: 3px;
    background: var(--gold); z-index: 2;
}
.parish .legio-card:hover { transform: translateY(-5px); box-shadow: 0 30px 50px -22px rgba(22,35,63,.45); }
.parish .legio-photo { position: relative; aspect-ratio: 16 / 10; background: var(--ink); overflow: hidden; display: grid; place-items: center; }
.parish .legio-photo::after { content: ''; position: absolute; inset: 0; background: linear-gradient(to top, rgba(12,21,43,.35), transparent 55%); pointer-events: none; }
.parish .legio-photo img { width: 100%; height: 100%; object-fit: cover; display: block; transition: transform .9s var(--ease); }
.parish .legio-card:hover .legio-photo img { transform: scale(1.05); }
.parish .legio-photo .cross { width: 44px; color: rgba(255,255,255,.18); }
.parish .legio-body { padding: 1.75rem 2rem 2rem; }
.parish .legio-body h3 {
    font-family: var(--display); font-weight: 600; font-size: clamp(1.6rem, 2.4vw, 2.1rem);
    line-height: 1.15; color: var(--ink); margin-bottom: 1rem;
}
.parish .legio-body .rich { font-size: 1.02rem; line-height: 1.72; max-width: none; }
.parish .legio-grid.is-single { grid-template-columns: 1fr; max-width: 62rem; margin: 0 auto; }
@media (min-width: 900px) {
    .parish .legio-grid.is-single .legio-card { flex-direction: row; }
    .parish .legio-grid.is-single .legio-photo { flex: 0 0 42%; aspect-ratio: auto; min-height: 18rem; }
    .parish .legio-grid.is-single .legio-body { display: flex; flex-direction: column; justify-content: center; padding: 2.5rem; }
}
@media (min-width: 1024px) { .parish .legio-grid:not(.is-single) .legio-photo { aspect-ratio: 2 / 1; } }

/* =========================================================
   PROFIL GEREJA (carousel, latar blur dari foto itu sendiri)
   ========================================================= */
.parish .profil { background: #fff; }
.parish .profil-carousel-wrapper { position: relative; width: 100%; max-width: 58rem; margin: 0 auto; }
.parish .profil-stage-large {
    position: relative; width: 100%; aspect-ratio: 16 / 9;
    background: var(--ink); border-radius: var(--r); overflow: hidden; touch-action: pan-y;
    box-shadow: var(--shadow), 0 0 0 10px rgba(185,138,62,.1);
}
.parish .profil-slide { position: absolute; inset: 0; margin: 0; opacity: 0; transition: opacity .7s ease; display: grid; place-items: center; overflow: hidden; }
.parish .profil-slide.is-active { opacity: 1; z-index: 1; }
.parish .profil-slide::before {
    content: ''; position: absolute; inset: -2rem; background: var(--bg) center / cover no-repeat;
    filter: blur(28px) saturate(1.15); opacity: .6; transform: scale(1.1);
}
.parish .profil-slide img { position: relative; display: block; max-width: 100%; max-height: 100%; width: auto; height: auto; object-fit: contain; }
.parish .profil-cap {
    position: absolute; left: 0; right: 0; bottom: 0; z-index: 2; pointer-events: none;
    padding: 4rem 1.5rem 1.4rem; color: #fff; text-align: center;
    font-family: var(--display); font-size: clamp(1.2rem, 2vw, 1.6rem); font-weight: 600;
    background: linear-gradient(to top, rgba(12,21,43,.85), transparent);
}
.parish .profil-stage-large .round-btn { position: absolute; top: 50%; transform: translateY(-50%); z-index: 3; }
.parish .profil-stage-large [data-prev] { left: 1rem; }
.parish .profil-stage-large [data-next] { right: 1rem; }

.parish .dots { display: flex; justify-content: center; gap: .55rem; margin-top: 1.4rem; }
.parish .dot { width: .55rem; height: .55rem; border-radius: 999px; border: 0; padding: 0; background: #c3cbdc; cursor: pointer; transition: width .3s var(--ease), background .3s; }
.parish .dot:hover { background: #9aa6c0; }
.parish .dot[aria-current="true"] { width: 1.8rem; background: var(--gold); }
.parish .is-single .dots { display: none; }

/* =========================================================
   JADWAL MISA
   ========================================================= */
.parish .jadwal { background: var(--paper); }
.parish .jadwal-grid { display: grid; gap: 3rem; align-items: center; }
@media (min-width: 1024px) { .parish .jadwal-grid { grid-template-columns: 4fr 7fr; gap: 6rem; } }
.parish .jadwal-note { color: var(--muted); font-size: 1.05rem; line-height: 1.7; max-width: 24rem; }
.parish .jadwal-list {
    position: relative; margin: 0; padding: 0; list-style: none; background: #fff;
    border: 1px solid var(--line); border-radius: var(--r); overflow: hidden; box-shadow: var(--shadow);
}
.parish .jadwal-list::before {
    content: ''; position: absolute; left: 0; right: 0; top: 0; height: 3px;
    background: var(--ink); z-index: 2;
}
.parish .jadwal-row {
    position: relative; display: flex; align-items: center; justify-content: space-between; gap: 1.5rem;
    padding: 1.5rem 1.75rem; border-bottom: 1px solid var(--line); transition: background .3s;
}
.parish .jadwal-row:last-child { border-bottom: 0; }
.parish .jadwal-row::before { content: ''; position: absolute; left: 0; top: 0; bottom: 0; width: 4px; background: var(--gold); transform: scaleY(0); transition: transform .35s var(--ease); }
.parish .jadwal-row:hover { background: #f8fafd; }
.parish .jadwal-day { font-family: var(--display); font-weight: 600; font-size: clamp(1.4rem, 2.6vw, 2rem); color: var(--ink); line-height: 1.2; }
.parish .jadwal-sub {
    display: block; margin-top: .4rem;
    font-family: var(--body); font-size: .74rem; font-weight: 700;
    letter-spacing: .18em; text-transform: uppercase; color: var(--muted);
}
.parish .jadwal-time { font-family: var(--display); font-weight: 700; font-size: clamp(1.9rem, 3.6vw, 2.9rem); color: var(--gold); line-height: 1; white-space: nowrap; font-variant-numeric: lining-nums tabular-nums; }
.parish .today-tag { display: none; margin-left: .75rem; font-family: var(--body); font-size: .78rem; font-weight: 700; color: #fff; background: var(--gold); padding: .2rem .65rem; border-radius: 999px; vertical-align: middle; }
.parish .jadwal-row.is-today { background: #fbf6ea; }
.parish .jadwal-row.is-today::before { transform: scaleY(1); }
.parish .jadwal-row.is-today .today-tag { display: inline-block; }
.parish .jadwal-row.is-today .jadwal-sub { color: var(--gold); }

/* =========================================================
   DOKUMENTASI
   ========================================================= */
.parish .docs { background: #fff; min-height: 0; display: block; }
.parish .stage { position: relative; aspect-ratio: 16 / 9; max-height: 82svh; width: 100%; overflow: hidden; background: var(--ink); border-radius: var(--r); box-shadow: var(--shadow); touch-action: pan-y; }
.parish .stage-slide { position: absolute; inset: 0; margin: 0; opacity: 0; transition: opacity 1s ease; }
.parish .stage-slide.is-active { opacity: 1; z-index: 1; }
.parish .stage-slide button { display: block; width: 100%; height: 100%; padding: 0; border: 0; cursor: zoom-in; background: none; }
.parish .stage-slide img { width: 100%; height: 100%; object-fit: cover; display: block; transform: scale(1.04); transition: transform 7s ease-out; }
.parish .stage-slide.is-active img { transform: scale(1); }
.parish .stage-cap { position: absolute; left: 0; right: 0; bottom: 0; z-index: 2; pointer-events: none; padding: 4rem 1.5rem 1.5rem; color: #fff; font-family: var(--display); font-size: clamp(1.2rem, 2.2vw, 1.8rem); font-weight: 600; background: linear-gradient(to top, rgba(12,21,43,.8), transparent); }
.parish .stage-count {
    position: absolute; top: 1rem; right: 1rem; z-index: 3; padding: .3rem .9rem; border-radius: 999px;
    background: rgba(12,21,43,.55); backdrop-filter: blur(6px); color: #fff;
    font-family: var(--display); font-size: 1.05rem; letter-spacing: .06em;
}
.parish .stage .round-btn { position: absolute; top: 50%; transform: translateY(-50%); z-index: 3; }
.parish .stage [data-prev] { left: 1rem; } .parish .stage [data-next] { right: 1rem; }
.parish .thumbs { display: flex; gap: .6rem; margin-top: .9rem; overflow-x: auto; padding: .35rem .35rem .5rem; scrollbar-width: thin; scrollbar-color: var(--gold-soft) transparent; }
.parish .thumbs::-webkit-scrollbar { height: 6px; }
.parish .thumbs::-webkit-scrollbar-thumb { background: var(--gold-soft); border-radius: 999px; }
.parish .thumbs::-webkit-scrollbar-track { background: transparent; }
.parish .thumb { flex: none; width: 7.5rem; aspect-ratio: 16 / 10; border: 0; padding: 0; cursor: pointer; opacity: .55; border-radius: 8px; overflow: hidden; outline: 2px solid transparent; outline-offset: 2px; transition: opacity .25s, outline-color .25s; }
.parish .thumb img { width: 100%; height: 100%; object-fit: cover; display: block; }
.parish .thumb:hover { opacity: .85; }
.parish .thumb[aria-current="true"] { opacity: 1; outline-color: var(--gold); }
.parish .is-single [data-prev], .parish .is-single [data-next], .parish .is-single .thumbs, .parish .is-single .stage-count { display: none; }
.parish .empty-docs { padding: 4rem 1.5rem; text-align: center; border: 1px dashed #b8c1d4; color: var(--muted); border-radius: var(--r); }

.parish dialog.lightbox { border: 0; padding: 0; background: transparent; max-width: 94vw; max-height: 92vh; overflow: visible; }
.parish dialog.lightbox::backdrop { background: rgba(10,16,32,.92); }
.parish .lightbox img { display: block; max-width: 94vw; max-height: 82vh; margin: 0 auto; object-fit: contain; border-radius: 8px; }
.parish .lightbox p { color: #fff; text-align: center; margin-top: .9rem; font-family: var(--display); font-size: 1.3rem; }
.parish .lightbox .close { position: absolute; top: -3rem; right: 0; }

/* =========================================================
   DEKORASI LATAR (Legio Maria & Profil Gereja)
   ========================================================= */
.parish .deco-sect { position: relative; overflow: hidden; isolation: isolate; }
.parish .deco-sect > .wrap { position: relative; z-index: 1; }
.parish .bg-deco { position: absolute; inset: 0; z-index: 0; pointer-events: none; overflow: hidden; }

/* titik-titik halus, memudar ke tengah supaya konten tetap bersih */
.parish .bg-dots {
    position: absolute; inset: 0;
    background-image: radial-gradient(rgba(22,35,63,.14) 1.2px, transparent 1.5px);
    background-size: 26px 26px;
    -webkit-mask-image: radial-gradient(ellipse at center, transparent 28%, #000 88%);
            mask-image: radial-gradient(ellipse at center, transparent 28%, #000 88%);
}

/* bola cahaya lembut yang melayang pelan */
.parish .orb { position: absolute; border-radius: 50%; filter: blur(60px); animation: deco-float 18s ease-in-out infinite alternate; }
.parish .orb.gold { background: radial-gradient(circle, rgba(230,207,155,.95), rgba(230,207,155,0) 70%); opacity: .7; }
.parish .orb.navy { background: radial-gradient(circle, rgba(34,52,92,.34), rgba(34,52,92,0) 70%); opacity: .6; animation-duration: 22s; animation-direction: alternate-reverse; }
.parish .orb.o1 { width: 30rem; height: 30rem; top: -10rem; left: -8rem; }
.parish .orb.o2 { width: 34rem; height: 34rem; bottom: -12rem; right: -9rem; }
.parish .orb.o3 { width: 26rem; height: 26rem; top: 30%; right: -8rem; }
.parish .orb.o4 { width: 28rem; height: 28rem; bottom: -10rem; left: -8rem; }
@keyframes deco-float { from { transform: translate3d(0,0,0) scale(1); } to { transform: translate3d(50px,-36px,0) scale(1.14); } }

/* tasbih (rosario) yang berputar sangat pelan */
.parish .bg-rosary { position: absolute; color: var(--gold); opacity: .26; }
.parish .bg-rosary.r1 { width: min(38rem, 95vw); top: -9rem; right: -11rem; animation: deco-spin 150s linear infinite; }
.parish .bg-rosary.r2 { width: min(26rem, 70vw); bottom: -8rem; left: -9rem; opacity: .2; animation: deco-spin 120s linear infinite reverse; }
@keyframes deco-spin { to { transform: rotate(360deg); } }

/* sinar cahaya dari atas, di belakang carousel profil */
.parish .bg-rays {
    position: absolute; left: 50%; top: -2rem; width: 150%; height: 85%; transform: translateX(-50%);
    background: conic-gradient(from 120deg at 50% 0%,
        transparent 0deg, rgba(230,207,155,.34) 8deg, transparent 16deg,
        rgba(230,207,155,.22) 28deg, transparent 38deg,
        rgba(230,207,155,.36) 52deg, transparent 62deg,
        rgba(230,207,155,.24) 74deg, transparent 84deg,
        rgba(230,207,155,.32) 96deg, transparent 106deg, transparent 360deg);
    -webkit-mask-image: linear-gradient(to bottom, #000, transparent 85%);
            mask-image: linear-gradient(to bottom, #000, transparent 85%);
    animation: deco-glow 9s ease-in-out infinite alternate;
}
@keyframes deco-glow { from { opacity: .55; } to { opacity: 1; } }

/* lengkung jendela gereja di kiri & kanan */
.parish .bg-arch { position: absolute; bottom: 0; color: var(--gold); opacity: .3; display: none; }
.parish .bg-arch.a-left { left: 3%; width: 11rem; }
.parish .bg-arch.a-right { right: 3%; width: 8rem; transform: scaleX(-1); opacity: .22; }
@media (min-width: 1024px) { .parish .bg-arch { display: block; } }

/* =========================================================
   LAIN-LAIN
   ========================================================= */
.parish a:focus-visible, .parish button:focus-visible { outline: 3px solid var(--gold-soft); outline-offset: 3px; }

@media (prefers-reduced-motion: reduce) {
    .parish .hero-slide.is-active, .parish .rise, .parish .pulse { animation: none; opacity: 1; transform: none; }
    .parish .hero-slide, .parish .stage-slide, .parish .stage-slide img, .parish .profil-slide { transition: none; }
    .parish.js-on .reveal { opacity: 1; transform: none; transition: none; }
    .parish .legio-card, .parish .legio-photo img, .parish .btn-gold, .parish .btn-ghost { transition: none; }
    .parish .scroll-cue { animation: none; }
    .parish .orb, .parish .bg-rosary, .parish .bg-rays { animation: none; }
    .parish .bar { display: none; }
}
</style>

<div class="parish">
<script>document.currentScript.parentNode.classList.add('js-on');</script>

    <!-- HERO SECTION -->
    <section id="hero" class="hero" data-hero data-slider data-interval="6500" aria-roledescription="carousel" aria-label="Foto kegiatan paroki">
        @foreach($heroSlides as $i => $slide)
            <div class="hero-slide {{ $i === 0 ? 'is-active' : '' }}" data-slide
                 data-title="{{ $slide['title'] }}"
                 style="background-image: url('{{ $slide['src'] }}');"></div>
        @endforeach
        <div class="hero-shade"></div>

        <div class="hero-body">
            <div class="wrap">
                <p class="hero-chip rise" id="hero-today" hidden>
                    <span class="pulse" aria-hidden="true"></span>
                    <span data-text></span>
                </p>
                <h1 class="hero-title rise d1">{{ $settings['hero_title'] ?? 'Paroki Santo Petrus Tuban' }}</h1>
                <div class="hero-sub rise d2">
                    {!! $settings['hero_subtitle'] ?? '<p>Tempat pertumbuhan rohani dan pelayanan kasih bagi sesama.</p>' !!}
                </div>
                <div class="hero-actions rise d3">
                    <a href="#about" class="btn-gold">Kenali kami</a>
                    <a href="#schedule" class="btn-ghost">Lihat jadwal misa</a>
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

        <a href="#about" class="scroll-cue" aria-label="Lanjut ke bagian Tentang Kami">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                <path d="M12 5v14M5 12l7 7 7-7"/>
            </svg>
        </a>

        <div class="bar"><i data-bar></i></div>
    </section>

    <!-- TENTANG KAMI -->
    <section id="about" class="sect about">
        <div class="wrap">
            <div class="about-grid">
                <figure class="portrait reveal">
                    <img src="{{ $aboutImage }}" alt="Kegiatan umat Paroki Santo Petrus Tuban" loading="lazy">
                </figure>

                <div class="reveal" style="--d: .12s;">
                    <div class="sec-head">
                        <span class="eyebrow">Mengenal Kami</span>
                        <h2 class="h-display">Tentang Kami</h2>
                    </div>
                    <div class="rich">
                        {!! $settings['about_us'] ?? '<p>Informasi tentang kami belum diisi oleh admin.</p>' !!}
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- VISI & MISI -->
    <section id="vision-mission" class="sect vm">
        <div class="wrap">
            <div class="sec-head on-dark reveal">
                <span class="eyebrow">Arah Pelayanan</span>
                <h2 class="h-display">Visi &amp; Misi</h2>
            </div>

            <div class="rich rich-dark reveal" style="margin-top: 2.5rem; position: relative; z-index: 1; --d: .12s;">
                {!! $settings['visi_misi'] ?? '<p>Visi & Misi belum diisi oleh admin.</p>' !!}
            </div>
        </div>
    </section>

    <!-- LEGIO MARIA -->
    @if($legios->isNotEmpty())
    <section id="legio" class="sect legio deco-sect">
        <div class="bg-deco" aria-hidden="true">
            <div class="bg-dots"></div>
            <div class="orb gold o1"></div>
            <div class="orb navy o2"></div>
            <svg class="bg-rosary r1" viewBox="0 0 600 600">
                <circle cx="300" cy="300" r="260" fill="none" stroke="currentColor" stroke-width="9" stroke-linecap="round" stroke-dasharray="0 29.7023"/>
                <circle cx="300" cy="300" r="260" fill="none" stroke="currentColor" stroke-width="20" stroke-linecap="round" stroke-dasharray="0 326.7256"/>
                <circle cx="300" cy="300" r="205" fill="none" stroke="currentColor" stroke-width="1.5" stroke-dasharray="2 10"/>
            </svg>
            <svg class="bg-rosary r2" viewBox="0 0 600 600">
                <circle cx="300" cy="300" r="260" fill="none" stroke="currentColor" stroke-width="9" stroke-linecap="round" stroke-dasharray="0 29.7023"/>
                <circle cx="300" cy="300" r="260" fill="none" stroke="currentColor" stroke-width="20" stroke-linecap="round" stroke-dasharray="0 326.7256"/>
            </svg>
        </div>
        <div class="wrap">
            <div class="sec-head center reveal">
                <span class="eyebrow">Pelayanan Umat</span>
                <h2 class="h-display">Legio Maria</h2>
            </div>

            <div class="legio-grid {{ $legios->count() === 1 ? 'is-single' : '' }}">
                @foreach($legios as $legio)
                    <div class="legio-item reveal" style="--d: {{ min($loop->index, 3) * 0.12 }}s;">
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
                    </div>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    <!-- PROFIL GEREJA (CAROUSEL AUTOMATIC) -->
    <section id="profile" class="sect profil deco-sect">
        <div class="bg-deco" aria-hidden="true">
            <div class="bg-rays"></div>
            <div class="orb gold o3"></div>
            <div class="orb navy o4"></div>
            <svg class="bg-arch a-left" viewBox="0 0 200 360">
                <path d="M10 360V110a90 90 0 0 1 180 0V360" fill="none" stroke="currentColor" stroke-width="2"/>
                <path d="M34 360V112a66 66 0 0 1 132 0V360" fill="none" stroke="currentColor" stroke-width="1.2"/>
                <path d="M100 42v56M76 62h48" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
            </svg>
            <svg class="bg-arch a-right" viewBox="0 0 200 360">
                <path d="M10 360V110a90 90 0 0 1 180 0V360" fill="none" stroke="currentColor" stroke-width="2"/>
                <path d="M34 360V112a66 66 0 0 1 132 0V360" fill="none" stroke="currentColor" stroke-width="1.2"/>
                <path d="M100 42v56M76 62h48" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
            </svg>
        </div>
        <div class="wrap">
            <div class="sec-head center reveal">
                <span class="eyebrow">Lingkungan Gereja</span>
                <h2 class="h-display">Profil Gereja</h2>
            </div>

            <div class="profil-carousel-wrapper reveal" data-slider data-interval="5000" data-pause-hover style="--d: .1s;">
                <div class="profil-stage-large" aria-roledescription="carousel" aria-label="Profil Gereja Santo Petrus">
                    @foreach($profil as $i => $p)
                        <figure class="profil-slide {{ $loop->first ? 'is-active' : '' }}" data-slide data-title="{{ $p['caption'] }}"
                                style="--bg: url('{{ asset('assets/images/profil/' . $p['file']) }}');">
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

                <div class="dots">
                    @foreach($profil as $p)
                        <button type="button" class="dot" data-thumb aria-label="Tampilkan foto profil {{ $loop->iteration }}" aria-current="{{ $loop->first ? 'true' : 'false' }}"></button>
                    @endforeach
                </div>
            </div>
        </div>
    </section>

    <!-- JADWAL MISA -->
    <section id="schedule" class="sect jadwal">
        <div class="wrap">
            <div class="jadwal-grid">
                <div class="reveal">
                    <div class="sec-head">
                        <span class="eyebrow">Perayaan Ekaristi</span>
                        <h2 class="h-display">Jadwal Misa Paroki</h2>
                    </div>
                    <p class="jadwal-note">{{ $settings['schedule_note'] ?? 'Misa di Gereja Santo Petrus, Tuban. Hari ini ditandai otomatis pada daftar.' }}</p>
                </div>

                <ul class="jadwal-list reveal" style="--d: .12s;">
                    @foreach($jadwalMisa as $misa)
                        <li class="jadwal-row" data-days="{{ implode(',', $misa['days']) }}">
                            <span class="jadwal-day">
                                {{ $misa['label'] }}<span class="today-tag">Hari ini</span>
                                @if (!empty($misa['note']))
                                    <small class="jadwal-sub">{{ $misa['note'] }}</small>
                                @endif
                            </span>
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
            <div class="sec-head center reveal">
                <span class="eyebrow">Momen Kebersamaan</span>
                <h2 class="h-display">Dokumentasi Kegiatan</h2>
                @if(!empty($settings['doc_description']))
                    <div class="rich" style="color: var(--muted); font-size: 1.05rem; max-width: 40rem; margin: 1.5rem auto 0;">{!! $settings['doc_description'] !!}</div>
                @endif
            </div>

            @if($documentations->isEmpty())
                <div class="empty-docs reveal">Belum ada foto dokumentasi. Foto yang ditambahkan admin akan tampil di sini.</div>
            @else
                <div data-slider data-interval="5500" data-pause-hover class="reveal {{ $documentations->count() < 2 ? 'is-single' : '' }}" style="--d: .1s;">
                    <div class="stage" data-stage aria-roledescription="carousel" aria-label="Galeri dokumentasi kegiatan">
                        @foreach($documentations as $i => $doc)
                            <figure class="stage-slide {{ $i === 0 ? 'is-active' : '' }}" data-slide data-title="{{ $doc->title }}">
                                <button type="button" data-open aria-label="Perbesar foto{{ $doc->title ? ': ' . $doc->title : '' }}">
                                    <img src="{{ asset('storage/' . $doc->image_path) }}" alt="{{ $doc->title ?? 'Dokumentasi kegiatan' }}" loading="{{ $i === 0 ? 'eager' : 'lazy' }}">
                                </button>
                            </figure>
                        @endforeach

                        <span class="stage-count" data-count aria-live="off"></span>
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

    // Tandai jadwal hari ini + tampilkan chip "Misa hari ini" di hero
    var today = String(new Date().getDay());
    var todayTimes = [];
    document.querySelectorAll('.parish .jadwal-row').forEach(function (row) {
        if (row.getAttribute('data-days').split(',').indexOf(today) !== -1) {
            row.classList.add('is-today');
            var t = row.querySelector('.jadwal-time');
            if (t) todayTimes.push(t.textContent.trim());
        }
    });
    var chip = document.getElementById('hero-today');
    if (chip && todayTimes.length) {
        chip.querySelector('[data-text]').textContent = 'Misa hari ini · ' + todayTimes.join(' & ');
        chip.hidden = false;
    }

    // Muncul halus saat di-scroll
    var reveals = document.querySelectorAll('.parish .reveal');
    if ('IntersectionObserver' in window && !reduce) {
        var io = new IntersectionObserver(function (entries) {
            entries.forEach(function (e) {
                if (e.isIntersecting) { e.target.classList.add('in'); io.unobserve(e.target); }
            });
        }, { threshold: 0.12, rootMargin: '0px 0px -6% 0px' });
        reveals.forEach(function (el) { io.observe(el); });
    } else {
        reveals.forEach(function (el) { el.classList.add('in'); });
    }

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
