<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{$title}}</title>
    <style>
        @page {
            margin: 5mm;
            /* Margin diatur sesuai kebutuhan */
        }

        body {
            font-family: Arial, sans-serif;
        }

        .table-container {
            margin: 4px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 6px;
        }

        th {
            background-color: #d9ecd0;
            /* Warna hijau pada <th> */
            text-align: center;
            padding: 6px;
        }

        th,
        td {
            border: 1px solid #000;
            padding: 6px;
            font-size: 10px;
            /* Ukuran font yang lebih kecil */
        }

        .center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

        p {
            font-size: 11px;
            padding: 0px;
            margin: 2px;
        }

        p.title {
            font-size: 13px;
            font-weight: bold;
        }
    </style>
</head>

<body>
    <div class="table-container">
        <p class="title">LAPORAN TRANSAKSI</p>
        <p>Waktu download : {{$waktu_cetak}}</p>
        <p>Didownload oleh : {{Auth::user()->nama}} ({{Auth::user()->username}})</p>
        <table>
            <thead>
                <tr>
                    <th rowspan="2">NO</th>
                    <th rowspan="2">BULAN TAHUN</th>
                    <th rowspan="2">PELANGGAN</th>
                    <th rowspan="2">KODE TRANSAKSI</th>
                    <th colspan="3">PEMAKAIAN (M3)</th>
                    <th colspan="3">BIAYA (RP)</th>
                    <th colspan="2">TAGIHAN (RP)</th>
                    <th rowspan="2">TANGGAL PEMBAYARAN</th>
                    <th rowspan="2">STATUS</th>
                </tr>
                <tr>
                    <th>AWAL</th>
                    <th>AKHIR</th>
                    <th>TOTAL</th>
                    <th>PEMAKAIAN</th>
                    <th>PEMELIHARAAN</th>
                    <th>ADMINISTRASI</th>
                    <th>TOTAL TAGIHAN</th>
                    <th>BAYAR</th>
                </tr>
            </thead>
            <tbody>
                <?php $no = 0; ?>
                @foreach($data_laporan as $laporan)
                <?php $no++; ?>
                <tr>
                    <td class="center">{{ $no }}</td>
                    <td>{{ $laporan->bulan_tahun_indo }}</td>
                    <td>{{ $laporan->pelanggan->kode }} | {{ $laporan->pelanggan->nama_lengkap }}</td>
                    <td>{{ $laporan->kode }}</td>
                    <td class="text-right">{{ number_format($laporan->pemakaian_sebelumnya, 0, ',', '.') }}</td>
                    <td class="text-right">{{ number_format($laporan->pemakaian_saat_ini, 0, ',', '.') }}</td>
                    <td class="text-right">{{ number_format($laporan->total_pemakaian, 0, ',', '.') }}</td>

                    <td class="text-right">{{ number_format(($laporan->pemakaian_saat_ini-$laporan->pemakaian_sebelumnya)*$laporan->tarif_per_meter, 2, ',', '.') }}</td>
                    <td class="text-right">{{ number_format($laporan->biaya_pemeliharaan, 2, ',', '.') }}</td>
                    <td class="text-right">{{ number_format($laporan->biaya_administrasi, 2, ',', '.') }}</td>
                    <td class="text-right">{{ number_format($laporan->total_tagihan, 2, ',', '.') }}</td>
                    <td class="text-right">{{ number_format($laporan->total_tagihan, 2, ',', '.') }}</td>
                    <td>{{ $laporan->tanggal_pembayaran_indo }}</td>
                    <td>{{ $laporan->status_str }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</body>

</html>