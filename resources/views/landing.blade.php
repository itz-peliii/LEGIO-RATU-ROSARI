@extends('layouts.app')

@section('content')

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
            {!! $settings['about_us'] ?? '<p class="text-center text-slate-400">Konten tentang kami belum diisi oleh admin.</p>' !!}
        </div>
    </div>
</section>

<!-- 3. VISI & MISI SECTION (at least 1 screen height) -->
<section id="vision-mission" class="min-h-screen flex items-center bg-slate-50 py-20 border-b border-slate-200">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 w-full">
        <div class="text-center max-w-3xl mx-auto space-y-4 mb-12">
            <span class="text-indigo-600 font-semibold text-sm tracking-wider uppercase">Arah & Tujuan</span>
            <h2 class="text-3xl sm:text-4xl font-bold text-slate-900">Visi & Misi</h2>
            <div class="w-16 h-1 bg-indigo-600 mx-auto rounded-full"></div>
        </div>

        <div class="bg-white border border-slate-200 rounded-2xl p-8 sm:p-12 shadow-sm prose prose-slate max-w-none text-slate-700 leading-relaxed">
            {!! $settings['visi_misi'] ?? '<p class="text-center text-slate-400">Visi & Misi belum diisi oleh admin.</p>' !!}
        </div>
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

@endsection