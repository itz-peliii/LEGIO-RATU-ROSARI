@extends('layouts.member')

@section('title', 'Absensi')

@section('content')
@php
    /*
     | Agregasi dihitung di sini dari $rows yang sudah dikirim AbsensiController,
     | jadi controller tidak perlu diubah. Kalau nanti $rows dibatasi/paginate
     | di controller, pindahkan perhitungan ini ke controller.
     */
    $now = now();

    $all = collect($rows);

    /*
     | Tahun yang boleh dipilih: dari 2025 (atau tahun data paling lama, mana
     | yang lebih dulu) sampai tahun berjalan. Tahun depan tidak pernah muncul.
     */
    $tahunMulai = 2025;

    $tahunData = $all
        ->map(fn ($r) => (int) $r['kegiatan']->held_at->format('Y'))
        ->filter(fn ($y) => $y <= $now->year);

    if ($tahunData->isNotEmpty()) {
        $tahunMulai = min($tahunMulai, $tahunData->min());
    }

    $years = collect(range($tahunMulai, $now->year));

    // Tahun aktif — kalau diminta tahun yang tidak tersedia, jatuh ke tahun terbaru
    $year = (int) request('tahun', $now->year);
    if (! $years->contains($year)) {
        $year = $years->last();
    }

    // Bulan paling jauh yang boleh dibuka pada tahun aktif
    $maxMonth = $year === $now->year ? (int) $now->format('n') : 12;

    /*
     | Bulan aktif. Default = bulan berjalan (kalau yang dibuka tahun ini),
     | jadi anggota langsung melihat bulan ini tanpa mengatur apa pun.
     | 'all' = lihat seluruh tahun. Bulan yang belum terjadi diabaikan.
     */
    $bulanDefault = $year === (int) $now->format('Y') ? (int) $now->format('n') : null;
    $bulanParam   = request('bulan');

    if ($bulanParam === 'all') {
        $month = null;
    } elseif (is_numeric($bulanParam)) {
        $month = (int) $bulanParam;
    } else {
        $month = $bulanDefault;
    }

    if ($month !== null && ($month < 1 || $month > $maxMonth)) {
        $month = $bulanDefault;
    }

    $namaBulan = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
    $namaBulanPanjang = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];

    // Hitung per bulan untuk tahun aktif
    $monthly = [];
    for ($m = 1; $m <= 12; $m++) {
        $monthly[$m] = ['hadir' => 0, 'tidak_hadir' => 0, 'lain' => 0, 'total' => 0, 'future' => $m > $maxMonth];
    }

    foreach ($all as $r) {
        $tanggal = $r['kegiatan']->held_at;

        if ((int) $tanggal->format('Y') !== $year) {
            continue;
        }

        $m = (int) $tanggal->format('n');
        $key = in_array($r['status'], ['hadir', 'tidak_hadir'], true) ? $r['status'] : 'lain';

        $monthly[$m][$key]++;
        $monthly[$m]['total']++;
    }

    // Skala grafik
    $peak = max(1, collect($monthly)->max('total'));

    // Angka untuk cakupan yang sedang dilihat (satu bulan, atau seluruh tahun)
    $scope      = $month ? [$monthly[$month]] : array_values($monthly);
    $hadir      = collect($scope)->sum('hadir');
    $tidakHadir = collect($scope)->sum('tidak_hadir');
    $lain       = collect($scope)->sum('lain');

    $dinilai = $hadir + $tidakHadir;
    $rate    = $dinilai > 0 ? (int) round($hadir / $dinilai * 100) : null;

    // Ring SVG
    $r_ring  = 54;
    $circ    = 2 * M_PI * $r_ring;
    $dash    = $rate !== null ? $circ * $rate / 100 : 0;

    $labelCakupan = $month ? $namaBulanPanjang[$month - 1].' '.$year : 'Tahun '.$year;
@endphp

<div class="mb-8 flex flex-wrap items-end justify-between gap-4">
    <div>
        <h1 class="serif text-3xl font-bold text-[#16233f]">Absensi Kegiatan Legio</h1>
        <p class="text-sm text-slate-500 mt-1">Rekap kehadiranmu per bulan.</p>
    </div>

    <form method="GET" action="{{ route('member.absensi') }}" class="flex items-end gap-2">
        <div>
            <label for="tahun" class="block text-[11px] font-semibold uppercase tracking-[.14em] text-slate-500 mb-1.5">Tahun</label>

            {{-- appearance-none + panah sendiri, supaya panah bawaan browser
                 tidak menimpa angkanya --}}
            <div class="relative">
                <select id="tahun" name="tahun" onchange="this.form.submit()"
                        class="appearance-none w-full pl-4 pr-11 py-2.5 border border-slate-300 rounded-lg text-sm font-semibold text-[#16233f] bg-white cursor-pointer hover:border-[#b98a3e] focus:outline-none focus:ring-2 focus:ring-[#e6cf9b] focus:border-[#b98a3e] transition">
                    @foreach ($years->reverse() as $y)
                        <option value="{{ $y }}" @selected($y === $year)>{{ $y }}</option>
                    @endforeach
                </select>
                <svg class="pointer-events-none absolute right-3.5 top-1/2 -translate-y-1/2 w-4 h-4 text-[#b98a3e]"
                     viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <path d="M6 9l6 6 6-6"/>
                </svg>
            </div>
        </div>

        <noscript>
            <button type="submit" class="px-4 py-2.5 rounded-lg text-sm font-semibold text-slate-600 border border-slate-300 bg-white hover:bg-slate-50 transition">
                Tampilkan
            </button>
        </noscript>
    </form>
</div>

{{-- ===== RINGKASAN ===== --}}
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-[1.7fr_1fr_1fr_1fr] gap-5 mb-6">

    {{-- Ring persentase --}}
    <div class="sm:col-span-2 lg:col-span-1 bg-white rounded-2xl border border-slate-200 shadow-[0_18px_40px_-32px_rgba(22,35,63,.5)] p-6 flex items-center gap-6">
        <div class="relative flex-none">
            <svg viewBox="0 0 128 128" class="w-32 h-32 -rotate-90" aria-hidden="true">
                <circle cx="64" cy="64" r="{{ $r_ring }}" fill="none" stroke="#eef1f6" stroke-width="13"/>
                @if ($rate !== null)
                    <circle cx="64" cy="64" r="{{ $r_ring }}" fill="none" stroke="#b98a3e" stroke-width="13"
                            stroke-linecap="round"
                            stroke-dasharray="{{ $dash }} {{ $circ }}"/>
                @endif
            </svg>
            <div class="absolute inset-0 grid place-items-center">
                @if ($rate !== null)
                    <span class="serif text-4xl font-bold text-[#16233f] leading-none">{{ $rate }}<span class="text-xl">%</span></span>
                @else
                    <span class="text-slate-400 text-sm font-semibold">—</span>
                @endif
            </div>
        </div>
        <div>
            <p class="text-[11px] font-bold uppercase tracking-[.16em] text-[#b98a3e]">Tingkat kehadiran</p>
            <p class="serif text-2xl font-bold text-[#16233f] mt-1 leading-tight">{{ $labelCakupan }}</p>
            <p class="text-sm text-slate-500 mt-2">
                @if ($dinilai > 0)
                    Hadir {{ $hadir }} dari {{ $dinilai }} kegiatan yang kamu ikuti.
                @else
                    Belum ada kegiatan yang tercatat.
                @endif
            </p>
        </div>
    </div>

    {{-- Kartu angka --}}
    <div class="bg-white rounded-2xl border border-slate-200 border-l-4 border-l-emerald-500 p-5 flex flex-col justify-center">
        <p class="text-[11px] font-bold uppercase tracking-[.16em] text-slate-500">Hadir</p>
        <p class="serif text-5xl font-bold text-emerald-600 mt-1 leading-none">{{ $hadir }}</p>
        <p class="text-xs text-slate-400 mt-3 leading-relaxed">Kegiatan yang kamu hadiri.</p>
    </div>

    <div class="bg-white rounded-2xl border border-slate-200 border-l-4 border-l-rose-500 p-5 flex flex-col justify-center">
        <p class="text-[11px] font-bold uppercase tracking-[.16em] text-slate-500">Tidak hadir</p>
        <p class="serif text-5xl font-bold text-rose-600 mt-1 leading-none">{{ $tidakHadir }}</p>
        <p class="text-xs text-slate-400 mt-3 leading-relaxed">Sudah terdaftar, tapi tidak hadir.</p>
    </div>

    <div class="bg-white rounded-2xl border border-slate-200 border-l-4 border-l-slate-300 p-5 flex flex-col justify-center">
        <p class="text-[11px] font-bold uppercase tracking-[.16em] text-slate-500">Tidak diikuti</p>
        <p class="serif text-5xl font-bold text-slate-400 mt-1 leading-none">{{ $lain }}</p>
        <p class="text-xs text-slate-400 mt-3 leading-relaxed">Tidak kamu daftari, atau belum berlangsung.</p>
    </div>
</div>

{{-- ===== GRAFIK PER BULAN ===== --}}
<div class="bg-white rounded-2xl border border-slate-200 shadow-[0_18px_40px_-32px_rgba(22,35,63,.5)] p-6">

    <div class="flex flex-wrap items-center justify-between gap-3 mb-6">
        <div>
            <h2 class="serif text-xl font-bold text-[#16233f]">Kehadiran {{ $year }}</h2>
            <p class="text-xs text-slate-500 mt-0.5">Klik batang bulan untuk melihat rinciannya. Bulan yang belum terjadi tidak bisa dipilih.</p>
        </div>

        <div class="flex items-center gap-4 text-xs font-semibold text-slate-600">
            <span class="flex items-center gap-1.5"><i class="w-3 h-3 rounded-sm bg-emerald-500"></i> Hadir</span>
            <span class="flex items-center gap-1.5"><i class="w-3 h-3 rounded-sm bg-rose-500"></i> Tidak hadir</span>
            <span class="flex items-center gap-1.5"><i class="w-3 h-3 rounded-sm bg-slate-300"></i> Tidak diikuti</span>
        </div>
    </div>

    @if (collect($monthly)->sum('total') === 0)
        <p class="py-12 text-center text-sm text-slate-500">Belum ada kegiatan yang tercatat pada {{ $year }}.</p>
    @else
        <div class="flex items-end gap-1.5 sm:gap-2.5 h-56">
            @foreach ($monthly as $m => $data)
                @php
                    $isActive = $month === $m;
                    $tinggi   = $data['total'] > 0 ? max(6, (int) round($data['total'] / $peak * 100)) : 0;
                    $url      = route('member.absensi', [
                                    'tahun' => $year,
                                    'bulan' => $isActive ? 'all' : $m,
                                ]);
                    $judul    = $data['future']
                                ? $namaBulanPanjang[$m - 1].' '.$year.' — belum terjadi'
                                : $namaBulanPanjang[$m - 1].' '.$year.' — hadir '.$data['hadir'].', tidak hadir '.$data['tidak_hadir'].', tidak diikuti '.$data['lain'];
                @endphp

                <div class="flex-1 flex flex-col items-center h-full">
                    @if ($data['future'])
                        <div class="flex-1 w-full flex items-end" title="{{ $judul }}" aria-disabled="true">
                            <div class="w-full h-full rounded-t-md opacity-60"
                                 style="background-image: repeating-linear-gradient(45deg, #eef1f6 0 5px, transparent 5px 10px);"></div>
                        </div>
                        <span class="mt-2 text-[10px] sm:text-xs font-semibold text-slate-300">{{ $namaBulan[$m - 1] }}</span>
                    @else
                        <a href="{{ $url }}" title="{{ $judul }}"
                           class="flex-1 w-full flex flex-col justify-end group {{ $isActive ? '' : 'opacity-90 hover:opacity-100' }}">
                            @if ($data['total'] === 0)
                                <div class="w-full h-1.5 rounded-full bg-slate-100 group-hover:bg-slate-200 transition"></div>
                            @else
                                <div class="w-full flex flex-col justify-end rounded-t-md overflow-hidden transition-transform duration-200 {{ $isActive ? 'ring-2 ring-[#b98a3e] ring-offset-2' : 'group-hover:-translate-y-1' }}"
                                     style="height: {{ $tinggi }}%;">
                                    @if ($data['lain'] > 0)
                                        <div class="bg-slate-300" style="flex: {{ $data['lain'] }} 1 0%;"></div>
                                    @endif
                                    @if ($data['tidak_hadir'] > 0)
                                        <div class="bg-rose-500" style="flex: {{ $data['tidak_hadir'] }} 1 0%;"></div>
                                    @endif
                                    @if ($data['hadir'] > 0)
                                        <div class="bg-emerald-500" style="flex: {{ $data['hadir'] }} 1 0%;"></div>
                                    @endif
                                </div>
                            @endif
                        </a>
                        <span class="mt-2 text-[10px] sm:text-xs font-semibold {{ $isActive ? 'text-[#b98a3e]' : 'text-slate-500' }}">
                            {{ $namaBulan[$m - 1] }}
                        </span>
                    @endif
                </div>
            @endforeach
        </div>

        <div class="mt-6 pt-5 border-t border-slate-100 flex flex-wrap items-center justify-between gap-3">
            <p class="text-sm text-slate-600">
                Menampilkan <span class="font-semibold text-[#16233f]">{{ $labelCakupan }}</span>.
            </p>

            @if ($month)
                <a href="{{ route('member.absensi', ['tahun' => $year, 'bulan' => 'all']) }}"
                   class="text-sm font-semibold text-[#b98a3e] hover:text-[#8f6a2c] transition">
                    Tampilkan seluruh tahun {{ $year }}
                </a>
            @elseif ($bulanDefault !== null)
                <a href="{{ route('member.absensi', ['tahun' => $year, 'bulan' => $bulanDefault]) }}"
                   class="text-sm font-semibold text-[#b98a3e] hover:text-[#8f6a2c] transition">
                    Kembali ke bulan ini
                </a>
            @endif
        </div>
    @endif
</div>
@endsection
