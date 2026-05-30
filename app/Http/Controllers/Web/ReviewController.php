<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Pemesanan;
use App\Models\Review;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'id_pesanan'  => 'required|exists:pemesanan,id_pesanan',
            'id_properti' => 'required|exists:properti,id_properti',
            'rating'      => 'required|integer|min:1|max:5',
            'komentar'    => 'nullable|string|max:1000',
        ]);

        $pemesanan = Pemesanan::where('id_pesanan', $validated['id_pesanan'])
            ->where('id_user', Auth::id())
            ->firstOrFail();

        if ($pemesanan->review()->exists()) {
            return back()->with('error', 'Anda sudah memberikan ulasan untuk pemesanan ini.');
        }

        Review::create([
            'id_pesanan'  => $validated['id_pesanan'],
            'id_user'     => Auth::id(),
            'id_properti' => $validated['id_properti'],
            'rating'      => $validated['rating'],
            'komentar'    => $validated['komentar'],
        ]);

        return redirect("/booking-detail/{$validated['id_pesanan']}")
            ->with('success', 'Ulasan berhasil dikirim! Terima kasih atas masukan Anda.');
    }

    public function update(Request $request, $id): RedirectResponse
    {
        $review = Review::where('id_review', $id)
            ->where('id_user', Auth::id())
            ->firstOrFail();

        $validated = $request->validate([
            'rating'   => 'required|integer|min:1|max:5',
            'komentar' => 'nullable|string|max:1000',
        ]);

        $review->update($validated);

        return redirect("/booking-detail/{$review->id_pesanan}")
            ->with('success', 'Ulasan berhasil diperbarui.');
    }
}
