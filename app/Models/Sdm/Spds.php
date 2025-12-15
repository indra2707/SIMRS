<?php

namespace App\Models\Sdm;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Spds extends Model
{
    use HasFactory;

    protected $table = 'tbl_spds';

    protected $fillable = [
        'id',
        'no_surat',
        'id_pegawai',
        'pelaksanaan',
        'id_kota1',
        'id_kota2',
        'tgl_awal',
        'tgl_akhir',
        'tgl_masuk',
        'kendaraan',
        'ditanggung',
        'hak_cuti',
        'cuti_lalu',
        'jatuh_tempo',
        'panjar_cuti',
        'keterangan',
        'id_pimpinan',
        'pengikut',
        'status',
        'created_at',
        'updated_at',
        'created_by',
        'updated_by'
    ];
}
