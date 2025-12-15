<html>
<!DOCTYPE html>

<html lang="id">

<head>
    <meta charset="UTF-8">
    <style>
        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 12px;
            color: #000;
            /* background-image: url("data:image/png;base64,{{ base64_encode(file_get_contents(public_path('assets/images/ihc/ihc_default.png'))) }}");
            background-size: 370px auto;
            background-repeat: no-repeat;
            background-position: center;
            background-attachment: fixed; */
        }

        .center {
            text-align: center;
        }

        .bold {
            font-weight: bold;
        }

        .underline {
            text-decoration: underline;
        }

        .mt-1 {
            margin-top: 5px;
        }

        .mt-2 {
            margin-top: 10px;
        }

        .mt-3 {
            margin-top: 15px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        td,
        th {
            padding: 2px;
            vertical-align: top;
        }

        .border td,
        .border th {
            border: 1px solid #000;
        }

        .no-border td {
            border: none;
        }

        .label {
            width: 180px;
        }

        .checkbox {
            display: inline-block;
            width: 8px;
            height: 8px;
            border: 1px solid #000;
            margin-right: 4px;
        }

        .checked {
            background: #000;
        }
    </style>
</head>

<body>

    <div class="center mt-3">
        <div class="bold underline" style="font-size:18px; letter-spacing:2px;">SURAT KETERANGAN</div>
        <div class="bold mt-1">Nomor : {{$spd->no_surat}} </div>
    </div>

    <p class="mt-3 bold">Dengan ini mengijinkan / menugaskan :</p>

    <table class="no-border">
        <tr>
            <td class="label">Nama Lengkap</td>
            <td>: {{$spd->nama_pegawai}} </td>
        </tr>
        <tr>
            <td class="label">Nomor Pekerja</td>
            <td>: {{$spd->nomor_pekerja}}</td>
        </tr>
        <tr>
            <td class="label">Pangkat / Golongan</td>
            <td>: - / -</td>
        </tr>
        <tr>
            <td class="label">Jabatan</td>
            <td>: -</td>
        </tr>
        <tr>
            <td class="label">Eselon</td>
            <td>: RS Otak & Jantung Pertamina Royal Biringkanaya</td>
        </tr>
        <tr>
            <td class="label">UNTUK MELAKSANAKAN</td>
            <td>:
                <span class="checkbox {{ $spd->pelaksanaan == 'PD-DN' ? 'checked' : '' }}"></span> PD-DN &nbsp;&nbsp;&nbsp;
                <span class="checkbox {{ $spd->pelaksanaan == 'PD-LN' ? 'checked' : '' }}"></span> PD-LN &nbsp;&nbsp;&nbsp;
                <span class="checkbox {{ $spd->pelaksanaan == 'SIJ' ? 'checked' : '' }}"></span> SIJ &nbsp;&nbsp;&nbsp;
                <span class="checkbox {{ $spd->pelaksanaan == 'Mutasi' ? 'checked' : '' }}"></span> Mutasi &nbsp;&nbsp;&nbsp;
                <span class="checkbox {{ $spd->pelaksanaan == 'Cuti' ? 'checked' : '' }}"></span> Cuti &nbsp;
            </td>
        </tr>
    </table>

    <table class="no-border mt-2">
        <tr>
            <td class="label">Dari / Asal</td>
            <td>: {{$spd->nama_kota1}}</td>
            <td class="label">Hak Cuti Tahun ke</td>
            <td>: {{$spd->hak_cuti}}</td>
        </tr>
        <tr>
            <td class="label">Tempat Tujuan</td>
            <td>: {{$spd->nama_kota2}}</td>
            <td class="label">Cuti Yang Lalu</td>
            <td>: {{$spd->hak_cuti}}</td>
        </tr>
        <tr>
            <td class="label">Terhitung Mulai Tanggal</td>
            <td>: {{ \Carbon\Carbon::parse($spd->tgl_awal)->translatedFormat('d F Y') }}</td>
            <td class="label">Panjar Cuti</td>
            <td>: {{$spd->panjar_cuti}}</td>
        </tr>
        <tr>
            <td class="label">Berangkat / Kembali</td>
            <td>: {{ \Carbon\Carbon::parse($spd->tgl_akhir)->translatedFormat('d F Y') }}</td>
            <td class="label">Bekerja Kembali</td>
            <td>: {{ \Carbon\Carbon::parse($spd->tgl_masuk)->translatedFormat('d F Y') }}</td>
        </tr>
        <tr>
            <td class="label">Berkendaraan</td>
            <td>: {{$spd->kendaraan}}</td>
            <td class="label">Due Date Cuti</td>
            <td>: {{$spd->jatuh_tempo}}</td>
        </tr>
        <tr>
            <td class="label">Biaya Ditanggung Oleh</td>
            <td colspan="3">: {{$spd->ditanggung}}</td>
        </tr>
    </table>

    <table class="border">
        <tr>
            <th colspan="6" align="left">
                KETERANGAN / KEPERLUAN : {{$spd->keterangan}}<br><br><br>
            </th>
        </tr>
        <tr>
            <th width="5%">No</th>
            <th width="30%">Nama Pengikut</th>
            <th width="15%">Nopek</th>
            <th width="20%">Jabatan</th>
            <th width="30%" colspan="2">Keterangan</th>
        </tr>
        <tr>
            <td align="center">1</td>
            <td></td>
            <td></td>
            <td></td>
            <td colspan="2"></td>
        </tr>
        <tr>
            <td align="center">2</td>
            <td></td>
            <td></td>
            <td></td>
            <td colspan="2"></td>
        </tr>
        <tr>
           <td align="center">3</td>
            <td></td>
            <td></td>
            <td></td>
            <td colspan="2"></td>
        </tr>
        TEST
        <tr>
            <td colspan="4">
                PANJAR / LUMPSUM PERJALANAN DINAS<br><br><br><br>
                Catatan : Batas akhir pertanggungjawaban panjar dinas 1 (satu) minggu setelah kepulangan
            </td>
            <td colspan="2" class="center">
                Menyetujui :<br>
                <b  style="font-size:11px;">RSOJ Pertamina Royal Biringkanaya</b><br>
                TEST<br><br>
                <img src="TEST" width="75"><br>
                TEST
            </td>
        </tr>
        <tr>
            <td colspan="2" rowspan="2" class="center"><br><br>KETERANGAN</td>
            <td colspan="4" class="center">TUJUAN</td>
        </tr>
        <tr>
            <td class="center">I</td>
            <td class="center">II</td>
            <td class="center">III</td>
            <td class="center">IV</td>
        </tr>
        <tr>
            <td colspan="2">Tanggal Tiba</td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
        <tr>
            <td colspan="2">Tanggal Kembali</td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
        <tr>
            <td colspan="2"><br>Tanda Tangan Pejabat yang Dikunjungi<br><br></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
    </table>

</body>

</html>

</html>