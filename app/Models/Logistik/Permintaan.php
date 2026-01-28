<?php

namespace App\Models\Logistik;

use App\Models\MaterData\Unit;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Permintaan extends Model
{
    use HasFactory;

    protected $table = 'tbl_permintaan';

    protected $fillable = [
        'no_agenda',
        'no_surat',
        'nama_permintaan',
        'tgl',
        'id_unit',
        'status',
        'catatan',
        'tembusan',
        'created_by',
        'updated_by'
    ];

    protected $casts = [
        'tgl' => 'date',
        'id_unit' => 'array',
        'tembusan' => 'array',
    ];

    public function units()
    {
        if (is_array($this->id_unit) && !empty($this->id_unit)) {
            return Unit::whereIn('id', $this->id_unit)->get();
        }
        return collect();
    }
    public function getUnitNamesAttribute()
    {
        $units = $this->units();
        return $units->pluck('nama')->implode(', ');
    }
    public function getTembusansAttribute()
    {
        if (is_array($this->tembusan) && !empty($this->tembusan)) {
            return Unit::whereIn('id', $this->tembusan)->get();
        }
        return collect();
    }

    public function getTembusanNamesAttribute()
    {
        return $this->tembusans->pluck('nama')->implode(', ');
    }
}
