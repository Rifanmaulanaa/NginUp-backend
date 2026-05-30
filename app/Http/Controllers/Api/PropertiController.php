<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePropertiRequest;
use App\Http\Requests\SyncAturanRequest;
use App\Http\Requests\SyncFasilitasRequest;
use App\Http\Requests\UpdatePropertiRequest;
use App\Http\Requests\VerifyPropertiRequest;
use App\Http\Resources\AturanResource;
use App\Http\Resources\FasilitasResource;
use App\Http\Resources\PropertiResource;
use App\Models\Properti;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PropertiController extends Controller
{
    public function index(Request $request)
    {
        $query = Properti::with(['gambar', 'fasilitas', 'aturan']);

        if ($request->filled('kota')) {
            $query->where('kota', 'like', '%' . $request->kota . '%');
        }

        if ($request->filled('tipe_properti')) {
            $query->where('tipe_properti', $request->tipe_properti);
        }

        if ($request->filled('min_harga')) {
            $query->where('harga_per_malam', '>=', $request->min_harga);
        }

        if ($request->filled('max_harga')) {
            $query->where('harga_per_malam', '<=', $request->max_harga);
        }

        if ($request->filled('max_tamu')) {
            $query->where('max_tamu', '>=', $request->max_tamu);
        }

        if ($request->filled('id_user')) {
            $query->where('id_user', $request->id_user);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $properti = $query->paginate($request->per_page ?? 10);

        return PropertiResource::collection($properti)->additional([
            'success' => true,
        ]);
    }

    public function store(StorePropertiRequest $request): JsonResponse
    {
        $validated = $request->validated();
        $validated['id_user'] = $request->user()->id_user;
        $validated['verified_status'] = 'pending';

        $properti = Properti::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Properti berhasil ditambahkan',
            'data'    => new PropertiResource($properti),
        ], 201);
    }

    public function show($id): JsonResponse
    {
        $properti = Properti::with([
            'gambar', 'fasilitas', 'aturan', 'user', 'review.user', 'ketersediaan'
        ])->findOrFail($id);

        return response()->json([
            'success' => true,
            'data'    => new PropertiResource($properti),
        ]);
    }

    public function update(UpdatePropertiRequest $request, $id): JsonResponse
    {
        $properti = Properti::findOrFail($id);

        if ($properti->id_user !== $request->user()->id_user) {
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak memiliki akses ke properti ini',
            ], 403);
        }

        $properti->update($request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Properti berhasil diperbarui',
            'data'    => new PropertiResource($properti),
        ]);
    }

    public function destroy(Request $request, $id): JsonResponse
    {
        $properti = Properti::findOrFail($id);
        $user = $request->user();

        if ($user->role !== 'admin' && $properti->id_user !== $user->id_user) {
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak memiliki akses ke properti ini',
            ], 403);
        }

        $properti->delete();

        return response()->json([
            'success' => true,
            'message' => 'Properti berhasil dihapus',
        ]);
    }

    public function verify(VerifyPropertiRequest $request, $id): JsonResponse
    {
        $properti = Properti::findOrFail($id);
        $validated = $request->validated();

        $properti->update(['verified_status' => $validated['verified_status']]);

        if ($validated['verified_status'] === 'verified') {
            $properti->update(['status' => 'active']);
        }

        return response()->json([
            'success' => true,
            'message' => 'Status verifikasi properti diperbarui',
            'data'    => new PropertiResource($properti),
        ]);
    }

    public function syncFasilitas(SyncFasilitasRequest $request, $id): JsonResponse
    {
        $properti = Properti::findOrFail($id);

        if ($properti->id_user !== $request->user()->id_user) {
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak memiliki akses ke properti ini',
            ], 403);
        }

        $properti->fasilitas()->sync($request->validated()['id_fasilitas']);

        return response()->json([
            'success' => true,
            'message' => 'Fasilitas berhasil disinkronkan',
            'data'    => FasilitasResource::collection($properti->fasilitas),
        ]);
    }

    public function syncAturan(SyncAturanRequest $request, $id): JsonResponse
    {
        $properti = Properti::findOrFail($id);

        if ($properti->id_user !== $request->user()->id_user) {
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak memiliki akses ke properti ini',
            ], 403);
        }

        $properti->aturan()->sync($request->validated()['id_aturan']);

        return response()->json([
            'success' => true,
            'message' => 'Aturan berhasil disinkronkan',
            'data'    => AturanResource::collection($properti->aturan),
        ]);
    }
}
