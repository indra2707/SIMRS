<?php

namespace App\Models\MaterData;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StrSip extends Model
{
    use HasFactory;

    protected $table = 'tbl_str_sip';

    protected $fillable = [
        'id',
        'id_pegawai',
        'nomor',
        'jenis',
        'masa_berlaku',
        'tanggal_mulai',
        'tanggal_berakhir',
        'lampiran',
    ];
}
