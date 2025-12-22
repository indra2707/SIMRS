<html>
<!DOCTYPE html>

<html lang="id">

<head>
    <meta charset="UTF-8">
    <style>
        html,
        body {
            height: 100%;
            margin: 0px;
            padding: 50px;
        }

        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 12px;
            color: #000;
            background-image: url("data:image/png;base64,{{ base64_encode(file_get_contents(public_path('assets/images/ihc/ihc_default.png'))) }}");
            background-size: cover;
            background-repeat: no-repeat;
            background-position: center center;
            background-attachment: fixed;
            /* margin: 0;
            padding: 0; */
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
            width: 12px;
            height: 12px;
            border: 1px solid #000;
            margin-right: 4px;
            vertical-align: middle;
        }

        .checked {
            background: #000;
        }

        .footer {
            position: fixed;
            bottom: 20px;
            /* jarak dari bawah halaman */
            left: 30px;
            right: 0;
            text-align: left;
            font-size: 10px;
            line-height: 1.4;
            color: #000;
        }
    </style>
</head>

<body>
    @php
        $pathPertamedika = public_path('assets/images/ihc/pertamedika.png');
        $logoPertamedika = base64_encode(file_get_contents($pathPertamedika));
        $pathText = public_path('assets/images/ihc/logo_doc.png');
        $logoText = base64_encode(file_get_contents($pathText));
    @endphp

    <table>
        <tr>
            <td>
                <img src="data:image/png;base64,{{ $logoPertamedika }}" style="width:210px; ">
            </td>
            <td align="right">
                <img src="data:image/png;base64,{{ $logoText }}" style="width:180px; ">
            </td>
        </tr>
    </table>

    <div class="body">
        <div class="center mt-3">
            <div class="bold underline" style="font-size:18px; letter-spacing:1px;">SURAT KETERANGAN</div>
            <div class="" style="margin-top:auto; font-size:13px;">Nomor : {{ $spd->no_surat }} </div>
        </div>

        <p class="mt-3 bold">Dengan ini mengijinkan / menugaskan :</p>

        <table class="no-border">
            <tr>
                <td class="label">Nama Lengkap</td>
                <td>: {{ $spd->nama_pegawai }} </td>
            </tr>
            <tr>
                <td class="label">Nomor Pekerja</td>
                <td>: {{ $spd->nomor_pekerja }}</td>
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
                    <span class="checkbox {{ $spd->pelaksanaan == 'PD-DN' ? 'checked' : '' }}"></span> PD-DN
                    &nbsp;&nbsp;&nbsp;
                    <span class="checkbox {{ $spd->pelaksanaan == 'PD-LN' ? 'checked' : '' }}"></span> PD-LN
                    &nbsp;&nbsp;&nbsp;
                    <span class="checkbox {{ $spd->pelaksanaan == 'SIJ' ? 'checked' : '' }}"></span> SIJ
                    &nbsp;&nbsp;&nbsp;
                    <span class="checkbox {{ $spd->pelaksanaan == 'Mutasi' ? 'checked' : '' }}"></span> Mutasi
                    &nbsp;&nbsp;&nbsp;
                    <span class="checkbox {{ $spd->pelaksanaan == 'Cuti' ? 'checked' : '' }}"></span> Cuti &nbsp;
                </td>
            </tr>
        </table>

        <table class="no-border mt-2">
            <tr>
                <td class="label">Dari / Asal</td>
                <td>: {{ $spd->nama_kota1 }}</td>
                <td class="label">Hak Cuti Tahun ke</td>
                <td>: {{ $spd->hak_cuti }}</td>
            </tr>
            <tr>
                <td class="label">Tempat Tujuan</td>
                <td>: {{ $spd->nama_kota2 }}</td>
                <td class="label">Cuti Yang Lalu</td>
                <td>: {{ $spd->hak_cuti }}</td>
            </tr>
            <tr>
                <td class="label">Terhitung Mulai Tanggal</td>
                <td>: {{ \Carbon\Carbon::parse($spd->tgl_awal)->translatedFormat('d F Y') }}</td>
                <td class="label">Panjar Cuti</td>
                <td>: {{ $spd->panjar_cuti }}</td>
            </tr>
            <tr>
                <td class="label">Berangkat / Kembali</td>
                <td>: {{ \Carbon\Carbon::parse($spd->tgl_akhir)->translatedFormat('d F Y') }}</td>
                <td class="label">Bekerja Kembali</td>
                <td>: {{ \Carbon\Carbon::parse($spd->tgl_masuk)->translatedFormat('d F Y') }}</td>
            </tr>
            <tr>
                <td class="label">Berkendaraan</td>
                <td>: {{ $spd->kendaraan }}</td>
                <td class="label">Due Date Cuti</td>
                <td>: {{ $spd->jatuh_tempo }}</td>
            </tr>
            <tr>
                <td class="label">Biaya Ditanggung Oleh</td>
                <td colspan="3">: {{ $spd->ditanggung }}</td>
            </tr>
        </table>

        <table class="border mt-3">
            <tr>
                <td colspan="6" align="left">
                    KETERANGAN / KEPERLUAN : {{ $spd->keterangan }}<br><br><br>
                </td>
            </tr>
            <tr>
                <th width="5%">No</th>
                <th width="30%">Nama Pengikut</th>
                <th width="15%">Nopek</th>
                <th width="20%">Jabatan</th>
                <th width="30%" colspan="2">Keterangan</th>
            </tr>
            @if ($pengikut && count($pengikut) > 0)
                @foreach ($pengikut as $index => $item)
                    <tr>
                        <td align="center">{{ $index + 1 }}</td>
                        <td>{{ $item->nama_pengikut }}</td>
                        <td>{{ $item->nopek }}</td>
                        <td>{{ $item->jabatan }}</td>
                        <td colspan="2"></td>
                    </tr>
                @endforeach

                @for ($i = count($pengikut); $i < 3; $i++)
                    <tr>
                        <td align="center">{{ $i + 1 }}</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td colspan="2"></td>
                    </tr>
                @endfor
            @else
                @for ($i = 1; $i <= 3; $i++)
                    <tr>
                        <td align="center">{{ $i }}</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td colspan="2"></td>
                    </tr>
                @endfor
            @endif
            <tr>
                <td colspan="4">
                    PANJAR / LUMPSUM PERJALANAN DINAS
                    <div style="margin-top:auto; font-size:10px;"><br><br><br><br><br><br><br><br><br>
                        Catatan : Batas akhir pertanggungjawaban panjar dinas 1 (satu) minggu setelah kepulangan
                    </div>
                </td>
                <td colspan="2">
                    Menyetujui :<br>
                    <b style="font-size:11px">RSOJ Pertamina Royal Biringkanaya</b><br>
                    Direktur<br><br><br><br><br><br>
                    <!-- <img src="TEST" width="75" ><br> -->
                    {{ $spd->nama_pimpinan }}
                </td>
            </tr>
            <tr>
                <td colspan="2" rowspan="2" class="center" class="center"
                    style="height:30px; vertical-align:middle; text-align:center;">KETERANGAN</td>
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

        <div class="footer">
            <b>RS Otak & Jantung Pertamina Royal Biringkanaya</b><br>
            Jl. Pajjaiyyang Sudiang Raya
            Kecamatan Biringkanaya Kota madya Ujung Pandang
            Sulawesi Selatan
            <br>
            Call Center. (021) 150442 &nbsp;|&nbsp; Telp. (0411) 4821000 &nbsp;|&nbsp; Email: rsoj.prb@ihc.id
        </div>

</body>

</html>

</html>