<?php

namespace App\Models\RsOnline;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TempatTidur extends Model
{
    use HasFactory;

    protected $table = 'tempat_tidur';

    protected $fillable = [
        'id_tt',
        'jenis_tt',
        'ruang',
        'kode_siranap',
        'jumlah_ruang',
        'jumlah',
        'kosong',
        'terpakai',
        'antrian',
        'covid',
    ];
}