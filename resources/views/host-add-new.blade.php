@extends('layouts.host', ['activeTab' => 'add-new'])

@section('title', 'Add New Property - Host NginUp')
@section('page_title', 'Add New Property')

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
@endpush

@section('content')

<div class="max-w-6xl mx-auto md:pt-4 pb-12" x-data="{ 
    step: {{ $errors->any() ? '4' : '1' }},
    scrollToTop() { 
        window.scrollTo({top:0, behavior:'smooth'}); 
        const mainScroll = document.querySelector('main');
        if(mainScroll) mainScroll.scrollTo({top:0, behavior:'smooth'});
    } 
}">

    <div class="bg-white md:rounded-[32px] shadow-sm md:shadow-xl md:shadow-gray-200/50 md:border md:border-gray-100 flex flex-col md:flex-row w-full overflow-hidden">
        
        {{-- Left Side: Image & Desktop Progress (Sticky) --}}
        <div class="hidden md:flex w-full md:w-2/5 relative shrink-0 bg-gray-900 flex-col p-10 overflow-hidden">
            <img src="https://images.unsplash.com/photo-1600596542815-ffad4c1539a9?q=80&w=1200&auto=format&fit=crop" class="absolute inset-0 w-full h-full object-cover opacity-40 hover:scale-105 transition-transform duration-1000">
            <div class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/50 to-black/20"></div>
            
            <div class="relative z-10 mb-12">
                <h2 class="text-white text-4xl font-extrabold leading-tight tracking-tight">Add New<br>Property</h2>
                <p class="text-white/80 text-sm mt-4 leading-relaxed max-w-xs">
                    Share your space with the world. Complete these steps to publish your listing.
                </p>
            </div>

            <div class="relative z-10 mt-auto space-y-8 pb-8">
                {{-- Desktop Steps Tracker --}}
                <div class="flex items-center gap-4 transition-all duration-500" :class="step >= 1 ? 'opacity-100' : 'opacity-40'">
                    <div class="w-10 h-10 rounded-full flex items-center justify-center font-bold shadow-lg transition-all duration-300" :class="step > 1 ? 'bg-brand-orange text-white' : (step === 1 ? 'bg-white text-brand-orange ring-4 ring-white/20' : 'border-2 border-white/50 text-white')">
                        <i class="fa-solid fa-check" x-show="step > 1"></i>
                        <span x-show="step <= 1">1</span>
                    </div>
                    <span class="text-white font-bold tracking-wide">Basics</span>
                </div>
                
                <div class="flex items-center gap-4 transition-all duration-500" :class="step >= 2 ? 'opacity-100' : 'opacity-40'">
                    <div class="w-10 h-10 rounded-full flex items-center justify-center font-bold shadow-lg transition-all duration-300" :class="step > 2 ? 'bg-brand-orange text-white' : (step === 2 ? 'bg-white text-brand-orange ring-4 ring-white/20' : 'border-2 border-white/50 text-white')">
                        <i class="fa-solid fa-check" x-show="step > 2" style="display: none;"></i>
                        <span x-show="step <= 2">2</span>
                    </div>
                    <span class="text-white font-bold tracking-wide">Location & Pricing</span>
                </div>
                
                <div class="flex items-center gap-4 transition-all duration-500" :class="step >= 3 ? 'opacity-100' : 'opacity-40'">
                    <div class="w-10 h-10 rounded-full flex items-center justify-center font-bold shadow-lg transition-all duration-300" :class="step > 3 ? 'bg-brand-orange text-white' : (step === 3 ? 'bg-white text-brand-orange ring-4 ring-white/20' : 'border-2 border-white/50 text-white')">
                        <i class="fa-solid fa-check" x-show="step > 3" style="display: none;"></i>
                        <span x-show="step <= 3">3</span>
                    </div>
                    <span class="text-white font-bold tracking-wide">Amenities & Features</span>
                </div>

                <div class="flex items-center gap-4 transition-all duration-500" :class="step >= 4 ? 'opacity-100' : 'opacity-40'">
                    <div class="w-10 h-10 rounded-full flex items-center justify-center font-bold shadow-lg transition-all duration-300" :class="step > 4 ? 'bg-brand-orange text-white' : (step === 4 ? 'bg-white text-brand-orange ring-4 ring-white/20' : 'border-2 border-white/50 text-white')">
                        <i class="fa-solid fa-check" x-show="step > 4" style="display: none;"></i>
                        <span x-show="step <= 4">4</span>
                    </div>
                    <span class="text-white font-bold tracking-wide">Photos & Review</span>
                </div>
            </div>
        </div>

        {{-- Right Side: Form Content --}}
        <div class="w-full md:w-3/5 bg-white relative flex flex-col min-h-[80vh] md:min-h-0">
            
            {{-- Mobile Progress Bar --}}
            <div x-show="step < 5" class="md:hidden bg-white px-4 py-4 border-b border-gray-100 sticky top-0 z-20">
                <div class="flex justify-between items-end mb-2">
                    <span class="text-brand-orange font-bold text-xs uppercase tracking-wider" x-text="'STEP ' + step + ' OF 4'"></span>
                    <span class="text-[10px] text-gray-500 font-semibold" x-text="Math.round((step / 4) * 100) + '% Complete'"></span>
                </div>
                <div class="w-full h-1.5 bg-gray-100 rounded-full overflow-hidden">
                    <div class="h-full bg-brand-orange rounded-full transition-all duration-500 ease-out" :style="'width: ' + ((step / 4) * 100) + '%'"></div>
                </div>
            </div>

            <div class="p-6 md:p-10 lg:p-12 w-full max-w-xl mx-auto flex-1">
                
                @if ($errors->any())
                    <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-2xl">
                        <ul class="list-disc list-inside text-sm text-red-600 space-y-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="/host/add-new" enctype="multipart/form-data" class="contents">
                @csrf

                {{-- STEP 1: Basics --}}
                <div x-show="step === 1" x-transition.opacity.duration.300ms>
                    <div class="mb-6">
                        <h2 class="text-2xl font-extrabold text-gray-900 mb-2">Let's start with the basics</h2>
                        <p class="text-sm text-gray-500">Tell us a bit about your property. You can always edit these details later.</p>
                    </div>

                    <div class="space-y-6">
                        {{-- Property Name --}}
                        <div>
                            <label class="text-xs font-bold text-gray-700 block mb-2">Property Name <span class="text-red-500">*</span></label>
                            <input type="text" name="nama_properti" required placeholder="e.g. Serene Riverfront Villa" value="{{ old('nama_properti') }}" class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-brand-orange focus:ring-1 focus:ring-brand-orange">
                        </div>

                        {{-- Property Type --}}
                        <div>
                            <label class="text-xs font-bold text-gray-700 block mb-3">Property Type <span class="text-red-500">*</span></label>
                            <div class="grid grid-cols-2 gap-3">
                                @php
                                    $tipeList = [
                                        ['value' => 'hotel', 'label' => 'Hotel', 'icon' => 'fa-building'],
                                        ['value' => 'villa', 'label' => 'Villa', 'icon' => 'fa-house-chimney-window'],
                                        ['value' => 'apartemen', 'label' => 'Apartemen', 'icon' => 'fa-city'],
                                        ['value' => 'rumah', 'label' => 'Rumah', 'icon' => 'fa-house'],
                                        ['value' => 'kost', 'label' => 'Kost', 'icon' => 'fa-door-open'],
                                        ['value' => 'guesthouse', 'label' => 'Guesthouse', 'icon' => 'fa-tree-city'],
                                    ];
                                @endphp
                                @foreach ($tipeList as $tipe)
                                <label class="border rounded-xl py-4 flex flex-col items-center gap-2 transition-all cursor-pointer select-none {{ old('tipe_properti') === $tipe['value'] ? 'border-brand-orange bg-orange-50 text-brand-orange' : 'border-gray-200 text-gray-500 hover:border-brand-orange hover:bg-orange-50/50' }}">
                                    <input type="radio" name="tipe_properti" value="{{ $tipe['value'] }}" {{ old('tipe_properti') === $tipe['value'] ? 'checked' : '' }} class="sr-only" required>
                                    <i class="fa-solid {{ $tipe['icon'] }} text-xl"></i>
                                    <span class="text-[11px] font-bold">{{ $tipe['label'] }}</span>
                                </label>
                                @endforeach
                            </div>
                        </div>

                        {{-- Description --}}
                        <div>
                            <div class="flex justify-between mb-2">
                                <label class="text-xs font-bold text-gray-700 block">Description <span class="text-red-500">*</span></label>
                                <span class="text-[10px] text-gray-400">0/500</span>
                            </div>
                            <textarea name="deskripsi" required rows="4" placeholder="Describe the unique features, the neighborhood, and the vibe of your place..." class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-brand-orange focus:ring-1 focus:ring-brand-orange resize-none">{{ old('deskripsi') }}</textarea>
                        </div>
                    </div>

                    <div class="mt-10">
                        <button type="button" @click="step = 2; scrollToTop()" class="w-full bg-brand-orange hover:bg-brand-orange-hover text-white py-4 rounded-xl font-bold shadow-md shadow-brand-orange/20 transition-all active:scale-[0.98] flex items-center justify-center gap-2">
                            Next Step <i class="fa-solid fa-chevron-right text-xs"></i>
                        </button>
                    </div>
                </div>

                {{-- STEP 2: Location & Pricing --}}
                <div x-show="step === 2" x-transition.opacity.duration.300ms style="display: none;">
                    <div class="mb-6">
                        <h2 class="text-2xl font-extrabold text-gray-900 mb-2">Location & Pricing</h2>
                        <p class="text-sm text-gray-500">Help guests find your place and set your rates.</p>
                    </div>

                    <div class="space-y-6">
                        {{-- Location --}}
                        <div>
                            <label class="text-xs font-bold text-gray-700 block mb-2 flex items-center gap-2">
                                <i class="fa-solid fa-location-dot text-brand-orange"></i> Where is your property? <span class="text-red-500">*</span>
                            </label>
                            <div class="relative mb-3">
                                <input type="text" name="alamat" required placeholder="Enter your full address" value="{{ old('alamat') }}" class="w-full border border-gray-200 rounded-xl pl-10 pr-4 py-3 text-sm focus:outline-none focus:border-brand-orange focus:ring-1 focus:ring-brand-orange">
                                <i class="fa-solid fa-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"></i>
                            </div>
                            <div class="grid grid-cols-2 gap-3 mb-3">
                                <input type="text" name="kota" required placeholder="City" value="{{ old('kota') }}" class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-brand-orange focus:ring-1 focus:ring-brand-orange">
                                <input type="text" name="provinsi" required placeholder="Province" value="{{ old('provinsi') }}" class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-brand-orange focus:ring-1 focus:ring-brand-orange">
                            </div>
                            <div id="location-picker-map" class="w-full h-64 bg-gray-100 rounded-xl overflow-hidden border border-gray-200"></div>
                            <input type="hidden" name="latitude" id="latitude" value="{{ old('latitude') }}">
                            <input type="hidden" name="longitude" id="longitude" value="{{ old('longitude') }}">
                            <p class="text-xs text-gray-400 mt-1">Klik peta untuk menandai lokasi properti Anda</p>
                        </div>

                        <hr class="border-gray-100">

                        {{-- Pricing --}}
                        <div>
                            <label class="text-xs font-bold text-gray-700 block mb-2 flex items-center gap-2">
                                <i class="fa-solid fa-money-bill-wave text-brand-orange"></i> Set your base price <span class="text-red-500">*</span>
                            </label>
                            <div class="flex items-center gap-3 mb-4">
                                <div class="flex-1 relative">
                                    <div class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-500 font-bold">Rp</div>
                                    <input type="number" name="harga_per_malam" required placeholder="Contoh: 500000" value="{{ old('harga_per_malam') }}" class="w-full border border-gray-200 rounded-xl pl-10 pr-4 py-4 text-xl font-bold text-gray-800 focus:outline-none focus:border-brand-orange focus:ring-1 focus:ring-brand-orange">
                                </div>
                                <span class="text-xs text-gray-500 font-medium">per<br>night</span>
                            </div>

                            <div class="grid grid-cols-2 gap-3 mb-4">
                                <div>
                                    <label class="text-xs font-bold text-gray-700 block mb-2">Max Guests <span class="text-red-500">*</span></label>
                                    <input type="number" name="max_tamu" required value="{{ old('max_tamu', 1) }}" min="1" class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-brand-orange focus:ring-1 focus:ring-brand-orange">
                                </div>
                                <div>
                                    <label class="text-xs font-bold text-gray-700 block mb-2">Bedrooms <span class="text-red-500">*</span></label>
                                    <input type="number" name="jumlah_ruang" required value="{{ old('jumlah_ruang', 1) }}" min="0" class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-brand-orange focus:ring-1 focus:ring-brand-orange">
                                </div>
                            </div>
                    </div>
                    </div>

                    <div class="mt-10 flex gap-3">
                        <button type="button" @click="step = 1; scrollToTop()" class="w-1/3 bg-white border border-gray-200 text-gray-600 py-4 rounded-xl font-bold shadow-sm hover:bg-gray-50 transition-colors">
                            Back
                        </button>
                        <button type="button" @click="step = 3; scrollToTop()" class="w-2/3 bg-brand-orange hover:bg-brand-orange-hover text-white py-4 rounded-xl font-bold shadow-md shadow-brand-orange/20 transition-all active:scale-[0.98] flex items-center justify-center gap-2">
                            Next Step <i class="fa-solid fa-chevron-right text-xs"></i>
                        </button>
                    </div>
                </div>

                {{-- STEP 3: Amenities & Features --}}
                <div x-show="step === 3" x-transition.opacity.duration.300ms style="display: none;">
                    <div class="mb-6">
                        <h2 class="text-2xl font-extrabold text-gray-900 mb-2">Amenities & Features</h2>
                        <p class="text-sm text-gray-500">What makes your place special?</p>
                    </div>

                    <div x-data="{ search: '' }" class="space-y-4">
                    <div class="relative mb-2">
                        <input type="text" x-model="search" placeholder="Search amenities..." class="w-full bg-gray-50 border-none rounded-xl pl-10 pr-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-gray-200">
                        <i class="fa-solid fa-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"></i>
                    </div>

                    <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
                        @forelse ($fasilitasList as $fasilitas)
                        <div x-data="{ checked: false }" x-show="$el.textContent.toLowerCase().includes(search.toLowerCase()) || search === ''">
                            <input type="checkbox" name="fasilitas[]" value="{{ $fasilitas->id_fasilitas }}" :checked="checked" class="hidden">
                            <button type="button" @click="checked = !checked"
                                :class="checked ? 'border-brand-orange bg-orange-50 text-brand-orange' : 'border-gray-200 text-gray-500 hover:border-brand-orange hover:bg-orange-50/50'"
                                class="w-full border rounded-xl p-4 flex flex-col items-center justify-center gap-2 cursor-pointer transition-all">
                                <i class="fa-solid fa-{{ $fasilitas->ikon_fasilitas }} text-2xl"></i>
                                <span class="text-[10px] font-bold text-center">{{ $fasilitas->nama_fasilitas }}</span>
                                <i x-show="checked" class="fa-solid fa-circle-check text-brand-orange text-xs"></i>
                            </button>
                        </div>
                        @empty
                        <p class="text-sm text-gray-500 col-span-full">No amenities available.</p>
                        @endforelse
                    </div>

                    <div class="mt-10 flex gap-3">
                        <button type="button" @click="step = 2; scrollToTop()" class="w-1/3 bg-white border border-gray-200 text-gray-600 py-4 rounded-xl font-bold shadow-sm hover:bg-gray-50 transition-colors">
                            Back
                        </button>
                        <button type="button" @click="step = 4; scrollToTop()" class="w-2/3 bg-brand-orange hover:bg-brand-orange-hover text-white py-4 rounded-xl font-bold shadow-md shadow-brand-orange/20 transition-all active:scale-[0.98] flex items-center justify-center gap-2">
                            Continue
                        </button>
                    </div>
                </div>
                </div>
                </div>

                {{-- STEP 4: Photos & Review --}}
                <div x-show="step === 4" x-transition.opacity.duration.300ms style="display: none;">
                    <div class="mb-6">
                        <h2 class="text-2xl font-extrabold text-gray-900 mb-2">Photos & Review</h2>
                        <p class="text-sm text-gray-500">Upload photos and review your listing before submitting.</p>
                    </div>

                    <div class="space-y-6">
                            {{-- Photos Upload --}}
                            <div>
                                <div class="flex justify-between items-end mb-2">
                                    <label class="text-xs font-bold text-gray-700">Property Photos</label>
                                    <span class="text-[10px] text-gray-400">Max 5MB per image</span>
                                </div>
                                
                                <input type="file" name="gambar[]" multiple accept="image/jpeg,image/png" onchange="previewImages(event)" class="w-full border border-gray-200 rounded-xl px-4 py-8 text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-bold file:bg-brand-orange file:text-white hover:file:bg-brand-orange-hover cursor-pointer">
                                <div id="image-preview" class="grid grid-cols-3 gap-2 mt-4"></div>
                            </div>

                            <hr class="border-gray-100">

                            <script>
                            function previewImages(event) {
                                const files = event.target.files;
                                const container = document.getElementById('image-preview');
                                container.innerHTML = '';
                                for (let i = 0; i < files.length; i++) {
                                    const reader = new FileReader();
                                    reader.onload = function(e) {
                                        const div = document.createElement('div');
                                        div.className = 'relative rounded-xl overflow-hidden h-28';
                                        div.innerHTML = '<img src="' + e.target.result + '" class="w-full h-full object-cover">';
                                        container.appendChild(div);
                                    };
                                    reader.readAsDataURL(files[i]);
                                }
                            }
                            </script>
                    </div>

                    <div class="mt-10 flex flex-col gap-3">
                        <button type="submit" class="w-full bg-brand-orange hover:bg-brand-orange-hover text-white py-4 rounded-xl font-bold shadow-md shadow-brand-orange/20 transition-all active:scale-[0.98]">
                            Submit for Review
                        </button>
                        <button type="button" @click="step = 3; scrollToTop()" class="w-full bg-white border border-gray-200 text-gray-600 py-3 rounded-xl text-sm font-bold shadow-sm hover:bg-gray-50 transition-colors flex items-center justify-center gap-2">
                            <i class="fa-solid fa-arrow-left"></i> Back
                        </button>
                    </div>
                </div>

                </form>

                {{-- STEP 5: Success --}}
                <div x-show="step === 5" x-transition.opacity.duration.500ms style="display: none;" class="text-center pt-8 pb-10">
                    <div class="w-20 h-20 bg-orange-50 text-brand-orange rounded-full flex items-center justify-center text-4xl mx-auto mb-6 shadow-sm border-4 border-white relative">
                        <i class="fa-solid fa-check"></i>
                        <div class="absolute inset-0 rounded-full border-2 border-brand-orange animate-ping opacity-20"></div>
                    </div>
                    
                    <h2 class="text-3xl font-extrabold text-gray-900 mb-3 tracking-tight">Listing Submitted!</h2>
                    <p class="text-sm text-gray-500 mb-8 leading-relaxed max-w-sm mx-auto">
                        Your property is now being reviewed by the NginUp team. We typically verify all listings within 24 hours to ensure quality and trust.
                    </p>

                    <div class="bg-gray-50 border border-gray-100 rounded-2xl p-4 mb-8 text-left max-w-sm mx-auto">
                        <div class="flex gap-4 items-center">
                            <img src="https://images.unsplash.com/photo-1600596542815-ffad4c1539a9?q=80&w=400&auto=format&fit=crop" class="w-16 h-12 rounded-lg object-cover">
                            <div>
                                <h4 class="font-bold text-gray-900 text-xs">Serene Riverfront Villa</h4>
                                <div class="flex gap-2 mt-1">
                                    <span class="px-2 py-0.5 bg-blue-100 text-blue-600 text-[8px] font-bold rounded-full">Under Review</span>
                                    <span class="text-[9px] text-gray-500">Est. < 24 hrs remaining</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="flex flex-col gap-3 max-w-sm mx-auto">
                        <a href="/host/dashboard" class="w-full bg-brand-orange hover:bg-brand-orange-hover text-white py-4 rounded-xl font-bold shadow-md shadow-brand-orange/20 transition-all active:scale-[0.98]">
                            Go to Dashboard
                        </a>
                        <button @click="step = 1; scrollToTop()" class="w-full bg-white border border-gray-200 text-gray-600 py-3.5 rounded-xl text-sm font-bold shadow-sm hover:bg-gray-50 transition-colors flex items-center justify-center gap-2">
                            <i class="fa-solid fa-plus"></i> Add Another Property
                        </button>
                        
                        <a href="#" class="text-[10px] text-brand-orange hover:underline mt-2">Need help? Contact Host Support</a>
                    </div>
                </div>

            </div>
        </div>

    </div>

</div>

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    var defaultLat = -7.250445;
    var defaultLng = 107.5;
    var map = L.map('location-picker-map').setView([defaultLat, defaultLng], 5);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; OpenStreetMap'
    }).addTo(map);
    var marker = null;
    map.on('click', function(e) {
        if (marker) map.removeLayer(marker);
        marker = L.marker(e.latlng).addTo(map);
        document.getElementById('latitude').value = e.latlng.lat.toFixed(7);
        document.getElementById('longitude').value = e.latlng.lng.toFixed(7);
    });
    // Try geolocation
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(function(pos) {
            map.setView([pos.coords.latitude, pos.coords.longitude], 13);
        }, function() {});
    }
});
</script>
@endpush

@endsection
