<?php

namespace App\Http\Controllers\Surat;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Surat\Surat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use Dompdf\Dompdf;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Facades\File;

class SuratController extends Controller
{
    public function index()
    {
        $data = [
            'title' => 'Surat',
            'menuTitle' => 'Administrasi',
            'menuSubtitle' => 'Surat',
        ];

        $users = User::where('status', 'aktif')
            ->orderBy('username', 'asc')
            ->get();

        return view('surat.list-surat.list-surat', [
            'data' => $data,
            'users' => $users,
        ]);
    }

    // Nomor Surat Otomatis
    public function getNomorSurat()
    {
        $kodeSurat = session('kode_surat');
        $tahun = date('Y');
        $idUnit = session('id_unit');

        if (!$kodeSurat) {
            return response()->json([
                'success' => false,
                'message' => 'Kode surat tidak ditemukan pada session.'
            ], 400);
        }

        // Cari surat terakhir berdasarkan kode surat,
        $lastSurat = DB::table('surat')
            ->where('id_unit', $idUnit)
            ->where('no_surat', 'like', '%/' . $kodeSurat . '/' . $tahun . '-S0')
            ->orderByDesc('id')
            ->first();

        $nomorUrut = 1;
        if ($lastSurat) {
            preg_match('/^(\d+)\/' . preg_quote($kodeSurat, '/') . '\/' . $tahun . '-S0$/', $lastSurat->no_surat, $matches);
            if (isset($matches[1])) {
                $nomorUrut = intval($matches[1]) + 1;
            }
        }

        $nomor = str_pad($nomorUrut, 4, '0', STR_PAD_LEFT);
        $noSurat = $nomor . '/' . $kodeSurat . '/' . $tahun . '-S0';
        return response()->json([
            'success' => true,
            'no_surat' => $noSurat
        ]);
    }

    // View surat
    public function views(Request $request)
    {
        $id_unit = session('id_unit');
        $query = Surat::query()
            ->leftJoin('tbl_aproval', 'tbl_aproval.id', '=', 'surat.approval_id')
            ->where('surat.id_unit', $id_unit)
            ->select(
                'surat.id',
                'surat.tanggal',
                'surat.no_surat',
                'surat.approval_id',
                'surat.lampiran',
                'surat.jumlah_lampiran',
                'surat.perihal',
                'surat.isi_surat',
                'surat.status',
                'surat.id_unit',
                'surat.created_at',
                'tbl_aproval.nama_aproval as nama_aproval'
            )
            ->orderByDesc('surat.created_at')
            ->get();
        $data = [];
        foreach ($query as $value) {
            $data[] = [
                'id' => $value->id,
                'tanggal' => Carbon::parse($value->tanggal)->format('d/m/Y'),
                'no_surat' => $value->no_surat,
                'approval_id' => $value->approval_id,
                'lampiran' => $value->lampiran,
                'jumlah_lampiran' => $value->jumlah_lampiran,
                'perihal' => $value->perihal,
                'isi_surat' => $value->isi_surat,
                'status' => $value->status,
                'id_unit' => $value->id_unit,
                'nama_aproval' => $value->nama_aproval,
                'created_at' => Carbon::parse($value->created_at)->format('d/m/Y H:i:s')
            ];
        }
        return response()->json($data, 200);
    }

    // View Approval Surat
    public function viewsapproval(Request $request)
    {
        $query = DB::table('tbl_aproval_surat')
            ->leftJoin(
                'pegawai',
                'pegawai.id',
                '=',
                'tbl_aproval_surat.id_pegawai'
            )
            ->select(
                'tbl_aproval_surat.id',
                'tbl_aproval_surat.id_surat',
                'tbl_aproval_surat.parent_jabatan',
                'tbl_aproval_surat.id_pegawai',
                'tbl_aproval_surat.id_aproval',
                'tbl_aproval_surat.tanggal_aproval',
                'tbl_aproval_surat.keterangan',
                'pegawai.nama_pekerja'
            )
            ->where('tbl_aproval_surat.id_surat', $request->id_surat)
            ->orderBy('tbl_aproval_surat.parent_jabatan', 'desc');

        return response()->json($query->get(), 200);
    }

    // Simpan
    public function store(Request $request)
    {
        $request->validate([
            'lampiran' => 'nullable|array|max:5',
            'lampiran.*' => 'image|mimes:jpg,jpeg,png,webp|max:5120',
        ]);

        $id_unit = session('id_unit');
        $id_pegawai = session('id_pegawai');

        if (!$id_unit) {
            return response()->json([
                'success' => false,
                'message' => 'ID unit tidak ditemukan pada session.'
            ], 400);
        }

        DB::beginTransaction();

        $lampiranPaths = [];
        try {
            if ($request->hasFile('lampiran')) {
                $folder = public_path('uploads/surat/memo');

                // Buat folder jika belum ada
                if (!file_exists($folder)) {
                    mkdir($folder, 0755, true);
                }

                foreach ($request->file('lampiran') as $file) {
                    $namaFile = time() . '_' . uniqid() . '_' . $file->getClientOriginalName();
                    $file->move($folder, $namaFile);
                    $lampiranPaths[] = 'uploads/surat/memo/' . $namaFile;
                }
            }

            $tanggal = Carbon::createFromFormat('d/m/Y', $request->tanggal)->format('Y-m-d');
            $surat = Surat::create([
                'tanggal' => $tanggal,
                'no_surat' => $request->no_surat,
                'approval_id' => $request->approval_id,
                'lampiran' => json_encode($lampiranPaths),
                'perihal' => $request->perihal,
                'isi_surat' => $request->isi_surat,
                'jumlah_lampiran' => $request->jumlah_lampiran,
                'status' => 'Draft',
                'id_unit' => $id_unit,
                'id_pegawai' => $id_pegawai,
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'data' => $surat,
                'message' => 'Data Berhasil Ditambahkan.',
            ], 200);

        } catch (\Throwable $e) {
            DB::rollBack();
            foreach ($lampiranPaths as $path) {
                $filePath = public_path($path);
                if (file_exists($filePath)) {
                    unlink($filePath);
                }
            }

            return response()->json([
                'success' => false,
                'data' => [],
                'message' => 'Data Gagal Ditambahkan.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    // Update
    public function update(Request $request, $id)
    {
        $id_unit = session('id_unit');
        $id_pegawai = session('id_pegawai');

        if (!$id_unit) {
            return response()->json([
                'success' => false,
                'message' => 'ID unit tidak ditemukan pada session.'
            ], 400);
        }

        $surat = Surat::where('id', $id)
            ->where('id_unit', $id_unit)
            ->first();

        if (!$surat) {
            return response()->json([
                'success' => false,
                'message' => 'Data surat tidak ditemukan.'
            ], 404);
        }

        DB::beginTransaction();

        $lampiranBaru = [];
        $lampiranLama = [];

        try {

            // Hapus Lampiran Lama
            if (!empty($surat->lampiran)) {
                $lampiranLama = json_decode($surat->lampiran, true);
                if (!is_array($lampiranLama)) {
                    $lampiranLama = [];
                }
            }

            // Chek apakah lampiran baru
            $adaLampiranBaru = $request->hasFile('lampiran');

            if ($adaLampiranBaru) {
                // Hampus Lampiran Lama
                foreach ($lampiranLama as $path) {
                    if (!empty($path)) {
                        $path = str_replace('public/', '', $path);
                        $filePath = public_path($path);
                        if (File::exists($filePath)) {
                            File::delete($filePath);
                        }
                    }
                }

                //  Lampiran Brau
                $folder = public_path('uploads/surat/memo');
                if (!file_exists($folder)) {
                    mkdir($folder, 0755, true);
                }

                foreach ($request->file('lampiran') as $file) {
                    $namaFile = time() . '_' .
                        uniqid() . '_' .
                        $file->getClientOriginalName();
                    $file->move($folder, $namaFile);
                    $lampiranBaru[] =  'uploads/surat/memo/' . $namaFile;
                }

            } else {
                $lampiranBaru = $lampiranLama;
            }


            $tanggal = Carbon::createFromFormat('d/m/Y',$request->tanggal)->format('Y-m-d');

            //  Update Data Surat
            $surat->update([
                'tanggal' => $tanggal,
                'no_surat' => $request->no_surat,
                'approval_id' => $request->approval_id,
                'lampiran' => json_encode($lampiranBaru),
                'jumlah_lampiran' => $request->jumlah_lampiran,
                'perihal' => $request->perihal,
                'isi_surat' => $request->isi_surat,
                'id_pegawai' => $id_pegawai,
            ]);

            DB::commit();
            return response()->json([
                'success' => true,
                'data' => $surat->fresh(),
                'message' => 'Data Surat berhasil diperbarui.'
            ], 200);

        } catch (\Throwable $e) {
            DB::rollBack();
            //  HAPUS FILE BARU JIKA UPDATE GAGAL
            foreach ($lampiranBaru as $path) {
                // Hanya hapus file yang benar-benar baru
                if (!in_array($path, $lampiranLama)) {
                    $filePath = public_path($path);
                    if (File::exists($filePath)) {
                        File::delete($filePath);
                    }
                }
            }

            return response()->json([
                'success' => false,
                'data' => [],
                'message' => 'Data Surat gagal diperbarui.',
                'error' => $e->getMessage()
            ], 500);
        }
    }


    // Hapus
    public function destroy($id)
    {
        $id_unit = session('id_unit');
        if (!$id_unit) {
            return response()->json([
                'success' => false,
                'message' => 'ID unit tidak ditemukan pada session.',
            ], 400);
        }

        $surat = Surat::where('id', $id)
            ->where('id_unit', $id_unit)
            ->first();

        if (!$surat) {
            return response()->json([
                'success' => false,
                'message' => 'Data surat tidak ditemukan.',
            ], 404);
        }

        DB::beginTransaction();

        try {
            $lampiranLama = [];
            if (!empty($surat->lampiran)) {
                $lampiranLama = json_decode(
                    $surat->lampiran,
                    true
                );

                if (!is_array($lampiranLama)) {
                    $lampiranLama = [];
                }
            }

            foreach ($lampiranLama as $path) {
                if (!empty($path)) {
                    $path = str_replace('public/', '', $path);
                    $filePath = public_path($path);
                    if (File::exists($filePath)) {
                        File::delete($filePath);
                    }
                }
            }

            $surat->delete();
            DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'Surat berhasil dihapus.',
            ], 200);

        } catch (\Throwable $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Surat gagal dihapus.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }


   // Update Status
    public function updateStatus(Request $request, $id)
    {
        DB::beginTransaction();

        try {

            // Update status surat
            $query = Surat::where('id', $id)->update([
                'status' => $request->status,
            ]);

            if (!$query) {
                DB::rollBack();

                return response()->json([
                    'success' => false,
                    'message' => 'Gagal mengubah status.',
                    'data' => [],
                ], 400);
            }

            // Jika status Approve, insert data approval
            if ($request->status === 'Approve') {
                $approval_id = $request->approval_id;

                // Ambil id_pegawai PEMBUAT surat ini (bukan yang sedang login,
                // supaya konsisten walau yang klik tombol Approve orang lain)
                $suratRow = DB::table('surat')->where('id', $id)->first();
                $idPegawaiPembuat = $suratRow->id_pegawai ?? null;

                $approvalDetails = DB::table('tbl_aproval_detail')
                    ->where('id_aproval', $approval_id)
                    ->get();

                foreach ($approvalDetails as $detail) {

                    // Kalau level approval ini kebetulan pegawai yang sama
                    // dengan pembuat surat -> auto-approve, jangan biarkan
                    // dia harus approve suratnya sendiri. Langsung lanjut
                    // ke level/atasan berikutnya.
                    $adalahPembuatSendiri = $idPegawaiPembuat
                        && (int) $detail->id_pegawai === (int) $idPegawaiPembuat;

                    DB::table('tbl_aproval_surat')->insert([
                        'id_surat' => $id,
                        'parent_jabatan' => $detail->parent_jabatan,
                        'id_aproval' => $detail->id_aproval,
                        'id_pegawai' => $detail->id_pegawai,
                        'id_unit' => $detail->id_unit,
                        'tanggal_aproval' => $adalahPembuatSendiri ? now() : null,
                        'keterangan' => $adalahPembuatSendiri
                            ? 'Auto-approve (pembuat surat)'
                            : null,
                        'status' => $adalahPembuatSendiri ? 'Approve' : 'Menunggu',
                    ]);
                }

                // Cek: kalau SEMUA level (termasuk yang baru saja auto-approve)
                // ternyata sudah approve semua (kasus ekstrem: cuma dia
                // sendiri 1 level di workflow ini), langsung set Selesai.
                $masihAdaPending = DB::table('tbl_aproval_surat')
                    ->where('id_surat', $id)
                    ->where('id_aproval', $approval_id)
                    ->whereNull('tanggal_aproval')
                    ->exists();

                if (!$masihAdaPending) {
                    DB::table('surat')
                        ->where('id', $id)
                        ->update(['status' => 'Selesai']);
                }
            }

            DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'Sukses mengubah status menjadi Approve.',
                'data' => [],
            ], 200);

        } catch (\Exception $e) {

            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Gagal mengubah status.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    // Print Pdf
    public function previewPdf($id)
    {
        // Ambil data surat
        $surat = DB::table('surat')
            ->leftJoin('tbl_aproval', 'tbl_aproval.id', '=', 'surat.approval_id')
            ->leftJoin('tbl_unit', 'tbl_unit.id', '=', 'surat.id_unit')
            ->leftJoin('pegawai', 'pegawai.id', '=', 'surat.id_pegawai')
            ->select(
                'surat.id',
                'surat.tanggal',
                'surat.no_surat',
                'surat.approval_id',
                'surat.lampiran',
                'surat.jumlah_lampiran',
                'surat.perihal',
                'surat.isi_surat',
                'surat.status',
                'surat.id_unit',
                'tbl_aproval.nama_aproval as nama_aproval',
                'tbl_unit.nama as nama_unit',
                'pegawai.nama_pekerja as nama_pegawai'
            )
            ->where('surat.id', $id)
            ->first();

        if (!$surat) {
            abort(404, 'Data surat tidak ditemukan.');
        }

        $bgPath = public_path('assets/images/ihc/bingkai_ihc.png');
        $bgBase64 = '';

        if (file_exists($bgPath)) {
            $bgBase64 = base64_encode(
                file_get_contents($bgPath)
            );
        }

        $isiSurat = $surat->isi_surat ?? '';
        $isiSurat = strip_tags(
            str_replace(
                ['</p>', '<br>', '<br/>', '<br />'],
                "\n",
                $isiSurat
            )
        );

        $paragrafIsi = array_values(
            array_filter(
                array_map(
                    'trim',
                    preg_split(
                        '/\r\n|\r|\n/',
                        $isiSurat
                    )
                ),
                fn($p) => $p !== ''
            )
        );

        $lampiranEncoded = [];
        $lampiran = [];

        if (!empty($surat->lampiran)) {

            $lampiran = json_decode($surat->lampiran, true);

            if (!is_array($lampiran)) {
                $lampiran = [];
            }
        }

        foreach ($lampiran as $file) {

            if (empty($file)) {
                continue;
            }

            $fileName = basename($file);

            // Lokasi file fisik
            $fullPath = public_path('uploads/surat/memo/' . $fileName);

            if (!file_exists($fullPath)) {
                continue;
            }

            $ext = strtolower(
                pathinfo($fileName, PATHINFO_EXTENSION)
            );

            // Hanya gambar
            if (!in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'webp'])) {
                continue;
            }

            // MIME type gambar
            $mime = mime_content_type($fullPath);

            // Base64 untuk Dompdf
            $base64 = base64_encode(
                file_get_contents($fullPath)
            );

            $lampiranEncoded[] = [
                'name' => $fileName,
                'mime' => $mime,
                'base64' => $base64,
                'extension' => $ext,
            ];
        }

        $tanggalSurat = Carbon::parse($surat->tanggal)
            ->translatedFormat('d F Y');

        $qrData =
            "Nomor Surat : " . $surat->no_surat . "\n" .
            "Tanggal : " . $tanggalSurat . "\n" .
            "Perihal : " . $surat->perihal . "\n" .
            "Nama Pegawai : " . $surat->nama_pegawai . "\n" .
            "Verifikasi Dokumen: : " . (strcasecmp($surat->status, 'Approve') === 0 ? 'Terverifikasi' : $surat->status);

        $qrSvg = QrCode::size(120)
            ->margin(1)
            ->generate($qrData);

        $qrCode = 'data:image/svg+xml;base64,' . base64_encode($qrSvg);

        $html = view(
            'surat.list-surat.preview',
            [
                'surat' => $surat,
                'tanggal' => Carbon::parse($surat->tanggal)->locale('id')->translatedFormat('d F Y'),
                'bgBase64' => $bgBase64,
                'lampiranList' => $lampiranEncoded,
                'jumlah_lampiran' => $surat->jumlah_lampiran,
                'status' => $surat->status,
                'id_unit' => $surat->id_unit,
                'nama_unit' => $surat->nama_unit,
                'isi_surat' => $surat->isi_surat,
                'nama_pegawai' => $surat->nama_pegawai,
                'qrCode' => $qrCode,
            ]
        )->render();
        $namaFile = str_replace(['/', '\\'], '-', $surat->no_surat) . '.pdf';
        $dompdf = new Dompdf();
        $dompdf->loadHtml($html);
        $dompdf->setPaper('a4', 'portrait');
        $dompdf->addInfo('Title', pathinfo($namaFile, PATHINFO_FILENAME));
        $dompdf->render();

        return $dompdf->stream(
            $namaFile,
            [
                'Attachment' => false
            ]
        );
    }
}
