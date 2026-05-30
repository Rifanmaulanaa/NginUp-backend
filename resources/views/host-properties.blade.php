@extends('layouts.host', ['activeTab' => 'properties'])

@section('title', 'Properties - Host NginUp')
@section('page_title', 'Properties')

@section('content')

<div class="w-full max-w-7xl mx-auto pt-6" x-data="propertiesApp()">

    {{-- ========================================== --}}
    {{-- VIEW: LIST (PROPERTY PORTFOLIO)            --}}
    {{-- ========================================== --}}
    <div x-show="view === 'list'" x-transition.opacity.duration.300ms>
        
        @if (session('success'))
            <div class="mb-6 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-xl text-sm">{{ session('success') }}</div>
        @endif
        @if (session('error'))
            <div class="mb-6 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl text-sm">{{ session('error') }}</div>
        @endif

        {{-- Header --}}
        <div class="mb-6 flex flex-col md:flex-row md:justify-between md:items-end gap-4">
            <div>
                <h1 class="text-2xl md:text-3xl font-extrabold text-gray-900 mb-2">Property Portfolio</h1>
                <p class="text-sm text-gray-500">Manage your listings, track performance, and maximize your occupancy.</p>
            </div>
            <button class="px-4 py-2 bg-white border border-gray-200 text-gray-700 text-sm font-bold rounded-full flex items-center gap-2 hover:bg-gray-50 shadow-sm transition-colors md:w-auto w-full justify-center">
                <i class="fa-solid fa-filter"></i> Filter
            </button>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 pb-20 relative">
            
            @forelse ($properti as $item)
            <div class="bg-white rounded-[24px] overflow-hidden border border-gray-100 shadow-sm group">
                <div class="relative h-48 md:h-56">
                    <img src="{{ $item->gambar->first()->url ?? 'https://images.unsplash.com/photo-1600596542815-ffad4c1539a9?q=80&w=800&auto=format&fit=crop' }}" class="w-full h-full object-cover">
                    <div class="absolute top-4 left-4 bg-white/90 backdrop-blur-sm text-brand-orange text-[10px] font-bold px-3 py-1 rounded-full shadow-sm">
                        {{ $item->status === 'active' ? 'Active' : 'Inactive' }}
                    </div>
                    <div class="absolute bottom-4 right-4 bg-brand-orange text-white text-sm font-bold px-3 py-1.5 rounded-lg shadow-md">
                        Rp {{ number_format($item->harga_per_malam, 0, ',', '.') }}/night
                    </div>
                </div>
                <div class="p-5">
                    <h3 class="text-lg font-bold text-gray-900">{{ $item->nama_properti }}</h3>
                    <p class="text-xs text-gray-500 flex items-center gap-1 mb-4 mt-1"><i class="fa-solid fa-location-dot text-gray-400"></i> {{ $item->kota }}, {{ $item->provinsi }}</p>
                    
                    <div class="space-y-5">
                        <div class="flex gap-6">
                            <div>
                                <div class="text-[11px] text-gray-400 font-bold uppercase tracking-wider mb-1">Tipe</div>
                                <div class="text-base font-bold text-gray-900">{{ ucfirst($item->tipe_properti) }}</div>
                            </div>
                            <div>
                                <div class="text-[11px] text-gray-400 font-bold uppercase tracking-wider mb-1">Kamar</div>
                                <div class="text-base font-bold text-gray-900">{{ $item->jumlah_ruang }}</div>
                            </div>
                            <div>
                                <div class="text-[11px] text-gray-400 font-bold uppercase tracking-wider mb-1">Rating</div>
                                <div class="text-base font-bold text-gray-900 flex items-center gap-1">{{ number_format($item->review_avg_rating ?? 0, 1) }} <i class="fa-solid fa-star text-[#1c333a] text-[11px]"></i></div>
                            </div>
                        </div>
                        <div class="flex gap-2">
                            <button @click="showDetail(@json($item->id_properti))" class="px-5 py-2.5 bg-white border border-gray-200 text-gray-600 rounded-xl text-sm font-bold hover:bg-gray-50 transition-colors">View</button>
                            <button @click="showEdit(@json($item->id_properti))" class="px-5 py-2.5 bg-white border border-gray-200 text-gray-600 rounded-xl text-sm font-bold hover:bg-gray-50 transition-colors">Edit</button>
                            <a href="/host/properties/{{ $item->id_properti }}/rooms" class="px-5 py-2.5 bg-white border border-gray-200 text-gray-600 rounded-xl text-sm font-bold hover:bg-brand-orange hover:text-white hover:border-brand-orange transition-colors">Rooms</a>
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-span-full text-center py-16">
                <div class="w-20 h-20 mx-auto bg-orange-50 rounded-full flex items-center justify-center mb-4">
                    <i class="fa-solid fa-house-circle-exclamation text-3xl text-brand-orange"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-2">Belum Ada Properti</h3>
                <p class="text-sm text-gray-500 mb-6">Mulai tambahkan properti pertama Anda untuk mulai menerima pemesanan.</p>
                <a href="/host/add-new" class="inline-block bg-brand-orange hover:bg-brand-orange-hover text-white px-8 py-3 rounded-xl font-bold transition-colors shadow-md">
                    <i class="fa-solid fa-plus mr-2"></i> Tambah Properti
                </a>
            </div>
            @endforelse

            {{-- Floating Action Button --}}
            <a href="/host/add-new" class="fixed bottom-20 md:bottom-10 right-6 w-14 h-14 bg-brand-orange text-white rounded-full flex items-center justify-center text-2xl shadow-lg hover:scale-105 transition-transform">
                <i class="fa-solid fa-plus"></i>
            </a>

        </div>
    </div>

    {{-- ========================================== --}}
    {{-- VIEW: DETAIL                               --}}
    {{-- ========================================== --}}
    <div x-show="view === 'detail'" x-transition.opacity.duration.300ms style="display: none;">
        <div class="w-full">
            {{-- Header --}}
            <div class="flex items-center justify-between mb-6">
                <div class="flex items-center gap-3">
                    <button @click="view = 'list'; scrollToTop()" class="w-8 h-8 rounded-full bg-white shadow-sm border border-gray-100 flex items-center justify-center text-brand-orange hover:bg-orange-50 transition-colors">
                        <i class="fa-solid fa-arrow-left"></i>
                    </button>
                    <h1 class="text-lg font-extrabold text-brand-orange">Detail Property</h1>
                </div>
                <div class="w-8 h-8 rounded-full bg-orange-100 flex items-center justify-center text-brand-orange font-bold border-2 border-white shadow-sm md:hidden">
                    <i class="fa-solid fa-house"></i>
                </div>
            </div>

            <div class="bg-white rounded-[24px] border border-gray-100 p-5 lg:p-8 shadow-sm mb-6">
            <div class="flex items-center gap-2 mb-1">
                <h2 class="text-xl font-extrabold text-gray-900" x-text="selected.nama_properti"></h2>
                <span class="px-2 py-0.5 text-[9px] font-bold rounded-full"
                      :class="selected.verified_status === 'verified' ? 'bg-green-100 text-green-700' : 'bg-amber-100 text-amber-700'"
                      x-text="selected.verified_status === 'verified' ? 'Verified' : (selected.verified_status === 'pending' ? 'Pending' : 'Rejected')"></span>
            </div>
            <p class="text-xs text-gray-500 flex items-center gap-1 mb-5"><i class="fa-solid fa-location-dot text-gray-400"></i> <span x-text="selected.kota + ', ' + selected.provinsi"></span></p>

            <div class="flex gap-3 mb-6">
                <button class="flex-1 py-3 bg-[#1c333a] text-white rounded-xl text-xs font-bold hover:bg-[#15272c] transition-colors flex items-center justify-center gap-2">
                    <i class="fa-solid fa-pause"></i> Pause Listing
                </button>
                <button @click="showEdit(selected.id_properti)" class="flex-1 py-3 bg-brand-orange text-white rounded-xl text-xs font-bold hover:bg-brand-orange-hover transition-colors flex items-center justify-center gap-2">
                    <i class="fa-solid fa-pen"></i> Edit Listing
                </button>
            </div>

            {{-- Stats --}}
            <div class="grid grid-cols-2 md:grid-cols-4 gap-3 mb-6">
                <div class="border border-gray-100 rounded-2xl p-4 flex items-center gap-4">
                    <div class="w-10 h-10 rounded-xl bg-blue-50 text-blue-500 flex items-center justify-center text-xl"><i class="fa-solid fa-bed"></i></div>
                    <div>
                        <div class="text-[10px] text-gray-500 font-bold mb-0.5">Max Guests</div>
                        <div class="text-xl font-extrabold text-gray-900" x-text="selected.max_tamu"></div>
                    </div>
                </div>
                <div class="border border-gray-100 rounded-2xl p-4 flex items-center gap-4">
                    <div class="w-10 h-10 rounded-xl bg-green-50 text-green-600 flex items-center justify-center text-xl"><i class="fa-solid fa-door-open"></i></div>
                    <div>
                        <div class="text-[10px] text-gray-500 font-bold mb-0.5">Total Kamar</div>
                        <div class="text-xl font-extrabold text-gray-900" x-text="selected.jumlah_ruang"></div>
                    </div>
                </div>
                <div class="border border-gray-100 rounded-2xl p-4 flex items-center gap-4">
                    <div class="w-10 h-10 rounded-xl bg-blue-50 text-blue-500 flex items-center justify-center text-xl"><i class="fa-solid fa-star"></i></div>
                    <div>
                        <div class="text-[10px] text-gray-500 font-bold mb-0.5">Rating</div>
                        <div class="text-xl font-extrabold text-gray-900 flex items-end gap-1" x-text="selected.review_avg_rating"></div>
                    </div>
                </div>
                <div class="border border-gray-100 rounded-2xl p-4 flex items-center gap-4">
                    <div class="w-10 h-10 rounded-xl bg-orange-50 text-brand-orange flex items-center justify-center text-xl"><i class="fa-solid fa-wallet"></i></div>
                    <div>
                        <div class="text-[10px] text-gray-500 font-bold mb-0.5">Price/Night</div>
                        <div class="text-xl font-extrabold text-gray-900" x-text="'Rp ' + Number(selected.harga_per_malam).toLocaleString('id-ID')"></div>
                    </div>
                </div>
            </div>

            {{-- Photos --}}
            <div class="rounded-[20px] overflow-hidden mb-6 relative group">
                <img :src="selected.gambar?.[0]?.url ?? 'https://images.unsplash.com/photo-1600596542815-ffad4c1539a9?q=80&w=800&auto=format&fit=crop'" class="w-full h-48 object-cover">
                <div class="absolute inset-0 bg-black/20 group-hover:bg-black/10 transition-colors"></div>
                <div class="absolute bottom-3 right-3 bg-white/90 backdrop-blur-sm text-gray-800 text-[10px] font-bold px-3 py-1.5 rounded-lg shadow-sm" x-text="'+ ' + (selected.gambar?.length ?? 0) + ' Photos'">
                </div>
            </div>

            {{-- Description --}}
            <div class="mb-6">
                <h3 class="text-sm font-extrabold text-gray-900 mb-2">Description</h3>
                <p class="text-xs text-gray-600 leading-relaxed" x-text="selected.deskripsi || 'No description provided.'"></p>
            </div>

            {{-- Amenities --}}
            <div class="mb-6" x-data="{ amenities: [] }">
                <h3 class="text-sm font-extrabold text-gray-900 mb-3">Amenities</h3>
                <div class="grid grid-cols-2 gap-3 text-xs text-gray-600 font-medium">
                    <template x-for="item in (selected.fasilitas || [])" :key="item.id_fasilitas">
                        <div class="flex items-center gap-2">
                            <i :class="'fa-solid fa-' + (item.ikon_fasilitas || 'check') + ' w-4 text-brand-orange'"></i>
                            <span x-text="item.nama_fasilitas"></span>
                        </div>
                    </template>
                    <p x-show="!(selected.fasilitas?.length)" class="text-gray-400 col-span-full">No amenities listed.</p>
                </div>
            </div>

            {{-- Rooms --}}
            <div class="mb-6">
                <div class="flex items-center justify-between mb-3">
                    <h3 class="text-sm font-extrabold text-gray-900">Rooms</h3>
                    <a :href="'/host/properties/' + selected.id_properti + '/rooms'" class="text-[10px] font-bold text-brand-orange hover:underline flex items-center gap-1">
                        <i class="fa-solid fa-pen"></i> Manage
                    </a>
                </div>
                <template x-if="selected.kamar?.length">
                    <div class="space-y-2">
                        <template x-for="room in selected.kamar" :key="room.id_kamar">
                            <div class="flex items-center justify-between border border-gray-100 rounded-xl px-4 py-3">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-lg bg-orange-50 text-brand-orange flex items-center justify-center">
                                        <i class="fa-solid fa-door-open text-xs"></i>
                                    </div>
                                    <div>
                                        <div class="text-xs font-bold text-gray-900" x-text="room.nama_kamar"></div>
                                        <div class="text-[9px] text-gray-500">
                                            <span x-text="room.kapasitas + ' guests'"></span>
                                            <span x-show="room.jumlah_tempat_tidur"> · <span x-text="room.jumlah_tempat_tidur + ' ' + room.tipe_tempat_tidur"></span></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="flex items-center gap-2">
                                    <span x-show="room.harga_per_malam" class="text-[10px] font-bold text-brand-orange" x-text="'Rp ' + Number(room.harga_per_malam).toLocaleString('id-ID')"></span>
                                    <span class="text-[9px] font-bold px-2 py-0.5 rounded-full"
                                          :class="room.status === 'available' ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700'"
                                          x-text="room.status === 'available' ? 'Available' : 'Maintenance'"></span>
                                </div>
                            </div>
                        </template>
                    </div>
                </template>
                <p x-show="!selected.kamar?.length" class="text-xs text-gray-400 italic">No rooms configured.</p>
            </div>

            <hr class="border-gray-100 my-6">

            {{-- Base Rate --}}
            <div class="flex justify-between items-center mb-6">
                <div>
                    <div class="text-[10px] text-gray-500 font-bold mb-1">Base Rate</div>
                    <div class="flex items-end gap-1">
                        <span class="text-3xl font-extrabold text-brand-orange" x-text="'Rp ' + Number(selected.harga_per_malam).toLocaleString('id-ID')"></span>
                        <span class="text-[10px] text-gray-500 font-bold mb-1.5">/night</span>
                    </div>
                </div>
            </div>

            {{-- Map --}}
            <div class="rounded-xl overflow-hidden relative h-40 border border-gray-100 group">
                <img src="https://images.unsplash.com/photo-1524661135-423995f22d0b?q=80&w=600&auto=format&fit=crop" class="w-full h-full object-cover opacity-80 group-hover:opacity-100 transition-opacity grayscale">
                <i class="fa-solid fa-location-dot absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 text-3xl text-brand-orange drop-shadow-md"></i>
                <div class="absolute bottom-0 w-full bg-white p-3">
                    <div class="text-xs font-bold text-gray-900" x-text="selected.alamat || selected.nama_properti"></div>
                    <div class="text-[9px] text-gray-500" x-text="selected.kota + ', ' + selected.provinsi"></div>
                </div>
            </div>

        </div>
        </div>
    </div>

    {{-- ========================================== --}}
    {{-- VIEW: EDIT                                 --}}
    {{-- ========================================== --}}
    <div x-show="view === 'edit'" x-transition.opacity.duration.300ms style="display: none;">
        <div class="w-full">
            {{-- Header --}}
            <div class="flex items-center justify-between mb-6">
                <div class="flex items-center gap-3">
                    <button @click="view = 'list'; scrollToTop()" class="w-8 h-8 rounded-full bg-white shadow-sm border border-gray-100 flex items-center justify-center text-brand-orange hover:bg-orange-50 transition-colors">
                        <i class="fa-solid fa-arrow-left"></i>
                    </button>
                    <h1 class="text-lg font-extrabold text-brand-orange">Edit Listing</h1>
                </div>
            </div>

            <form method="POST" :action="'/host/properties/' + selected.id_properti" enctype="multipart/form-data">
                @csrf
                @method('PUT')

            <div class="bg-white rounded-[24px] border border-gray-100 p-6 lg:p-8 shadow-sm mb-6 space-y-6">
            
            {{-- General Info --}}
            <div>
                <h3 class="text-sm font-extrabold text-gray-900 mb-4">General Information</h3>
                
                <div class="space-y-4">
                    <div>
                        <label class="text-[10px] font-bold text-blue-500 block mb-1">Property Title</label>
                        <input type="text" name="nama_properti" :value="selected.nama_properti" class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-brand-orange">
                    </div>
                    <div>
                        <label class="text-[10px] font-bold text-blue-500 block mb-1">Description</label>
                        <textarea name="deskripsi" rows="4" class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-brand-orange leading-relaxed text-gray-600" x-model="selected.deskripsi"></textarea>
                    </div>
                    <div>
                        <label class="text-[10px] font-bold text-blue-500 block mb-1">Nightly Rate (Rp)</label>
                        <div class="relative">
                            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-500 font-bold">Rp</span>
                            <input type="number" name="harga_per_malam" :value="selected.harga_per_malam" class="w-full border border-gray-200 rounded-xl pl-10 pr-4 py-3 text-sm focus:outline-none focus:border-brand-orange font-bold">
                        </div>
                    </div>
                    <div>
                        <label class="text-[10px] font-bold text-blue-500 block mb-1">Property Type</label>
                        <div class="relative">
                            <select name="tipe_properti" class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-brand-orange appearance-none bg-white">
                                <option value="hotel" :selected="selected.tipe_properti === 'hotel'">Hotel</option>
                                <option value="villa" :selected="selected.tipe_properti === 'villa'">Villa</option>
                                <option value="apartemen" :selected="selected.tipe_properti === 'apartemen'">Apartemen</option>
                                <option value="rumah" :selected="selected.tipe_properti === 'rumah'">Rumah</option>
                                <option value="kost" :selected="selected.tipe_properti === 'kost'">Kost</option>
                                <option value="guesthouse" :selected="selected.tipe_properti === 'guesthouse'">Guesthouse</option>
                            </select>
                            <i class="fa-solid fa-chevron-down absolute right-4 top-1/2 -translate-y-1/2 text-[10px] text-gray-400 pointer-events-none"></i>
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="text-[10px] font-bold text-blue-500 block mb-1">Max Guests</label>
                            <input type="number" name="max_tamu" :value="selected.max_tamu" min="1" class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-brand-orange font-bold">
                        </div>
                        <div>
                            <label class="text-[10px] font-bold text-blue-500 block mb-1">Bedrooms</label>
                            <input type="number" name="jumlah_ruang" :value="selected.jumlah_ruang" min="0" class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-brand-orange font-bold">
                        </div>
                    </div>
                </div>
            </div>

            <hr class="border-gray-100">

            {{-- Location --}}
            <div>
                <h3 class="text-sm font-extrabold text-gray-900 mb-4">Location</h3>
                <div class="space-y-4">
                    <div>
                        <label class="text-[10px] font-bold text-blue-500 block mb-1">Address</label>
                        <input type="text" name="alamat" :value="selected.alamat" class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-brand-orange">
                    </div>
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="text-[10px] font-bold text-blue-500 block mb-1">City</label>
                            <input type="text" name="kota" :value="selected.kota" class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-brand-orange">
                        </div>
                        <div>
                            <label class="text-[10px] font-bold text-blue-500 block mb-1">Province</label>
                            <input type="text" name="provinsi" :value="selected.provinsi" class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-brand-orange">
                        </div>
                    </div>
                </div>
            </div>

            <hr class="border-gray-100">

            {{-- Photos --}}
            <div>
                <div class="flex justify-between items-center mb-3">
                    <div>
                        <h3 class="text-sm font-extrabold text-gray-900">Property Photos</h3>
                        <p class="text-[9px] text-gray-400">High-definition images increase booking rate by 40%</p>
                    </div>
                </div>
                
                <div class="grid grid-cols-2 gap-2">
                    <template x-for="(img, i) in (selected.gambar || [])" :key="i">
                        <div class="relative rounded-xl overflow-hidden" :class="i === 0 ? 'col-span-2 h-32' : 'h-24'">
                            <img :src="img.url ?? 'https://images.unsplash.com/photo-1600596542815-ffad4c1539a9?q=80&w=800&auto=format&fit=crop'" class="w-full h-full object-cover">
                            <div x-show="i === 0" class="absolute top-2 left-2 bg-brand-orange text-white text-[8px] font-bold px-2 py-0.5 rounded-full">Cover Photo</div>
                        </div>
                    </template>
                    <div class="rounded-xl border-2 border-dashed border-blue-200 bg-blue-50 flex flex-col items-center justify-center text-blue-500 h-24 cursor-pointer hover:bg-blue-100 transition-colors relative">
                        <i class="fa-solid fa-plus mb-1"></i>
                        <span class="text-[10px] font-bold">Add Photo</span>
                        <input type="file" name="gambar[]" multiple accept="image/jpeg,image/png" class="absolute inset-0 opacity-0 cursor-pointer">
                    </div>
                </div>
            </div>

            {{-- Submit --}}
            <div class="flex gap-3 pt-4">
                <button type="button" @click="view = 'list'; scrollToTop()" class="w-1/3 py-3 border border-gray-200 rounded-xl text-xs font-bold text-gray-600 hover:bg-gray-50 transition-colors">
                    Cancel
                </button>
                <button type="submit" class="w-2/3 py-3 bg-brand-orange text-white rounded-xl text-xs font-bold shadow-md shadow-brand-orange/20 hover:bg-brand-orange-hover transition-colors">
                    Save Changes
                </button>
            </div>

        </div>
        </form>
        </div>
    </div>

</div>

@push('scripts')
<script>
function propertiesApp() {
    return {
        view: 'list',
        selected: {},
        properties: {!! $propertiJson->toJson() !!},
        scrollToTop() {
            window.scrollTo({top:0, behavior:'smooth'});
            const mainScroll = document.querySelector('main');
            if(mainScroll) mainScroll.scrollTo({top:0, behavior:'smooth'});
        },
        showDetail(id) {
            this.selected = this.properties.find(p => p.id_properti === id) || {};
            this.view = 'detail';
            this.scrollToTop();
        },
        showEdit(id) {
            this.selected = this.properties.find(p => p.id_properti === id) || {};
            this.view = 'edit';
            this.scrollToTop();
        },
    };
}
</script>
@endpush

@endsection
