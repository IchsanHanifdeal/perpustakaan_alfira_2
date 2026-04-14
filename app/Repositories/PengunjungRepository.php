<?php

namespace App\Repositories;

use App\Models\User;
use App\Models\Pengunjung;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class PengunjungRepository
{
    public function updateOrCreateSiswa(array $data)
    {
        return DB::transaction(function () use ($data) {
            $nisn = str_replace("'", "", $data['nisn']);
            $email = $nisn . '@gmail.com'; 

            $existingPengunjung = Pengunjung::where('nisn', $nisn)->first();

            if ($existingPengunjung) {
                $user = $existingPengunjung->user;
                $user->update([
                    'nama' => $data['nama'],
                ]);

                $existingPengunjung->update([
                    'kelas' => $data['kelas'],
                ]);

                return $existingPengunjung;
            } else {
                $user = User::where('email', $email)->first();
                
                if (!$user) {
                    $user = User::create([
                        'nama' => $data['nama'],
                        'email' => $email,
                        'password' => Hash::make($nisn),
                        'role' => 'user', // Sesuaikan dengan enum: admin, user
                    ]);
                }

                return Pengunjung::create([
                    'user_id' => $user->id,
                    'nisn' => $nisn,
                    'kelas' => $data['kelas'],
                ]);
            }
        });
    }
}
