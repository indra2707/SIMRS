<?php

namespace App\Http\Controllers\Dashboard;

use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class DashboardController extends Controller
{

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
}
