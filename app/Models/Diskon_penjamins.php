<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Diskon_penjamins extends Model
{
    use HasFactory;

    protected $fillable = [
        'penjamin',
        'kategori',
        'tindakan',
        'konsultasi',
        'ok',
        'cathlab',
        'radiologi',
        'laboratorium',
        'akomodasi',
        'paket'
    ];
}
