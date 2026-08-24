<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <style>
        @page {
            margin: 35mm 20mm 25mm 20mm;
        }

        body {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
            font-size: 11pt;
            color: #000000;
        }

        .logo-text {
            position: fixed;
            top: -25mm;
            left: 0;
            width: 230px;
            height: auto;
        }

        .memo-title {
            position: fixed;
            left: 68%;
            top: -25mm;
            color: #8ea4ca;
            font-family: Arial, sans-serif;
            font-size: 28px;
            font-weight: normal;
        }

        .bg-fixed {
            position: fixed;
            top: -35mm;
            left: -15mm;
            width: 208mm;
            height: 297mm;
            z-index: -1;
        }

        .ttd-block {
            text-align: right;
            margin-top: 20px;
            page-break-inside: avoid;
            break-inside: avoid;
        }

        .tanggal {
            margin-bottom: 10px;
        }

        table.info-table {
            border-collapse: collapse;
            margin-bottom: 10px;
        }

        .dengan-hormat {
            margin: 10px 0;
        }

        .lampiran-title {
            font-weight: bold;
            margin-bottom: 8px;
        }

        .lampiran-img {
            display: block;
            margin: 8px auto;
        }

        .lampiran-page {
            page-break-before: always;
            break-before: page;
            page-break-inside: avoid;
        }

        .surat {
            padding-left: 70px;
            padding-right: 70px;
            box-sizing: border-box;
        }

        .isi-surat {
            width: 100%;
            max-width: 100%;
            margin: 0 !important;
            padding: 0 !important;
            box-sizing: border-box;
            text-align: justify;
        }

        .isi-surat p {
            width: 100%;
            max-width: 100%;
            margin: 0 0 15px 0;
            padding: 0;
        }

        .isi-surat figure.table {
            display: block !important;
            width: 100% !important;
            max-width: 100% !important;
            margin: 10px 0 15px 0 !important;
            padding: 0 !important;
            position: static !important;
            left: auto !important;
            right: auto !important;
            box-sizing: border-box !important;
        }

        .isi-surat figure.table>table {
            display: table !important;
            width: 100% !important;
            max-width: 100% !important;
            margin: 0 !important;
            padding: 0 !important;
            border-collapse: collapse !important;
            border-spacing: 0 !important;
            table-layout: fixed !important;
            position: static !important;
            box-sizing: border-box !important;
        }

        .isi-surat figure.table>table td {
            border: 1px solid #000;
            padding: 3px;
            vertical-align: top;
            box-sizing: border-box;
            word-wrap: break-word;
            overflow-wrap: break-word;
        }

        .isi-surat figure.table>table th {
            border: 1px solid #000;
            padding: 5px 7px;
            vertical-align: top;
            box-sizing: border-box;
            word-wrap: break-word;
            overflow-wrap: break-word;
            width: 100% !important;
            table-layout: auto !important;
            border-collapse: collapse !important;
        }

        .isi-surat figure.table>table th {
            font-weight: bold;
            text-align: left;
        }

        .isi-surat figure.table>table {
            width: 100% !important;
            table-layout: auto !important;
            border-collapse: collapse !important;
        }

        .isi-surat figure.table>table {
            margin-left: 0 !important;
            margin-right: 0 !important;
        }

        .footer {
            position: fixed;
            bottom: -70px;
            left: 0;
            right: 0;
            text-align: left;
            font-size: 10px;
            line-height: 1.2;
            color: #8ea4ca;
        }

        .qr-wrapper {
            position: relative;
            width: 100px;
            height: 85px;
            /* display: inline-block; */
        }

        .qr-code {
            width: 100px;
            height: 100px;
            display: block;
        }

        .qr-logo {
            position: absolute;
            width: 15px;
            height: 15px;
            left: 48%;
            top: 35%;
            transform: translate(-50%, -50%);
            background: #fff;
            padding: 3px;
            box-sizing: border-box;
        }
    </style>
</head>

<body>


    @php
        $pathIhc = public_path('assets/images/ihc/LogoRCode.png');
        $logoIhc = base64_encode(file_get_contents($pathIhc));
        $pathText = public_path('assets/images/ihc/logo_doc.png');
        $logoText = base64_encode(file_get_contents($pathText));
    @endphp

    <div class="header">
        <img src="data:image/png;base64,{{ $logoText }}" class="logo-text">
        <div class="memo-title">MEMORANDUM</div>
    </div>

    <img class="bg-fixed" src="data:image/png;base64,{{ $bgBase64 }}">
    <div class="tanggal">Makassar, {{ $tanggal }} </div>

    <table class="info-table">
        <tr>
            <td class="label">Nomor </td>
            <td>: {{ $surat->no_surat }} </td><br><br>
        </tr>
        <tr>
            <td class="label">Kepada</td>
            <td>: {{ $surat->nama_aproval }} </td>
        </tr>
        <tr>
            <td class="label">Dari</td>
            <td>: {{ $surat->nama_unit }} </td>
        </tr><br>
        <tr>
            <td class="label">Lampiran </td>
            <td class="perihal-value">: {{ $surat->jumlah_lampiran }} </td>
        </tr>
        <tr>
            <td class="label">Perihal </td>
            <td class="perihal-value">: {{ $surat->perihal }}</td>
        </tr>
    </table>


    <div class="isi-surat">
        {!! $surat->isi_surat !!}
    </div><br>

    <div>RSOJ Pertamina Royal Biringkanaya</div>
    <div>{{ $surat->nama_unit }}</div><br><br>

    <div class="qr-wrapper">
        <img src="{{ $qrCode }}" class="qr-code">
        <img src="data:image/png;base64,{{ $logoIhc }}" class="qr-logo">
    </div>

    <div><b>{{ $surat->nama_pegawai }}</b></div>

    @foreach ($lampiranList as $i => $lampiran)
        <div class="lampiran-page">

            <div class="lampiran-title">
                Lampiran {{ count($lampiranList) > 1 ? ($i + 1) . ' dari ' . count($lampiranList) : '' }}:
            </div>

            <img class="lampiran-img" src="data:{{ $lampiran['mime'] }};base64,{{ $lampiran['base64'] }}"
                style="width: 650px; height: auto;">

        </div>
    @endforeach


    <div class="footer">
        <b>RSOJ Pertamina Royal Biringkanaya</b><br>
        Jl. Pajjaiyyang Sudiang Raya
        Kecamatan Biringkanaya Kota Makassar
        Sulawesi Selatan
        <br>
        Call Center. (021) 150442 &nbsp;|&nbsp; Telp. (0411) 4821000 &nbsp;|&nbsp; Email: rsoj.prb@ihc.id
    </div>
</body>

</html>