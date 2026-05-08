<?php

namespace App\Http\Controllers\RsOnline;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;

class TempattidurController extends Controller
{
    public function index()
    {
        $data = [
            'title' => 'RS Online',
            'menuTitle' => 'RS Online',
            'menuSubtitle' => 'Tempat Tidur',
        ];
        return view('rs-online.tempat_tidur.tempat_tidur', $data);
    }

    // Get data tempat tidur
    public function get(Request $request)
    {
        try {
            $timestamp = time();
            $response = Http::withoutVerifying()
                ->withHeaders([
                    'x-rs-id' => '7371449',
                    'x-pass' => 'Pertamedika@123',
                    'x-timestamp' => $timestamp,
                ])
                ->get('https://sirs.kemkes.go.id/fo/index.php/Referensi/tempat_tidur');
            $result = json_decode($response->body(), true);
            $data = $result['tempat_tidur'] ?? [];
            // ambil keyword search dari select2
            $search = $request->q;

            // filter nama_tt
            if ($search) {
                $data = array_filter($data, function ($item) use ($search) {
                    return stripos($item['nama_tt'], $search) !== false;
                });
                // reset index array
                $data = array_values($data);
            }
            return response()->json($data);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);

        }
    }

    // View
    public function views(Request $request)
    {
        try {
            $timestamp = time();
            $response = Http::withHeaders([
                'x-rs-id' => '7371449',
                'x-pass' => 'Pertamedika@123',
                'x-timestamp' => $timestamp,
            ])->get('https://sirs.kemkes.go.id/fo/index.php/Fasyankes');
            // Ambil body response
            $result = json_decode($response->body(), true);
            // return response()->json($result);
            $rows = [];
            // Sesuaikan dengan struktur response API
            if (isset($result['fasyankes'])) {
                foreach ($result['fasyankes'] as $row) {
                    $rows[] = [
                        'id_tt' => $row['id_tt'] ?? '',
                        'tt' => $row['tt'] ?? '',
                        'ruang' => $row['ruang'] ?? '',
                        'kode_siranap' => $row['kode_siranap'] ?? '',
                        'jumlah_ruang' => $row['jumlah_ruang'] ?? 0,
                        'jumlah' => $row['jumlah'] ?? 0,
                        'terpakai_suspek' => $row['terpakai_suspek'] ?? 0,
                        'terpakai_konfirmasi' => $row['terpakai_konfirmasi'] ?? 0,
                        'antrian' => $row['antrian'] ?? 0,
                        'prepare' => $row['prepare'] ?? 0,
                        'prepare_plan' => $row['prepare_plan'] ?? 0,
                        'kosong' => $row['kosong'] ?? 0,
                        'terpakai_dbd' => $row['terpakai_dbd'] ?? 0,
                        'terpakai_dbd_anak' => $row['terpakai_dbd_anak'] ?? 0,
                        'terpakai' => $row['terpakai'] ?? 0,
                        'covid' => $row['covid'] ?? 0,
                        'id_t_tt' => $row['id_t_tt'] ?? '',
                    ];
                }
            }
            return response()->json([
                'total' => count($rows),
                'rows' => $rows
            ]);

        } catch (\Exception $e) {

            return response()->json([
                'total' => 0,
                'rows' => [],
                'message' => $e->getMessage()
            ], 500);
        }
    }

    // Simpan
    public function store(Request $request)
    {
        try {

            $timestamp = time();

            // payload sesuai postman
            $payload = [
                "id_tt" => $request->id_tt,
                "ruang" => $request->ruang,
                "jumlah_ruang" => '0',
                "jumlah" => $request->jumlah,
                "terpakai" => $request->terpakai,
                "terpakai_suspek" => "0",
                "terpakai_konfirmasi" => "0",
                "antrian" => "0",
                "prepare" => "0",
                "prepare_plan" => "0",
                "covid" => 0
            ];

            $response = Http::withoutVerifying()
                ->withHeaders([
                    'x-rs-id' => '7371449',
                    'x-pass' => 'Pertamedika@123',
                    'x-timestamp' => $timestamp,
                    'Content-Type' => 'application/json',
                    'Accept' => 'application/json',
                ])
                ->withBody(
                    json_encode($payload),
                    'application/json'
                )
                ->post('https://sirs.kemkes.go.id/fo/index.php/Fasyankes');

            $result = json_decode($response->body(), true);

            // debug
            // dd($result);

            if (!$response->successful()) {

                return response()->json([
                    'success' => false,
                    'status' => $response->status(),
                    'message' => $result['message'] ?? 'Gagal simpan data',
                    'response' => $result
                ], $response->status());
            }

            return response()->json([
                'success' => true,
                'message' => $result['message'] ?? 'Data berhasil disimpan',
                'data' => $result
            ]);

        } catch (\Exception $e) {

            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);

        }
    }

    //Update
    public function update(Request $request, $id)
    {
        try {

            $timestamp = time();
            // Payload sesuai dokumentasi API
            $payload = [
                'id_t_tt' => $id,
                'ruang' => $request->ruang,
                'jumlah_ruang' => $request->jumlah_ruang,
                'jumlah' => $request->jumlah,
                'terpakai' => $request->terpakai,
                'terpakai_suspek' => $request->terpakai_suspek,
                'terpakai_konfirmasi' => $request->terpakai_konfirmasi,
                'antrian' => $request->antrian,
                'prepare' => $request->prepare,
                'prepare_plan' => $request->prepare_plan,
                'covid' => $request->covid,
            ];

            // Request PUT ke API SIRS
            $response = Http::withHeaders([
                'x-rs-id' => '7371449',
                'x-pass' => 'Pertamedika@123',
                'x-timestamp' => $timestamp,
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
            ])->send(
                    'PUT',
                    'https://sirs.kemkes.go.id/fo/index.php/Fasyankes',
                    [
                        'json' => $payload
                    ]
                );

            // Ambil response API
            $result = json_decode($response->body(), true);

            // Debug jika diperlukan
            // dd($result);

            // Jika gagal
            if (!$response->successful()) {

                return response()->json([
                    'success' => false,
                    'status' => $response->status(),
                    'message' => $result['message'] ?? 'Gagal update data',
                    'response' => $result
                ], $response->status());
            }

            // Success
            return response()->json([
                'success' => true,
                'timestamp' => $timestamp,
                'message' => $result['message'] ?? 'Data berhasil diupdate',
                'data' => $result
            ], 200);

        } catch (\Exception $e) {

            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }


    // Delete
    public function destroy($id)
    {
        try {

            $timestamp = time();

            $response = Http::withHeaders([
                'x-rs-id' => '7371449',
                'x-pass' => 'Pertamedika@123',
                'x-timestamp' => $timestamp,
                'Accept' => 'application/json',
            ])->send('DELETE', 'https://sirs.kemkes.go.id/fo/index.php/Fasyankes', [
                        'json' => [
                            'id_t_tt' => $id
                        ]
                    ]);

            $result = json_decode($response->body(), true);

            // Debug response
            // dd($result);

            if (!$response->successful()) {

                return response()->json([
                    'success' => false,
                    'status' => $response->status(),
                    'message' => $result['message'] ?? 'Gagal menghapus data',
                    'response' => $result
                ], $response->status());
            }

            return response()->json([
                'success' => true,
                'timestamp' => $timestamp,
                'message' => $result['message'] ?? 'Data berhasil dihapus',
                'data' => $result
            ], 200);

        } catch (\Exception $e) {

            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function autoUpdate()
    {
        try {

            $timestamp = time();
            $getResponse = Http::withHeaders([
                'x-rs-id' => '7371449',
                'x-pass' => 'Pertamedika@123',
                'x-timestamp' => $timestamp,
                'Accept' => 'application/json',
            ])->get('https://sirs.kemkes.go.id/fo/index.php/Fasyankes');

            $getResult = json_decode($getResponse->body(), true);

            if (!isset($getResult['fasyankes'])) {

                return response()->json([
                    'success' => false,
                    'message' => 'Data tempat tidur tidak ditemukan'
                ], 400);
            }

            $success = 0;
            $failed = 0;

            foreach ($getResult['fasyankes'] as $tt) {

                $payload = [
                    'id_t_tt' => $tt['id_t_tt'],
                    'ruang' => $tt['ruang'],
                    'jumlah_ruang' => $tt['jumlah_ruang'],
                    'jumlah' => $tt['jumlah'],
                    'terpakai' => $tt['terpakai'],
                    'terpakai_suspek' => $tt['terpakai_suspek'],
                    'terpakai_konfirmasi' => $tt['terpakai_konfirmasi'],
                    'antrian' => $tt['antrian'],
                    'prepare' => $tt['prepare'],
                    'prepare_plan' => $tt['prepare_plan'],
                    'covid' => $tt['covid'],
                ];

                $updateResponse = Http::withHeaders([
                    'x-rs-id' => '7371449',
                    'x-pass' => 'Pertamedika@123',
                    'x-timestamp' => time(),
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/json',
                ])->send(
                        'PUT',
                        'https://sirs.kemkes.go.id/fo/index.php/Fasyankes',
                        [
                            'json' => $payload
                        ]
                    );

                if ($updateResponse->successful()) {

                    $success++;

                } else {

                    $failed++;
                }
            }

            return response()->json([
                'success' => true,
                'message' => 'Auto update selesai',
                'berhasil' => $success,
                'gagal' => $failed
            ]);

        } catch (\Exception $e) {

            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
