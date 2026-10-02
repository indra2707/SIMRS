<?php

namespace App\Http\Controllers\Surat;

use App\Http\Controllers\Controller;
use App\Models\Surat\DisposisiSurat;
use App\Models\Surat\DisposisiMaster;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DisposisiSuratController extends Controller
{
    public function index()
    {
        $data = [
            'title' => 'Disposisi Surat',
            'menuTitle' => 'Surat',
            'menuSubtitle' => 'Disposisi',
        ];

        return view('surat.disposisi.disposisi', $data);
    }

    /**
     * List disposisi masuk untuk pegawai yang login.
     * Hanya menampilkan disposisi yang id_pegawai_tujuan = pegawai login (aturan: "hanya lihat punya sendiri").
     */
    public function views(Request $request)
    {
        $idPegawai = session('id_pegawai');
        $idUnit = session('id_unit');

        if (!$idPegawai) {
            return response()->json([
                'success' => false,
                'message' => 'ID pegawai tidak ditemukan pada session.',
                'data' => [],
            ], 401);
        }

        $query = DB::table('tbl_disposisi_surat as ds')
            ->join('surat as s', 's.id', '=', 'ds.id_surat')
            ->leftJoin('pegawai as p_kirim', 'p_kirim.id', '=', 'ds.id_pegawai_pengirim')
            ->leftJoin('jabatan as j_tujuan', 'j_tujuan.id', '=', 'ds.id_jabatan_tujuan')
            ->select(
                'ds.id as id_disposisi',
                'ds.id_surat',
                'ds.id_parent',
                'ds.is_action',
                'ds.is_tanggapan',
                'ds.is_info',
                'ds.is_file',
                'ds.tingkat_surat',
                'ds.catatan',
                'ds.tanggal_diteruskan',
                'ds.paraf',
                'ds.status',
                'ds.tanggal_dibaca',
                'ds.tanggal_selesai',
                's.no_surat',
                's.perihal',
                's.tanggal',
                's.lampiran',
                'p_kirim.nama_pekerja as nama_pengirim',
                'j_tujuan.nama_jabatan as nama_jabatan_tujuan'
            )
            ->where('ds.id_pegawai_tujuan', $idPegawai);

        // Filter tambahan opsional: status
        if ($request->filled('status')) {
            $query->where('ds.status', $request->status);
        }

        $query->orderByDesc('ds.created_at');

        $result = $query->get();

        $data = [];
        foreach ($result as $value) {
            $lampiranArr = [];
            if (!empty($value->lampiran)) {
                $lampiranArr = json_decode($value->lampiran, true);
                if (!is_array($lampiranArr)) {
                    $lampiranArr = [];
                }
            }

            // Apakah disposisi ini pernah diteruskan lagi oleh penerima ke bawahannya?
            $sudahDiteruskan = DB::table('tbl_disposisi_surat')
                ->where('id_parent', $value->id_disposisi)
                ->exists();

            $data[] = [
                'id_disposisi' => $value->id_disposisi,
                'id_surat' => $value->id_surat,
                'id_parent' => $value->id_parent,
                'no_surat' => $value->no_surat,
                'perihal' => $value->perihal,
                'tanggal' => $value->tanggal,
                'lampiran' => $lampiranArr,
                'nama_pengirim' => $value->nama_pengirim,
                'nama_jabatan_tujuan' => $value->nama_jabatan_tujuan,
                'is_action' => (bool) $value->is_action,
                'is_tanggapan' => (bool) $value->is_tanggapan,
                'is_info' => (bool) $value->is_info,
                'is_file' => (bool) $value->is_file,
                'tingkat_surat' => $value->tingkat_surat,
                'catatan' => $value->catatan,
                'tanggal_diteruskan' => $value->tanggal_diteruskan,
                'paraf' => $value->paraf,
                'status' => $value->status,
                'tanggal_dibaca' => $value->tanggal_dibaca,
                'tanggal_selesai' => $value->tanggal_selesai,
                'sudah_diteruskan' => $sudahDiteruskan,
            ];
        }

        return response()->json($data, 200);
    }

    /**
     * Detail 1 disposisi. Dipanggil saat penerima membuka disposisinya.
     * Otomatis update status Menunggu -> Dibaca.
     */
    public function show($id)
    {
        $idPegawai = session('id_pegawai');

        $disposisi = DisposisiSurat::with(['surat', 'pengirim', 'tujuan', 'jabatanTujuan', 'parent'])
            ->where('id', $id)
            ->where('id_pegawai_tujuan', $idPegawai)
            ->first();

        if (!$disposisi) {
            return response()->json([
                'success' => false,
                'message' => 'Data disposisi tidak ditemukan.',
            ], 404);
        }

        if ($disposisi->status === 'Menunggu') {
            $disposisi->update([
                'status' => 'Dibaca',
                'tanggal_dibaca' => now(),
            ]);
        }

        return response()->json([
            'success' => true,
            'data' => $disposisi,
        ], 200);
    }

    /**
     * Buat disposisi baru.
     * Dipakai untuk:
     * 1) Disposisi awal (Direktur/pejabat memilih siapa saja tujuannya) -> id_parent dikirim null.
     * 2) Meneruskan disposisi yang sudah diterima (mis. VD HCGA -> Head of Logistics)
     *    -> id_parent diisi dengan id disposisi milik pengirim (harus dicek kepemilikannya).
     *
     * PENTING: tujuan dipilih berdasarkan JABATAN (sesuai kertas, bukan nama orang).
     * Backend akan resolve setiap id_jabatan ke pegawai yang SAAT INI memegang
     * jabatan tsb (lihat helper getPegawaiByJabatan()). Kalau suatu jabatan lagi
     * kosong (belum ada pegawainya), baris itu dilewati dan dilaporkan di response.
     *
     * Tidak ada keterkaitan sama sekali dengan alur approval (tbl_aproval_surat) --
     * disposisi murni berbasis id_surat.
     *
     * Format request:
     * {
     *   "id_surat": 1,
     *   "id_parent": null, // atau id disposisi yang sedang diteruskan
     *   "tujuan": [
     *      { "id_jabatan": 5, "id_unit": 2, "is_action": true,  "is_tanggapan": false, "is_info": true,  "is_file": false, "tingkat_surat": "P", "catatan": "Mohon ditindaklanjuti" },
     *      { "id_jabatan": 8, "id_unit": 3, "is_action": false, "is_tanggapan": true,  "is_info": false, "is_file": false, "tingkat_surat": "B", "catatan": null }
     *   ]
     * }
     */
    public function store(Request $request)
    {
        $request->validate([
            'id_surat' => 'required|integer|exists:surat,id',
            'id_parent' => 'nullable|integer|exists:tbl_disposisi_surat,id',
            'tujuan' => 'required|array|min:1',
            'tujuan.*.id_jabatan' => 'required|integer|exists:jabatan,id',
            'tujuan.*.id_unit' => 'nullable|integer',
            'tujuan.*.is_action' => 'nullable|boolean',
            'tujuan.*.is_tanggapan' => 'nullable|boolean',
            'tujuan.*.is_info' => 'nullable|boolean',
            'tujuan.*.is_file' => 'nullable|boolean',
            'tujuan.*.tingkat_surat' => 'nullable|in:R,P,S,B',
            'tujuan.*.catatan' => 'nullable|string|max:1000',
        ]);

        $idPegawai = session('id_pegawai');
        $idUnit = session('id_unit');

        if (!$idPegawai) {
            return response()->json([
                'success' => false,
                'message' => 'ID pegawai tidak ditemukan pada session.',
            ], 401);
        }

        $idParent = $request->id_parent;

        // Jika ini adalah "meneruskan" disposisi (bukan disposisi awal),
        // pastikan disposisi parent memang milik pegawai yang sedang login.
        if ($idParent) {
            $parentValid = DisposisiSurat::where('id', $idParent)
                ->where('id_pegawai_tujuan', $idPegawai)
                ->exists();

            if (!$parentValid) {
                return response()->json([
                    'success' => false,
                    'message' => 'Disposisi yang ingin diteruskan tidak ditemukan atau bukan milik Anda.',
                ], 403);
            }
        }

        DB::beginTransaction();
        try {
            $dibuat = [];
            $jabatanKosong = [];

            foreach ($request->tujuan as $t) {
                $pegawaiTujuan = $this->getPegawaiByJabatan($t['id_jabatan']);

                if (!$pegawaiTujuan) {
                    // Jabatan ini belum ada pemegangnya sekarang -- lewati,
                    // catat supaya bisa dilaporkan balik ke pengirim.
                    $jabatanKosong[] = $t['id_jabatan'];
                    continue;
                }

                // Cegah disposisi ganda ke jabatan yang sama untuk surat & parent yang sama
                $sudahAda = DisposisiSurat::where('id_surat', $request->id_surat)
                    ->where('id_parent', $idParent)
                    ->where('id_jabatan_tujuan', $t['id_jabatan'])
                    ->exists();

                if ($sudahAda) {
                    continue;
                }

                $row = DisposisiSurat::create([
                    'id_surat' => $request->id_surat,
                    'id_parent' => $idParent,
                    'id_pegawai_pengirim' => $idPegawai,
                    'id_unit_pengirim' => $idUnit,
                    'id_pegawai_tujuan' => $pegawaiTujuan->id,
                    'id_jabatan_tujuan' => $t['id_jabatan'],
                    'id_unit_tujuan' => $t['id_unit'] ?? null,
                    'is_action' => $t['is_action'] ?? false,
                    'is_tanggapan' => $t['is_tanggapan'] ?? false,
                    'is_info' => $t['is_info'] ?? false,
                    'is_file' => $t['is_file'] ?? false,
                    'tingkat_surat' => $t['tingkat_surat'] ?? 'B',
                    'catatan' => $t['catatan'] ?? null,
                    'tanggal_diteruskan' => now(),
                    'status' => 'Menunggu',
                ]);

                $dibuat[] = $row->id;
            }

            if (empty($dibuat)) {
                DB::rollBack();

                return response()->json([
                    'success' => false,
                    'message' => count($jabatanKosong) > 0
                        ? 'Semua jabatan tujuan yang dipilih belum ada pemegangnya saat ini.'
                        : 'Tidak ada disposisi baru yang dibuat (mungkin sudah ada sebelumnya).',
                ], 422);
            }

            DB::commit();

            $pesan = count($dibuat) . ' disposisi berhasil dibuat.';
            if (count($jabatanKosong) > 0) {
                $pesan .= ' (' . count($jabatanKosong) . ' jabatan dilewati karena belum ada pemegangnya.)';
            }

            return response()->json([
                'success' => true,
                'message' => $pesan,
                'data' => $dibuat,
                'jabatan_kosong' => $jabatanKosong,
            ], 200);
        } catch (\Throwable $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Gagal membuat disposisi.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Tandai 1 disposisi (yang diterima pegawai login) sebagai Selesai.
     */
    public function selesai(Request $request, $id)
    {
        $idPegawai = session('id_pegawai');

        $disposisi = DisposisiSurat::where('id', $id)
            ->where('id_pegawai_tujuan', $idPegawai)
            ->first();

        if (!$disposisi) {
            return response()->json([
                'success' => false,
                'message' => 'Data disposisi tidak ditemukan.',
            ], 404);
        }

        $disposisi->update([
            'status' => 'Selesai',
            'tanggal_selesai' => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Disposisi ditandai selesai.',
        ], 200);
    }

    /**
     * Hapus disposisi. Hanya boleh oleh pengirimnya sendiri,
     * dan hanya jika belum diteruskan lagi ke orang lain (biar tree tidak putus tiba-tiba).
     */
    public function destroy($id)
    {
        $idPegawai = session('id_pegawai');

        $disposisi = DisposisiSurat::where('id', $id)
            ->where('id_pegawai_pengirim', $idPegawai)
            ->first();

        if (!$disposisi) {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak ditemukan atau bukan disposisi yang Anda buat.',
            ], 404);
        }

        $punyaTurunan = DisposisiSurat::where('id_parent', $id)->exists();
        if ($punyaTurunan) {
            return response()->json([
                'success' => false,
                'message' => 'Disposisi ini sudah diteruskan ke pihak lain, tidak bisa dihapus.',
            ], 400);
        }

        $disposisi->delete();

        return response()->json([
            'success' => true,
            'message' => 'Disposisi berhasil dihapus.',
        ], 200);
    }

    /**
     * Daftar surat yang bisa didisposisikan oleh pegawai yang login.
     *
     * Tidak terikat ke alur approval sama sekali -- cukup surat yang statusnya
     * sudah final (mis. "Selesai"), lalu pegawai ybs (yang punya lembar disposisi
     * sendiri di tbl_disposisi_master) bisa membuat disposisi darinya kapan saja.
     * Flag 'sudah_disposisi' menandakan pegawai ini sudah pernah membuat
     * disposisi root untuk surat tsb, supaya UI bisa sembunyikan/nonaktifkan tombolnya
     * (tidak wajib dicegah di backend, murni penanda -- lihat catatan di store()).
     */
    public function suratSiapDisposisi(Request $request)
    {
        $idPegawai = session('id_pegawai');
        $idUnit = session('id_unit');

        if (!$idPegawai) {
            return response()->json([
                'success' => false,
                'message' => 'ID pegawai tidak ditemukan pada session.',
                'data' => [],
            ], 401);
        }

        $status = $request->filled('status') ? $request->status : 'Selesai';

        $query = DB::table('surat as s')
            ->leftJoin('tbl_aproval as a', 'a.id', '=', 's.approval_id')
            ->leftJoin('pegawai as p', 'p.id', '=', 's.id_pegawai')
            ->select(
                's.id as id_surat',
                's.tanggal',
                's.no_surat',
                's.perihal',
                's.isi_surat',
                's.lampiran',
                's.jumlah_lampiran',
                's.status as status_surat',
                's.id_unit',
                's.created_at',
                'a.nama_aproval',
                'p.nama_pekerja as nama_pembuat'
            )
            ->where('s.status', $status)
            ->where('s.id_unit', $idUnit)
            ->orderByDesc('s.created_at')
            ->get();

        $data = [];
        foreach ($query as $value) {
            $lampiranArr = [];
            if (!empty($value->lampiran)) {
                $lampiranArr = json_decode($value->lampiran, true);
                if (!is_array($lampiranArr)) {
                    $lampiranArr = [];
                }
            }

            $sudahDisposisi = DisposisiSurat::where('id_surat', $value->id_surat)
                ->where('id_pegawai_pengirim', $idPegawai)
                ->whereNull('id_parent')
                ->exists();

            $data[] = [
                'id_surat' => $value->id_surat,
                'tanggal' => $value->tanggal,
                'no_surat' => $value->no_surat,
                'perihal' => $value->perihal,
                'isi_surat' => $value->isi_surat,
                'lampiran' => $lampiranArr,
                'nama_pembuat' => $value->nama_pembuat,
                'nama_aproval' => $value->nama_aproval,
                'status_surat' => $value->status_surat,
                'sudah_disposisi' => $sudahDisposisi,
            ];
        }

        return response()->json($data, 200);
    }

    /**
     * Daftar tujuan BAKU (template) sesuai "Lembar Penerus" milik pegawai yang login.
     * Contoh: kalau yang login adalah VD HCGA, akan muncul 8 JABATAN sesuai lembar VD HCGA
     * (bukan nama orang -- persis kertas aslinya).
     *
     * Dipakai untuk mengisi otomatis form "buat disposisi baru" -> user tinggal
     * centang siapa saja yang dituju + centang kolom A/T/I/F per orang, lalu submit ke store().
     */
    public function templateTujuan(Request $request)
    {
        $idPegawai = session('id_pegawai');

        if (!$idPegawai) {
            return response()->json([
                'success' => false,
                'message' => 'ID pegawai tidak ditemukan pada session.',
                'data' => [],
            ], 401);
        }

        $idJabatanLogin = $this->getJabatanPegawai($idPegawai);

        if (!$idJabatanLogin) {
            return response()->json([
                'success' => false,
                'message' => 'Jabatan Anda tidak ditemukan, tidak bisa memuat template disposisi.',
                'data' => [],
            ], 404);
        }

        $master = DisposisiMaster::with(['detail.jabatanTujuan'])
            ->where('id_jabatan_pemilik', $idJabatanLogin)
            ->where('status', 'Aktif')
            ->first();

        if (!$master) {
            return response()->json([
                'success' => false,
                'message' => 'Anda belum memiliki lembar disposisi (template tujuan). Silakan hubungi admin untuk mengaturnya.',
                'data' => [],
            ], 404);
        }

        $data = $master->detail->map(function ($d) {
            return [
                'id_detail' => $d->id,
                'urutan' => $d->urutan,
                'id_jabatan_tujuan' => $d->id_jabatan_tujuan,
                'nama_jabatan_tujuan' => optional($d->jabatanTujuan)->nama_jabatan,
                'id_unit' => $d->id_unit,
            ];
        })->values();

        return response()->json([
            'success' => true,
            'id_master' => $master->id,
            'nama_master' => $master->nama_master,
            'data' => $data,
        ], 200);
    }

    /**
     * Daftar jabatan untuk dipilih sebagai tujuan disposisi (dipakai admin
     * saat setup tbl_disposisi_master / tbl_disposisi_master_detail).
     * Sesuaikan nama tabel & kolom kalau berbeda di sistem Anda.
     */
    public function listJabatanTujuan(Request $request)
    {
        $query = DB::table('jabatan')
            ->select('id', 'nama_jabatan');

        if ($request->filled('search')) {
            $query->where('nama_jabatan', 'like', '%' . $request->search . '%');
        }

        return response()->json($query->orderBy('nama_jabatan')->get(), 200);
    }

    /**
     * ================== HELPER RESOLVE JABATAN <-> PEGAWAI ==================
     * Kedua method di bawah ini SATU-SATUNYA tempat yang tahu bagaimana cara
     * mencari jabatan seorang pegawai, dan mencari pegawai yang sedang
     * memegang suatu jabatan. Diisolasi di sini supaya kalau struktur tabel
     * jabatan/pegawai Anda ternyata berbeda dari asumsi (pegawai.id_jabatan),
     * cukup 2 method ini yang perlu diubah -- sisa controller tidak perlu disentuh.
     */

    // Cari id_jabatan milik seorang pegawai. Sesuaikan kalau strukturnya beda.
    private function getJabatanPegawai($idPegawai)
    {
        return DB::table('pegawai')
            ->where('id', $idPegawai)
            ->value('id_jabatan');
    }

    // Cari pegawai yang SAAT INI memegang suatu jabatan. Sesuaikan kalau strukturnya beda
    // (mis. kalau ada kolom status aktif di pegawai, tambahkan ->where('status','aktif')).
    private function getPegawaiByJabatan($idJabatan)
    {
        return DB::table('pegawai')
            ->where('id_jabatan', $idJabatan)
            ->first();
    }
}
