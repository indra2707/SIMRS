<?php

namespace App\Models\sdm;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rincian_spds extends Model
{
    use HasFactory;

    protected $table = 'tbl_spd_details';

    protected $fillable = [
        'id',
        'id_pegawai',
        'id_mengajukan',
        'id_menyetujui',
        'jenis',
        'status',
        'panjar',
        'created_at',
        'updated_at'
    ];
}
