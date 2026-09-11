<?php

namespace App\Models\MaterData;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HasilMcu extends Model
{
    use HasFactory;

    protected $table = 'tbl_mcu';

    protected $fillable = [
        'id',
        'id_pegawai',
        'hasil',
        'catatan',
        'tanggal',
        'lampiran',
    ];
}
