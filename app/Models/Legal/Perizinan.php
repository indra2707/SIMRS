<?php

namespace App\Models\Legal;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Perizinan extends Model
{
    use HasFactory;

    protected $table = 'perizinan';

    protected $fillable = [
        'nomor_perizinan',
        'jenis_perizinan',
        'tgl_awal',
        'tgl_akhir',
        'upload',
        'status',
    ];
}
