<?php
namespace App\Models\Logistik;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Perpustakaan extends Model
{
    use HasFactory;

    protected $table = 'tbl_perpustakaan';

    protected $fillable = [
        'id',
        'judul',
        'kategori',
        'status',
        'lampiran',
        'created_by',
        'updated_by'

    ];
}
