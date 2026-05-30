<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    @vite('resources/css/app.css')
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <title>Checkout - NginUp</title>
    <style>
        .toast-notification {
            animation: slideDown 0.4s ease-out;
        }
        @keyframes slideDown {
            from { transform: translateY(-100%); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }
        .payment-option { cursor: pointer; }
        .payment-option input[type="radio"] { display: none; }
        .payment-option input[type="radio"]:checked + .payment-card {
            border-color: #E9631A;
            background-color: rgba(233,99,26,0.04);
        }
        .payment-option input[type="radio"]:checked + .payment-card .pay-icon {
            background-color: rgba(233,99,26,0.15);
            color: #E9631A;
        }
    </style>
</head>
<body class="bg-gray-50 text-gray-800 min-h-screen">

    <div id="checkout-panel">

        {{-- Navbar --}}
        <nav class="flex justify-between items-center px-4 md:px-12 py-4 bg-white border-b sticky top-0 z-50 shadow-sm">
            <a href="/detail/{{ $properti->id_properti }}" class="flex items-center gap-2 text-gray-500 hover:text-brand-orange transition-colors">
                <i class="fa-solid fa-arrow-left text-xl"></i>
            </a>
            <a href="/home" class="text-2xl font-bold text-brand-orange-hover tracking-tight">NginUp</a>
            <img src="https://images.unsplash.com/photo-1534528741775-53994a69daeb?q=80&w=100&auto=format&fit=crop"
                 class="w-9 h-9 rounded-full object-cover border-2 border-white shadow">
        </nav>

        <main class="max-w-7xl mx-auto px-4 md:px-12 mt-6 md:mt-10 mb-32">

            <div class="md:grid md:grid-cols-12 md:gap-8 lg:gap-12 items-start">

                {{-- Left: Property + Payment Methods --}}
                <div class="md:col-span-7" x-data="{ payment: '' }">

                    {{-- Property Summary Card --}}
                    <div class="bg-white border border-gray-100 rounded-3xl overflow-hidden shadow-sm mb-8">
                        <div class="h-52 w-full relative">
                            <img src="{{ $properti->gambar->first()->url ?? 'https://images.unsplash.com/photo-1540541338287-41700207dee6?q=80&w=800&auto=format&fit=crop' }}"
                                 class="w-full h-full object-cover">
                            <div class="absolute inset-0 bg-gradient-to-t from-black/30 to-transparent"></div>
                        </div>
                        <div class="p-5 md:p-6">
                            <span class="bg-brand-orange/10 text-brand-orange text-[10px] md:text-xs font-bold px-3 py-1 rounded-full inline-block mb-3">
                                Properti Terverifikasi
                            </span>
                            <h2 class="text-xl md:text-2xl font-bold text-gray-900 mb-4 leading-tight">
                                {{ $properti->nama_properti }}
                            </h2>
                            <div class="flex items-center text-sm text-gray-600 border-t border-gray-100 pt-4">
                                <div class="flex items-center gap-2 flex-1 border-r border-gray-100 pr-4">
                                    <i class="fa-regular fa-calendar text-brand-green"></i>
                                    <div>
                                        <p class="text-xs md:text-sm font-semibold text-gray-800">
                                            @if ($checkIn && $checkOut)
                                                {{ $checkIn->format('d M') }} – {{ $checkOut->format('d M Y') }}
                                            @else
                                                Pilih tanggal
                                            @endif
                                        </p>
                                        <p class="text-[10px] md:text-xs text-gray-400">
                                            @if ($checkIn && $checkOut)
                                                {{ $checkIn->diffInDays($checkOut) }} Malam
                                            @else
                                                —
                                            @endif
                                        </p>
                                    </div>
                                </div>
                                <div class="flex items-center gap-2 pl-4">
                                    <i class="fa-solid fa-user-group text-brand-green"></i>
                                    <p class="text-xs md:text-sm font-semibold text-gray-800">
                                        {{ $request->tamu ?? $properti->max_tamu }} Tamu
                                    </p>
                                </div>
                            </div>
                            @if ($selectedKamar)
                            <div class="border-t border-gray-100 pt-4 mt-4 flex items-center gap-2 text-sm text-gray-600">
                                <i class="fa-solid fa-door-open text-brand-green"></i>
                                <p class="text-xs md:text-sm font-semibold text-gray-800">
                                    {{ $selectedKamar->nama_kamar }}
                                </p>
                                <span class="text-gray-400">·</span>
                                <span class="text-xs text-gray-500">{{ $selectedKamar->kapasitas }} tamu · {{ $selectedKamar->jumlah_tempat_tidur }} {{ ucfirst($selectedKamar->tipe_tempat_tidur) }}</span>
                            </div>
                            @endif
                        </div>
                    </div>

                    {{-- Payment Methods --}}
                    <h2 class="text-xl md:text-2xl font-bold text-gray-900 mb-5">Metode Pembayaran</h2>

                    <div class="space-y-3 mb-8 md:mb-0">

                        {{-- QRIS --}}
                        <label class="payment-option block">
                            <input type="radio" value="qris" x-model="payment">
                            <div class="payment-card flex items-center p-4 md:p-5 border-2 transition-all duration-200 rounded-2xl"
                                 :class="payment === 'qris' ? 'border-brand-orange bg-brand-orange/5' : 'border-transparent bg-white hover:border-gray-200 shadow-sm'">
                                <div class="pay-icon w-12 h-12 rounded-xl flex items-center justify-center shrink-0 transition-all duration-200"
                                     :class="payment === 'qris' ? 'bg-brand-orange/15 text-brand-orange' : 'bg-gray-100 text-brand-green'">
                                    <i class="fa-solid fa-qrcode text-base"></i>
                                </div>
                                <div class="ml-4 flex-1">
                                    <h3 class="font-bold text-gray-900 text-sm md:text-base">QRIS</h3>
                                    <p class="text-xs md:text-sm text-gray-500 mt-0.5">Scan QR untuk bayar</p>
                                </div>
                                <i class="fa-solid fa-circle-check text-brand-orange text-lg" x-show="payment === 'qris'"></i>
                            </div>
                        </label>

                        {{-- QRIS content --}}
                        <div x-show="payment === 'qris'" class="bg-white border border-brand-orange/20 rounded-2xl p-6 shadow-sm -mt-2">
                            <div class="flex flex-col items-center text-center">
                                <div class="w-48 h-48 bg-white border-2 border-gray-200 rounded-2xl flex items-center justify-center mb-4 p-4">
                                    <img src="https://api.qrserver.com/v1/create-qr-code/?size=300x300&data=NGINUP-{{ $properti->id_properti }}-{{ now()->timestamp }}" alt="QRIS Code" class="w-full h-full object-contain">
                                </div>
                                <p class="text-xs text-gray-500 mb-1">Scan QRIS ini menggunakan</p>
                                <p class="text-sm font-bold text-gray-900 mb-3">GoPay, OVO, DANA, ShopeePay, M-Banking</p>
                                <div class="bg-orange-50 text-brand-orange text-[10px] font-bold px-4 py-2 rounded-lg">
                                    <i class="fa-regular fa-clock mr-1"></i> Scan & Bayar sebelum pesanan kadaluarsa
                                </div>
                            </div>
                        </div>

                        {{-- Transfer Bank --}}
                        <label class="payment-option block">
                            <input type="radio" value="transfer" x-model="payment">
                            <div class="payment-card flex items-center p-4 md:p-5 border-2 transition-all duration-200 rounded-2xl"
                                 :class="payment === 'transfer' ? 'border-brand-orange bg-brand-orange/5' : 'border-transparent bg-white hover:border-gray-200 shadow-sm'">
                                <div class="pay-icon w-12 h-12 rounded-xl flex items-center justify-center shrink-0 transition-all duration-200"
                                     :class="payment === 'transfer' ? 'bg-brand-orange/15 text-brand-orange' : 'bg-gray-100 text-brand-green'">
                                    <i class="fa-solid fa-building-columns text-base"></i>
                                </div>
                                <div class="ml-4 flex-1">
                                    <h3 class="font-bold text-gray-900 text-sm md:text-base">Transfer Bank</h3>
                                    <p class="text-xs md:text-sm text-gray-500 mt-0.5">BCA, Mandiri, BNI</p>
                                </div>
                                <i class="fa-solid fa-circle-check text-brand-orange text-lg" x-show="payment === 'transfer'"></i>
                            </div>
                        </label>

                        {{-- Transfer Bank content --}}
                        <div x-show="payment === 'transfer'" class="bg-white border border-brand-orange/20 rounded-2xl p-6 shadow-sm -mt-2 space-y-4">
                            <div class="flex items-center justify-between border-b border-gray-100 pb-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-11 h-11 rounded-xl bg-gradient-to-br from-red-600 to-red-700 text-white flex items-center justify-center font-black text-xs tracking-wide shadow-sm">BCA</div>
                                    <div>
                                        <p class="text-[11px] text-gray-400">Bank Central Asia</p>
                                        <p class="text-sm font-bold text-gray-900 tracking-wider">123 456 7890</p>
                                    </div>
                                </div>
                                <button onclick="navigator.clipboard.writeText('1234567890')" class="text-[10px] font-bold text-brand-orange border border-brand-orange px-3 py-1.5 rounded-lg hover:bg-brand-orange/5 transition-colors">
                                    <i class="fa-regular fa-copy mr-1"></i> Salin
                                </button>
                            </div>
                            <div class="flex items-center justify-between border-b border-gray-100 pb-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-11 h-11 rounded-xl bg-gradient-to-br from-blue-600 to-blue-700 text-white flex items-center justify-center font-black text-[9px] tracking-wide shadow-sm">MANDIRI</div>
                                    <div>
                                        <p class="text-[11px] text-gray-400">Bank Mandiri</p>
                                        <p class="text-sm font-bold text-gray-900 tracking-wider">987 654 3210</p>
                                    </div>
                                </div>
                                <button onclick="navigator.clipboard.writeText('9876543210')" class="text-[10px] font-bold text-brand-orange border border-brand-orange px-3 py-1.5 rounded-lg hover:bg-brand-orange/5 transition-colors">
                                    <i class="fa-regular fa-copy mr-1"></i> Salin
                                </button>
                            </div>
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    <div class="w-11 h-11 rounded-xl bg-gradient-to-br from-orange-500 to-orange-600 text-white flex items-center justify-center font-black text-xs tracking-wide shadow-sm">BNI</div>
                                    <div>
                                        <p class="text-[11px] text-gray-400">Bank Negara Indonesia</p>
                                        <p class="text-sm font-bold text-gray-900 tracking-wider">555 666 7777</p>
                                    </div>
                                </div>
                                <button onclick="navigator.clipboard.writeText('5556667777')" class="text-[10px] font-bold text-brand-orange border border-brand-orange px-3 py-1.5 rounded-lg hover:bg-brand-orange/5 transition-colors">
                                    <i class="fa-regular fa-copy mr-1"></i> Salin
                                </button>
                            </div>
                            <div class="bg-blue-50 text-blue-600 text-[10px] font-bold px-4 py-3 rounded-lg flex items-center gap-2">
                                <i class="fa-solid fa-circle-info"></i>
                                <span>Transfer sesuai nominal total pembayaran lalu upload bukti bayar.</span>
                            </div>
                        </div>

                        {{-- Virtual Account --}}
                        <label class="payment-option block">
                            <input type="radio" value="va" x-model="payment">
                            <div class="payment-card flex items-center p-4 md:p-5 border-2 transition-all duration-200 rounded-2xl"
                                 :class="payment === 'va' ? 'border-brand-orange bg-brand-orange/5' : 'border-transparent bg-white hover:border-gray-200 shadow-sm'">
                                <div class="pay-icon w-12 h-12 rounded-xl flex items-center justify-center shrink-0 transition-all duration-200"
                                     :class="payment === 'va' ? 'bg-brand-orange/15 text-brand-orange' : 'bg-gray-100 text-brand-green'">
                                    <i class="fa-solid fa-receipt text-base"></i>
                                </div>
                                <div class="ml-4 flex-1">
                                    <h3 class="font-bold text-gray-900 text-sm md:text-base">Virtual Account</h3>
                                    <p class="text-xs md:text-sm text-gray-500 mt-0.5">Generate VA BCA/Mandiri/BNI</p>
                                </div>
                                <i class="fa-solid fa-circle-check text-brand-orange text-lg" x-show="payment === 'va'"></i>
                            </div>
                        </label>

                        {{-- VA content --}}
                        <div x-show="payment === 'va'" class="bg-white border border-brand-orange/20 rounded-2xl p-6 shadow-sm -mt-2 space-y-4">
                            <div class="flex items-center justify-between border-b border-gray-100 pb-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-11 h-11 rounded-xl bg-gradient-to-br from-red-600 to-red-700 text-white flex items-center justify-center font-black text-xs tracking-wide shadow-sm">BCA</div>
                                    <div>
                                        <p class="text-[11px] text-gray-400">BCA Virtual Account</p>
                                        <p class="text-sm font-bold text-gray-900 tracking-wider">88012 {{ str_pad($properti->id_properti, 8, '0', STR_PAD_LEFT) }}001</p>
                                    </div>
                                </div>
                                <button onclick="navigator.clipboard.writeText('88012{{ str_pad($properti->id_properti, 8, '0', STR_PAD_LEFT) }}001')" class="text-[10px] font-bold text-brand-orange border border-brand-orange px-3 py-1.5 rounded-lg hover:bg-brand-orange/5 transition-colors">
                                    <i class="fa-regular fa-copy mr-1"></i> Salin
                                </button>
                            </div>
                            <div class="flex items-center justify-between border-b border-gray-100 pb-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-11 h-11 rounded-xl bg-gradient-to-br from-blue-600 to-blue-700 text-white flex items-center justify-center font-black text-[9px] tracking-wide shadow-sm">MANDIRI</div>
                                    <div>
                                        <p class="text-[11px] text-gray-400">Mandiri Virtual Account</p>
                                        <p class="text-sm font-bold text-gray-900 tracking-wider">89123 {{ str_pad($properti->id_properti, 8, '0', STR_PAD_LEFT) }}001</p>
                                    </div>
                                </div>
                                <button onclick="navigator.clipboard.writeText('89123{{ str_pad($properti->id_properti, 8, '0', STR_PAD_LEFT) }}001')" class="text-[10px] font-bold text-brand-orange border border-brand-orange px-3 py-1.5 rounded-lg hover:bg-brand-orange/5 transition-colors">
                                    <i class="fa-regular fa-copy mr-1"></i> Salin
                                </button>
                            </div>
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    <div class="w-11 h-11 rounded-xl bg-gradient-to-br from-orange-500 to-orange-600 text-white flex items-center justify-center font-black text-xs tracking-wide shadow-sm">BNI</div>
                                    <div>
                                        <p class="text-[11px] text-gray-400">BNI Virtual Account</p>
                                        <p class="text-sm font-bold text-gray-900 tracking-wider">88123 {{ str_pad($properti->id_properti, 8, '0', STR_PAD_LEFT) }}001</p>
                                    </div>
                                </div>
                                <button onclick="navigator.clipboard.writeText('88123{{ str_pad($properti->id_properti, 8, '0', STR_PAD_LEFT) }}001')" class="text-[10px] font-bold text-brand-orange border border-brand-orange px-3 py-1.5 rounded-lg hover:bg-brand-orange/5 transition-colors">
                                    <i class="fa-regular fa-copy mr-1"></i> Salin
                                </button>
                            </div>
                            <div class="bg-blue-50 text-blue-600 text-[10px] font-bold px-4 py-3 rounded-lg flex items-center gap-2">
                                <i class="fa-solid fa-circle-info"></i>
                                <span>VA akan tergenerate otomatis, lakukan pembayaran melalui ATM/M-Banking.</span>
                            </div>
                        </div>

                        {{-- Cash --}}
                        <label class="payment-option block">
                            <input type="radio" value="cash" x-model="payment">
                            <div class="payment-card flex items-center p-4 md:p-5 border-2 transition-all duration-200 rounded-2xl"
                                 :class="payment === 'cash' ? 'border-brand-orange bg-brand-orange/5' : 'border-transparent bg-white hover:border-gray-200 shadow-sm'">
                                <div class="pay-icon w-12 h-12 rounded-xl flex items-center justify-center shrink-0 transition-all duration-200"
                                     :class="payment === 'cash' ? 'bg-brand-orange/15 text-brand-orange' : 'bg-gray-100 text-brand-green'">
                                    <i class="fa-solid fa-money-bill-wave text-base"></i>
                                </div>
                                <div class="ml-4 flex-1">
                                    <h3 class="font-bold text-gray-900 text-sm md:text-base">Cash</h3>
                                    <p class="text-xs md:text-sm text-gray-500 mt-0.5">Bayar langsung di properti</p>
                                </div>
                                <i class="fa-solid fa-circle-check text-brand-orange text-lg" x-show="payment === 'cash'"></i>
                            </div>
                        </label>

                        {{-- Cash content --}}
                        <div x-show="payment === 'cash'" class="bg-white border border-brand-orange/20 rounded-2xl p-6 shadow-sm -mt-2">
                            <div class="flex flex-col items-center text-center">
                                <div class="w-16 h-16 rounded-full bg-green-50 flex items-center justify-center mb-4">
                                    <i class="fa-solid fa-hand-holding-dollar text-2xl text-green-600"></i>
                                </div>
                                <h3 class="text-sm font-bold text-gray-900 mb-2">Bayar Langsung di Properti</h3>
                                <p class="text-xs text-gray-500 leading-relaxed max-w-sm">
                                    Lakukan pembayaran tunai langsung ke host saat Anda check-in di properti.
                                </p>
                            </div>
                        </div>

                    </div>
                </div>

                {{-- Right: Price Summary + Pay Button --}}
                <div class="md:col-span-5 md:sticky md:top-24 mt-8 md:mt-0">
                        <form method="POST" action="/checkout">
                            @csrf
                            <input type="hidden" name="id_properti" value="{{ $properti->id_properti }}">
                            <input type="hidden" name="id_kamar" value="{{ $selectedKamar->id_kamar ?? '' }}">
                            <input type="hidden" name="metode_pembayaran" x-model="payment">
                            <input type="hidden" name="tanggal_check_in" value="{{ $checkIn ? $checkIn->format('Y-m-d') : '' }}">
                            <input type="hidden" name="tanggal_check_out" value="{{ $checkOut ? $checkOut->format('Y-m-d') : '' }}">
                            <input type="hidden" name="total_tamu" value="{{ $request->tamu ?? 1 }}">

                        {{-- Rincian Transparansi --}}
                        <div class="bg-white rounded-3xl p-6 md:p-8 mb-5 border border-gray-100 shadow-sm">
                            <div class="flex items-center gap-2 mb-6">
                                <i class="fa-solid fa-receipt text-brand-green"></i>
                                <h3 class="font-bold text-xl text-gray-900">Rincian Transparansi</h3>
                            </div>

                                                        @php
                                $malam = ($checkIn && $checkOut) ? $checkIn->diffInDays($checkOut) : 1;
                                $hargaPerMalam = $selectedKamar && $selectedKamar->harga_per_malam ? $selectedKamar->harga_per_malam : $properti->harga_per_malam;
                                $totalSewa = $hargaPerMalam * $malam;
                                $biayaLayanan = round($totalSewa * 0.05);
                                $totalBayar = $totalSewa + $biayaLayanan;
                            @endphp
                            <div class="space-y-4 text-sm md:text-base mb-6">
                                <div class="flex justify-between text-gray-600">
                                    <span>Harga Sewa ({{ $malam }} Malam)</span>
                                    <span class="font-bold text-gray-900">Rp {{ number_format($totalSewa, 0, ',', '.') }}</span>
                                </div>
                                <div class="flex justify-between text-gray-600">
                                    <span>Biaya Layanan Platform</span>
                                    <span class="font-bold text-gray-900">Rp {{ number_format($biayaLayanan, 0, ',', '.') }}</span>
                                </div>
                            </div>

                            <div class="bg-gray-50 rounded-2xl p-5 flex items-center justify-between border border-gray-100">
                                <span class="font-bold text-gray-600 text-base md:text-lg">Total Pembayaran</span>
                                <span class="text-2xl md:text-3xl font-bold text-brand-orange">Rp {{ number_format($totalBayar, 0, ',', '.') }}</span>
                            </div>
                        </div>

                        {{-- Pay Button + Disclaimer --}}
                        <div class="bg-white border border-gray-100 shadow-sm rounded-3xl p-6 md:p-8">
                            <div class="flex items-start gap-3 bg-blue-50 rounded-2xl p-4 mb-5 text-xs text-gray-600 leading-relaxed">
                                <i class="fa-solid fa-shield-halved text-brand-green mt-0.5 text-base shrink-0"></i>
                                <span>Transaksi Anda dilindungi oleh enkripsi 256-bit dan garansi kenyamanan NginUp.</span>
                            </div>

                            <button type="submit"
                                class="w-full bg-brand-orange hover:bg-brand-orange-hover active:scale-[0.98] text-white font-bold py-4 md:py-5 rounded-xl transition-all duration-200 text-lg mb-4 shadow-lg shadow-orange-900/20">
                                <i class="fa-solid fa-lock mr-2 text-sm"></i>
                                Bayar Sekarang
                            </button>

                            <p class="text-xs text-gray-400 text-center leading-relaxed">
                                Dengan mengklik 'Bayar Sekarang', Anda menyetujui<br>
                                <a href="#" class="text-brand-orange hover:underline font-medium">Syarat &amp; Ketentuan</a>
                                serta Kebijakan Privasi kami.
                            </p>
                        </div>
                    </form>
                </div>

            </div>
        </main>
    </div>



    {{-- Floating Validation Toast --}}
    @if ($errors->any())
    <div id="error-toast" class="toast-notification fixed top-4 left-1/2 -translate-x-1/2 w-[92%] max-w-lg bg-white border border-red-200 rounded-2xl shadow-2xl shadow-red-900/20 p-5" style="z-index: 99999" role="alert">
        <div class="flex items-start gap-3">
            <div class="w-10 h-10 rounded-full bg-red-100 flex items-center justify-center shrink-0">
                <i class="fa-solid fa-circle-exclamation text-red-500 text-lg"></i>
            </div>
            <div class="flex-1 min-w-0">
                <h4 class="font-bold text-gray-900 text-sm mb-1">Validasi Gagal</h4>
                <ul class="text-sm text-red-600 space-y-1">
                    @foreach ($errors->all() as $error)
                        <li class="flex items-start gap-2">
                            <i class="fa-solid fa-chevron-right text-[10px] mt-1 shrink-0"></i>
                            <span>{{ $error }}</span>
                        </li>
                    @endforeach
                </ul>
            </div>
            <button type="button" onclick="document.getElementById('error-toast').remove()" class="text-gray-400 hover:text-gray-600 shrink-0">
                <i class="fa-solid fa-xmark text-xl"></i>
            </button>
        </div>
    </div>
    @endif

</body>
</html>
