<?php

namespace App\Models\MaterData;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class jenis_kontrak extends Model
{
    use HasFactory;

    protected $table = 'tbl_jenis_kontrak';

    protected $fillable = [
        'id',
        'nama',
        'status',
        'created_at',
        'updated_at'
    ];
}
