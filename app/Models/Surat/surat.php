<?php

namespace App\Models\Surat;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class surat extends Model
{
    use HasFactory;
    protected $table = 'surat';
    protected $fillable = [
        'id',
        'tanggal',
        'no_surat',
        'approval_id',
        'lampiran',
        'jumlah_lampiran',
        'perihal',
        'isi_surat',
        'status',
        'id_unit',
        'id_pegawai'
    ];
}