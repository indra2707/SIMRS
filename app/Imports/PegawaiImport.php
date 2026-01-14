<?php

namespace App\Imports;

use App\Models\Sdm\Pegawai;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Illuminate\Support\Facades\Log;

class PegawaiImport implements
    ToModel,
    WithHeadingRow,
    SkipsEmptyRows,
    WithChunkReading,
    WithBatchInserts
{
    public function model(array $row)
    {
        try {
            // Skip jika nama_pekerja kosong
            if (empty($row['nama_pekerja'])) {
                Log::warning('Skip baris: nama_pekerja kosong', ['row' => $row]);
                return null;
            }

            // Konversi NIK dari scientific notation jika perlu
            $nik = $this->convertScientificNotation($row['nik'] ?? null);

            return new Pegawai([
                // SKIP kolom 'id' - auto increment
                'anak_perusahaan'                  => $row['anak_perusahaan'] ?? null,
                'id_sk_struktur'                   => $row['id_sk_struktur'] ?? null,
                'id_jabatan'                       => $row['id_jabatan'] ?? null,
                'penempatan'                       => $row['penempatan'] ?? null,
                'lokasi_kerja'                     => $row['lokasi_kerja'] ?? null,
                'status_kepegawaian'               => $row['status_kepegawaian'] ?? null,
                'nomor_pekerja'                    => $row['nomor_pekerja'] ?? null,
                'nama_pekerja'                     => $row['nama_pekerja'],
                'jenis_kelamin'                    => $row['jenis_kelamin'] ?? null,
                'agama'                            => $row['agama'] ?? null,
                'nik'                              => $nik,
                'status_pernikahan'                => $row['status_pernikahan'] ?? null,
                'golongan_darah'                   => $row['golongan_darah'] ?? null,
                'disabilitas'                      => $row['disabilitas'] ?? null,
                'tanggal_lahir'                    => $this->convertDate($row['tanggal_lahir'] ?? null),
                'golongan_upah'                    => $row['golongan_upah'] ?? null,
                'tmt_status_kepegawaian'           => $this->convertDate($row['tmt_status_kepegawaian'] ?? null),
                'tmt_pwtt'                         => $this->convertDate($row['tmt_pwtt'] ?? null),
                'tmt_pwt'                          => $this->convertDate($row['tmt_pwt'] ?? null),
                'masa_kerja'                       => $row['masa_kerja'] ?? null,
                'fungsi' => !empty($row['fungsi']) ? $row['fungsi'] : 'UMUM',
                'id_sub_fungsi'                    => $row['id_sub_fungsi'] ?? null,
                'tmt_jabatan'                      => $this->convertDate($row['tmt_jabatan'] ?? null),
                'tmt_golongan_upah'                => $this->convertDate($row['tmt_golongan_upah'] ?? null),
                'penyetaraan_jabatan_ap'           => $row['penyetaraan_jabatan_ap'] ?? null,
                'penyetaraan_golongan_upah_ap'     => $row['penyetaraan_golongan_upah_ap'] ?? null,
                'id_bank'                          => $row['id_bank'] ?? null,
                'nomor_rekening'                   => $this->convertScientificNotation($row['nomor_rekening'] ?? null),
                'nama_rekening'                    => $row['nama_rekening'] ?? null,
                'nomor_bpjstk'                     => $this->convertScientificNotation($row['nomor_bpjstk'] ?? null),
                'nomor_bpjskesehatan'              => $this->convertScientificNotation($row['nomor_bpjskesehatan'] ?? null),
                'nomor_npwp'                       => $this->convertScientificNotation($row['nomor_npwp'] ?? null),
                'nomor_hp'                         => $this->convertScientificNotation($row['nomor_hp'] ?? null),
                'nomor_kontak_darurat'             => $this->convertScientificNotation($row['nomor_kontak_darurat'] ?? null),
                'nama_kontak_darurat'              => $row['nama_kontak_darurat'] ?? null,
                'hubungan_kontak_darurat'          => $row['hubungan_kontak_darurat'] ?? null,
                'email'                            => $row['email'] ?? null,
                'email_dinas'                      => $row['email_dinas'] ?? null,
                'alamat_ktp'                       => $row['alamat_ktp'] ?? null,
                'alamat_npwp'                      => $row['alamat_npwp'] ?? null,
                'alamat_domisili'                  => $row['alamat_domisili'] ?? null,
                'nomor_str'                        => $row['nomor_str'] ?? null,
                'str_seumur_hidup'                 => $row['str_seumur_hidup'] ?? null,
                'masa_berlaku_str'                 => $this->convertDate($row['masa_berlaku_str'] ?? null),
                'nomor_sip'                        => $row['nomor_sip'] ?? null,
                'masa_berlaku_sip'                 => $this->convertDate($row['masa_berlaku_sip'] ?? null),
                'asuransi_profesi'                 => $row['asuransi_profesi'] ?? null,
                'nomor_polis'                      => $row['nomor_polis'] ?? null,
                'masa_berlaku_asuransi'            => $this->convertDate($row['masa_berlaku_asuransi'] ?? null),
                'pend_diploma'                     => $row['pend_diploma'] ?? null,
                'pend_s1'                          => $row['pend_s1'] ?? null,
                'pend_s2'                          => $row['pend_s2'] ?? null,
                'pend_s3'                          => $row['pend_s3'] ?? null,
                'kampus_terakhir'                  => $row['kampus_terakhir'] ?? null,
                'jenjang_pendidikan_terakhir'      => $row['jenjang_pendidikan_terakhir'] ?? null,
                'keterangan'                       => $row['keterangan'] ?? null,
                'tanggal_akhir_kontrak'            => $this->convertDate($row['tanggal_akhir_kontrak'] ?? null),

                // Sistem fields - jangan ambil dari CSV
                'created_by'                       => auth()->user()->username ?? 'import',
                'created_at'                       => now(),
            ]);
        } catch (\Exception $e) {
            Log::error('Import Pegawai Error: ' . $e->getMessage(), [
                'row' => $row,
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);
            return null;
        }
    }

    /**
     * Konversi scientific notation (3,17401E+15) ke string angka biasa
     */
    private function convertScientificNotation($value)
    {
        if (empty($value)) {
            return null;
        }

        // Jika sudah string dan bukan scientific notation
        if (is_string($value) && strpos($value, 'E') === false && strpos($value, 'e') === false) {
            return str_replace([' ', ',', '.'], '', $value);
        }

        // Konversi scientific notation ke string
        if (is_numeric($value) || strpos($value, 'E') !== false || strpos($value, 'e') !== false) {
            // Replace koma dengan titik untuk konversi float
            $cleaned = str_replace(',', '.', $value);
            $number = floatval($cleaned);

            // Format tanpa desimal dan hilangkan koma
            return number_format($number, 0, '', '');
        }

        return str_replace([' ', ','], '', $value);
    }

    /**
     * Konversi berbagai format tanggal ke Y-m-d
     */
    private function convertDate($date)
    {
        if (empty($date) || $date === '-' || $date === '0') {
            return null;
        }

        try {
            // Jika numeric (Excel date format)
            if (is_numeric($date)) {
                $excelDate = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($date);
                return $excelDate->format('Y-m-d');
            }

            // Jika string date
            if (is_string($date)) {
                // Coba berbagai format
                $formats = [
                    'd/m/Y',
                    'd-m-Y',
                    'Y-m-d',
                    'd M Y',
                    'd/m/y',
                    'm/d/Y',
                ];

                foreach ($formats as $format) {
                    $dateObj = \DateTime::createFromFormat($format, $date);
                    if ($dateObj !== false) {
                        return $dateObj->format('Y-m-d');
                    }
                }

                // Last resort - gunakan strtotime
                $timestamp = strtotime($date);
                if ($timestamp !== false) {
                    return date('Y-m-d', $timestamp);
                }
            }

            return null;
        } catch (\Exception $e) {
            Log::warning('Date conversion error: ' . $e->getMessage(), ['date' => $date]);
            return null;
        }
    }

    /**
     * Proses data per chunk untuk performa
     */
    public function chunkSize(): int
    {
        return 100;
    }

    /**
     * Insert batch untuk performa
     */
    public function batchSize(): int
    {
        return 100;
    }

    /**
     * Header di baris pertama
     */
    public function headingRow(): int
    {
        return 1;
    }
}
