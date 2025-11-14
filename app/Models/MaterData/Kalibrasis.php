<?php
namespace App\Models\MaterData;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Kalibrasis extends Model
{
    use HasFactory;
     
    protected $table = 'tbl_kalibrasi';

    protected $fillable = [
        'id',
        'id_aset',
        'tgl_kalibrasi',
        'status',
        'aktif',
    ];
}
