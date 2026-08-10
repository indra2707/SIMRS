<?php

namespace App\Models\Surat;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Aproval extends Model
{
    use HasFactory;

    protected $table = 'tbl_aproval';

    protected $fillable = [
        'nama_aproval',
        'status'
    ];
}
