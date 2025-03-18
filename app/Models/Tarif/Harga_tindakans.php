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
        'kelas_1',
        'kelas_2',
        'kelas_3',
        'kelas_isolasi',
        'kelas_intensif',
        'kelas_vip',
        'kelas_vvip',
    ];
}
