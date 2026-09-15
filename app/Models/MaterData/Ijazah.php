<?php

namespace App\Models\MaterData;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ijazah extends Model
{
    use HasFactory;

    protected $table = 'tbl_ijazah';

    protected $fillable = [
        'id',
        'id_pegawai',
        'nomor_ijazah',
        'institusi',
        'pendidikan',
        'prodi',
        'tahun_lulus',
        'lampiran',
    ];
}
