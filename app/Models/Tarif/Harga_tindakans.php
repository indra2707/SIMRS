<?php

namespace App\Models\Tarif;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Harga_tindakans extends Model
{
    use HasFactory;

    protected $table = 'harga_tindakans';

    protected $fillable = [
        'kode_tarif',
        'kode_sk',
        'kelas1',
        'kelas2',
        'kelas3',
        'kelasisolasi',
        'kelasintensif',
        'kelasvip',
        'kelasvvip',
    ];
}
