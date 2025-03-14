<?php

namespace App\Models\MaterData;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Petugas extends Model
{
    use HasFactory;

    protected $fillable = [
        'kode_petugas',
        'nama',
        'nik',
        'jenis_kelamin',
        'status_petugas',
        'no_hp',
        'alamat',
        'kode_bpjs',
        'kategori',
        'no_sip',
        'masa_berlaku_sip',
        'kode_spesialis',
        'tindakan_konsul',
        'tindakan_visite',
        'foto',
        'signatures',
        'status'
    ];
}
