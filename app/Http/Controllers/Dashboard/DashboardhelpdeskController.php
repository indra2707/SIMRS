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
    public function dashboardHelpdesk()
    {
        $data = DB::table('help_desk')
            ->select('status', DB::raw('count(*) as total'))
            ->where('kategori', 'IT')
            ->groupBy('status')
            ->get();

        $result = [
            'accept' => 0,
            'on-progress' => 0,
            'done' => 0,
        ];

        foreach ($data as $row) {
            $result[$row->status] = $row->total;
        }

        return response()->json($result);
    }


     // Dashboard Teknik
    public function dashboardHelpdeskTeknik()
    {
        $data = DB::table('help_desk')
            ->select('status', DB::raw('count(*) as total'))
            ->where('kategori', 'Teknik')
            ->groupBy('status')
            ->get();

        $result = [
            'accept' => 0,
            'on-progress' => 0,
            'done' => 0,
        ];

        foreach ($data as $row) {
            $result[$row->status] = $row->total;
        }

        return response()->json($result);
    }


    // Dashboard ElektroMedis
    public function dashboardHelpdeskElektroMedis()
    {
        $data = DB::table('help_desk')
            ->select('status', DB::raw('count(*) as total'))
            ->where('kategori', 'Medis')
            ->groupBy('status')
            ->get();

        $result = [
            'accept' => 0,
            'on-progress' => 0,
            'done' => 0,
        ];

        foreach ($data as $row) {
            $result[$row->status] = $row->total;
        }

        return response()->json($result);
    }

    // Dashboard General Affair
    public function dashboardHelpdeskGeneralAffair()
    {
        $data = DB::table('help_desk')
            ->select('status', DB::raw('count(*) as total'))
            ->where('kategori', 'General Affair')
            ->groupBy('status')
            ->get();

        $result = [
            'accept' => 0,
            'on-progress' => 0,
            'done' => 0,
        ];

        foreach ($data as $row) {
            $result[$row->status] = $row->total;
        }

        return response()->json($result);
    }

}
