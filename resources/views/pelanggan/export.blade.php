<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pelanggan</title>
</head>

<body>
    <table>
        <thead>
            <tr>
                <td colspan="9"><strong>DATA PELANGGAN</strong></td>
            </tr>
            <tr>
                <td colspan="9">Waktu download : {{$time_download}}</td>
            </tr>
            <tr>
                <td colspan="9">Didownload oleh : {{Auth::user()->nama}} ({{Auth::user()->username}})</td>
            </tr>
        </thead>
        <tbody>
            <tr>
            </tr>
            <tr>
                <td align="center" style="border: 1px solid #000000; background-color: #d9ecd0;"><strong>NO</strong></td>
                <td align="center" style="border: 1px solid #000000; background-color: #d9ecd0;"><strong>KODE PELANGGAN</strong></td>
                <td align="center" style="border: 1px solid #000000; background-color: #d9ecd0;"><strong>NAMA LENGKAP</strong></td>
                <td align="center" style="border: 1px solid #000000; background-color: #d9ecd0;"><strong>NOMOR HP/WA</strong></td>
                <td align="center" style="border: 1px solid #000000; background-color: #d9ecd0;"><strong>ALAMAT</strong></td>
                <td align="center" style="border: 1px solid #000000; background-color: #d9ecd0;"><strong>STATUS</strong></td>
            </tr>
            <?php $no = 0; ?>
            @foreach($data_pelanggan as $pelanggan)
            <?php $no++; ?>
            <tr>
                <td align="center" style="border: 1px solid #000000;">{{ $no }}</td>
                <td style="border: 1px solid #000000;">{{ $pelanggan->kode }}</td>
                <td style="border: 1px solid #000000;">{{ $pelanggan->nama_lengkap }}</td>
                <td style="border: 1px solid #000000;">{{ $pelanggan->nomor_hp_wa }}</td>
                <td style="border: 1px solid #000000;">{{ $pelanggan->alamat }}</td>
                <td style="border: 1px solid #000000;">{{ $pelanggan->status_pelanggan }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>