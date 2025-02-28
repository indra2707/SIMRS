<?php

namespace App\Models\Tarif;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SKTarif extends Model
{
    use HasFactory;
    protected $table = 'sk_tarifs';
    protected $fillable = [
        'no_sk',
        'tgl_efektif_mulai',
        'tgl_efektif_akhir',
        'deskripsi',
        'status'
    ];
}
