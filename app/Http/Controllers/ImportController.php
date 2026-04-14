<?php

namespace App\Http\Controllers;

use App\Http\Requests\ImportExcelRequest;
use App\Services\ImportService;
use App\Services\BukuImportService;
use Devrabiul\ToastMagic\Facades\ToastMagic;
use Illuminate\Http\Request;

class ImportController extends Controller
{
    protected $importService;
    protected $bukuImportService;

    public function __construct(ImportService $importService, BukuImportService $bukuImportService)
    {
        $this->importService = $importService;
        $this->bukuImportService = $bukuImportService;
    }

    public function index()
    {
        return view('dashboard.import');
    }

    public function store(ImportExcelRequest $request)
    {
        try {
            $this->importService->executeImport($request->file('file'));

            ToastMagic::success('Proses import sedang diproses.');

            return redirect()->route('pengunjung')->with('toast', [
                'message' => 'Import sedang diproses.',
                'type' => 'success'
            ]);
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal memulai proses import: ' . $e->getMessage());
        }
    }
    public function storeBuku(Request $request)
    {
        $request->validate([
            'jumlah' => 'required|integer|min:1|max:100',
        ]);

        try {
            $this->bukuImportService->fetchAndQueue($request->jumlah);

            ToastMagic::success('Permintaan import ' . $request->jumlah . ' buku telah dijadwalkan.');
            
            return redirect()->route('buku')->with('toast', [
                'message' => 'Proses pencarian dan import buku berjalan di latar belakang.',
                'type' => 'success'
            ]);
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal memulai import buku: ' . $e->getMessage());
        }
    }
}
