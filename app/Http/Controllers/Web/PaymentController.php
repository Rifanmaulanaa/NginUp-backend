<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Pembayaran;
use App\Models\Pemesanan;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class PaymentController extends Controller
{
    public function create($id): View
    {
        $booking = Pemesanan::with(['properti.gambar', 'pembayaran'])
            ->findOrFail($id);

        if ($booking->id_user !== Auth::id()) {
            abort(403);
        }

        if ($booking->pembayaran) {
            return redirect("/booking-detail/{$id}")->with('info', 'Pembayaran sudah pernah dikirim');
        }

        $totalSewa = $booking->total_harga;
        $biayaLayanan = round($totalSewa * 0.05);
        $totalBayar = $totalSewa + $biayaLayanan;

        return view('payment', compact('booking', 'totalSewa', 'biayaLayanan', 'totalBayar'));
    }

    public function store(Request $request, $id): RedirectResponse
    {
        $booking = Pemesanan::with('pembayaran')->findOrFail($id);

        if ($booking->id_user !== Auth::id()) {
            abort(403);
        }

        if ($booking->pembayaran) {
            return redirect("/booking-detail/{$id}")->with('info', 'Pembayaran sudah pernah dikirim');
        }

        $validated = $request->validate([
            'metode_pembayaran' => 'required|in:transfer,virtual_account,cash',
            'bukti_pembayaran'  => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $totalSewa = $booking->total_harga;
        $biayaLayanan = round($totalSewa * 0.05);
        $totalBayar = $totalSewa + $biayaLayanan;

        $lastCode = Pembayaran::max('id_pembayaran') ?? 0;
        $code = 'PAY-' . now()->format('Ymd') . '-' . str_pad($lastCode + 1, 4, '0', STR_PAD_LEFT);

        $data = [
            'id_pesanan'        => $id,
            'code_pembayaran'   => $code,
            'jumlah_pembayaran' => $totalBayar,
            'metode_pembayaran' => $validated['metode_pembayaran'],
            'status_pembayaran' => 'pending',
        ];

        if ($request->hasFile('bukti_pembayaran')) {
            $path = $request->file('bukti_pembayaran')->store('bukti-bayar', 'public');
            $data['bukti_pembayaran'] = $path;
        }

        Pembayaran::create($data);

        return redirect("/booking-detail/{$id}")->with('success', 'Bukti pembayaran berhasil dikirim, menunggu konfirmasi admin');
    }
}
