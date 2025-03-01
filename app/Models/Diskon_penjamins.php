<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Diskon_penjamins extends Model
{
    use HasFactory;

    protected $table = 'diskon_penjamins';

    protected $fillable = [
        'penjamin',
        'kategori',
        'tindakan',
        'konsultasi',
        'sewa_alat',
        'ok',
        'cathlab',
        'radiologi',
        'laboratorium',
        'akomodasi',
        'paket',
        'obat'
    ];
}
