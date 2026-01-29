<?php

namespace App\Http\Controllers\Logistik;

use App\Http\Controllers\Controller;
use App\Models\Logistik\Permintaan;
use App\Models\MaterData\Unit;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class PermintaanController extends Controller
{

    public function index()
    {
        $data = [
            'title' => 'Logistik',
            'menuTitle' => 'Master Data',
            'menuSubtitle' => 'Permintaan Logistik',
        ];
        return view('logistik.permintaan.permintaan', $data);
    }

    // View
    public function views()
    {
        $permintaans = Permintaan::all();

        $allUnitIds = $permintaans->pluck('id_unit')->flatten()->filter()->unique()->toArray();
        $units = Unit::whereIn('id', $allUnitIds)->pluck('nama', 'id')->toArray();

        return response()->json(
            $permintaans->map(function ($value) use ($units) {
                $unitIds = is_string($value->id_unit)
                    ? json_decode($value->id_unit, true) ?? []
                    : ($value->id_unit ?? []);
                $unitIds = array_map('intval', array_filter($unitIds));

                $tembusanArray = is_string($value->tembusan)
                    ? json_decode($value->tembusan, true) ?? []
                    : ($value->tembusan ?? []);

                $unitNames = collect($unitIds)
                    ->map(fn($id) => $units[$id] ?? null)
                    ->filter()
                    ->toArray();

                return [
                    'id' => $value->id,
                    'no_agenda' => $value->no_agenda,
                    'no_surat' => $value->no_surat,
                    'nama_permintaan' => $value->nama_permintaan,
                    'status' => $value->status,
                    'unit' => !empty($unitNames) ? implode(', ', $unitNames) : '-',
                    'tembusan_text' => !empty($tembusanArray) ? implode(', ', $tembusanArray) : '-',
                    'created_by' => $value->created_by,
                    'updated_by' => $value->updated_by,
                    'tgl' => Carbon::parse($value->tgl)->format('d/m/Y'),
                    // 'tgl_raw' => Carbon::parse($value->tgl)->format('d/m/Y'),
                    'catatan' => $value->catatan,
                    'id_unit' => $unitIds,
                    'tembusan' => $tembusanArray,
                ];
            })
        );
    }

    // simpan 
    public function store(Request $request)
    {
        $request->validate([
            'nama_permintaan' => 'required|string|max:255',
            'id_unit' => 'required|array',
            'id_unit.*' => 'exists:tbl_unit,id',
            'no_surat' => 'nullable|string',
            'catatan' => 'nullable|string',
            'tembusan' => 'nullable|array',
            'tembusan.*' => 'string|max:50',
        ]);

        $tembusan = $request->input('tembusan', []);
        if (!is_array($tembusan)) {
            $tembusan = [];
        }
        $permintaan = Permintaan::create([
            'no_agenda' => $request->no_agenda,
            'no_surat' => $request->no_surat,
            'nama_permintaan' => $request->nama_permintaan,
            'id_unit' => $request->id_unit,
            'catatan' => $request->catatan,
            'tembusan' => $tembusan,
            'status' => $request->status,
            'tgl' => now(),
            'created_by' => Auth::user()->username ?? 'system',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Berhasil membuat permintaan',
            'data' => $permintaan
        ]);
    }

    // Select Unit 
    public function getSelectUnit(Request $request)
    {
        $ids = $request->input('ids', []);
        $search = $request->input('q', '');
        $page = $request->input('page', 1);
        $perPage = 10;

        if (!empty($ids)) {
            if (!is_array($ids)) {
                $ids = [$ids];
            }

            $units = Unit::whereIn('id', $ids)
                ->orderBy('nama', 'asc')
                ->get();

            return response()->json([
                'results' => $units->map(function ($unit) {
                    return [
                        'id' => (int) $unit->id,
                        'text' => $unit->nama
                    ];
                })->values(),
                'total_count' => $units->count()
            ]);
        }

        $query = Unit::query();

        if ($search) {
            $query->where('nama', 'LIKE', "%{$search}%");
        }

        $total = $query->count();
        $units = $query->orderBy('nama', 'asc')
            ->offset(($page - 1) * $perPage)
            ->limit($perPage)
            ->get();

        return response()->json([
            'results' => $units->map(function ($unit) {
                return [
                    'id' => (int) $unit->id,
                    'text' => $unit->nama
                ];
            })->values(),
            'total_count' => $total
        ]);
    }

    // Update
    public function update(Request $request, $id)
    {
        DB::beginTransaction();

        try {
            $permintaan = Permintaan::findOrFail($id);
            $permintaan->update([
                'no_surat' => $request->no_surat,
                'nama_permintaan' => $request->nama_permintaan,
                'tgl' => Carbon::createFromFormat('d/m/Y', $request->tgl)->format('Y-m-d'),
                'id_unit' => $request->id_unit,
                'status' => $request->status ?? $permintaan->status,
                'catatan' => $request->catatan,
                'tembusan' => $request->has('tembusan') ? $request->tembusan : $permintaan->tembusan,
                'updated_by' => Auth::user()->username ?? 'system'
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'data' => $permintaan,
                'message' => 'Data berhasil diubah'
            ], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Data tidak ditemukan'
            ], 404);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error updating Permintaan: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Data gagal diubah: ' . $e->getMessage()
            ], 500);
        }
    }

    // Delete
    public function destroy($id)
    {
        $query = Permintaan::where('id', $id)->delete();
        if ($query) {
            return response()->json([
                'success' => true,
                'data' => [],
                'message' => 'Data Berhasil Dihapus.',
            ], status: 200);
        } else {
            return response()->json([
                'success' => false,
                'data' => [],
                'message' => 'Data Gagal Dihapus.',
            ], status: 400);
        }
    }
}
