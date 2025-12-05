<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Print Label Aset</title>
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
            /* jarak antara kotak */
        }
        td.outer-cell {
            width: 50%;
            vertical-align: top;
        }
        .label-box {
            border: 0px solid #000;
            padding: 16px;
            height: 170px;
            /* tinggi disamakan */
            box-sizing: border-box;
            background-image: url("data:image/png;base64,{{ base64_encode(file_get_contents(public_path('assets/images/ihc/bgaset.png'))) }}");
            background-size: 370px auto;
            background-repeat: no-repeat;
            background-position: center;
            background-attachment: fixed;
        }
        .header-title {
            text-align: center;
            font-weight: bold;
            font-size: 13px;
            margin-bottom: 6px;
        }
        table.inner {
            width: 100%;
            border-collapse: collapse;
        }
        td {
            padding: 1px 0;
        }
        .label-col {
            width: 70px;
        }
        .box-small {
            border: 1px solid #000;
            width: 30px;
            height: 40px;
            text-align: center;
            vertical-align: top;
            font-size: 9px;
        }
    </style>
</head>
<body>
    <table class="outer-table">
        @foreach ($asets->chunk(2) as $chunk)
            <tr>
                @foreach ($chunk as $asset)
                    {{-- KOLOM --}}
                    <td class="outer-cell">
                        <div class="label-box"><br><br>
                            <div class="header-title">ASET TETAP PERTAMEDIKA - IHC</div>

                            <table class="inner">
                                <tr>
                                    <td class="label-col">Unit Usaha</td>
                                    <td>:</td>
                                    <td colspan="3" style="border-bottom:1px solid #000;">
                                        RSOJ Pertamina Royal Biringkanaya
                                    </td>
                                </tr>

                                <tr>
                                    <td class="label-col">No. Aset</td>
                                    <td>:</td>
                                    <td colspan="3" style="border-bottom:1px solid #000;">
                                        {{ $asset->no_aset }}
                                    </td>
                                </tr>

                                <tr>
                                    <td class="label-col">No. Manufacture</td>
                                    <td>:</td>
                                    <td colspan="3" style="border-bottom:1px solid #000;">
                                        {{ $asset->no_sn }}
                                    </td>
                                </tr>

                                <tr>
                                    <td class="label-col">Deskripsi</td>
                                    <td>:</td>
                                    <td style="border-bottom:1px solid #000;">
                                        {{ $asset->nama }}
                                    </td>
                                    <td rowspan="3" class="box-small">Paraf</td>
                                    <td rowspan="3" class="box-small">
                                        Bln/Thn<br><br>
                                        {{ \Carbon\Carbon::parse($asset->tahun)->format('m/Y') }}
                                    </td>
                                </tr>

                                <tr>
                                    <td class="label-col">Lokasi</td>
                                    <td>:</td>
                                    <td style="border-bottom:1px solid #000;">
                                        {{ $asset->nama_lokasi }}
                                    </td>
                                </tr>

                                <tr>
                                    <td class="label-col">Kondisi</td>
                                    <td>:</td>
                                    <td style="border-bottom:1px solid #000;">
                                        {{ $asset->nama_kondisi }}
                                    </td>
                                </tr>
                            </table>
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
