<?php

namespace App\Models\Surat;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class surat extends Model
{
    use HasFactory;
    protected $fillable = [
        'tanggal',
        'no_surat',
        'approval_id',
        'status',
        'lampiran',
        'perihal',
        'isi_surat',
    ];

    protected $casts = [
        'tanggal' => 'date',
    ];

    public function approver()
    {
        return $this->belongsTo(
            \App\Models\User::class,
            'approval_id',
            'id'
        );
    }
}
