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
            padding: 4px 6px;
            line-height: 1.2;
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

        .footer {
            position: fixed;
            bottom: 20px;
            /* jarak dari bawah halaman */
            left: 40px;
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
            <td>{{ $rincian->nama_pegawai }} / {{ $rincian->jabatan_pegawai }}</td>
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
            @php
                $totalBiaya = 0;
            @endphp

            @foreach ($details as $index => $item)
                @php
                    $jumlah = $item->jumlah ?? 0;
                    $harga = $item->harga ?? 0;
                    $subtotal = $jumlah * $harga;
                    $totalBiaya += $subtotal;
                @endphp
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>{{ $item->nama_biaya }}</td>
                    <td class="text-center">{{ $item->jumlah ?? '-' }}</td>
                    <td class="text-right">
                        {{ number_format($item->harga, 0, ',', '.') }}
                    </td>
                    <td class="text-right">
                        {{ number_format($subtotal, 0, ',', '.') }}
                    </td>
                </tr>
            @endforeach
            <tr>
                <td colspan="4" class="text-right"><b>Total Biaya</b></td>
                <td class="text-right">
                    <b>{{ number_format($totalBiaya, 0, ',', '.') }}</b>
                </td>
            </tr>
            <tr>
                <td colspan="4" class="text-right"><b>Panjar yang Diterima</b></td>
                <td class="text-right"><b>{{ number_format($rincian->panjar, 0, ',', '.') }}</b></td>
            </tr>
            <tr>
                <td colspan="4" class="text-right"><b>Biaya yang Diterima</b></td>
                <td class="text-right"><b>{{ number_format($totalBiaya - $rincian->panjar, 0, ',', '.') }}</b></td>
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
                <b>{{ $rincian->jabatan_menyetujui }}</b><br><br><br><br><br>
                <b>{{ $rincian->nama_menyetujui }}</b>
            </td>
            <td width="40%">
                Yang Mengajukan,<br>
                <b>{{ $rincian->jabatan_mengajukan }}</b><br><br><br><br><br><br>
                <b>{{ $rincian->nama_mengajukan }}</b>
            </td>
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