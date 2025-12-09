<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Print Label Aset & Inventaris</title>
    <style>
        @page {
            margin: 10px;
        }

        body {
            font-family: Arial, sans-serif;
            font-size: 9.4px;
            margin: 0;
            padding: 0;
        }

        table.outer-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 10px 8px;
        }

        td.outer-cell {
            width: 50%;
            vertical-align: top;
        }

        .label-box {
            border: 0px solid #000;
            padding: 16px;
            height: 170px;
            box-sizing: border-box;
            background-size: 370px auto;
            background-repeat: no-repeat;
            background-position: center;
            background-attachment: fixed;
        }

        .label-box.bg-aset {
            background-image: url("data:image/png;base64,{{ base64_encode(file_get_contents(public_path('assets/images/ihc/bgaset.png'))) }}");
        }

        .label-box.bg-inventaris {
            background-image: url("data:image/png;base64,{{ base64_encode(file_get_contents(public_path('assets/images/ihc/bginventaris.jpg'))) }}");
        }

        .header-title {
            text-align: center;
            font-weight: bold;
            font-size: 13px;
            margin-bottom: 6px;
        }

        /* Style ASET */
        table.inner-aset {
            width: 100%;
            border-collapse: collapse;
        }

        table.inner-aset td {
            padding: 1px 0;
        }

        table.inner-aset .label-col {
            width: 70px;
        }

        table.inner-aset .box-small {
            border: 1px solid #000;
            width: 30px;
            height: 40px;
            text-align: center;
            vertical-align: top;
            font-size: 9px;
        }

        table.inner-aset .label-col {
            width: 70px;
            font-weight: bold;
        }
        table.inner-aset .separator {
            width: 3px;
            /* color: white; */
            font-weight: bold;
            text-align: center;
        }

        table.inner-aset .value-cell {
            border-bottom: 1px solid ;
            /* background-color: white; */
            font-weight: bold;
        }

        table.inner-aset .box-small {
            border: 1px solid ;
            width: 30px;
            /* background-color: white; */
            text-align: center;
            vertical-align: top;
        }
          table.inner-aset td {
            padding: 3px;
        }
         table.inner-aset .value-cell {
            border-bottom: 1px solid #000;
            /* background-color: white; */
            font-weight: bold;
        }

        /* style invntariss */
        table.inner-inventaris {
            width: 98%;
            border-collapse: collapse;
        }

        table.inner-inventaris td {
            padding: 3px;
        }

        table.inner-inventaris .label-col {
            width: 70px;
            color: white;
            font-weight: bold;
        }

        table.inner-inventaris .separator {
            width: 3px;
            color: white;
            font-weight: bold;
            text-align: center;
        }

        table.inner-inventaris .value-cell {
            border-bottom: 1px solid #3c04bd;
            background-color: white;
            font-weight: bold;
        }

        table.inner-inventaris .box-small {
            border: 1px solid #3c04bd;
            width: 30px;
            background-color: white;
            text-align: center;
            vertical-align: top;
        }
    </style>
</head>

<body>
    <table class="outer-table">
        @foreach ($asets->chunk(2) as $chunk)
            <tr>
                @foreach ($chunk as $aset)
                    <td class="outer-cell">
                        <div class="label-box {{ strtolower($aset->jenis) == 'aset' ? 'bg-aset' : 'bg-inventaris' }}">
                            @if (strtolower($aset->jenis) == 'aset')
                                <div class="header-title" style="margin-top: 25px">
                                    ASET TETAP PERTAMEDIKA - IHC
                                </div>

                                <table class="inner-aset">
                                    <tr>
                                        <td class="label-col">Unit Usaha</td>
                                        <td>:</td>
                                        <td colspan="3" style="border-bottom:1px solid #000;"class="value-cell">
                                            RSOJ Pertamina Royal Biringkanaya
                                        </td>
                                    </tr>

                                    <tr>
                                        <td class="label-col">No. Aset</td>
                                        <td>:</td>
                                        <td colspan="3" style="border-bottom:1px solid #000;"class="value-cell">
                                            {{ $aset->no_aset }}
                                        </td>
                                    </tr>

                                    <tr>
                                        <td class="label-col">No. Manufacture</td>
                                        <td>:</td>
                                        <td colspan="3" style="border-bottom:1px solid #000;"class="value-cell">
                                            {{ $aset->no_sn }}
                                        </td>
                                    </tr>

                                    <tr>
                                        <td class="label-col">Deskripsi</td>
                                        <td>:</td>
                                        <td style="border-bottom:1px solid #000;"class="value-cell">
                                            {{ $aset->nama }}
                                        </td>
                                        <td rowspan="3" class="box-small"class="value-cell">Paraf</td>
                                        <td rowspan="3" class="box-small"class="value-cell">
                                            Bln/Thn<br><br>
                                            {{ \Carbon\Carbon::parse($aset->tahun)->format('m/Y') }}
                                        </td>
                                    </tr>

                                    <tr>
                                        <td class="label-col">Lokasi</td>
                                        <td>:</td>
                                        <td style="border-bottom:1px solid #000;"class="value-cell">
                                            {{ $aset->nama_lokasi }}
                                        </td>
                                    </tr>

                                    <tr>
                                        <td class="label-col">Kondisi</td>
                                        <td>:</td>
                                        <td style="border-bottom:1px solid #000;"class="value-cell">
                                            {{ $aset->nama_kondisi }}
                                        </td>
                                    </tr>
                                </table>
                            @else
                                <div class="header-title" style="margin-top: 15px;color: white">
                                    INVENTARIS RSOJ PERTAMINA <br> ROYAL BIRINGKANAYA
                                </div>
                                <table class="inner-inventaris">
                                    <tr>
                                        <td align="left" valign="middle" class="label-col">Unit Usaha</td>
                                        <td align="center" valign="middle" class="separator">:</td>
                                        <td align="left" valign="middle" colspan="3" class="value-cell">
                                            RSOJ Pertamina Royal Biringkanaya
                                        </td>
                                    </tr>

                                    <tr>
                                        <td align="left" valign="middle" class="label-col">No. Inventaris</td>
                                        <td align="center" valign="middle" class="separator">:</td>
                                        <td align="left" valign="middle" colspan="3" class="value-cell">
                                            {{ $aset->no_aset }}
                                        </td>
                                    </tr>

                                    <tr>
                                        <td align="left" valign="middle" class="label-col">No. Manufacture</td>
                                        <td align="center" valign="middle" class="separator">:</td>
                                        <td align="left" valign="middle" colspan="3" class="value-cell">
                                            {{ $aset->no_sn }}
                                        </td>
                                    </tr>

                                    <tr>
                                        <td align="left" valign="middle" class="label-col">Deskripsi</td>
                                        <td align="center" valign="middle" class="separator">:</td>
                                        <td align="left" valign="middle" class="value-cell">
                                            {{ $aset->nama }}
                                        </td>
                                        <td align="center" valign="top" rowspan="3" class="box-small">
                                            Paraf
                                        </td>
                                        <td align="center" valign="top" rowspan="3" class="box-small">
                                            Bln/Thn<br><br>
                                            {{ \Carbon\Carbon::parse($aset->tahun)->format('m/Y') }}
                                        </td>
                                    </tr>

                                    <tr>
                                        <td align="left" valign="middle" class="label-col">Lokasi</td>
                                        <td align="center" valign="middle" class="separator">:</td>
                                        <td align="left" valign="middle" class="value-cell">
                                            {{ $aset->nama_lokasi }}
                                        </td>
                                    </tr>

                                    <tr>
                                        <td align="left" valign="middle" class="label-col">Kondisi</td>
                                        <td align="center" valign="middle" class="separator">:</td>
                                        <td align="left" valign="middle" class="value-cell">
                                            {{ $aset->nama_kondisi }}
                                        </td>
                                    </tr>
                                </table>
                            @endif
                        </div>
                    </td>
                @endforeach

                @if ($chunk->count() == 1)
                    <td class="outer-cell"></td>
                @endif
            </tr>
        @endforeach
    </table>
</body>

</html>
