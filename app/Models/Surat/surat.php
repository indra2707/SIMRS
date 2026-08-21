<?php

namespace App\Models\Surat;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class surat extends Model
{
    use HasFactory;
    protected $table = 'surat';
    protected $fillable = [
        'tanggal',
        'no_surat',
        'approval_id',
        'lampiran',
        'perihal',
        'isi_surat',
    ];


    protected $casts = [
        'lampiran' => 'array',
    ];


    public function approver()
    {
        return $this->belongsTo( User::class,'approval_id','id' );
    }
}
