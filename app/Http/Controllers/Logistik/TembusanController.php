<?php

namespace App\Http\Controllers\Logistik;

use App\Http\Controllers\Controller;
use App\Models\Logistik\Permintaan;
use App\Models\MaterData\Unit;
use Carbon\Carbon;
use Illuminate\Support\Facades\Session;
use function Psy\debug;

class TembusanController extends Controller
{

    public function index()
    {
        $data = [
            'title' => 'Logistik',
            'menuTitle' => 'Master Data',
            'menuSubtitle' => 'Tembusan Logistik',
        ];
        return view('logistik.tembusan.tembusan', $data);
    }

    // View
    public function views()
    {
        $idUnit = (string) Session::get('id_unit'); // samakan tipe

        $permintaans = Permintaan::whereJsonContains('id_unit', $idUnit)->get();

        // Ambil semua ID unit dari JSON
        $allUnitIds = $permintaans
            ->flatMap(function ($item) {
                return is_string($item->id_unit)
                    ? json_decode($item->id_unit, true) ?? []
                    : ($item->id_unit ?? []);
            })
            ->map(fn($id) => (int) $id)
            ->unique()
            ->toArray();

        $units = Unit::whereIn('id', $allUnitIds)
            ->pluck('nama', 'id')
            ->toArray();

        $data = $permintaans->map(function ($value) use ($units) {

            $unitIds = is_string($value->id_unit)
                ? json_decode($value->id_unit, true) ?? []
                : ($value->id_unit ?? []);

            $unitNames = collect($unitIds)
                ->map(fn($id) => $units[(int) $id] ?? null)
                ->filter()
                ->implode(', ');

            return [
                'id' => $value->id,
                'no_agenda' => $value->no_agenda,
                'no_surat' => $value->no_surat,
                'nama_permintaan' => $value->nama_permintaan,
                'status' => $value->status,
                'unit' => $unitNames ?: '-',
                'created_by' => $value->created_by,
                'tgl' => $value->tgl ? Carbon::parse($value->tgl)->format('d/m/Y') : null,
                'id_unit' => session::get('id_unit'),
            ];
        });

        return response()->json($data, 200);
    }

}