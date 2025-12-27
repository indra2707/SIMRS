<?php

namespace App\Models\MaterData;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Jabatan extends Model
{
    use HasFactory;

    protected $table = 'tbl_jabatan';

    protected $fillable = [
        'id',
        'id_sk_struktur',
        'unit',
        'nama_jabatan',
        'status',
        'created_at',
        'updated_at'
    ];
}
