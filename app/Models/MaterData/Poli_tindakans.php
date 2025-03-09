<?php

namespace App\Models\MaterData;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Poli_tindakans extends Model
{
    use HasFactory;

    protected $table = 'poli_tindakans';

    protected $fillable = [
        'kode_poli',
        'kode_tindakan',
        'status'
    ];
}
