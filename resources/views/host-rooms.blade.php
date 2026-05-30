@extends('layouts.host', ['activeTab' => 'properties'])

@section('title', 'Manage Rooms - Host NginUp')

@section('content')

<div class="max-w-4xl mx-auto pt-6">

    {{-- Header --}}
    <div class="flex items-center justify-between mb-6">
        <div class="flex items-center gap-3">
            <a href="/host/properties" class="w-8 h-8 rounded-full bg-white shadow-sm border border-gray-100 flex items-center justify-center text-brand-orange hover:bg-orange-50 transition-colors">
                <i class="fa-solid fa-arrow-left"></i>
            </a>
            <div>
                <h1 class="text-xl font-extrabold text-brand-orange">Manage Rooms</h1>
                <p class="text-xs text-gray-500">{{ $properti->nama_properti }}</p>
            </div>
        </div>
        <span class="px-3 py-1 bg-gray-100 text-gray-600 text-xs font-bold rounded-full">{{ $properti->kamar->count() }} kamar</span>
    </div>

    @if (session('success'))
    <div class="mb-6 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-xl text-sm">{{ session('success') }}</div>
    @endif
    @if (session('error'))
    <div class="mb-6 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl text-sm">{{ session('error') }}</div>
    @endif

    {{-- Existing Rooms --}}
    @forelse ($properti->kamar as $kamar)
    <div class="bg-white border border-gray-100 rounded-[24px] p-5 shadow-sm mb-4 flex items-center justify-between group hover:border-brand-orange/30 transition-colors">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-orange-50 text-brand-orange flex items-center justify-center text-lg">
                <i class="fa-solid fa-door-open"></i>
            </div>
            <div>
                <h3 class="text-sm font-bold text-gray-900">{{ $kamar->nama_kamar }}</h3>
                <div class="flex items-center gap-3 text-[10px] text-gray-500 mt-1">
                    <span><i class="fa-solid fa-user mr-1"></i>{{ $kamar->kapasitas }} guests</span>
                    <span><i class="fa-solid fa-bed mr-1"></i>{{ $kamar->jumlah_tempat_tidur }} {{ ucfirst($kamar->tipe_tempat_tidur) }}</span>
                    @if ($kamar->harga_per_malam)
                    <span class="text-brand-orange font-bold">Rp {{ number_format($kamar->harga_per_malam, 0, ',', '.') }}/night</span>
                    @endif
                </div>
            </div>
        </div>
        <div class="flex items-center gap-2">
            <span class="text-[10px] font-bold px-2 py-1 rounded-full {{ $kamar->status === 'available' ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700' }}">
                {{ $kamar->status === 'available' ? 'Available' : 'Maintenance' }}
            </span>
            <form method="POST" action="/host/properties/{{ $properti->id_properti }}/rooms/{{ $kamar->id_kamar }}" onsubmit="return confirm('Hapus kamar ini?')">
                @csrf
                @method('DELETE')
                <button class="w-8 h-8 rounded-full bg-red-50 text-red-500 hover:bg-red-100 transition-colors flex items-center justify-center">
                    <i class="fa-solid fa-trash-can text-[10px]"></i>
                </button>
            </form>
        </div>
    </div>
    @empty
    <div class="bg-white border border-gray-100 rounded-[24px] p-10 shadow-sm text-center mb-4">
        <div class="w-16 h-16 mx-auto bg-gray-50 rounded-full flex items-center justify-center mb-4">
            <i class="fa-solid fa-door-open text-2xl text-gray-300"></i>
        </div>
        <h3 class="text-sm font-bold text-gray-900 mb-1">Belum Ada Kamar</h3>
        <p class="text-xs text-gray-500">Tambahkan kamar untuk properti ini.</p>
    </div>
    @endforelse

    {{-- Add Room Form --}}
    <div class="bg-white border border-gray-100 rounded-[24px] p-6 shadow-sm">
        <h3 class="text-sm font-extrabold text-gray-900 mb-4">Tambah Kamar Baru</h3>

        <form method="POST" action="/host/properties/{{ $properti->id_properti }}/rooms">
            @csrf

            <div class="space-y-4 mb-5">
                <div>
                    <label class="text-[10px] font-bold text-blue-500 block mb-1">Nama Kamar <span class="text-red-500">*</span></label>
                    <input type="text" name="nama_kamar" required placeholder="Contoh: Deluxe 101, Standard Room A" class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-brand-orange">
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="text-[10px] font-bold text-blue-500 block mb-1">Kapasitas Tamu <span class="text-red-500">*</span></label>
                        <input type="number" name="kapasitas" required value="2" min="1" class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-brand-orange">
                    </div>
                    <div>
                        <label class="text-[10px] font-bold text-blue-500 block mb-1">Jumlah Tempat Tidur <span class="text-red-500">*</span></label>
                        <input type="number" name="jumlah_tempat_tidur" required value="1" min="1" class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-brand-orange">
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="text-[10px] font-bold text-blue-500 block mb-1">Tipe Tempat Tidur</label>
                        <select name="tipe_tempat_tidur" class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-brand-orange appearance-none bg-white">
                            <option value="single">Single</option>
                            <option value="double">Double</option>
                            <option value="queen">Queen</option>
                            <option value="king">King</option>
                            <option value="twin">Twin</option>
                            <option value="bunk">Bunk</option>
                        </select>
                    </div>
                    <div>
                        <label class="text-[10px] font-bold text-blue-500 block mb-1">Harga/Malam (opsional)</label>
                        <div class="relative">
                            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-500 font-bold text-xs">Rp</span>
                            <input type="number" name="harga_per_malam" placeholder="Kosongi untuk pakai harga properti" min="0" class="w-full border border-gray-200 rounded-xl pl-10 pr-4 py-3 text-sm focus:outline-none focus:border-brand-orange">
                        </div>
                    </div>
                </div>
            </div>

            <button type="submit" class="w-full py-3 bg-brand-orange text-white rounded-xl text-xs font-bold shadow-md shadow-brand-orange/20 hover:bg-brand-orange-hover transition-colors">
                <i class="fa-solid fa-plus mr-2"></i> Tambah Kamar
            </button>
        </form>
    </div>

</div>

@endsection