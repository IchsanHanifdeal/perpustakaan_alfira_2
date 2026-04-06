<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Buku;
use Faker\Factory;
use Spatie\Browsershot\Browsershot;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class GenerateDummyBuku extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'dummy:buku {jumlah=10}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate dummy buku data dengan cover menggunakan Browsershot';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $faker = Factory::create('id_ID');
        $jumlah = (int) $this->argument('jumlah');

        $this->info("Sedang men-generate {$jumlah} buku dengan cover...");
        
        if (!Storage::disk('public')->exists('buku')) {
            Storage::disk('public')->makeDirectory('buku');
        }

        $bar = $this->output->createProgressBar($jumlah);
        $bar->start();

        for ($i = 0; $i < $jumlah; $i++) {
            $baseWords = [
                'Rahasia', 'Panduan', 'Misteri', 'Sejarah', 'Cara', 'Kumpulan', 'Seni', 'Dunia', 
                'Filosofi', 'Kisah', 'Petualangan', 'Analisis', 'Metode', 'Belajar', 'Tuntas', 
                'Sukses', 'Bahagia', 'Cinta', 'Masa', 'Lalu', 'Depan', 'Teknologi', 'Modern', 
                'Alam', 'Semesta', 'Manusia', 'Jiwa', 'Hati', 'Negeri', 'Pelangi', 'Laskar', 
                'Pulau', 'Harta', 'Karun', 'Puncak', 'Gunung', 'Laut', 'Dalam', 'Bintang', 
                'Malam', 'Cahaya', 'Keadilan', 'Membangun', 'Bisnis', 'Kreatif'
            ];
            
            $judul = Str::title(implode(' ', $faker->randomElements($baseWords, rand(2, 4))));
            $penulis = $faker->name;
            $penerbit = $faker->company;

            $colors = [
                ['#1e3a8a', '#3b82f6'], 
                ['#1e293b', '#64748b'], 
                ['#312e81', '#4f46e5'], 
                ['#431407', '#ea580c'], 
                ['#064e3b', '#10b981'], 
                ['#4c1d95', '#8b5cf6'], 
                ['#701a75', '#d946ef'], 
                ['#831843', '#db2777'], 
            ];
            $color = $faker->randomElement($colors);
            
            $html = "
                <style>
                    body { margin: 0; padding: 0; }
                </style>
                <div style='width: 300px; height: 450px; background: linear-gradient(135deg, {$color[0]} 0%, {$color[1]} 100%); color: white; font-family: \"Palatino\", \"Georgia\", serif; display: flex; flex-direction: column; justify-content: space-between; align-items: center; text-align: center; padding: 45px; box-sizing: border-box; position: relative; overflow: hidden; box-shadow: inset 0 0 100px rgba(0,0,0,0.3);'>
                    
                    <!-- Spine Shadow Effect -->
                    <div style='position: absolute; top: 0; left: 0; width: 15px; height: 100%; background: linear-gradient(to right, rgba(0,0,0,0.3), transparent);'></div>
                    <div style='position: absolute; top: 0; left: 15px; width: 2px; height: 100%; background: rgba(255,255,255,0.05);'></div>
                    
                    <!-- Artistic Border -->
                    <div style='position: absolute; top: 20px; left: 20px; right: 20px; bottom: 20px; border: 1px solid rgba(255,255,255,0.2); pointer-events: none;'></div>

                    <div style='z-index: 10;'>
                        <div style='font-size: 10px; text-transform: uppercase; letter-spacing: 4px; opacity: 0.6; margin-bottom: 30px; font-weight: 300;'>Pustaka Alfira</div>
                        <h1 style='font-size: 34px; text-transform: uppercase; margin: 0; font-weight: 900; line-height: 1.1; letter-spacing: -0.5px; text-shadow: 3px 3px 0px rgba(0,0,0,0.1);'>{$judul}</h1>
                        <div style='width: 50px; height: 3px; background: white; margin: 30px auto; border-radius: 2px; opacity: 0.8;'></div>
                    </div>

                    <div style='z-index: 10;'>
                        <div style='font-size: 14px; font-style: italic; opacity: 0.7; margin-bottom: 8px;'>Edisi Spesial Oleh</div>
                        <div style='font-size: 20px; font-weight: 400; letter-spacing: 1.5px; font-family: sans-serif; text-transform: uppercase;'>{$penulis}</div>
                    </div>

                    <div style='z-index: 10; width: 100%;'>
                        <div style='font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 2.5px; border-top: 1px solid rgba(255,255,255,0.3); padding-top: 15px; opacity: 0.9;'>{$penerbit}</div>
                    </div>
                </div>
            ";

            $filename = 'cover-' . Str::uuid() . '.png';
            $path = storage_path('app/public/buku/' . $filename);

            try {
                Browsershot::html($html)
                    ->windowSize(300, 450)
                    ->save($path);
                
                $coverUrl = $filename;
            } catch (\Exception $e) {
                $this->error("\nError generating cover: " . $e->getMessage());
                $coverUrl = null;
            }

            Buku::create([
                'cover'     => $coverUrl,
                'judul'     => $judul,
                'penerbit'  => $penerbit,
                'penulis'   => $penulis,
                'jenis'     => $faker->randomElement(['Novel', 'Komik', 'Sains', 'Teknologi', 'Sejarah', 'Biografi', 'Filsafat', 'Pendidikan']),
                'tahun'     => $faker->numberBetween(1990, 2025),
                'stock'     => $faker->numberBetween(1, 100),
            ]);

            $bar->advance();
        }

        $bar->finish();
        $this->info("\nBerhasil generate {$jumlah} data dummy buku!");
        $this->info("Simbolik link storage jika belum ada: php artisan storage:link");
    }
}

