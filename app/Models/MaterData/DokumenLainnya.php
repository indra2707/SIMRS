<?php

namespace App\Models\MaterData;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DokumenLainnya extends Model
{
    use HasFactory;

    protected $table = 'tbl_dokumen_lainnya';

    protected $fillable = [
        'id',
        'id_pegawai',
        'jenis',
        'nomor',
        'catatan',
        'lampiran',
    ];
}
