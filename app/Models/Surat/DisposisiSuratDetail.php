<?php

namespace App\Models\Surat;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DisposisiSuratDetail extends Model
{
    use HasFactory;
        protected $table = 'tbl_disposisi_surat_detail';
 
    protected $fillable = [
        'id_disposisi_surat',
        'id_disposisi_jabatan',
        'nama_jabatan',
        'id_pegawai',
        'id_unit',
        'tindakan_action',
        'tindakan_tanggapan',
        'tindakan_info',
        'tindakan_file',
        'status',
        'tanggal_diteruskan',
        'tanggal_dibaca',
        'tanggal_paraf',
        'catatan_tindak_lanjut',
    ];
 
    protected $casts = [
        'tindakan_action' => 'boolean',
        'tindakan_tanggapan' => 'boolean',
        'tindakan_info' => 'boolean',
        'tindakan_file' => 'boolean',
        'tanggal_diteruskan' => 'datetime',
        'tanggal_dibaca' => 'datetime',
        'tanggal_paraf' => 'datetime',
    ];
 
    public function disposisiSurat()
    {
        return $this->belongsTo(DisposisiSurat::class, 'id_disposisi_surat');
    }

}
