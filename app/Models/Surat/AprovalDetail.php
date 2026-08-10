<?php

namespace App\Models\Surat;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AprovalDetail extends Model
{
    use HasFactory;

    protected $table = 'tbl_aproval_detail';

    protected $fillable = [
        'id',
        'id_aproval',
        'parent_jabatan',
        'id_pegawai',
    ];
}
