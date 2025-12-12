<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">

    <style>
        @page {
            margin: 0;
        }

        body {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
            font-size: 10px;

            @if ($aset->jenis == 'Aset')
                background-image: url("data:image/png;base64,{{ base64_encode(file_get_contents(public_path('assets/images/ihc/bgaset.png'))) }}");
            @else background-image: url("data:image/png;base64,{{ base64_encode(file_get_contents(public_path('assets/images/ihc/bginventaris.jpg'))) }}");
            @endif background-size: 370px auto;
            background-repeat: no-repeat;
            background-position: center;
            background-attachment: fixed;
            /* diperkecil */
        }

        .label-container {
            width: 90%;
            max-height: 200%;
            overflow: hidden;
            /* cegah halaman kedua */
            padding: 15px;
            padding-right: 30px;
            /* jarak kanan */

            /* background: #34a308ff; Hijau muda */

        }

        .header-title {
            text-align: center;
            font-weight: bold;
            font-size: 12px;
            margin: 2px 0 4px 0;
        }

        .row {
            display: flex;
            margin-bottom: 2px;
            text-align: justify;
        }

        .label {
            width: 70px;
            font-weight: bold;
        }

        .value {
            flex: 1;
        }
    </style>

</head>

<body>
    <div class="label-container">
        <br><br>
        @php

            $path = public_path('assets/images/ihc/ttd-feny.png');
            $ttd = base64_encode(file_get_contents($path));
        @endphp
        @if ($aset->jenis == 'Aset')
            <div class="header-title">ASET TETAP PERTAMEDIKA - IHC</div>
            <table width="98%" cellpadding="3" cellspacing="0" border="0">
                <tr>
                    <td align="left" valign="middle" width="70">Unit Usaha</td>
                    <td align="center" valign="middle" width="3">:</td>
                    <td align="left" valign="middle" colspan="3" style="border-bottom: 1px solid #000;">RSOJ
                        Pertamina
                        Royal
                        Biringkanaya</td>
                </tr>

                <tr>
                    <td align="left" valign="middle">No. Aset</td>
                    <td align="center" valign="middle">:</td>
                    <td align="left" valign="middle" colspan="3" style="border-bottom: 1px solid #000;">
                        {{ $aset->no_aset }}
                    </td>
                </tr>

                <tr>
                    <td align="left" valign="middle">No. Manufacture</td>
                    <td align="center" valign="middle">:</td>
                    <td align="left" valign="middle" colspan="3" style="border-bottom: 1px solid #000;">
                        {{ $aset->no_sn }}
                    </td>
                </tr>

                <tr>
                    <td align="left" valign="middle">Deskripsi</td>
                    <td align="center" valign="middle">:</td>
                    <td align="left" valign="middle" style="border-bottom: 1px solid #000; width: 145px;">
                        {{ $aset->nama }}
                    </td>

                    <!-- Kolom Paraf -->
                    <td align="center" valign="top" rowspan="3" style="border: 1px solid #000; width: 30px;">
                        Paraf
                    </td>

                    <!-- Kolom Tanggal -->
                    <td align="center" valign="top" rowspan="3" style="border: 1px solid #000; width: 30px;">
                        Bln/Thn <br><br>
                        <!-- {{ \Carbon\Carbon::parse($aset->tahun)->format('m/Y') }} -->
                    </td>

                </tr>

                <tr>
                    <td align="left" valign="middle">Lokasi</td>
                    <td align="center" valign="middle">:</td>
                    <td align="left" valign="middle" style="border-bottom: 1px solid #000;">{{ $aset->nama_lokasi }}
                    </td>
                </tr>

                <tr>
                    <td align="left" valign="middle">Kondisi</td>
                    <td align="center" valign="middle">:</td>
                    <td align="left" valign="middle" style="border-bottom: 1px solid #000;">{{ $aset->nama_kondisi }}
                    </td>
                </tr>

            </table>
        @else
            <div class="header-title" style="color: white">INVENTARIS RSOJ PERTAMINA ROYAL BIRINGKANAYA</div>
            <table width="98%" cellpadding="2" cellspacing="0" border="0">
                <tr>
                    <td align="left" valign="middle" width="70" style="color: white;font-weight: bold">Unit Usaha
                    </td>
                    <td align="center" valign="middle" width="3" style="color: white;font-weight: bold">:</td>
                    <td align="left" valign="middle" colspan="3"
                        style="border-bottom: 1px solid #3c04bd;background-color: white;font-weight: bold">RSOJ
                        Pertamina
                        Royal
                        Biringkanaya</td>
                </tr>

                <tr>
                    <td align="left" valign="middle" style="color: white;font-weight: bold">No. Inventaris</td>
                    <td align="center" valign="middle" style="color: white;font-weight: bold">:</td>
                    <td align="left" valign="middle" colspan="3"
                        style="border-bottom: 1px solid #3c04bd;background-color: white;font-weight: bold">
                        {{ $aset->no_aset }}
                    </td>
                </tr>

                <tr>
                    <td align="left" valign="middle" style="color: white;font-weight: bold">No. Manufacture</td>
                    <td align="center" valign="middle" style="color: white;font-weight: bold">:</td>
                    <td align="left" valign="middle" colspan="3"
                        style="border-bottom: 1px solid #3c04bd;background-color: white;font-weight: bold">
                        {{ $aset->no_sn }}
                    </td>
                </tr>

                <tr>
                    <td align="left" valign="middle" style="color: white;font-weight: bold">Deskripsi</td>
                    <td align="center" valign="middle" style="color: white;font-weight: bold">:</td>
                    <td align="left" valign="middle"
                        style="border-bottom: 1px solid #3c04bd;background-color: white;font-weight: bold">
                        {{ $aset->nama }}
                    </td>


                    @if ($aset->kategori == 'Alkes')
                        <!-- Kolom Paraf -->
                        <td align="center" valign="top" rowspan="3"
                            style="border: 1px solid #3c04bd; width: 30px;background-color: white">
                            Paraf
                            <br>
                            <br>
                            <img src="data:image/png;base64,{{ $ttd }}" style="width:20px; ">
                        </td>
                    @else
                        <td align="center" valign="top" rowspan="3"
                            style="border: 1px solid #3c04bd; width: 30px;background-color: white">
                            Paraf
                        </td>
                    @endif

                    <!-- Kolom Tanggal -->
                    <td align="center" valign="top" rowspan="3"
                        style="border: 1px solid #3c04bd; width: 30px;background-color: white">
                        Bln/Thn <br><br>{{ \Carbon\Carbon::parse($aset->tahun)->format('m/Y') }}
                    </td>

                </tr>

                <tr>
                    <td align="left" valign="middle" style="color: white;font-weight: bold">Lokasi</td>
                    <td align="center" valign="middle" style="color: white;font-weight: bold">:</td>
                    <td align="left" valign="middle"
                        style="border-bottom: 1px solid #3c04bd;background-color: white;font-weight: bold">
                        {{ $aset->nama_lokasi }}
                    </td>
                </tr>

                <tr>
                    <td align="left" valign="middle" style="color: white;font-weight: bold">Kondisi</td>
                    <td align="center" valign="middle" style="color: white;font-weight: bold">:</td>
                    <td align="left" valign="middle"
                        style="border-bottom: 1px solid #3c04bd;background-color: white;font-weight: bold">
                        {{ $aset->nama_kondisi }}
                    </td>
                </tr>

            </table>
        @endif

    </div>

</body>

</html>