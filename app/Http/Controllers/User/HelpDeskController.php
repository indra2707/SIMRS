<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\HelpDesk;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use PHPUnit\TextUI\Help;
use App\Events\HelpdeskCreated;

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
            'title' => 'HELP DESK',
            'menuTitle' => 'Master Data',
            'menuSubtitle' => 'HELP DESK',
        ];
        return view('help-desk.user.helpdesk', $data);
    }

    public function views()
    {
        $query = HelpDesk::where('user_id', Auth()->user()->id)->get();

        $data = [];
        foreach ($query as $key => $value) {
            $data[] = [
                'id' => $value->id,
                // 'kode' => $value->kode,
                // 'kategori' => $value->kategori,
                'keterangan' => $value->keterangan ?? '-',
                'tanggal' => $value->tanggal ?? '-',
                'status' => $value->status ?? '-',
                'created_at' => $value->created_at,
            ];
        }
        return response()->json($data, 200);
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'keterangan' => 'required|string|max:255',
            ]);

            $validated['user_id'] = Auth::id();
            $validated['tanggal'] = now();

            $helpdesk = HelpDesk::create($validated);
            broadcast(new HelpdeskCreated($helpdesk))->toOthers();
            return response()->json([
                'success' => true,
                'message' => 'Berhasil membuat laporan Help Desk.',
                'data' => $helpdesk,
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal: ' . implode(', ', $e->errors()['keterangan'] ?? []),
            ], 400);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan pada server.',
            ], 500);
        }
    }


    public function destroy(HelpDesk $helpDesk)
    {
        $helpDesk->delete();

        return response()->json([
            'success' => true,
            'message' => 'Berhasil Menghapus Data Laporan',

        ], 200);
    }
}
