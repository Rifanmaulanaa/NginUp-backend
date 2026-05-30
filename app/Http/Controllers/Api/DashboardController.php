<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Pembayaran;
use App\Models\Pemesanan;
use App\Models\Properti;
use App\Models\RevenueSplit;
use App\Models\User;
use Illuminate\Http\JsonResponse;

class DashboardController extends Controller
{
    public function index(): JsonResponse
    {
        $totalUsers    = User::count();
        $totalOwners   = User::where('role', 'owner')->count();
        $totalTravelers = User::where('role', 'traveler')->count();
        $totalAdmins   = User::where('role', 'admin')->count();

        $totalProperti      = Properti::count();
        $propertiVerified   = Properti::where('verified_status', 'verified')->count();
        $propertiPending    = Properti::where('verified_status', 'pending')->count();
        $propertiRejected   = Properti::where('verified_status', 'rejected')->count();
        $propertiActive     = Properti::where('status', 'active')->count();
        $propertiDraft      = Properti::where('status', 'draft')->count();

        $totalBookings         = Pemesanan::count();
        $bookingPending        = Pemesanan::where('status_pemesanan', 'pending')->count();
        $bookingConfirmed      = Pemesanan::where('status_pemesanan', 'confirmed')->count();
        $bookingOngoing        = Pemesanan::where('status_pemesanan', 'ongoing')->count();
        $bookingCompleted      = Pemesanan::where('status_pemesanan', 'completed')->count();
        $bookingCancelled      = Pemesanan::where('status_pemesanan', 'cancelled')->count();

        $totalRevenue = Pembayaran::where('status_pembayaran', 'paid')
            ->sum('jumlah_pembayaran');

        $platformRevenue = RevenueSplit::sum('jumlah_biaya_platform');
        $ownerPayout     = RevenueSplit::sum('jumlah_pemilik');
        $settledSplits   = RevenueSplit::where('status', 'settled')->sum('jumlah_biaya_platform');
        $pendingSplits   = RevenueSplit::where('status', 'pending')->sum('jumlah_biaya_platform');

        return response()->json([
            'success' => true,
            'data'    => [
                'users' => [
                    'total'    => $totalUsers,
                    'owner'    => $totalOwners,
                    'traveler' => $totalTravelers,
                    'admin'    => $totalAdmins,
                ],
                'properti' => [
                    'total'    => $totalProperti,
                    'verified' => $propertiVerified,
                    'pending'  => $propertiPending,
                    'rejected' => $propertiRejected,
                    'active'   => $propertiActive,
                    'draft'    => $propertiDraft,
                ],
                'bookings' => [
                    'total'     => $totalBookings,
                    'pending'   => $bookingPending,
                    'confirmed' => $bookingConfirmed,
                    'ongoing'   => $bookingOngoing,
                    'completed' => $bookingCompleted,
                    'cancelled' => $bookingCancelled,
                ],
                'revenue' => [
                    'total_pembayaran' => (float) $totalRevenue,
                    'platform_fee'     => (float) $platformRevenue,
                    'owner_payout'     => (float) $ownerPayout,
                    'settled'          => (float) $settledSplits,
                    'pending'          => (float) $pendingSplits,
                ],
            ],
        ]);
    }
}
