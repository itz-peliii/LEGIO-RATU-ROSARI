@extends('layouts.admin')

@section('content')

<form action="{{ route('admin.settings.update') }}" method="POST" class="space-y-8">
    @csrf
    @method('PUT')

    <!-- CARD 1: HERO SECTION -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <h3 class="text-lg font-semibold text-gray-800 border-b pb-3 mb-4">1. Hero Section</h3>
        <div class="space-y-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Judul Utama</label>
                <input type="text" name="hero_title" value="{{ $settings['hero_title'] ?? '' }}" class="w-full px-4 py-2 border rounded-lg text-sm">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Sub Judul</label>
                <input id="hero_subtitle" type="hidden" name="hero_subtitle" value="{{ $settings['hero_subtitle'] ?? '' }}">
                <trix-editor input="hero_subtitle" class="bg-white border rounded-lg min-h-[100px] text-sm"></trix-editor>
            </div>
        </div>
    </div>

    <!-- CARD 2: TENTANG KAMI SECTION -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <h3 class="text-lg font-semibold text-gray-800 border-b pb-3 mb-4">2. Tentang Kami Section</h3>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Teks Tentang Kami</label>
            <input id="about_us" type="hidden" name="about_us" value="{{ $settings['about_us'] ?? '' }}">
            <trix-editor input="about_us" class="bg-white border rounded-lg min-h-[180px] text-sm"></trix-editor>
        </div>
    </div>

    <!-- CARD 3: VISI MISI SECTION -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <h3 class="text-lg font-semibold text-gray-800 border-b pb-3 mb-4">3. Visi & Misi Section</h3>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Konten Visi & Misi</label>
            <input id="visi_misi" type="hidden" name="visi_misi" value="{{ $settings['visi_misi'] ?? '' }}">
            <trix-editor input="visi_misi" class="bg-white border rounded-lg min-h-[200px] text-sm"></trix-editor>
        </div>
    </div>

    <!-- CARD 4: JADWAL MISA NOTE -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <h3 class="text-lg font-semibold text-gray-800 border-b pb-3 mb-4">4. Catatan Jadwal Misa</h3>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Teks Catatan / Pengantar Jadwal</label>
            <input type="text" name="schedule_note" value="{{ $settings['schedule_note'] ?? '' }}" class="w-full px-4 py-2 border rounded-lg text-sm">
        </div>
    </div>

    <!-- CARD 5: DOKUMENTASI DESKRIPSI -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <h3 class="text-lg font-semibold text-gray-800 border-b pb-3 mb-4">5. Pengantar Dokumentasi</h3>
        <div>
            <input id="doc_description" type="hidden" name="doc_description" value="{{ $settings['doc_description'] ?? '' }}">
            <trix-editor input="doc_description" class="bg-white border rounded-lg min-h-[100px] text-sm"></trix-editor>
        </div>
    </div>

    <!-- CARD 6: KONTAK (FOOTER) -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <h3 class="text-lg font-semibold text-gray-800 border-b pb-3 mb-4">6. Kontak (Tampil di Footer)</h3>
        <div class="space-y-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Alamat</label>
                <input type="text" name="address" value="{{ $settings['address'] ?? '' }}" class="w-full px-4 py-2 border rounded-lg text-sm">
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                    <input type="email" name="email" value="{{ $settings['email'] ?? '' }}" class="w-full px-4 py-2 border rounded-lg text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Telepon</label>
                    <input type="text" name="phone" value="{{ $settings['phone'] ?? '' }}" class="w-full px-4 py-2 border rounded-lg text-sm">
                </div>
            </div>
        </div>
    </div>

    <div class="flex justify-end">
        <button type="submit" class="px-6 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white font-medium rounded-lg text-sm shadow transition">
            Simpan Perubahan Teks
        </button>
    </div>
</form>

<!-- CARD LEGIO MARIA -->
<div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mt-8">
    <div class="border-b pb-4 mb-6">
        <h3 class="text-lg font-semibold text-gray-800">Legio Maria</h3>
        <p class="text-xs text-gray-500 mt-0.5">Tambah, edit, atau hapus legio yang tampil di section "Legio Maria" pada website.</p>
    </div>

    @if($errors->legio->any())
        <div class="mb-4 p-3 bg-red-50 border-l-4 border-red-500 text-red-700 text-xs rounded">
            @foreach($errors->legio->all() as $err)
                <p>{{ $err }}</p>
            @endforeach
        </div>
    @endif

    <!-- Daftar legio yang sudah ada -->
    <div class="space-y-6">
        @foreach($legios as $legio)
            <div class="border rounded-xl p-4 bg-gray-50">
                <form action="{{ route('admin.legios.update', $legio) }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                    @csrf
                    @method('PUT')

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nama Legio</label>
                        <input type="text" name="name" value="{{ $legio->name }}" required class="w-full px-4 py-2 border rounded-lg text-sm bg-white">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi</label>
                        <input id="legio_desc_{{ $legio->id }}" type="hidden" name="description" value="{{ $legio->description }}">
                        <trix-editor input="legio_desc_{{ $legio->id }}" class="bg-white border rounded-lg min-h-[160px] text-sm"></trix-editor>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Foto</label>
                        @if($legio->photo_path)
                            <img src="{{ asset('storage/' . $legio->photo_path) }}" alt="{{ $legio->name }}" class="w-full max-w-sm max-h-48 object-cover rounded-lg mb-2">
                            <label class="flex items-center gap-2 text-xs text-red-600 mb-2">
                                <input type="checkbox" name="remove_photo" value="1"> Hapus foto ini
                            </label>
                        @endif
                        <input type="file" name="photo" accept="image/*" class="text-xs text-gray-600">
                        <p class="text-xs text-gray-500 mt-1">JPG/PNG/WEBP, maks 2 MB. Kosongkan jika tidak ingin mengganti.</p>
                    </div>

                    <div class="flex justify-end">
                        <button type="submit" class="px-5 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-medium rounded-lg text-sm shadow transition">
                            Simpan Perubahan
                        </button>
                    </div>
                </form>

                <form action="{{ route('admin.legios.destroy', $legio) }}" method="POST" onsubmit="return confirm('Hapus legio ini beserta fotonya?')" class="mt-2">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="text-xs text-red-600 hover:text-red-800 font-medium">Hapus Legio ini</button>
                </form>
            </div>
        @endforeach
    </div>

    <!-- Form tambah legio baru -->
    <div class="border-2 border-dashed rounded-xl p-4 mt-6">
        <p class="text-sm font-semibold text-emerald-700 mb-4">+ Tambah Legio Baru</p>
        <form action="{{ route('admin.legios.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
            @csrf

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Nama Legio</label>
                <input type="text" name="name" required placeholder="Contoh: Legio Maria Presidium “...”" class="w-full px-4 py-2 border rounded-lg text-sm">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi</label>
                <input id="legio_desc_new" type="hidden" name="description">
                <trix-editor input="legio_desc_new" class="bg-white border rounded-lg min-h-[140px] text-sm"></trix-editor>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Foto (opsional)</label>
                <input type="file" name="photo" accept="image/*" class="text-xs text-gray-600">
            </div>

            <div class="flex justify-end">
                <button type="submit" class="px-5 py-2 bg-emerald-600 hover:bg-emerald-700 text-white font-medium rounded-lg text-sm shadow transition">
                    + Tambah Legio
                </button>
            </div>
        </form>
    </div>
</div>

<!-- CARD FOTO DOKUMENTASI -->
<div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mt-8">
    <div class="flex flex-col sm:flex-row justify-between sm:items-center gap-4 border-b pb-4 mb-6">
        <div>
            <h3 class="text-lg font-semibold text-gray-800">7. Foto Dokumentasi (Hero & Galeri)</h3>
            <p class="text-xs text-gray-500 mt-0.5">Foto terbaru otomatis menjadi slide pada section Hero dan Galeri.</p>
        </div>
        <form action="{{ route('admin.documentations.store') }}" method="POST" enctype="multipart/form-data" class="flex items-center gap-3">
            @csrf
            <input type="text" name="title" placeholder="Judul Foto (Opsional)" class="px-3 py-1.5 border rounded-lg text-xs">
            <input type="file" name="image" required accept="image/*" class="text-xs text-gray-600">
            <button type="submit" class="px-4 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg text-xs font-medium shadow-sm">
                + Upload Foto
            </button>
        </form>
    </div>

    @if($documentations->isEmpty())
        <div class="text-center py-12 border-2 border-dashed rounded-xl text-gray-400 text-sm">Belum ada foto dokumentasi yang diunggah.</div>
    @else
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-4">
            @foreach($documentations as $doc)
                <div class="bg-gray-50 border rounded-lg overflow-hidden flex flex-col justify-between">
                    <div class="aspect-video w-full bg-slate-200">
                        <img src="{{ asset('storage/' . $doc->image_path) }}" alt="{{ $doc->title }}" class="w-full h-full object-cover">
                    </div>
                    <div class="p-3 flex items-center justify-between gap-2 bg-white">
                        <span class="text-xs text-gray-600 truncate">{{ $doc->title ?? 'Tanpa Judul' }}</span>
                        <form action="{{ route('admin.documentations.destroy', $doc->id) }}" method="POST" onsubmit="return confirm('Hapus foto ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-500 hover:text-red-700 p-1">Hapus</button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>

@endsection