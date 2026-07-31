<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Console\Scheduling\Schedule;

class AutoUpdateSirsTT extends Command
{
    protected $signature = 'sirs:auto-update-tt';

    protected $description = 'Auto update tempat tidur ke SIRS';

    public function handle()
    {
        try {

            $timestamp = time();

            /*
            |--------------------------------------------------------------------------
            | AMBIL DATA TEMPAT TIDUR DARI API SIRS
            |--------------------------------------------------------------------------
            */

            $response = Http::withHeaders([
                'x-rs-id' => '7371449',
                'x-pass' => 'Pertamedika@123',
                'x-timestamp' => $timestamp,
                'Accept' => 'application/json',
            ])->get(
                    'https://sirs.kemkes.go.id/fo/index.php/Fasyankes'
                );

            $result = json_decode($response->body(), true);

            // Debug response
            // dd($result);

            if (!isset($result['fasyankes'])) {

                $this->error('Data tempat tidur tidak ditemukan');

                return;
            }

            /*
            |--------------------------------------------------------------------------
            | UPDATE DATA YANG SUDAH ADA
            |--------------------------------------------------------------------------
            */

            foreach ($result['fasyankes'] as $row) {

                $payload = [
                    'id_t_tt' => $row['id_t_tt'],
                    'ruang' => $row['ruang'],
                    'jumlah_ruang' => $row['jumlah_ruang'],
                    'jumlah' => $row['jumlah'],
                    'terpakai' => $row['terpakai'],
                    'terpakai_suspek' => $row['terpakai_suspek'],
                    'terpakai_konfirmasi' => $row['terpakai_konfirmasi'],
                    'antrian' => $row['antrian'],
                    'prepare' => $row['prepare'],
                    'prepare_plan' => $row['prepare_plan'],
                    'covid' => $row['covid'],
                ];

                /*
                |--------------------------------------------------------------------------
                | KIRIM UPDATE
                |--------------------------------------------------------------------------
                */

                $update = Http::withHeaders([
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

                $updateResult = json_decode($update->body(), true);

                if ($update->successful()) {

                    $this->info(
                        'Berhasil update ID : ' .
                        $row['id_t_tt']
                    );

                } else {

                    $this->error(
                        'Gagal update ID : ' .
                        $row['id_t_tt'] .
                        ' | ' .
                        json_encode($updateResult)
                    );
                }
            }

            $this->info('Selesai update semua data.');

        } catch (\Exception $e) {

            $this->error($e->getMessage());
        }
    }

    protected function schedule(Schedule $schedule)
{
    $schedule->command('sirs:auto-update-tt')
        ->everySixHours();
}
}


