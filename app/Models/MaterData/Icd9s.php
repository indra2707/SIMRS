<?php

namespace App\Models\MaterData;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Icd9s extends Model
{
    use HasFactory;

    protected $fillable = [
        'kode',
        'nama',
        'status'
    ];
}
