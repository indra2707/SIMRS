<?php

namespace App\Http\Controllers\Sdm;

use App\Http\Controllers\Controller;
use App\Models\Sdm\Gaji;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

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

    // Views Table
    public function views()
    {
        $user = Auth::user();

        // pengaman kalau user belum punya nomor_pekerja
        if (!$user || !$user->nomor_pekerja) {
            return response()->json([]);
        }

        $data = Gaji::where('nomor_pekerja', $user->nomor_pekerja)
            ->orderBy('bulan', 'desc')
            ->get()
            ->map(function ($value) {
                return [
                    'id' => $value->id,
                    'nomor_pekerja' => $value->nomor_pekerja,
                    'bulan' => Carbon::parse($value->bulan)->format('M Y'),
                    'file' => $value->file,
                ];
            })
            ->values(); // reset index

        return response()->json($data);
    }
}
