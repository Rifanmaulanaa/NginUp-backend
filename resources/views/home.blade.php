@extends('layouts.app', ['activeTab' => 'home'])

@section('title', 'Home - NginUp')

@section('content')

<div x-data="{
    activeType: '',
    kota: '', checkIn: '', checkOut: '', tamu: 1,
    showKota: false, showCalendar: false,
    selectingFor: 'checkIn',
    calMonth: new Date().getMonth(),
    calYear: new Date().getFullYear(),
    monthNames: ['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'],
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
    prevMonth() { if (this.calMonth === 0) { this.calMonth = 11; this.calYear-- } else this.calMonth-- },
    nextMonth() { if (this.calMonth === 11) { this.calMonth = 0; this.calYear++ } else this.calMonth++ },
    isPast(day) { return new Date(this.calYear, this.calMonth, day) < new Date(new Date().toDateString()) },
    isSel(day) { const s = this.calYear + '-' + String(this.calMonth + 1).padStart(2,'0') + '-' + String(day).padStart(2,'0'); return s === this.checkIn || s === this.checkOut },
    isIn(day) { if (!this.checkIn || !this.checkOut) return false; const s = this.calYear + '-' + String(this.calMonth + 1).padStart(2,'0') + '-' + String(day).padStart(2,'0'); return s > this.checkIn && s < this.checkOut },
    isCI(day) { const s = this.calYear + '-' + String(this.calMonth + 1).padStart(2,'0') + '-' + String(day).padStart(2,'0'); return s === this.checkIn },
    isCO(day) { const s = this.calYear + '-' + String(this.calMonth + 1).padStart(2,'0') + '-' + String(day).padStart(2,'0'); return s === this.checkOut },
    setKota(val) { this.kota = val; this.showKota = false },
    searchUrl() {
        let url = '/search';
        const params = [];
        if (this.kota) { params.push('kota=' + encodeURIComponent(this.kota)); localStorage.setItem('nginup_kota', this.kota) }
        if (this.checkIn) params.push('check_in=' + this.checkIn);
        if (this.checkOut) params.push('check_out=' + this.checkOut);
        if (this.tamu > 1) params.push('max_tamu=' + this.tamu);
        if (params.length) url += '?' + params.join('&');
        return url;
    },
    init() {
        // tidak auto-load dari localStorage
    }
}">

    {{-- Hero --}}
    <section class="relative min-h-[500px] sm:min-h-[550px] md:min-h-[550px] bg-black flex flex-col items-center justify-center pt-14 pb-16 sm:pt-16 sm:pb-20 md:pt-20 md:pb-24 px-4 overflow-hidden">

        <img
            src="{{ asset('hero.jpg') }}"
            class="absolute inset-0 w-full h-full object-cover opacity-60"
        >

        <div class="absolute inset-0 bg-black/40"></div>

        {{-- Content Wrapper --}}
        <div class="relative z-10 w-full max-w-md flex flex-col items-center gap-6 text-center">
            <h1 class="text-white text-3xl md:text-5xl font-bold leading-snug">
                Temukan Petualangan Berikutnya
            </h1>
            {{-- Search Box --}}
            <div class="w-full relative">
                <div class="bg-white rounded-[28px] shadow-[0_10px_40px_rgba(0,0,0,0.08)] border border-gray-100 p-5 flex flex-col gap-4">

                    {{-- Destinasi --}}
                    <a href="/search" class="flex items-center gap-4 px-4 py-3.5 bg-white border border-[#F3E3DB] hover:border-brand-orange/40 rounded-[20px] transition-all duration-300 group">
                        <div class="text-brand-green/90 group-hover:text-brand-orange shrink-0"><i class="fa-solid fa-location-dot text-2xl"></i></div>
                        <div class="flex flex-col text-left">
                            <span class="text-[10px] font-bold text-[#8C6D60] uppercase tracking-wider leading-none">Destinasi</span>
                            <span class="text-[15px] md:text-base font-semibold mt-1.5 leading-none" :class="kota ? 'text-brand-orange' : 'text-brand-green'" x-text="kota || 'Mau menginap di mana?'"></span>
                        </div>
                    </a>

                    {{-- Check-In & Check-Out --}}
                    <div class="grid grid-cols-2 gap-3">
                        <div @click="openCalendar('checkIn')" class="flex items-center gap-3 px-3 py-3.5 bg-white border border-[#F3E3DB] hover:border-brand-orange/40 rounded-[20px] cursor-pointer transition-all duration-300 group">
                            <div class="text-brand-green/90 group-hover:text-brand-orange shrink-0"><i class="fa-regular fa-calendar-days text-xl"></i></div>
                            <div class="flex flex-col text-left">
                                <span class="text-[9px] md:text-[10px] font-bold text-[#8C6D60] uppercase tracking-wider leading-none">Check-In</span>
                                <span x-text="checkIn || 'Pilih Tanggal'" class="text-[13px] md:text-sm font-semibold mt-1.5 leading-none" :class="checkIn ? 'text-brand-orange' : 'text-brand-green'"></span>
                            </div>
                        </div>
                        <div @click="openCalendar('checkOut')" class="flex items-center gap-3 px-3 py-3.5 bg-white border border-[#F3E3DB] hover:border-brand-orange/40 rounded-[20px] cursor-pointer transition-all duration-300 group">
                            <div class="text-brand-green/90 group-hover:text-brand-orange shrink-0"><i class="fa-regular fa-calendar-days text-xl"></i></div>
                            <div class="flex flex-col text-left">
                                <span class="text-[9px] md:text-[10px] font-bold text-[#8C6D60] uppercase tracking-wider leading-none">Check-Out</span>
                                <span x-text="checkOut || 'Pilih Tanggal'" class="text-[13px] md:text-sm font-semibold mt-1.5 leading-none" :class="checkOut ? 'text-brand-orange' : 'text-brand-green'"></span>
                            </div>
                        </div>
                    </div>

                    {{-- Tamu --}}
                    <div class="relative" x-data="{ openTamu: false }">
                        <div @click="openTamu = !openTamu" class="flex items-center gap-4 px-4 py-3.5 bg-white border border-[#F3E3DB] hover:border-brand-orange/40 rounded-[20px] cursor-pointer transition-all duration-300 group">
                            <div class="text-brand-green/90 group-hover:text-brand-orange shrink-0"><i class="fa-solid fa-user-group text-xl"></i></div>
                            <div class="flex flex-col text-left flex-1">
                                <span class="text-[10px] font-bold text-[#8C6D60] uppercase tracking-wider leading-none">Tamu</span>
                                <span x-text="tamu + ' Tamu'" class="text-[15px] md:text-base font-semibold text-brand-green mt-1.5 leading-none"></span>
                            </div>
                            <i class="fa-solid fa-chevron-down text-xs text-gray-400 transition-transform" :class="openTamu ? 'rotate-180' : ''"></i>
                        </div>
                        <div x-show="openTamu" @click.outside="openTamu = false" class="absolute left-0 right-0 top-full mt-1 bg-white border border-gray-200 rounded-xl shadow-lg z-20 py-1">
                            <template x-for="i in 6" :key="i">
                                <button @click="tamu = i; openTamu = false"
                                    :class="tamu === i ? 'bg-brand-orange/10 text-brand-orange-hover font-bold' : 'text-gray-700 hover:bg-gray-50'"
                                    class="w-full text-left px-4 py-2.5 text-sm transition-colors"
                                    x-text="i + (i === 6 ? '+ Tamu' : ' Tamu')">
                                </button>
                            </template>
                        </div>
                    </div>

                    {{-- Calendar Popup --}}
                    <div x-show="showCalendar" @click.outside="showCalendar = false"
                         class="bg-white border border-gray-200 rounded-xl p-4 shadow-lg"
                         x-transition:enter="transition ease-out duration-150" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100">
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
                        <div class="flex justify-center gap-6 mt-3 text-xs text-gray-400 pt-2 border-t">
                            <span class="flex items-center gap-1"><span class="w-3 h-3 inline-block bg-brand-orange rounded-full"></span> Terpilih</span>
                            <span class="flex items-center gap-1"><span class="w-3 h-3 inline-block bg-brand-orange/10 rounded-full"></span> Range</span>
                        </div>
                    </div>

                    {{-- Button Cari --}}
                    <a :href="searchUrl()" class="w-full bg-brand-orange hover:bg-brand-orange-hover active:scale-[0.98] text-white py-4 rounded-[20px] font-bold flex items-center justify-center gap-2 shadow-[0_4px_14px_rgba(233,99,26,0.3)] hover:shadow-[0_6px_20px_rgba(233,99,26,0.4)] transition-all duration-300 text-center">
                        <i class="fa-solid fa-magnifying-glass text-lg"></i>
                        <span class="text-base">Cari</span>
                    </a>

                </div>
            </div>

        </div>

    </section>

    {{-- Category --}}
    @php
        $tipeIcons = [
            'apartemen' => 'building',
            'villa' => 'city',
            'hotel' => 'hotel',
            'kost' => 'door-open',
            'rumah' => 'house',
            'guesthouse' => 'umbrella-beach',
        ];
    @endphp
    <section class="px-4 sm:px-6 md:px-12 mt-10 md:mt-12">

        <div class="flex text-xs sm:text-sm text-gray-700 overflow-x-auto md:overflow-visible gap-5 sm:gap-7 pb-4 hide-scrollbar md:flex-wrap md:justify-center lg:justify-between">

            <button @click="activeType = ''" class="flex flex-col items-center min-w-[60px] sm:min-w-[70px] cursor-pointer shrink-0 group">
                <div :class="activeType === '' ? 'bg-brand-orange/10' : 'bg-gray-50'"
                     class="w-11 h-11 sm:w-12 sm:h-12 md:w-14 md:h-14 rounded-full flex items-center justify-center transition-colors">
                    <i class="fa-solid fa-th-large sm:text-lg md:text-xl" :class="activeType === '' ? 'text-brand-orange' : 'text-gray-500'"></i>
                </div>
                <p class="mt-1.5 md:mt-2 font-medium whitespace-nowrap" :class="activeType === '' ? 'text-brand-orange' : ''">Semua</p>
            </button>

            @foreach ($tipeList as $tipe)
            <button @click="activeType = activeType === '{{ $tipe }}' ? '' : '{{ $tipe }}'" class="flex flex-col items-center min-w-[60px] sm:min-w-[70px] cursor-pointer shrink-0 group">
                <div :class="activeType === '{{ $tipe }}' ? 'bg-brand-orange/20' : 'bg-gray-50'"
                     class="w-11 h-11 sm:w-12 sm:h-12 md:w-14 md:h-14 rounded-full flex items-center justify-center transition-colors">
                    <i class="fa-solid fa-{{ $tipeIcons[$tipe] ?? 'tag' }} sm:text-lg md:text-xl" :class="activeType === '{{ $tipe }}' ? 'text-brand-orange' : 'text-gray-500'"></i>
                </div>
                <p class="mt-1.5 md:mt-2 font-medium whitespace-nowrap" :class="activeType === '{{ $tipe }}' ? 'text-brand-orange' : ''">{{ ucfirst($tipe) }}</p>
            </button>
            @endforeach

        </div>

    </section>

    {{-- Properti Pilihan --}}
    <section class="px-4 sm:px-6 md:px-12 mt-10 md:mt-12 pb-12">

        <div class="flex justify-between items-end mb-6">
            <div>
                <h2 class="text-2xl md:text-3xl font-bold text-gray-800">
                    Properti Pilihan
                </h2>
                <p class="text-sm md:text-base text-gray-500 mt-2">
                    Akomodasi eksklusif dengan standar kenyamanan tinggi.
                </p>
            </div>
            <a href="/search" class="text-brand-orange font-medium hover:underline">
                Lihat Semua
            </a>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 md:gap-6">
            
            @forelse ($propertiPilihan as $item)
            <div x-show="!activeType || activeType === '{{ $item->tipe_properti }}'" class="bg-white border border-gray-200 rounded-[20px] md:rounded-[24px] overflow-hidden shadow-sm hover:shadow-md transition-shadow relative flex flex-col">
                <a href="/detail/{{ $item->id_properti }}" class="h-48 sm:h-52 md:h-56 w-full relative block">
                    <img src="{{ $item->gambar->first()->url ?? 'https://images.unsplash.com/photo-1494526585095-c41746248156?q=80&w=1200&auto=format&fit=crop' }}" class="w-full h-full object-cover">
                    <div class="absolute top-4 left-4 bg-white px-3 py-1 rounded-full flex items-center gap-1 shadow-sm">
                        <i class="fa-solid fa-circle-check text-brand-orange-hover text-xs"></i>
                        <span class="text-[10px] font-bold text-gray-800">VERIFIED HOST</span>
                    </div>
                    <button onclick="event.preventDefault(); event.stopPropagation();" class="absolute top-4 right-4 w-8 h-8 bg-black/30 backdrop-blur-sm rounded-full flex items-center justify-center text-white hover:bg-black/50 transition-colors">
                        <i class="fa-regular fa-heart"></i>
                    </button>
                </a>
                <div class="p-5 flex flex-col flex-1">
                    <div class="flex justify-between items-start mb-1">
                        <h3 class="text-xl font-bold text-gray-900">{{ $item->nama_properti }}</h3>
                        <div class="flex items-center gap-1 bg-brand-green/10 px-2 py-1 rounded-md text-sm font-medium text-[#1c333a]">
                            <i class="fa-solid fa-star text-brand-green text-[10px]"></i> {{ number_format($item->review_avg_rating ?? 0, 1) }}
                        </div>
                    </div>
                    <p class="text-sm text-gray-500 mb-4">{{ $item->kota }}, {{ $item->provinsi }}</p>
                    <div class="flex items-center gap-4 text-sm text-gray-600 mb-4">
                        <div class="flex items-center gap-1"><i class="fa-solid fa-bed"></i> {{ $item->jumlah_ruang }} Kamar</div>
                        <div class="flex items-center gap-1"><i class="fa-solid fa-user-group"></i> {{ $item->max_tamu }} Tamu</div>
                    </div>
                    <div class="mt-auto"></div>
                    <hr class="border-gray-200 mb-4">
                    <div class="flex justify-between items-center">
                        <div>
                            <span class="text-xl font-bold text-brand-orange-hover">Rp {{ number_format($item->harga_per_malam, 0, ',', '.') }}</span>
                            <span class="text-sm text-gray-500"> / malam</span>
                        </div>
                        <a href="/detail/{{ $item->id_properti }}" class="bg-brand-orange hover:bg-brand-orange-hover text-white px-5 py-2 rounded-xl font-bold transition-colors inline-block text-center">
                            Book Now
                        </a>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-span-2 text-center py-12 text-gray-500">
                Belum ada properti tersedia.
            </div>
            @endforelse

        </div>

    </section>

    {{-- Rekomendasi Untukmu --}}
    <section class="px-4 sm:px-6 md:px-12 pb-24 md:pb-12">

        <div class="mb-6">
            <h2 class="text-2xl md:text-3xl font-bold text-gray-900">
                Rekomendasi Untukmu
            </h2>
        </div>

        <div class="flex overflow-x-auto gap-4 md:gap-6 hide-scrollbar pb-4">
            
            @forelse ($rekomendasi as $item)
            <div x-show="!activeType || activeType === '{{ $item->tipe_properti }}'" class="flex-none w-[260px] sm:w-[280px] lg:w-[320px] bg-white border border-gray-200 rounded-[20px] md:rounded-[24px] overflow-hidden shadow-sm hover:shadow-md transition-shadow relative flex flex-col">
                <a href="/detail/{{ $item->id_properti }}" class="h-40 sm:h-44 md:h-48 w-full relative block">
                    <img src="{{ $item->gambar->first()->url ?? 'https://images.unsplash.com/photo-1522708323590-d24dbb6b0267?q=80&w=600&auto=format&fit=crop' }}" class="w-full h-full object-cover">
                    <div class="absolute top-4 left-4 bg-white px-3 py-1 rounded-full flex items-center gap-1 shadow-sm">
                        <i class="fa-solid fa-circle-check text-brand-orange-hover text-xs"></i>
                        <span class="text-[10px] font-bold text-gray-800">VERIFIED HOST</span>
                    </div>
                    <button onclick="event.preventDefault(); event.stopPropagation();" class="absolute top-4 right-4 w-8 h-8 bg-black/30 backdrop-blur-sm rounded-full flex items-center justify-center text-white hover:bg-black/50 transition-colors">
                        <i class="fa-regular fa-heart text-sm"></i>
                    </button>
                </a>
                <div class="p-5 flex flex-col flex-1">
                    <div class="flex justify-between items-start mb-1">
                        <h3 class="text-lg font-bold text-gray-900 truncate">{{ $item->nama_properti }}</h3>
                        <div class="flex items-center gap-1 bg-brand-green/10 px-2 py-1 rounded-md text-xs font-medium text-[#1c333a] shrink-0">
                            <i class="fa-solid fa-star text-brand-green text-[10px]"></i> {{ number_format($item->review_avg_rating ?? 0, 1) }}
                        </div>
                    </div>
                    <p class="text-sm text-gray-500 mb-4">{{ $item->kota }}, {{ $item->provinsi }}</p>
                    <div class="flex items-center gap-3 text-xs text-gray-600 mb-4">
                        <div class="flex items-center gap-1"><i class="fa-solid fa-bed"></i> {{ $item->jumlah_ruang }} Kamar</div>
                        <div class="flex items-center gap-1"><i class="fa-solid fa-user-group"></i> {{ $item->max_tamu }} Tamu</div>
                    </div>
                    <div class="mt-auto"></div>
                    <hr class="border-gray-200 mb-4">
                    <div class="flex justify-between items-center">
                        <div>
                            <span class="text-lg font-bold text-brand-orange-hover">Rp {{ number_format($item->harga_per_malam, 0, ',', '.') }}</span>
                            <span class="text-xs text-gray-500"> / malam</span>
                        </div>
                        <a href="/detail/{{ $item->id_properti }}" class="bg-brand-orange hover:bg-brand-orange-hover text-white px-4 py-2 rounded-xl text-sm font-bold transition-colors inline-block text-center">
                            Book Now
                        </a>
                    </div>
                </div>
            </div>
            @empty
            <div class="text-center py-12 text-gray-500 w-full">
                Belum ada rekomendasi tersedia.
            </div>
            @endforelse

        </div>

    </section>

</div>

@endsection