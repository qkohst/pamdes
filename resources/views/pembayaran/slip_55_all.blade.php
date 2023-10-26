<!DOCTYPE html>
<html>

<head>
    <title>{{$title}}</title>
    <style>
        @page {
            size: 55mm 115mm;
            /* Lebar 8 cm, tinggi sesuai kebutuhan */
            margin: 1mm;
            /* Margin diatur sesuai kebutuhan */
        }

        body {
            font-family: Arial, sans-serif;
        }

        .invoice {
            width: 50mm;
            /* Lebar kertas 8 cm */
            margin: 0 auto;
            padding: 4px;
            /* border: 1px solid #000; */
        }

        .invoice-header {
            text-align: center;
        }

        .invoice-header p {
            font-size: 10px;
            margin: 0px;
            padding: 0px;
            font-weight: bolder;
        }

        .invoice-info {
            margin-top: 10px;
        }

        .invoice-info td {
            font-size: 9px;
        }

        .invoice-info p {
            padding: 0px;
            margin: 0px;
        }

        .invoice-detail {
            margin-top: 0px;
        }

        .invoice-detail td {
            font-size: 9px;
        }

        .invoice-detail p {
            padding: 0px;
            margin: 0px;
        }

        .invoice-total {
            margin-top: 0px;
        }

        .invoice-total td {
            font-size: 9px;
        }

        .invoice-total p {
            padding: 0px;
            margin: 0px;
        }

        .timestamp {
            text-align: center;
            margin: 0px;
            font-size: 8px;
            padding: 0px;
        }

        .thank-you {
            margin-top: 6px;
            font-size: 10px;
            text-align: center;
        }

        .thank-you p {
            padding: 2px;
            margin: 0px;
        }

        .garis {
            font-size: 11px;
            text-align: center;
            padding: 0px;
            margin: 0px;
        }

        table tr td.angka {
            text-align: right;
            padding-right: 0px;
            margin-right: 0px;
        }

        .page-break {
            page-break-after: always;
        }
    </style>
</head>

<body>
    <?php $index = 0; ?>
    <?php $countdata = $data_transaksi->count(); ?>
    @foreach ($data_transaksi as $transaksi)
    <?php $index++; ?>
    <div class="invoice">
        <div class="invoice-header">
            <p>{{$setting_global->nama}}</p>
            <p>{{$setting_global->alamat}}</p>
            <p>WA/TELP : {{$setting_global->nomor_hp_wa}}</p>
            <p>=================================</p>
            <p>BUKTI PEMBAYARAN AIR</p>
        </div>

        <div class="invoice-info">
            <table>
                <tr>
                    <td>KD. TRANSAKSI</td>
                    <td>:</td>
                    <td>{{$transaksi->kode}}</td>
                </tr>
                <tr>
                    <td>BULAN</td>
                    <td>:</td>
                    <td>{{Str::upper($transaksi->bulan_tahun_indo)}}</td>
                </tr>
                <tr>
                    <td>KD. PELANGGAN</td>
                    <td>:</td>
                    <td>{{Str::upper($transaksi->pelanggan->kode)}}</td>
                </tr>
                <tr>
                    <td>NAMA</td>
                    <td>:</td>
                    <td>{{Str::upper($transaksi->pelanggan->nama_lengkap)}}</td>
                </tr>
                <tr>
                    <td>TGL. BAYAR</td>
                    <td>:</td>
                    <td>{{Str::upper($transaksi->tanggal_pembayaran_indo)}}</td>
                </tr>
            </table>
            <p class="garis">=============================</p>
        </div>

        <div class="invoice-detail">
            <table width="100%">
                <tr>
                    <td>TOTAL PEMAKAIAN</td>
                    <td class="angka">{{$transaksi->total_pemakaian}}</td>
                    <td class="angka">{{number_format($transaksi->tarif_per_meter,0,",",".")}}</td>
                    <td class="angka">{{number_format($transaksi->total_pemakaian*$transaksi->tarif_per_meter,0,",",".")}}</td>
                </tr>
                <tr>
                    <td>BIAYA PEMELIHARAAN</td>
                    <td class="angka">1</td>
                    <td class="angka">{{number_format($transaksi->biaya_pemeliharaan,0,",",".")}}</td>
                    <td class="angka">{{number_format($transaksi->biaya_pemeliharaan,0,",",".")}}</td>
                </tr>
                <tr>
                    <td>BIAYA ADMINISTRASI</td>
                    <td class="angka">1</td>
                    <td class="angka">{{number_format($transaksi->biaya_administrasi,0,",",".")}}</td>
                    <td class="angka">{{number_format($transaksi->biaya_administrasi,0,",",".")}}</td>
                </tr>
            </table>
            <p class="garis">=============================</p>
        </div>
        <div class="invoice-total">
            <table width="100%">
                <tr>
                    <td>TOTAL TAGIHAN</td>
                    <td class="angka">{{number_format($transaksi->total_tagihan,0,",",".")}}</td>
                </tr>
                <tr>
                    <td>PEMBAYARAN</td>
                    <td class="angka">{{number_format($transaksi->total_tagihan,0,",",".")}}</td>
                </tr>
            </table>
            <p class="garis">=============================</p>
        </div>

        <p class="timestamp">PETUGAS : {{Str::upper($petugas)}}</p>
        <p class="timestamp">DICETAK : {{Str::upper($waktu_cetak)}}</p>
        <p class="garis">=============================</p>

        <div class="thank-you">
            <p>===TERIMA KASIH===</p>
            <p>{{$setting_global->nama}}</p>
            <p>{{$setting_global->alamat}}</p>
        </div>
    </div>
    @if($index < $countdata) <div class="page-break">
        </div>
        @endif
        @endforeach
</body>

</html>