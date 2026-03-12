<html>
<style>
    @page {
        size: 5cm 3cm;
        margin: 0;
    }

    body {
        margin: 0;
        padding: 2mm;
        font-size: 8px;
        font-family: "Arial Black", Arial, sans-serif;
    }
</style>

<div style="text-align:center">

    <!-- <b style="font-size:9px">KARTU JAGA</b>
    <hr style="margin:3px 0"> -->

    <table width="100%" style="font-size:9px">
        <tr>
            <td width="35%" style="font-weight:bold;">Nama Pasien</td>
            <td style="font-weight:bold;">: {{ $data->nama }}</td>
        </tr>

        <tr>
            <td style="font-weight:bold;">No HP</td>
            <td style="font-weight:bold;">: {{ $data->no_hp }}</td>
        </tr>

        <tr>
            <td style="font-weight:bold;">Kamar</td>
            <td style="font-weight:bold;">: {{ $data->ruangan }}</td>
        </tr>

        <tr>
            <td style="font-weight:bold;">Kartu</td>
            <td style="font-weight:bold;">: {{ $data->no_kartu }}</td>
        </tr>

        <tr>
            <td style="font-weight:bold;">Tanggal</td>
            <td style="font-weight:bold;">: {{ now()->format('d M Y H:i') }}</td>
        </tr>
    </table>
    
    <div style="text-align:left; font-weight:bold;">
        selesai pengurusan administrasi kartu jaga
    </div>

</div>

</html>