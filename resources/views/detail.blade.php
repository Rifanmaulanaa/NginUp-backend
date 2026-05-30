<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    @vite('resources/css/app.css')
    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

    <title>Detail Penginapan - NginUp</title>
</head>
<body class="bg-white text-gray-800">

    {{-- Navbar --}}
    <nav class="flex justify-between items-center px-4 md:px-12 py-4 bg-white border-b sticky top-0 z-50">
        <a href="/home" class="flex items-center gap-2 text-gray-600 hover:text-brand-orange-hover transition-colors">
            <i class="fa-solid fa-arrow-left text-xl"></i>
        </a>
        <a href="/home" class="flex items-center gap-2 text-2xl font-bold text-brand-orange-hover">
            NginUp
        </a>
        <div class="flex items-center gap-4 text-gray-600">
            <button class="hover:text-brand-orange-hover transition-colors"><i class="fa-solid fa-share-nodes text-xl"></i></button>
            <button class="hover:text-red-500 transition-colors"><i class="fa-regular fa-heart text-xl"></i></button>
        </div>
    </nav>

    <main class="max-w-7xl mx-auto px-4 md:px-12 py-6">

        {{-- Main Image (Desktop & Mobile) --}}
        <div class="w-full h-[300px] md:h-[500px] rounded-2xl overflow-hidden mb-8">
            <img src="{{ $properti->gambar->first()->url ?? 'https://images.unsplash.com/photo-1540541338287-41700207dee6?q=80&w=1200&auto=format&fit=crop' }}" alt="{{ $properti->nama_properti }}" class="w-full h-full object-cover">
        </div>

        {{-- Header Info --}}
        <div class="flex flex-col md:flex-row gap-8">
            
            {{-- Left Content --}}
            <div class="flex-1">
                
                {{-- Tags --}}
                <div class="flex flex-wrap gap-2 mb-4">
                    <span class="bg-blue-50 text-blue-600 px-3 py-1 rounded-full text-sm font-medium flex items-center gap-1">
                        <i class="fa-solid fa-house-user"></i> {{ ucfirst($properti->tipe_properti) }}
                    </span>
                    @if ($properti->verified_status === 'verified')
                    <span class="bg-green-50 text-green-600 px-3 py-1 rounded-full text-sm font-medium flex items-center gap-1">
                        <i class="fa-solid fa-circle-check"></i> Verified
                    </span>
                    @endif
                </div>

                {{-- Title & Location --}}
                <h1 class="text-3xl md:text-4xl font-bold mb-2">{{ $properti->nama_properti }}</h1>
                <p class="text-gray-500 flex items-center gap-2 mb-8">
                    <i class="fa-solid fa-location-dot text-gray-400"></i> {{ $properti->kota }}, {{ $properti->provinsi }}
                </p>

                {{-- Host Info --}}
                <div class="bg-blue-50/50 p-4 md:p-6 rounded-2xl flex items-center justify-between mb-8 border border-blue-100">
                    <div class="flex items-center gap-4">
                        <img src="https://images.unsplash.com/photo-1534528741775-53994a69daeb?q=80&w=100&auto=format&fit=crop" alt="{{ $properti->user->nama }}" class="w-14 h-14 rounded-full object-cover">
                        <div>
                            <p class="text-gray-500 text-sm">Dikelola oleh</p>
                            <h3 class="font-bold text-lg">{{ $properti->user->nama }}</h3>
                            <p class="text-gray-500 text-sm">Bergabung {{ $properti->user->created_at->format('Y') }}</p>
                        </div>
                    </div>
                    <button class="border border-gray-300 text-gray-700 bg-white px-4 py-2 rounded-xl font-medium hover:bg-gray-50 transition-colors">
                        Hubungi Host
                    </button>
                </div>

                {{-- Tentang Properti --}}
                <div class="mb-8">
                    <h2 class="text-2xl font-bold mb-4">Tentang Properti</h2>
                    <p class="text-gray-600 leading-relaxed mb-2">
                        {{ $properti->deskripsi ?? 'Tidak ada deskripsi.' }}
                    </p>
                </div>

                {{-- Fasilitas Utama --}}
                @if ($properti->fasilitas->count())
                <div class="mb-12">
                    <h2 class="text-2xl font-bold mb-4">Fasilitas Utama</h2>
                    <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                        @foreach ($properti->fasilitas as $fasilitas)
                        <div class="border rounded-xl p-4 flex flex-col items-center justify-center gap-2 text-center">
                            <i class="fa-solid fa-check-circle text-2xl text-brand-orange-hover"></i>
                            <span class="font-medium text-gray-700 text-sm">{{ $fasilitas->nama_fasilitas }}</span>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif

                {{-- Rooms Section --}}
                @if ($properti->kamar->count())
                <div class="mb-12">
                    <h2 class="text-2xl font-bold mb-4">Kamar Tersedia</h2>
                    <div class="space-y-3">
                        @foreach ($properti->kamar as $kamar)
                        <div class="border rounded-xl p-4 flex items-center justify-between">
                            <div class="flex items-center gap-4">
                                <div class="w-10 h-10 rounded-xl bg-orange-50 text-brand-orange flex items-center justify-center">
                                    <i class="fa-solid fa-door-open"></i>
                                </div>
                                <div>
                                    <h3 class="font-bold text-gray-900">{{ $kamar->nama_kamar }}</h3>
                                    <p class="text-sm text-gray-500">
                                        {{ $kamar->kapasitas }} tamu · {{ $kamar->jumlah_tempat_tidur }} {{ ucfirst($kamar->tipe_tempat_tidur) }}
                                    </p>
                                </div>
                            </div>
                            <div class="text-right">
                                @if ($kamar->harga_per_malam)
                                <span class="font-bold text-brand-orange">Rp {{ number_format($kamar->harga_per_malam, 0, ',', '.') }}</span>
                                <span class="text-xs text-gray-500">/malam</span>
                                @else
                                <span class="text-sm text-gray-400">Termasuk harga properti</span>
                                @endif
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif

                {{-- Review Section --}}
                @if ($properti->review->count())
                <div class="mb-12">
                    <h2 class="text-2xl font-bold mb-4">Ulasan Tamu</h2>
                    <div class="flex items-center gap-2 mb-6">
                        <i class="fa-solid fa-star text-brand-orange text-2xl"></i>
                        <span class="text-2xl font-bold">{{ number_format($properti->review_avg_rating, 1) }}</span>
                        <span class="text-gray-500">({{ $properti->review->count() }} ulasan)</span>
                    </div>
                    <div class="space-y-6">
                        @foreach ($properti->review as $review)
                        <div class="border-b pb-6">
                            <div class="flex items-center gap-3 mb-2">
                                <div class="w-10 h-10 rounded-full bg-brand-green/10 flex items-center justify-center text-brand-green font-bold">
                                    {{ strtoupper(substr($review->user->nama ?? 'U', 0, 1)) }}
                                </div>
                                <div>
                                    <p class="font-bold">{{ $review->user->nama ?? 'Pengguna' }}</p>
                                    <div class="flex items-center gap-1 text-sm text-yellow-500">
                                        @for ($i = 1; $i <= 5; $i++)
                                            <i class="fa-solid fa-star {{ $i <= $review->rating ? 'text-yellow-500' : 'text-gray-200' }}"></i>
                                        @endfor
                                    </div>
                                </div>
                            </div>
                            <p class="text-gray-600 text-sm">{{ $review->komentar }}</p>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif

                {{-- Map --}}
                <div class="mb-12">
                    <h2 class="text-2xl font-bold mb-4">Lokasi</h2>
                    <p class="text-gray-500 mb-4 text-sm">{{ $properti->alamat }}, {{ $properti->kota }}, {{ $properti->provinsi }}</p>
                    <div id="property-map" class="w-full h-[300px] rounded-2xl overflow-hidden border border-gray-200"></div>
                </div>

            </div>

            {{-- Right Content (Booking Card) --}}
            <div class="w-full md:w-[400px]">
            <div class="bg-white border rounded-2xl shadow-xl p-6 sticky top-24 relative" x-data="{
                checkIn: '', checkOut: '', guests: 1,
                maxGuests: {{ $properti->max_tamu ?? 1 }},
                harga: {{ $properti->harga_per_malam ?? 0 }},
                showCalendar: false,
                selectingFor: 'checkIn',
                calMonth: new Date().getMonth(),
                calYear: new Date().getFullYear(),
                monthNames: ['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'],
                rooms: {!! $properti->kamar->map(fn($k) => ['id_kamar' => $k->id_kamar, 'nama_kamar' => $k->nama_kamar, 'kapasitas' => $k->kapasitas, 'jumlah_tempat_tidur' => $k->jumlah_tempat_tidur, 'tipe_tempat_tidur' => $k->tipe_tempat_tidur, 'harga_per_malam' => $k->harga_per_malam, 'status' => $k->status])->toJson() !!},
                selectedRoom: null,
                daysInMonth() { return new Date(this.calYear, this.calMonth + 1, 0).getDate() },
                firstDay() { return new Date(this.calYear, this.calMonth, 1).getDay() },
                openCalendar(field) {
                    this.selectingFor = field;
                    const d = field === 'checkIn' && this.checkIn ? new Date(this.checkIn) : field === 'checkOut' && this.checkOut ? new Date(this.checkOut) : new Date();
                    this.calMonth = d.getMonth();
                    this.calYear = d.getFullYear();
                    this.showCalendar = true;
                },
                selectDate(day) {
                    const sel = this.calYear + '-' + String(this.calMonth + 1).padStart(2,'0') + '-' + String(day).padStart(2,'0');
                    if (this.selectingFor === 'checkIn') {
                        this.checkIn = sel;
                        if (this.checkOut && this.checkOut <= sel) this.checkOut = '';
                        this.selectingFor = 'checkOut';
                    } else {
                        this.checkOut = sel;
                        this.showCalendar = false;
                    }
                },
                prevMonth() { if (this.calMonth === 0) { this.calMonth = 11; this.calYear--; } else this.calMonth-- },
                nextMonth() { if (this.calMonth === 11) { this.calMonth = 0; this.calYear++; } else this.calMonth++ },
                isPast(day) { return new Date(this.calYear, this.calMonth, day) < new Date(new Date().toDateString()) },
                isSel(day) { const s = this.calYear + '-' + String(this.calMonth + 1).padStart(2,'0') + '-' + String(day).padStart(2,'0'); return s === this.checkIn || s === this.checkOut },
                isIn(day) { if (!this.checkIn || !this.checkOut) return false; const s = this.calYear + '-' + String(this.calMonth + 1).padStart(2,'0') + '-' + String(day).padStart(2,'0'); return s > this.checkIn && s < this.checkOut },
                isCI(day) { const s = this.calYear + '-' + String(this.calMonth + 1).padStart(2,'0') + '-' + String(day).padStart(2,'0'); return s === this.checkIn },
                isCO(day) { const s = this.calYear + '-' + String(this.calMonth + 1).padStart(2,'0') + '-' + String(day).padStart(2,'0'); return s === this.checkOut },
                malam() { if (!this.checkIn || !this.checkOut) return 0; const ci = new Date(this.checkIn), co = new Date(this.checkOut); return Math.max(0, Math.floor((co - ci) / (1000*60*60*24))) },
                roomPrice(room) { return room?.harga_per_malam || this.harga },
                totalSewa() {
                    const h = this.selectedRoom ? this.roomPrice(this.selectedRoom) : this.harga;
                    return this.malam() * h;
                },
                biayaLayanan() { return Math.round(this.totalSewa() * 0.05) },
                totalBayar() { return this.totalSewa() + this.biayaLayanan() },
                selectRoom(room) { this.selectedRoom = room; },
            }">
                    <div class="flex justify-between items-end mb-6">
                        <div>
                            <span class="text-2xl font-bold">Rp {{ number_format($properti->harga_per_malam, 0, ',', '.') }}</span>
                            <span class="text-gray-500 text-sm">/ malam</span>
                        </div>
                        <div class="flex items-center gap-1 font-medium">
                            <i class="fa-solid fa-star text-brand-orange text-sm"></i> {{ number_format($properti->review_avg_rating ?? 0, 1) }}
                        </div>
                    </div>

                    {{-- Booking Inputs (click to open calendar popup, readonly) --}}
                    <div class="border rounded-xl mb-6">
                        <div class="flex border-b">
                            <div class="flex-1 p-3 border-r cursor-pointer" @click="openCalendar('checkIn')">
                                <p class="text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-1">Check-In</p>
                                <div class="flex items-center gap-1">
                                    <span x-text="checkIn || 'Pilih Tanggal'" class="text-sm font-medium"
                                        :class="checkIn ? 'text-gray-800' : 'text-gray-500'"></span>
                                </div>
                            </div>
                            <div class="flex-1 p-3 cursor-pointer" @click="openCalendar('checkOut')">
                                <p class="text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-1">Check-Out</p>
                                <div class="flex items-center gap-1">
                                    <span x-text="checkOut || 'Pilih Tanggal'" class="text-sm font-medium"
                                        :class="checkOut ? 'text-gray-800' : 'text-gray-500'"></span>
                                </div>
                            </div>
                        </div>

                        {{-- Custom Calendar Popup (below dates, above Tamu) --}}
                        <div x-show="showCalendar" @click.outside="showCalendar = false" class="border-t bg-white px-4 pt-4 pb-2">
                            <div class="flex items-center justify-between mb-4">
                                <button @click="prevMonth" class="w-8 h-8 flex items-center justify-center rounded-full hover:bg-gray-100"><i class="fa-solid fa-chevron-left text-sm"></i></button>
                                <span class="font-bold text-sm" x-text="monthNames[calMonth] + ' ' + calYear"></span>
                                <button @click="nextMonth" class="w-8 h-8 flex items-center justify-center rounded-full hover:bg-gray-100"><i class="fa-solid fa-chevron-right text-sm"></i></button>
                            </div>
                            <div class="grid grid-cols-7 gap-0 text-center mb-2">
                                <template x-for="(d,i) in ['Min','Sen','Sel','Rab','Kam','Jum','Sab']" :key="i">
                                    <span class="text-[10px] font-bold text-gray-400 uppercase py-1" x-text="d"></span>
                                </template>
                            </div>
                            <div class="grid grid-cols-7 gap-0 text-center">
                                <template x-for="blank in firstDay()" :key="'b'+blank">
                                    <span></span>
                                </template>
                                <template x-for="day in daysInMonth()" :key="day">
                                    <button @click="isPast(day) || selectDate(day)"
                                        :disabled="isPast(day)"
                                        :class="{
                                            'bg-brand-orange text-white rounded-full': isCI(day) || isCO(day),
                                            'bg-brand-orange/10 text-brand-orange-hover rounded-full': isIn(day),
                                            'text-gray-300 cursor-not-allowed': isPast(day),
                                            'hover:bg-gray-100 rounded-full': !isPast(day) && !isCI(day) && !isCO(day)
                                        }"
                                        class="w-full aspect-square text-sm font-medium flex items-center justify-center transition-colors"
                                        x-text="day">
                                    </button>
                                </template>
                            </div>
                            <div class="flex justify-center gap-6 mt-3 text-xs text-gray-400 pb-2 border-b">
                                <span class="flex items-center gap-1"><span class="w-3 h-3 inline-block bg-brand-orange rounded-full"></span> Terpilih</span>
                                <span class="flex items-center gap-1"><span class="w-3 h-3 inline-block bg-brand-orange/10 rounded-full"></span> Range</span>
                            </div>
                        </div>
                        <div class="p-3 relative" x-data="{ openTamu: false }">
                            <p class="text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-1">Tamu</p>
                            <div class="flex items-center gap-1">
                                <input type="number" x-model.number="guests" :min="1" :max="maxGuests"
                                    placeholder="Jumlah Tamu"
                                    class="flex-1 text-sm font-medium text-gray-800 border-0 p-0 focus:ring-0 outline-none bg-transparent"
                                    @click.stop="openTamu = !openTamu">
                                <button @click="openTamu = !openTamu" class="text-gray-400 hover:text-brand-orange">
                                    <i class="fa-solid fa-chevron-down text-xs transition-transform" :class="openTamu ? 'rotate-180' : ''"></i>
                                </button>
                            </div>
                            <div x-show="openTamu" @click.outside="openTamu = false" class="absolute left-3 right-3 top-[calc(100%-4px)] bg-white border rounded-xl shadow-lg z-10 py-1">
                                <template x-for="i in maxGuests" :key="i">
                                    <button @click="guests = i; openTamu = false"
                                        :class="guests === i ? 'bg-brand-orange/10 text-brand-orange-hover font-bold' : 'text-gray-700 hover:bg-gray-50'"
                                        class="w-full text-left px-4 py-2.5 text-sm transition-colors"
                                        x-text="i + ' Tamu'">
                                    </button>
                                </template>
                            </div>
                        </div>
                    </div>

                    {{-- Room Selection --}}
                    <template x-if="checkIn && checkOut && rooms.length > 0">
                        <div class="mb-6 border-b pb-6">
                            <p class="text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-3">Pilih Kamar</p>
                            <div class="space-y-2">
                                <template x-for="room in rooms" :key="room.id">
                                    <div @click="selectRoom(room)"
                                         class="flex items-center justify-between p-3 rounded-xl border-2 transition-all duration-200 cursor-pointer"
                                         :class="selectedRoom?.id_kamar === room.id_kamar ? 'border-brand-orange bg-brand-orange/5' : 'border-gray-100 hover:border-gray-200'">
                                        <div class="flex items-center gap-3">
                                            <div class="w-8 h-8 rounded-lg flex items-center justify-center"
                                                 :class="selectedRoom?.id_kamar === room.id_kamar ? 'bg-brand-orange/15 text-brand-orange' : 'bg-gray-50 text-gray-400'">
                                                <i class="fa-solid fa-door-open text-xs"></i>
                                            </div>
                                            <div>
                                                <div class="text-xs font-bold text-gray-900" x-text="room.nama_kamar"></div>
                                                <div class="text-[9px] text-gray-500" x-text="room.kapasitas + ' tamu · ' + room.jumlah_tempat_tidur + ' ' + room.tipe_tempat_tidur"></div>
                                            </div>
                                        </div>
                                        <div class="text-right">
                                            <div class="text-xs font-bold" x-text="room.harga_per_malam ? 'Rp ' + Number(room.harga_per_malam).toLocaleString('id-ID') : 'Rp ' + Number(harga).toLocaleString('id-ID')"></div>
                                            <div class="text-[8px] text-gray-400">/malam</div>
                                        </div>
                                    </div>
                                </template>
                            </div>
                        </div>
                    </template>

                    {{-- Dynamic Price Breakdown --}}
                    <template x-if="checkIn && checkOut">
                        <div class="flex flex-col gap-3 mb-6 border-b pb-6">
                            <div class="flex justify-between text-gray-600">
                                <span x-text="'Rp ' + new Intl.NumberFormat('id-ID').format(selectedRoom ? roomPrice(selectedRoom) : harga) + ' x ' + malam() + ' malam'"></span>
                                <span class="font-bold text-gray-900" x-text="'Rp ' + new Intl.NumberFormat('id-ID').format(totalSewa())"></span>
                            </div>
                            <div class="flex justify-between text-gray-600">
                                <span class="underline decoration-dashed underline-offset-4 cursor-pointer">Biaya Layanan</span>
                                <span class="font-bold text-gray-900" x-text="'Rp ' + new Intl.NumberFormat('id-ID').format(biayaLayanan())"></span>
                            </div>
                        </div>
                    </template>
                    <template x-if="!checkIn || !checkOut">
                        <div class="flex flex-col gap-3 mb-6 border-b pb-6">
                            <div class="flex justify-between text-gray-600">
                                <span>Rp {{ number_format($properti->harga_per_malam, 0, ',', '.') }} x malam</span>
                                <span>—</span>
                            </div>
                            <div class="flex justify-between text-gray-600">
                                <span class="underline decoration-dashed underline-offset-4 cursor-pointer">Biaya Layanan</span>
                                <span>—</span>
                            </div>
                        </div>
                    </template>

                    {{-- Total --}}
                    <div class="flex justify-between font-bold text-lg mb-6">
                        <span>Total</span>
                        <template x-if="checkIn && checkOut">
                            <span class="text-brand-orange" x-text="'Rp ' + new Intl.NumberFormat('id-ID').format(totalBayar())"></span>
                        </template>
                        <template x-if="!checkIn || !checkOut">
                            <span>—</span>
                        </template>
                    </div>

                    <form method="GET" action="/checkout">
                        <input type="hidden" name="id" :value="'{{ $properti->id_properti }}'">
                        <input type="hidden" name="check_in" :value="checkIn">
                        <input type="hidden" name="check_out" :value="checkOut">
                        <input type="hidden" name="tamu" :value="guests">
                        <input type="hidden" name="id_kamar" :value="selectedRoom?.id_kamar || ''">
                        <button type="submit" :disabled="!checkIn || !checkOut" 
                            :class="checkIn && checkOut ? 'bg-brand-orange-hover hover:bg-[#a6410a]' : 'bg-gray-300 cursor-not-allowed'"
                            class="w-full text-white font-bold py-4 rounded-xl transition-colors mb-4 block text-center">
                            Pesan Sekarang
                        </button>
                    </form>

                    <p class="text-center text-sm text-gray-500">Anda belum akan dikenakan biaya</p>

                </div>
            </div>



        </div>

    </main>

<script>
document.addEventListener('DOMContentLoaded', function() {
    var lat = {{ $properti->latitude ?? '-7.250445' }};
    var lng = {{ $properti->longitude ?? '107.5' }};
    var map = L.map('property-map').setView([lat, lng], 14);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; OpenStreetMap'
    }).addTo(map);
    L.marker([lat, lng]).addTo(map)
        .bindPopup('<strong>{{ $properti->nama_properti }}</strong><br>{{ $properti->kota }}, {{ $properti->provinsi }}');
});
</script>
</body>
</html>
