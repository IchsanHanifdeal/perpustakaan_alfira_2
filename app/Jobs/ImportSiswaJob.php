<?php

namespace App\Jobs;

use App\Imports\SiswaImport;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;

class ImportSiswaJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $filePath;

    public function __construct($filePath)
    {
        $this->filePath = $filePath;
    }

    public function handle()
    {
        Log::info('Memulai import siswa dari file: ' . $this->filePath);

        if (!File::exists($this->filePath)) {
            Log::error('File import tidak ditemukan: ' . $this->filePath);
            return;
        }

        try {
            $import = new SiswaImport();
            $allSheets = Excel::toCollection($import, $this->filePath);

            Log::info('Berhasil membaca ' . $allSheets->count() . ' sheet.');

            foreach ($allSheets as $index => $sheetRows) {
                Log::info('Memproses sheet ke-' . ($index + 1) . ' dengan ' . $sheetRows->count() . ' baris.');
                $import->collection($sheetRows);
            }

            Log::info('Import selesai.');
        } catch (\Exception $e) {
            Log::error('Terjadi kesalahan saat import: ' . $e->getMessage());
            throw $e;
        } finally {
            if (File::exists($this->filePath)) {
                File::delete($this->filePath);
            }
        }
    }
}
