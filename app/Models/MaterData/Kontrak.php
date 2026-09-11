<?php

namespace App\Models\MaterData;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kontrak extends Model
{
    use HasFactory;

    protected $table = 'tbl_kontrak';

    protected $fillable = [
        'id',
        'id_pegawai',
        'nomor_kontrak',
        'status',
        'tanggal_mulai',
        'masa_berlaku',
        'tanggal_berakhir',
        'lampiran',
    ];
}
