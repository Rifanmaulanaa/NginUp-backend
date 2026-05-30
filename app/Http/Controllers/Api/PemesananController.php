<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePemesananRequest;
use App\Http\Requests\UpdatePemesananStatusRequest;
use App\Http\Resources\PemesananResource;
use App\Models\Pemesanan;
use App\Models\Properti;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PemesananController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $query = Pemesanan::with(['properti.gambar', 'pembayaran']);

        if ($user->role === 'traveler') {
            $query->where('id_user', $user->id_user);
        } elseif ($user->role === 'owner') {
            $query->whereHas('properti', function ($q) use ($user) {
                $q->where('id_user', $user->id_user);
            });
        }

        if ($request->filled('status_pemesanan')) {
            $query->where('status_pemesanan', $request->status_pemesanan);
        }

        if ($request->filled('id_user') && $user->role === 'admin') {
            $query->where('id_user', $request->id_user);
        }

        if ($request->filled('id_properti') && $user->role === 'admin') {
            $query->where('id_properti', $request->id_properti);
        }

        $pemesanan = $query->orderBy('created_at', 'desc')->paginate($request->per_page ?? 10);

        return PemesananResource::collection($pemesanan)->additional([
            'success' => true,
        ]);
    }

    public function store(StorePemesananRequest $request): JsonResponse
    {
        $validated = $request->validated();

        if ($request->user()->role === 'admin') {
            return response()->json([
                'success' => false,
                'message' => 'Admin tidak bisa melakukan pemesanan',
            ], 403);
        }

        $properti = Properti::with('kamar')->findOrFail($validated['id_properti']);

        if ($properti->id_user === $request->user()->id_user) {
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak bisa memesan properti sendiri',
            ], 403);
        }

        // Validasi kamar milik properti ini
        if (!empty($validated['id_kamar']) && !$properti->kamar->contains('id_kamar', $validated['id_kamar'])) {
            return response()->json([
                'success' => false,
                'message' => 'Kamar tidak tersedia di properti ini',
            ], 422);
        }

        $checkIn  = Carbon::parse($validated['tanggal_check_in']);
        $checkOut = Carbon::parse($validated['tanggal_check_out']);
        $totalMalam = $checkIn->diffInDays($checkOut);

        if ($totalMalam < 1) {
            return response()->json([
                'success' => false,
                'message' => 'Minimal menginap 1 malam',
            ], 422);
        }

        // Cek bentrok — per kamar jika id_kamar diisi, per properti jika tidak
        $bentrokQuery = Pemesanan::where('status_pemesanan', '!=', 'cancelled')
            ->where(function ($q) use ($checkIn, $checkOut) {
                $q->where('tanggal_check_in', '<', $checkOut)
                  ->where('tanggal_check_out', '>', $checkIn);
            });

        if (!empty($validated['id_kamar'])) {
            $bentrokQuery->where('id_kamar', $validated['id_kamar']);
        } else {
            $bentrokQuery->where('id_properti', $validated['id_properti']);
        }

        if ($bentrokQuery->exists()) {
            $label = !empty($validated['id_kamar']) ? 'Kamar' : 'Properti';
            return response()->json([
                'success' => false,
                'message' => "$label sudah dipesan di tanggal tersebut",
            ], 409);
        }

        // Harga: pake harga kamar jika ada, else harga properti
        $hargaPerMalam = $properti->harga_per_malam;
        if (!empty($validated['id_kamar'])) {
            $kamar = $properti->kamar->find($validated['id_kamar']);
            if ($kamar && $kamar->harga_per_malam) {
                $hargaPerMalam = $kamar->harga_per_malam;
            }
        }

        $totalHarga = $totalMalam * $hargaPerMalam;

        $pemesanan = Pemesanan::create([
            'id_user'           => $request->user()->id_user,
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

        $pemesanan->load(['properti.gambar', 'pembayaran']);

        return response()->json([
            'success' => true,
            'message' => 'Pemesanan berhasil dibuat',
            'data'    => new PemesananResource($pemesanan),
        ], 201);
    }

    public function show(Request $request, $id): JsonResponse
    {
        $pemesanan = Pemesanan::with([
            'properti.gambar', 'properti.fasilitas', 'pembayaran', 'user', 'review'
        ])->findOrFail($id);

        $user = $request->user();
        if ($user->role !== 'admin') {
            if ($user->role === 'traveler' && $pemesanan->id_user !== $user->id_user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Akses ditolak',
                ], 403);
            }

            if ($user->role === 'owner' && $pemesanan->properti->id_user !== $user->id_user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Akses ditolak',
                ], 403);
            }
        }

        return response()->json([
            'success' => true,
            'data'    => new PemesananResource($pemesanan),
        ]);
    }

    public function updateStatus(UpdatePemesananStatusRequest $request, $id): JsonResponse
    {
        $pemesanan = Pemesanan::findOrFail($id);

        $validated = $request->validated();

        $user = $request->user();

        if ($user->role === 'traveler') {
            if ($pemesanan->id_user !== $user->id_user) {
                return response()->json(['success' => false, 'message' => 'Akses ditolak'], 403);
            }
            if (!in_array($validated['status_pemesanan'], ['cancelled'])) {
                return response()->json(['success' => false, 'message' => 'Aksi tidak diizinkan'], 403);
            }
        }

        if ($user->role === 'owner') {
            if ($pemesanan->properti->id_user !== $user->id_user) {
                return response()->json(['success' => false, 'message' => 'Akses ditolak'], 403);
            }
        }

        $pemesanan->update(['status_pemesanan' => $validated['status_pemesanan']]);

        return response()->json([
            'success' => true,
            'message' => 'Status pemesanan diperbarui',
            'data'    => new PemesananResource($pemesanan),
        ]);
    }
}
