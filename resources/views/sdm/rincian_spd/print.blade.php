<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <style>
        /* @page {
            margin: 100px;
        } */

        html,
        body {
            height: 100%;
            margin: 0px;
            padding: 50px;
        }

        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 12px;
            line-height: 1.5;
            background-image: url("data:image/png;base64,{{ base64_encode(file_get_contents(public_path('assets/images/ihc/bingkai_ihc.png'))) }}");
            background-size: 98% 100%;
            background-repeat: no-repeat;
            background-position: right 0px;
            background-attachment: fixed;
        }

        .title {
            text-align: center;
            font-weight: 10px;
            letter-spacing: 0px;
            margin-bottom: 30px;
        }

        .section {
            margin-bottom: 5px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        .table-info td {
            padding: 2px 4px;
            vertical-align: middle;
        }

        .table-biaya th,
        .table-biaya td {
            border: 1px solid #000;
            padding: 5px;
            height: 0px;
        }

        .table-biaya th {
            text-align: center;
            font-weight: bold;
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        .signature {
            margin-top: 40px;
        }

        .signature td {
            vertical-align: top;
        }
    </style>
</head>

<body>
    @php
        // $pathPertamedika = public_path('assets/images/ihc/pertamedika.png');
        // $logoPertamedika = base64_encode(file_get_contents($pathPertamedika));
        $pathText = public_path('assets/images/ihc/logo_doc.png');
        $logoText = base64_encode(file_get_contents($pathText));
    @endphp

    <img src="data:image/png;base64,{{ $logoText }}" style="width:180px;"><br><br>

    <div class="title">
        <h2>PANJAR PERJALANAN DINAS DALAM NEGERI </h2>
    </div>

    <div class="section">
        <table>
            <tr>
                <td width="15%">Kepada</td>
                <td width="2%">:</td>
                <td><b>Vice Director Finance</b></td>
            </tr>
        </table>
    </div>

    <div class="section">
        Yang bertanda tangan di bawah ini menerangkan bahwa :
    </div>

    <table class="table-info">
        <tr>
            <td width="20%">Nomor Pekerja</td>
            <td width="2%">:</td>
            <td> {{ $rincian->nomor_pekerja }} </td>
        </tr>
        <tr>
            <td>Nama / Jabatan</td>
            <td>:</td>
            <td>{{ $rincian->nama_pegawai }}</td>
        </tr>
        <tr>
            <td>Tanggal</td>
            <td>:</td>
            <td> {{ \Carbon\Carbon::parse($rincian->tgl_awal)->translatedFormat('d F Y') }}
                s.d
                {{ \Carbon\Carbon::parse($rincian->tgl_akhir)->translatedFormat('d F Y') }}
            </td>
        </tr>
        <tr>
            <td>Daerah Asal / Tujuan</td>
            <td>:</td>
            <td>{{ $rincian->nama_kota1 }} / {{ $rincian->nama_kota2 }}</td>
        </tr>
    </table>

    <div class="section">
        Mengajukan Deklarasi / Panjar Dinas Sebagai Berikut :
    </div>

    <table class="table-biaya">
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="45%">Jenis Biaya</th>
                <th width="10%">Preq</th>
                <th width="20%">Tarif PJL</th>
                <th width="20%">Jumlah</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td class="text-center">Test</td>
                <td>Test</td>
                <td class="text-center">Test</td>
                <td class="text-right">Test</td>
                <td class="text-right">Test</td>
            </tr>
            <tr>
                <td colspan="4" class="text-right"><b>TOTAL BIAYA</b></td>
                <td class="text-right"><b>Test</b></td>
            </tr>
            <tr>
                <td colspan="4" class="text-right"><b>PANJAR YANG DITERIMA</b></td>
                <td class="text-right">Test</td>
            </tr>
            <tr>
                <td colspan="4" class="text-right"><b>BIAYA YANG DITERIMA</b></td>
                <td class="text-right">Test</td>
            </tr>
        </tbody>
    </table>
    <div class="section">
        @if ($rincian->jenis === 'Panjar')
            Pertanggungjawaban paling lambat diserahkan ke SDM,
            2 minggu setelah pelaksanaan perjalanan dinas.
            Panjar diberikan maksimal 80%.
        @endif

    </div>

    <div class="section text-right">
        Makassar, {{ \Carbon\Carbon::parse($rincian->tanggal)->translatedFormat('d F Y') }}
    </div>

    <table class="signature">
        <tr>
            <td width="60%">
                Menyetujui,<br>
                RSOJ Pertamina Royal Biringkanaya<br>
                <b>Direktur</b><br><br><br><br><br>
                <b>{{ $rincian->nama_menyetujui }}</b>
            </td>
            <td width="40%">
                Yang Mengajukan,<br>
                <b>VD Human Capital And General Affair</b><br><br><br><br><br><br>
                <b>{{ $rincian->nama_mengajukan }}</b>
            </td>
        </tr>
    </table>

</body>

</html>