<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Poli_tindakans extends Model
{
    use HasFactory;

    protected $fillable = [
        'kode_poli',
        'kode_tindakan',
        'status'
    ];
}
