@extends('layouts.admin')

@section('content')
<div x-data="{ device: 'desktop' }" class="space-y-4">
    
    <!-- Preview Control Bar -->
    <div class="bg-white p-4 rounded-xl border border-gray-200 shadow-sm flex flex-col sm:flex-row items-center justify-between gap-4">
        <div class="flex items-center gap-3">
            <span class="text-sm font-medium text-gray-700">Mode Tampilan:</span>
            <div class="inline-flex rounded-lg border border-gray-200 bg-gray-50 p-1">
                <!-- Desktop View Button -->
                <button @click="device = 'desktop'" 
                        :class="device === 'desktop' ? 'bg-white shadow text-indigo-600 font-semibold' : 'text-gray-600 hover:text-gray-900'"
                        class="px-3 py-1.5 rounded-md text-xs transition flex items-center gap-1.5">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                    Desktop
                </button>
                
                <!-- Mobile View Button -->
                <button @click="device = 'mobile'" 
                        :class="device === 'mobile' ? 'bg-white shadow text-indigo-600 font-semibold' : 'text-gray-600 hover:text-gray-900'"
                        class="px-3 py-1.5 rounded-md text-xs transition flex items-center gap-1.5">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                    </svg>
                    Mobile
                </button>
            </div>
        </div>

        <div class="flex items-center gap-3">
            <!-- Refresh Iframe Button -->
            <button onclick="document.getElementById('preview-frame').src = document.getElementById('preview-frame').src;" 
                    class="px-3 py-1.5 bg-gray-100 hover:bg-gray-200 text-gray-700 text-xs font-medium rounded-lg transition flex items-center gap-1.5">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                </svg>
                Refresh Preview
            </button>
        </div>
    </div>

    <!-- Iframe Container -->
    <div class="w-full flex justify-center items-center bg-slate-800 rounded-xl p-6 min-h-[800px] border border-gray-300 overflow-x-auto">
        <iframe id="preview-frame" 
                src="{{ route('home') }}" 
                :style="device === 'mobile' ? 'width: 390px; height: 780px;' : 'width: 100%; height: 750px;'"
                :class="device === 'mobile' ? 'rounded-[35px] border-[10px] border-slate-900 shadow-2xl ring-1 ring-slate-700' : 'rounded-lg border-0 shadow-md'"
                class="transition-all duration-300 bg-white">
        </iframe>
    </div>

</div>
@endsection