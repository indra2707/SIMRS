<?php

namespace App\Http\Controllers\Admin;

use App\Models\HelpDesk;
use App\Models\User\Users;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Events\HelpdeskStatusUpdated;
use Illuminate\Support\Facades\File;

class HelpDeskController extends Controller
{


    public function index()
    {
        $data = [
            'title' => 'Users',
            'menuTitle' => 'Master Data',
            'menuSubtitle' => 'User',

        ];
        return view('help-desk.Admin.helpDesk', $data);
    }

    public function views()
    {



        $query = HelpDesk::with(['user.rolls'])->get();

        $data = [];
        foreach ($query as $key => $value) {
            $data[] = [
                'id' => $value->id,
                // 'nama_lengkap' => $value->kode,
                // 'username' => $value->kategori,
                'nama_lengkap' => $value->user->nama_lengkap ?? '-',
                'username' => $value->user->username ?? '-',
                'department' => $value->user->rolls->nama ?? '-',
                'keterangan' => $value->keterangan ?? '-',
                'tiket' => $value->tiket ?? '-',
                'judul_laporan' => $value->judul_laporan ?? '-',
                'kategori' => $value->kategori ?? '-',
                'prioritas' => $value->prioritas ?? '-',
                'tanggal' => $value->tanggal ?? '-',
                'status' => $value->status ?? '-',
                'created_at' => $value->created_at,
                'gambar' => $value->gambar
            ];
        }



        return response()->json($data, 200);
    }

    public function edit(HelpDesk $helpDesk)
    {
        return view('pages.admin.helpDesk-edit', compact('helpDesk'));
    }

    public function update(Request $request, HelpDesk $helpDesk)
    {
        $item = HelpDesk::findOrFail($helpDesk->id);
        $item->update($request->only('keterangan')); // field yang bisa diupdate
        return response()->json([
            'success' => true,
            'message' => 'Data berhasil diupdate',
            'data' => $item // kembalikan row terbaru untuk bootstrap table
        ]);
    }

    public function updateStatus(HelpDesk $helpDesk)
    {
        // Logika status
        if ($helpDesk->status === 'accept') {
            $helpDesk->status = 'on-progress';
            $message = 'Berhasil menerima Helpdesk';
        } elseif ($helpDesk->status === 'on-progress') {
            $helpDesk->status = 'done';
            $message = 'Berhasil menyelesaikan Helpdesk';
        } else {
            $message = 'Status sudah done';
        }

        $helpDesk->save();

        broadcast(new HelpdeskStatusUpdated([
            'id' => $helpDesk->id,
            'tiket' => $helpDesk->tiket,
            'judul_laporan' => $helpDesk->judul_laporan,
            'status' => $helpDesk->status,
            'updated_at' => $helpDesk->updated_at->toDateTimeString(),
        ]))->toOthers();

        return response()->json([
            'success' => true,
            'message' => $message,
            'new_status' => $helpDesk->status
        ]);
    }


    public function destroy($id)
    {
        $helpdesk = HelpDesk::findOrFail($id);

        if ($helpdesk->gambar) {
            $images = json_decode($helpdesk->gambar, true);
            $path = public_path('uploads/images/help-desk/');

            foreach ($images as $image) {
                $filePath = $path . $image;
                if (File::exists($filePath)) {
                    File::delete($filePath);
                }
            }
        }

        $helpdesk->delete();

        return response()->json([
            'success' => true,
            'message' => 'Data dan gambar berhasil dihapus'
        ]);
    }


    public function getHelpdeskInfo($id)
    {
        try {
            $helpdesk = HelpDesk::with('user.rolls')->findOrFail($id);

            return response()->json([
                'success' => true,
                'id' => $helpdesk->id,
                'username' => $helpdesk->user->username ?? 'Unknown',
                'nama_lengkap' => $helpdesk->user->nama_lengkap ?? 'Unknown',
                'judul_laporan' => $helpdesk->judul_laporan,
                'department' => $helpdesk->user->rolls->nama ?? 'Unknown',
                'tiket' => $helpdesk->tiket,
                'prioritas' => $helpdesk->prioritas,
                'kategori' => $helpdesk->kategori,
                'keterangan' => $helpdesk->keterangan,
                'status' => $helpdesk->status,
                'tanggal' => $helpdesk->tanggal,
                'created_at' => $helpdesk->created_at,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Helpdesk tidak ditemukan'
            ], 404);
        }
    }
}
