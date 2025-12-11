<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HelpDesk;
use Illuminate\Http\Request;
use App\Events\HelpdeskStatusUpdated;

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
        $query = HelpDesk::all();

        $data = []; 
        foreach ($query as $key => $value) {
            $data[] = [
                'id' => $value->id,
                // 'nama_lengkap' => $value->kode,
                // 'username' => $value->kategori,
                'nama_lengkap' => $value->user->nama_lengkap ?? '-',
                'username' => $value->user->username ?? '-',
                'department' => $value->user->department->nama ?? '-',
                'keterangan' => $value->keterangan ?? '-',
                'tanggal' => $value->tanggal ?? '-',
                'status' => $value->status ?? '-',
                'created_at' => $value->created_at,
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
        // Logika status: accept → on-progress → done
        if ($helpDesk->status === 'accept') {
            $helpDesk->status = 'on-progress';
            $message = 'Berhasil menerima Helpdesk';
        } elseif ($helpDesk->status === 'on-progress') {
            $helpDesk->status = 'done';
            $message = 'Berhasil menyelesaikan Helpdesk';
        } else {
            $helpDesk->status = 'done'; // tetap done
            $message = 'Status sudah done';
        }

        $helpDesk->save();
        HelpdeskStatusUpdated::dispatch($helpDesk);
        return response()->json([
            'success' => true,
            'message' => $message,
            'new_status' => $helpDesk->status
        ]);
    }


    public function destroy(HelpDesk $helpDesk)
    {
        $helpDesk->delete();
        return response()->json([
            'success' => true,
            'message' => 'Berhasil Menghapus Data Laporan',

        ], 200);
    }


    public function getHelpdeskInfo($id)
    {
        try {
            $helpdesk = HelpDesk::with('user')->findOrFail($id);

            return response()->json([
                'success' => true,
                'id' => $helpdesk->id,
                'username' => $helpdesk->user->username ?? 'Unknown',
                'nama_lengkap' => $helpdesk->user->nama_lengkap ?? 'Unknown',
                'department' => $helpdesk->department,
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
