<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\NotifikasiResource;
use App\Models\Notifikasi;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class NotifikasiController extends Controller
{
    public function index(Request $request)
    {
        $notifikasi = Notifikasi::where('id_user', $request->user()->id_user)
            ->orderBy('created_at', 'desc')
            ->paginate($request->per_page ?? 20);

        return NotifikasiResource::collection($notifikasi)->additional([
            'success' => true,
        ]);
    }

    public function markAsRead(Request $request, $id): JsonResponse
    {
        $notifikasi = Notifikasi::where('id_user', $request->user()->id_user)
            ->findOrFail($id);

        $notifikasi->update(['is_read' => true]);

        return response()->json([
            'success' => true,
            'message' => 'Notifikasi ditandai sudah dibaca',
            'data'    => new NotifikasiResource($notifikasi),
        ]);
    }

    public function markAllAsRead(Request $request): JsonResponse
    {
        Notifikasi::where('id_user', $request->user()->id_user)
            ->where('is_read', false)
            ->update(['is_read' => true]);

        return response()->json([
            'success' => true,
            'message' => 'Semua notifikasi ditandai sudah dibaca',
        ]);
    }
}
