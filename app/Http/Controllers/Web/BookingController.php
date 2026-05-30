<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Pemesanan;
use App\Models\Properti;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class BookingController extends Controller
{
    public function index(): View
    {
        $bookings = Pemesanan::with(['properti.gambar', 'pembayaran', 'review'])
            ->where('id_user', Auth::id())
            ->orderBy('created_at', 'desc')
            ->get();

        return view('bookings', compact('bookings'));
    }

    public function show($id): View
    {
        $booking = Pemesanan::with([
            'properti.gambar', 'properti.fasilitas', 'pembayaran', 'user', 'review'
        ])->findOrFail($id);

        return view('booking-detail', compact('booking'));
    }

    public function checkout(Request $request): View
    {
        $properti = Properti::with(['gambar', 'kamar'])->findOrFail($request->id);
        $checkIn  = $request->check_in ? Carbon::parse($request->check_in) : null;
        $checkOut = $request->check_out ? Carbon::parse($request->check_out) : null;

        $selectedKamar = null;
        if ($request->filled('id_kamar')) {
            $selectedKamar = $properti->kamar->find($request->id_kamar);
        }

        return view('checkout', compact('properti', 'checkIn', 'checkOut', 'request', 'selectedKamar'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'id_properti'      => 'required|exists:properti,id_properti',
            'id_kamar'         => 'nullable|exists:kamar,id_kamar',
            'tanggal_check_in'  => 'required|date',
            'tanggal_check_out' => 'required|date|after:tanggal_check_in',
            'total_tamu'       => 'required|integer|min:1',
            'catatan'          => 'nullable|string',
        ]);

        $properti  = Properti::with('kamar')->findOrFail($validated['id_properti']);

        if ($properti->max_tamu < $validated['total_tamu']) {
            return back()->withErrors([
                'total_tamu' => 'Jumlah tamu melebihi kapasitas maksimal (' . $properti->max_tamu . ')',
            ])->withInput();
        }

        // Validasi kamar milik properti ini
        if ($validated['id_kamar'] && !$properti->kamar->contains('id_kamar', $validated['id_kamar'])) {
            return back()->withErrors(['id_kamar' => 'Kamar tidak tersedia di properti ini'])->withInput();
        }

        $checkIn   = Carbon::parse($validated['tanggal_check_in']);
        $checkOut  = Carbon::parse($validated['tanggal_check_out']);
        $totalMalam = $checkIn->diffInDays($checkOut);

        // Cek bentrok — per kamar jika id_kamar diisi, per properti jika tidak
        $bentrokQuery = Pemesanan::where('status_pemesanan', '!=', 'cancelled')
            ->where(function ($q) use ($checkIn, $checkOut) {
                $q->where('tanggal_check_in', '<', $checkOut)
                  ->where('tanggal_check_out', '>', $checkIn);
            });

        if ($validated['id_kamar']) {
            $bentrokQuery->where('id_kamar', $validated['id_kamar']);
        } else {
            $bentrokQuery->where('id_properti', $validated['id_properti']);
        }

        if ($bentrokQuery->exists()) {
            $label = $validated['id_kamar'] ? 'Kamar' : 'Properti';
            return back()->withErrors(['tanggal' => "$label sudah dipesan di tanggal tersebut"]);
        }

        // Harga: pake harga kamar jika ada, else harga properti
        $hargaPerMalam = $properti->harga_per_malam;
        if ($validated['id_kamar']) {
            $kamar = $properti->kamar->find($validated['id_kamar']);
            if ($kamar && $kamar->harga_per_malam) {
                $hargaPerMalam = $kamar->harga_per_malam;
            }
        }

        $totalHarga = $totalMalam * $hargaPerMalam;

        $pemesanan = Pemesanan::create([
            'id_user'           => Auth::id(),
            'id_properti'       => $validated['id_properti'],
            'id_kamar'          => $validated['id_kamar'] ?? null,
            'tanggal_check_in'  => $validated['tanggal_check_in'],
            'tanggal_check_out' => $validated['tanggal_check_out'],
            'total_malam'       => $totalMalam,
            'total_tamu'        => $validated['total_tamu'],
            'total_harga'       => $totalHarga,
            'status_pemesanan'  => 'pending',
            'catatan_traveler'  => $validated['catatan'] ?? null,
        ]);

        return redirect("/booking-detail/{$pemesanan->id_pesanan}");
    }
}
