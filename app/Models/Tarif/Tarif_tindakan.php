<?php

namespace App\Models\Tarif;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tarif_tindakan extends Model
{
    use HasFactory;

    protected $table = 'tarif_tindakans';

    protected $fillable = [
        'id',
        'kode_tarif',
        'no_sk',
        'tindakan',
        'kelompok_tindakan',
        'tarif_rs',
        'group_tindakan',
        'tipe',
        'kategori_layanan',
        'status_cito',
        'cito',
        'status',
        'flat',
        'status_operasi',
    ];
}
