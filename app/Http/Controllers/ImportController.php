<?php

namespace App\Http\Controllers;

use App\Http\Requests\ImportExcelRequest;
use App\Services\ImportService;
use Devrabiul\ToastMagic\Facades\ToastMagic;

class ImportController extends Controller
{
    protected $importService;

    public function __construct(ImportService $importService)
    {
        $this->importService = $importService;
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
}
