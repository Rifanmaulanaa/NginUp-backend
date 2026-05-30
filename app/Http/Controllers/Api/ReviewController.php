<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreReviewRequest;
use App\Http\Requests\UpdateReviewRequest;
use App\Http\Resources\ReviewResource;
use App\Models\Properti;
use App\Models\Review;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function index($id_properti): JsonResponse
    {
        $properti = Properti::findOrFail($id_properti);
        $review = $properti->review()->with('user')->orderBy('created_at', 'desc')->get();

        return response()->json([
            'success' => true,
            'data'    => ReviewResource::collection($review),
        ]);
    }

    public function store(StoreReviewRequest $request): JsonResponse
    {
        $validated = $request->validated();
        $validated['id_user'] = $request->user()->id_user;

        $review = Review::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Review berhasil ditambahkan',
            'data'    => new ReviewResource($review),
        ], 201);
    }

    public function update(UpdateReviewRequest $request, $id): JsonResponse
    {
        $review = Review::findOrFail($id);

        if ($review->id_user !== $request->user()->id_user) {
            return response()->json([
                'success' => false,
                'message' => 'Akses ditolak',
            ], 403);
        }

        $review->update($request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Review berhasil diperbarui',
            'data'    => new ReviewResource($review),
        ]);
    }

    public function destroy(Request $request, $id): JsonResponse
    {
        $review = Review::findOrFail($id);
        $user   = $request->user();

        if ($user->role !== 'admin' && $review->id_user !== $user->id_user) {
            return response()->json([
                'success' => false,
                'message' => 'Akses ditolak',
            ], 403);
        }

        $review->delete();

        return response()->json([
            'success' => true,
            'message' => 'Review berhasil dihapus',
        ]);
    }
}
