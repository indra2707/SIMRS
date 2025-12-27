<?php

namespace App\Models\MaterData;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bank extends Model
{
    use HasFactory;

    protected $table = 'tbl_bank';

    protected $fillable = [
        'id',
        'nama_bank',
        'singkatan',
        'jenis_bank',
        'status',
        'created_at',
        'updated_at'
    ];
}
