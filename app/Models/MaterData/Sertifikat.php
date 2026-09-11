<?php

namespace App\Models\MaterData;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sertifikat extends Model
{
    use HasFactory;

    protected $table = 'tbl_sertifikat';

    protected $fillable = [
        'id',
        'id_pegawai',
        'nama',
        'jenis',
        'penyelenggara',
        'tahun',
        'lampiran',
    ];
}
