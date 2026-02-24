<?php

namespace App\Http\Controllers\Dashboard;

use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class DashboardHelpdeskController extends Controller
{
    public function index()
    {
        $data = [
            'title' => 'Helpdesk',
            'menuTitle' => 'Dashboard',
            'menuSubtitle' => 'Helpdesk',
        ];
        return view('dashboard.helpdesk.dashboar-helpdesk', $data);
    }

    // Dashboard ICT
    public function dashboardHelpdesk(Request $request)
    {
        $query = DB::table('help_desk')
            ->select('status', DB::raw('count(*) as total'))
            ->where('kategori', 'IT');

        // Jika ada filter tanggal
        if ($request->tgl_awal && $request->tgl_akhir) {

            $tglAwal = Carbon::createFromFormat('d/m/Y', $request->tgl_awal)->format('Y-m-d') ?? null;
            $tglAkhir = Carbon::createFromFormat('d/m/Y', $request->tgl_akhir)->format('Y-m-d') ?? null;

            $query->whereBetween('tanggal', [$tglAwal, $tglAkhir]);
        }

        $data = $query->groupBy('status')->get();

        $result = [
            'accept' => 0,
            'on-progress' => 0,
            'done' => 0,
        ];

        foreach ($data as $row) {
            if (array_key_exists($row->status, $result)) {
                $result[$row->status] = $row->total;
            }
        }

        return response()->json($result);
    }


    // Dashboard Teknik
    public function dashboardHelpdeskTeknik(Request $request)
    {
        $query = DB::table('help_desk')
            ->select('status', DB::raw('count(*) as total'))
            ->where('kategori', 'Teknik');

        // Jika ada filter tanggal
        if ($request->tgl_awal && $request->tgl_akhir) {

            $tglAwal = Carbon::createFromFormat('d/m/Y', $request->tgl_awal)->format('Y-m-d') ?? null;
            $tglAkhir = Carbon::createFromFormat('d/m/Y', $request->tgl_akhir)->format('Y-m-d') ?? null;

            $query->whereBetween('tanggal', [$tglAwal, $tglAkhir]);
        }

        $data = $query->groupBy('status')->get();

        $result = [
            'accept' => 0,
            'on-progress' => 0,
            'done' => 0,
        ];

        foreach ($data as $row) {
            if (array_key_exists($row->status, $result)) {
                $result[$row->status] = $row->total;
            }
        }

        return response()->json($result);
    }


    // Dashboard ElektroMedis
    public function dashboardHelpdeskElektroMedis(Request $request)
    {
        $query = DB::table('help_desk')
            ->select('status', DB::raw('count(*) as total'))
            ->where('kategori', 'Medis');

        // Jika ada filter tanggal
        if ($request->tgl_awal && $request->tgl_akhir) {

            $tglAwal = Carbon::createFromFormat('d/m/Y', $request->tgl_awal)->format('Y-m-d') ?? null;
            $tglAkhir = Carbon::createFromFormat('d/m/Y', $request->tgl_akhir)->format('Y-m-d') ?? null;

            $query->whereBetween('tanggal', [$tglAwal, $tglAkhir]);
        }

        $data = $query->groupBy('status')->get();

        $result = [
            'accept' => 0,
            'on-progress' => 0,
            'done' => 0,
        ];

        foreach ($data as $row) {
            if (array_key_exists($row->status, $result)) {
                $result[$row->status] = $row->total;
            }
        }

        return response()->json($result);
    }

    // Dashboard General Affair
    public function dashboardHelpdeskGeneralAffair(Request $request)
    {
        $query = DB::table('help_desk')
            ->select('status', DB::raw('count(*) as total'))
            ->where('kategori', 'General Affair');

        // Jika ada filter tanggal
        if ($request->tgl_awal && $request->tgl_akhir) {

            $tglAwal = Carbon::createFromFormat('d/m/Y', $request->tgl_awal)->format('Y-m-d') ?? null;
            $tglAkhir = Carbon::createFromFormat('d/m/Y', $request->tgl_akhir)->format('Y-m-d') ?? null;

            $query->whereBetween('tanggal', [$tglAwal, $tglAkhir]);
        }

        $data = $query->groupBy('status')->get();

        $result = [
            'accept' => 0,
            'on-progress' => 0,
            'done' => 0,
        ];

        foreach ($data as $row) {
            if (array_key_exists($row->status, $result)) {
                $result[$row->status] = $row->total;
            }
        }

        return response()->json($result);
    }

    // Dashboard All
    public function dashboardHelpdeskAll(Request $request)
    {
        $query = DB::table('help_desk')
            ->select('updated_by', DB::raw('COUNT(*) as total'))
            ->whereNotNull('updated_by');

        // Filter tanggal jika ada
        if ($request->tgl_awal && $request->tgl_akhir) {

            $tglAwal = Carbon::createFromFormat('d/m/Y', $request->tgl_awal)->format('Y-m-d');
            $tglAkhir = Carbon::createFromFormat('d/m/Y', $request->tgl_akhir)->format('Y-m-d');

            $query->whereBetween('tanggal', [$tglAwal, $tglAkhir]);
        }

        $data = $query->groupBy('updated_by')->get();

        $labels = [];
        $totals = [];

        foreach ($data as $row) {
            $labels[] = $row->updated_by;
            $totals[] = $row->total;
        }

        return response()->json([
            'labels' => $labels,
            'data' => $totals
        ]);
    }

}
