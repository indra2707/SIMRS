<html>
<style>
    @page {
        size: 5cm 3cm;
        margin: 0;
    }

    body {
        margin: 0;
        padding: 2mm;
        font-size: 9px;
    }
</style>

<div style="text-align:center">

    <!-- <b style="font-size:9px">KARTU JAGA</b>
    <hr style="margin:3px 0"> -->

    <table width="100%" style="font-size:9px">
        <tr>
            <td width="35%">Nama Pasien</td>
            <td>: {{ $data->nama }}</td>
        </tr>

        <tr>
            <td>No HP</td>
            <td>: {{ $data->no_hp }}</td>
        </tr>

        <tr>
            <td>Kamar</td>
            <td>: {{ $data->ruangan }}</td>
        </tr>

        <tr>
            <td>Kartu</td>
            <td>: {{ $data->no_kartu }}</td>
        </tr>

        <tr>
            <td>Tanggal</td>
            <td>: {{ now()->format('d M Y H:i') }}</td>
        </tr>
    </table>
    <br>
    <div style="text-align:left; font-weight:bold;">
        Selesai Pengurusan administrasi kartu jaga
    </div>

</div>

</html>