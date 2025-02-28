<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Penjamins extends Model
{
    use HasFactory;

    protected $fillable = [
        'kode',
        'nama',
        'coa',
        'email',
        'tarif',
        'status',
        'telpon',
        'alamat',
        'margin'
    ];
}
