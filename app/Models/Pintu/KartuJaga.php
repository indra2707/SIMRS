<?php

namespace App\Models\Pintu;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KartuJaga extends Model
{
    use HasFactory;

    protected $table = 'kartu_jaga';

    protected $fillable = [
        'id',
        'nama_pasien',
        'nama',
        'no_hp',
        'ruangan',
        'no_kartu',
        'deposit',
        'created_by',
        'updated_by',
        'status',
    ];
}
