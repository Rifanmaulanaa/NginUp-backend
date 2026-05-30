<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Properti;
use Illuminate\View\View;

class PropertiController extends Controller
{
    public function show($id): View
    {
        $properti = Properti::with([
            'gambar', 'fasilitas', 'aturan', 'user', 'review.user', 'ketersediaan', 'kamar'
        ])->findOrFail($id);

        return view('detail', compact('properti'));
    }
}
