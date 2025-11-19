<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use App\Models\Absensi;
use App\Models\Kunjungan;
use App\Models\Pengunjung;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        if (Auth::user()->role == 'admin') {

            $rankingPengunjung = Pengunjung::with('user')
                ->withCount('absensis')
                ->orderByDesc('absensis_count')
                ->take(10)
                ->get();

            return view('dashboard.index', [
                'totalBuku' => Buku::count(),
                'totalPengunjung' => Pengunjung::count(),
                'totalKunjungan' => Absensi::count(),
                'rankingPengunjung' => $rankingPengunjung,
            ]);
        }

        $id_user = Auth::user()->id;
        $pengunjung = Pengunjung::where('user_id', $id_user)->first();

        return view('dashboard.index', [
            'totalBuku' => Buku::count(),
            'totalPengunjung' => Pengunjung::count(),
            'totalKunjungan' => Absensi::count(),
            'kunjunganUser' => Absensi::where('pengunjung_id', $pengunjung->id)->count(),
        ]);
    }
}
