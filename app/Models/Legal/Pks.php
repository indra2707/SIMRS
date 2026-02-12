<?php

namespace App\Models\Legal;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pks extends Model
{
    use HasFactory;

    protected $table = 'tbl_pks';

    protected $fillable = [
        'id',
        'judul',
        'nomor_kontrak',
        'id_jenis_kontrak',
        'pihak',
        'tanggal_mulai',
        'tanggal_selesai',
        'file',
        'created_by',
        'updated_by'
    ];
}
