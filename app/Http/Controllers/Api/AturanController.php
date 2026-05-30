<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreAturanRequest;
use App\Http\Resources\AturanResource;
use App\Models\Aturan;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AturanController extends Controller
{
    public function index(): JsonResponse
    {
        $aturan = Aturan::all();

        return response()->json([
            'success' => true,
            'data'    => AturanResource::collection($aturan),
        ]);
    }

    public function store(StoreAturanRequest $request): JsonResponse
    {
        $aturan = Aturan::create($request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Aturan berhasil ditambahkan',
            'data'    => new AturanResource($aturan),
        ], 201);
    }

    public function destroy($id): JsonResponse
    {
        $aturan = Aturan::findOrFail($id);
        $aturan->delete();

        return response()->json([
            'success' => true,
            'message' => 'Aturan berhasil dihapus',
        ]);
    }
}
