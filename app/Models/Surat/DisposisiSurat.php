<?php

namespace App\Models\Surat;

use App\Models\MaterData\Jabatan;
use App\Models\Sdm\Pegawai;
// use App\Models\Surat\DisposisiSurat;
use Illuminate\Database\Eloquent\Model;

class DisposisiSurat extends Model
{
    protected $table = 'tbl_disposisi_surat';

    protected $fillable = [
        'id_surat',
        'id_parent',
        'id_pegawai_pengirim',
        'id_unit_pengirim',
        'id_pegawai_tujuan',
        'id_jabatan_tujuan',
        'id_unit_tujuan',
        'is_action',
        'is_tanggapan',
        'is_info',
        'is_file',
        'tingkat_surat',
        'catatan',
        'tanggal_diteruskan',
        'paraf',
        'status',
        'tanggal_dibaca',
        'tanggal_selesai',
    ];

    protected $casts = [
        'is_action' => 'boolean',
        'is_tanggapan' => 'boolean',
        'is_info' => 'boolean',
        'is_file' => 'boolean',
        'tanggal_diteruskan' => 'date',
        'tanggal_dibaca' => 'datetime',
        'tanggal_selesai' => 'datetime',
    ];

    // Relasi ke surat yang didisposisikan
    // Sesuaikan namespace App\Models\Surat\Surat jika model Surat Anda berbeda.
    public function surat()
    {
        return $this->belongsTo(Surat::class, 'id_surat');
    }

    // Disposisi induk (yang meneruskan ke disposisi ini)
    public function parent()
    {
        return $this->belongsTo(DisposisiSurat::class, 'id_parent');
    }

    // Disposisi turunan (hasil diteruskan dari disposisi ini) -> dipakai untuk tree/mermaid nanti
    public function children()
    {
        return $this->hasMany(DisposisiSurat::class, 'id_parent');
    }

    // Rekursif ambil semua turunan (dipakai saat build tree tracking)
    public function childrenRecursive()
    {
        return $this->children()->with('childrenRecursive');
    }

    // Pengirim disposisi
    // Sesuaikan namespace App\Models\Pegawai jika model Pegawai Anda berbeda.
    public function pengirim()
    {
        return $this->belongsTo(Pegawai::class, 'id_pegawai_pengirim');
    }

    // Penerima / tujuan disposisi (pegawai yang sedang memegang jabatan tsb saat dikirim)
    public function tujuan()
    {
        return $this->belongsTo(Pegawai::class, 'id_pegawai_tujuan');
    }

    // Jabatan yang dituju (sesuai kertas -- ini yang ditampilkan, bukan nama orang)
    public function jabatanTujuan()
    {
        return $this->belongsTo(Jabatan::class, 'id_jabatan_tujuan');
    }

    // Scope: disposisi yang jadi "akar" (dikeluarkan langsung dari surat, bukan hasil forward)
    public function scopeAkar($query)
    {
        return $query->whereNull('id_parent');
    }

    // Scope: disposisi milik pegawai tertentu (sebagai tujuan)
    public function scopeUntukPegawai($query, $idPegawai)
    {
        return $query->where('id_pegawai_tujuan', $idPegawai);
    }
}
