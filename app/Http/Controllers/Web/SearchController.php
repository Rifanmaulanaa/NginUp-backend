<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Properti;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SearchController extends Controller
{
    public function filter(): View
    {
        return view('filter');
    }

    public function index(Request $request): View
    {
        $query = Properti::with('gambar')
            ->where('status', 'active')
            ->where('verified_status', 'verified');

        if ($request->filled('kota')) {
            $query->where('kota', 'like', '%' . $request->kota . '%');
        }

        if ($request->filled('tipe_properti')) {
            $query->where('tipe_properti', $request->tipe_properti);
        }

        if ($request->filled('min_harga')) {
            $query->where('harga_per_malam', '>=', $request->min_harga);
        }

        if ($request->filled('max_harga')) {
            $query->where('harga_per_malam', '<=', $request->max_harga);
        }

        if ($request->filled('max_tamu')) {
            $query->where('max_tamu', '>=', $request->max_tamu);
        }

        $properti = $query->paginate(12);

        return view('search', [
            'properti'     => $properti,
            'query'        => $request->input('kota', ''),
            'checkIn'      => $request->input('check_in', ''),
            'checkOut'     => $request->input('check_out', ''),
            'maxTamu'      => $request->input('max_tamu', ''),
            'tipeProperti' => $request->input('tipe_properti', ''),
        ]);
    }
}
