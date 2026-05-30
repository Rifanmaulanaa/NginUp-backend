<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreFasilitasRequest;
use App\Http\Resources\FasilitasResource;
use App\Models\Fasilitas;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class FasilitasController extends Controller
{
    public function index(): JsonResponse
    {
        $fasilitas = Fasilitas::all();

        return response()->json([
            'success' => true,
            'data'    => FasilitasResource::collection($fasilitas),
        ]);
    }

    public function store(StoreFasilitasRequest $request): JsonResponse
    {
        $fasilitas = Fasilitas::create($request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Fasilitas berhasil ditambahkan',
            'data'    => new FasilitasResource($fasilitas),
        ], 201);
    }

    public function destroy($id): JsonResponse
    {
        $fasilitas = Fasilitas::findOrFail($id);
        $fasilitas->delete();

        return response()->json([
            'success' => true,
            'message' => 'Fasilitas berhasil dihapus',
        ]);
    }
}
