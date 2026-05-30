<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateUserStatusRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AdminUserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::query();

        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                  ->orWhere('username', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $users = $query->orderBy('created_at', 'desc')->paginate($request->per_page ?? 10);

        return UserResource::collection($users)->additional([
            'success' => true,
        ]);
    }

    public function show($id): JsonResponse
    {
        $user = User::withCount(['properti', 'pemesanan', 'review'])->findOrFail($id);

        return response()->json([
            'success' => true,
            'data'    => new UserResource($user),
        ]);
    }

    public function updateStatus(UpdateUserStatusRequest $request, $id): JsonResponse
    {
        $user = User::findOrFail($id);

        if ($user->id_user === $request->user()->id_user) {
            return response()->json([
                'success' => false,
                'message' => 'Tidak bisa mengubah status akun sendiri',
            ], 403);
        }

        $user->update(['status' => $request->validated()['status']]);

        return response()->json([
            'success' => true,
            'message' => 'Status user berhasil diperbarui',
            'data'    => new UserResource($user),
        ]);
    }

    public function destroy(Request $request, $id): JsonResponse
    {
        $user = User::findOrFail($id);

        if ($user->id_user === $request->user()->id_user) {
            return response()->json([
                'success' => false,
                'message' => 'Tidak bisa menghapus akun sendiri',
            ], 403);
        }

        $user->delete();

        return response()->json([
            'success' => true,
            'message' => 'User berhasil dihapus',
        ]);
    }
}
