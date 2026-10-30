@extends('layouts.admin')

@section('content')

@php
    // Tab aktif awal: kalau ada error validasi legio, langsung buka tab Legio.
    $activeTab = $errors->legio->any() ? 'legio' : 'konten';
@endphp

<style>
/* =========================================================
   ADMIN UI — Pengaturan Konten
   Tekstur latar ditangani oleh layout. Di sini fokus ke kartu & form.
   ========================================================= */
.admin-ui{
    --ink:#16233f; --ink-soft:#22345c;
    --gold:#b98a3e; --gold-soft:#e6cf9b; --gold-bg:#fbf6ea;
    --paper:#f3f6fa; --line:#e4e9f2; --line-strong:#d3dae7;
    --text:#2b3550; --muted:#6b7793;
    --danger:#c0392b; --danger-bg:#fdeceb;
    --display:'Cormorant Garamond', Georgia, serif;
    --body:'Nunito Sans', system-ui, sans-serif;
    --r:18px;
    --shadow:0 14px 36px -24px rgba(22,35,63,.40);
    --shadow-lg:0 30px 60px -34px rgba(22,35,63,.5);
    --ease:cubic-bezier(.2,.7,.2,1);
    --cross-ink:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 32'%3E%3Cpath fill='%2316233f' d='M10 0h4v9h8v4h-8v19h-4V13H2V9h8z'/%3E%3C/svg%3E");
    --cross-gold:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 32'%3E%3Cpath fill='%23e6cf9b' d='M10 0h4v9h8v4h-8v19h-4V13H2V9h8z'/%3E%3C/svg%3E");
    font-family:var(--body); color:var(--text);
    max-width:70rem; margin:0 auto;
}
.admin-ui *, .admin-ui *::before, .admin-ui *::after{ box-sizing:border-box; }
.admin-ui ::selection{ background:var(--gold-soft); color:var(--ink); }

/* ---------- Header halaman (ringkas) ---------- */
.admin-ui .page-head{ margin:.25rem 0 1.5rem; }
.admin-ui .page-head .eyebrow{
    display:inline-flex; align-items:center; gap:.55rem;
    font-size:.7rem; font-weight:800; letter-spacing:.2em; text-transform:uppercase;
    color:var(--gold); margin-bottom:.6rem;
}
.admin-ui .page-head .eyebrow::before{ content:''; width:1.5rem; height:1px; background:currentColor; }
.admin-ui .page-head h1{ font-family:var(--display); font-weight:700; color:var(--ink); font-size:clamp(1.9rem,3vw,2.5rem); line-height:1.05; }
.admin-ui .page-head p{ color:var(--muted); margin-top:.7rem; font-size:.95rem; max-width:42rem; }

/* ---------- Tabs ---------- */
.admin-ui .tabs{
    display:flex; gap:.25rem; flex-wrap:wrap; align-items:flex-end;
    margin:0 0 2rem; border-bottom:1px solid var(--line-strong);
}
.admin-ui .tab{
    position:relative; display:inline-flex; align-items:center; gap:.55rem;
    font-family:var(--body); font-weight:700; font-size:.92rem; color:var(--muted);
    background:none; border:0; cursor:pointer; padding:.85rem 1.15rem;
    border-radius:12px 12px 0 0; transition:color .2s, background .2s;
}
.admin-ui .tab svg{ width:1.1rem; height:1.1rem; }
.admin-ui .tab:hover{ color:var(--ink); background:rgba(22,35,63,.03); }
.admin-ui .tab.active{ color:var(--ink); }
.admin-ui .tab.active::after{
    content:''; position:absolute; left:.7rem; right:.7rem; bottom:-1px; height:3px;
    border-radius:3px 3px 0 0; background:linear-gradient(90deg,var(--gold),var(--gold-soft));
}
.admin-ui .tab-count{
    font-size:.68rem; font-weight:800; letter-spacing:.02em; padding:.08rem .45rem; border-radius:999px;
    background:var(--gold-bg); border:1px solid var(--gold-soft); color:var(--gold);
}
.admin-ui .tab-panel{ display:none; animation:fade .3s var(--ease); }
.admin-ui .tab-panel.active{ display:block; }
@keyframes fade{ from{ opacity:0; transform:translateY(6px); } to{ opacity:1; transform:none; } }

/* ---------- Kartu ---------- */
.admin-ui .card{
    position:relative; background:#fff; border:1px solid var(--line);
    border-radius:var(--r); box-shadow:var(--shadow);
    padding:2rem 2.1rem; margin-bottom:2rem; overflow:hidden;
}
.admin-ui .card::before{ content:''; position:absolute; left:0; right:0; top:0; height:3px; background:linear-gradient(90deg,var(--gold),var(--gold-soft)); }
.admin-ui .card::after{ content:''; position:absolute; right:-.6rem; top:-.4rem; width:6rem; height:8rem; background:var(--cross-ink) no-repeat center/contain; opacity:.035; pointer-events:none; }
.admin-ui .card-head{ display:flex; align-items:flex-start; gap:1.1rem; padding-bottom:1.25rem; margin-bottom:1.5rem; border-bottom:1px solid var(--line); }
.admin-ui .card-num{
    flex:none; width:2.6rem; height:2.6rem; border-radius:13px; display:grid; place-items:center; position:relative;
    font-family:var(--display); font-weight:700; font-size:1.35rem; color:#fff;
    background:linear-gradient(135deg,var(--ink),var(--ink-soft)); box-shadow:0 8px 16px -8px rgba(22,35,63,.85);
}
.admin-ui .card-num::after{ content:''; position:absolute; inset:-3px; border-radius:16px; border:1px solid var(--gold-soft); opacity:.55; }
.admin-ui .card-head .meta{ flex:1; min-width:0; }
.admin-ui .card-head h3{ font-family:var(--display); font-weight:700; color:var(--ink); font-size:1.55rem; line-height:1.12; }
.admin-ui .card-head .meta p{ color:var(--muted); font-size:.88rem; margin-top:.25rem; }
.admin-ui .card-ic{ flex:none; width:1.7rem; height:1.7rem; color:var(--gold); margin-top:.2rem; }

/* ---------- Field ---------- */
.admin-ui .stack{ display:flex; flex-direction:column; gap:1.5rem; }
.admin-ui .grid-2{ display:grid; grid-template-columns:1fr; gap:1.5rem; }
@media(min-width:720px){ .admin-ui .grid-2{ grid-template-columns:1fr 1fr; } }
.admin-ui .field > .lbl{ display:block; font-size:.82rem; font-weight:800; color:var(--ink-soft); letter-spacing:.02em; margin-bottom:.5rem; }
.admin-ui .hint{ font-size:.78rem; color:var(--muted); margin-top:.5rem; }

.admin-ui .inp{ width:100%; font-family:var(--body); font-size:.95rem; color:var(--text); background:#fff; border:1px solid var(--line-strong); border-radius:12px; padding:.78rem 1rem; transition:border-color .2s, box-shadow .2s; }
.admin-ui .inp::placeholder{ color:#9aa4bb; }
.admin-ui .inp:focus{ outline:none; border-color:var(--gold); box-shadow:0 0 0 3px rgba(185,138,62,.16); }

/* Trix */
.admin-ui trix-toolbar{ margin-bottom:.45rem; }
.admin-ui trix-toolbar .trix-button-group{ border-color:var(--line-strong); border-radius:10px; overflow:hidden; background:#fff; }
.admin-ui trix-toolbar .trix-button{ border-bottom:none; }
.admin-ui trix-toolbar .trix-button.trix-active{ background:var(--gold-bg); }
.admin-ui trix-editor{ display:block; width:100%; font-family:var(--body); font-size:.95rem; color:var(--text); line-height:1.6; background:#fff; border:1px solid var(--line-strong); border-radius:12px; padding:.9rem 1rem; transition:border-color .2s, box-shadow .2s; }
.admin-ui trix-editor:focus{ outline:none; border-color:var(--gold); box-shadow:0 0 0 3px rgba(185,138,62,.16); }
.admin-ui trix-editor h1{ font-family:var(--display); font-size:1.4rem; color:var(--ink); }

/* file */
.admin-ui input[type=file]{ font-size:.85rem; color:var(--muted); max-width:100%; }
.admin-ui input[type=file]::file-selector-button{ font-family:var(--body); font-weight:700; font-size:.82rem; color:var(--ink); background:var(--gold-bg); border:1px solid var(--gold-soft); border-radius:999px; padding:.55rem 1.1rem; margin-right:.85rem; cursor:pointer; transition:background .2s, border-color .2s; }
.admin-ui input[type=file]::file-selector-button:hover{ background:#f5ead0; border-color:var(--gold); }
.admin-ui .check{ display:inline-flex; align-items:center; gap:.5rem; margin:.75rem 0; font-size:.82rem; font-weight:600; color:var(--danger); cursor:pointer; }
.admin-ui .check input{ accent-color:var(--danger); width:1rem; height:1rem; }

/* ---------- Tombol ---------- */
.admin-ui .btn{ display:inline-flex; align-items:center; justify-content:center; gap:.5rem; font-family:var(--body); font-weight:700; font-size:.9rem; line-height:1; border-radius:999px; padding:.8rem 1.6rem; cursor:pointer; border:1px solid transparent; text-decoration:none; white-space:nowrap; transition:transform .2s var(--ease), box-shadow .2s, filter .2s, background .2s, color .2s, border-color .2s; }
.admin-ui .btn svg{ width:1.05rem; height:1.05rem; }
.admin-ui .btn-lg{ padding:.95rem 2rem; font-size:.98rem; }
.admin-ui .btn-sm{ padding:.62rem 1.2rem; font-size:.84rem; }
.admin-ui .btn-primary{ color:#fff; background:linear-gradient(135deg,#c79646,var(--gold) 55%,#a87c33); box-shadow:0 14px 28px -14px rgba(185,138,62,.9); }
.admin-ui .btn-primary:hover{ filter:brightness(1.06); transform:translateY(-2px); }
.admin-ui .btn-navy{ color:#fff; background:var(--ink); }
.admin-ui .btn-navy:hover{ background:var(--ink-soft); transform:translateY(-2px); }
.admin-ui .btn-link-danger{ display:inline-flex; align-items:center; gap:.4rem; background:none; border:0; cursor:pointer; font-family:var(--body); font-weight:700; font-size:.82rem; color:var(--danger); padding:.4rem .2rem; transition:color .2s; }
.admin-ui .btn-link-danger:hover{ color:#8e2a20; text-decoration:underline; text-underline-offset:3px; }
.admin-ui .btn:active{ transform:translateY(0); }
.admin-ui .btn:focus-visible, .admin-ui a:focus-visible, .admin-ui button:focus-visible{ outline:3px solid var(--gold-soft); outline-offset:3px; }

/* ---------- Panel Simpan ---------- */
.admin-ui .save-panel{ position:relative; overflow:hidden; display:flex; align-items:center; justify-content:space-between; gap:1.5rem; flex-wrap:wrap; margin-top:2.5rem; padding:1.6rem 1.9rem; background:linear-gradient(135deg,var(--ink),var(--ink-soft)); border-radius:var(--r); box-shadow:var(--shadow-lg); }
.admin-ui .save-panel::after{ content:''; position:absolute; right:-1rem; top:50%; transform:translateY(-50%); width:9rem; height:12rem; background:var(--cross-gold) no-repeat center/contain; opacity:.1; pointer-events:none; }
.admin-ui .save-panel .txt{ position:relative; z-index:1; }
.admin-ui .save-panel h4{ font-family:var(--display); font-weight:700; color:#fff; font-size:1.5rem; line-height:1.1; }
.admin-ui .save-panel p{ color:rgba(255,255,255,.72); font-size:.84rem; margin-top:.25rem; }
.admin-ui .save-panel .btn{ position:relative; z-index:1; }
@media(max-width:620px){ .admin-ui .save-panel{ flex-direction:column; align-items:stretch; text-align:center; } .admin-ui .save-panel .btn{ width:100%; } }

/* ---------- Judul di dalam tab ---------- */
.admin-ui .panel-head{ display:flex; align-items:center; gap:.85rem; margin:.25rem 0 .4rem; }
.admin-ui .panel-head .emblem{ flex:none; width:2.3rem; height:2.3rem; border-radius:50%; display:grid; place-items:center; color:var(--gold); background:var(--gold-bg); border:1px solid var(--gold-soft); box-shadow:0 0 0 4px rgba(230,207,155,.18); }
.admin-ui .panel-head .emblem svg{ width:1rem; }
.admin-ui .panel-head h2{ font-family:var(--display); font-weight:700; color:var(--ink); font-size:1.75rem; }
.admin-ui .panel-note{ color:var(--muted); font-size:.88rem; margin:0 0 1.75rem; max-width:44rem; }

/* ---------- Legio Maria ---------- */
.admin-ui .legio-panel{ position:relative; border:1px solid var(--line); border-radius:var(--r); background:linear-gradient(180deg,#fff,var(--paper)); box-shadow:var(--shadow); padding:1.7rem 1.8rem; margin-bottom:1.75rem; overflow:hidden; }
.admin-ui .legio-panel::before{ content:''; position:absolute; left:0; top:0; bottom:0; width:4px; background:var(--gold); }
.admin-ui .legio-topline{ display:flex; align-items:center; gap:.7rem; margin-bottom:1.25rem; }
.admin-ui .legio-topline .em{ width:1.9rem; height:1.9rem; border-radius:8px; flex:none; display:grid; place-items:center; color:var(--gold); background:var(--gold-bg); border:1px solid var(--gold-soft); }
.admin-ui .legio-topline .em svg{ width:.85rem; }
.admin-ui .legio-topline h4{ font-family:var(--display); font-weight:700; color:var(--ink); font-size:1.4rem; }
.admin-ui .legio-preview{ width:100%; max-width:22rem; aspect-ratio:16/10; object-fit:cover; border-radius:12px; border:1px solid var(--line-strong); margin-bottom:.65rem; }
.admin-ui .legio-foot{ display:flex; align-items:center; justify-content:space-between; gap:1rem; margin-top:1.25rem; padding-top:1.25rem; border-top:1px solid var(--line); }
.admin-ui .add-panel{ position:relative; border:2px dashed var(--line-strong); border-radius:var(--r); background:#fff; padding:1.75rem; transition:border-color .25s, background .25s; }
.admin-ui .add-panel:hover{ border-color:var(--gold-soft); background:var(--gold-bg); }
.admin-ui .add-panel .add-title{ display:inline-flex; align-items:center; gap:.5rem; font-weight:800; color:var(--ink); font-size:.98rem; margin-bottom:1.25rem; }
.admin-ui .add-panel .add-title svg{ width:1.25rem; height:1.25rem; color:var(--gold); }
.admin-ui .errbox{ border-left:4px solid var(--danger); background:var(--danger-bg); color:#8e2a20; border-radius:10px; padding:.85rem 1rem; font-size:.84rem; margin-bottom:1.25rem; }
.admin-ui .errbox p{ margin:.1rem 0; }

/* ---------- Dokumentasi ---------- */
.admin-ui .upload-row{ display:flex; flex-wrap:wrap; align-items:center; gap:.9rem; background:linear-gradient(180deg,#fff,var(--paper)); border:1px solid var(--line); border-radius:14px; padding:1.1rem 1.2rem; margin-bottom:1.75rem; box-shadow:var(--shadow); }
.admin-ui .upload-row .inp{ max-width:17rem; flex:1 1 12rem; }
.admin-ui .gallery{ display:grid; grid-template-columns:repeat(2,1fr); gap:1.1rem; }
@media(min-width:640px){ .admin-ui .gallery{ grid-template-columns:repeat(3,1fr); } }
@media(min-width:920px){ .admin-ui .gallery{ grid-template-columns:repeat(4,1fr); } }
.admin-ui .photo{ position:relative; border:1px solid var(--line); border-radius:14px; overflow:hidden; background:#fff; box-shadow:var(--shadow); transition:transform .25s var(--ease), box-shadow .25s; }
.admin-ui .photo:hover{ transform:translateY(-3px); box-shadow:var(--shadow-lg); }
.admin-ui .photo .thumb{ aspect-ratio:16/10; width:100%; background:#e7ecf5; }
.admin-ui .photo .thumb img{ width:100%; height:100%; object-fit:cover; display:block; }
.admin-ui .photo .cap{ display:flex; align-items:center; gap:.5rem; padding:.65rem .75rem .65rem .9rem; }
.admin-ui .photo .cap span{ font-size:.78rem; color:var(--muted); overflow:hidden; text-overflow:ellipsis; white-space:nowrap; }
.admin-ui .photo .del{ position:absolute; top:.6rem; right:.6rem; z-index:2; width:2rem; height:2rem; border-radius:999px; border:0; cursor:pointer; display:grid; place-items:center; color:#fff; background:rgba(22,35,63,.55); backdrop-filter:blur(4px); opacity:0; transform:scale(.9); transition:opacity .2s, transform .2s, background .2s; }
.admin-ui .photo .del svg{ width:1rem; height:1rem; }
.admin-ui .photo:hover .del, .admin-ui .photo:focus-within .del{ opacity:1; transform:scale(1); }
.admin-ui .photo .del:hover{ background:var(--danger); }
.admin-ui .empty{ padding:4rem 1.5rem; text-align:center; border:2px dashed var(--line-strong); border-radius:var(--r); color:var(--muted); font-size:.9rem; background:#fff; }

@media(prefers-reduced-motion:reduce){ .admin-ui *{ transition:none !important; } .admin-ui .tab-panel{ animation:none; } }
</style>

<div class="admin-ui">

    {{-- ===================== HEADER HALAMAN ===================== --}}
    <header class="page-head">
        <span class="eyebrow">Panel Administrasi</span>
        <h1>Pengaturan Konten</h1>
        <p>Kelola konten halaman depan paroki. Pilih tab di bawah; setiap bagian punya tombol simpannya sendiri.</p>
    </header>

    {{-- ===================== TAB BAR ===================== --}}
    <div class="tabs" role="tablist">
        <button type="button" class="tab" data-tab="konten" role="tab">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M8 4h9a2 2 0 0 1 2 2v14l-4-2-4 2V6a2 2 0 0 0-2-2z"/><path d="M8 4H6a2 2 0 0 0-2 2v12"/></svg>
            Konten Website
        </button>
        <button type="button" class="tab" data-tab="legio" role="tab">
            <svg viewBox="0 0 24 32" aria-hidden="true"><path fill="currentColor" d="M10 0h4v9h8v4h-8v19h-4V13H2V9h8z"/></svg>
            Legio Maria
            <span class="tab-count">{{ $legios->count() }}</span>
        </button>
        <button type="button" class="tab" data-tab="dok" role="tab">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M4 7h3l1.5-2h7L18 7h2a1 1 0 0 1 1 1v10a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V8a1 1 0 0 1 1-1z"/><circle cx="12" cy="13" r="3.2"/></svg>
            Dokumentasi
            <span class="tab-count">{{ $documentations->count() }}</span>
        </button>
    </div>

    {{-- ========================================================= --}}
    {{-- TAB 1 — KONTEN WEBSITE (Kartu 1–6)                        --}}
    {{-- ========================================================= --}}
    <section class="tab-panel" id="panel-konten">
        <form action="{{ route('admin.settings.update') }}" method="POST">
            @csrf
            @method('PUT')

            <!-- CARD 1: HERO SECTION -->
            <div class="card">
                <div class="card-head">
                    <span class="card-num">1</span>
                    <div class="meta">
                        <h3>Hero Section</h3>
                        <p>Tampilan besar paling atas — hal pertama yang dilihat pengunjung.</p>
                    </div>
                    <svg class="card-ic" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="16" rx="2"/><path d="M3 15l5-4 4 3 3-2 6 4"/><circle cx="8" cy="9" r="1.4"/></svg>
                </div>
                <div class="stack">
                    <div class="field">
                        <span class="lbl">Judul Utama</span>
                        <input type="text" name="hero_title" value="{{ $settings['hero_title'] ?? '' }}" class="inp" placeholder="Paroki Santo Petrus Tuban">
                        <p class="hint">Tulisan terbesar di halaman depan.</p>
                    </div>
                    <div class="field">
                        <span class="lbl">Sub Judul</span>
                        <input id="hero_subtitle" type="hidden" name="hero_subtitle" value="{{ $settings['hero_subtitle'] ?? '' }}">
                        <trix-editor input="hero_subtitle" style="min-height:100px;"></trix-editor>
                        <p class="hint">Kalimat pengantar singkat di bawah judul utama.</p>
                    </div>
                </div>
            </div>

            <!-- CARD 2: TENTANG KAMI SECTION -->
            <div class="card">
                <div class="card-head">
                    <span class="card-num">2</span>
                    <div class="meta">
                        <h3>Tentang Kami</h3>
                        <p>Perkenalan singkat mengenai paroki.</p>
                    </div>
                    <svg class="card-ic" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="9"/><path d="M12 11v5"/><circle cx="12" cy="8" r=".6" fill="currentColor"/></svg>
                </div>
                <div class="field">
                    <span class="lbl">Teks Tentang Kami</span>
                    <input id="about_us" type="hidden" name="about_us" value="{{ $settings['about_us'] ?? '' }}">
                    <trix-editor input="about_us" style="min-height:180px;"></trix-editor>
                </div>
            </div>

            <!-- CARD 3: VISI MISI SECTION -->
            <div class="card">
                <div class="card-head">
                    <span class="card-num">3</span>
                    <div class="meta">
                        <h3>Visi &amp; Misi</h3>
                        <p>Arah dan tujuan pelayanan paroki. Gunakan daftar bernomor untuk misi.</p>
                    </div>
                    <svg class="card-ic" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="9"/><circle cx="12" cy="12" r="4"/><circle cx="12" cy="12" r=".6" fill="currentColor"/></svg>
                </div>
                <div class="field">
                    <span class="lbl">Konten Visi &amp; Misi</span>
                    <input id="visi_misi" type="hidden" name="visi_misi" value="{{ $settings['visi_misi'] ?? '' }}">
                    <trix-editor input="visi_misi" style="min-height:200px;"></trix-editor>
                </div>
            </div>

            <!-- CARD 4: JADWAL MISA NOTE -->
            <div class="card">
                <div class="card-head">
                    <span class="card-num">4</span>
                    <div class="meta">
                        <h3>Catatan Jadwal Misa</h3>
                        <p>Teks pengantar kecil di samping daftar jadwal misa.</p>
                    </div>
                    <svg class="card-ic" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 2"/></svg>
                </div>
                <div class="field">
                    <span class="lbl">Teks Catatan / Pengantar Jadwal</span>
                    <input type="text" name="schedule_note" value="{{ $settings['schedule_note'] ?? '' }}" class="inp">
                </div>
            </div>

            <!-- CARD 5: DOKUMENTASI DESKRIPSI -->
            <div class="card">
                <div class="card-head">
                    <span class="card-num">5</span>
                    <div class="meta">
                        <h3>Pengantar Dokumentasi</h3>
                        <p>Kalimat pembuka di atas galeri foto dokumentasi.</p>
                    </div>
                    <svg class="card-ic" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M4 7h3l1.5-2h7L18 7h2a1 1 0 0 1 1 1v10a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V8a1 1 0 0 1 1-1z"/><circle cx="12" cy="13" r="3.2"/></svg>
                </div>
                <div class="field">
                    <span class="lbl">Teks Pengantar Dokumentasi</span>
                    <input id="doc_description" type="hidden" name="doc_description" value="{{ $settings['doc_description'] ?? '' }}">
                    <trix-editor input="doc_description" style="min-height:100px;"></trix-editor>
                </div>
            </div>

            <!-- CARD 6: KONTAK (FOOTER) -->
            <div class="card">
                <div class="card-head">
                    <span class="card-num">6</span>
                    <div class="meta">
                        <h3>Kontak</h3>
                        <p>Informasi ini tampil di bagian footer website.</p>
                    </div>
                    <svg class="card-ic" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M4 5c0 9 6 15 15 15l1.5-3.5-4-1.5-1.5 2a11 11 0 0 1-5-5l2-1.5-1.5-4L7 4.5A2.5 2.5 0 0 0 4 5z"/></svg>
                </div>
                <div class="stack">
                    <div class="field">
                        <span class="lbl">Alamat</span>
                        <input type="text" name="address" value="{{ $settings['address'] ?? '' }}" class="inp">
                    </div>
                    <div class="grid-2">
                        <div class="field">
                            <span class="lbl">Email</span>
                            <input type="email" name="email" value="{{ $settings['email'] ?? '' }}" class="inp">
                        </div>
                        <div class="field">
                            <span class="lbl">Telepon</span>
                            <input type="text" name="phone" value="{{ $settings['phone'] ?? '' }}" class="inp">
                        </div>
                    </div>
                </div>
            </div>

            {{-- Panel simpan --}}
            <div class="save-panel">
                <div class="txt">
                    <h4>Simpan Perubahan Teks</h4>
                    <p>Menyimpan Bagian 1–6 (tab Konten Website).</p>
                </div>
                <button type="submit" class="btn btn-primary btn-lg">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 13l4 4L19 7"/></svg>
                    Simpan Perubahan Teks
                </button>
            </div>
        </form>
    </section>

    {{-- ========================================================= --}}
    {{-- TAB 2 — LEGIO MARIA                                       --}}
    {{-- ========================================================= --}}
    <section class="tab-panel" id="panel-legio">
        <div class="panel-head">
            <span class="emblem"><svg viewBox="0 0 24 32" aria-hidden="true"><path fill="currentColor" d="M10 0h4v9h8v4h-8v19h-4V13H2V9h8z"/></svg></span>
            <h2>Legio Maria</h2>
        </div>
        <p class="panel-note">Tambah, edit, atau hapus legio yang tampil di section “Legio Maria” pada website.</p>

        @if($errors->legio->any())
            <div class="errbox">
                @foreach($errors->legio->all() as $err)
                    <p>{{ $err }}</p>
                @endforeach
            </div>
        @endif

        {{-- Daftar legio yang sudah ada --}}
        @foreach($legios as $legio)
            <div class="legio-panel">
                <div class="legio-topline">
                    <span class="em"><svg viewBox="0 0 24 32" aria-hidden="true"><path fill="currentColor" d="M10 0h4v9h8v4h-8v19h-4V13H2V9h8z"/></svg></span>
                    <h4>{{ $legio->name ?: 'Legio tanpa nama' }}</h4>
                </div>

                <form action="{{ route('admin.legios.update', $legio) }}" method="POST" enctype="multipart/form-data" class="stack">
                    @csrf
                    @method('PUT')

                    <div class="field">
                        <span class="lbl">Nama Legio</span>
                        <input type="text" name="name" value="{{ $legio->name }}" required class="inp">
                    </div>

                    <div class="field">
                        <span class="lbl">Deskripsi</span>
                        <input id="legio_desc_{{ $legio->id }}" type="hidden" name="description" value="{{ $legio->description }}">
                        <trix-editor input="legio_desc_{{ $legio->id }}" style="min-height:160px;"></trix-editor>
                    </div>

                    <div class="field">
                        <span class="lbl">Foto</span>
                        @if($legio->photo_path)
                            <img src="{{ asset('storage/' . $legio->photo_path) }}" alt="{{ $legio->name }}" class="legio-preview">
                            <label class="check">
                                <input type="checkbox" name="remove_photo" value="1"> Hapus foto ini
                            </label>
                        @endif
                        <input type="file" name="photo" accept="image/*">
                        <p class="hint">JPG / PNG / WEBP, maks 2 MB. Kosongkan jika tidak ingin mengganti.</p>
                    </div>

                    <div class="legio-foot">
                        <button type="submit" class="btn btn-navy btn-sm">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 13l4 4L19 7"/></svg>
                            Simpan Perubahan
                        </button>

                        <button type="submit" form="legio-del-{{ $legio->id }}" class="btn-link-danger">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M4 7h16M9 7V5h6v2M7 7l1 12h8l1-12"/></svg>
                            Hapus legio ini
                        </button>
                    </div>
                </form>

                {{-- form hapus terpisah agar tidak bersarang di dalam form edit --}}
                <form id="legio-del-{{ $legio->id }}" action="{{ route('admin.legios.destroy', $legio) }}" method="POST"
                      onsubmit="return confirm('Hapus legio ini beserta fotonya?')">
                    @csrf
                    @method('DELETE')
                </form>
            </div>
        @endforeach

        {{-- Form tambah legio baru --}}
        <div class="add-panel">
            <span class="add-title">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 5v14M5 12h14"/></svg>
                Tambah Legio Baru
            </span>
            <form action="{{ route('admin.legios.store') }}" method="POST" enctype="multipart/form-data" class="stack">
                @csrf
                <div class="field">
                    <span class="lbl">Nama Legio</span>
                    <input type="text" name="name" required placeholder="Contoh: Legio Maria Presidium “...”" class="inp">
                </div>
                <div class="field">
                    <span class="lbl">Deskripsi</span>
                    <input id="legio_desc_new" type="hidden" name="description">
                    <trix-editor input="legio_desc_new" style="min-height:140px;"></trix-editor>
                </div>
                <div class="field">
                    <span class="lbl">Foto (opsional)</span>
                    <input type="file" name="photo" accept="image/*">
                </div>
                <div>
                    <button type="submit" class="btn btn-primary btn-sm">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 5v14M5 12h14"/></svg>
                        Tambah Legio
                    </button>
                </div>
            </form>
        </div>
    </section>

    {{-- ========================================================= --}}
    {{-- TAB 3 — FOTO DOKUMENTASI                                  --}}
    {{-- ========================================================= --}}
    <section class="tab-panel" id="panel-dok">
        <div class="panel-head">
            <span class="emblem"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 7h3l1.5-2h7L18 7h2a1 1 0 0 1 1 1v10a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V8a1 1 0 0 1 1-1z"/><circle cx="12" cy="13" r="3.2"/></svg></span>
            <h2>Foto Dokumentasi</h2>
        </div>
        <p class="panel-note">Foto terbaru otomatis menjadi slide pada section <b>Hero</b> dan <b>Galeri</b> di website.</p>

        <form action="{{ route('admin.documentations.store') }}" method="POST" enctype="multipart/form-data" class="upload-row">
            @csrf
            <input type="text" name="title" placeholder="Judul foto (opsional)" class="inp">
            <input type="file" name="image" required accept="image/*">
            <button type="submit" class="btn btn-navy btn-sm" style="margin-left:auto;">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 16V4M7 9l5-5 5 5M5 20h14"/></svg>
                Upload Foto
            </button>
        </form>

        @if($documentations->isEmpty())
            <div class="empty">Belum ada foto dokumentasi yang diunggah.</div>
        @else
            <div class="gallery">
                @foreach($documentations as $doc)
                    <div class="photo">
                        <form action="{{ route('admin.documentations.destroy', $doc->id) }}" method="POST"
                              onsubmit="return confirm('Hapus foto ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="del" aria-label="Hapus foto">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 6l12 12M18 6L6 18"/></svg>
                            </button>
                        </form>
                        <div class="thumb">
                            <img src="{{ asset('storage/' . $doc->image_path) }}" alt="{{ $doc->title }}">
                        </div>
                        <div class="cap">
                            <span>{{ $doc->title ?? 'Tanpa Judul' }}</span>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </section>

</div>

<script>
(function () {
    var root = document.currentScript.previousElementSibling; // .admin-ui
    if (!root || !root.classList.contains('admin-ui')) root = document.querySelector('.admin-ui');
    var tabs = root.querySelectorAll('.tab');
    var panels = root.querySelectorAll('.tab-panel');

    function activate(id, push) {
        var found = false;
        panels.forEach(function (p) {
            var on = p.id === 'panel-' + id;
            p.classList.toggle('active', on);
            if (on) found = true;
        });
        if (!found) { return activate('konten', push); }
        tabs.forEach(function (t) { t.classList.toggle('active', t.getAttribute('data-tab') === id); });
        if (push) { try { history.replaceState(null, '', '#' + id); } catch (e) {} }
    }

    tabs.forEach(function (t) {
        t.addEventListener('click', function () { activate(t.getAttribute('data-tab'), true); });
    });

    // Tab awal: dari URL (#legio / #dok), lalu dari server (error legio), lalu default.
    var initial = @json($activeTab);
    var hash = (location.hash || '').replace('#', '');
    if (hash && root.querySelector('#panel-' + hash)) initial = hash;
    activate(initial, false);
})();
</script>

@endsection