<?php

namespace App\Models\Surat;

use Illuminate\Database\Eloquent\Model;

class DisposisiSurat extends Model
{
    protected $table = 'disposisi';

    protected $fillable = [
        'id_surat',
        'id_unit',
        'id_pengirim',
        'id_penerima',
        'catatan',
        'status',
        'tanggal_dibaca',
        'tanggal_selesai',
        'catatan_tindak_lanjut',
    ];

    protected $casts = [
        'tanggal_dibaca' => 'datetime',
        'tanggal_selesai' => 'datetime',
    ];

    public function surat()
    {
        return $this->belongsTo(Surat::class, 'id_surat');
    }
}
