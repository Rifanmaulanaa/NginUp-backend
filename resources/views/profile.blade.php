@extends('layouts.app', ['activeTab' => 'profile'])

@section('title', 'Profile - NginUp')

@section('content')

    <div class="md:px-12 py-6 max-w-2xl mx-auto w-full pb-24 md:pb-6 bg-[#F8F9FA] md:bg-transparent min-h-screen">


        {{-- Profile Header (Avatar & Name) --}}
        <div class="flex flex-col items-center mb-8 px-4">
            <div class="relative mb-4">
                <div class="w-24 h-24 rounded-full overflow-hidden border-4 border-white shadow-md">
                    <div class="w-full h-full bg-brand-green/10 flex items-center justify-center text-brand-green text-3xl font-bold">
                        {{ strtoupper(substr($user->nama, 0, 1)) }}
                    </div>
                </div>
                <div class="absolute -bottom-2 left-1/2 -translate-x-1/2 bg-brand-orange text-white text-[10px] font-bold px-3 py-1 rounded-full whitespace-nowrap flex items-center gap-1 shadow-sm border border-white">
                    <i class="fa-solid fa-trophy text-[9px]"></i> {{ ucfirst($user->role) }} Member
                </div>
            </div>
            
            <h2 class="text-xl font-bold text-gray-900">{{ $user->nama }}</h2>
            <p class="text-xs text-gray-500 mt-1">{{ $user->email }}</p>
        </div>

        {{-- Stats Row --}}
        <div class="grid grid-cols-3 gap-3 mb-8 px-4">
            <div class="bg-blue-50/50 border border-blue-50/50 rounded-2xl p-4 flex flex-col items-center justify-center text-center">
                <i class="fa-solid fa-pen text-brand-orange mb-2 text-sm"></i>
                <span class="text-lg font-bold text-gray-900">{{ $ulasanCount }}</span>
                <span class="text-[10px] text-gray-500 font-medium">Ulasan</span>
            </div>
            <div class="bg-blue-50/50 border border-blue-50/50 rounded-2xl p-4 flex flex-col items-center justify-center text-center">
                <i class="fa-regular fa-calendar-check text-brand-orange mb-2 text-sm"></i>
                <span class="text-lg font-bold text-gray-900">{{ $bookingCount }}</span>
                <span class="text-[10px] text-gray-500 font-medium">Booking</span>
            </div>
            <div class="bg-blue-50/50 border border-blue-50/50 rounded-2xl p-4 flex flex-col items-center justify-center text-center">
                <i class="fa-solid fa-coins text-brand-orange mb-2 text-sm"></i>
                <span class="text-lg font-bold text-gray-900">{{ $bookingCount * 50 }}</span>
                <span class="text-[10px] text-gray-500 font-medium">Poin</span>
            </div>
        </div>

        {{-- Program Loyalitas --}}
        <div class="px-4 mb-8">
            <h3 class="text-sm font-semibold text-gray-700 mb-3">Program Loyalitas</h3>
            <div class="bg-gradient-to-br from-[#213c44] to-[#1c333a] rounded-[24px] p-6 text-white relative overflow-hidden shadow-lg shadow-brand-green/20">
                <div class="absolute right-6 top-6 text-white/30 text-2xl">
                    <i class="fa-solid fa-wallet"></i>
                </div>
                
                <p class="text-xs text-brand-green/60 font-medium tracking-wide mb-1 text-gray-300">Saldo Cashback</p>
                <h3 class="text-2xl font-bold mb-6 tracking-tight">Rp 2.450.000</h3>
                
                <a href="/rewards" class="w-full bg-brand-orange hover:bg-brand-orange-hover active:scale-[0.98] text-white py-3.5 rounded-xl font-bold flex justify-center items-center gap-2 transition-all text-sm shadow-md shadow-brand-orange/20">
                    View Rewards <i class="fa-solid fa-arrow-right text-xs"></i>
                </a>
            </div>
        </div>

        {{-- Pengaturan --}}
        <div class="px-4 mb-8">
            <h3 class="text-sm font-semibold text-gray-700 mb-3">Pengaturan</h3>
            
            <div class="bg-white rounded-[24px] border border-gray-100 shadow-sm overflow-hidden flex flex-col">
                
                {{-- Item 1: Pesan --}}
                <a href="#" class="flex items-center justify-between p-4 border-b border-gray-50 hover:bg-gray-50 transition-colors">
                    <div class="flex items-center gap-4">
                        <div class="w-10 h-10 rounded-full bg-blue-50 text-blue-500 flex items-center justify-center">
                            <i class="fa-regular fa-envelope"></i>
                        </div>
                        <span class="text-sm font-medium text-gray-800">Pesan (Inbox)</span>
                    </div>
                    <div class="flex items-center gap-3">
                        <span class="bg-red-600 text-white text-[10px] font-bold w-5 h-5 flex items-center justify-center rounded-full">3</span>
                        <i class="fa-solid fa-chevron-right text-xs text-gray-400"></i>
                    </div>
                </a>

                {{-- Item 2: Personal Info --}}
                <a href="#" class="flex items-center justify-between p-4 border-b border-gray-50 hover:bg-gray-50 transition-colors">
                    <div class="flex items-center gap-4">
                        <div class="w-10 h-10 rounded-full bg-blue-50 text-blue-500 flex items-center justify-center">
                            <i class="fa-regular fa-user"></i>
                        </div>
                        <span class="text-sm font-medium text-gray-800">Personal Info</span>
                    </div>
                    <i class="fa-solid fa-chevron-right text-xs text-gray-400"></i>
                </a>

                {{-- Item 3: Security --}}
                <a href="#" class="flex items-center justify-between p-4 border-b border-gray-50 hover:bg-gray-50 transition-colors">
                    <div class="flex items-center gap-4">
                        <div class="w-10 h-10 rounded-full bg-blue-50 text-blue-500 flex items-center justify-center">
                            <i class="fa-solid fa-shield-halved"></i>
                        </div>
                        <span class="text-sm font-medium text-gray-800">Security</span>
                    </div>
                    <i class="fa-solid fa-chevron-right text-xs text-gray-400"></i>
                </a>

                {{-- Item 4: Notifications --}}
                <a href="#" class="flex items-center justify-between p-4 border-b border-gray-50 hover:bg-gray-50 transition-colors">
                    <div class="flex items-center gap-4">
                        <div class="w-10 h-10 rounded-full bg-blue-50 text-blue-500 flex items-center justify-center">
                            <i class="fa-regular fa-bell"></i>
                        </div>
                        <span class="text-sm font-medium text-gray-800">Notifications</span>
                    </div>
                    <i class="fa-solid fa-chevron-right text-xs text-gray-400"></i>
                </a>

                {{-- Item 5: Language --}}
                <a href="#" class="flex items-center justify-between p-4 border-b border-gray-50 hover:bg-gray-50 transition-colors">
                    <div class="flex items-center gap-4">
                        <div class="w-10 h-10 rounded-full bg-blue-50 text-blue-500 flex items-center justify-center">
                            <i class="fa-solid fa-globe"></i>
                        </div>
                        <span class="text-sm font-medium text-gray-800">Language</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="text-xs text-gray-500">English</span>
                        <i class="fa-solid fa-chevron-right text-xs text-gray-400"></i>
                    </div>
                </a>

                {{-- Item 6: Terms of Service --}}
                <a href="#" class="flex items-center justify-between p-4 border-b border-gray-50 hover:bg-gray-50 transition-colors">
                    <div class="flex items-center gap-4">
                        <div class="w-10 h-10 rounded-full bg-blue-50 text-blue-500 flex items-center justify-center">
                            <i class="fa-solid fa-gavel"></i>
                        </div>
                        <span class="text-sm font-medium text-gray-800">Terms of Service</span>
                    </div>
                    <i class="fa-solid fa-chevron-right text-xs text-gray-400"></i>
                </a>

                {{-- Item 7: Support --}}
                <a href="#" class="flex items-center justify-between p-4 hover:bg-gray-50 transition-colors">
                    <div class="flex items-center gap-4">
                        <div class="w-10 h-10 rounded-full bg-blue-50 text-blue-500 flex items-center justify-center">
                            <i class="fa-regular fa-circle-question"></i>
                        </div>
                        <span class="text-sm font-medium text-gray-800">Support</span>
                    </div>
                    <i class="fa-solid fa-chevron-right text-xs text-gray-400"></i>
                </a>

            </div>
        </div>

        {{-- Logout Button --}}
        <div class="px-4 mb-4">
            <form method="POST" action="/logout">
                @csrf
                <button type="submit" class="w-full bg-[#FFEAE8] hover:bg-red-100 text-[#D84B4B] py-4 rounded-[20px] font-bold flex justify-center items-center gap-2 transition-colors text-sm">
                    <i class="fa-solid fa-arrow-right-from-bracket"></i> Logout
                </button>
            </form>
        </div>

    </div>

@endsection
