<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Faktur Pesanan</title>
    <style>
        body { font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; font-size: 14px; color: #333; }
        .header { border-bottom: 2px solid #eee; padding-bottom: 20px; margin-bottom: 30px; }
        .title { font-size: 24px; font-weight: bold; color: #111; }
        .info-table { width: 100%; margin-bottom: 40px; }
        .info-table td { vertical-align: top; width: 50%; }
        .info-label { font-weight: bold; color: #666; margin-bottom: 5px; font-size: 12px; text-transform: uppercase; }
        
        .items-table { width: 100%; border-collapse: collapse; margin-bottom: 40px; }
        .items-table th { text-align: left; padding: 12px; border-bottom: 2px solid #333; font-size: 13px; color: #111; }
        .items-table td { padding: 12px; border-bottom: 1px solid #eee; }
        
        .text-right { text-align: right; }
        .footer { font-size: 12px; color: #888; text-align: center; border-top: 1px solid #eee; padding-top: 20px; }
    </style>
</head>
<body>

    <div class="header">
        <div class="title">INVOICE SUMMARY</div>
        <div style="color: #666; margin-top: 5px;">Date: {{ \Carbon\Carbon::now()->format('Y-m-d') }}</div>
    </div>

    <table class="info-table">
        <tr>
            <td>
                <div class="info-label">Billed to:</div>
                <div style="font-weight: bold; font-size: 16px;">{{ $user->nama ?? $user->username ?? 'Customer' }}</div>
                <div>{{ $user->email ?? 'Email tidak tersedia' }}</div>
                <div style="margin-top: 15px;">
                    @php
                        // Menentukan periode dinamis berdasarkan rentang data pemesanan
                        $periodeTampil = $periode ?? \Carbon\Carbon::now()->format('F Y');
                        
                        if(isset($pemesanans) && $pemesanans->count() > 0) {
                            $minDate = \Carbon\Carbon::parse($pemesanans->min('waktu_pemesanan'));
                            $maxDate = \Carbon\Carbon::parse($pemesanans->max('waktu_pemesanan'));
                            
                            if($minDate->format('Y-m') === $maxDate->format('Y-m')) {
                                $periodeTampil = $minDate->format('F Y'); // Jika bulan sama
                            } else {
                                $periodeTampil = $minDate->format('M Y') . ' - ' . $maxDate->format('M Y'); // Jika beda bulan
                            }
                        }
                    @endphp
                    <span class="info-label">Invoice period:</span> {{ $periodeTampil }}
                </div>
            </td>
            <td>
                <div class="info-label">From:</div>
                <div style="font-weight: bold;">Moriarty Marketplace Gaming Digital</div>
                <div>sirmoriarty@moriarty.gg</div>
                <div>Platform Transaksi Terpercaya</div>
            </td>
        </tr>
    </table>

    <table class="items-table">
        <thead>
            <tr>
                <th>Service / Item</th>
                <th>Status</th>
                <th class="text-right">Total Order Value</th>
            </tr>
        </thead>
        <tbody>
            @php $totalSemua = 0; @endphp
            @forelse($pemesanans as $item)
                @php 
                    $harga = $item->pembayaran->harga ?? 0; 
                    $totalSemua += $harga;
                @endphp
                <tr>
                    <td>
                        <div style="font-weight: bold;">{{ $item->penawaran->judul ?? 'Pembelian Akun Game' }}</div>
                        <div style="font-size: 12px; color: #666;">
                            ID: PSN-{{ $item->id_pemesanan }} | {{ \Carbon\Carbon::parse($item->waktu_pemesanan)->format('d M Y') }}
                        </div>
                    </td>
                    <td>
                        <span style="text-transform: capitalize;">{{ $item->status ?? 'Pending' }}</span>
                    </td>
                    <td class="text-right">
                        Rp {{ number_format($harga, 0, ',', '.') }}
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="3" style="text-align: center; color: #888;">Tidak ada riwayat transaksi untuk periode/filter ini.</td>
                </tr>
            @endforelse
        </tbody>
        <tfoot>
            <tr>
                <th colspan="2" class="text-right" style="border-bottom: none; padding-top: 20px;">TOTAL AMOUNT</th>
                <th class="text-right" style="border-bottom: none; font-size: 18px; padding-top: 20px;">
                    Rp {{ number_format($totalSemua, 0, ',', '.') }}
                </th>
            </tr>
        </tfoot>
    </table>

    <div class="footer">
        Marketplace Gaming Digital<br>
        <i>Terima kasih atas kepercayaan Anda bertransaksi di platform kami.</i>
    </div>

</body>
</html>