<?php

namespace App\Models\MaterData;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Poli_obats extends Model
{
    use HasFactory;

    protected $fillable = [
        'kode_poli',
        'kode_obat',
        'status'
    ];
}
