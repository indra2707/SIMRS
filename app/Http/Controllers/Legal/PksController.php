<?php

namespace App\Http\Controllers\Legal;

use App\Http\Controllers\Controller;
use App\Models\Legal\Pks;
use Illuminate\Http\Request;
use Carbon\Carbon;

class PksController extends Controller
{
    // Index
    public function index()
    {
        $data = [
            'title' => 'PKS',
            'menuTitle' => 'Master Data',
            'menuSubtitle' => 'PKS',
        ];
        return view('legal.pks.pks', $data);
    }

    // Views Table
    public function views(Request $request)
    {
        $query = Pks::query();

        // FILTER BERDASARKAN BULAN / RANGE TANGGAL
        if ($request->tgl_awal && $request->tgl_akhir) {
            $query->whereBetween('tanggal_mulai', [
                $request->tgl_awal,
                $request->tgl_akhir
            ]);
        }

        $data = [];
        foreach ($query->get() as $value) {
            $data[] = [
                'id' => $value->id,
                'nomor_pekerja' => $value->nomor_pekerja,
                'bulan' => Carbon::parse($value->bulan)->format('M Y'),
                'file' => $value->file
            ];
        }

        return response()->json($data, 200);
    }

}
