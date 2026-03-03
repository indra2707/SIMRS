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
use Illuminate\Support\Facades\Session;
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

    // view jumlah data
    public function count()
    {
        try {
            $namaRole = Session::get('nama_role');

            $query = HelpDesk::query();

            // Filter berdasarkan role
            if (in_array($namaRole, ['Teknik', 'Medis', 'General Affair'])) {
                $query->whereHas('user.rolls', function ($q) use ($namaRole) {
                    $q->where('kategori', $namaRole);
                });
            }

            $countAccept = (clone $query)
                ->where('status', 'accept')
                ->count();

            return response()->json([
                'status' => true,
                'count_accept' => $countAccept
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }


    //View
    public function views(Request $request)
    {
        try {
            $namaRole = Session::get('nama_role');
            $query = HelpDesk::with(['user.rolls'])
                ->join('users', 'users.id', '=', 'help_desk.user_id')
                ->join('pegawai', 'pegawai.id', '=', 'users.id_pegawai')
                ->join('tbl_unit', 'tbl_unit.id', '=', 'help_desk.id_unit')
                ->select(
                    'help_desk.*',
                    'help_desk.created_at as created_at',
                    'users.username as user_name',
                    'pegawai.nama_pekerja as nama_lengkap',
                    'tbl_unit.nama as nama_unit'
                )

                ->orderBy('created_at', 'DESC');

            // Filter berdasarkan role
            if (in_array($namaRole, ['Teknik', 'Medis', 'General Affair'])) {
                $query->whereHas('user.rolls', function ($q) use ($namaRole) {
                    $q->where('kategori', $namaRole);
                });
            }

            // Filter tanggal
            if ($request->tgl_awal && $request->tgl_akhir) {
                $query->whereBetween('tanggal', [
                    Carbon::parse($request->tgl_awal)->startOfDay(),
                    Carbon::parse($request->tgl_akhir)->endOfDay()
                ]);
            }

            $data = $query->get()->map(function ($value) {
                return [
                    'id' => $value->id,
                    'username' => $value->user->username ?? '-',
                    'nama_unit' => $value->nama_unit ?? '-',
                    'nama_lengkap' => $value->nama_lengkap ?? '-',
                    'keterangan' => $value->keterangan ?? '-',
                    'catatan' => $value->catatan,
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

        } catch (\Exception $e) {
            Log::error('Views error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error loading data: ' . $e->getMessage()
            ], 500);
        }
    }

    // Edit
    public function edit(HelpDesk $helpDesk)
    {
        return view('pages.admin.helpDesk-edit', compact('helpDesk'));
    }

    //Update Status
    public function updateStatus(HelpDesk $helpDesk)
    {
        try {
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

            // SIMPLE: Hanya pakai username
            $updatedBy = session('nama_pekerja');

            Log::info('Update status by: ' . $updatedBy, [
                'user_id' => Auth::id(),
                'helpdesk_id' => $helpDesk->id,
                'new_status' => $helpDesk->status
            ]);

            $helpDesk->updated_by = $updatedBy;
            $helpDesk->save();

            // Broadcast event
            try {
                broadcast(new HelpdeskStatusUpdated([
                    'id' => $helpDesk->id,
                    'tiket' => $helpDesk->tiket,
                    'judul_laporan' => $helpDesk->judul_laporan,
                    'status' => $helpDesk->status,
                    'updated_by' => $updatedBy,
                    'updated_at' => $helpDesk->updated_at->toDateTimeString(),
                ]))->toOthers();

                Log::info('Status update broadcasted successfully');
            } catch (\Exception $e) {
                Log::error('Broadcast error: ' . $e->getMessage());
            }

            return response()->json([
                'success' => true,
                'message' => $message,
                'new_status' => $helpDesk->status,
                'updated_by' => $updatedBy
            ]);

        } catch (\Exception $e) {
            Log::error('Update status error: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());

            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }

    // Hapus
    public function destroy($id)
    {
        try {
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

        } catch (\Exception $e) {
            Log::error('Delete error: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
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
            Log::error('Get helpdesk info error: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Helpdesk tidak ditemukan'
            ], 404);
        }
    }

    // Input Gambar2
    public function update(Request $request, HelpDesk $helpDesk)
    {
        try {
            $request->validate([
                'lampiran_selesai.*' => 'file|mimes:jpg,jpeg,png|max:2048',
                'catatan' => 'nullable|string',
            ]);

            $path = public_path('uploads/images/help-desk');
            $gambar2 = $helpDesk->gambar2 ? json_decode($helpDesk->gambar2, true) : [];

            if ($request->hasFile('lampiran_selesai')) {
                // hapus file lama
                if (is_array($gambar2)) {
                    foreach ($gambar2 as $img) {
                        $filePath = $path . '/' . $img;
                        if (File::exists($filePath)) {
                            File::delete($filePath);
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
            }

            $helpDesk->update([
                'gambar2' => json_encode($gambar2),
                'catatan' => $request->input('catatan'),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Berhasil update laporan Help Desk',
                'data' => $helpDesk->fresh()
            ]);

        } catch (\Exception $e) {
            Log::error('Update helpdesk error: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }
}