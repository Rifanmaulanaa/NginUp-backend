@extends('layouts.host', ['activeTab' => 'account'])

@section('title', 'Account - Host NginUp')
@section('page_title', 'Account')

@section('content')

<div class="max-w-2xl mx-auto pt-6">

    {{-- Profile Card --}}
    <div class="bg-white rounded-[24px] p-6 shadow-sm border border-gray-100 text-center mb-6">
        
        {{-- Default Avatar (No Image) --}}
        <div class="w-24 h-24 rounded-full bg-orange-100 flex items-center justify-center text-4xl text-brand-orange font-bold border-4 border-white shadow-sm relative mx-auto mb-4">
            {{ strtoupper(substr($user->nama, 0, 1)) }}
            <div class="absolute bottom-0 right-0 w-6 h-6 bg-brand-orange text-white rounded-full flex items-center justify-center border-2 border-white text-[10px]">
                <i class="fa-solid fa-check"></i>
            </div>
        </div>

        <h2 class="text-xl md:text-2xl font-extrabold text-gray-900 mb-2">{{ $user->nama }}</h2>
        
        <div class="inline-block px-3 py-1 bg-orange-50 border border-orange-100 text-brand-orange text-[10px] font-bold rounded-full mb-6 shadow-sm">
            @if ($propertiCount > 0) Verified Host @else Host @endif
        </div>

        {{-- Stats --}}
        <div class="flex justify-center items-center divide-x divide-gray-100 mb-8">
            <div class="px-6 flex flex-col items-center">
                <span class="text-xl font-bold text-gray-900">{{ $propertiCount }}</span>
                <span class="text-[9px] font-bold text-gray-400 tracking-wider">PROPERTIES</span>
            </div>
            <div class="px-6 flex flex-col items-center">
                <span class="text-xl font-bold text-gray-900">{{ $ulasanCount }}</span>
                <span class="text-[9px] font-bold text-gray-400 tracking-wider">REVIEWS</span>
            </div>
            <div class="px-6 flex flex-col items-center">
                <span class="text-xl font-bold text-gray-900">{{ $totalBookings }}</span>
                <span class="text-[9px] font-bold text-gray-400 tracking-wider">BOOKINGS</span>
            </div>
        </div>

        {{-- Switch Mode Button --}}
        <a href="/home" class="w-full bg-[#3c5d66] hover:bg-[#2c454c] text-white py-3.5 rounded-xl text-sm font-bold shadow-md shadow-[#3c5d66]/20 transition-all flex justify-center items-center gap-2 active:scale-[0.98]">
            <i class="fa-solid fa-right-left"></i> Switch to Traveller Mode
        </a>

    </div>

    {{-- Account Health --}}
    <div class="mb-6">
        <h3 class="text-[10px] font-bold text-gray-400 tracking-wider uppercase mb-3 px-2">Account Health</h3>
        
        <div class="grid grid-cols-2 gap-3">
            <div class="bg-white border border-gray-100 rounded-[20px] p-4 flex flex-col items-center text-center shadow-sm">
                <div class="w-8 h-8 rounded-full bg-green-50 text-green-500 flex items-center justify-center mb-3">
                    <i class="fa-solid fa-circle-check"></i>
                </div>
                <span class="text-xs font-bold text-gray-900">Identity Verified</span>
            </div>
            
            <div class="bg-white border border-gray-100 rounded-[20px] p-4 flex flex-col items-center text-center shadow-sm">
                <div class="w-8 h-8 rounded-full bg-green-50 text-green-500 flex items-center justify-center mb-3">
                    <i class="fa-solid fa-circle-check"></i>
                </div>
                <span class="text-xs font-bold text-gray-900">Host Certification</span>
            </div>
        </div>
    </div>

        {{-- Earnings Summary --}}
    <div class="mb-6">
        <h3 class="text-[10px] font-bold text-gray-400 tracking-wider uppercase mb-3 px-2">Total Earnings</h3>
        <div class="bg-white border border-gray-100 rounded-[20px] p-4 shadow-sm">
            <p class="text-[10px] text-gray-500 font-medium">Revenue from all properties</p>
            <p class="text-2xl font-extrabold text-gray-900">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</p>
        </div>
    </div>

    {{-- Payout Methods --}}
    <div class="mb-6">
        <div class="flex justify-between items-center mb-3 px-2">
            <h3 class="text-[10px] font-bold text-gray-400 tracking-wider uppercase">Payout Methods</h3>
            <button class="text-[10px] font-bold text-brand-orange hover:underline flex items-center gap-1">
                <i class="fa-solid fa-plus"></i> Add New
            </button>
        </div>
        
        <div class="space-y-3">
            {{-- Selected Method --}}
            <div class="bg-white border-2 border-brand-orange rounded-[20px] p-4 flex items-center justify-between shadow-sm cursor-pointer hover:bg-orange-50/30 transition-colors">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-xl bg-orange-50 text-brand-orange flex items-center justify-center text-xl">
                        <i class="fa-solid fa-building-columns"></i>
                    </div>
                    <div>
                        <div class="text-xs font-bold text-gray-900">Bank Transfer</div>
                        <div class="text-[10px] text-gray-500">Ending in •••• 4291</div>
                    </div>
                </div>
                <div class="text-brand-orange text-lg">
                    <i class="fa-solid fa-circle-check"></i>
                </div>
            </div>

            {{-- Unselected Method --}}
            <div class="bg-white border border-gray-100 rounded-[20px] p-4 flex items-center justify-between shadow-sm cursor-pointer hover:border-gray-200 transition-colors">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-xl bg-gray-50 text-gray-600 flex items-center justify-center text-xl">
                        <i class="fa-solid fa-wallet"></i>
                    </div>
                    <div>
                        <div class="text-xs font-bold text-gray-900">E-wallet</div>
                        <div class="text-[10px] text-gray-500">gibran.pay</div>
                    </div>
                </div>
                <div class="w-5 h-5 rounded-full border-2 border-gray-300"></div>
            </div>
        </div>
    </div>

    {{-- Settings Menu --}}
    <div class="bg-white border border-gray-100 rounded-[24px] overflow-hidden shadow-sm mb-6">
        <ul class="flex flex-col divide-y divide-gray-100">
            <li class="hover:bg-gray-50 transition-colors cursor-pointer group">
                <a href="#" class="flex items-center justify-between p-4">
                    <div class="flex items-center gap-4">
                        <i class="fa-regular fa-user w-5 text-center text-gray-500 group-hover:text-brand-orange transition-colors"></i>
                        <span class="text-sm font-semibold text-gray-700">Personal Info</span>
                    </div>
                    <i class="fa-solid fa-chevron-right text-[10px] text-gray-400 group-hover:text-brand-orange transition-colors"></i>
                </a>
            </li>
            <li class="hover:bg-gray-50 transition-colors cursor-pointer group">
                <a href="#" class="flex items-center justify-between p-4">
                    <div class="flex items-center gap-4">
                        <i class="fa-solid fa-shield-halved w-5 text-center text-gray-500 group-hover:text-brand-orange transition-colors"></i>
                        <span class="text-sm font-semibold text-gray-700">Security (Password & 2FA)</span>
                    </div>
                    <i class="fa-solid fa-chevron-right text-[10px] text-gray-400 group-hover:text-brand-orange transition-colors"></i>
                </a>
            </li>
            <li class="hover:bg-gray-50 transition-colors cursor-pointer group">
                <a href="#" class="flex items-center justify-between p-4">
                    <div class="flex items-center gap-4">
                        <i class="fa-regular fa-bell w-5 text-center text-gray-500 group-hover:text-brand-orange transition-colors"></i>
                        <span class="text-sm font-semibold text-gray-700">Notifications</span>
                    </div>
                    <i class="fa-solid fa-chevron-right text-[10px] text-gray-400 group-hover:text-brand-orange transition-colors"></i>
                </a>
            </li>
            <li class="hover:bg-gray-50 transition-colors cursor-pointer group">
                <a href="#" class="flex items-center justify-between p-4">
                    <div class="flex items-center gap-4">
                        <i class="fa-solid fa-globe w-5 text-center text-gray-500 group-hover:text-brand-orange transition-colors"></i>
                        <div class="flex flex-col">
                            <span class="text-sm font-semibold text-gray-700">Language</span>
                            <span class="text-[10px] text-gray-400">English US</span>
                        </div>
                    </div>
                    <i class="fa-solid fa-chevron-right text-[10px] text-gray-400 group-hover:text-brand-orange transition-colors"></i>
                </a>
            </li>
            <li class="hover:bg-gray-50 transition-colors cursor-pointer group">
                <a href="#" class="flex items-center justify-between p-4">
                    <div class="flex items-center gap-4">
                        <i class="fa-solid fa-gavel w-5 text-center text-gray-500 group-hover:text-brand-orange transition-colors"></i>
                        <span class="text-sm font-semibold text-gray-700">Terms of Service</span>
                    </div>
                    <i class="fa-solid fa-chevron-right text-[10px] text-gray-400 group-hover:text-brand-orange transition-colors"></i>
                </a>
            </li>
            <li class="hover:bg-gray-50 transition-colors cursor-pointer group">
                <a href="#" class="flex items-center justify-between p-4">
                    <div class="flex items-center gap-4">
                        <i class="fa-regular fa-circle-question w-5 text-center text-gray-500 group-hover:text-brand-orange transition-colors"></i>
                        <span class="text-sm font-semibold text-gray-700">Support</span>
                    </div>
                    <i class="fa-solid fa-chevron-right text-[10px] text-gray-400 group-hover:text-brand-orange transition-colors"></i>
                </a>
            </li>
        </ul>
    </div>

    {{-- Logout Button --}}
    <form method="POST" action="/logout">
        @csrf
        <button type="submit" class="w-full bg-red-100 hover:bg-red-200 text-red-600 py-4 rounded-[20px] text-sm font-bold transition-all flex justify-center items-center gap-2 active:scale-[0.98] mb-6">
            <i class="fa-solid fa-arrow-right-from-bracket"></i> Logout from NginUp
        </button>
    </form>

    {{-- App Version --}}
    <div class="text-center text-[10px] text-gray-400 font-medium pb-4">
        Version 2.4.1 (Build 108)
    </div>

</div>

@endsection
