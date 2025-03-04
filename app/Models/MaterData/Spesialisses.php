<?php

namespace App\Models\MaterData;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Spesialisses extends Model
{
    use HasFactory;

    protected $fillable = [
        'kode',
        'nama',
        'status'
    ];
}
