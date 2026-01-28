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
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = [
            'title' => 'Logistik',
            'menuTitle' => 'Master Data',
            'menuSubtitle' => 'Permintaan Logistik',
        ];
        return view('logistik.permintaan.permintaan', $data);
    }

    /**
     * Get data for table view
     */
    public function views(Request $request)
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
                    'tgl'      => $value->tgl?->format('d-m-Y'),
                    'tgl_raw'  => $value->tgl?->format('Y-m-d'),
                    'catatan' => $value->catatan,
                    'id_unit' => $unitIds,
                    'tembusan' => $tembusanArray,
                ];
            })
        );
    }

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
            'no_agenda' => $this->generateNoAgenda(),
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
    private function generateNoAgenda()
    {
        $year = now()->format('Y');
        $lastAgenda = Permintaan::whereYear('tgl', $year)
            ->orderBy('id', 'desc')
            ->first();

        $number = $lastAgenda ? ((int) substr($lastAgenda->no_agenda, -4)) + 1 : 1;
        return 'AGD-' . $year . '-' . str_pad($number, 4, '0', STR_PAD_LEFT);
    }

    public function update(Request $request, $id)
    {
        DB::beginTransaction();

        try {
            $permintaan = Permintaan::findOrFail($id);

            $tanggal = $permintaan->tgl;

            if ($request->filled('tgl') && !empty($request->tgl)) {
                try {
                    $tanggal = Carbon::parse($request->tgl)->format('Y-m-d');
                } catch (\Exception $e) {
                    Log::warning('Failed to parse date: ' . $request->tgl . ' - Error: ' . $e->getMessage());
                }
            }

            // Update data
            $permintaan->update([
                'no_surat' => $request->no_surat,
                'nama_permintaan' => $request->nama_permintaan,
                'tgl' => $tanggal,
                'id_unit' => $request->id_unit,
                'status' => $request->status ?? $permintaan->status,
                'catatan' => $request->catatan,
                'tembusan' => $request->has('tembusan')
                    ? $request->tembusan
                    : $permintaan->tembusan,

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

    public function destroy($id)
    {
        DB::beginTransaction();

        try {
            $permintaan = Permintaan::findOrFail($id);
            $permintaan->delete();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Data berhasil dihapus'
            ], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Data tidak ditemukan'
            ], 404);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error deleting Permintaan: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Data gagal dihapus: ' . $e->getMessage()
            ], 500);
        }
    }

    public function updateStatus(Request $request, $id)
    {
        try {
            $permintaan = Permintaan::findOrFail($id);

            $permintaan->status = $request->status;
            $permintaan->updated_by = Auth::user()->username ?? 'system';
            $permintaan->save();

            return response()->json([
                'success' => true,
                'message' => 'Status berhasil diubah menjadi ' . $request->status,
                'data' => $permintaan
            ], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak ditemukan'
            ], 404);
        } catch (\Exception $e) {
            Log::error('Error updating status: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Status gagal diubah: ' . $e->getMessage()
            ], 500);
        }
    }

    public function show($id)
    {
        try {
            $permintaan = Permintaan::with('unit')->findOrFail($id);

            return response()->json([
                'success' => true,
                'data' => $permintaan
            ], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak ditemukan'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }
}
