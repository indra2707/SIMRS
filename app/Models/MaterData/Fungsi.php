<?php

namespace App\Models\MaterData;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Fungsi extends Model
{
    use HasFactory;

    protected $table = 'tbl_fungsi';

    protected $fillable = [
        'id',
        'nama_fungsi',
        'status',
        'created_at',
        'updated_at'
    ];
}
