<?php

namespace App\Http\Controllers\Logistik;

use App\Http\Controllers\Controller;
use App\Models\Logistik\Permintaan;
use App\Models\MaterData\Unit;
use Carbon\Carbon;
use Illuminate\Support\Facades\Session;

class DisposisiController extends Controller
{

    public function index()
    {
        $data = [
            'title' => 'Logistik',
            'menuTitle' => 'Master Data',
            'menuSubtitle' => 'Disposisi Logistik',
        ];
        return view('logistik.disposisi.disposisi', $data);
    }

    // View
    public function views()
    {
        $unitLogin = Session::get('id_unit');

        $permintaans = Permintaan::whereJsonContains('id_unit', $unitLogin)->all();

        // 🔹 Kumpulkan semua ID unit (id_unit + tembusan)
        $allUnitIds = $permintaans->flatMap(function ($item) {
            $idUnit = is_string($item->id_unit)
                ? json_decode($item->id_unit, true)
                : $item->id_unit;

            $tembusan = is_string($item->tembusan)
                ? json_decode($item->tembusan, true)
                : $item->tembusan;

            return array_merge($idUnit ?? [], $tembusan ?? []);
        })
            ->map(fn($id) => (int) $id)
            ->unique()
            ->values()
            ->toArray();

        // 🔹 Ambil nama unit
        $units = Unit::whereIn('id', $allUnitIds)
            ->pluck('nama', 'id');

        // 🔹 Mapping data
        $data = $permintaans->map(function ($value) use ($units) {

            $unitIds = is_string($value->id_unit)
                ? json_decode($value->id_unit, true)
                : $value->id_unit;

            $tembusanIds = is_string($value->tembusan)
                ? json_decode($value->tembusan, true)
                : $value->tembusan;

            $unitText = collect($unitIds ?? [])
                ->map(fn($id) => $units[$id] ?? null)
                ->filter()
                ->implode(', ');

            $tembusanText = collect($tembusanIds ?? [])
                ->map(fn($id) => $units[$id] ?? null)
                ->filter()
                ->implode(', ');

            return [
                'id' => $value->id,
                'no_agenda' => $value->no_agenda,
                'no_surat' => $value->no_surat,
                'nama_permintaan' => $value->nama_permintaan,
                'status' => $value->status,
                'unit' => $unitText ?: '-',
                'tembusan_text' => $tembusanText ?: '-',
                'created_by' => $value->created_by,
                'updated_by' => $value->updated_by,
                'tgl' => $value->tgl ? Carbon::parse($value->tgl)->format('d/m/Y') : null,
                'catatan' => $value->catatan,
                'id_unit' => $unitIds ?? [],
                'tembusan' => $tembusanIds ?? [],
            ];
        });

        return response()->json($data, 200);
    }

}