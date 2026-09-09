<?php

namespace App\Models\Surat;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DisposisiJabatan extends Model
{
    use HasFactory;
    protected $table = 'tbl_disposisi_jabatan';

    protected $fillable = [
        'id_aproval',
        'urutan',
        'nama_jabatan',
        'id_pegawai',
        'id_unit',
    ];
}
