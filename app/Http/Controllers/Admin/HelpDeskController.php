<?php

namespace App\Http\Controllers\Admin;

use Carbon\Carbon;
use App\Models\HelpDesk;
use App\Models\User\Users;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Events\HelpdeskStatusUpdated;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

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

    //View
    public function views(Request $request)
    {
        $query = HelpDesk::with(['user.rolls']);
        if ($request->tgl_awal && $request->tgl_akhir) {
            $query->whereBetween('tanggal', [
                Carbon::parse($request->tgl_awal)->startOfDay(),
                Carbon::parse($request->tgl_akhir)->endOfDay()
            ]);
        }

        $data = $query->get()->map(function ($value) {
            return [
                'id' => $value->id,
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
                'created_at' => Carbon::parse($value->created_at)->format('d-m-Y H:i'),
                'gambar' => $value->gambar,
                'gambar2' => $value->gambar2 ? json_decode($value->gambar2, true) : [],
                'tgl_terima' => $value->tgl_terima ? Carbon::parse($value->tgl_terima)->format('d-m-Y H:i') : '-',
                'tgl_selesai' => $value->tgl_selesai ? Carbon::parse($value->tgl_selesai)->format('d-m-Y H:i') : '-',
                'updated_by' => $value->updated_by ?? '-'
            ];
        });

        return response()->json($data, 200);
    }

    public function edit(HelpDesk $helpDesk)
    {
        return view('pages.admin.helpDesk-edit', compact('helpDesk'));
    }

    //Update Status
    public function updateStatus(HelpDesk $helpDesk)
    {
        if ($helpDesk->status === 'accept') {
            $helpDesk->status = 'on-progress';
            $helpDesk->tgl_terima = Carbon::now();
            $message = 'Berhasil menerima Helpdesk';

        } elseif ($helpDesk->status === 'on-progress') {
            $helpDesk->status = 'done';
            $helpDesk->tgl_selesai = Carbon::now();
            $message = 'Berhasil menyelesaikan Helpdesk';

        } else {
            return response()->json([
                'success' => false,
                'message' => 'Status sudah done'
            ], 400);
        }

        $helpDesk->updated_by = Auth::user()->nama_lengkap;
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

    // Hapus
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

    //Info
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
                'updated_by' => $helpdesk->updated_by

            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Helpdesk tidak ditemukan'
            ], 404);
        }
    }


    // Input Gmbar2
    public function update(Request $request, HelpDesk $helpDesk)
    {
        $request->validate([
            'lampiran_selesai.*' => 'file|mimes:jpg,jpeg,png|max:2048',
        ]);

        $path = public_path('uploads/images/help-desk');

        if ($request->hasFile('lampiran_selesai')) {
            if ($helpDesk->gambar2) {
                $oldImages = json_decode($helpDesk->gambar2, true);

                if (is_array($oldImages)) {
                    foreach ($oldImages as $img) {
                        $filePath = $path . '/' . $img;
                        if (File::exists($filePath)) {
                            File::delete($filePath);
                        }
                    }
                }
            }

            $gambar2 = [];
            if (!File::exists($path)) {
                File::makeDirectory($path, 0755, true);
            }

            foreach ($request->file('lampiran_selesai') as $file) {
                if ($file->isValid()) {
                    $filename = uniqid('hd_') . '.' . $file->getClientOriginalExtension();
                    $file->move($path, $filename);
                    $gambar2[] = $filename;
                }
            }

            $helpDesk->update([
                'gambar2' => json_encode($gambar2),
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Berhasil update laporan Help Desk',
            'data' => $helpDesk->fresh()
        ]);
    }

}
