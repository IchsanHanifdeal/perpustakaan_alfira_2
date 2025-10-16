<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        DB::table('users')->insert([
            [
                'nama' => "Alfira Hafidzah",
                'email' => "admin@gmail.com",
                'email_verified_at' => now(),
                'password' => Hash::make('password'),
                'role' => "admin",
            ],
        ]);
    }
}
