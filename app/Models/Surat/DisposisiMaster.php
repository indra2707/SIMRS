<?php

namespace App\Models\Surat;

use App\Models\MaterData\Jabatan;
use App\Models\Surat\DisposisiMasterDetail;
use Illuminate\Database\Eloquent\Model;

class DisposisiMaster extends Model
{
    protected $table = 'tbl_disposisi_master';

    protected $fillable = [
        'nama_master',
        'id_jabatan_pemilik',
        'status',
    ];

    // Baris-baris tujuan pada lembar ini, urut sesuai kertas
    public function detail()
    {
        return $this->hasMany(DisposisiMasterDetail::class, 'id_disposisi_master')
            ->orderBy('urutan');
    }

    // Jabatan pemilik lembar ini (mis. "VICE DIRECTOR HCGA")
    public function jabatanPemilik()
    {
        return $this->belongsTo(Jabatan::class, 'id_jabatan_pemilik');
    }
}
