@extends('layouts.admin')

@section('content')

<style>
/* =========================================================
   ADMIN UI — Bacaan Mingguan (tab di area Moderasi Renungan)
   ========================================================= */
.admin-ui{
    --ink:#16233f; --ink-soft:#22345c;
    --gold:#b98a3e; --gold-soft:#e6cf9b; --gold-bg:#fbf6ea;
    --paper:#f3f6fa; --line:#e4e9f2; --line-strong:#d3dae7;
    --text:#2b3550; --muted:#6b7793;
    --danger:#c0392b; --danger-bg:#fdeceb;
    --ok:#2f8a5b; --ok-bg:#e9f7ef;
    --display:'Cormorant Garamond', Georgia, serif;
    --body:'Nunito Sans', system-ui, sans-serif;
    --r:18px;
    --shadow:0 14px 36px -24px rgba(22,35,63,.40);
    --shadow-lg:0 30px 60px -34px rgba(22,35,63,.5);
    --ease:cubic-bezier(.2,.7,.2,1);
    --cross-ink:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 32'%3E%3Cpath fill='%2316233f' d='M10 0h4v9h8v4h-8v19h-4V13H2V9h8z'/%3E%3C/svg%3E");
    font-family:var(--body); color:var(--text); max-width:70rem; margin:0 auto;
}
.admin-ui *, .admin-ui *::before, .admin-ui *::after{ box-sizing:border-box; }

.admin-ui .page-head{ margin:.25rem 0 1.5rem; }
.admin-ui .page-head .eyebrow{ display:inline-flex; align-items:center; gap:.55rem; font-size:.7rem; font-weight:800; letter-spacing:.2em; text-transform:uppercase; color:var(--gold); margin-bottom:.6rem; }
.admin-ui .page-head .eyebrow::before{ content:''; width:1.5rem; height:1px; background:currentColor; }
.admin-ui .page-head h1{ font-family:var(--display); font-weight:700; color:var(--ink); font-size:clamp(1.9rem,3vw,2.5rem); line-height:1.05; }

/* ----- Tab utama (domain) ----- */
.admin-ui .dtabs{ display:flex; gap:.25rem; flex-wrap:wrap; align-items:flex-end; margin:0 0 1.75rem; border-bottom:1px solid var(--line-strong); }
.admin-ui .dtab{ position:relative; display:inline-flex; align-items:center; gap:.5rem; font-weight:700; font-size:.95rem; color:var(--muted); text-decoration:none; padding:.85rem 1.2rem; border-radius:12px 12px 0 0; transition:color .2s, background .2s; }
.admin-ui .dtab svg{ width:1.1rem; height:1.1rem; }
.admin-ui .dtab:hover{ color:var(--ink); background:rgba(22,35,63,.03); }
.admin-ui .dtab.active{ color:var(--ink); }
.admin-ui .dtab.active::after{ content:''; position:absolute; left:.8rem; right:.8rem; bottom:-1px; height:3px; border-radius:3px 3px 0 0; background:linear-gradient(90deg,var(--gold),var(--gold-soft)); }

.admin-ui .lead{ color:var(--muted); font-size:.9rem; margin:-.5rem 0 1.75rem; max-width:46rem; }

.admin-ui .card{ position:relative; background:#fff; border:1px solid var(--line); border-radius:var(--r); box-shadow:var(--shadow); padding:2rem 2.1rem; margin-bottom:2rem; overflow:hidden; }
.admin-ui .card::before{ content:''; position:absolute; left:0; right:0; top:0; height:3px; background:linear-gradient(90deg,var(--gold),var(--gold-soft)); }
.admin-ui .card::after{ content:''; position:absolute; right:-.6rem; top:-.4rem; width:6rem; height:8rem; background:var(--cross-ink) no-repeat center/contain; opacity:.035; pointer-events:none; }
.admin-ui .card-head{ display:flex; align-items:flex-start; gap:1.1rem; padding-bottom:1.25rem; margin-bottom:1.5rem; border-bottom:1px solid var(--line); }
.admin-ui .card-ic{ flex:none; width:2.5rem; height:2.5rem; border-radius:12px; display:grid; place-items:center; color:#fff; background:linear-gradient(135deg,var(--ink),var(--ink-soft)); box-shadow:0 8px 16px -8px rgba(22,35,63,.85); }
.admin-ui .card-ic svg{ width:1.3rem; height:1.3rem; }
.admin-ui .card-head .meta{ flex:1; min-width:0; }
.admin-ui .card-head h3{ font-family:var(--display); font-weight:700; color:var(--ink); font-size:1.5rem; line-height:1.12; }
.admin-ui .card-head .meta p{ color:var(--muted); font-size:.86rem; margin-top:.25rem; }

.admin-ui .stack{ display:flex; flex-direction:column; gap:1.35rem; }
.admin-ui .grid-2{ display:grid; grid-template-columns:1fr; gap:1.35rem; }
.admin-ui .grid-3{ display:grid; grid-template-columns:1fr; gap:1.35rem; }
@media(min-width:680px){ .admin-ui .grid-2{ grid-template-columns:1fr 1fr; } .admin-ui .grid-3{ grid-template-columns:repeat(3,1fr); } }
.admin-ui .field > .lbl{ display:block; font-size:.8rem; font-weight:800; color:var(--ink-soft); letter-spacing:.02em; margin-bottom:.5rem; }
.admin-ui .hint{ font-size:.77rem; color:var(--muted); margin-top:.5rem; }
.admin-ui .inp{ width:100%; font-family:var(--body); font-size:.95rem; color:var(--text); background:#fff; border:1px solid var(--line-strong); border-radius:12px; padding:.72rem 1rem; transition:border-color .2s, box-shadow .2s; }
.admin-ui .inp:focus{ outline:none; border-color:var(--gold); box-shadow:0 0 0 3px rgba(185,138,62,.16); }
.admin-ui input[type=file]{ font-size:.85rem; color:var(--muted); max-width:100%; }
.admin-ui input[type=file]::file-selector-button{ font-family:var(--body); font-weight:700; font-size:.82rem; color:var(--ink); background:var(--gold-bg); border:1px solid var(--gold-soft); border-radius:999px; padding:.55rem 1.1rem; margin-right:.85rem; cursor:pointer; }
.admin-ui input[type=file]::file-selector-button:hover{ background:#f5ead0; border-color:var(--gold); }

.admin-ui .btn{ display:inline-flex; align-items:center; justify-content:center; gap:.5rem; font-family:var(--body); font-weight:700; font-size:.9rem; line-height:1; border-radius:999px; padding:.78rem 1.5rem; cursor:pointer; border:1px solid transparent; text-decoration:none; white-space:nowrap; transition:transform .2s var(--ease), filter .2s, background .2s, color .2s, border-color .2s; }
.admin-ui .btn svg{ width:1.05rem; height:1.05rem; }
.admin-ui .btn-primary{ color:#fff; background:linear-gradient(135deg,#c79646,var(--gold) 55%,#a87c33); box-shadow:0 14px 28px -14px rgba(185,138,62,.9); }
.admin-ui .btn-primary:hover{ filter:brightness(1.06); transform:translateY(-2px); }
.admin-ui .btn-navy{ color:#fff; background:var(--ink); }
.admin-ui .btn-navy:hover{ background:var(--ink-soft); transform:translateY(-2px); }
.admin-ui .btn-ghost{ color:var(--ink); background:#fff; border-color:var(--line-strong); }
.admin-ui .btn-ghost:hover{ border-color:var(--gold); color:var(--gold); }
.admin-ui .btn-sm{ padding:.5rem 1rem; font-size:.82rem; }
.admin-ui .btn-link-danger{ display:inline-flex; align-items:center; gap:.4rem; background:none; border:0; cursor:pointer; font-family:var(--body); font-weight:700; font-size:.82rem; color:var(--danger); padding:.4rem .2rem; }
.admin-ui .btn-link-danger:hover{ color:#8e2a20; text-decoration:underline; text-underline-offset:3px; }
.admin-ui .btn:focus-visible, .admin-ui a:focus-visible, .admin-ui button:focus-visible{ outline:3px solid var(--gold-soft); outline-offset:3px; }

.admin-ui .file-row{ display:flex; flex-wrap:wrap; align-items:center; gap:1rem; padding:1rem 1.15rem; border:1px solid var(--line); border-radius:14px; background:linear-gradient(180deg,#fff,var(--paper)); margin-bottom:1.5rem; }
.admin-ui .file-row .pdficon{ flex:none; width:2.6rem; height:3.2rem; border-radius:6px; display:grid; place-items:center; color:#fff; background:#c0392b; font-size:.6rem; font-weight:800; letter-spacing:.05em; box-shadow:0 8px 16px -10px rgba(192,57,43,.9); }
.admin-ui .file-row .fmeta{ flex:1; min-width:10rem; }
.admin-ui .file-row .fmeta b{ color:var(--ink); font-family:var(--display); font-size:1.2rem; font-weight:700; }
.admin-ui .file-row .fmeta span{ display:block; color:var(--muted); font-size:.8rem; margin-top:.15rem; }

.admin-ui .pdf-frame{ width:100%; height:26rem; border:1px solid var(--line-strong); border-radius:12px; background:#f1f4f9; }

.admin-ui .weeks{ border:1px solid var(--line); border-radius:14px; overflow:hidden; }
.admin-ui .wk{ display:grid; grid-template-columns:auto 1fr auto; gap:1rem; align-items:center; padding:1rem 1.2rem; border-bottom:1px solid var(--line); background:#fff; }
.admin-ui .wk:last-child{ border-bottom:0; }
.admin-ui .wk.is-current{ background:var(--gold-bg); }
.admin-ui .wk .wknum{ font-family:var(--display); font-weight:700; color:var(--ink); font-size:1.15rem; min-width:5.5rem; }
.admin-ui .wk .wknum small{ display:block; font-family:var(--body); font-size:.72rem; font-weight:700; color:var(--muted); letter-spacing:.02em; }
.admin-ui .wk .wkmid{ font-size:.86rem; color:var(--text); }
.admin-ui .wk .wkmid .pg{ font-weight:800; color:var(--ink); }
.admin-ui .tag{ display:inline-flex; align-items:center; gap:.35rem; font-size:.68rem; font-weight:800; letter-spacing:.04em; padding:.18rem .55rem; border-radius:999px; text-transform:uppercase; }
.admin-ui .tag.auto{ color:var(--muted); background:#eef2f8; border:1px solid var(--line-strong); }
.admin-ui .tag.ovr{ color:#8a5a00; background:var(--gold-bg); border:1px solid var(--gold-soft); }
.admin-ui .tag.now{ color:#fff; background:var(--gold); }
.admin-ui .wk form.ovrform{ display:flex; flex-wrap:wrap; align-items:center; gap:.5rem; justify-content:flex-end; }
.admin-ui .wk form.ovrform .inp{ padding:.45rem .6rem; font-size:.84rem; }
.admin-ui .wk form.ovrform .pageinp{ width:5rem; text-align:center; }
.admin-ui .wk form.ovrform .noteinp{ width:9rem; }
@media(max-width:720px){ .admin-ui .wk{ grid-template-columns:1fr; } .admin-ui .wk form.ovrform{ justify-content:flex-start; } }

.admin-ui .callout{ display:flex; gap:.7rem; padding:1rem 1.15rem; border-radius:12px; background:#eef4ff; border-left:4px solid var(--ink-soft); color:var(--ink-soft); font-size:.84rem; line-height:1.55; margin-bottom:1.5rem; }
.admin-ui .callout svg{ width:1.2rem; height:1.2rem; flex:none; margin-top:.1rem; }
.admin-ui .errbox{ border-left:4px solid var(--danger); background:var(--danger-bg); color:#8e2a20; border-radius:10px; padding:.85rem 1rem; font-size:.84rem; margin-bottom:1.25rem; }
.admin-ui .errbox p{ margin:.1rem 0; }

@media(prefers-reduced-motion:reduce){ .admin-ui *{ transition:none !important; } }
</style>

<div class="admin-ui">

    <header class="page-head">
        <span class="eyebrow">Panel Administrasi</span>
        <h1>Moderasi Renungan</h1>
    </header>

    {{-- ===== TAB UTAMA: Moderasi Kiriman / Bacaan Mingguan ===== --}}
    <nav class="dtabs">
        <a href="{{ route('admin.renungan.index') }}"
           class="dtab {{ request()->routeIs('admin.renungan.*') ? 'active' : '' }}">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M12 6.5C10.8 5.7 9.2 5.3 7.5 5.3S4.2 5.7 3 6.5v13c1.2-.8 2.8-1.2 4.5-1.2s3.3.4 4.5 1.2m0-13c1.2-.8 2.8-1.2 4.5-1.2s3.3.4 4.5 1.2v13c-1.2-.8-2.8-1.2-4.5-1.2s-3.3.4-4.5 1.2v-13z"/></svg>
            Moderasi Kiriman
        </a>
        <a href="{{ route('admin.bacaan.index') }}"
           class="dtab {{ request()->routeIs('admin.bacaan.*') ? 'active' : '' }}">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/></svg>
            Bacaan Mingguan
        </a>
    </nav>

    <p class="lead">Unggah PDF buku bacaan renungan. Sistem menampilkan halaman tertentu yang berganti otomatis tiap minggu; untuk minggu tertentu kamu bisa menimpanya secara manual.</p>

    @if($errors->any())
        <div class="errbox">
            @foreach($errors->all() as $err)
                <p>{{ $err }}</p>
            @endforeach
        </div>
    @endif

    @if(! $reading)
        {{-- ============ BELUM ADA PDF: FORM UNGGAH ============ --}}
        <div class="card">
            <div class="card-head">
                <span class="card-ic">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M12 16V4M7 9l5-5 5 5M5 20h14"/></svg>
                </span>
                <div class="meta">
                    <h3>Unggah PDF Bacaan</h3>
                    <p>Belum ada bacaan aktif. Unggah file PDF dan atur jadwalnya.</p>
                </div>
            </div>

            <form action="{{ route('admin.bacaan.store') }}" method="POST" enctype="multipart/form-data" class="stack">
                @csrf

                <div class="field">
                    <span class="lbl">Judul Bacaan</span>
                    <input type="text" name="title" value="{{ old('title', 'Buku Pegangan Legio Maria') }}" class="inp" required>
                </div>

                <div class="field">
                    <span class="lbl">File PDF</span>
                    <input type="file" name="pdf" accept="application/pdf" required>
                    <p class="hint">Format PDF, maksimal 20 MB.</p>
                </div>

                <div class="grid-3">
                    <div class="field">
                        <span class="lbl">Tanggal Mulai (minggu ke-1)</span>
                        <input type="date" name="start_date" value="{{ old('start_date', now()->toDateString()) }}" class="inp" required>
                    </div>
                    <div class="field">
                        <span class="lbl">Halaman Awal</span>
                        <input type="number" name="start_page" min="1" value="{{ old('start_page', 1) }}" class="inp" required>
                    </div>
                    <div class="field">
                        <span class="lbl">Halaman / Minggu</span>
                        <input type="number" name="pages_per_week" min="1" value="{{ old('pages_per_week', 2) }}" class="inp" required>
                    </div>
                </div>

                <div class="field">
                    <span class="lbl">Total Halaman (opsional)</span>
                    <input type="number" name="total_pages" min="1" value="{{ old('total_pages') }}" class="inp" placeholder="mis. 448">
                    <p class="hint">Jika diisi, halaman otomatis tidak akan melebihi angka ini.</p>
                </div>

                <div>
                    <button type="submit" class="btn btn-primary">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 16V4M7 9l5-5 5 5M5 20h14"/></svg>
                        Unggah & Aktifkan
                    </button>
                </div>
            </form>
        </div>
    @else
        {{-- ============ SUDAH ADA PDF: INFO + PENGATURAN ============ --}}
        @php $cur = $reading->weekInfo($reading->currentWeekIndex()); @endphp

        <div class="callout">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="9"/><path d="M12 11v5"/><circle cx="12" cy="8" r=".6" fill="currentColor"/></svg>
            <div>
                <b>Minggu ini (ke-{{ $cur['number'] }})</b> menampilkan <b>halaman {{ $cur['page'] }}</b>
                @if($cur['is_override']) <span class="tag ovr">manual</span> @else <span class="tag auto">otomatis</span> @endif
                — {{ $cur['start']->translatedFormat('d M Y') }} s/d {{ $cur['end']->translatedFormat('d M Y') }}.
            </div>
        </div>

        <div class="card">
            <div class="card-head">
                <span class="card-ic">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"/></svg>
                </span>
                <div class="meta">
                    <h3>File &amp; Jadwal Otomatis</h3>
                    <p>Ganti file atau ubah cara halaman bergeser tiap minggu.</p>
                </div>
            </div>

            <div class="file-row">
                <span class="pdficon">PDF</span>
                <div class="fmeta">
                    <b>{{ $reading->title }}</b>
                    <span>
                        {{ $reading->total_pages ? $reading->total_pages . ' halaman · ' : '' }}
                        mulai {{ $reading->start_date->translatedFormat('d M Y') }} · {{ $reading->pages_per_week }} hal/minggu
                    </span>
                </div>
                <a href="{{ $reading->fileUrl() }}" target="_blank" class="btn btn-ghost btn-sm">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M15 3h6v6M10 14L21 3M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"/></svg>
                    Buka PDF
                </a>
                <a href="{{ route('member.bacaan') }}" target="_blank" class="btn btn-ghost btn-sm">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/><circle cx="12" cy="12" r="3"/></svg>
                    Lihat sbg Anggota
                </a>
            </div>

            <iframe class="pdf-frame" src="{{ $reading->fileUrl() }}#page={{ $cur['page'] }}" title="Preview PDF bacaan"></iframe>

            <form action="{{ route('admin.bacaan.update', $reading) }}" method="POST" enctype="multipart/form-data" class="stack" style="margin-top:1.5rem;">
                @csrf
                @method('PUT')

                <div class="field">
                    <span class="lbl">Judul Bacaan</span>
                    <input type="text" name="title" value="{{ old('title', $reading->title) }}" class="inp" required>
                </div>

                <div class="grid-3">
                    <div class="field">
                        <span class="lbl">Tanggal Mulai (minggu ke-1)</span>
                        <input type="date" name="start_date" value="{{ old('start_date', $reading->start_date->toDateString()) }}" class="inp" required>
                    </div>
                    <div class="field">
                        <span class="lbl">Halaman Awal</span>
                        <input type="number" name="start_page" min="1" value="{{ old('start_page', $reading->start_page) }}" class="inp" required>
                    </div>
                    <div class="field">
                        <span class="lbl">Halaman / Minggu</span>
                        <input type="number" name="pages_per_week" min="1" value="{{ old('pages_per_week', $reading->pages_per_week) }}" class="inp" required>
                    </div>
                </div>

                <div class="grid-2">
                    <div class="field">
                        <span class="lbl">Total Halaman (opsional)</span>
                        <input type="number" name="total_pages" min="1" value="{{ old('total_pages', $reading->total_pages) }}" class="inp" placeholder="mis. 448">
                    </div>
                    <div class="field">
                        <span class="lbl">Ganti File PDF (opsional)</span>
                        <input type="file" name="pdf" accept="application/pdf">
                        <p class="hint">Kosongkan jika tidak ingin mengganti file.</p>
                    </div>
                </div>

                <div style="display:flex; gap:1rem; align-items:center; flex-wrap:wrap;">
                    <button type="submit" class="btn btn-primary">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 13l4 4L19 7"/></svg>
                        Simpan Pengaturan
                    </button>
                    <button type="submit" form="bacaan-del" class="btn-link-danger">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M4 7h16M9 7V5h6v2M7 7l1 12h8l1-12"/></svg>
                        Hapus PDF ini
                    </button>
                </div>
            </form>

            <form id="bacaan-del" action="{{ route('admin.bacaan.destroy', $reading) }}" method="POST"
                  onsubmit="return confirm('Hapus PDF bacaan ini beserta semua pengaturan mingguannya?')">
                @csrf
                @method('DELETE')
            </form>
        </div>

        {{-- ============ JADWAL MINGGUAN & OVERRIDE ============ --}}
        <div class="card">
            <div class="card-head">
                <span class="card-ic">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/></svg>
                </span>
                <div class="meta">
                    <h3>Jadwal per Minggu</h3>
                    <p>Angka halaman dihitung otomatis. Isi kolom halaman lalu “Set” untuk menimpa minggu tertentu.</p>
                </div>
            </div>

            <div class="weeks">
                @foreach($weeks as $w)
                    <div class="wk {{ $w['is_current'] ? 'is-current' : '' }}">
                        <div class="wknum">
                            Minggu {{ $w['number'] }}
                            <small>{{ $w['start']->translatedFormat('d M') }} – {{ $w['end']->translatedFormat('d M Y') }}</small>
                        </div>

                        <div class="wkmid">
                            Halaman <span class="pg">{{ $w['page'] }}</span>
                            @if($w['is_current']) <span class="tag now">minggu ini</span> @endif
                            @if($w['is_override'])
                                <span class="tag ovr">manual</span>
                                @if($w['note'])<span style="color:var(--muted);">· {{ $w['note'] }}</span>@endif
                            @else
                                <span class="tag auto">otomatis</span>
                            @endif
                        </div>

                        <form action="{{ route('admin.bacaan.override.store', $reading) }}" method="POST" class="ovrform">
                            @csrf
                            <input type="hidden" name="week_start" value="{{ $w['start']->toDateString() }}">
                            <input type="number" name="page" min="1" @if($reading->total_pages) max="{{ $reading->total_pages }}" @endif value="{{ $w['page'] }}" class="inp pageinp" aria-label="Halaman minggu {{ $w['number'] }}">
                            <input type="text" name="note" value="{{ $w['note'] }}" class="inp noteinp" placeholder="catatan (opsional)" aria-label="Catatan">
                            <button type="submit" class="btn btn-navy btn-sm">Set</button>
                            @if($w['is_override'])
                                <button type="submit" class="btn-link-danger" form="ovr-del-{{ $w['override']->id }}">Reset</button>
                            @endif
                        </form>
                    </div>
                @endforeach
            </div>

            {{-- form reset (destroy override) terpisah agar tidak bersarang --}}
            @foreach($weeks as $w)
                @if($w['is_override'])
                    <form id="ovr-del-{{ $w['override']->id }}" action="{{ route('admin.bacaan.override.destroy', $w['override']) }}" method="POST" class="hidden">
                        @csrf
                        @method('DELETE')
                    </form>
                @endif
            @endforeach
        </div>
    @endif

</div>

@endsection