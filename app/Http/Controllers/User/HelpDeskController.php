<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\HelpDesk;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use PHPUnit\TextUI\Help;
use Illuminate\Support\Facades\Log;
use App\Events\HelpdeskCreated;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class HelpDeskController extends Controller
{
    // public function index(Request $request)
    // {
    //     $helpDesks = HelpDesk::with('user')
    //         ->where('user_id', Auth()->user()->id)
    //         ->get();

    //         if ($request->ajax()) {
    //             return response()->json([
    //                 'data' => $helpDesks
    //             ]);
    //         }

    //     return view('master-data.help-desk.user.helpdesk', [
    //         'title' => 'Help Desk',
    //         'helpDesks' => $helpDesks,
    //         'menuTitle' => 'Master Data',
    //         'menuSubtitle' => 'Help Desk',
    //     ]);
    // }
    public function index()
    {
        $data = [
            'title' => 'Help Desk',
            'menuTitle' => 'Master Data',
            'menuSubtitle' => 'Help Desk',
        ];
        return view('help-desk.user.helpdesk', $data);
    }

    //View 
    public function views(Request $request)
    {
        $query = HelpDesk::where('user_id', auth()->id());

        // FILTER TANGGAL
        if ($request->tgl_awal && $request->tgl_akhir) {
            $query->whereBetween('created_at', [
                Carbon::parse($request->tgl_awal)->startOfDay(),
                Carbon::parse($request->tgl_akhir)->endOfDay()
            ]);
        }

        $data = $query->get()->map(function ($value) {
            return [
                'id' => $value->id,
                'tiket' => $value->tiket,
                'judul_laporan' => $value->judul_laporan,
                'kategori' => $value->kategori,
                'prioritas' => $value->prioritas,
                'keterangan' => $value->keterangan ?? '-',
                'tanggal' => $value->tanggal ?? '-',
                'status' => $value->status ?? '-',
                'created_at' => Carbon::parse($value->created_at)->format('d-M-Y H:i'),
            ];
        });

        return response()->json($data);
    }

    public function store(Request $request)
    {
        $request->validate([
            'lampiran.*' => 'image|mimes:jpg,jpeg,png|max:5120', // 5MB
        ]);

        $gambar = [];
        if ($request->hasFile('lampiran')) {
            $path = public_path('uploads/images/help-desk');
            if (!File::exists($path)) {
                File::makeDirectory($path, 0755, true);
            }

            foreach ($request->file('lampiran') as $file) {
                if ($file->isValid()) {
                    $filename = uniqid() . '.' . $file->getClientOriginalExtension();
                    $file->move($path, $filename);
                    $gambar[] = $filename;
                    Log::info("File uploaded: {$filename}");
                }
            }
        }
        Log::info('Total files: ' . count($gambar));
        $helpdesk = HelpDesk::create([
            'user_id' => Auth::id(),
            'tiket' => 'IHC-' . now()->format('YmdHis'),
            'judul_laporan' => $request->judul_laporan,
            'kategori' => $request->kategori,
            'prioritas' => $request->prioritas,
            'keterangan' => $request->keterangan,
            'status' => 'accept',
            'tanggal' => now(),
            'gambar' => !empty($gambar) ? json_encode($gambar) : null,
        ]);

        broadcast(new HelpdeskCreated($helpdesk))->toOthers();
        return response()->json([
            'success' => true,
            'message' => 'Berhasil membuat laporan Help Desk',
            'data' => $helpdesk
        ]);
    }


    // Hapus
    public function destroy(HelpDesk $helpDesk)
    {
        $helpDesk->delete();

        return response()->json([
            'success' => true,
            'message' => 'Berhasil Menghapus Data Laporan',

        ], 200);
    }
}
