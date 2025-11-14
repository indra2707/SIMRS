<?php
namespace App\Models\MaterData;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Mutasis extends Model
{
    use HasFactory;

    protected $table = 'tbl_mutasis';

    protected $fillable = [
        'id',
        'tgl_mutasi',
        'id_aset',
        'id_lokasi',
        'id_lokasi_new',
        'id_kondisi',
        'keterangan',
    ];
}
