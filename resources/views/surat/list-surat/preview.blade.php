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

        .ttd-nama {
            font-weight: bold;
            text-decoration: underline;
            margin-top: 60px;
        }

        .tanggal {
            margin-bottom: 10px;
        }

        table.info-table {
            border-collapse: collapse;
            margin-bottom: 10px;
        }

        table.info-table td {
            border: none;
            padding: 1px 0;
            vertical-align: top;
            font-size: 11pt;
        }

        table.info-table td.label {
            width: 90px;
            white-space: nowrap;
        }

        table.info-table td.perihal-value {
            font-weight: bold;
        }

        .dengan-hormat {
            margin: 10px 0;
        }

        .isi-surat p {
            text-align: justify;
            margin: 0 0 5px 0;
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
    </style>
</head>

<body>


    <img class="bg-fixed" src="data:image/png;base64,{{ $bgBase64 }}">




    <div class="tanggal">
        Makassar, {{ $tanggal }}
    </div>



    <table class="info-table">

        <tr>
            <td class="label">
                Nomor
            </td>

            <td>
                : {{ $surat->no_surat }}
            </td>
        </tr>

        <tr>
            <td class="label">
                Perihal
            </td>

            <td class="perihal-value">
                : {{ $surat->perihal }}
            </td>
        </tr>

        @if ($surat->approver)
            <tr>
                <td class="label">
                    Approval
                </td>

                <td>
                    : {{ $surat->approver->username }}
                </td>
            </tr>
        @endif

    </table>



    <div class="dengan-hormat">
        Dengan hormat,
    </div>



    <div class="isi-surat">

        @foreach ($paragrafIsi as $paragraf)
            <p>
                {{ $paragraf }}
            </p>
        @endforeach

    </div>



       @if ($surat->approver)
        <div class="ttd-block">
 
            <div>
                Mengetahui,
            </div>
 
            <div class="ttd-nama">
                {{ $surat->approver->username }}
            </div>
 
        </div>
    @endif
 
 
   
 
    @foreach ($lampiranList as $i => $lampiran)
        <div class="lampiran-page">
 
            <div class="lampiran-title">
                Lampiran {{ count($lampiranList) > 1 ? ($i + 1) . ' dari ' . count($lampiranList) : '' }}:
            </div>
 
            <img class="lampiran-img" src="data:image/{{ $lampiran['mime'] }};base64,{{ $lampiran['base64'] }}"
                width="{{ $lampiran['width'] }}">
 
        </div>
    @endforeach


</body>

</html>
