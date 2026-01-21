<?php

namespace App\Http\Controllers\Sdm;

use App\Http\Controllers\Controller;
use App\Models\Sdm\Gaji;
use Carbon\Carbon;
use Illuminate\Support\Facades\Session;

class GajiUserController extends Controller
{
    // Index
    public function index()
    {
        $data = [
            'title' => 'Slip Gaji',
            'menuTitle' => 'Master Data',
            'menuSubtitle' => 'Slip Gaji',
        ];
        return view('sdm.gaji_user.gaji_user', $data);
    }

    public function views()
    {
        $nomorPekerja = Session::get('nomor_pekerja');

        $query = Gaji::where('nomor_pekerja', $nomorPekerja)
            ->orderBy('bulan', 'desc')
            ->get();

        $data = [];
        foreach ($query as $value) {
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
