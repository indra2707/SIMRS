<?php
namespace App\Models\MaterData;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Diskon_penjamins extends Model
{
    use HasFactory;

    protected $table = 'diskon_penjamins';

    // disable update_at
    public $timestamps = false;

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
