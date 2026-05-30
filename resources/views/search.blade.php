<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    @vite('resources/css/app.css')
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <title>Search</title>
</head>
<body class="bg-gray-50 text-[#1A1F2B]">

<div class="w-full bg-white min-h-screen flex flex-col relative shadow-sm">

    {{-- Header --}}
    <header class="flex items-center gap-3 px-4 md:px-8 py-4 border-b bg-white sticky top-0 z-20">
        <a href="/home" class="text-gray-700 hover:bg-gray-100 w-10 h-10 flex items-center justify-center rounded-full transition-colors shrink-0">
            <i class="fa-solid fa-arrow-left text-lg"></i>
        </a>
        <div class="flex-1 bg-[#F5F5F5] rounded-xl flex items-center px-4 py-3 md:py-4">
            <i class="fa-solid fa-magnifying-glass text-gray-500 mr-3 text-lg"></i>
            <input type="text" id="search-input" placeholder="Cari destinasi atau penginapan..." class="w-full bg-transparent outline-none text-gray-800 text-sm md:text-base placeholder-gray-500 font-medium">
            <i class="fa-solid fa-microphone text-gray-500 ml-3 text-lg"></i>
        </div>
    </header>

    <div class="flex-1 overflow-y-auto pb-32">
        
        <div id="default-content">
        {{-- Saran Lokasi --}}
        <section class="px-5 md:px-8 py-6">
            <h2 class="text-xl md:text-2xl font-bold text-gray-900 mb-5">Saran Lokasi</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <a href="/search?kota=Seminyak" class="flex items-center gap-4 hover:bg-gray-50 p-2 -mx-2 md:mx-0 md:p-4 rounded-xl md:border md:border-gray-100 transition-colors">
                    <div class="w-12 h-12 rounded-xl bg-brand-green/10 flex items-center justify-center text-brand-green shrink-0">
                        <i class="fa-solid fa-location-dot text-xl"></i>
                    </div>
                    <div>
                        <h3 class="text-base font-bold text-gray-900">Seminyak, Bali</h3>
                        <p class="text-sm text-gray-500 mt-0.5">Kecamatan Kuta, Kabupaten Badung</p>
                    </div>
                </a>
                <a href="/search?kota=Ubud" class="flex items-center gap-4 hover:bg-gray-50 p-2 -mx-2 md:mx-0 md:p-4 rounded-xl md:border md:border-gray-100 transition-colors">
                    <div class="w-12 h-12 rounded-xl bg-brand-green/10 flex items-center justify-center text-brand-green shrink-0">
                        <i class="fa-solid fa-location-dot text-xl"></i>
                    </div>
                    <div>
                        <h3 class="text-base font-bold text-gray-900">Ubud, Bali</h3>
                        <p class="text-sm text-gray-500 mt-0.5">Kabupaten Gianyar, Bali</p>
                    </div>
                </a>
            </div>
        </section>

        {{-- Pencarian Terakhir --}}
        <section class="px-5 md:px-8 py-4">
            <div class="flex justify-between items-center mb-5">
                <h2 class="text-xl md:text-2xl font-bold text-gray-900">Pencarian Terakhir</h2>
                <button class="text-brand-orange text-sm font-bold hover:underline">Hapus</button>
            </div>
            <div class="flex flex-wrap gap-3">
                <a href="/search?kota=Bali" class="flex items-center gap-2 border border-gray-200 rounded-full px-5 py-2.5 hover:bg-gray-50 transition-colors">
                    <i class="fa-solid fa-clock-rotate-left text-gray-500 text-sm"></i>
                    <span class="text-sm font-semibold text-gray-700">Bali</span>
                </a>
                <a href="/search?kota=Bandung" class="flex items-center gap-2 border border-gray-200 rounded-full px-5 py-2.5 hover:bg-gray-50 transition-colors">
                    <i class="fa-solid fa-clock-rotate-left text-gray-500 text-sm"></i>
                    <span class="text-sm font-semibold text-gray-700">Bandung</span>
                </a>
                <a href="/search?kota=Yogyakarta" class="flex items-center gap-2 border border-gray-200 rounded-full px-5 py-2.5 hover:bg-gray-50 transition-colors">
                    <i class="fa-solid fa-clock-rotate-left text-gray-500 text-sm"></i>
                    <span class="text-sm font-semibold text-gray-700">Yogyakarta</span>
                </a>
            </div>
        </section>

        {{-- Destinasi Populer --}}
        <section class="px-5 md:px-8 py-8">
            <h2 class="text-xl md:text-2xl font-bold text-gray-900 mb-5">Destinasi Populer</h2>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 md:gap-6">
                <a href="/search?kota=Bali" class="rounded-2xl overflow-hidden relative h-[240px] md:h-[300px] group shadow-sm block">
                    <img src="https://images.unsplash.com/photo-1537996194471-e657df975ab4?q=80&w=600&auto=format&fit=crop" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent"></div>
                    <div class="absolute top-4 right-4 bg-brand-orange text-white text-[10px] md:text-xs font-bold px-3 py-1.5 rounded-full uppercase tracking-wider shadow-md">
                        Trending
                    </div>
                    <div class="absolute bottom-5 left-5">
                        <h3 class="text-white text-2xl font-bold">Bali</h3>
                    </div>
                </a>
                <a href="/search?kota=Lombok" class="rounded-2xl overflow-hidden relative h-[240px] md:h-[300px] group shadow-sm block">
                    <img src="https://images.unsplash.com/photo-1582298653637-2338bd486a42?q=80&w=600&auto=format&fit=crop" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent"></div>
                    <div class="absolute bottom-5 left-5">
                        <h3 class="text-white text-2xl font-bold">Lombok</h3>
                    </div>
                </a>
                <a href="/search?kota=Yogyakarta" class="hidden md:block rounded-2xl overflow-hidden relative h-[300px] group shadow-sm">
                    <img src="https://images.unsplash.com/photo-1513415564515-763d91423bdd?q=80&w=600&auto=format&fit=crop" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent"></div>
                    <div class="absolute bottom-5 left-5">
                        <h3 class="text-white text-2xl font-bold">Yogyakarta</h3>
                    </div>
                </a>
                <a href="/search?kota=Bandung" class="hidden md:block rounded-2xl overflow-hidden relative h-[300px] group shadow-sm">
                    <img src="https://images.unsplash.com/photo-1549473889-14f410d83298?q=80&w=600&auto=format&fit=crop" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent"></div>
                    <div class="absolute bottom-5 left-5">
                        <h3 class="text-white text-2xl font-bold">Bandung</h3>
                    </div>
                </a>
            </div>
        </section>
        </div>

        {{-- Search Results --}}
        <div id="search-results" class="hidden px-4 md:px-8 py-6" x-data="{ showMap: false }">
            <!-- Header Area for Results -->
            <div class="flex justify-between items-start mb-6">
                <div>
                    <h1 class="text-2xl font-bold text-[#1A1F2B]">
                        @if ($query)
                            Stay in {{ $query }}
                        @else
                            Semua Properti
                        @endif
                    </h1>
                    <p class="text-sm text-gray-500">{{ $properti->total() }} properti tersedia</p>
                    @if ($checkIn || $maxTamu)
                    <div class="flex gap-2 mt-2">
                        @if ($checkIn)
                        <span class="text-[10px] bg-orange-50 text-brand-orange px-2 py-1 rounded-full font-medium">{{ \Carbon\Carbon::parse($checkIn)->format('d M') }}{{ $checkOut ? ' - ' . \Carbon\Carbon::parse($checkOut)->format('d M Y') : '' }}</span>
                        @endif
                        @if ($maxTamu)
                        <span class="text-[10px] bg-blue-50 text-blue-600 px-2 py-1 rounded-full font-medium">{{ $maxTamu }} Tamu</span>
                        @endif
                    </div>
                    @endif
                </div>
                <button @click="showMap = !showMap" class="w-10 h-10 rounded-full flex items-center justify-center transition-colors"
                    :class="showMap ? 'bg-brand-orange text-white' : 'bg-[#F0F4F8] text-[#4A5568] hover:bg-gray-200'">
                    <i class="fa-solid fa-map"></i>
                </button>
            </div>

            <!-- Filters -->
            <div class="flex gap-2 overflow-x-auto pb-2 mb-6" style="scrollbar-width: none;">
                <a href="/filter" class="flex items-center gap-2 bg-brand-orange-hover text-white px-5 py-2.5 rounded-full text-sm font-bold shrink-0 shadow-sm hover:bg-[#8A3F14] transition-all">
                    <i class="fa-solid fa-sliders"></i> Filters
                </a>
                <a href="/search?{{ http_build_query(array_merge(request()->except('page'), ['sort' => 'price'])) }}" class="flex items-center gap-2 bg-white border border-gray-200 text-gray-700 px-5 py-2.5 rounded-full text-sm font-medium shrink-0 hover:border-brand-orange hover:text-brand-orange transition-all @if(request('sort') === 'price') border-brand-orange text-brand-orange bg-orange-50 @endif">
                    <i class="fa-solid fa-arrow-up-wide-short text-xs"></i> Price
                </a>

                {{-- Type Dropdown --}}
                <div class="relative" x-data="{ openType: false }">
                    <button @click="openType = !openType" class="flex items-center gap-2 bg-white border px-5 py-2.5 rounded-full text-sm font-medium shrink-0 transition-all whitespace-nowrap @if($tipeProperti) border-brand-orange text-brand-orange bg-orange-50 @else border-gray-200 text-gray-700 hover:border-brand-orange hover:text-brand-orange @endif">
                        <i class="fa-solid fa-building text-xs"></i> {{ $tipeProperti ? ucfirst($tipeProperti) : 'Type' }}
                        <i class="fa-solid fa-chevron-down text-[10px]" :class="openType ? 'rotate-180' : ''"></i>
                    </button>
                    <div x-show="openType" @click.outside="openType = false" class="absolute left-0 top-full mt-1 bg-white border border-gray-200 rounded-xl shadow-lg z-10 py-1 min-w-[160px]">
                        @foreach (['hotel','villa','apartemen','kost','rumah','guesthouse'] as $t)
                        <a href="/search?{{ http_build_query(array_merge(request()->except(['page', 'tipe_properti']), ['tipe_properti' => $tipeProperti === $t ? '' : $t])) }}" 
                            class="block px-4 py-2.5 text-sm transition-colors @if($tipeProperti === $t) bg-brand-orange/10 text-brand-orange-hover font-bold @else text-gray-700 hover:bg-gray-50 @endif">
                            {{ ucfirst($t) }}
                            @if($tipeProperti === $t) <i class="fa-solid fa-check ml-2 text-brand-orange"></i> @endif
                        </a>
                        @endforeach
                    </div>
                </div>

                <a href="/search?{{ http_build_query(array_merge(request()->except('page'), ['sort' => 'rating'])) }}" class="flex items-center gap-2 bg-white border border-gray-200 text-gray-700 px-5 py-2.5 rounded-full text-sm font-medium shrink-0 hover:border-brand-orange hover:text-brand-orange transition-all @if(request('sort') === 'rating') border-brand-orange text-brand-orange bg-orange-50 @endif">
                    <i class="fa-solid fa-star text-xs"></i> Rating
                </a>
            </div>

            {{-- Active Filters Display --}}
            @if ($checkIn || $maxTamu || $tipeProperti || request('sort'))
            <div class="flex flex-wrap gap-2 mb-4">
                @if ($checkIn)
                <span class="inline-flex items-center gap-1 text-xs bg-orange-50 text-brand-orange-hover px-3 py-1.5 rounded-full font-medium border border-orange-200">
                    <i class="fa-solid fa-calendar"></i> {{ \Carbon\Carbon::parse($checkIn)->format('d M') }}{{ $checkOut ? ' - ' . \Carbon\Carbon::parse($checkOut)->format('d M Y') : '' }}
                    <a href="/search?{{ http_build_query(request()->except(['check_in', 'check_out', 'page'])) }}" class="ml-1 hover:text-red-500"><i class="fa-solid fa-xmark"></i></a>
                </span>
                @endif
                @if ($maxTamu)
                <span class="inline-flex items-center gap-1 text-xs bg-blue-50 text-blue-600 px-3 py-1.5 rounded-full font-medium border border-blue-200">
                    <i class="fa-solid fa-user"></i> {{ $maxTamu }} Tamu
                    <a href="/search?{{ http_build_query(request()->except(['max_tamu', 'page'])) }}" class="ml-1 hover:text-red-500"><i class="fa-solid fa-xmark"></i></a>
                </span>
                @endif
                @if ($tipeProperti)
                <span class="inline-flex items-center gap-1 text-xs bg-purple-50 text-purple-600 px-3 py-1.5 rounded-full font-medium border border-purple-200">
                    <i class="fa-solid fa-building"></i> {{ ucfirst($tipeProperti) }}
                    <a href="/search?{{ http_build_query(request()->except(['tipe_properti', 'page'])) }}" class="ml-1 hover:text-red-500"><i class="fa-solid fa-xmark"></i></a>
                </span>
                @endif
                @if (request('sort'))
                <span class="inline-flex items-center gap-1 text-xs bg-gray-100 text-gray-600 px-3 py-1.5 rounded-full font-medium border border-gray-200">
                    <i class="fa-solid fa-arrow-up-wide-short"></i> {{ ucfirst(request('sort')) }}
                    <a href="/search?{{ http_build_query(request()->except(['sort', 'page'])) }}" class="ml-1 hover:text-red-500"><i class="fa-solid fa-xmark"></i></a>
                </span>
                @endif
            </div>
            @endif

            {{-- Map View --}}
            <div x-show="showMap" x-transition.opacity.duration.300ms style="display: none;">
                <div id="search-map" class="w-full h-[400px] rounded-2xl overflow-hidden border border-gray-200 mb-6"></div>
            </div>

            <!-- Properties List -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse ($properti as $item)
                <div class="bg-white rounded-2xl overflow-hidden shadow-sm border border-gray-100 hover:shadow-md transition-shadow">
                    <a href="/detail/{{ $item->id_properti }}" class="relative h-56 block">
                        <img src="{{ $item->gambar->first()->url ?? 'https://images.unsplash.com/photo-1494526585095-c41746248156?q=80&w=1200&auto=format&fit=crop' }}" class="w-full h-full object-cover">
                        <div class="absolute top-3 left-3 bg-white px-2 py-1 rounded-md flex items-center gap-1 text-[10px] font-bold text-gray-800 shadow-sm uppercase">
                            <i class="fa-solid fa-circle-check text-brand-orange"></i> Verified Host
                        </div>
                        <button onclick="event.preventDefault(); event.stopPropagation();" class="absolute top-3 right-3 w-8 h-8 rounded-full bg-black/20 flex items-center justify-center text-white backdrop-blur-sm transition-colors hover:bg-black/40">
                            <i class="fa-regular fa-heart"></i>
                        </button>
                    </a>
                    <div class="p-4">
                        <div class="flex justify-between items-start mb-1">
                            <h3 class="text-lg font-bold text-gray-900 line-clamp-1">{{ $item->nama_properti }}</h3>
                            <div class="flex items-center gap-1 bg-brand-green/10 text-brand-green px-2 py-0.5 rounded text-xs font-bold shrink-0">
                                <i class="fa-solid fa-star text-[10px]"></i> {{ number_format($item->review_avg_rating ?? 0, 1) }}
                            </div>
                        </div>
                        <p class="text-sm text-gray-500 mb-3">{{ $item->kota }}, {{ $item->provinsi }}</p>
                        <div class="flex flex-wrap gap-x-4 gap-y-2 text-xs text-gray-600 mb-4">
                            <span class="flex items-center gap-1"><i class="fa-solid fa-bed"></i> {{ $item->jumlah_ruang }} Kamar</span>
                            <span class="flex items-center gap-1"><i class="fa-solid fa-user-group"></i> {{ $item->max_tamu }} Tamu</span>
                        </div>
                        <div class="flex justify-between items-center mt-2 border-t pt-4">
                            <div>
                                <span class="text-xl font-bold text-brand-orange">Rp {{ number_format($item->harga_per_malam, 0, ',', '.') }}</span>
                                <span class="text-xs text-gray-500">/ malam</span>
                            </div>
                            <a href="/detail/{{ $item->id_properti }}" class="bg-brand-orange text-white px-5 py-2 rounded-lg text-sm font-semibold hover:bg-brand-orange-hover transition-colors inline-block text-center">
                                Book Now
                            </a>
                        </div>
                    </div>
                </div>
                @empty
                <div class="col-span-full text-center py-12 text-gray-500">
                    Tidak ada properti ditemukan.
                </div>
                @endforelse
            </div>

            {{ $properti->links() }} 
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('search-input');
            const defaultContent = document.getElementById('default-content');
            const searchResults = document.getElementById('search-results');

            @if ($query)
                defaultContent.classList.add('hidden');
                searchResults.classList.remove('hidden');
            @else
                defaultContent.classList.remove('hidden');
                searchResults.classList.add('hidden');
            @endif

            searchInput.addEventListener('keydown', function(e) {
                if (e.key === 'Enter' && e.target.value.trim()) {
                    localStorage.setItem('nginup_kota', e.target.value.trim());
                    window.location.href = '/search?kota=' + encodeURIComponent(e.target.value.trim());
                }
            });

            // Map for search results
            var searchMap = null;
            var markersLayer = null;
            document.addEventListener('click', function(e) {
                var mapBtn = e.target.closest('[x-data]') ? null : null;
            });
            var searchMapEl = document.getElementById('search-map');
            if (searchMapEl) {
                var observer = new MutationObserver(function() {
                    if (searchMapEl.style.display !== 'none' && !searchMap) {
                        searchMap = L.map('search-map').setView([-2.5, 118], 5);
                        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                            attribution: '&copy; OpenStreetMap'
                        }).addTo(searchMap);
                        markersLayer = L.layerGroup().addTo(searchMap);
                        @foreach ($properti as $item)
                            @if ($item->latitude && $item->longitude)
                            L.marker([{{ $item->latitude }}, {{ $item->longitude }}]).addTo(markersLayer)
                                .bindPopup('<strong>{{ addslashes($item->nama_properti) }}</strong><br>Rp {{ number_format($item->harga_per_malam, 0, ',', '.') }}/malam<br><a href="/detail/{{ $item->id_properti }}" style="color:#D2691E;font-weight:bold;">Lihat Detail</a>');
                            @endif
                        @endforeach
                        if (markersLayer.getLayers().length > 0) {
                            var group = L.featureGroup(markersLayer.getLayers());
                            searchMap.fitBounds(group.getBounds().pad(0.1));
                        }
                        setTimeout(function() { searchMap.invalidateSize(); }, 100);
                    }
                });
                observer.observe(searchMapEl, { attributes: true, attributeFilter: ['style'] });
            }
        });
    </script>


</div>

</body>
</html>
