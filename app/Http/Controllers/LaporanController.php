<?php

namespace App\Http\Controllers;

use App\Models\Kunjungan;
use App\Models\Peminjaman;
use App\Models\Pengembalian;
use App\Models\Pengunjung;
use Illuminate\Http\Request;

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        $type = $request->query('type');
        $start_date = $request->query('start_date');
        $end_date = $request->query('end_date');

        $data = null;

        if ($type) {
            switch ($type) {
                case 'pengunjung':
                    $query = Pengunjung::with('user');
                    if ($start_date && $end_date) {
                        $query->whereBetween('created_at', [$start_date . ' 00:00:00', $end_date . ' 23:59:59']);
                    }
                    $data = $query->get();
                    break;
                case 'kunjungan':
                    $query = Kunjungan::with('pengunjung.user');
                    if ($start_date && $end_date) {
                        $query->whereBetween('visit_time', [$start_date . ' 00:00:00', $end_date . ' 23:59:59']);
                    }
                    $data = $query->get();
                    break;
                case 'peminjaman':
                    $query = Peminjaman::with(['pengunjung.user', 'buku']);
                    if ($start_date && $end_date) {
                        $query->whereBetween('tanggal_pinjam', [$start_date, $end_date]);
                    }
                    $data = $query->get();
                    break;
                case 'pengembalian':
                    $query = Pengembalian::with(['peminjaman.pengunjung.user', 'peminjaman.buku']);
                    if ($start_date && $end_date) {
                        $query->whereBetween('tanggal_kembali', [$start_date, $end_date]);
                    }
                    $data = $query->get();
                    break;
            }
        }

        return view('dashboard.laporan', compact('data', 'type', 'start_date', 'end_date'));
    }
}

