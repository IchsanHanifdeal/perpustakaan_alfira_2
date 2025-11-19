<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Absensi extends Model
{
    protected $fillable = [
        'pengunjung_id',
        'visit_time',
    ];
    public function pengunjung()
    {
        return $this->belongsTo(Pengunjung::class);
    }
}
