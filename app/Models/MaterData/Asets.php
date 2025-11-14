<?php

namespace App\Models\MaterData;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Asets extends Model
{
    use HasFactory;

    protected $table = 'tbl_asets';

    protected $fillable = [
        'id',
        'no_aset',
        'jenis',
        'nama',
        'merek',
        'tipe',
        'tahun',
        'harga',
        'no_sn',
        'status',
        'kategori',
        'id_lokasi',
        'id_kondisi',
        'id_kelompok',
        'id_vendor',        
        'created_at',
        'updated_at'
    ];
}
