<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pengunjung extends Model
{
    protected $fillable = [
        'user_id',
        'nisn',
        'kelas',
    ];

    public function user()
    {
        return $this->belongsTo(User::class,  'user_id');
    }
}
