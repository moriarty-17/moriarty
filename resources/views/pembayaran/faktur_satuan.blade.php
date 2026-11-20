<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Nota Pesanan - {{ $pemesanan->kode_pembayaran }}</title>
    <style>
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            font-size: 12px;
            color: #333;
            margin: 0;
            padding: 20px;
        }
        .header {
            border-bottom: 2px solid #222;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }
        .header h1 {
            font-size: 20px;
            margin: 0;
            text-transform: uppercase;
        }
        .header .logo {
            font-weight: bold;
            font-size: 16px;
            color: #ff5722; /* Warna khas ala e-commerce, bisa diganti brand Moriarty */
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .info-table td {
            padding: 4px 0;
            vertical-align: top;
        }
        .info-label {
            font-weight: bold;
            width: 150px;
        }
        .details-table th, .details-table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        .details-table th {
            background-color: #f9f9f9;
            font-weight: bold;
        }
        .details-table .right {
            text-align: right;
        }
        .details-table .center {
            text-align: center;
        }
        .summary-box {
            width: 50%;
            float: right;
        }
        .summary-table td {
            padding: 4px 8px;
            border: none;
        }
        .summary-table .total-row {
            font-size: 14px;
            font-weight: bold;
            border-top: 1px solid #333;
        }
        .footer {
            clear: both;
            margin-top: 50px;
            text-align: center;
            font-size: 10px;
            color: #777;
        }
        .clearfix::after {
            content: "";
            clear: both;
            display: table;
        }
    </style>
</head>
<body>

    <div class="header">
        <h1>Nota Pesanan</h1>
        <div class="logo">Moriarty Marketplace</div>
    </div>

    <!-- Informasi Pembeli & Penjual -->
    <table class="info-table">
        <tr>
            <td class="info-label">Nama Pembeli</td>
            <td>: {{ auth()->user()->username ?? 'Buyer' }}</td>
        </tr>
        <tr>
            <td class="info-label">Email Pembeli</td>
            <td>: {{ auth()->user()->email ?? '-' }}</td>
        </tr>
        <tr>
            <td class="info-label">Nama Penjual</td>
            <td>: {{ $pemesanan->penawaran->Pengguna->username ?? 'Seller' }}</td>
        </tr>
    </table>

    <!-- Rincian Status Pesanan -->
    <table class="details-table">
        <tr>
            <th>No. Pesanan</th>
            <th>Waktu Pembayaran</th>
            <th>Metode Pembayaran</th>
            <th>Status</th>
        </tr>
        <tr>
            <td>{{ $pemesanan->kode_pembayaran }}</td>
            <td>{{ \Carbon\Carbon::parse($pemesanan->waktu_pembayaran)->format('d/m/y H:i') }}</td>
            <td>{{ strtoupper($pemesanan->metode_pembayaran) }}</td>
            <td>{{ ucfirst($pemesanan->status) }}</td>
        </tr>
    </table>

    <h3 style="margin-top: 30px; font-size: 14px;">Rincian Pesanan</h3>
    
    <!-- Tabel Produk -->
    <table class="details-table">
        <tr>
            <th width="5%" class="center">No.</th>
            <th width="45%">Produk</th>
            <th width="15%">Kategori</th>
            <th width="15%" class="right">Harga Produk</th>
            <th width="5%" class="center">Kuantitas</th>
            <th width="15%" class="right">Subtotal</th>
        </tr>
        <tr>
            <td class="center">1</td>
            <td>{{ $pemesanan->penawaran->judul ?? 'Judul Penawaran' }}</td>
            <td>{{ $pemesanan->penawaran->jenis ?? '-' }}</td>
            <td class="right">Rp{{ number_format($pemesanan->penawaran->harga, 0, ',', '.') }}</td>
            <td class="center">1</td>
            <td class="right">Rp{{ number_format($pemesanan->penawaran->harga, 0, ',', '.') }}</td>
        </tr>
    </table>

    <!-- Ringkasan Total -->
    @php
        $hargaAsli = (float) ($pemesanan->penawaran->harga ?? 0);
        $pajak = $hargaAsli * 0.10;
        $total = $hargaAsli + $pajak;
    @endphp

    <div class="clearfix">
        <div class="summary-box">
            <table class="summary-table" style="width: 100%;">
                <tr>
                    <td>Total Kuantitas</td>
                    <td class="right">1 produk</td>
                </tr>
                <tr>
                    <td>Subtotal untuk Produk</td>
                    <td class="right">Rp{{ number_format($hargaAsli, 0, ',', '.') }}</td>
                </tr>
                <tr>
                    <td>Pajak / Biaya Layanan (10%)</td>
                    <td class="right">Rp{{ number_format($pajak, 0, ',', '.') }}</td>
                </tr>
                <tr class="total-row">
                    <td>Total Pembayaran</td>
                    <td class="right">Rp{{ number_format($total, 0, ',', '.') }}</td>
                </tr>
            </table>
        </div>
    </div>

    <div class="footer">
        Faktur ini sah dan diterbitkan secara otomatis oleh sistem Moriarty.<br>
        End of receipt.
    </div>

</body>
</html>