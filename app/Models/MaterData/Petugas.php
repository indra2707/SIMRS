<?php

namespace App\Models\MaterData;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Petugas extends Model
{
    use HasFactory;

    protected $fillable = [
        'kode',
        'nama',
        'kategori',
        'status',
        'nik',
        'jenis_kelamin',
        'no_hp',
        'kode_spesialis',
        'kode_bpjs',
        'alamat',
        'kode_tindakan1',
        'kode_tindakan2',
        'tanggal',
        'foto',
        'ttd'
    ];
}
