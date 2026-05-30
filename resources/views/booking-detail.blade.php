@extends('layouts.app', ['activeTab' => 'bookings'])

@section('title', 'Booking Detail - NginUp')

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
@endpush

@section('content')

    {{-- Top navigation with back button --}}
    <div class="sticky top-0 bg-white/80 backdrop-blur-md z-40 border-b border-gray-100 px-4 py-4 flex items-center justify-between md:hidden">
        <a href="/bookings" class="w-8 h-8 flex items-center justify-center rounded-full hover:bg-gray-100 transition-colors">
            <i class="fa-solid fa-arrow-left text-gray-700"></i>
        </a>
        <span class="font-bold text-gray-900 text-lg">Booking Detail</span>
        <div class="w-8"></div>
    </div>

    <div class="md:px-12 md:py-8 max-w-3xl mx-auto w-full pb-24 md:pb-6 bg-[#F8F9FA] md:bg-transparent min-h-screen">
        
        <div class="bg-white md:rounded-[32px] md:shadow-sm md:border md:border-gray-100 overflow-hidden pb-8">
            
            {{-- Image Header --}}
            <div class="relative h-64 md:h-80 w-full">
                <img src="{{ $booking->properti->gambar->first()->url ?? 'https://images.unsplash.com/photo-1540541338287-41700207dee6?q=80&w=1200&auto=format&fit=crop' }}" class="w-full h-full object-cover">
                <div class="absolute top-4 left-4 bg-brand-orange text-white text-[10px] font-bold px-3 py-1.5 rounded-full tracking-wider shadow-md">
                    <i class="fa-solid fa-circle-check mr-1"></i>
                    {{ ucfirst($booking->status_pemesanan) }}
                </div>
            </div>

            <div class="px-5 md:px-8 pt-6">
                
                {{-- Title Area --}}
                <div class="mb-6 border-b border-gray-100 pb-6">
                    <span class="text-[10px] text-gray-400 font-bold tracking-widest uppercase mb-1 block">{{ ucfirst($booking->properti->tipe_properti) }} • Detail Reservasi</span>
                    <h1 class="text-2xl font-extrabold text-gray-900 mb-2">{{ $booking->properti->nama_properti }}</h1>
                    <p class="text-sm text-gray-500 flex items-center gap-1.5">
                        <i class="fa-solid fa-location-dot text-gray-400"></i>
                        {{ $booking->properti->alamat ?? $booking->properti->kota . ', ' . $booking->properti->provinsi }}
                    </p>
                </div>

                {{-- Date Cards --}}
                <div class="flex gap-4 mb-8">
                    <div class="flex-1 bg-blue-50/50 border border-blue-100 rounded-2xl p-4 flex flex-col justify-center">
                        <div class="flex items-center gap-2 mb-2">
                            <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center text-blue-600">
                                <i class="fa-regular fa-calendar-check"></i>
                            </div>
                            <span class="text-xs font-bold text-gray-500 uppercase tracking-wider">Check In</span>
                        </div>
                        <span class="text-sm font-bold text-gray-900 block">{{ \Carbon\Carbon::parse($booking->tanggal_check_in)->format('d M Y') }}</span>
                        <span class="text-xs text-gray-500 mt-0.5 block">Dari pukul 14:00</span>
                    </div>

                    <div class="flex-1 bg-orange-50/50 border border-orange-100 rounded-2xl p-4 flex flex-col justify-center">
                        <div class="flex items-center gap-2 mb-2">
                            <div class="w-8 h-8 rounded-full bg-orange-100 flex items-center justify-center text-brand-orange">
                                <i class="fa-regular fa-calendar-xmark"></i>
                            </div>
                            <span class="text-xs font-bold text-gray-500 uppercase tracking-wider">Check Out</span>
                        </div>
                        <span class="text-sm font-bold text-gray-900 block">{{ \Carbon\Carbon::parse($booking->tanggal_check_out)->format('d M Y') }}</span>
                        <span class="text-xs text-gray-500 mt-0.5 block">Sebelum pukul 12:00</span>
                    </div>
                </div>

                {{-- Rincian Tamu --}}
                <div class="mb-8">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Rincian Tamu</h3>
                    <div class="flex flex-col gap-3">
                        <div class="flex justify-between items-center py-2 border-b border-gray-50">
                            <span class="text-sm text-gray-500">Tamu Utama</span>
                            <span class="text-sm font-bold text-gray-900">{{ $booking->user->nama ?? Auth::user()->nama }}</span>
                        </div>
                        <div class="flex justify-between items-center py-2 border-b border-gray-50">
                            <span class="text-sm text-gray-500">Jumlah Tamu</span>
                            <span class="text-sm font-bold text-gray-900">{{ $booking->total_tamu }} Tamu</span>
                        </div>
                        <div class="flex justify-between items-center py-2">
                            <span class="text-sm text-gray-500">Total Harga</span>
                            <span class="text-sm font-bold text-brand-orange">Rp {{ number_format($booking->total_harga, 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>

                {{-- Payment Status --}}
                <div class="mb-8 border-b border-gray-100 pb-6">
                    <span class="text-[10px] text-gray-400 font-bold tracking-widest uppercase mb-4 block">Status Pembayaran</span>
                    @php $payment = $booking->pembayaran; @endphp
                    @if ($payment)
                        <div class="bg-white border border-gray-100 rounded-2xl p-4 shadow-sm">
                            <div class="flex justify-between items-center mb-3">
                                <span class="text-sm text-gray-500">Kode Pembayaran</span>
                                <span class="text-sm font-bold text-gray-900">{{ $payment->code_pembayaran }}</span>
                            </div>
                            <div class="flex justify-between items-center mb-3">
                                <span class="text-sm text-gray-500">Status</span>
                                @php
                                    $statusClass = match($payment->status_pembayaran) {
                                        'paid' => 'bg-green-100 text-green-700',
                                        'failed' => 'bg-red-100 text-red-700',
                                        'refunded' => 'bg-purple-100 text-purple-700',
                                        default => 'bg-yellow-100 text-yellow-700',
                                    };
                                @endphp
                                <span class="text-xs font-bold px-3 py-1 rounded-full {{ $statusClass }}">
                                    {{ ucfirst($payment->status_pembayaran) }}
                                </span>
                            </div>
                            <div class="flex justify-between items-center mb-3">
                                <span class="text-sm text-gray-500">Metode</span>
                                <span class="text-sm font-bold text-gray-900">{{ str_replace('_', ' ', ucfirst($payment->metode_pembayaran)) }}</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-500">Jumlah</span>
                                <span class="text-sm font-bold text-brand-orange">Rp {{ number_format($payment->jumlah_pembayaran, 0, ',', '.') }}</span>
                            </div>
                            @if ($payment->status_pembayaran === 'paid')
                                <div class="mt-3 pt-3 border-t border-gray-100 flex items-center gap-2 text-green-600 text-xs">
                                    <i class="fa-solid fa-circle-check"></i>
                                    Lunas pada {{ $payment->tanggal_pembayaran ? \Carbon\Carbon::parse($payment->tanggal_pembayaran)->format('d M Y H:i') : '-' }}
                                </div>
                            @endif
                            @if ($payment->status_pembayaran === 'failed')
                                <div class="mt-3 pt-3 border-t border-gray-100 flex items-center gap-2 text-red-500 text-xs">
                                    <i class="fa-solid fa-circle-xmark"></i>
                                    Pembayaran ditolak. <a href="/payment/{{ $booking->id_pesanan }}" class="underline font-semibold">Upload ulang bukti bayar</a>
                                </div>
                            @endif
                        </div>
                    @else
                        <div class="bg-yellow-50 border border-yellow-100 rounded-2xl p-4">
                            <div class="flex items-start gap-3">
                                <i class="fa-solid fa-clock text-yellow-600 mt-1"></i>
                                <div>
                                    <p class="text-sm font-bold text-yellow-800">Menunggu Pembayaran</p>
                                    <p class="text-xs text-yellow-700 mt-1 mb-3">Silakan upload bukti pembayaran untuk melanjutkan.</p>
                                    <a href="/payment/{{ $booking->id_pesanan }}" class="inline-block bg-brand-orange hover:bg-brand-orange-hover text-white text-xs font-bold px-4 py-2 rounded-xl transition-all">
                                        <i class="fa-regular fa-credit-card mr-1"></i>
                                        Upload Bukti Bayar
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>

                {{-- Review Section --}}
                <div class="mb-8 border-b border-gray-100 pb-6">
                    <span class="text-[10px] text-gray-400 font-bold tracking-widest uppercase mb-4 block">Ulasan</span>
                    @php
                        $checkOutDate = \Carbon\Carbon::parse($booking->tanggal_check_out);
                        $canReview = $checkOutDate->isPast() || $checkOutDate->isToday();
                        $existingReview = $booking->review;
                    @endphp
                    @if ($existingReview)
                        <div class="bg-white border border-gray-100 rounded-2xl p-4 shadow-sm">
                            <div class="flex items-center gap-1 mb-2">
                                @for ($i = 1; $i <= 5; $i++)
                                    <i class="fa-solid fa-star {{ $i <= $existingReview->rating ? 'text-amber-400' : 'text-gray-200' }} text-sm"></i>
                                @endfor
                                <span class="text-sm font-bold text-gray-900 ml-1">{{ $existingReview->rating }}/5</span>
                            </div>
                            @if ($existingReview->komentar)
                                <p class="text-sm text-gray-600 mb-3">{{ $existingReview->komentar }}</p>
                            @endif
                            <div class="flex items-center gap-2">
                                <span class="text-[10px] text-gray-400">Ditulis {{ $existingReview->created_at ? \Carbon\Carbon::parse($existingReview->created_at)->format('d M Y') : '-' }}</span>
                                <button onclick="document.getElementById('editReviewForm').classList.toggle('hidden')" class="text-[10px] font-bold text-brand-orange hover:underline">
                                    <i class="fa-regular fa-pen-to-square mr-1"></i>Edit
                                </button>
                            </div>
                            <form id="editReviewForm" method="POST" action="/review/{{ $existingReview->id_review }}" class="hidden mt-4 border-t border-gray-100 pt-4 space-y-3">
                                @csrf
                                @method('PUT')
                                <div class="flex items-center gap-1 mb-2" x-data="{ editRating: {{ $existingReview->rating }} }">
                                    <template x-for="star in 5" :key="star">
                                        <button type="button" @click="editRating = star" class="text-2xl transition-colors focus:outline-none"
                                                :class="star <= editRating ? 'text-amber-400' : 'text-gray-200'">
                                            <i class="fa-solid fa-star"></i>
                                        </button>
                                    </template>
                                    <input type="hidden" name="rating" x-model="editRating">
                                </div>
                                <textarea name="komentar" rows="3" class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-brand-orange" placeholder="Perbarui ulasan Anda...">{{ $existingReview->komentar }}</textarea>
                                <button type="submit" class="bg-brand-orange hover:bg-brand-orange-hover text-white text-xs font-bold px-5 py-2.5 rounded-xl transition-all">
                                    Simpan Perubahan
                                </button>
                            </form>
                        </div>
                    @elseif ($canReview)
                        <div class="bg-white border border-gray-100 rounded-2xl p-4 shadow-sm">
                            <p class="text-sm text-gray-600 mb-3">Bagaimana pengalaman menginap Anda? Beri ulasan untuk membantu traveler lain.</p>
                            <button onclick="document.getElementById('reviewForm').classList.toggle('hidden')" class="bg-brand-orange hover:bg-brand-orange-hover text-white text-xs font-bold px-5 py-2.5 rounded-xl transition-all">
                                <i class="fa-regular fa-star mr-1"></i> Beri Ulasan
                            </button>
                            <form id="reviewForm" method="POST" action="/review" class="hidden mt-4 border-t border-gray-100 pt-4 space-y-3">
                                @csrf
                                <input type="hidden" name="id_pesanan" value="{{ $booking->id_pesanan }}">
                                <input type="hidden" name="id_properti" value="{{ $booking->id_properti }}">
                                <div class="flex items-center gap-1 mb-2" x-data="{ rating: 0 }">
                                    <template x-for="star in 5" :key="star">
                                        <button type="button" @click="rating = star" class="text-2xl transition-colors focus:outline-none"
                                                :class="star <= rating ? 'text-amber-400' : 'text-gray-200'">
                                            <i class="fa-solid fa-star"></i>
                                        </button>
                                    </template>
                                    <input type="hidden" name="rating" x-model="rating">
                                </div>
                                <textarea name="komentar" rows="3" class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-brand-orange" placeholder="Tulis ulasan Anda..."></textarea>
                                <button type="submit" class="bg-brand-orange hover:bg-brand-orange-hover text-white text-xs font-bold px-5 py-2.5 rounded-xl transition-all">
                                    Kirim Ulasan
                                </button>
                            </form>
                        </div>
                    @else
                        <div class="bg-gray-50 border border-gray-100 rounded-2xl p-4">
                            <div class="flex items-start gap-3">
                                <i class="fa-solid fa-clock text-gray-400 mt-1"></i>
                                <div>
                                    <p class="text-sm font-bold text-gray-800">Belum bisa memberi ulasan</p>
                                    <p class="text-xs text-gray-500 mt-1">Ulasan dapat diberikan setelah tanggal check-out ({{ $checkOutDate->format('d M Y') }}).</p>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>

                {{-- QR Code / Booking ID area --}}
                <div class="bg-gray-50 border border-gray-100 rounded-3xl p-6 flex flex-col items-center justify-center text-center mb-8 shadow-sm">
                    <span class="text-[10px] text-gray-400 font-bold tracking-widest uppercase mb-1">Booking ID</span>
                    <h2 class="text-2xl font-black text-gray-900 tracking-wider mb-4">#{{ $booking->id_pesanan }}</h2>
                    
                    <div class="w-32 h-32 bg-white rounded-xl flex items-center justify-center mb-5 shadow-sm border border-gray-100 p-2">
                        <img src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&data={{ $booking->id_pesanan }}" alt="QR Code" class="w-full h-full object-contain mix-blend-multiply opacity-80">
                    </div>
                    
                    <p class="text-[10px] text-gray-500 mb-4 px-4 leading-relaxed">
                        Tunjukkan QR Code ini kepada resepsionis di properti saat proses check-in.
                    </p>
                    
                    <button class="w-full bg-brand-orange hover:bg-brand-orange-hover active:scale-95 text-white py-3.5 rounded-xl font-bold transition-all shadow-md shadow-brand-orange/20">
                        Cetak E-Voucher
                    </button>
                </div>

                {{-- Kontak Host --}}
                <div class="mb-8 border-t border-gray-100 pt-8">
                    <span class="text-[10px] text-gray-400 font-bold tracking-widest uppercase mb-4 block">Kontak Host</span>
                    <div class="flex items-center gap-4 mb-4">
                        <div class="w-12 h-12 rounded-full bg-brand-green/10 flex items-center justify-center text-brand-green font-bold">
                            {{ strtoupper(substr($booking->properti->user->nama ?? 'H', 0, 1)) }}
                        </div>
                        <div>
                            <h4 class="font-bold text-gray-900 text-sm">{{ $booking->properti->user->nama ?? 'Host' }}</h4>
                            <p class="text-xs text-gray-500">"Selamat datang!"</p>
                        </div>
                    </div>
                    <div class="flex gap-3">
                        <button class="flex-1 border border-gray-200 py-2.5 rounded-xl text-sm font-semibold text-gray-700 hover:bg-gray-50 flex items-center justify-center gap-2 transition-colors">
                            <i class="fa-regular fa-message"></i> Chat
                        </button>
                        <button class="flex-1 border border-gray-200 py-2.5 rounded-xl text-sm font-semibold text-gray-700 hover:bg-gray-50 flex items-center justify-center gap-2 transition-colors">
                            <i class="fa-solid fa-phone"></i> Telepon
                        </button>
                    </div>
                </div>

                {{-- Lokasi Properti --}}
                <div class="border-t border-gray-100 pt-8">
                    <span class="text-[10px] text-gray-400 font-bold tracking-widest uppercase mb-4 block">Lokasi Properti</span>
                    <div id="booking-map" class="w-full h-[200px] bg-gray-200 rounded-2xl mb-4 overflow-hidden border border-gray-100"></div>
                    <p class="text-xs text-gray-600 mb-4 leading-relaxed">
                        {{ $booking->properti->alamat ?? $booking->properti->kota . ', ' . $booking->properti->provinsi }}
                    </p>
                    @push('scripts')
                    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
                    <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        var lat = {{ $booking->properti->latitude ?? '-7.250445' }};
                        var lng = {{ $booking->properti->longitude ?? '107.5' }};
                        var map = L.map('booking-map').setView([lat, lng], 15);
                        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                            attribution: '&copy; OpenStreetMap'
                        }).addTo(map);
                        L.marker([lat, lng]).addTo(map)
                            .bindPopup('<strong>{{ $booking->properti->nama_properti }}</strong>');
                    });
                    </script>
                    @endpush
                </div>

            </div>
        </div>

    </div>

@endsection
