<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Pendapatan Platform</title>
    <style>
        body { font-family: sans-serif; font-size: 13px; color: #333; }
        .header { text-align: center; margin-bottom: 25px; border-bottom: 2px solid #222; padding-bottom: 15px; }
        .header h2 { margin: 0; padding: 0; font-size: 22px; color: #111; text-transform: uppercase; }
        .header p { margin: 5px 0 0 0; color: #555; }
        .summary-box { width: 100%; margin-bottom: 20px; border-collapse: collapse; }
        .summary-box td { padding: 10px; border: 1px solid #ccc; background-color: #f8f9fa; font-weight: bold; font-size: 14px; }
        table.data-table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        table.data-table th, table.data-table td { border: 1px solid #aaa; padding: 8px; }
        table.data-table th { background-color: #2c3e50; color: #ffffff; text-align: left; }
        table.data-table tr:nth-child(even) { background-color: #f2f2f2; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .text-success { color: #16a085; font-weight: bold; }
    </style>
</head>
<body>

    <div class="header">
        <h2>Moriarty - Laporan Pendapatan Platform</h2>
        <p>
            Periode: 
            {{-- Mengubah logika menjadi pengecekan $periode (Y-m) --}}
            @if(isset($periode) && $periode)
                {{ \Carbon\Carbon::parse($periode . '-01')->translatedFormat('F Y') }}
            @else
                Semua Waktu
            @endif
        </p>
    </div>

    <table class="summary-box">
        <tr>
            <td>Total Transaksi Sukses: {{ number_format($totalTransaksiSukses ?? 0, 0, ',', '.') }} Transaksi</td>
            <td>Total Pendapatan (Pajak): IDR {{ number_format($totalPendapatan ?? 0, 0, ',', '.') }}</td>
        </tr>
    </table>

    <table class="data-table">
        <thead>
            <tr>
                <th class="text-center" width="5%">No.</th>
                <th width="15%">Kode Pesanan</th>
                <th width="15%">Pembeli</th>
                <th width="15%">Penjual</th>
                <th class="text-center" width="15%">Waktu Selesai</th>
                <th class="text-right" width="15%">Nilai Transaksi</th>
                <th class="text-right" width="20%">Pajak Platform (5%)</th>
            </tr>
        </thead>
        <tbody>
            @forelse($pemesanans as $index => $item)
            <tr>
                <td class="text-center">{{ $index + 1 }}</td>
                <td>{{ \Vinkla\Hashids\Facades\Hashids::encode($item->id_pemesanan) }}</td>
                <td>{{ $item->pengguna->username ?? '-' }}</td>
                <td>{{ $item->penawaran->pengguna->username ?? '-' }}</td>
                <td class="text-center">{{ $item->waktu_pemesanan ? \Carbon\Carbon::parse($item->waktu_pemesanan)->format('d/m/Y H:i') : '-' }}</td>
                <td class="text-right">IDR {{ number_format($item->pembayaran->harga ?? 0, 0, ',', '.') }}</td>
                <td class="text-right text-success">+ IDR {{ number_format(($item->pembayaran->harga ?? 0) * 0.1, 0, ',', '.') }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="7" class="text-center" style="padding: 30px;">Tidak ada transaksi pada bulan ini.</td>
            </tr>
            @endforelse
        </tbody>
    </table>

</body>
</html>