<?php

namespace App\Models\MaterData;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SpkRkk extends Model
{
    use HasFactory;

    protected $table = 'tbl_spk_rkk';

    protected $fillable = [
        'id',
        'id_pegawai',
        'nomor',
        'tanggal_mulai',
        'tanggal_berakhir',
        'lampiran',
    ];
}
