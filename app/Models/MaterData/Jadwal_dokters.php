<?php
namespace App\Models\MaterData;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Jadwal_dokters extends Model
{
    use HasFactory;

    protected $fillable = [
        'kode_poli',
        'hari',
        'kode_dokter',
        'mulai',
        'akhir',
        'estimasi',
        'kouta',
        'status'
    ];
}
