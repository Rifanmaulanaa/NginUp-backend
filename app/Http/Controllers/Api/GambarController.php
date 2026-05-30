<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreGambarRequest;
use App\Http\Requests\UpdateGambarRequest;
use App\Http\Resources\GambarResource;
use App\Models\Gambar;
use App\Models\Properti;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class GambarController extends Controller
{
    public function index($id_properti): JsonResponse
    {
        $properti = Properti::findOrFail($id_properti);
        $gambar   = $properti->gambar()->orderBy('urutan')->get();

        return response()->json([
            'success' => true,
            'data'    => GambarResource::collection($gambar),
        ]);
    }

    public function store(StoreGambarRequest $request, $id_properti): JsonResponse
    {
        $properti = Properti::findOrFail($id_properti);

        if ($properti->id_user !== $request->user()->id_user) {
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak memiliki akses ke properti ini',
            ], 403);
        }

        $validated = $request->validated();
        $validated['id_properti'] = $id_properti;

        if ($validated['is_primary'] ?? false) {
            Gambar::where('id_properti', $id_properti)->update(['is_primary' => false]);
        }

        $gambar = Gambar::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Gambar berhasil ditambahkan',
            'data'    => new GambarResource($gambar),
        ], 201);
    }

    public function update(UpdateGambarRequest $request, $id_properti, $id_gambar): JsonResponse
    {
        $properti = Properti::findOrFail($id_properti);

        if ($properti->id_user !== $request->user()->id_user) {
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak memiliki akses ke properti ini',
            ], 403);
        }

        $gambar = Gambar::where('id_properti', $id_properti)->findOrFail($id_gambar);

        $validated = $request->validated();

        if ($validated['is_primary'] ?? false) {
            Gambar::where('id_properti', $id_properti)->update(['is_primary' => false]);
        }

        $gambar->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Gambar berhasil diperbarui',
            'data'    => new GambarResource($gambar),
        ]);
    }

    public function destroy(Request $request, $id_properti, $id_gambar): JsonResponse
    {
        $properti = Properti::findOrFail($id_properti);

        if ($properti->id_user !== $request->user()->id_user) {
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak memiliki akses ke properti ini',
            ], 403);
        }

        $gambar = Gambar::where('id_properti', $id_properti)->findOrFail($id_gambar);
        $gambar->delete();

        return response()->json([
            'success' => true,
            'message' => 'Gambar berhasil dihapus',
        ]);
    }
}
