<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    @vite('resources/css/app.css')
    <title>Pembayaran - NginUp</title>
</head>
<body class="bg-gray-50 text-gray-800 min-h-screen">

    <div id="payment-panel">

        <nav class="flex justify-between items-center px-4 md:px-12 py-4 bg-white border-b sticky top-0 z-50 shadow-sm">
            <a href="/booking-detail/{{ $booking->id_pesanan }}" class="flex items-center gap-2 text-gray-500 hover:text-brand-orange transition-colors">
                <i class="fa-solid fa-arrow-left text-xl"></i>
            </a>
            <a href="/home" class="text-2xl font-bold text-brand-orange-hover tracking-tight">NginUp</a>
            <div class="w-9"></div>
        </nav>

        <main class="max-w-3xl mx-auto px-4 md:px-12 mt-6 md:mt-10 mb-32">

            @if ($errors->any())
            <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-2xl">
                <ul class="list-disc list-inside text-sm text-red-600">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            @if (session('success'))
            <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-2xl text-sm text-green-700">
                {{ session('success') }}
            </div>
            @endif

            @if (session('info'))
            <div class="mb-6 p-4 bg-blue-50 border border-blue-200 rounded-2xl text-sm text-blue-700">
                {{ session('info') }}
            </div>
            @endif

            <form method="POST" action="/payment/{{ $booking->id_pesanan }}" enctype="multipart/form-data">
                @csrf

                <div class="bg-white border border-gray-100 rounded-3xl overflow-hidden shadow-sm mb-8">
                    <div class="h-40 w-full relative">
                        <img src="{{ $booking->properti->gambar->first()->url ?? 'https://images.unsplash.com/photo-1540541338287-41700207dee6?q=80&w=800&auto=format&fit=crop' }}"
                             class="w-full h-full object-cover">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/30 to-transparent"></div>
                        <div class="absolute bottom-4 left-4">
                            <h2 class="text-white font-bold text-xl drop-shadow-lg">{{ $booking->properti->nama_properti }}</h2>
                            <p class="text-white/80 text-sm">
                                {{ $booking->tanggal_check_in->format('d M') }} – {{ $booking->tanggal_check_out->format('d M Y') }}
                            </p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-3xl p-6 md:p-8 mb-5 border border-gray-100 shadow-sm">
                    <h3 class="font-bold text-xl text-gray-900 mb-6">Detail Pembayaran</h3>

                    <div class="space-y-4 text-sm md:text-base mb-6">
                        <div class="flex justify-between text-gray-600">
                            <span>Harga Sewa ({{ $booking->total_malam }} Malam)</span>
                            <span class="font-bold text-gray-900">Rp {{ number_format($totalSewa, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between text-gray-600">
                            <span>Biaya Layanan Platform (5%)</span>
                            <span class="font-bold text-gray-900">Rp {{ number_format($biayaLayanan, 0, ',', '.') }}</span>
                        </div>
                        <div class="border-t border-gray-100 pt-4">
                            <div class="flex justify-between">
                                <span class="font-bold text-gray-800">Total Pembayaran</span>
                                <span class="text-xl font-bold text-brand-orange">Rp {{ number_format($totalBayar, 0, ',', '.') }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-3xl p-6 md:p-8 border border-gray-100 shadow-sm">
                    <h3 class="font-bold text-xl text-gray-900 mb-6">Metode Pembayaran</h3>

                    <div class="space-y-3 mb-8">
                        <label class="flex items-center p-4 border-2 border-brand-orange bg-brand-orange/5 rounded-2xl cursor-pointer transition-all">
                            <input type="radio" name="metode_pembayaran" value="transfer" class="mr-3 accent-brand-orange" checked>
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-brand-orange/15 text-brand-orange rounded-xl flex items-center justify-center">
                                    <i class="fa-solid fa-building-columns"></i>
                                </div>
                                <div>
                                    <span class="font-bold text-gray-900 text-sm">Transfer Bank</span>
                                    <p class="text-xs text-gray-500">BCA, Mandiri, BNI</p>
                                </div>
                            </div>
                        </label>
                        <label class="flex items-center p-4 border-2 border-transparent hover:border-gray-200 bg-white rounded-2xl cursor-pointer transition-all">
                            <input type="radio" name="metode_pembayaran" value="virtual_account" class="mr-3 accent-brand-orange">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-gray-100 text-gray-600 rounded-xl flex items-center justify-center">
                                    <i class="fa-solid fa-wallet"></i>
                                </div>
                                <div>
                                    <span class="font-bold text-gray-900 text-sm">Virtual Account</span>
                                    <p class="text-xs text-gray-500">Generate VA BCA/Mandiri/BNI</p>
                                </div>
                            </div>
                        </label>
                        <label class="flex items-center p-4 border-2 border-transparent hover:border-gray-200 bg-white rounded-2xl cursor-pointer transition-all">
                            <input type="radio" name="metode_pembayaran" value="cash" class="mr-3 accent-brand-orange">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-gray-100 text-gray-600 rounded-xl flex items-center justify-center">
                                    <i class="fa-solid fa-money-bill"></i>
                                </div>
                                <div>
                                    <span class="font-bold text-gray-900 text-sm">Cash</span>
                                    <p class="text-xs text-gray-500">Bayar langsung di properti</p>
                                </div>
                            </div>
                        </label>
                    </div>

                    <div class="mb-8">
                        <label class="block text-sm font-bold text-gray-700 mb-2">Upload Bukti Pembayaran</label>
                        <div class="border-2 border-dashed border-gray-200 rounded-2xl p-6 text-center hover:border-brand-orange transition-colors cursor-pointer" onclick="document.getElementById('bukti').click()">
                            <i class="fa-solid fa-cloud-arrow-up text-3xl text-gray-300 mb-2"></i>
                            <p class="text-sm text-gray-500">Klik untuk upload foto bukti transfer</p>
                            <p class="text-xs text-gray-400 mt-1">Format: JPG, PNG. Maks 2MB</p>
                            <input type="file" id="bukti" name="bukti_pembayaran" class="hidden" accept="image/jpeg,image/png" onchange="previewFile(event)">
                        </div>
                        <div id="preview" class="hidden mt-4 relative">
                            <img id="preview-img" class="w-full h-48 object-cover rounded-2xl border border-gray-200">
                            <button type="button" class="absolute top-2 right-2 bg-red-500 text-white w-8 h-8 rounded-full flex items-center justify-center" onclick="removeFile()">
                                <i class="fa-solid fa-xmark"></i>
                            </button>
                        </div>
                    </div>

                    <button type="submit" class="w-full bg-brand-orange hover:bg-brand-orange-hover active:scale-[0.98] text-white font-bold py-4 rounded-xl transition-all duration-200 text-lg shadow-lg shadow-orange-900/20">
                        <i class="fa-regular fa-paper-plane mr-2"></i>
                        Kirim Bukti Pembayaran
                    </button>
                </div>
            </form>
        </main>
    </div>

    <script>
        function previewFile(event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('preview-img').src = e.target.result;
                    document.getElementById('preview').classList.remove('hidden');
                };
                reader.readAsDataURL(file);
            }
        }

        function removeFile() {
            document.getElementById('bukti').value = '';
            document.getElementById('preview').classList.add('hidden');
        }

        document.querySelectorAll('input[name="metode_pembayaran"]').forEach(radio => {
            radio.addEventListener('change', function() {
                document.querySelectorAll('input[name="metode_pembayaran"]').forEach(r => {
                    r.closest('label').classList.remove('border-brand-orange', 'bg-brand-orange/5');
                    r.closest('label').classList.add('border-transparent');
                });
                this.closest('label').classList.add('border-brand-orange', 'bg-brand-orange/5');
                this.closest('label').classList.remove('border-transparent');
            });
        });
    </script>

</body>
</html>
