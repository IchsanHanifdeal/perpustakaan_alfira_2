<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use App\Models\Kunjungan;
use App\Models\Pengunjung;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        if (Auth::user()->role == 'admin') {
            return view('dashboard.index', [
                'totalBuku' => Buku::count(),
                'totalPengunjung' => Pengunjung::count(),
                'totalKunjungan' => Kunjungan::count(),
            ]);
        }
        $id_user = Auth::user()->id;
        $pengunjung_id = Pengunjung::where('user_id', $id_user)->first();

        return view('dashboard.index', [
            'totalBuku' => Buku::count(),
            'totalPengunjung' => Pengunjung::count(),
            'totalKunjungan' => Kunjungan::count(),
            'kunjunganUser' => Kunjungan::where('pengunjung_id', $pengunjung_id->id)->count(),
        ]);
    }
}
