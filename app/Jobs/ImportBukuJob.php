<?php

namespace App\Jobs;

use App\Repositories\BukuRepository;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class ImportBukuJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $bookData;

    public function __construct(array $bookData)
    {
        $this->bookData = $bookData;
    }

    public function handle(BukuRepository $repository)
    {
        try {
            $judul = $this->bookData['title'] ?? 'Tanpa Judul';
            
            if ($repository->findByJudul($judul)) {
                Log::info("Buku sudah ada, melewati: " . $judul);
                return;
            }

            // Pilih penulis
            $penulis = 'Anonim';
            if (isset($this->bookData['author_name'][0])) {
                $penulis = $this->bookData['author_name'][0];
            } elseif (isset($this->bookData['authors'][0]['name'])) {
                $penulis = $this->bookData['authors'][0]['name'];
            }

            // Pilih penerbit
            $penerbit = $this->bookData['publisher'][0] ?? ($this->bookData['publishers'][0]['name'] ?? 'Anonim');
            if (is_array($penerbit)) {
                $penerbit = $penerbit['name'] ?? 'Anonim';
            }

            $repository->create([
                'judul'    => $judul,
                'cover'    => $this->getCoverUrl(), 
                'penerbit' => $penerbit,
                'penulis'  => $penulis,
                'jenis'    => $this->bookData['subject'][0] ?? 'Umum',
                'tahun'    => $this->bookData['first_publish_year'] ?? ($this->bookData['publish_year'][0] ?? date('Y')),
                'stock'    => rand(1, 5),
            ]);

            Log::info("Buku berhasil diimport: " . $judul);
        } catch (\Exception $e) {
            Log::error("Gagal import buku: " . $e->getMessage());
        }
    }

    protected function getCoverUrl()
    {
        if (isset($this->bookData['cover_i'])) {
            return "https://covers.openlibrary.org/b/id/" . $this->bookData['cover_i'] . "-L.jpg";
        }
        
        if (isset($this->bookData['isbn'][0])) {
            return "https://covers.openlibrary.org/b/isbn/" . $this->bookData['isbn'][0] . "-L.jpg";
        }

        return null;
    }
}
