<?php

namespace App\Models\Logistik;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Permintaan extends Model
{
    use HasFactory;

    protected $table = 'tbl_permintaan';

    protected $fillable = [
        'id',
        'no_agenda',
        'no_surat',
        'nama_permintaan',
        'tanggal',
        'id_unit',
        'status',
        'catatan',
        'created_at',
        'updated_at',
        'created_by',
        'updated_by'
    ];
}
