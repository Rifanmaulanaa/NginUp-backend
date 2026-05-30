<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\RevenueSplitResource;
use App\Models\RevenueSplit;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class RevenueSplitController extends Controller
{
    public function index(Request $request)
    {
        $query = RevenueSplit::with('pemesanan.properti', 'user');

        if ($request->user()->role !== 'admin') {
            $query->where('id_user', $request->user()->id_user);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('id_user') && $request->user()->role === 'admin') {
            $query->where('id_user', $request->id_user);
        }

        $revenue = $query->orderBy('created_at', 'desc')
            ->paginate($request->per_page ?? 10);

        return RevenueSplitResource::collection($revenue)->additional([
            'success' => true,
        ]);
    }
}
