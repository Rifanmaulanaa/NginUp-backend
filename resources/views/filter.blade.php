<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    @vite('resources/css/app.css')
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <title>Filter Pencarian</title>
</head>
<body class="bg-gray-50 text-[#1A1F2B]">

<div class="w-full bg-white min-h-screen flex flex-col relative shadow-sm"
     x-data="{
        checkIn: '', checkOut: '', tamu: 1,
        showCalendar: false,
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
        prevMonth() { if (this.calMonth === 0) { this.calMonth = 11; this.calYear--; } else this.calMonth-- },
        nextMonth() { if (this.calMonth === 11) { this.calMonth = 0; this.calYear++; } else this.calMonth++ },
        isPast(day) { return new Date(this.calYear, this.calMonth, day) < new Date(new Date().toDateString()) },
        isSel(day) { const s = this.calYear + '-' + String(this.calMonth + 1).padStart(2,'0') + '-' + String(day).padStart(2,'0'); return s === this.checkIn || s === this.checkOut },
        isIn(day) { if (!this.checkIn || !this.checkOut) return false; const s = this.calYear + '-' + String(this.calMonth + 1).padStart(2,'0') + '-' + String(day).padStart(2,'0'); return s > this.checkIn && s < this.checkOut },
        isCI(day) { const s = this.calYear + '-' + String(this.calMonth + 1).padStart(2,'0') + '-' + String(day).padStart(2,'0'); return s === this.checkIn },
        isCO(day) { const s = this.calYear + '-' + String(this.calMonth + 1).padStart(2,'0') + '-' + String(day).padStart(2,'0'); return s === this.checkOut }
    }">

    {{-- Header --}}
    <header class="flex justify-between items-center px-4 md:px-8 py-4 border-b bg-white sticky top-0 z-20">
        <div class="flex items-center gap-4">
            <a href="javascript:history.back()" class="text-brand-orange-hover hover:bg-brand-orange/10 w-10 h-10 flex items-center justify-center rounded-full transition-colors">
                <i class="fa-solid fa-arrow-left text-lg"></i>
            </a>
            <h1 class="text-lg font-semibold">Filter Pencarian</h1>
        </div>
    </header>

    {{-- Content --}}
    <div class="flex-1 overflow-y-auto px-4 md:px-8 py-6 pb-32">
        <div class="max-w-md mx-auto space-y-6">

            {{-- Check-In / Check-Out --}}
            <div class="flex gap-3">
                <div class="flex-1 border border-gray-200 rounded-xl p-3 cursor-pointer" @click="openCalendar('checkIn')">
                    <p class="text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-1">Check-In</p>
                    <div class="flex items-center gap-1">
                        <span x-text="checkIn || 'Pilih Tanggal'" class="text-sm font-medium"
                            :class="checkIn ? 'text-gray-800' : 'text-gray-500'"></span>
                    </div>
                </div>
                <div class="flex-1 border border-gray-200 rounded-xl p-3 cursor-pointer" @click="openCalendar('checkOut')">
                    <p class="text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-1">Check-Out</p>
                    <div class="flex items-center gap-1">
                        <span x-text="checkOut || 'Pilih Tanggal'" class="text-sm font-medium"
                            :class="checkOut ? 'text-gray-800' : 'text-gray-500'"></span>
                    </div>
                </div>
            </div>

            {{-- Calendar Popup --}}
            <div x-show="showCalendar" @click.outside="showCalendar = false" class="bg-white border border-gray-200 rounded-xl p-4 shadow-lg">
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

            {{-- Tamu --}}
            <div class="relative" x-data="{ openTamu: false }">
                <label class="text-xs font-bold text-gray-700 block mb-2">Jumlah Tamu</label>
                <div class="flex items-center gap-1 border border-gray-200 rounded-xl px-4 py-3 cursor-pointer" @click="openTamu = !openTamu">
                    <input type="number" x-model.number="tamu" :min="1" max="6"
                        placeholder="Jumlah Tamu"
                        class="flex-1 text-sm text-gray-800 border-0 p-0 focus:ring-0 outline-none bg-transparent"
                        @click.stop="openTamu = !openTamu">
                    <i class="fa-solid fa-chevron-down text-xs text-gray-400 transition-transform" :class="openTamu ? 'rotate-180' : ''"></i>
                </div>
                <div x-show="openTamu" @click.outside="openTamu = false" class="absolute left-0 right-0 top-full mt-1 bg-white border border-gray-200 rounded-xl shadow-lg z-10 py-1">
                    <template x-for="i in 6" :key="i">
                        <button @click="tamu = i; openTamu = false"
                            :class="tamu === i ? 'bg-brand-orange/10 text-brand-orange-hover font-bold' : 'text-gray-700 hover:bg-gray-50'"
                            class="w-full text-left px-4 py-2.5 text-sm transition-colors"
                            x-text="i + (i === 6 ? '+ Tamu' : ' Tamu')">
                        </button>
                    </template>
                </div>
            </div>

            {{-- Preview --}}
            <template x-if="checkIn && checkOut">
                <div class="bg-orange-50 rounded-2xl p-5 border border-orange-100 text-sm">
                    <p class="font-bold text-gray-900 mb-1">Ringkasan</p>
                    <p class="text-gray-600">
                        <span x-text="new Date(checkIn).toLocaleDateString('id-ID', {day:'numeric',month:'short',year:'numeric'})"></span>
                        —
                        <span x-text="new Date(checkOut).toLocaleDateString('id-ID', {day:'numeric',month:'short',year:'numeric'})"></span>
                    </p>
                    <p class="text-gray-500 text-xs mt-1" x-text="tamu + ' Tamu'"></p>
                </div>
            </template>
        </div>
    </div>

    {{-- Bottom Action Bar --}}
    <div class="fixed bottom-0 left-0 right-0 bg-white border-t p-4 z-20">
        <a :href="'/search?check_in=' + checkIn + '&check_out=' + checkOut + '&max_tamu=' + tamu"
           class="block w-full bg-brand-orange hover:bg-brand-orange-hover text-white font-medium py-4 rounded-xl transition-colors shadow-lg text-center">
            Terapkan Filter
        </a>
    </div>

</div>

</body>
</html>
