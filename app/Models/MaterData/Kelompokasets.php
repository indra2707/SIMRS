<?php

namespace App\Models\MaterData;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Kelompokasets extends Model
{
    use HasFactory;

    protected $table = 'tbl_kelompok';

    protected $fillable = [
        'id',
        'kode',
        'nama',
        'bulan',
        'status',
        'created_at',
        'updated_at'
    ];
}
