<?php

namespace App\Models\Sdm;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Gaji extends Model
{
    use HasFactory;

    protected $table = 'tbl_gaji';

    protected $fillable = [
        'id',
        'nomor_pekerja',
        'bulan',
        'file',
        'created_at',
        'created_by',
        'updated_by'
    ];
}
