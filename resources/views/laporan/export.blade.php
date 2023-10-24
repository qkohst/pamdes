<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan</title>
</head>

<body>
    <table>
        <thead>
            <tr>
                <td colspan="9"><strong>LAPORAN TRANSAKSI</strong></td>
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
                <td align="center" style="border: 1px solid #000000; background-color: #d9ecd0;" rowspan="2"><strong>NO</strong></td>
                <td align="center" style="border: 1px solid #000000; background-color: #d9ecd0;" rowspan="2"><strong>BULAN TAHUN</strong></td>
                <td align="center" style="border: 1px solid #000000; background-color: #d9ecd0;" rowspan="2"><strong>PELANGGAN</strong></td>
                <td align="center" style="border: 1px solid #000000; background-color: #d9ecd0;" rowspan="2"><strong>KODE TRANSAKSI</strong></td>

                <td align="center" style="border: 1px solid #000000; background-color: #d9ecd0;" colspan="3"><strong>PEMAKAIAN (M3)</strong></td>
                <td align="center" style="border: 1px solid #000000; background-color: #d9ecd0;" colspan="3"><strong>BIAYA (RP)</strong></td>
                <td align="center" style="border: 1px solid #000000; background-color: #d9ecd0;" colspan="2"><strong>TAGIHAN (RP)</strong></td>
                <td align="center" style="border: 1px solid #000000; background-color: #d9ecd0;" rowspan="2"><strong>TANGGAL PEMBAYARAN</strong></td>
                <td align="center" style="border: 1px solid #000000; background-color: #d9ecd0;" rowspan="2"><strong>STATUS</strong></td>
            </tr>
            <tr>
                <td align="center" style="border: 1px solid #000000; background-color: #d9ecd0;"><strong>AWAL</strong></td>
                <td align="center" style="border: 1px solid #000000; background-color: #d9ecd0;"><strong>AKHIR</strong></td>
                <td align="center" style="border: 1px solid #000000; background-color: #d9ecd0;"><strong>TOTAL</strong></td>
                <td align="center" style="border: 1px solid #000000; background-color: #d9ecd0;"><strong>PEMAKAIAN</strong></td>
                <td align="center" style="border: 1px solid #000000; background-color: #d9ecd0;"><strong>PEMELIHARAAN</strong></td>
                <td align="center" style="border: 1px solid #000000; background-color: #d9ecd0;"><strong>ADMINISTRASI</strong></td>
                <td align="center" style="border: 1px solid #000000; background-color: #d9ecd0;"><strong>TOTAL TAGIHAN</strong></td>
                <td align="center" style="border: 1px solid #000000; background-color: #d9ecd0;"><strong>BAYAR</strong></td>
            </tr>
            <?php $no = 0; ?>
            @foreach($data_laporan as $laporan)
            <?php $no++; ?>
            <tr>
                <td align="center" style="border: 1px solid #000000;">{{ $no }}</td>
                <td style="border: 1px solid #000000;">{{ $laporan->bulan_tahun_indo }}</td>
                <td style="border: 1px solid #000000;">{{ $laporan->pelanggan->kode }} | {{ $laporan->pelanggan->nama_lengkap }}</td>
                <td style="border: 1px solid #000000;">{{ $laporan->kode }}</td>
                <td style="border: 1px solid #000000;">{{ number_format($laporan->pemakaian_sebelumnya, 0, ',', '.') }}</td>
                <td style="border: 1px solid #000000;">{{ number_format($laporan->pemakaian_saat_ini, 0, ',', '.') }}</td>
                <td style="border: 1px solid #000000;">{{ number_format($laporan->total_pemakaian, 0, ',', '.') }}</td>

                <td style="border: 1px solid #000000;">{{ number_format(($laporan->pemakaian_saat_ini-$laporan->pemakaian_sebelumnya)*$laporan->tarif_per_meter, 2, ',', '.') }}</td>
                <td style="border: 1px solid #000000;">{{ number_format($laporan->biaya_pemeliharaan, 2, ',', '.') }}</td>
                <td style="border: 1px solid #000000;">{{ number_format($laporan->biaya_administrasi, 2, ',', '.') }}</td>
                <td style="border: 1px solid #000000;">{{ number_format($laporan->total_tagihan, 2, ',', '.') }}</td>
                <td style="border: 1px solid #000000;">{{ number_format($laporan->total_tagihan, 2, ',', '.') }}</td>
                <td style="border: 1px solid #000000;">{{ $laporan->tanggal_pembayaran_indo }}</td>
                <td style="border: 1px solid #000000;">{{ $laporan->status_str }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>