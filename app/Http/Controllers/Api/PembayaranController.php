<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\KonfirmasiPembayaranRequest;
use App\Http\Requests\StorePembayaranRequest;
use App\Http\Resources\PembayaranResource;
use App\Models\Pembayaran;
use App\Models\Pemesanan;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PembayaranController extends Controller
{
    public function index($id_pesanan): JsonResponse
    {
        $pemesanan = Pemesanan::findOrFail($id_pesanan);
        $pembayaran = $pemesanan->pembayaran;

        return response()->json([
            'success' => true,
            'data'    => $pembayaran ? new PembayaranResource($pembayaran) : null,
        ]);
    }

    public function store(StorePembayaranRequest $request, $id_pesanan): JsonResponse
    {
        $pemesanan = Pemesanan::findOrFail($id_pesanan);

        if ($pemesanan->id_user !== $request->user()->id_user) {
            return response()->json([
                'success' => false,
                'message' => 'Akses ditolak',
            ], 403);
        }

        if ($pemesanan->pembayaran) {
            return response()->json([
                'success' => false,
                'message' => 'Pembayaran sudah ada',
            ], 409);
        }

        $validated = $request->validated();
        $validated['id_pesanan']       = $id_pesanan;
        $validated['status_pembayaran'] = 'pending';

        $pembayaran = Pembayaran::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Pembayaran berhasil dikirim',
            'data'    => new PembayaranResource($pembayaran),
        ], 201);
    }

    public function show($id_pesanan, $id_pembayaran): JsonResponse
    {
        $pembayaran = Pembayaran::where('id_pesanan', $id_pesanan)->findOrFail($id_pembayaran);

        return response()->json([
            'success' => true,
            'data'    => new PembayaranResource($pembayaran),
        ]);
    }

    public function konfirmasi(KonfirmasiPembayaranRequest $request, $id_pesanan, $id_pembayaran): JsonResponse
    {
        $pembayaran = Pembayaran::where('id_pesanan', $id_pesanan)->findOrFail($id_pembayaran);
        $pemesanan  = $pembayaran->pemesanan;

        if ($request->user()->role !== 'admin') {
            return response()->json([
                'success' => false,
                'message' => 'Akses ditolak',
            ], 403);
        }

        $validated = $request->validated();

        $data = ['status_pembayaran' => $validated['status_pembayaran']];

        if ($validated['status_pembayaran'] === 'paid') {
            $data['tanggal_pembayaran'] = Carbon::now();
        }

        $pembayaran->update($data);

        if ($validated['status_pembayaran'] === 'paid') {
            $pemesanan->update(['status_pemesanan' => 'confirmed']);

            $totalKotor = $pembayaran->jumlah_pembayaran;
            $persentasePlatform = 5.00;
            $biayaPlatform = round($totalKotor * $persentasePlatform / 100, 2);
            $jumlahPemilik = $totalKotor - $biayaPlatform;

            $pemesanan->revenueSplit()->create([
                'id_user'                 => $pemesanan->properti->id_user,
                'jumlah_kotor'            => $totalKotor,
                'persentase_biaya_platform' => $persentasePlatform,
                'jumlah_biaya_platform'   => $biayaPlatform,
                'jumlah_pemilik'          => $jumlahPemilik,
                'status'                  => 'pending',
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Status pembayaran diperbarui',
            'data'    => new PembayaranResource($pembayaran),
        ]);
    }
}
