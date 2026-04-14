<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use App\Repositories\PengunjungRepository;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;

class SiswaImport implements ToCollection, WithHeadingRow, SkipsEmptyRows
{
    protected $repository;

    public function __construct()
    {
        $this->repository = new PengunjungRepository();
    }

    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            $nama = $row['nama_lengkap'] ?? null;
            $nisn = $row['nisn'] ?? null;
            $kelas = $row['tingkat_rombel'] ?? null;

            if (!$nama || !$nisn) {
                continue;
            }

            try {
                $this->repository->updateOrCreateSiswa([
                    'nama' => $nama,
                    'nisn' => $nisn,
                    'kelas' => $kelas,
                ]);
            } catch (\Exception $e) {
                continue;
            }
        }
    }
}
