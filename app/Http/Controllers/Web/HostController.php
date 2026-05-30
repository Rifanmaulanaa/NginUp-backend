<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Pemesanan;
use App\Models\Properti;
use App\Models\RevenueSplit;
use App\Models\Fasilitas;
use App\Models\Gambar;
use App\Models\Kamar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class HostController extends Controller
{
    public function welcome(): View
    {
        return view('host-welcome');
    }

    public function verification(): View
    {
        return view('host-verification');
    }

    public function dashboard(): View
    {
        $userId = Auth::id();
        $propertiCount = Properti::where('id_user', $userId)->count();
        $totalRevenue = RevenueSplit::where('id_user', $userId)->sum('jumlah_pemilik');
        $avgRating = \App\Models\Review::whereHas('pemesanan.properti', function ($q) use ($userId) {
            $q->where('id_user', $userId);
        })->avg('rating') ?? 0;
        $activeBookings = Pemesanan::whereHas('properti', function ($q) use ($userId) {
            $q->where('id_user', $userId);
        })->whereIn('status_pemesanan', ['confirmed', 'ongoing'])->count();
        $recentBookings = Pemesanan::with(['user', 'properti'])
            ->whereHas('properti', function ($q) use ($userId) {
                $q->where('id_user', $userId);
            })
            ->latest()
            ->take(5)
            ->get();

        return view('host-dashboard', compact('propertiCount', 'totalRevenue', 'avgRating', 'activeBookings', 'recentBookings'));
    }

    public function properties(): View
    {
        $properti = Properti::with(['gambar', 'fasilitas', 'kamar'])
            ->where('id_user', Auth::id())
            ->latest()
            ->get();

        $propertiJson = $properti->map(function ($p) {
            return [
                'id_properti'      => $p->id_properti,
                'nama_properti'    => $p->nama_properti,
                'tipe_properti'    => $p->tipe_properti,
                'deskripsi'        => $p->deskripsi,
                'alamat'           => $p->alamat,
                'kota'             => $p->kota,
                'provinsi'         => $p->provinsi,
                'harga_per_malam'  => $p->harga_per_malam,
                'max_tamu'         => $p->max_tamu,
                'jumlah_ruang'     => $p->jumlah_ruang,
                'status'           => $p->status,
                'verified_status'  => $p->verified_status,
                'review_avg_rating' => $p->review_avg_rating ?? 0,
                'gambar'           => $p->gambar->map(fn ($g) => ['url' => $g->url]),
                'fasilitas'        => $p->fasilitas->map(fn ($f) => [
                    'id_fasilitas'   => $f->id_fasilitas,
                    'nama_fasilitas' => $f->nama_fasilitas,
                    'ikon_fasilitas' => $f->ikon_fasilitas,
                ]),
                'kamar'            => $p->kamar->map(fn ($k) => [
                    'id_kamar'           => $k->id_kamar,
                    'nama_kamar'         => $k->nama_kamar,
                    'kapasitas'          => $k->kapasitas,
                    'jumlah_tempat_tidur' => $k->jumlah_tempat_tidur,
                    'tipe_tempat_tidur'   => $k->tipe_tempat_tidur,
                    'harga_per_malam'     => $k->harga_per_malam,
                    'status'              => $k->status,
                ]),
            ];
        });

        return view('host-properties', compact('properti', 'propertiJson'));
    }

    public function addNew(): View
    {
        $fasilitasList = Fasilitas::all()->unique('nama_fasilitas');
        return view('host-add-new', compact('fasilitasList'));
    }

    public function storeProperty(Request $request)
    {
        $validated = $request->validate([
            'nama_properti'  => 'required|string|max:200',
            'tipe_properti'  => 'required|in:hotel,villa,apartemen,kost,rumah,guesthouse',
            'deskripsi'      => 'nullable|string|max:5000',
            'alamat'         => 'required|string|max:500',
            'kota'           => 'required|string|max:100',
            'provinsi'       => 'required|string|max:100',
            'harga_per_malam' => 'required|numeric|min:0',
            'max_tamu'       => 'required|integer|min:1',
            'jumlah_ruang'   => 'required|integer|min:0',
            'fasilitas'      => 'nullable|array',
            'fasilitas.*'    => 'exists:fasilitas,id_fasilitas',
            'latitude'       => 'nullable|numeric',
            'longitude'      => 'nullable|numeric',
            'gambar'         => 'nullable|array',
            'gambar.*'       => 'image|mimes:jpeg,png,jpg|max:5120',
        ]);

        DB::transaction(function () use ($validated, $request) {
            $properti = Properti::create([
                'id_user'         => Auth::id(),
                'nama_properti'   => $validated['nama_properti'],
                'tipe_properti'   => $validated['tipe_properti'],
                'deskripsi'       => $validated['deskripsi'] ?? null,
                'latitude'        => !empty($validated['latitude']) ? $validated['latitude'] : null,
                'longitude'       => !empty($validated['longitude']) ? $validated['longitude'] : null,
                'alamat'          => $validated['alamat'],
                'kota'            => $validated['kota'],
                'provinsi'        => $validated['provinsi'],
                'harga_per_malam' => $validated['harga_per_malam'],
                'max_tamu'        => $validated['max_tamu'],
                'jumlah_ruang'    => $validated['jumlah_ruang'],
                'status'          => 'active',
                'verified_status' => 'pending',
            ]);

            if (!empty($validated['fasilitas'])) {
                $properti->fasilitas()->attach($validated['fasilitas']);
            }

            if ($request->hasFile('gambar')) {
                foreach ($request->file('gambar') as $file) {
                    $path = $file->store('properti', 'public');
                    Gambar::create([
                        'id_properti' => $properti->id_properti,
                        'url_gambar'  => 'storage/' . $path,
                    ]);
                }
            }
        });

        return redirect('/host/dashboard')->with('success', 'Properti berhasil ditambahkan!');
    }

    public function updateProperty(Request $request, $id)
    {
        $properti = Properti::where('id_user', Auth::id())->findOrFail($id);

        $validated = $request->validate([
            'nama_properti'  => 'required|string|max:200',
            'tipe_properti'  => 'required|in:hotel,villa,apartemen,kost,rumah,guesthouse',
            'deskripsi'      => 'nullable|string|max:5000',
            'alamat'         => 'required|string|max:500',
            'kota'           => 'required|string|max:100',
            'provinsi'       => 'required|string|max:100',
            'harga_per_malam' => 'required|numeric|min:0',
            'max_tamu'       => 'required|integer|min:1',
            'jumlah_ruang'   => 'required|integer|min:0',
            'latitude'       => 'nullable|numeric',
            'longitude'      => 'nullable|numeric',
        ]);

        $properti->update([
            'nama_properti'  => $validated['nama_properti'],
            'tipe_properti'  => $validated['tipe_properti'],
            'deskripsi'      => $validated['deskripsi'] ?? null,
            'alamat'         => $validated['alamat'],
            'kota'           => $validated['kota'],
            'provinsi'       => $validated['provinsi'],
            'harga_per_malam' => $validated['harga_per_malam'],
            'max_tamu'       => $validated['max_tamu'],
            'jumlah_ruang'   => $validated['jumlah_ruang'],
            'latitude'       => !empty($validated['latitude']) ? $validated['latitude'] : null,
            'longitude'      => !empty($validated['longitude']) ? $validated['longitude'] : null,
        ]);

        if ($request->hasFile('gambar')) {
            foreach ($request->file('gambar') as $file) {
                $path = $file->store('properti', 'public');
                $properti->gambar()->create([
                    'url_gambar' => 'storage/' . $path,
                ]);
            }
        }

        return redirect('/host/properties')->with('success', 'Properti berhasil diperbarui!');
    }

    public function rooms($id): View
    {
        $properti = Properti::with('kamar')
            ->where('id_user', Auth::id())
            ->findOrFail($id);

        return view('host-rooms', compact('properti'));
    }

    public function storeRoom(Request $request, $id)
    {
        $properti = Properti::where('id_user', Auth::id())->findOrFail($id);

        $validated = $request->validate([
            'nama_kamar'          => 'required|string|max:200',
            'kapasitas'           => 'required|integer|min:1',
            'jumlah_tempat_tidur' => 'required|integer|min:1',
            'tipe_tempat_tidur'   => 'required|in:single,double,queen,king,twin,bunk',
            'harga_per_malam'     => 'nullable|numeric|min:0',
        ]);

        $properti->kamar()->create($validated);

        return redirect("/host/properties/{$id}/rooms")
            ->with('success', 'Kamar berhasil ditambahkan!');
    }

    public function destroyRoom($id, $roomId)
    {
        $properti = Properti::where('id_user', Auth::id())->findOrFail($id);
        $kamar = $properti->kamar()->findOrFail($roomId);
        $kamar->delete();

        return redirect("/host/properties/{$id}/rooms")
            ->with('success', 'Kamar berhasil dihapus.');
    }

    public function reports(): View
    {
        $revenue = RevenueSplit::where('id_user', Auth::id())
            ->with('pemesanan.properti')
            ->orderBy('id_revenue_split', 'desc')
            ->get();

        $totalIncome      = $revenue->sum('jumlah_kotor');
        $platformFees     = $revenue->sum('jumlah_biaya_platform');
        $netProfit        = $revenue->sum('jumlah_pemilik');
        $unpaidPayout     = $revenue->where('status', 'pending')->sum('jumlah_pemilik');

        $totalMalam = $revenue->reduce(function ($carry, $rs) {
            return $carry + ($rs->pemesanan->total_malam ?? 0);
        }, 0);

        $propertyIds = $revenue->pluck('pemesanan.id_properti')->unique()->filter();
        $bookedProperties  = $propertyIds->count();
        $ownedProperties   = \App\Models\Properti::where('id_user', Auth::id())->count();

        $settledCount = $revenue->where('status', 'settled')->count();
        $pendingCount = $revenue->where('status', 'pending')->count();

        return view('host-reports', compact(
            'revenue', 'totalIncome', 'platformFees', 'netProfit', 'unpaidPayout',
            'totalMalam', 'bookedProperties', 'ownedProperties',
            'settledCount', 'pendingCount'
        ));
    }

    public function account(): View
    {
        $user = Auth::user();
        $propertiCount = Properti::where('id_user', $user->id_user)->count();
        $totalRevenue = RevenueSplit::where('id_user', $user->id_user)->sum('jumlah_pemilik');
        $ulasanCount = (int) \App\Models\Review::whereHas('pemesanan.properti', function ($q) use ($user) {
            $q->where('id_user', $user->id_user);
        })->count();
        $totalBookings = Pemesanan::whereHas('properti', function ($q) use ($user) {
            $q->where('id_user', $user->id_user);
        })->count();

        return view('host-account', compact('user', 'propertiCount', 'totalRevenue', 'ulasanCount', 'totalBookings'));
    }
}
