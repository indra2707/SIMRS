<?php

namespace App\Models\MaterData;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SkJabatan extends Model
{
    use HasFactory;

    protected $table = 'tbl_sk_jabatan';

    protected $fillable = [
        'id',
        'id_pegawai',
        'nomor_sk',
        'nama_jabatan',
        'tanggal_mulai',
        'tanggal_berakhir',
        'lampiran',
    ];
}
