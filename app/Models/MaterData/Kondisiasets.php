<?php

namespace App\Models\MaterData;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kondisiasets extends Model
{
    use HasFactory;

    protected $table = 'tbl_kondisis';

    protected $fillable = [
        'id',
        'kode',
        'nama',
        'status',
        'created_at',
        'updated_at'
    ];
}
