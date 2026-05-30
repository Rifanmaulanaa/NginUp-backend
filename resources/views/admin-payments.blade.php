@extends('layouts.admin', ['activeTab' => 'payments'])

@section('title', 'Admin - Payments')

@section('content')

<div class="w-full space-y-6">

    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Manajemen Pembayaran</h1>
        <p class="text-sm text-gray-500 mt-1">Konfirmasi atau tolak pembayaran dari traveler.</p>
    </div>

    @if (session('success'))
    <div class="p-4 bg-green-50 border border-green-200 rounded-2xl text-sm text-green-700">
        {{ session('success') }}
    </div>
    @endif

    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-gray-50 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">
                        <th class="px-6 py-4">ID</th>
                        <th class="px-6 py-4">Traveler</th>
                        <th class="px-6 py-4">Properti</th>
                        <th class="px-6 py-4">Kode</th>
                        <th class="px-6 py-4">Metode</th>
                        <th class="px-6 py-4">Jumlah</th>
                        <th class="px-6 py-4">Status Payment</th>
                        <th class="px-6 py-4">Status Booking</th>
                        <th class="px-6 py-4">Bukti</th>
                        <th class="px-6 py-4">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse ($payments as $payment)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4 font-mono text-xs">#{{ $payment->id_pembayaran }}</td>
                        <td class="px-6 py-4">
                            <span class="font-semibold text-gray-900">{{ $payment->pemesanan->user->nama ?? '-' }}</span>
                            <span class="text-xs text-gray-400 block">{{ $payment->pemesanan->user->email ?? '' }}</span>
                        </td>
                        <td class="px-6 py-4 text-gray-700">{{ $payment->pemesanan->properti->nama_properti ?? '-' }}</td>
                        <td class="px-6 py-4 font-mono text-xs">{{ $payment->code_pembayaran }}</td>
                        <td class="px-6 py-4">{{ str_replace('_', ' ', ucfirst($payment->metode_pembayaran)) }}</td>
                        <td class="px-6 py-4 font-bold text-gray-900">Rp {{ number_format($payment->jumlah_pembayaran, 0, ',', '.') }}</td>
                        <td class="px-6 py-4">
                            @php
                                $payClass = match($payment->status_pembayaran) {
                                    'paid' => 'bg-green-100 text-green-700',
                                    'failed' => 'bg-red-100 text-red-700',
                                    'refunded' => 'bg-purple-100 text-purple-700',
                                    default => 'bg-yellow-100 text-yellow-700',
                                };
                            @endphp
                            <span class="text-xs font-bold px-3 py-1.5 rounded-full {{ $payClass }}">
                                {{ ucfirst($payment->status_pembayaran) }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            @php
                                $bookClass = match($payment->pemesanan->status_pemesanan) {
                                    'confirmed' => 'bg-green-100 text-green-700',
                                    'cancelled' => 'bg-red-100 text-red-700',
                                    'ongoing' => 'bg-blue-100 text-blue-700',
                                    'completed' => 'bg-gray-100 text-gray-700',
                                    default => 'bg-yellow-100 text-yellow-700',
                                };
                            @endphp
                            <span class="text-xs font-bold px-3 py-1.5 rounded-full {{ $bookClass }}">
                                {{ ucfirst($payment->pemesanan->status_pemesanan) }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            @if ($payment->bukti_pembayaran)
                                <a href="{{ asset('storage/' . $payment->bukti_pembayaran) }}" target="_blank" class="text-brand-orange hover:underline text-xs font-semibold">
                                    <i class="fa-regular fa-image mr-1"></i> Lihat
                                </a>
                            @else
                                <span class="text-gray-400 text-xs">-</span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            @if ($payment->status_pembayaran === 'pending')
                            <div class="flex gap-2">
                                <form method="POST" action="/admin/payments/{{ $payment->id_pembayaran }}/confirm" class="inline">
                                    @csrf
                                    <input type="hidden" name="status_pembayaran" value="paid">
                                    <button type="submit" class="bg-green-500 hover:bg-green-600 text-white text-xs font-bold px-3 py-1.5 rounded-lg transition-colors">
                                        <i class="fa-solid fa-check mr-1"></i> Confirm
                                    </button>
                                </form>
                                <form method="POST" action="/admin/payments/{{ $payment->id_pembayaran }}/confirm" class="inline">
                                    @csrf
                                    <input type="hidden" name="status_pembayaran" value="failed">
                                    <button type="submit" class="bg-red-500 hover:bg-red-600 text-white text-xs font-bold px-3 py-1.5 rounded-lg transition-colors" onclick="return confirm('Tolak pembayaran ini?')">
                                        <i class="fa-solid fa-xmark mr-1"></i> Reject
                                    </button>
                                </form>
                            </div>
                            @else
                            <span class="text-xs text-gray-400">-</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="10" class="px-6 py-12 text-center text-gray-500">
                            Belum ada pembayaran.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection
