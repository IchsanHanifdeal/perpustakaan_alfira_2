<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

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
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $faker = \Faker\Factory::create('id_ID');
        $jumlah = (int) $this->argument('jumlah');

        for ($i = 0; $i < $jumlah; $i++) {
            DB::table('buku')->insert([
                'cover'     => null,
                'judul'     => $faker->sentence(3),
                'penerbit'  => $faker->company,
                'penulis'   => $faker->name,
                'jenis'     => $faker->randomElement(['Novel', 'Komik', 'Sains', 'Teknologi', 'Sejarah']),
                'tahun'     => $faker->numberBetween(1990, 2025),
                'stock'     => $faker->numberBetween(1, 50),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        $this->info("Berhasil generate {$jumlah} data dummy buku!");
    }
}
