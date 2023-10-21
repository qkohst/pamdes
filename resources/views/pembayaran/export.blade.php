<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pembayaran</title>
</head>

<body>
    <table>
        <thead>
            <tr>
                <td colspan="9"><strong>DATA PEMBAYARAN</strong></td>
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
                <td align="center" style="border: 1px solid #000000; background-color: #d9ecd0;"><strong>KODE TRANSAKSI</strong></td>
                <td align="center" style="border: 1px solid #000000; background-color: #d9ecd0;"><strong>BULAN TAHUN</strong></td>
                <td align="center" style="border: 1px solid #000000; background-color: #d9ecd0;"><strong>KODE PELANGGAN</strong></td>
                <td align="center" style="border: 1px solid #000000; background-color: #d9ecd0;"><strong>NAMA PELANGGAN</strong></td>
                <td align="center" style="border: 1px solid #000000; background-color: #d9ecd0;"><strong>TOTAL PEMAKAIAN</strong></td>
                <td align="center" style="border: 1px solid #000000; background-color: #d9ecd0;"><strong>TOTAL TAGIHAN</strong></td>
                <td align="center" style="border: 1px solid #000000; background-color: #d9ecd0;"><strong>TANGGAL PEMBAYARAN</strong></td>
                <td align="center" style="border: 1px solid #000000; background-color: #d9ecd0;"><strong>STATUS</strong></td>
            </tr>
            <?php $no = 0; ?>
            @foreach($data_pembayaran as $pembayaran)
            <?php $no++; ?>
            <tr>
                <td align="center" style="border: 1px solid #000000;">{{ $no }}</td>
                <td style="border: 1px solid #000000;">{{ $pembayaran->kode }}</td>
                <td style="border: 1px solid #000000;">{{ $pembayaran->bulan_tahun_indo }}</td>
                <td style="border: 1px solid #000000;">{{ $pembayaran->pelanggan->kode }}</td>
                <td style="border: 1px solid #000000;">{{ $pembayaran->pelanggan->nama_lengkap }}</td>
                <td style="border: 1px solid #000000;">{{ number_format($pembayaran->total_pemakaian, 0, ',', '.') }}</td>
                <td style="border: 1px solid #000000;">{{ number_format($pembayaran->total_tagihan, 2, ',', '.') }}</td>
                <td style="border: 1px solid #000000;">{{ $pembayaran->tanggal_pembayaran_indo }}</td>
                <td style="border: 1px solid #000000;">{{ $pembayaran->status_str }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>