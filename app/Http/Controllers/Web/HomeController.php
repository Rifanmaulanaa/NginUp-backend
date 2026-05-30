<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Properti;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(): View
    {
        $propertiPilihan = Properti::with('gambar')
            ->where('status', 'active')
            ->where('verified_status', 'verified')
            ->latest()
            ->take(6)
            ->get();

        $rekomendasi = Properti::with('gambar')
            ->where('status', 'active')
            ->where('verified_status', 'verified')
            ->inRandomOrder()
            ->take(10)
            ->get();

        $tipeList = Properti::where('status', 'active')
            ->where('verified_status', 'verified')
            ->distinct()
            ->pluck('tipe_properti')
            ->toArray();

        $kotaList = Properti::where('status', 'active')
            ->where('verified_status', 'verified')
            ->distinct()
            ->pluck('kota')
            ->toArray();
        sort($kotaList);

        return view('home', compact('propertiPilihan', 'rekomendasi', 'tipeList', 'kotaList'));
    }
}
