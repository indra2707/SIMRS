<?php

namespace App\Models\MaterData;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SKStruktur extends Model
{
    use HasFactory;

    protected $table = 'tbl_sk_struktur';

    protected $fillable = [
        'id',
        'no_sk',
        'tanggal_mulai',
        'tanggal_selesai',
        'keterangan',
        'status',
        'created_at',
        'updated_at'
    ];
}
