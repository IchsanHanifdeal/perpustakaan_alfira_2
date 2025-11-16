<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Faker\Factory as Faker;

class DummySiswa extends Command
{
    protected $signature = 'generate:siswa {jumlah=10}';
    protected $description = 'Generate data users dan siswa menggunakan Faker (Indonesia)';

    public function handle()
    {
        $jumlah = (int) $this->argument('jumlah');
        $faker = Faker::create('id_ID');

        $this->info("Mulai generate {$jumlah} data...");

        DB::beginTransaction();

        try {
            for ($i = 0; $i < $jumlah; $i++) {

                $userId = DB::table('users')->insertGetId([
                    'nama' => $faker->name(),
                    'email' => strtolower(str_replace(' ', '.', $faker->unique()->name())) . '@gmail.com',
                    'password' => Hash::make('password'),
                    'role' => 'user',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                DB::table('pengunjungs')->insert([
                    'user_id' => $userId,
                    'nisn' => $faker->unique()->numerify('##########'),
                    'kelas' => $faker->randomElement(['X', 'XI', 'XII']) . ' ' . $faker->randomElement(['IPA', 'IPS', 'RPL', 'TKJ']),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            DB::commit();

            $this->info("Berhasil generate {$jumlah} data!");
        } catch (\Exception $e) {
            DB::rollBack();
            $this->error("Gagal: " . $e->getMessage());
        }
    }
}
