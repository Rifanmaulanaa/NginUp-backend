@extends('layouts.host', ['activeTab' => 'reports'])

@section('title', 'Reports - Host NginUp')
@section('page_title', 'Reports')

@section('content')

<div class="max-w-4xl mx-auto pt-6" x-data="{ view: 'report', scrollToTop() { window.scrollTo({top:0, behavior:'smooth'}); const mainScroll = document.querySelector('main'); if(mainScroll) mainScroll.scrollTo({top:0, behavior:'smooth'}); } }">

    {{-- ========================================== --}}
    {{-- VIEW: EARNINGS REPORT                      --}}
    {{-- ========================================== --}}
    <div x-show="view === 'report'" x-transition.opacity.duration.300ms>
        
        {{-- Header --}}
        <div class="flex flex-col md:flex-row md:justify-between md:items-end gap-4 mb-6">
            <div>
                <h1 class="text-2xl md:text-3xl font-extrabold text-gray-900 mb-1">Earnings Report</h1>
                <p class="text-sm text-gray-500">All-time earnings summary</p>
            </div>
            <div class="flex items-center gap-2">
                <button class="px-4 py-2 bg-white border border-gray-200 text-gray-700 text-sm font-bold rounded-full shadow-sm flex items-center gap-2 hover:bg-gray-50 transition-colors">
                    <i class="fa-regular fa-calendar"></i> All Time <i class="fa-solid fa-chevron-down text-[10px]"></i>
                </button>
                <button class="px-4 py-2 bg-brand-orange text-white text-sm font-bold rounded-full shadow-md shadow-brand-orange/20 flex items-center gap-2 hover:bg-brand-orange-hover transition-colors">
                    <i class="fa-solid fa-download"></i> Export
                </button>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
            
            {{-- Total Income Card --}}
            <div class="bg-white border border-gray-100 rounded-[24px] p-6 shadow-sm flex flex-col justify-between">
                <div>
                    <h3 class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-2">Total Income</h3>
                    <div class="flex items-baseline gap-2 mb-6">
                        <span class="text-3xl font-extrabold text-gray-900">Rp {{ number_format($totalIncome, 0, ',', '.') }}</span>
                    </div>
                </div>
                
                <div class="bg-blue-50/50 border border-blue-50 rounded-xl p-4 flex justify-between items-center">
                    <div>
                        <div class="text-[10px] font-bold text-gray-500 mb-1">Unpaid Payout</div>
                        <div class="text-sm font-bold text-gray-900">Rp {{ number_format($unpaidPayout, 0, ',', '.') }}</div>
                    </div>
                    <span class="px-3 py-1 bg-blue-100 text-blue-600 text-[10px] font-bold rounded-full">Processing</span>
                </div>
            </div>

            <div class="flex flex-col gap-4">
                {{-- Bookings/Occupancy Card --}}
                <div class="bg-white border border-gray-100 rounded-[24px] p-6 shadow-sm">
                    <h3 class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-4">Bookings/Occupancy</h3>
                    
                    <div class="flex justify-between items-end mb-6 text-center">
                        <div>
                            <div class="text-xl font-extrabold text-gray-900">{{ $revenue->count() }}</div>
                            <div class="text-[10px] font-bold text-gray-400">STAYS</div>
                        </div>
                        <div class="w-px h-8 bg-gray-200"></div>
                        <div>
                            <div class="text-xl font-extrabold text-blue-500">{{ $totalMalam }}</div>
                            <div class="text-[10px] font-bold text-gray-400">NIGHTS</div>
                        </div>
                        <div class="w-px h-8 bg-gray-200"></div>
                        <div>
                            <div class="text-xl font-extrabold text-red-500">{{ $bookedProperties }}</div>
                            <div class="text-[10px] font-bold text-gray-400">UNITS</div>
                        </div>
                    </div>

                    <div class="w-full h-1.5 bg-gray-100 rounded-full flex overflow-hidden">
                        <div class="h-full bg-brand-orange" style="width: {{ $revenue->count() > 0 ? 70 : 0 }}%"></div>
                        <div class="h-full bg-blue-500" style="width: {{ $totalMalam > 0 ? 20 : 0 }}%"></div>
                        <div class="h-full bg-red-500" style="width: {{ $bookedProperties > 0 ? 10 : 0 }}%"></div>
                    </div>
                </div>

                {{-- Net Profit Card --}}
                <div class="bg-[#1c333a] rounded-[24px] p-6 shadow-md flex justify-between items-center text-white">
                    <div>
                        <h3 class="text-[10px] font-bold text-white/60 uppercase tracking-wider mb-2">Net Profit</h3>
                        <div class="text-2xl font-extrabold mb-1">Rp {{ number_format($netProfit, 0, ',', '.') }}</div>
                        <div class="text-[10px] text-white/60">After platform fees</div>
                    </div>
                    <button class="px-4 py-2 bg-white/10 hover:bg-white/20 border border-white/20 rounded-xl text-xs font-bold transition-colors">
                        Details
                    </button>
                </div>
            </div>

        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
            {{-- Revenue Chart --}}
            <div class="bg-white border border-gray-100 rounded-[24px] p-6 shadow-sm">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-sm font-extrabold text-gray-900">Revenue</h3>
                    <div class="flex items-center gap-3 text-[10px] font-bold text-gray-400">
                        <div class="flex items-center gap-1"><span class="w-2 h-2 rounded-sm bg-brand-orange"></span> Total</div>
                    </div>
                </div>
                
                {{-- Mock Chart --}}
                <div class="w-full h-32 flex items-end justify-between gap-2">
                    <div class="w-full relative h-full flex items-end justify-center">
                        <div class="absolute bottom-0 w-full h-[30%] bg-blue-100 rounded-t-sm"></div>
                        <div class="absolute bottom-0 w-full h-[20%] bg-brand-orange rounded-t-sm z-10"></div>
                    </div>
                    <div class="w-full relative h-full flex items-end justify-center">
                        <div class="absolute bottom-0 w-full h-[50%] bg-blue-100 rounded-t-sm"></div>
                        <div class="absolute bottom-0 w-full h-[40%] bg-brand-orange rounded-t-sm z-10"></div>
                    </div>
                    <div class="w-full relative h-full flex items-end justify-center">
                        <div class="absolute bottom-0 w-full h-[40%] bg-blue-100 rounded-t-sm"></div>
                        <div class="absolute bottom-0 w-full h-[30%] bg-brand-orange rounded-t-sm z-10"></div>
                    </div>
                    <div class="w-full relative h-full flex items-end justify-center">
                        <div class="absolute bottom-0 w-full h-[70%] bg-blue-100 rounded-t-sm"></div>
                        <div class="absolute bottom-0 w-full h-[55%] bg-brand-orange rounded-t-sm z-10"></div>
                    </div>
                    <div class="w-full relative h-full flex items-end justify-center">
                        <div class="absolute bottom-0 w-full h-[60%] bg-blue-100 rounded-t-sm"></div>
                        <div class="absolute bottom-0 w-full h-[90%] bg-brand-orange rounded-t-sm z-10"></div>
                    </div>
                    <div class="w-full relative h-full flex items-end justify-center">
                        <div class="absolute bottom-0 w-full h-[80%] bg-blue-100 rounded-t-sm"></div>
                        <div class="absolute bottom-0 w-full h-[65%] bg-brand-orange rounded-t-sm z-10"></div>
                    </div>
                    <div class="w-full relative h-full flex items-end justify-center">
                        <div class="absolute bottom-0 w-full h-[50%] bg-blue-100 rounded-t-sm"></div>
                        <div class="absolute bottom-0 w-full h-[35%] bg-brand-orange rounded-t-sm z-10"></div>
                    </div>
                </div>
                <div class="flex justify-between mt-2 text-[8px] font-bold text-gray-400 px-2">
                    <span>M</span>
                    <span>T</span>
                    <span>W</span>
                    <span>T</span>
                    <span>F</span>
                    <span>S</span>
                    <span>S</span>
                </div>
            </div>

            {{-- Fees & Taxes --}}
            <div class="bg-white border border-gray-100 rounded-[24px] p-6 shadow-sm flex flex-col justify-between">
                <div>
                    <h3 class="text-sm font-extrabold text-gray-900 mb-6">Fees & Taxes</h3>
                    
                    <div class="space-y-4">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl bg-orange-50 text-brand-orange flex items-center justify-center">
                                    <i class="fa-solid fa-receipt"></i>
                                </div>
                                <div>
                                    <div class="text-xs font-bold text-gray-900">NginUp Fees</div>
                                    <div class="text-[9px] text-gray-500">{{ $platformFees > 0 ? round(($platformFees / $totalIncome) * 100, 1) : 0 }}% of reservation total</div>
                                </div>
                            </div>
                            <div class="text-sm font-bold text-gray-900">-Rp {{ number_format($platformFees, 0, ',', '.') }}</div>
                        </div>
                    </div>
                </div>

                <div class="mt-6 pt-4 border-t border-gray-100 flex justify-between items-center bg-blue-50/50 -mx-6 px-6 -mb-6 pb-6 rounded-b-[24px]">
                    <span class="text-xs font-bold text-gray-700">Total Deductions</span>
                    <span class="text-sm font-extrabold text-brand-orange">-Rp {{ number_format($platformFees, 0, ',', '.') }}</span>
                </div>
            </div>
        </div>

        {{-- Transactions Overview --}}
        <div class="bg-white border border-gray-100 rounded-[24px] p-6 shadow-sm mb-4">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-sm font-extrabold text-gray-900">Transactions</h3>
                <button class="text-[10px] font-bold text-brand-orange flex items-center gap-1 hover:underline">Filter</button>
            </div>

            <div class="flex gap-2 mb-6">
                <button class="w-1/2 py-2 bg-gray-50 text-gray-800 text-xs font-bold rounded-lg shadow-sm border border-gray-100">All</button>
                <button class="w-1/2 py-2 bg-blue-50/50 text-blue-600 text-xs font-bold rounded-lg">Payouts</button>
            </div>

            <div class="space-y-4">
                @forelse ($revenue->take(3) as $rs)
                <div class="flex items-center justify-between group">
                    <div class="flex items-center gap-3">
                        @if ($rs->pemesanan && $rs->pemesanan->properti && $rs->pemesanan->properti->gambar->first())
                        <img src="{{ $rs->pemesanan->properti->gambar->first()->url }}" class="w-10 h-10 rounded-xl object-cover">
                        @else
                        <div class="w-10 h-10 rounded-xl bg-gray-200 flex items-center justify-center text-gray-400">
                            <i class="fa-solid fa-building"></i>
                        </div>
                        @endif
                        <div>
                            <div class="text-xs font-bold text-gray-900 group-hover:text-brand-orange transition-colors">{{ $rs->pemesanan->properti->nama_properti ?? 'Unknown Property' }}</div>
                            <div class="text-[9px] text-gray-400">{{ $rs->pemesanan->tanggal_check_in ? \Carbon\Carbon::parse($rs->pemesanan->tanggal_check_in)->format('M d') : '-' }} • #TXN-{{ str_pad($rs->id_revenue_split, 5, '0', STR_PAD_LEFT) }}</div>
                        </div>
                    </div>
                    <div class="text-right">
                        <div class="text-sm font-bold text-gray-900 mb-0.5">Rp {{ number_format($rs->jumlah_kotor, 0, ',', '.') }}</div>
                        <span class="px-2 py-0.5 text-[8px] font-bold rounded-full {{ $rs->status === 'settled' ? 'bg-green-100 text-green-700' : 'bg-orange-100 text-brand-orange' }}">{{ strtoupper($rs->status) }}</span>
                    </div>
                </div>
                @empty
                <div class="text-center py-8 text-gray-400 text-sm">
                    No transactions yet
                </div>
                @endforelse
            </div>

            @if ($revenue->count() > 3)
            <div class="mt-6 pt-4 border-t border-gray-100 text-center">
                <button @click="view = 'history'; scrollToTop()" class="text-xs font-bold text-brand-orange hover:text-brand-orange-hover transition-colors">
                    View Full History
                </button>
            </div>
            @endif
        </div>

    </div>

    {{-- ========================================== --}}
    {{-- VIEW: TRANSACTION HISTORY                  --}}
    {{-- ========================================== --}}
    <div x-show="view === 'history'" x-transition.opacity.duration.300ms style="display: none;">
        
        {{-- Header --}}
        <div class="flex items-center justify-between mb-6">
            <div class="flex items-center gap-3">
                <button @click="view = 'report'; scrollToTop()" class="w-8 h-8 rounded-full bg-white shadow-sm border border-gray-100 flex items-center justify-center text-brand-orange hover:bg-orange-50 transition-colors">
                    <i class="fa-solid fa-arrow-left"></i>
                </button>
                <h1 class="text-2xl font-extrabold text-brand-orange">Transaction History</h1>
            </div>
            <div class="w-8 h-8 rounded-full bg-orange-100 flex items-center justify-center text-brand-orange font-bold border-2 border-white shadow-sm md:hidden">
                G
            </div>
        </div>

        {{-- Search & Filters --}}
        <div class="bg-white border border-gray-100 rounded-[24px] p-5 shadow-sm mb-4 space-y-4">
            <div class="relative">
                <i class="fa-solid fa-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"></i>
                <input type="text" placeholder="Search by ID or property name" class="w-full bg-gray-50 border border-gray-100 rounded-xl pl-10 pr-4 py-3 text-sm focus:outline-none focus:border-brand-orange focus:ring-1 focus:ring-brand-orange">
            </div>
            
            <div class="flex items-center gap-2 overflow-x-auto hide-scrollbar pb-1">
                <button class="px-5 py-2 bg-brand-orange text-white text-xs font-bold rounded-full shadow-sm whitespace-nowrap">All</button>
                <button class="px-5 py-2 bg-gray-100 text-gray-600 hover:bg-gray-200 text-xs font-bold rounded-full transition-colors whitespace-nowrap">Bookings</button>
                <button class="px-5 py-2 bg-white border border-gray-200 text-gray-600 hover:bg-gray-50 text-xs font-bold rounded-full flex items-center gap-2 transition-colors ml-auto whitespace-nowrap">
                    <i class="fa-regular fa-calendar"></i> All Time <i class="fa-solid fa-chevron-down text-[10px]"></i>
                </button>
            </div>
        </div>

        {{-- History List --}}
        <div class="space-y-4">
            @forelse ($revenue as $rs)
            <div class="bg-white border border-gray-100 rounded-[24px] p-5 shadow-sm flex items-center justify-between cursor-pointer hover:border-brand-orange/30 transition-colors group">
                <div class="flex gap-4 items-center">
                    @if ($rs->pemesanan && $rs->pemesanan->properti && $rs->pemesanan->properti->gambar->first())
                    <img src="{{ $rs->pemesanan->properti->gambar->first()->url }}" class="w-14 h-14 rounded-xl object-cover">
                    @else
                    <div class="w-14 h-14 rounded-xl bg-gray-200 flex items-center justify-center text-gray-400 text-xl">
                        <i class="fa-solid fa-building"></i>
                    </div>
                    @endif
                    <div class="flex flex-col gap-1">
                        <h4 class="text-sm font-bold text-gray-900 group-hover:text-brand-orange transition-colors">{{ $rs->pemesanan->properti->nama_properti ?? 'Unknown Property' }}</h4>
                        <div class="flex items-center gap-2 text-[10px] text-gray-500 font-medium">
                            <span class="text-brand-orange font-bold">#TXN-{{ str_pad($rs->id_revenue_split, 5, '0', STR_PAD_LEFT) }}</span>
                            <span>•</span>
                            <span>{{ $rs->pemesanan->tanggal_check_in ? \Carbon\Carbon::parse($rs->pemesanan->tanggal_check_in)->format('M d, Y') : '-' }}</span>
                        </div>
                        <div>
                            @if ($rs->status === 'settled')
                            <span class="px-2 py-0.5 bg-green-100 text-green-700 text-[8px] font-bold rounded-full">COMPLETED</span>
                            @else
                            <span class="px-2 py-0.5 bg-orange-100 text-brand-orange text-[8px] font-bold rounded-full">PENDING</span>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="flex flex-col items-end gap-1">
                    <span class="text-base font-extrabold text-brand-orange">+Rp {{ number_format($rs->jumlah_pemilik, 0, ',', '.') }}</span>
                    <i class="fa-solid fa-chevron-right text-gray-300 text-xs mt-2 group-hover:text-brand-orange transition-colors"></i>
                </div>
            </div>
            @empty
            <div class="bg-white border border-gray-100 rounded-[24px] p-10 shadow-sm text-center">
                <div class="text-gray-400 text-sm">No transactions found</div>
            </div>
            @endforelse
            
            @if ($revenue->count() > 0)
            <button class="w-full py-4 text-xs font-bold text-gray-500 hover:text-brand-orange transition-colors">
                Load More Transactions
            </button>
            @endif
        </div>

    </div>

</div>

@endsection