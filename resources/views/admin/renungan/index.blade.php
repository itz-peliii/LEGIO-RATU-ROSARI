@extends('layouts.admin')

@section('content')
@php
    $tabs = [
        'pending'  => ['Menunggu', $counts['pending']],
        'tampil'   => ['Tampil', $counts['tampil']],
        'hidden'   => ['Disembunyikan', null],
        'expired'  => ['Kedaluwarsa', null],
        'rejected' => ['Ditolak', null],
        'semua'    => ['Semua', null],
    ];
@endphp

<style>
/* =========================================================
   ADMIN UI — Moderasi Renungan (tema navy-emas)
   ========================================================= */
.admin-ui{
    --ink:#16233f; --ink-soft:#22345c;
    --gold:#b98a3e; --gold-soft:#e6cf9b; --gold-bg:#fbf6ea;
    --paper:#f3f6fa; --line:#e4e9f2; --line-strong:#d3dae7;
    --text:#2b3550; --muted:#6b7793;
    --danger:#c0392b; --danger-bg:#fdeceb;
    --ok:#2f8a5b;
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

/* ----- Tab utama (domain): Moderasi Kiriman / Bacaan Mingguan ----- */
.admin-ui .dtabs{ display:flex; gap:.25rem; flex-wrap:wrap; align-items:flex-end; margin:0 0 1.75rem; border-bottom:1px solid var(--line-strong); }
.admin-ui .dtab{ position:relative; display:inline-flex; align-items:center; gap:.5rem; font-weight:700; font-size:.95rem; color:var(--muted); text-decoration:none; padding:.85rem 1.2rem; border-radius:12px 12px 0 0; transition:color .2s, background .2s; }
.admin-ui .dtab svg{ width:1.1rem; height:1.1rem; }
.admin-ui .dtab:hover{ color:var(--ink); background:rgba(22,35,63,.03); }
.admin-ui .dtab.active{ color:var(--ink); }
.admin-ui .dtab.active::after{ content:''; position:absolute; left:.8rem; right:.8rem; bottom:-1px; height:3px; border-radius:3px 3px 0 0; background:linear-gradient(90deg,var(--gold),var(--gold-soft)); }

.admin-ui .lead{ color:var(--muted); font-size:.9rem; margin:-.5rem 0 1.5rem; max-width:46rem; }

/* ----- Filter status (pill) ----- */
.admin-ui .filters{ display:flex; flex-wrap:wrap; gap:.5rem; margin-bottom:1.75rem; }
.admin-ui .filter{ font-family:var(--body); font-size:.8rem; font-weight:700; padding:.5rem .95rem; border-radius:999px; text-decoration:none; border:1px solid var(--line-strong); background:#fff; color:var(--muted); transition:border-color .2s, color .2s, background .2s; }
.admin-ui .filter:hover{ border-color:var(--gold); color:var(--ink); }
.admin-ui .filter.active{ background:var(--ink); color:#fff; border-color:var(--ink); }
.admin-ui .filter .num{ opacity:.6; margin-left:.2rem; }
.admin-ui .filter.active .num{ color:var(--gold-soft); opacity:1; }

/* ----- Kartu renungan ----- */
.admin-ui .rcard{ position:relative; background:#fff; border:1px solid var(--line); border-radius:var(--r); box-shadow:var(--shadow); overflow:hidden; margin-bottom:1.5rem; }
.admin-ui .rcard::before{ content:''; position:absolute; left:0; right:0; top:0; height:3px; background:linear-gradient(90deg,var(--gold),var(--gold-soft)); z-index:1; }
.admin-ui .rcard-head{ display:flex; flex-wrap:wrap; align-items:center; justify-content:space-between; gap:.75rem; padding:1.15rem 1.5rem; border-bottom:1px solid var(--line); }
.admin-ui .rname{ font-weight:800; color:var(--ink); }
.admin-ui .rsub{ font-size:.77rem; color:var(--muted); margin-top:.12rem; }
.admin-ui .rbadge{ flex:none; font-size:.68rem; font-weight:800; padding:.28rem .75rem; border-radius:999px; letter-spacing:.02em; }
.admin-ui .rbody{ padding:1.3rem 1.5rem; }
.admin-ui .rbody .content{ font-size:.92rem; color:#394257; line-height:1.72; white-space:pre-line; }
.admin-ui .reject-note{ margin-top:1rem; padding:.8rem 1rem; background:var(--danger-bg); border-left:4px solid var(--danger); color:#8e2a20; font-size:.8rem; border-radius:8px; }
.admin-ui .reject-note b{ font-weight:800; }

/* ----- Jejak waktu ----- */
.admin-ui .rtrace{ padding:1rem 1.5rem; border-top:1px solid var(--line); background:var(--paper); display:grid; grid-template-columns:1fr 1fr; gap:1rem; }
@media(min-width:900px){ .admin-ui .rtrace{ grid-template-columns:repeat(4,1fr); } }
.admin-ui .rtrace dt{ font-weight:800; color:#9aa4bb; text-transform:uppercase; letter-spacing:.05em; font-size:.62rem; }
.admin-ui .rtrace dd{ color:var(--text); margin:.22rem 0 0; font-size:.76rem; }
.admin-ui .rtrace dd .by{ color:var(--muted); }

/* ----- Aksi ----- */
.admin-ui .ractions{ padding:1.2rem 1.5rem; border-top:1px solid var(--line); display:flex; flex-wrap:wrap; align-items:flex-end; gap:1rem 1.5rem; }
.admin-ui .ractions .small-label{ display:block; font-size:.72rem; font-weight:800; color:var(--ink-soft); margin-bottom:.4rem; }
.admin-ui .inp{ width:100%; font-family:var(--body); font-size:.9rem; color:var(--text); background:#fff; border:1px solid var(--line-strong); border-radius:10px; padding:.55rem .8rem; transition:border-color .2s, box-shadow .2s; }
.admin-ui .inp:focus{ outline:none; border-color:var(--gold); box-shadow:0 0 0 3px rgba(185,138,62,.16); }
.admin-ui .inp.w-days{ width:11rem; } .admin-ui .inp.w-reason{ width:15rem; }
.admin-ui .f-inline{ display:flex; align-items:flex-end; gap:.6rem; }
.admin-ui .grow-right{ margin-left:auto; }

.admin-ui .btn{ display:inline-flex; align-items:center; justify-content:center; gap:.5rem; font-family:var(--body); font-weight:700; font-size:.88rem; line-height:1; border-radius:999px; padding:.6rem 1.3rem; cursor:pointer; border:1px solid transparent; text-decoration:none; white-space:nowrap; transition:transform .2s var(--ease), filter .2s, background .2s, color .2s, border-color .2s; }
.admin-ui .btn:hover{ transform:translateY(-1px); }
.admin-ui .btn-ok{ color:#fff; background:linear-gradient(135deg,#36a46f,var(--ok)); box-shadow:0 12px 24px -14px rgba(47,138,91,.9); }
.admin-ui .btn-ok:hover{ filter:brightness(1.05); }
.admin-ui .btn-outline-danger{ color:var(--danger); background:#fff; border-color:#f0c7c2; }
.admin-ui .btn-outline-danger:hover{ background:var(--danger-bg); border-color:var(--danger); }
.admin-ui .link-amber{ background:none; border:0; cursor:pointer; font-family:var(--body); font-weight:700; font-size:.82rem; color:#9a6b00; padding-bottom:.65rem; }
.admin-ui .link-amber:hover{ color:#6f4d00; text-decoration:underline; text-underline-offset:3px; }
.admin-ui .link-navy{ background:none; border:0; cursor:pointer; font-family:var(--body); font-weight:700; font-size:.82rem; color:var(--ink-soft); padding-bottom:.65rem; }
.admin-ui .link-navy:hover{ color:var(--gold); text-decoration:underline; text-underline-offset:3px; }
.admin-ui .btn:focus-visible, .admin-ui a:focus-visible, .admin-ui button:focus-visible{ outline:3px solid var(--gold-soft); outline-offset:3px; }

.admin-ui .empty{ padding:4rem 1.5rem; text-align:center; border:2px dashed var(--line-strong); border-radius:var(--r); color:var(--muted); font-size:.9rem; background:#fff; }
.admin-ui .pager{ margin-top:1.5rem; }

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

    <p class="lead">Setujui renungan anggota dan atur berapa lama renungan itu tampil di halaman anggota lain.</p>

    {{-- ===== FILTER STATUS ===== --}}
    <div class="filters">
        @foreach ($tabs as $key => [$label, $jumlah])
            <a href="{{ route('admin.renungan.index', ['tab' => $key]) }}"
               class="filter {{ $tab === $key ? 'active' : '' }}">
                {{ $label }}
                @if ($jumlah !== null)
                    <span class="num">({{ $jumlah }})</span>
                @endif
            </a>
        @endforeach
    </div>

    {{-- ===== DAFTAR ===== --}}
    @forelse ($renungans as $r)
        <div class="rcard">

            <div class="rcard-head">
                <div style="min-width:0;">
                    <p class="rname" style="overflow:hidden; text-overflow:ellipsis; white-space:nowrap;">{{ $r->name }}</p>
                    <p class="rsub" style="overflow:hidden; text-overflow:ellipsis; white-space:nowrap;">
                        {{ $r->user?->email ?? 'akun terhapus' }}
                        &middot; {{ $r->written_on->locale('id')->translatedFormat('d F Y') }}
                    </p>
                </div>

                <span class="rbadge {{ $r->statusColor() }}">{{ $r->statusLabel() }}</span>
            </div>

            <div class="rbody">
                <p class="content">{{ $r->content }}</p>

                @if ($r->status === 'rejected' && $r->reject_reason)
                    <p class="reject-note"><b>Alasan penolakan:</b> {{ $r->reject_reason }}</p>
                @endif
            </div>

            {{-- Jejak waktu --}}
            <dl class="rtrace">
                <div>
                    <dt>Dibuat</dt>
                    <dd>{{ $r->created_at->locale('id')->translatedFormat('d M Y, H:i') }}</dd>
                </div>
                <div>
                    <dt>Diedit peserta</dt>
                    <dd>{{ $r->last_edited_at?->locale('id')->translatedFormat('d M Y, H:i') ?? '—' }}</dd>
                </div>
                <div>
                    <dt>Disetujui</dt>
                    <dd>
                        {{ $r->approved_at?->locale('id')->translatedFormat('d M Y, H:i') ?? '—' }}
                        @if ($r->approver) <span class="by">({{ $r->approver->name }})</span> @endif
                    </dd>
                </div>
                <div>
                    <dt>Disembunyikan</dt>
                    <dd>
                        {{ $r->hidden_at?->locale('id')->translatedFormat('d M Y, H:i') ?? '—' }}
                        @if ($r->hider) <span class="by">({{ $r->hider->name }})</span> @endif
                    </dd>
                </div>
            </dl>

            {{-- Aksi --}}
            <div class="ractions">

                {{-- Setujui / perbarui masa tampil --}}
                <form method="POST"
                      action="{{ $r->status === 'approved' ? route('admin.renungan.extend', $r) : route('admin.renungan.approve', $r) }}"
                      class="f-inline">
                    @csrf
                    <div>
                        <label for="days-{{ $r->id }}" class="small-label">Lama tampil (hari)</label>
                        <input id="days-{{ $r->id }}" type="number" name="display_days" min="1" max="365"
                               value="{{ $r->display_days ?? \App\Models\Renungan::DEFAULT_DISPLAY_DAYS }}"
                               placeholder="kosong = tanpa batas"
                               class="inp w-days">
                    </div>
                    <button type="submit" class="btn btn-ok">
                        {{ $r->status === 'approved' ? 'Perbarui' : 'Setujui' }}
                    </button>
                </form>

                {{-- Sembunyikan / tampilkan lagi --}}
                @if ($r->hidden_at)
                    <form method="POST" action="{{ route('admin.renungan.unhide', $r) }}">
                        @csrf
                        <button type="submit" class="link-navy">Tampilkan lagi</button>
                    </form>
                @elseif ($r->status === 'approved')
                    <form method="POST" action="{{ route('admin.renungan.hide', $r) }}">
                        @csrf
                        <button type="submit" class="link-amber">Sembunyikan</button>
                    </form>
                @endif

                {{-- Tolak --}}
                @if ($r->status !== 'rejected')
                    <form method="POST" action="{{ route('admin.renungan.reject', $r) }}" class="f-inline grow-right">
                        @csrf
                        <div>
                            <label for="reason-{{ $r->id }}" class="small-label">Alasan penolakan</label>
                            <input id="reason-{{ $r->id }}" type="text" name="reject_reason" maxlength="255"
                                   placeholder="Opsional" class="inp w-reason">
                        </div>
                        <button type="submit" class="btn btn-outline-danger">Tolak</button>
                    </form>
                @endif
            </div>
        </div>
    @empty
        <div class="empty">Tidak ada renungan di kategori ini.</div>
    @endforelse

    <div class="pager">{{ $renungans->links() }}</div>

</div>
@endsection