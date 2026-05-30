<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreKetersediaanRequest;
use App\Http\Resources\KetersediaanPropertiResource;
use App\Models\KetersediaanProperti;
use App\Models\Properti;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class KetersediaanPropertiController extends Controller
{
    public function index($id_properti): JsonResponse
    {
        $properti = Properti::findOrFail($id_properti);
        $ketersediaan = $properti->ketersediaan()->orderBy('blocked_from')->get();

        return response()->json([
            'success' => true,
            'data'    => KetersediaanPropertiResource::collection($ketersediaan),
        ]);
    }

    public function store(StoreKetersediaanRequest $request, $id_properti): JsonResponse
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

        $ketersediaan = KetersediaanProperti::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Ketersediaan berhasil ditambahkan',
            'data'    => new KetersediaanPropertiResource($ketersediaan),
        ], 201);
    }

    public function destroy(Request $request, $id_properti, $id_ketersediaan): JsonResponse
    {
        $properti = Properti::findOrFail($id_properti);

        if ($properti->id_user !== $request->user()->id_user) {
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak memiliki akses ke properti ini',
            ], 403);
        }

        $ketersediaan = KetersediaanProperti::where('id_properti', $id_properti)
            ->findOrFail($id_ketersediaan);
        $ketersediaan->delete();

        return response()->json([
            'success' => true,
            'message' => 'Blokir ketersediaan berhasil dihapus',
        ]);
    }
}
