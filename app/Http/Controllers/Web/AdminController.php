<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Pembayaran;
use App\Models\Pemesanan;
use App\Models\Properti;
use App\Models\RevenueSplit;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AdminController extends Controller
{
    public function dashboard(): View
    {
        $totalUsers      = User::count();
        $totalOwners     = User::where('role', 'owner')->count();
        $totalTravelers  = User::where('role', 'traveler')->count();
        $totalProperti   = Properti::count();
        $totalBookings   = Pemesanan::count();
        $totalRevenue    = Pembayaran::where('status_pembayaran', 'paid')->sum('jumlah_pembayaran');
        $platformRevenue = RevenueSplit::sum('jumlah_biaya_platform');

        return view('admin-dashboard', array_merge(
            compact('totalUsers', 'totalOwners', 'totalTravelers',
                    'totalProperti', 'totalBookings', 'totalRevenue', 'platformRevenue'),
            ['activeTab' => 'dashboard']
        ));
    }

    public function verification(): View
    {
        $pending   = Properti::with('user')->where('verified_status', 'pending')->latest()->get();
        $verified  = Properti::with('user')->where('verified_status', 'verified')->latest()->get();
        $rejected  = Properti::with('user')->where('verified_status', 'rejected')->latest()->get();

        return view('admin-verification', compact('pending', 'verified', 'rejected'));
    }

    public function verifyProperty(Request $request, $id)
    {
        $properti = Properti::findOrFail($id);
        $validated = $request->validate([
            'verified_status' => 'required|in:verified,rejected',
        ]);

        $properti->update(['verified_status' => $validated['verified_status']]);

        if ($validated['verified_status'] === 'verified') {
            $properti->update(['status' => 'active']);
        }

        return redirect('/admin/verification')->with('success', 'Status verifikasi properti diperbarui');
    }

    public function users(): View
    {
        $users = User::latest()->paginate(20);
        return view('admin-users', compact('users'));
    }

    public function updateUserStatus(Request $request, $id)
    {
        $user = User::findOrFail($id);

        if ($user->id_user === Auth::id()) {
            return redirect('/admin/users')->with('error', 'Tidak bisa mengubah status akun sendiri');
        }

        $validated = $request->validate(['status' => 'required|in:active,banned']);
        $user->update(['status' => $validated['status']]);

        return redirect('/admin/users')->with('success', 'Status user berhasil diperbarui');
    }

    public function deleteUser(Request $request, $id)
    {
        $user = User::findOrFail($id);

        if ($user->id_user === Auth::id()) {
            return redirect('/admin/users')->with('error', 'Tidak bisa menghapus akun sendiri');
        }

        $user->delete();

        return redirect('/admin/users')->with('success', 'User berhasil dihapus');
    }

    public function properties(): View
    {
        $properti = Properti::with('user')->latest()->get();
        return view('admin-properties', compact('properti'));
    }

    public function deleteProperty($id)
    {
        $properti = Properti::findOrFail($id);
        $properti->delete();

        return redirect('/admin/properties')->with('success', 'Properti berhasil dihapus');
    }

    public function payments(): View
    {
        $payments = Pembayaran::with(['pemesanan.properti', 'pemesanan.user'])
            ->orderBy('id_pembayaran', 'desc')
            ->get();

        return view('admin-payments', compact('payments'));
    }

    public function confirmPayment(Request $request, $id): RedirectResponse
    {
        $payment = Pembayaran::findOrFail($id);
        $pemesanan = $payment->pemesanan;

        $validated = $request->validate([
            'status_pembayaran' => 'required|in:paid,failed',
        ]);

        $data = ['status_pembayaran' => $validated['status_pembayaran']];

        if ($validated['status_pembayaran'] === 'paid') {
            $data['tanggal_pembayaran'] = now();
        }

        $payment->update($data);

        if ($validated['status_pembayaran'] === 'paid') {
            $pemesanan->update(['status_pemesanan' => 'confirmed']);

            $totalKotor = $payment->jumlah_pembayaran;
            $persentasePlatform = 5.00;
            $biayaPlatform = round($totalKotor * $persentasePlatform / 100, 2);
            $jumlahPemilik = $totalKotor - $biayaPlatform;

            $pemesanan->revenueSplit()->create([
                'id_user'                 => $pemesanan->properti->id_user,
                'jumlah_kotor'            => $totalKotor,
                'persentase_biaya_platform' => $persentasePlatform,
                'jumlah_biaya_platform'   => $biayaPlatform,
                'jumlah_pemilik'          => $jumlahPemilik,
                'status'                  => 'pending',
            ]);
        }

        return redirect('/admin/payments')->with('success', 'Status pembayaran berhasil diperbarui');
    }

    public function messages(): View
    {
        return view('admin-messages');
    }

    public function monitor(): View
    {
        return view('admin-monitor');
    }

    public function settings(): View
    {
        return view('admin-settings');
    }

    public function reports(): View
    {
        $totalUsers      = User::count();
        $totalOwners     = User::where('role', 'owner')->count();
        $totalTravelers  = User::where('role', 'traveler')->count();
        $totalProperti   = Properti::count();
        $totalBookings   = Pemesanan::count();
        $totalRevenue    = Pembayaran::where('status_pembayaran', 'paid')->sum('jumlah_pembayaran');
        $platformRevenue = RevenueSplit::sum('jumlah_biaya_platform');
        $revenue = RevenueSplit::with('pemesanan.properti', 'user')->orderBy('id_revenue_split', 'desc')->get();

        // Monthly revenue (last 6 months)
        $monthlyRevenue = Pembayaran::selectRaw("
                DATE_FORMAT(tanggal_pembayaran, '%Y-%m') as bulan,
                SUM(jumlah_pembayaran) as total
            ")
            ->where('status_pembayaran', 'paid')
            ->whereNotNull('tanggal_pembayaran')
            ->groupBy('bulan')
            ->orderBy('bulan', 'desc')
            ->limit(6)
            ->get()
            ->reverse()
            ->values();

        // Regional distribution (count bookings per city)
        $regionalData = Pemesanan::selectRaw("
                p.kota,
                p.provinsi,
                COUNT(*) as total
            ")
            ->join('properti as p', 'p.id_properti', '=', 'pemesanan.id_properti')
            ->groupBy('p.kota', 'p.provinsi')
            ->orderBy('total', 'desc')
            ->limit(5)
            ->get();

        $totalRegional = $regionalData->sum('total');

        // Pending properties count (for "open tickets" analog)
        $pendingVerification = Properti::where('verified_status', 'pending')->count();

        return view('admin-dashboard', array_merge(
            compact('totalUsers', 'totalOwners', 'totalTravelers',
                    'totalProperti', 'totalBookings', 'totalRevenue',
                    'platformRevenue', 'revenue', 'monthlyRevenue',
                    'regionalData', 'totalRegional', 'pendingVerification'),
            ['activeTab' => 'reports']
        ));
    }
}
