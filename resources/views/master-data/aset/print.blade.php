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
            /* diperkecil */
            background: url('public/assets/images/ihc/bgaset.jpeg') no-repeat center center;
            background-size: cover;
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
            font-size: 14px;
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
        <div class="header-title">ASET TETAP PERTAMEDIKA - IHC</div>
        <img src="{{ asset('../public/assets/images/ihc/bgaset.jpeg') }}" alt="" >
        <table width="98%" cellpadding="3" cellspacing="0" border="0">
            <tr>
                <td align="left" valign="middle" width="70">Unit Usaha</td>
                <td align="center" valign="middle" width="3">:</td>
                <td align="left" valign="middle" colspan="5" style="border-bottom: 1px solid #000;">RSOJ Pertamina Royal
                    Biringkanaya</td>
            </tr>

            <tr>
                <td align="left" valign="middle">No. Aset</td>
                <td align="center" valign="middle">:</td>
                <td align="left" valign="middle" colspan="5" style="border-bottom: 1px solid #000;">{{ $aset->no_aset }}
                </td>
            </tr>

            <tr>
                <td align="left" valign="middle">No. Manufacture</td>
                <td align="center" valign="middle">:</td>
                <td align="left" valign="middle" colspan="5" style="border-bottom: 1px solid #000;">{{ $aset->no_sn }}
                </td>
            </tr>

            <tr>
                <td align="left" valign="middle">Deskripsi</td>
                <td align="center" valign="middle">:</td>
                <td align="left" valign="middle" style="border-bottom: 1px solid #000; width: 145px;">{{ $aset->nama }}
                </td>


                <!-- Kolom Paraf -->
                <td align="center" valign="top" rowspan="3" style="border: 1px solid #000; width: 30px;">
                    Paraf
                </td>

                <!-- Kolom Tanggal -->
                <td align="center" valign="top" rowspan="3" style="border: 1px solid #000; width: 30px;">
                    Bln/Thn <br><br>{{ \Carbon\Carbon::parse($aset->tahun)->format('m/Y') }}
                </td>

                <!-- <td align="center" valign="top" rowspan="3"  style="border: 1px solid #000; width: 0px;">
                </td> -->
            </tr>

            <tr>
                <td align="left" valign="middle">Lokasi</td>
                <td align="center" valign="middle">:</td>
                <td align="left" valign="middle" style="border-bottom: 1px solid #000;">{{ $aset->nama_lokasi }}</td>
            </tr>

            <tr>
                <td align="left" valign="middle">Kondisi</td>
                <td align="center" valign="middle">:</td>
                <td align="left" valign="middle" style="border-bottom: 1px solid #000;">{{ $aset->nama_kondisi }}</td>
            </tr>

        </table>
    </div>

</body>

</html>