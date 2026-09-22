```html
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">

    <style>
        @page {
            margin: 30mm 15mm 22mm 15mm;
        }

        body {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
            font-size: 10pt;
            color: #222;
        }

        .logo-text {
            position: fixed;
            top: -22mm;
            left: 0;
            width: 190px;
            height: auto;
        }

        .title {
            position: fixed;
            right: 0;
            top: -22mm;
            color: #8ea4ca;
            font-size: 22px;
            font-weight: normal;
        }

        .bg-fixed {
            position: fixed;
            top: -30mm;
            left: -15mm;
            width: 210mm;
            height: 297mm;
            z-index: -1;
        }

        .content {
            width: 100%;
        }

        /* =========================
           PROFILE HEADER
        ========================= */

        .profile-header {
            width: 100%;
            border-bottom: 2px solid #8ea4ca;
            padding-bottom: 15px;
            margin-bottom: 18px;
        }

        .profile-table {
            width: 100%;
            border-collapse: collapse;
        }

        .profile-photo {
            width: 95px;
            vertical-align: top;
        }

        .photo {
            width: 85px;
            height: 105px;
            object-fit: cover;
            border: 1px solid #ccc;
        }

        .profile-info {
            vertical-align: middle;
            padding-left: 15px;
        }

        .profile-name {
            font-size: 21px;
            font-weight: bold;
            margin-bottom: 5px;
            color: #1f2937;
        }

        .profile-position {
            font-size: 12px;
            color: #6b7280;
            margin-bottom: 8px;
        }

        .profile-contact {
            font-size: 9pt;
            color: #555;
            line-height: 1.6;
        }

        /* =========================
           SECTION
        ========================= */

        .section-title {
            font-size: 12px;
            font-weight: bold;
            color: #334155;
            text-transform: uppercase;
            border-bottom: 1px solid #8ea4ca;
            padding-bottom: 5px;
            margin-top: 15px;
            margin-bottom: 9px;
        }

        /* =========================
           TWO COLUMN
        ========================= */

        .main-table {
            width: 100%;
            border-collapse: collapse;
        }

        .left-column {
            width: 34%;
            vertical-align: top;
            padding-right: 15px;
            border-right: 1px solid #ddd;
        }

        .right-column {
            width: 66%;
            vertical-align: top;
            padding-left: 18px;
        }

        /* =========================
           PERSONAL INFORMATION
        ========================= */

        .info-table {
            width: 100%;
            border-collapse: collapse;
        }

        .info-table td {
            padding: 4px 0;
            vertical-align: top;
        }

        .info-label {
            width: 42%;
            color: #666;
            font-size: 9pt;
        }

        .info-value {
            font-weight: bold;
            font-size: 9pt;
        }

        /* =========================
           EXPERIENCE
        ========================= */

        .experience {
            margin-bottom: 14px;
        }

        .experience-title {
            font-size: 11px;
            font-weight: bold;
            color: #1f2937;
        }

        .experience-company {
            font-size: 9pt;
            color: #64748b;
            margin-top: 2px;
        }

        .experience-date {
            font-size: 8pt;
            color: #8ea4ca;
            margin-top: 2px;
        }

        .experience-description {
            margin-top: 5px;
            font-size: 9pt;
            line-height: 1.5;
        }

        /* =========================
           EDUCATION
        ========================= */

        .education-item {
            margin-bottom: 12px;
        }

        .education-degree {
            font-weight: bold;
            font-size: 10px;
        }

        .education-school {
            font-size: 9pt;
            color: #555;
        }

        .education-year {
            font-size: 8pt;
            color: #8ea4ca;
        }

        /* =========================
           SKILLS
        ========================= */

        .skill {
            margin-bottom: 8px;
        }

        .skill-name {
            font-size: 9pt;
            margin-bottom: 3px;
        }

        .skill-bar {
            width: 100%;
            height: 6px;
            background: #e5e7eb;
        }

        .skill-progress {
            height: 6px;
            background: #8ea4ca;
        }

        /* =========================
           CONTACT
        ========================= */

        .contact-item {
            margin-bottom: 8px;
            font-size: 9pt;
            line-height: 1.4;
        }

        .contact-label {
            font-weight: bold;
            color: #555;
        }

        /* =========================
           FOOTER
        ========================= */

        .footer {
            position: fixed;
            bottom: -55px;
            left: 0;
            right: 0;
            text-align: left;
            font-size: 8px;
            line-height: 1.3;
            color: #8ea4ca;
        }

        .signature {
            margin-top: 30px;
            text-align: right;
        }

        .signature-name {
            margin-top: 45px;
            font-weight: bold;
        }
    </style>
</head>

<body>

    @php

        // LOGO

        $pathText = public_path('assets/images/ihc/logo_doc.png');

        $logoText = file_exists($pathText)
            ? base64_encode(file_get_contents($pathText))
            : null;


        // BACKGROUND

        $pathBg = public_path('assets/images/ihc/background.png');

        $bgBase64 = file_exists($pathBg)
            ? base64_encode(file_get_contents($pathBg))
            : null;


        // FOTO PEGAWAI
        $foto = null;
        $fotoMime = null;

        if (!empty($pegawai->foto)) {
            $pathFoto = public_path(
                'uploads/images/foto-pegawai/' . $pegawai->foto
            );

            if (file_exists($pathFoto)) {
                $foto = base64_encode(
                    file_get_contents($pathFoto)
                );
                $fotoMime = mime_content_type($pathFoto);
            }
        }
    @endphp


    {{-- HEADER --}}
    @if ($logoText)
        <img src="data:image/png;base64,{{ $logoText }}" class="logo-text">
    @endif

    <div class="title">
        CURRICULUM VITAE
    </div>


    {{-- BACKGROUND --}}
    @if ($bgBase64)
        <img class="bg-fixed" src="data:image/png;base64,{{ $bgBase64 }}">
    @endif


    <div class="content">

        <!-- PROFILE -->
        <div class="profile-header">
            <table class="profile-table">
                <tr>
                    <td class="profile-photo">
                        @if ($foto)
                            <img src="data:image/jpeg;base64,{{ $foto }}" class="photo">
                        @else
                            <div style="
                                                                                    width:85px;
                                                                                    height:105px;
                                                                                    border:1px solid #ccc;
                                                                                    text-align:center;
                                                                                    padding-top:35px;
                                                                                    box-sizing:border-box;
                                                                                    color:#999;
                                                                                ">
                                FOTO
                            </div>
                        @endif
                    </td>


                    <td class="profile-info">
                        <div class="profile-name">
                            {{ $pegawai->nama_pekerja ?? '-' }}
                        </div>

                        <div class="profile-position">
                            {{ $pegawai->nama_jabatan ?? '-' }}
                            @if (!empty($pegawai->nama_fungsi))
                                &nbsp; | &nbsp;
                                {{ $pegawai->nama_fungsi }}
                            @endif
                        </div>


                        <div class="profile-contact">
                            {{ $pegawai->email ?? '-' }}
                            &nbsp; | &nbsp;
                            {{ $pegawai->nomor_hp ?? '-' }}
                            <br>
                            {{ $pegawai->alamat_domisili ?? '-' }}
                        </div>
                    </td>
                </tr>
            </table>
        </div>


        <!-- MAIN CONTENT -->
        <table class="main-table">

            <tr>
                <!-- LEFT COLUMN -->
                <td class="left-column">
                    {{-- PERSONAL DATA --}}
                    <div class="section-title">
                        Data Pribadi
                    </div>

                    <table class="info-table">
                        <tr>
                            <td class="info-label">NIK</td>
                            <td class="info-value">{{ $pegawai->nik ?? '-' }}</td>
                        </tr>

                        <tr>
                            <td class="info-label">No. Pekerja </td>
                            <td class="info-value">{{ $pegawai->nomor_pekerja ?? '-' }} </td>
                        </tr>

                        <tr>
                            <td class="info-label">Tgl. Lahir</td>
                            <td class="info-value">
                                {{ !empty($pegawai->tanggal_lahir) ? \Carbon\Carbon::parse($pegawai->tanggal_lahir)->format('d/m/Y') : '-' }}
                            </td>
                        </tr>

                        <tr>
                            <td class="info-label">Jenis Kelamin</td>
                            <td class="info-value">{{ $pegawai->jenis_kelamin ?? '-' }}</td>
                        </tr>

                        <tr>
                            <td class="info-label">Status</td>
                            <td class="info-value">{{ $pegawai->status_pernikahan ?? '-' }}</td>
                        </tr>
                    </table>


                    {{-- ORGANIZATION --}}
                    <div class="section-title">
                        Organisasi
                    </div>

                    <table class="info-table">
                        <tr>
                            <td class="info-label"> Rumah Sakit</td>
                            <td class="info-value">{{ $pegawai->nama_rumah_sakit ?? '-' }}
                            </td>
                        </tr>

                        <tr>
                            <td class="info-label"> Unit</td>
                            <td class="info-value">{{ $pegawai->nama_unit ?? '-' }}</td>
                        </tr>

                        <tr>
                            <td class="info-label"> Jabatan</td>
                            <td class="info-value"> {{ $pegawai->nama_jabatan ?? '-' }} </td>
                        </tr>

                        <tr>
                            <td class="info-label"> Fungsi </td>
                            <td class="info-value"> {{ $pegawai->nama_fungsi ?? '-' }} </td>
                        </tr>

                        <tr>
                            <td class="info-label"> No. SK</td>
                            <td class="info-value">{{ $pegawai->no_sk_struktur ?? '-' }}</td>
                        </tr>
                    </table>


                    {{-- CONTACT DARURAT --}}
                    <div class="section-title">
                        Kontak Darurat
                    </div>

                    <table class="info-table">
                        <tr>
                            <td class="info-label"> Nama </td>
                            <td class="info-value"> {{ $pegawai->nama_kontak_darurat ?? '-' }} </td>
                        </tr>

                        <tr>
                            <td class="info-label"> Nomor </td>
                            <td class="info-value"> {{ $pegawai->nomor_kontak_darurat ?? '-' }}</td>
                        </tr>

                        <tr>
                            <td class="info-label">Hubungan</td>
                            <td class="info-value"> {{ $pegawai->hubungan_kontak_darurat ?? '-' }}</td>
                        </tr>
                    </table>
                </td>


                <!-- RIGHT COLUMN -->
                <td class="right-column">
                    {{-- EDUCATION --}}
                    @if (!empty($ijazah) && $ijazah->count() > 0)
                        @foreach ($ijazah as $item)
                            <div class="section-title">
                                Pendidikan
                            </div>

                            <div class="education-item">
                                {{-- Institusi --}}
                                <div class="education-school">{{ $item->institusi ?? '-' }} </div>
                                {{-- Pendidikan & Program Studi --}}
                                <div class="education-degree">
                                    {{ $item->pendidikan ?? '-' }} @if (!empty($item->prodi)) <span> - {{ $item->prodi }}
                                    </span> @endif
                                </div>
                                {{-- Tahun Lulus --}}
                                <div class="education-year">
                                    Tahun Lulus :
                                    @if (!empty($item->tahun_lulus)){{ \Carbon\Carbon::parse($item->tahun_lulus)->format('Y') }}
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    @endif


                    {{-- Kontrak --}}
                    @if (!empty($kontrak) && $kontrak->count() > 0)
                        @foreach ($kontrak as $item)
                        
                            <div class="section-title">
                                Pengalaman Kerja
                            </div>

                            <div class="education-item">
                                {{-- Nomor Kontrak --}}
                                <div class="education-school">RSOJ Pertamina Royal Biringkanaya </div>
                                {{-- Nama Status --}}
                                <div class="education-degree">{{ $item->nomor_kontrak ?? '-' }} - {{ $item->status ?? '-' }}
                                </div>
                                {{-- Tahun --}}
                                <div class="education-year">
                                    @if (!empty($item->tanggal_mulai)){{ \Carbon\Carbon::parse($item->tanggal_mulai)->format('d/m/Y') }}
                                    s.d @endif
                                    @if (!empty($item->tanggal_berakhir)){{ \Carbon\Carbon::parse($item->tanggal_berakhir)->format('d/m/Y') }}
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    @endif

                    {{-- SK Jabatan --}}
                    @if (!empty($skjabatan) && $skjabatan->count() > 0)
                        @foreach ($skjabatan as $item)
                            <div class="section-title">
                                Jabatan
                            </div>

                            <div class="education-item">
                                {{-- Nomor SK --}}
                                <div class="education-school">RSOJ Pertamina Royal Biringkanaya </div>
                                {{-- Nama Jabatan --}}
                                <div class="education-degree">{{ $item->nomor_sk ?? '-' }} - {{ $item->nama_jabatan ?? '-' }}
                                </div>
                                {{-- Tahun Lulus --}}
                                <div class="education-year">
                                    @if (!empty($item->tanggal_mulai)){{ \Carbon\Carbon::parse($item->tanggal_mulai)->format('d/m/Y') }}
                                    s.d @endif
                                    @if (!empty($item->tanggal_berakhir)){{ \Carbon\Carbon::parse($item->tanggal_berakhir)->format('d/m/Y') }}
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    @endif


                     {{-- STR dan SIP --}}
                    @if (!empty($str) && $str->count() > 0)
                        @foreach ($str as $item)
                            <div class="section-title">
                                STR dan SIP
                            </div>

                            <div class="education-item">
                                {{-- Nomor SK --}}
                                <div class="education-school">{{ $item->nomor ?? '-' }} </div>
                                {{-- Nama Jabatan --}}
                                <div class="education-degree">{{ $item->jenis ?? '-' }}
                                </div>
                                {{-- Tahun Lulus --}}
                                <div class="education-year">
                                    @if (!empty($item->tanggal_mulai)){{ \Carbon\Carbon::parse($item->tanggal_mulai)->format('d/m/Y') }}
                                    s.d @endif
                                    @if (!empty($item->tanggal_berakhir)){{ \Carbon\Carbon::parse($item->tanggal_berakhir)->format('d/m/Y') }}
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    @endif

                    {{-- SPK dan RKK --}}
                    @if (!empty($spk) && $spk->count() > 0)
                        @foreach ($spk as $item)
                            <div class="section-title">
                               SPK dan RKK
                            </div>

                            <div class="education-item">
                                {{-- Nomor SK --}}
                                <div class="education-school">{{ $item->nomor ?? '-' }} </div>
                                {{-- Nama Jabatan --}}
                                <!-- <div class="education-degree">{{ $item->jenis ?? '-' }} -->
                                </div>
                                {{-- Tahun Lulus --}}
                                <div class="education-year">
                                    @if (!empty($item->tanggal_mulai)){{ \Carbon\Carbon::parse($item->tanggal_mulai)->format('d/m/Y') }}
                                    s.d @endif
                                    @if (!empty($item->tanggal_berakhir)){{ \Carbon\Carbon::parse($item->tanggal_berakhir)->format('d/m/Y') }}
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    @endif

                    {{-- Sertificat --}}
                     @if (!empty($sertifikat) && $sertifikat->count() > 0)
                        @foreach ($sertifikat as $item)
                   
                            <div class="section-title">
                                Sertifikat
                            </div>
                    
                            <div class="education-item">
                                {{-- Nomor SK --}}
                                <div class="education-school">{{ $item->nama ?? '-' }} </div>
                                {{-- Nama Jabatan --}}
                                <div class="education-degree">{{ $item->jenis ?? '-' }} - {{ $item->penyelenggara ?? '-' }} </div>
                                {{-- Tahun  --}}
                                <div class="education-year">{{ $item->tahun ?? '-' }} </div>
                            </div>
                        @endforeach
                    @endif

                </td>
            </tr>
        </table>


        <!-- SIGNATURE -->
        <!-- <div class="signature">
            Makassar,
            {{ now()->format('d/m/Y') }}

            <div class="signature-name">
                {{ $pegawai->nama_pekerja ?? '-' }}
            </div>
        </div> -->

    </div>


    {{-- FOOTER --}}

    <div class="footer">

        <b>RSOJ Pertamina Royal Biringkanaya</b><br>

        Jl. Pajjaiang Sudiang Raya,
        Kecamatan Biringkanaya Kota Makassar,
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