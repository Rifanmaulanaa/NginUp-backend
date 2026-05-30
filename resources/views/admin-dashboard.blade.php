@extends('layouts.admin', ['activeTab' => $activeTab ?? 'dashboard'])

@section('title', 'Admin - Laporan Platform')

@section('content')

<div class="w-full space-y-6">
    
    <!-- Header Area -->
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Laporan Platform</h1>
        <p class="text-sm text-gray-500 mt-1">Analisis performa bisnis dan sistem real-time.</p>
    </div>

    <!-- Toggle Buttons -->
    <div class="bg-white p-1.5 rounded-full flex gap-1 inline-flex shadow-sm border border-gray-100">
        <button class="px-6 py-2 bg-[#E9631A] text-white text-sm font-semibold rounded-full shadow-sm">
            Bulanan
        </button>
        <button class="px-6 py-2 text-gray-500 hover:text-gray-700 hover:bg-gray-50 text-sm font-medium rounded-full transition-colors">
            Mingguan
        </button>
        <button class="px-6 py-2 text-gray-500 hover:text-gray-700 hover:bg-gray-50 text-sm font-medium rounded-full transition-colors">
            Harian
        </button>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

        <!-- Trend Pendapatan -->
        <div class="bg-white rounded-3xl p-6 shadow-sm border border-gray-100 flex flex-col justify-between">
            <div class="flex justify-between items-center mb-6">
                <h3 class="font-bold text-gray-800 text-sm">Trend Pendapatan</h3>
                <i class="fa-solid fa-ellipsis-vertical text-gray-400"></i>
            </div>
            
            <!-- Chart -->
            <div class="h-32 bg-[#F4F7FF] rounded-2xl flex items-end justify-center gap-3 p-4 mb-6">
                @isset($monthlyRevenue)
                @php $maxRevenue = $monthlyRevenue->max('total') ?: 1; @endphp
                @forelse ($monthlyRevenue as $m)
                    @php $pct = ($m->total / $maxRevenue) * 100; @endphp
                    <div class="flex flex-col items-center gap-1">
                        <div class="w-10 rounded-t-lg transition-all duration-500"
                             style="height: {{ max($pct, 4) }}%; background: {{ $loop->even ? '#E9631A' : '#FFC5A8' }};"></div>
                        <span class="text-[8px] text-gray-400 font-medium mt-1">{{ substr($m->bulan, 5, 2) }}/{{ substr($m->bulan, 2, 2) }}</span>
                    </div>
                @empty
                    <span class="text-xs text-gray-400">Belum ada data pendapatan</span>
                @endforelse
                @endisset
            </div>

            <div class="grid grid-cols-3 gap-3">
                <div class="bg-[#F4F7FF] rounded-xl p-3 flex flex-col justify-center">
                    <span class="text-[10px] text-gray-500 font-medium mb-1">Total Revenue</span>
                    <span class="text-sm font-bold text-[#E9631A]">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</span>
                </div>
                <div class="bg-[#F4F7FF] rounded-xl p-3 flex flex-col justify-center">
                    <span class="text-[10px] text-gray-500 font-medium mb-1">Booking</span>
                    <span class="text-sm font-bold text-[#E9631A]">{{ number_format($totalBookings) }}</span>
                </div>
                <div class="bg-[#EAF3FF] rounded-xl p-3 flex flex-col justify-center">
                    <span class="text-[10px] text-gray-500 font-medium mb-1">Platform Fee</span>
                    <span class="text-sm font-bold text-blue-600">Rp {{ number_format($platformRevenue, 0, ',', '.') }}</span>
                </div>
            </div>
        </div>

        <!-- Sistem Performa -->
        <div class="bg-[#242A38] rounded-3xl p-6 shadow-md border border-[#3A4150] flex flex-col">
            <div class="flex justify-between items-center mb-6">
                <h3 class="font-bold text-white text-sm">Sistem Performa</h3>
                <i class="fa-solid fa-server text-gray-400"></i>
            </div>

            <div class="space-y-5 mb-auto">
                <!-- Uptime Server -->
                <div>
                    <div class="flex justify-between text-xs mb-2">
                        <span class="text-gray-300">Uptime Server</span>
                        <span class="text-white font-medium">99.98%</span>
                    </div>
                    <div class="w-full bg-gray-700 rounded-full h-1.5">
                        <div class="bg-[#FF9B6A] h-1.5 rounded-full" style="width: 99%"></div>
                    </div>
                </div>
                
                <!-- API Latency -->
                <div>
                    <div class="flex justify-between text-xs mb-2">
                        <span class="text-gray-300">API Latency</span>
                        <span class="text-white font-medium">43ms</span>
                    </div>
                    <div class="w-full bg-gray-700 rounded-full h-1.5">
                        <div class="bg-blue-400 h-1.5 rounded-full" style="width: 30%"></div>
                    </div>
                </div>

                <!-- Storage Used -->
                <div>
                    <div class="flex justify-between text-xs mb-2">
                        <span class="text-gray-300">Storage Used</span>
                        <span class="text-white font-medium">64%</span>
                    </div>
                    <div class="w-full bg-gray-700 rounded-full h-1.5">
                        <div class="bg-white h-1.5 rounded-full" style="width: 64%"></div>
                    </div>
                </div>
            </div>

            <button class="w-full mt-6 py-3 bg-[#C05014] hover:bg-[#A64511] text-white text-sm font-semibold rounded-xl transition-colors">
                Lihat Detail Server
            </button>
        </div>

        <!-- Tren Pertumbuhan -->
        <div class="bg-white rounded-3xl p-6 shadow-sm border border-gray-100">
            <div class="flex justify-between items-center mb-6">
                <h3 class="font-bold text-gray-800 text-sm">Tren Pertumbuhan</h3>
                <div class="flex gap-1">
                    <div class="w-2 h-2 rounded-full bg-[#E9631A]"></div>
                    <div class="w-2 h-2 rounded-full bg-[#E9631A]/50"></div>
                    <div class="w-2 h-2 rounded-full bg-blue-600"></div>
                </div>
            </div>

                    <div class="space-y-4">
                <div class="flex items-center gap-4 p-4 rounded-2xl bg-white border border-gray-50 shadow-sm hover:shadow-md transition-shadow">
                    <div class="w-12 h-12 rounded-xl bg-[#FFEDDF] text-[#E9631A] flex items-center justify-center">
                        <i class="fa-solid fa-users"></i>
                    </div>
                    <div class="flex-1">
                        <h4 class="text-xs font-bold text-gray-800">Total User</h4>
                        <p class="text-[10px] text-gray-500">{{ number_format($totalUsers) }} terdaftar</p>
                    </div>
                    <div class="text-xs font-bold text-[#E9631A] flex items-center gap-1">
                        {{ $totalTravelers }} Traveler
                    </div>
                </div>

                <div class="flex items-center gap-4 p-4 rounded-2xl bg-white border border-gray-50 shadow-sm hover:shadow-md transition-shadow">
                    <div class="w-12 h-12 rounded-xl bg-cyan-50 text-cyan-600 flex items-center justify-center">
                        <i class="fa-solid fa-shop"></i>
                    </div>
                    <div class="flex-1">
                        <h4 class="text-xs font-bold text-gray-800">Total Owner</h4>
                        <p class="text-[10px] text-gray-500">{{ number_format($totalOwners) }} host terdaftar</p>
                    </div>
                    <div class="text-xs font-bold text-cyan-600 flex items-center gap-1">
                        <i class="fa-solid fa-arrow-trend-up"></i>
                    </div>
                </div>

                <div class="flex items-center gap-4 p-4 rounded-2xl bg-white border border-gray-50 shadow-sm hover:shadow-md transition-shadow">
                    <div class="w-12 h-12 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center">
                        <i class="fa-solid fa-building"></i>
                    </div>
                    <div class="flex-1">
                        <h4 class="text-xs font-bold text-gray-800">Properti</h4>
                        <p class="text-[10px] text-gray-500">{{ number_format($totalProperti) }} unit terdaftar</p>
                    </div>
                    <div class="text-xs font-bold text-blue-600 flex items-center gap-1">
                        <i class="fa-solid fa-arrow-trend-up"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Verifikasi Properti -->
        <div class="bg-white rounded-3xl p-6 shadow-sm border border-gray-100 flex flex-col">
            <div class="flex justify-between items-center mb-6">
                <h3 class="font-bold text-gray-800 text-sm">Verifikasi Properti</h3>
                <i class="fa-solid fa-clipboard-check text-blue-500"></i>
            </div>

            @php
                $verifiedCount = \App\Models\Properti::where('verified_status', 'verified')->count();
                $resolvedPct = $totalProperti > 0 ? round(($verifiedCount / $totalProperti) * 100) : 0;
            @endphp

            <div class="flex gap-4 h-full">
                <div class="flex-1 bg-[#FFF5F5] border border-red-100 rounded-2xl flex flex-col items-center justify-center p-6 text-center">
                    <span class="text-4xl font-bold text-red-500 mb-1">{{ $pendingVerification ?? 0 }}</span>
                    <span class="text-xs font-medium text-red-500">Menunggu<br>Verifikasi</span>
                </div>
                <div class="flex-1 flex flex-col gap-4">
                    <div class="flex-1 bg-[#F4F7FF] rounded-2xl flex flex-col justify-center p-4">
                        <span class="text-[11px] text-gray-500 font-medium mb-1">Terverifikasi</span>
                        <div class="flex items-end gap-2">
                            <span class="text-lg font-bold text-gray-800">{{ $resolvedPct }}%</span>
                        </div>
                    </div>
                    <div class="flex-1 bg-[#F4F7FF] rounded-2xl flex flex-col justify-center p-4">
                        <span class="text-[11px] text-gray-500 font-medium mb-1">Total Properti</span>
                        <span class="text-lg font-bold text-gray-800">{{ $totalProperti }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sebaran Reserved Regional -->
        @isset($regionalData)
        <div class="bg-white rounded-3xl p-6 shadow-sm border border-gray-100 lg:col-span-2">
            <h3 class="font-bold text-gray-800 text-sm mb-4">Sebaran Reservasi Regional</h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div class="relative h-64 bg-slate-800 rounded-2xl overflow-hidden group">
                    <div class="absolute inset-0 bg-gradient-to-br from-slate-700 to-slate-900 opacity-80"></div>
                    <div class="absolute inset-0 flex items-center justify-center opacity-40 group-hover:opacity-60 transition-opacity">
                        <i class="fa-solid fa-map text-white text-6xl"></i>
                    </div>
                    @if ($regionalData->isNotEmpty())
                        @php $top = $regionalData->first(); @endphp
                        <div class="absolute bottom-1/3 left-1/2 w-4 h-4 bg-[#FFC5A8] rounded-full animate-ping"></div>
                        <div class="absolute bottom-1/3 left-1/2 w-4 h-4 bg-[#E9631A] rounded-full"></div>
                        <div class="absolute bottom-4 left-4 bg-white/90 backdrop-blur-sm px-4 py-3 rounded-xl shadow-lg border border-white/20">
                            <span class="text-[10px] text-gray-500 block mb-0.5">Hotspot Teraktif</span>
                            <span class="text-sm font-bold text-[#E9631A]">{{ $top->kota }}, {{ $top->provinsi }}</span>
                        </div>
                    @else
                        <div class="absolute bottom-4 left-4 bg-white/90 backdrop-blur-sm px-4 py-3 rounded-xl shadow-lg border border-white/20">
                            <span class="text-[10px] text-gray-500 block mb-0.5">Hotspot Teraktif</span>
                            <span class="text-sm font-bold text-gray-400">Belum ada data</span>
                        </div>
                    @endif
                </div>

                <div class="flex flex-col justify-center">
                    <h4 class="text-xs font-medium text-gray-400 mb-5 uppercase tracking-wider">Top {{ min($regionalData->count(), 5) }} Lokasi</h4>
                    
                    <div class="space-y-5">
                        @forelse ($regionalData as $i => $r)
                            @php
                                $pct = $totalRegional > 0 ? round(($r->total / $totalRegional) * 100) : 0;
                                $colors = ['#E9631A', '#06B6D4', '#3B82F6', '#8B5CF6', '#A84A1A'];
                                $bgColors = ['#FFEDDF', '#CFFAFE', '#DBEAFE', '#EDE9FE', '#FEE2D6'];
                                $textColors = ['text-[#E9631A]', 'text-cyan-600', 'text-blue-600', 'text-purple-600', 'text-[#A84A1A]'];
                            @endphp
                            <div>
                                <div class="flex justify-between text-xs mb-2">
                                    <span class="text-gray-700 font-medium">{{ $r->kota }}{{ $r->provinsi ? ', ' . $r->provinsi : '' }}</span>
                                    <span class="{{ $textColors[$i % 5] }} font-bold {{ $bgColors[$i % 5] }} px-2 py-0.5 rounded text-[10px]">{{ $pct }}%</span>
                                </div>
                                <div class="w-full bg-gray-100 rounded-full h-1.5">
                                    <div class="h-1.5 rounded-full transition-all duration-500" style="width: {{ $pct }}%; background: {{ $colors[$i % 5] }}"></div>
                                </div>
                            </div>
                        @empty
                            <p class="text-sm text-gray-400 text-center py-8">Belum ada reservasi</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
        @endisset

    </div>
</div>

@endsection
