@extends('layouts.host', ['activeTab' => 'dashboard'])

@section('title', 'Dashboard - Host NginUp')
@section('page_title', '')

@section('content')

<div class="max-w-4xl mx-auto pt-6">

    {{-- Session Messages --}}
    @if (session('success'))
        <div class="mb-4 p-4 bg-green-50 border border-green-200 text-green-700 rounded-2xl text-sm">{{ session('success') }}</div>
    @endif
    @if (session('error'))
        <div class="mb-4 p-4 bg-red-50 border border-red-200 text-red-700 rounded-2xl text-sm">{{ session('error') }}</div>
    @endif

    {{-- Welcome Header --}}
    <div class="mb-6">
        <h1 class="text-2xl md:text-3xl font-bold text-[#1a2b3c] mb-1">Welcome back, {{ Auth::user()->nama }}!</h1>
        <p class="text-sm md:text-base text-gray-500">Check your performance for today.</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-8">
        
        {{-- Total Revenue Card --}}
        <div class="bg-white rounded-[24px] p-6 shadow-sm border border-gray-100 flex flex-col justify-between">
            <div>
                <h3 class="text-[10px] font-bold text-gray-400 tracking-wider uppercase mb-2">Total Revenue</h3>
                <div class="flex justify-between items-end">
                    <div>
                        <div class="text-3xl font-bold text-[#1a2b3c] mb-1">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</div>
                    </div>
                    <div class="flex items-end gap-1.5 h-10 mb-1">
                        <div class="w-3 bg-brand-orange/20 rounded-sm h-[40%]"></div>
                        <div class="w-3 bg-brand-orange/40 rounded-sm h-[60%]"></div>
                        <div class="w-3 bg-brand-orange/70 rounded-sm h-[50%]"></div>
                        <div class="w-3 bg-brand-orange rounded-sm h-[100%] shadow-sm shadow-brand-orange/30"></div>
                    </div>
                </div>
            </div>
            
            <div class="mt-6 pt-4 border-t border-gray-100 text-center">
                <a href="/host/reports" class="text-xs font-bold text-brand-orange hover:text-brand-orange-hover transition-colors inline-flex items-center gap-1">
                    View Detailed Report <i class="fa-solid fa-chevron-right text-[10px]"></i>
                </a>
            </div>
        </div>

        <div class="flex flex-col gap-4">
            {{-- Active Bookings Card --}}
            <div class="bg-white rounded-[24px] p-6 shadow-sm border border-gray-100">
                <h3 class="text-[10px] font-bold text-gray-400 tracking-wider uppercase mb-2">Active Bookings</h3>
                <div class="text-3xl font-bold text-[#1a2b3c] mb-4">{{ $activeBookings }}</div>
            </div>

            {{-- 2 Small Cards Row --}}
            <div class="grid grid-cols-2 gap-4">
                {{-- Rating Card --}}
                <div class="bg-white rounded-[24px] p-5 shadow-sm border border-gray-100 flex flex-col justify-between">
                    <h3 class="text-[10px] font-bold text-gray-400 tracking-wider uppercase mb-1">Rating</h3>
                    <div class="text-2xl font-bold text-[#1a2b3c] flex items-center gap-1 mb-3">
                        {{ number_format($avgRating, 2) }} <i class="fa-solid fa-star text-brand-orange text-sm"></i>
                    </div>
                </div>

                {{-- Active Units Card --}}
                    <div class="bg-white rounded-[24px] p-5 shadow-sm border border-gray-100 flex flex-col justify-between">
                        <h3 class="text-[10px] font-bold text-gray-400 tracking-wider uppercase mb-1">Active Units</h3>
                        <div class="text-2xl font-bold text-[#1a2b3c] mb-3">{{ $propertiCount }}</div>
                </div>
            </div>
        </div>

    </div>

    {{-- Active Reservations --}}
    <div class="mb-8">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-base font-bold text-gray-800">Reservasi Terbaru</h2>
        </div>

        <div class="flex flex-col gap-3">
            @forelse ($recentBookings as $b)
            <div class="bg-white p-4 rounded-[20px] shadow-sm border border-gray-100 flex items-center justify-between hover:border-brand-orange/30 transition-colors group">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full bg-brand-green/10 flex items-center justify-center text-brand-green font-bold text-sm shrink-0">
                        {{ strtoupper(substr($b->user->nama ?? '?', 0, 1)) }}
                    </div>
                    <div>
                        <h4 class="font-bold text-gray-900 text-sm">{{ $b->user->nama ?? 'Guest' }}</h4>
                        <p class="text-[10px] text-gray-500">{{ $b->properti->nama_properti ?? '-' }} • {{ \Carbon\Carbon::parse($b->tanggal_check_in)->format('d M') }} - {{ \Carbon\Carbon::parse($b->tanggal_check_out)->format('d M') }}</p>
                    </div>
                </div>
                <div class="flex items-center gap-3">
                    <span class="px-2.5 py-1 text-[9px] font-bold rounded-full
                        @if($b->status_pemesanan === 'confirmed') bg-blue-100 text-blue-600
                        @elseif($b->status_pemesanan === 'pending') bg-yellow-100 text-yellow-600
                        @elseif($b->status_pemesanan === 'cancelled') bg-red-100 text-red-600
                        @else bg-green-100 text-green-700 @endif">
                        {{ ucfirst($b->status_pemesanan) }}
                    </span>
                </div>
            </div>
            @empty
            <div class="text-center py-8 text-gray-500 text-sm">
                Belum ada reservasi untuk properti Anda.
            </div>
            @endforelse
        </div>
    </div>

    {{-- Pending Tasks --}}
    <div class="mb-8">
        <div class="bg-white rounded-[24px] p-6 shadow-sm border border-gray-100">
            <h3 class="text-[10px] font-bold text-[#8ba3b0] tracking-wider uppercase mb-5">PENDING TASKS</h3>
            
            <div class="flex flex-col gap-5">
                {{-- Task 1 --}}
                <div class="flex items-center justify-between cursor-pointer group">
                    <div class="flex items-center gap-4">
                        <div class="w-10 h-10 rounded-full bg-red-100 text-red-500 flex items-center justify-center">
                            <i class="fa-solid fa-file-invoice"></i>
                        </div>
                        <span class="text-sm font-semibold text-gray-800 group-hover:text-brand-orange transition-colors">Approve booking request</span>
                    </div>
                    <div class="w-2 h-2 rounded-full bg-brand-orange"></div>
                </div>

                {{-- Task 2 --}}
                <div class="flex items-center justify-between cursor-pointer group">
                    <div class="flex items-center gap-4">
                        <div class="w-10 h-10 rounded-full bg-blue-100 text-blue-400 flex items-center justify-center">
                            <i class="fa-regular fa-comment-dots"></i>
                        </div>
                        <span class="text-sm font-semibold text-gray-800 group-hover:text-brand-orange transition-colors">Reply guest messages</span>
                    </div>
                    <div class="bg-brand-orange text-white text-[10px] font-bold w-5 h-5 rounded-full flex items-center justify-center">3</div>
                </div>

                {{-- Task 3 (Disabled) --}}
                <div class="flex items-center justify-between opacity-50 cursor-not-allowed">
                    <div class="flex items-center gap-4">
                        <div class="w-10 h-10 rounded-full bg-gray-100 text-gray-400 flex items-center justify-center">
                            <i class="fa-solid fa-shield-halved"></i>
                        </div>
                        <span class="text-sm font-semibold text-gray-500">Complete verification</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

@endsection
