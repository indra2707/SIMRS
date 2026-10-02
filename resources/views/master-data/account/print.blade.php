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

        .main-content {
            width: 100%;
            margin-top: 10px;
        }

        .content-column {
            width: 100%;
        }

        .section-block {
            width: 100%;
            margin-bottom: 14px;
            page-break-inside: avoid;
        }

        .section-title {
            font-size: 12px;
            font-weight: bold;
            color: #334155;
            text-transform: uppercase;
            border-bottom: 1px solid #8ea4ca;
            padding-bottom: 5px;
            margin-top: 15px;
            margin-bottom: 9px;
            page-break-after: avoid;
        }

        .education-item,
        .experience,
        .detail-item {
            page-break-inside: avoid;
        }

        .education-item {
            margin-bottom: 12px;
        }

        .experience {
            margin-bottom: 12px;
        }

        .detail-item {
            margin-bottom: 5px;
        }

        .page-break {
            page-break-before: always;
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
        <!-- MAIN CONTENT -->
        <div class="main-content">

            <div class="content-column">

                {{-- ================= DATA PRIBADI ================= --}}
                <div class="section-block">
                    <div class="section-title">
                        Data Pribadi
                    </div>

                    <table width="100%" cellpadding="0" cellspacing="0">
                        <tr>
                            <td width="30%">Nama</td>
                            <td width="3%">:</td>
                            <td width="67%">
                                {{ $pegawai->nama_pekerja ?? '-' }}
                            </td>
                        </tr>

                        <tr>
                            <td>NIP / Nomor Pekerja</td>
                            <td>:</td>
                            <td>
                                {{ $pegawai->nomor_pekerja ?? '-' }}
                            </td>
                        </tr>

                        <tr>
                            <td>NIK</td>
                            <td>:</td>
                            <td>
                                {{ $pegawai->nik ?? '-' }}
                            </td>
                        </tr>

                        <tr>
                            <td>Tempat, Tanggal Lahir</td>
                            <td>:</td>
                            <td>
                                {{ $pegawai->tempat_lahir ?? '-' }},
                                {{ !empty($pegawai->tanggal_lahir)
    ? \Carbon\Carbon::parse($pegawai->tanggal_lahir)->format('d-m-Y')
    : '-' }}
                            </td>
                        </tr>

                        <tr>
                            <td>Jenis Kelamin</td>
                            <td>:</td>
                            <td>
                                {{ $pegawai->jenis_kelamin ?? '-' }}
                            </td>
                        </tr>

                        <tr>
                            <td>Agama</td>
                            <td>:</td>
                            <td>
                                {{ $pegawai->agama ?? '-' }}
                            </td>
                        </tr>

                        <tr>
                            <td>Status Perkawinan</td>
                            <td>:</td>
                            <td>
                                {{ $pegawai->status_perkawinan ?? '-' }}
                            </td>
                        </tr>

                        <tr>
                            <td>Alamat</td>
                            <td>:</td>
                            <td>
                                {{ $pegawai->alamat ?? '-' }}
                            </td>
                        </tr>
                    </table>
                </div>


                {{-- ================= ORGANISASI ================= --}}
                <div class="section-block">
                    <div class="section-title">
                        Organisasi
                    </div>

                    <table width="100%" cellpadding="0" cellspacing="0">
                        <tr>
                            <td width="30%">Rumah Sakit</td>
                            <td width="3%">:</td>
                            <td width="67%">
                                {{ $pegawai->nama_rumah_sakit ?? '-' }}
                            </td>
                        </tr>

                        <tr>
                            <td>Unit</td>
                            <td>:</td>
                            <td>
                                {{ $pegawai->nama_unit ?? '-' }}
                            </td>
                        </tr>

                        <tr>
                            <td>Jabatan</td>
                            <td>:</td>
                            <td>
                                {{ $pegawai->nama_jabatan ?? '-' }}
                            </td>
                        </tr>

                        <tr>
                            <td>Fungsi</td>
                            <td>:</td>
                            <td>
                                {{ $pegawai->nama_fungsi ?? '-' }}
                            </td>
                        </tr>
                    </table>
                </div>


                {{-- ================= KONTAK DARURAT ================= --}}
                <div class="section-block">
                    <div class="section-title">
                        Kontak Darurat
                    </div>

                    <table width="100%" cellpadding="0" cellspacing="0">
                        <tr>
                            <td width="30%">Nama</td>
                            <td width="3%">:</td>
                            <td width="67%">
                                {{ $pegawai->nama_kontak_darurat ?? '-' }}
                            </td>
                        </tr>

                        <tr>
                            <td>Hubungan</td>
                            <td>:</td>
                            <td>
                                {{ $pegawai->hubungan_kontak_darurat ?? '-' }}
                            </td>
                        </tr>

                        <tr>
                            <td>No. Telepon</td>
                            <td>:</td>
                            <td>
                                {{ $pegawai->telepon_kontak_darurat ?? '-' }}
                            </td>
                        </tr>
                    </table>
                </div>


                {{-- ================= PENDIDIKAN ================= --}}
                <div class="section-block">
                    <div class="section-title">
                        Pendidikan
                    </div>

                    @forelse($ijazah as $item)

                        <div class="education-item">

                            <div class="education-degree">
                                {{ $item->jenjang ?? '-' }}
                            </div>

                            <div>
                                {{ $item->nama_institusi ?? '-' }}
                            </div>

                            @if(!empty($item->jurusan))
                                <div>
                                    Jurusan: {{ $item->jurusan }}
                                </div>
                            @endif

                            @if(!empty($item->tahun_lulus))
                                <div>
                                    Tahun Lulus: {{ $item->tahun_lulus }}
                                </div>
                            @endif

                        </div>

                    @empty

                        <div>-</div>

                    @endforelse
                </div>


                {{-- ================= PENGALAMAN KERJA ================= --}}
                <div class="section-block">

                    <div class="section-title">
                        Pengalaman Kerja
                    </div>

                    @forelse($skjabatan as $item)

                        <div class="experience">

                            <strong>
                                {{ $item->nama_jabatan ?? '-' }}
                            </strong>

                            @if(!empty($item->unit))
                                <div>
                                    {{ $item->unit }}
                                </div>
                            @endif

                            @if(!empty($item->tanggal_mulai) || !empty($item->tanggal_berakhir))

                                            <div>
                                                Periode:
                                                {{ !empty($item->tanggal_mulai)
                                ? \Carbon\Carbon::parse($item->tanggal_mulai)->format('d-m-Y')
                                : '-' }}

                                                s/d

                                                {{ !empty($item->tanggal_berakhir)
                                ? \Carbon\Carbon::parse($item->tanggal_berakhir)->format('d-m-Y')
                                : 'Sekarang' }}
                                            </div>

                            @endif

                        </div>

                    @empty

                        <div>-</div>

                    @endforelse

                </div>


                {{-- ================= JABATAN ================= --}}
                <div class="section-block">

                    <div class="section-title">
                        Jabatan
                    </div>

                    @forelse($skjabatan as $item)

                        <div class="education-item">

                            <strong>
                                {{ $item->nama_jabatan ?? '-' }}
                            </strong>

                            @if(!empty($item->nomor_sk))
                                <div>
                                    No. SK: {{ $item->nomor_sk }}
                                </div>
                            @endif

                            @if(!empty($item->tanggal_mulai))
                                <div>
                                    Mulai:
                                    {{ \Carbon\Carbon::parse($item->tanggal_mulai)->format('d-m-Y') }}
                                </div>
                            @endif

                            @if(!empty($item->tanggal_berakhir))
                                <div>
                                    Berakhir:
                                    {{ \Carbon\Carbon::parse($item->tanggal_berakhir)->format('d-m-Y') }}
                                </div>
                            @endif

                        </div>

                    @empty

                        <div>-</div>

                    @endforelse

                </div>


                {{-- ================= STR DAN SIP ================= --}}
                <div class="section-block">

                    <div class="section-title">
                        STR dan SIP
                    </div>

                    @forelse($str as $item)

                        <div class="education-item">

                            <strong>
                                {{ $item->jenis ?? 'STR / SIP' }}
                            </strong>

                            @if(!empty($item->nomor))
                                <div>
                                    Nomor: {{ $item->nomor }}
                                </div>
                            @endif

                            @if(!empty($item->tanggal_terbit))
                                <div>
                                    Tanggal Terbit:
                                    {{ \Carbon\Carbon::parse($item->tanggal_terbit)->format('d-m-Y') }}
                                </div>
                            @endif

                            @if(!empty($item->tanggal_berakhir))
                                <div>
                                    Berlaku Sampai:
                                    {{ \Carbon\Carbon::parse($item->tanggal_berakhir)->format('d-m-Y') }}
                                </div>
                            @endif

                        </div>

                    @empty

                        <div>-</div>

                    @endforelse

                </div>


                {{-- ================= SPK DAN RKK ================= --}}
                <div class="section-block">

                    <div class="section-title">
                        SPK dan RKK
                    </div>

                    @forelse($spk as $item)

                        <div class="education-item">

                            <strong>
                                {{ $item->jenis ?? 'SPK / RKK' }}
                            </strong>

                            @if(!empty($item->nomor))
                                <div>
                                    Nomor: {{ $item->nomor }}
                                </div>
                            @endif

                            @if(!empty($item->tanggal_terbit))
                                <div>
                                    Tanggal Terbit:
                                    {{ \Carbon\Carbon::parse($item->tanggal_terbit)->format('d-m-Y') }}
                                </div>
                            @endif

                            @if(!empty($item->tanggal_berakhir))
                                <div>
                                    Berlaku Sampai:
                                    {{ \Carbon\Carbon::parse($item->tanggal_berakhir)->format('d-m-Y') }}
                                </div>
                            @endif

                        </div>

                    @empty

                        <div>-</div>

                    @endforelse

                </div>


                {{-- ================= SERTIFIKAT ================= --}}
                <div class="section-block">

                    <div class="section-title">
                        Sertifikat
                    </div>

                    @forelse($sertifikat as $item)

                        <div class="education-item">

                            <strong>
                                {{ $item->nama_sertifikat ?? '-' }}
                            </strong>

                            @if(!empty($item->penyelenggara))
                                <div>
                                    Penyelenggara:
                                    {{ $item->penyelenggara }}
                                </div>
                            @endif

                            @if(!empty($item->tahun_sertifikat))
                                <div>
                                    Tahun:
                                    {{ $item->tahun_sertifikat }}
                                </div>
                            @endif

                        </div>

                    @empty

                        <div>-</div>

                    @endforelse

                </div>

            </div>

        </div>
        <!-- END MAIN CONTENT -->


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