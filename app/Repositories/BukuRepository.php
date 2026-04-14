<?php

namespace App\Repositories;

use App\Models\Buku;

class BukuRepository
{
    public function create(array $data)
    {
        return Buku::create($data);
    }

    public function findByJudul($judul)
    {
        return Buku::where('judul', $judul)->first();
    }
}
