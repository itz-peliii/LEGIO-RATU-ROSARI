@extends('layouts.admin')

@section('content')

<!-- Form Update Konten Teks -->
<form action="{{ route('admin.settings.update') }}" method="POST" class="space-y-8">
    @csrf
    @method('PUT')

    <!-- CARD 1: HERO SECTION -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <div class="border-b border-gray-100 pb-4 mb-6">
            <h3 class="text-lg font-semibold text-gray-800">1. Hero / Banner Section</h3>
            <p class="text-xs text-gray-500 mt-0.5">Ubah teks pembuka yang tampil di bagian paling atas website.</p>
        </div>
        
        <div class="space-y-5">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Judul Utama (Hero Title)</label>
                <input type="text" name="hero_title" value="{{ $settings['hero_title'] ?? '' }}" 
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:outline-none">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Sub Judul / Deskripsi Ringkas</label>
                <input id="hero_subtitle" type="hidden" name="hero_subtitle" value="{{ $settings['hero_subtitle'] ?? '' }}">
                <trix-editor input="hero_subtitle" class="bg-white border-gray-300 rounded-lg min-h-[120px] text-sm"></trix-editor>
            </div>
        </div>
    </div>

    <!-- CARD 2: VISI MISI SECTION -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <div class="border-b border-gray-100 pb-4 mb-6">
            <h3 class="text-lg font-semibold text-gray-800">2. Visi & Misi Section</h3>
            <p class="text-xs text-gray-500 mt-0.5">Kelola poin visi dan misi organisasi gereja.</p>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Konten Visi & Misi</label>
            <input id="visi_misi" type="hidden" name="visi_misi" value="{{ $settings['visi_misi'] ?? '' }}">
            <trix-editor input="visi_misi" class="bg-white border-gray-300 rounded-lg min-h-[220px] text-sm"></trix-editor>
        </div>
    </div>

    <!-- CARD 3: DOCUMENTATION TEXT SECTION -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <div class="border-b border-gray-100 pb-4 mb-6">
            <h3 class="text-lg font-semibold text-gray-800">3. Deskripsi Section Dokumentasi</h3>
            <p class="text-xs text-gray-500 mt-0.5">Teks pengantar sebelum galeri foto ditampilkan.</p>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Pengantar Galeri</label>
            <input id="doc_description" type="hidden" name="doc_description" value="{{ $settings['doc_description'] ?? '' }}">
            <trix-editor input="doc_description" class="bg-white border-gray-300 rounded-lg min-h-[120px] text-sm"></trix-editor>
        </div>
    </div>

    <!-- Save Button for All Text Fields -->
    <div class="flex justify-end">
        <button type="submit" class="px-6 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white font-medium rounded-lg text-sm shadow transition">
            Simpan Perubahan Teks
        </button>
    </div>
</form>

<!-- CARD 4: DOCUMENTATION PHOTO MANAGEMENT -->
<div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mt-8">
    <div class="flex flex-col sm:flex-row justify-between sm:items-center gap-4 border-b border-gray-100 pb-4 mb-6">
        <div>
            <h3 class="text-lg font-semibold text-gray-800">4. Foto Dokumentasi</h3>
            <p class="text-xs text-gray-500 mt-0.5">Kelola foto-foto kegiatan yang akan tampil pada landing page.</p>
        </div>
        
        <!-- Form Add Photo -->
        <form action="{{ route('admin.documentations.store') }}" method="POST" enctype="multipart/form-data" class="flex items-center gap-3">
            @csrf
            <div>
                <input type="text" name="title" placeholder="Judul Foto (Opsional)" class="px-3 py-1.5 border border-gray-300 rounded-lg text-xs focus:outline-none">
            </div>
            <div>
                <input type="file" name="image" required accept="image/*" class="text-xs text-gray-600 file:mr-2 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
            </div>
            <button type="submit" class="px-4 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg text-xs font-medium transition shadow-sm">
                + Upload Foto
            </button>
        </form>
    </div>

    <!-- PHOTO GRID RECTANGLES -->
    @if($documentations->isEmpty())
        <div class="text-center py-12 border-2 border-dashed border-gray-200 rounded-xl">
            <p class="text-sm text-gray-400">Belum ada foto dokumentasi yang diunggah.</p>
        </div>
    @else
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-4">
            @foreach($documentations as $doc)
                <div class="group relative bg-gray-50 border border-gray-200 rounded-lg overflow-hidden shadow-sm flex flex-col justify-between">
                    
                    <!-- Rectangle Image Container -->
                    <div class="aspect-video w-full overflow-hidden bg-slate-200">
                        <img src="{{ asset('storage/' . $doc->image_path) }}" alt="{{ $doc->title }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
                    </div>

                    <!-- Caption & Delete Action -->
                    <div class="p-3 flex items-center justify-between gap-2 bg-white">
                        <span class="text-xs text-gray-600 font-medium truncate" title="{{ $doc->title }}">
                            {{ $doc->title ?? 'Tanpa Judul' }}
                        </span>

                        <form action="{{ route('admin.documentations.destroy', $doc->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus foto ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-500 hover:text-red-700 p-1 rounded hover:bg-red-50 transition" title="Hapus Foto">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                </svg>
                            </button>
                        </form>
                    </div>

                </div>
            @endforeach
        </div>
    @endif
</div>

@endsection