<?php

namespace App\Models\Tarif;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tarif_tindakan extends Model
{
    use HasFactory;

    protected $table = 'tarif_tindakans';

    protected $fillable = [
        'kode_tarif',
        'tindakan',
        'kategori',
        'cito',
        'status',
        'coa_pendapatan_rj',
        'coa_pendapatan_ri',
        'coa_reduksi_rj',
        'coa_reduksi_ri',
        'coa_mcu_onsite',
        'coa_mcu_insite',
    ];
}
