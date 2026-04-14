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

class ProcessBukuImportJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $count;

    public function __construct(int $count)
    {
        $this->count = $count;
    }

    public function handle(BukuRepository $repository)
    {
        Log::info("Memulai pencarian buku cerdas untuk mendapatkan " . $this->count . " buku baru.");

        $keywords = ['indonesia', 'programming', 'history', 'science', 'fiction', 'novel', 'horror', 'technology', 'art'];
        $queuedCount = 0;
        $page = 1;

        try {
            foreach ($keywords as $keyword) {
                if ($queuedCount >= $this->count) break;

                Log::info("Mencari buku dengan keyword: " . $keyword);

                $response = Http::withHeaders([
                    'User-Agent' => 'Perpustakaan/1.0'
                ])->timeout(60)->get("https://openlibrary.org/search.json", [
                    'q' => $keyword,
                    'limit' => 20,
                    'page' => $page
                ]);

                if ($response->successful()) {
                    $books = $response->json()['docs'] ?? [];

                    foreach ($books as $book) {
                        if ($queuedCount >= $this->count) break;

                        $judul = $book['title'] ?? null;
                        if (!$judul) continue;

                        if (!$repository->findByJudul($judul)) {
                            ImportBukuJob::dispatch($book);
                            $queuedCount++;
                        }
                    }
                }

                if ($queuedCount < $this->count) {
                    Log::info("Baru mendapatkan " . $queuedCount . " buku baru. Mencoba keyword selanjutnya...");
                }
            }

            Log::info("Selesai menjadwalkan " . $queuedCount . " buku baru untuk diimport.");
        } catch (\Exception $e) {
            Log::error("Gagal saat melakukan pencarian buku cerdas: " . $e->getMessage());
        }
    }
}
