{{-- ===================================================================
     TAB 3 — FOTO DOKUMENTASI  (maks. 10 foto + atur urutan)
     Dipakai lewat: @include('admin.partials.documentation-tab')
     Butuh variabel: $documentations (sudah diurutkan sort_order)
     =================================================================== --}}
@php
    $maxDocs  = 10;
    $docCount = $documentations->count();
    $docFull  = $docCount >= $maxDocs;
    $docOver  = max(0, $docCount - $maxDocs);
    $docPct   = min(100, round($docCount / $maxDocs * 100));
@endphp

<style>
.admin-ui #panel-dok .panel-note {
    max-width: 100%;
}
/* ---------- Kuota ---------- */
.admin-ui .quota{ background:#fff; border:1px solid var(--line); border-radius:14px; padding:1.1rem 1.25rem 1.2rem; margin-bottom:1.1rem; box-shadow:var(--shadow); }
.admin-ui .quota-top{ display:flex; align-items:baseline; justify-content:space-between; gap:1rem; margin-bottom:.7rem; }
.admin-ui .quota-label{ font-size:.72rem; font-weight:800; letter-spacing:.18em; text-transform:uppercase; color:var(--ink-soft); }
.admin-ui .quota-num{ font-family:var(--display); font-size:1.6rem; font-weight:700; color:var(--ink); line-height:1; }
.admin-ui .quota-num small{ font-family:var(--body); font-size:.8rem; font-weight:700; color:var(--muted); margin-left:.25rem; }
.admin-ui .quota-bar{ height:8px; border-radius:999px; background:var(--paper); overflow:hidden; border:1px solid var(--line); }
.admin-ui .quota-bar i{ display:block; height:100%; border-radius:999px; background:linear-gradient(90deg,var(--gold),var(--gold-soft)); transition:width .5s var(--ease); }
.admin-ui .quota.is-full .quota-bar i{ background:linear-gradient(90deg,#c0392b,#e08a80); }
.admin-ui .quota-foot{ margin:.65rem 0 0; font-size:.8rem; color:var(--muted); }

/* ---------- Peringatan & pesan ---------- */
.admin-ui .warn{ display:flex; gap:.8rem; align-items:flex-start; background:#fff6e5; border:1px solid #f0d9a4; border-left:4px solid var(--gold); color:#7a5a1a; border-radius:12px; padding:.9rem 1.05rem; font-size:.86rem; line-height:1.55; margin-bottom:1.1rem; }
.admin-ui .warn svg{ flex:none; width:1.25rem; height:1.25rem; margin-top:.1rem; color:var(--gold); }
.admin-ui .warn b{ color:#5e4310; }
.admin-ui .warn.is-danger{ background:var(--danger-bg); border-color:#f3c3be; border-left-color:var(--danger); color:#8e2a20; }
.admin-ui .warn.is-danger svg{ color:var(--danger); }
.admin-ui .warn.is-ok{ background:#eef8f0; border-color:#c4e3cb; border-left-color:#2e8b57; color:#25623f; }
.admin-ui .warn.is-ok svg{ color:#2e8b57; }

/* ---------- Upload terkunci ---------- */
.admin-ui .upload-row.is-locked{ opacity:.55; background:var(--paper); box-shadow:none; }
.admin-ui .upload-row.is-locked .btn{ cursor:not-allowed; transform:none; filter:grayscale(.3); }
.admin-ui .btn[disabled]{ cursor:not-allowed; }
.admin-ui .btn-ghost{ color:var(--ink); background:#fff; border-color:var(--line-strong); }
.admin-ui .btn-ghost:hover{ border-color:var(--gold); background:var(--gold-bg); transform:translateY(-2px); }

/* ---------- Petunjuk urutan ---------- */
.admin-ui .order-hint{ display:flex; align-items:center; gap:.55rem; font-size:.82rem; color:var(--muted); margin:0 0 1rem; }
.admin-ui .order-hint svg{ width:1.05rem; height:1.05rem; color:var(--gold); flex:none; }

/* ---------- Kartu foto yang bisa diurutkan ---------- */
.admin-ui .gallery .photo{ cursor:grab; }
.admin-ui .gallery .photo:active{ cursor:grabbing; }
.admin-ui .photo.dragging{ opacity:.4; transform:scale(.97); box-shadow:none; outline:2px dashed var(--gold); outline-offset:-2px; }
.admin-ui .photo .thumb{ position:relative; }
.admin-ui .photo .thumb img{ -webkit-user-drag:none; user-select:none; transition:filter .25s, opacity .25s; }
.admin-ui .order-badge{
    position:absolute; top:.6rem; left:.6rem; z-index:2; min-width:1.9rem; height:1.9rem; padding:0 .5rem;
    display:grid; place-items:center; border-radius:999px;
    font-family:var(--display); font-weight:700; font-size:1.05rem; color:#fff;
    background:linear-gradient(135deg,var(--ink),var(--ink-soft)); box-shadow:0 6px 14px -6px rgba(22,35,63,.8);
    border:1px solid var(--gold-soft);
}
.admin-ui .hidden-tag{
    display:none; position:absolute; left:.6rem; right:.6rem; bottom:.6rem; z-index:2; text-align:center;
    font-size:.7rem; font-weight:800; letter-spacing:.04em; padding:.3rem .5rem; border-radius:8px;
    color:#fff; background:rgba(192,57,43,.92);
}
.admin-ui .photo.is-hidden .hidden-tag{ display:block; }
.admin-ui .photo.is-hidden .thumb img{ filter:grayscale(1); opacity:.5; }

.admin-ui .photo .cap{ gap:.35rem; }
.admin-ui .photo .cap span{ flex:1; min-width:0; }
.admin-ui .mv{
    flex:none; width:1.9rem; height:1.9rem; display:grid; place-items:center; border-radius:999px; cursor:pointer;
    color:var(--ink-soft); background:var(--paper); border:1px solid var(--line-strong);
    transition:background .2s, border-color .2s, color .2s;
}
.admin-ui .mv svg{ width:.9rem; height:.9rem; }
.admin-ui .mv:hover:not([disabled]){ background:var(--gold-bg); border-color:var(--gold); color:var(--gold); }
.admin-ui .mv[disabled]{ opacity:.35; cursor:not-allowed; }

/* ---------- Bar simpan urutan (melayang di bawah) ---------- */
.admin-ui .reorder-bar{
    position:sticky; bottom:1rem; z-index:30; margin-top:1.5rem;
    display:flex; align-items:center; gap:.75rem; flex-wrap:wrap;
    padding:.95rem 1.2rem; border-radius:16px; color:#fff;
    background:linear-gradient(135deg,var(--ink),var(--ink-soft)); box-shadow:var(--shadow-lg);
    animation:fade .3s var(--ease);
}
.admin-ui .reorder-bar[hidden]{ display:none; }
.admin-ui .reorder-bar .txt{ flex:1 1 12rem; }
.admin-ui .reorder-bar .txt b{ display:block; font-family:var(--display); font-size:1.25rem; line-height:1.1; }
.admin-ui .reorder-bar .txt span{ font-size:.78rem; color:rgba(255,255,255,.72); }
@media(max-width:560px){ .admin-ui .reorder-bar .btn{ flex:1; } }
</style>

<section class="tab-panel" id="panel-dok">
    <div class="panel-head">
        <span class="emblem"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 7h3l1.5-2h7L18 7h2a1 1 0 0 1 1 1v10a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V8a1 1 0 0 1 1-1z"/><circle cx="12" cy="13" r="3.2"/></svg></span>
        <h2>Foto Dokumentasi</h2>
    </div>
    <p class="panel-note">
        Maksimal <b>{{ $maxDocs }} foto</b>. Urutan di bawah menentukan urutan slide pada section <b>Hero</b> dan <b>Galeri</b> di website.
        Foto urutan ke-2 juga dipakai pada section <b>Tentang Kami</b>.
    </p>

    {{-- Pesan dari server --}}
    {{-- Pesan sukses memakai session('success') seperti sebelumnya (ditampilkan oleh layout admin Anda). --}}
    @if(session('doc_error'))
        <div class="warn is-danger" role="alert">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M12 3l10 18H2L12 3z"/><path d="M12 10v5M12 18v.01"/></svg>
            <div>{{ session('doc_error') }}</div>
        </div>
    @endif
    @if($errors->hasAny(['image', 'title', 'order']))
        <div class="errbox">
            @foreach(array_merge($errors->get('image'), $errors->get('title'), $errors->get('order')) as $err)
                <p>{{ $err }}</p>
            @endforeach
        </div>
    @endif

    {{-- Kuota --}}
    <div class="quota {{ $docFull ? 'is-full' : '' }}">
        <div class="quota-top">
            <span class="quota-label">Kuota Foto</span>
            <span class="quota-num">{{ $docCount }} / {{ $maxDocs }}<small>foto</small></span>
        </div>
        <div class="quota-bar" role="progressbar" aria-valuemin="0" aria-valuemax="{{ $maxDocs }}" aria-valuenow="{{ min($docCount, $maxDocs) }}" aria-label="Kuota foto dokumentasi">
            <i style="width: {{ $docPct }}%"></i>
        </div>
        <p class="quota-foot">
            @if($docFull)
                Kuota penuh.
            @else
                Sisa kuota: <b>{{ $maxDocs - $docCount }}</b> foto lagi.
            @endif
        </p>
    </div>

    {{-- Peringatan kuota penuh / melebihi --}}
    @if($docOver > 0)
        <div class="warn is-danger" role="alert">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M12 3l10 18H2L12 3z"/><path d="M12 10v5M12 18v.01"/></svg>
            <div>
                Ada <b>{{ $docOver }} foto</b> melebihi batas {{ $maxDocs }}. Hanya <b>{{ $maxDocs }} foto pertama</b> yang tampil di website
                (ditandai “Tidak tampil”). Hapus foto yang tidak diperlukan atau atur ulang urutannya.
            </div>
        </div>
    @elseif($docFull)
        <div class="warn" role="alert">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M12 3l10 18H2L12 3z"/><path d="M12 10v5M12 18v.01"/></svg>
            <div>
                <b>Batas {{ $maxDocs }} foto sudah tercapai.</b> Hapus salah satu foto terlebih dahulu untuk bisa mengunggah foto baru.
            </div>
        </div>
    @endif

    {{-- Upload --}}
    <form action="{{ route('admin.documentations.store') }}" method="POST" enctype="multipart/form-data"
          class="upload-row {{ $docFull ? 'is-locked' : '' }}" @if($docFull) aria-disabled="true" title="Kuota penuh. Hapus foto untuk mengunggah lagi." @endif>
        @csrf
        <input type="text" name="title" value="{{ old('title') }}" placeholder="Judul foto (opsional)" class="inp" @disabled($docFull)>
        <input type="file" name="image" required accept="image/*" @disabled($docFull)>
        <button type="submit" class="btn btn-navy btn-sm" style="margin-left:auto;" @disabled($docFull)>
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 16V4M7 9l5-5 5 5M5 20h14"/></svg>
            Upload Foto
        </button>
    </form>

    @if($documentations->isEmpty())
        <div class="empty">Belum ada foto dokumentasi yang diunggah.</div>
    @else
        @if($docCount > 1)
            <p class="order-hint">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M7 4v16M7 4L4 7M7 4l3 3M17 20V4M17 20l-3-3M17 20l3-3"/></svg>
                Seret foto untuk mengubah urutan, atau gunakan tombol panah di bawah foto. Jangan lupa klik <b>Simpan Urutan</b>.
            </p>
        @endif

        <div class="gallery" id="doc-gallery" data-max="{{ $maxDocs }}">
            @foreach($documentations as $doc)
                <div class="photo {{ $loop->iteration > $maxDocs ? 'is-hidden' : '' }}" data-id="{{ $doc->id }}" @if($docCount > 1) draggable="true" @endif>
                    <form action="{{ route('admin.documentations.destroy', $doc->id) }}" method="POST" onsubmit="return docConfirmDelete()">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="del" aria-label="Hapus foto">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 6l12 12M18 6L6 18"/></svg>
                        </button>
                    </form>

                    <div class="thumb">
                        <span class="order-badge" aria-label="Urutan">{{ $loop->iteration }}</span>
                        <img src="{{ asset('storage/' . $doc->image_path) }}" alt="{{ $doc->title }}" draggable="false">
                        <span class="hidden-tag">Tidak tampil di website</span>
                    </div>

                    <div class="cap">
                        <span>{{ $doc->title ?? 'Tanpa Judul' }}</span>
                        @if($docCount > 1)
                            <button type="button" class="mv" data-dir="-1" aria-label="Geser ke depan">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M15 5l-7 7 7 7"/></svg>
                            </button>
                            <button type="button" class="mv" data-dir="1" aria-label="Geser ke belakang">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 5l7 7-7 7"/></svg>
                            </button>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>

        {{-- Bar simpan urutan: muncul hanya saat urutan berubah --}}
        <form id="reorder-form" method="POST" action="{{ route('admin.documentations.reorder') }}" class="reorder-bar" hidden>
            @csrf
            @method('PUT')
            <div class="txt">
                <b>Urutan foto berubah</b>
                <span>Perubahan belum disimpan.</span>
            </div>
            <button type="button" class="btn btn-ghost btn-sm" id="reorder-cancel">Batalkan</button>
            <button type="submit" class="btn btn-primary btn-sm">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 13l4 4L19 7"/></svg>
                Simpan Urutan
            </button>
        </form>
    @endif
</section>

<script>
(function () {
    var gal = document.getElementById('doc-gallery');
    var bar = document.getElementById('reorder-form');
    var dirty = false;

    window.docConfirmDelete = function () {
        var msg = 'Hapus foto ini?';
        if (dirty) msg += '\n\nPerubahan urutan yang belum disimpan akan hilang.';
        return confirm(msg);
    };
    if (!gal || !bar) return;

    var max = parseInt(gal.getAttribute('data-max'), 10) || 10;
    var cards = function () { return Array.prototype.slice.call(gal.querySelectorAll('.photo')); };
    var ids = function () { return cards().map(function (c) { return c.getAttribute('data-id'); }); };
    var original = ids();
    var dragged = null;

    function refresh() {
        var list = cards();
        list.forEach(function (c, i) {
            c.querySelector('.order-badge').textContent = i + 1;
            c.classList.toggle('is-hidden', i >= max);
            var prev = c.querySelector('[data-dir="-1"]');
            var next = c.querySelector('[data-dir="1"]');
            if (prev) prev.disabled = (i === 0);
            if (next) next.disabled = (i === list.length - 1);
        });
        dirty = ids().join(',') !== original.join(',');
        bar.hidden = !dirty;
    }

    // Tombol panah (juga untuk HP/tablet yang tidak mendukung seret)
    gal.addEventListener('click', function (e) {
        var btn = e.target.closest('.mv');
        if (!btn || btn.disabled) return;
        var card = btn.closest('.photo');
        var dir = parseInt(btn.getAttribute('data-dir'), 10);
        if (dir < 0 && card.previousElementSibling) {
            gal.insertBefore(card, card.previousElementSibling);
        } else if (dir > 0 && card.nextElementSibling) {
            gal.insertBefore(card.nextElementSibling, card);
        }
        refresh();
        var target = btn.disabled ? card.querySelector('.mv:not([disabled])') : btn;
        if (target) target.focus();
    });

    // Seret & lepas
    gal.addEventListener('dragstart', function (e) {
        var card = e.target.closest ? e.target.closest('.photo') : null;
        if (!card) return;
        dragged = card;
        card.classList.add('dragging');
        e.dataTransfer.effectAllowed = 'move';
        try { e.dataTransfer.setData('text/plain', card.getAttribute('data-id')); } catch (err) {}
    });
    gal.addEventListener('dragover', function (e) {
        if (!dragged) return;
        e.preventDefault();
        e.dataTransfer.dropEffect = 'move';
        var target = e.target.closest('.photo');
        if (!target || target === dragged) return;
        var list = cards();
        if (list.indexOf(dragged) < list.indexOf(target)) {
            gal.insertBefore(dragged, target.nextSibling);
        } else {
            gal.insertBefore(dragged, target);
        }
        refresh();
    });
    gal.addEventListener('drop', function (e) { if (dragged) e.preventDefault(); });
    gal.addEventListener('dragend', function () {
        if (dragged) dragged.classList.remove('dragging');
        dragged = null;
        refresh();
    });

    // Batalkan
    document.getElementById('reorder-cancel').addEventListener('click', function () {
        var map = {};
        cards().forEach(function (c) { map[c.getAttribute('data-id')] = c; });
        original.forEach(function (id) { gal.appendChild(map[id]); });
        refresh();
    });

    // Kirim urutan
    bar.addEventListener('submit', function () {
        Array.prototype.slice.call(bar.querySelectorAll('input[name="order[]"]')).forEach(function (i) { i.remove(); });
        ids().forEach(function (id) {
            var input = document.createElement('input');
            input.type = 'hidden'; input.name = 'order[]'; input.value = id;
            bar.appendChild(input);
        });
    });

    // Peringatan jika meninggalkan halaman saat urutan belum disimpan
    window.addEventListener('beforeunload', function (e) {
        if (dirty) { e.preventDefault(); e.returnValue = ''; }
    });
    bar.addEventListener('submit', function () { dirty = false; });

    refresh();
})();
</script>