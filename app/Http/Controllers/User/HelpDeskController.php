<?php

namespace App\Http\Controllers\User;

use Carbon\Carbon;
use App\Models\User;
use App\Models\HelpDesk;
use PHPUnit\TextUI\Help;
use Illuminate\Http\Request;
use App\Events\HelpdeskCreated;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;

class HelpDeskController extends Controller
{
    //Index
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
        $query = HelpDesk::query()
            ->join('users', 'users.id', '=', 'help_desk.user_id')
            ->where('help_desk.user_id', auth()->id())
            ->select(
                'help_desk.*',
                'help_desk.created_at as created_at',
                'users.username as user_name'
            );

        // FILTER TANGGAL
        if ($request->tgl_awal && $request->tgl_akhir) {
            $query->whereBetween('tanggal', [
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
                'created_at' => Carbon::parse($value->created_at)->format('d-m-Y H:i'),
                'user_name' => $value->user_name,
                'catatan' => $value->catatan,
                'updated_by' => $value->updated_by,
                'lampiran' => $value->gambar ? json_decode($value->gambar, true) : [],
                'tgl_terima' => $value->tgl_terima ? Carbon::parse($value->tgl_terima)->format('d-m-Y H:i') : '-',
                'tgl_selesai' => $value->tgl_selesai ? Carbon::parse($value->tgl_selesai)->format('d-m-Y H:i') : '-',
                'gambar2' => $value->gambar2 ? json_decode($value->gambar2, true) : [],
            ];
        });

        return response()->json($data);
    }

    // Simpan
    public function store(Request $request)
    {
        if (!is_dir('uploads/images/help-desk/')) {
            mkdir('uploads/images/help-desk/', 0777, true);
        }
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
            'user_id' => session('id'),
            // 'created_by' => Auth::user()->nama_lengkap ?? Auth::user()->username ?? 'Unknown',
            'tiket' => 'IHC-' . now()->format('YmdHis'),
            'judul_laporan' => $request->judul_laporan,
            'kategori' => $request->kategori,
            'prioritas' => $request->prioritas,
            // 'keterangan' => $request->keterangan,
            'status' => 'accept',
            'tanggal' => now(),
            'gambar' => !empty($gambar) ? json_encode($gambar) : null,
        ]);

        broadcast(new HelpdeskCreated([
            'id' => $helpdesk->id,
            // 'tiket' => $helpdesk->tiket,
            // 'judul_laporan' => $helpdesk->judul_laporan,
            'kategori' => $helpdesk->kategori,
            'prioritas' => $helpdesk->prioritas,
            'status' => $helpdesk->status,
            // 'tanggal' => $helpdesk->tanggal,
        ]))->toOthers();
        return response()->json([
            'success' => true,
            'message' => 'Berhasil membuat laporan Help Desk',
            'data' => $helpdesk
        ]);
    }

    public function getHelpdeskInfo($id)
    {
        try {
            $helpdesk = HelpDesk::with('user')->findOrFail($id);

            // Ambil nama dari kolom updated_by
            $opponentName = $helpdesk->updated_by ?? 'Support Team';
            $opponentUsername = '';
            $opponentRole = '';

            if ($helpdesk->updated_by) {
                $admin = User::where('nama_lengkap', $helpdesk->updated_by)->first();

                if ($admin) {
                    $opponentUsername = $admin->username;
                    $opponentRole = $admin->role; // superadmin, admin, support, dll
                }
            }

            return response()->json([
                'success' => true,
                'nama_lengkap' => $opponentName,
                'username' => $opponentUsername,
                'role' => $opponentRole,  // Kirim role
                'judul_laporan' => $helpdesk->judul_laporan,
                'status' => $helpdesk->status,
            ]);
        } catch (\Exception $e) {
            Log::error('Error getting helpdesk info: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Gagal memuat informasi'
            ], 500);
        }
    }

    // Hapus
    public function destroy(HelpDesk $helpDesk)
    {
        if ($helpDesk->gambar) {
            $images = json_decode($helpDesk->gambar, true);

            if (is_array($images)) {
                foreach ($images as $img) {
                    $path = public_path('uploads/images/help-desk/' . $img);

                    if (File::exists($path)) {
                        File::delete($path);
                    }
                }
            }
        }

        $helpDesk->delete();
        return response()->json([
            'success' => true,
            'message' => 'Berhasil Menghapus Data Laporan',
        ], 200);
    }
}
