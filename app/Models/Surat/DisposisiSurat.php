<?php

namespace App\Models\Surat;

use Illuminate\Database\Eloquent\Model;

class DisposisiSurat extends Model
{
       protected $table = 'tbl_disposisi_surat';
 
    protected $fillable = [
        'id_surat',
        'id_aproval',
        'no_agenda',
        'tingkat_surat',
        'catatan',
        'id_pengirim',
        'id_unit',
    ];
 
    public function surat()
    {
        return $this->belongsTo(Surat::class, 'id_surat');
    }
 
    public function detail()
    {
        return $this->hasMany(DisposisiSuratDetail::class, 'id_disposisi_surat');
    }

}
