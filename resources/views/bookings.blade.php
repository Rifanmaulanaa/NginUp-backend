@extends('layouts.app', ['activeTab' => 'bookings'])

@section('title', 'Pesanan Saya - NginUp')

@section('content')

    <div class="px-4 md:px-12 py-6 max-w-3xl mx-auto w-full pb-24 md:pb-6">
        
        {{-- Header Section --}}
        <div class="mb-6">
            <h1 class="text-2xl md:text-3xl font-extrabold text-[#1c333a] tracking-tight">Pesanan Saya</h1>
            <p class="text-gray-500 text-sm mt-1">Kelola tiket dan properti favorit kamu.</p>
        </div>

        {{-- Daftar Pemesanan --}}
        @forelse ($bookings as $booking)
        <div class="bg-white rounded-[24px] overflow-hidden shadow-[0_2px_15px_rgba(0,0,0,0.04)] border border-gray-100 mb-6">
            <div class="relative h-48 w-full">
                <img src="{{ $booking->properti->gambar->first()->url ?? 'https://images.unsplash.com/photo-1540541338287-41700207dee6?q=80&w=800&auto=format&fit=crop' }}" class="w-full h-full object-cover">
                <div class="absolute top-4 left-4 flex gap-2">
                    <span class="bg-brand-orange text-white text-[10px] font-bold px-2.5 py-1 rounded-md tracking-wider">
                        {{ ucfirst($booking->status_pemesanan) }}
                    </span>
                    @php $pay = $booking->pembayaran; @endphp
                    @if ($pay)
                        @php
                            $payClass = match($pay->status_pembayaran) {
                                'paid' => 'bg-green-500',
                                'failed' => 'bg-red-500',
                                'refunded' => 'bg-purple-500',
                                default => 'bg-yellow-500',
                            };
                        @endphp
                        <span class="{{ $payClass }} text-white text-[10px] font-bold px-2.5 py-1 rounded-md tracking-wider">
                            Payment: {{ ucfirst($pay->status_pembayaran) }}
                        </span>
                    @else
                        <span class="bg-gray-400 text-white text-[10px] font-bold px-2.5 py-1 rounded-md tracking-wider">
                            Belum Bayar
                        </span>
                    @endif
                </div>
            </div>
            <div class="p-5">
                <div class="flex justify-between items-start mb-1">
                    <h2 class="text-lg font-bold text-gray-900">{{ $booking->properti->nama_properti }}</h2>
                </div>
                <p class="text-sm text-gray-500 mb-4 flex items-center gap-1.5">
                    <i class="fa-solid fa-location-dot text-gray-400"></i> {{ $booking->properti->kota }}, {{ $booking->properti->provinsi }}
                </p>
                <div class="grid grid-cols-2 gap-4 mb-4">
                    <div>
                        <span class="text-[10px] text-gray-400 font-bold uppercase tracking-wider block">Check In</span>
                        <span class="text-sm font-bold text-gray-800 mt-0.5 block">{{ \Carbon\Carbon::parse($booking->tanggal_check_in)->format('d M Y') }}</span>
                    </div>
                    <div>
                        <span class="text-[10px] text-gray-400 font-bold uppercase tracking-wider block">Check Out</span>
                        <span class="text-sm font-bold text-gray-800 mt-0.5 block">{{ \Carbon\Carbon::parse($booking->tanggal_check_out)->format('d M Y') }}</span>
                    </div>
                </div>
                <div class="flex justify-between items-center pt-4 border-t border-gray-100">
                    <div>
                        <span class="text-[10px] text-gray-400 font-bold uppercase tracking-wider block">Booking ID</span>
                        <span class="text-sm font-bold text-gray-800 mt-0.5 block">#{{ $booking->id_pesanan }}</span>
                    </div>
                    <div class="flex items-center gap-2">
                        @php $review = $booking->review; @endphp
                        @if ($review)
                            <span class="text-[10px] font-bold text-amber-500 flex items-center gap-1 bg-amber-50 px-2.5 py-1 rounded-full border border-amber-200">
                                <i class="fa-solid fa-star text-[9px]"></i> {{ $review->rating }}/5
                            </span>
                        @endif
                        <a href="/booking-detail/{{ $booking->id_pesanan }}" class="bg-brand-orange hover:bg-brand-orange-hover text-white px-5 py-2 rounded-xl text-sm font-bold transition-all shadow-md shadow-brand-orange/20 active:scale-95">
                            View Details
                        </a>
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="text-center py-12 text-gray-500">
            Belum ada pemesanan.
        </div>
        @endforelse

    </div>

@endsection
