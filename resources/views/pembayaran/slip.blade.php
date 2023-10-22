<!DOCTYPE html>
<html>

<head>
    <title>{{$title}}</title>
    <style>
        @page {
            size: 80mm 140mm;
            /* Lebar 8 cm, tinggi sesuai kebutuhan */
            margin: 1mm;
            /* Margin diatur sesuai kebutuhan */
        }

        body {
            font-family: Arial, sans-serif;
        }

        .invoice {
            width: 72mm;
            /* Lebar kertas 8 cm */
            margin: 0 auto;
            padding: 8px;
            /* border: 1px solid #000; */
        }

        .invoice-header {
            text-align: center;
        }

        .invoice-header p {
            font-size: 13px;
            margin: 0px;
            padding: 0px;
            font-weight: bolder;
        }

        .invoice-info {
            margin-top: 13px;
        }

        .invoice-info td {
            font-size: 12px;
        }

        .invoice-info p {
            padding: 0px;
            margin: 0px;
        }

        .invoice-detail {
            margin-top: 0px;
        }

        .invoice-detail td {
            font-size: 12px;
        }

        .invoice-detail p {
            padding: 0px;
            margin: 0px;
        }

        .invoice-total {
            margin-top: 0px;
        }

        .invoice-total td {
            font-size: 12px;
        }

        .invoice-total p {
            padding: 0px;
            margin: 0px;
        }

        .timestamp {
            text-align: center;
            margin: 0px;
            font-size: 11px;
            padding: 0px;
        }

        .thank-you {
            margin-top: 6px;
            font-size: 13px;
            text-align: center;
        }

        .thank-you p {
            padding: 2px;
            margin: 0px;
        }

        .garis {
            font-size: 16px;
            text-align: center;
            padding: 0px;
            margin: 0px;
        }

        table tr td.angka {
            text-align: right;
            padding-right: 0px;
            margin-right: 0px;
        }
    </style>
</head>

<body>
    <div class="invoice">
        <div class="invoice-header">
            <p>PAMDES INDONESIA RAYA</p>
            <p>JL. SURABAYA TIMUR NOMOR 9 SURABAYA</p>
            <p>WA/TELP : 085232658965</p>
            <p>===================================</p>
            <p>BUKTI PEMBAYARAN AIR</p>
        </div>

        <div class="invoice-info">
            <table>
                <tr>
                    <td>KD. TRANSAKSI</td>
                    <td>:</td>
                    <td>2023030004</td>
                </tr>
                <tr>
                    <td>BULAN</td>
                    <td>:</td>
                    <td>MARET 2023</td>
                </tr>
                <tr>
                    <td>KD. PELANGGAN</td>
                    <td>:</td>
                    <td>PAM0004</td>
                </tr>
                <tr>
                    <td>NAMA</td>
                    <td>:</td>
                    <td>KUKOH SANTOSO</td>
                </tr>
                <tr>
                    <td>TGL. BAYAR</td>
                    <td>:</td>
                    <td>12 JANUARI 2023</td>
                </tr>
            </table>
            <p class="garis">=============================</p>
        </div>

        <div class="invoice-detail">
            <table width="100%">
                <tr>
                    <td>TOTAL PEMAKAIAN</td>
                    <td class="angka">10</td>
                    <td class="angka">4.000</td>
                    <td class="angka">40.000</td>
                </tr>
                <tr>
                    <td>BIAYA PEMELIHARAAN</td>
                    <td class="angka">1</td>
                    <td class="angka">0</td>
                    <td class="angka">0</td>
                </tr>
                <tr>
                    <td>BIAYA ADMINISTRASI</td>
                    <td class="angka">1</td>
                    <td class="angka">0</td>
                    <td class="angka">0</td>
                </tr>
            </table>
            <p class="garis">=============================</p>
        </div>
        <div class="invoice-total">
            <table width="100%">
                <tr>
                    <td>TOTAL TAGIHAN</td>
                    <td class="angka">40.000</td>
                </tr>
                <tr>
                    <td>PEMBAYARAN</td>
                    <td class="angka">40.000</td>
                </tr>
            </table>
            <p class="garis">=============================</p>
        </div>

        <p class="timestamp">PETUGAS : KUKOH SANTOSO | 08 JANUARI 2023 18:00:00 WIB</p>
        <p class="garis">=============================</p>

        <div class="thank-you">
            <p>===TERIMA KASIH===</p>
            <p>PAMDES INDONESIA RAYA</p>
            <p>JL. SURABAYA TIMUR NOMOR 9 SURABAYA</p>
        </div>
    </div>

    <script>
        window.onload = function() {
            window.print(); // Ubah 500 menjadi waktu yang sesuai jika diperlukan
        };
    </script>
</body>

</html>