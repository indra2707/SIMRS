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

        .title {
            position: fixed;
            right: 0;
            top: -25mm;
            color: #8ea4ca;
            font-size: 24px;
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

        .content {
            width: 100%;
        }

        .account-title {
            text-align: center;
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 25px;
        }

        .info-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        .info-table td {
            padding: 7px 5px;
            vertical-align: top;
        }

        .info-table .label {
            width: 180px;
            font-weight: bold;
        }

        .info-table .separator {
            width: 15px;
        }

        .section-title {
            font-size: 13px;
            font-weight: bold;
            margin-top: 20px;
            margin-bottom: 10px;
            padding-bottom: 5px;
            border-bottom: 1px solid #000;
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

        .signature {
            margin-top: 50px;
            text-align: left;
        }

        .signature-name {
            margin-top: 50px;
            font-weight: bold;
        }
    </style>
</head>

<body>

    @php
        $pathText = public_path('assets/images/ihc/logo_doc.png');
        $logoText = file_exists($pathText)
            ? base64_encode(file_get_contents($pathText))
            : null;

        $pathBg = public_path('assets/images/ihc/background.png');
        $bgBase64 = file_exists($pathBg)
            ? base64_encode(file_get_contents($pathBg))
            : null;
    @endphp

    {{-- Header --}}
    @if ($logoText)
        <img
            src="data:image/png;base64,{{ $logoText }}"
            class="logo-text">
    @endif

    <div class="title">
        ACCOUNT
    </div>

    {{-- Background --}}
    @if ($bgBase64)
        <img
            class="bg-fixed"
            src="data:image/png;base64,{{ $bgBase64 }}">
    @endif

    <div class="content">

        <div class="account-title">
            DATA ACCOUNT PEGAWAI
        </div>

        <table class="info-table">

            <tr>
                <td class="label">Nama Pegawai</td>
                <td class="separator">:</td>
                <td>
                    {{ $pegawai->nama_pekerja ?? '-' }}
                </td>
            </tr>

            <tr>
                <td class="label">NIK</td>
                <td class="separator">:</td>
                <td>
                    {{ $pegawai->nik ?? '-' }}
                </td>
            </tr>

            <tr>
                <td class="label">Nomor Pekerja</td>
                <td class="separator">:</td>
                <td>
                    {{ $pegawai->nomor_pekerja ?? '-' }}
                </td>
            </tr>

            <tr>
                <td class="label">Tanggal Lahir</td>
                <td class="separator">:</td>
                <td>
                    {{ !empty($pegawai->tanggal_lahir)
                        ? \Carbon\Carbon::parse($pegawai->tanggal_lahir)->format('d/m/Y')
                        : '-' }}
                </td>
            </tr>

            <tr>
                <td class="label">Email</td>
                <td class="separator">:</td>
                <td>
                    {{ $pegawai->email ?? '-' }}
                </td>
            </tr>

            <tr>
                <td class="label">Nomor HP</td>
                <td class="separator">:</td>
                <td>
                    {{ $pegawai->nomor_hp ?? '-' }}
                </td>
            </tr>

            <tr>
                <td class="label">Alamat Domisili</td>
                <td class="separator">:</td>
                <td>
                    {{ $pegawai->alamat_domisili ?? '-' }}
                </td>
            </tr>

            <tr>
                <td class="label">Rumah Sakit</td>
                <td class="separator">:</td>
                <td>
                    {{ $pegawai->nama_rumah_sakit ?? '-' }}
                </td>
            </tr>

            <tr>
                <td class="label">Unit</td>
                <td class="separator">:</td>
                <td>
                    {{ $pegawai->nama_unit ?? '-' }}
                </td>
            </tr>

            <tr>
                <td class="label">Jabatan</td>
                <td class="separator">:</td>
                <td>
                    {{ $pegawai->nama_jabatan ?? '-' }}
                </td>
            </tr>

            <tr>
                <td class="label">Fungsi</td>
                <td class="separator">:</td>
                <td>
                    {{ $pegawai->nama_fungsi ?? '-' }}
                </td>
            </tr>

            <tr>
                <td class="label">No. SK Struktur</td>
                <td class="separator">:</td>
                <td>
                    {{ $pegawai->no_sk_struktur ?? '-' }}
                </td>
            </tr>

            <tr>
                <td class="label">Bank</td>
                <td class="separator">:</td>
                <td>
                    {{ $pegawai->nama_bank ?? '-' }}
                </td>
            </tr>

        </table>

        <div class="section-title">
            KONTAK DARURAT
        </div>

        <table class="info-table">

            <tr>
                <td class="label">Nama Kontak Darurat</td>
                <td class="separator">:</td>
                <td>
                    {{ $pegawai->nama_kontak_darurat ?? '-' }}
                </td>
            </tr>

            <tr>
                <td class="label">Nomor Kontak Darurat</td>
                <td class="separator">:</td>
                <td>
                    {{ $pegawai->nomor_kontak_darurat ?? '-' }}
                </td>
            </tr>

            <tr>
                <td class="label">Hubungan</td>
                <td class="separator">:</td>
                <td>
                    {{ $pegawai->hubungan_kontak_darurat ?? '-' }}
                </td>
            </tr>

        </table>

        <div class="signature">

            <div>
                Makassar, {{ now()->format('d/m/Y') }}
            </div>

            <div class="signature-name">
                {{ $pegawai->nama_pekerja ?? '-' }}
            </div>

        </div>

    </div>

    {{-- Footer --}}
    <div class="footer">
        <b>RSOJ Pertamina Royal Biringkanaya</b><br>
        Jl. Pajjaiang Sudiang Raya
        Kecamatan Biringkanaya Kota Makassar
        Sulawesi Selatan
        <br>
        Call Center. (021) 150442
        &nbsp;|&nbsp;
        Telp. (0411) 4821000
        &nbsp;|&nbsp;
        Email: rsoj.prb@ihc.id
    </div>

</body>

</html>