<?php

namespace App\Models\Surat;

use App\Models\MaterData\Jabatan;
use App\Models\Surat\DisposisiMaster;
use Illuminate\Database\Eloquent\Model;

class DisposisiMasterDetail extends Model
{
    protected $table = 'tbl_disposisi_master_detail';

    protected $fillable = [
        'id_disposisi_master',
        'urutan',
        'id_jabatan_tujuan',
        'id_unit',
    ];

    public function master()
    {
        return $this->belongsTo(DisposisiMaster::class, 'id_disposisi_master');
    }

    public function jabatanTujuan()
    {
        return $this->belongsTo(Jabatan::class, 'id_jabatan_tujuan');
    }
}
