<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;
use Illuminate\Support\Facades\DB;
use App\Models\Pemesanan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();

// =========================================================================
// CRON JOB: OTOMATIS KONFIRMASI PESANAN SETELAH 3 HARI
// =========================================================================
Schedule::call(function () {
    
    // Cari waktu batas (3 hari yang lalu dari sekarang)
    $batasWaktu = now()->subDays(3);

    // Ambil pesanan yang belum selesai/batal dan usianya sudah melewati 3 hari
    $pesananExpired = Pemesanan::with('penawaran.Pengguna')
        ->whereNotIn('status', ['selesai', 'completed', 'batal', 'failed'])
        ->where('waktu_pemesanan', '<=', $batasWaktu) 
        ->get();

    foreach ($pesananExpired as $pemesanan) {
        DB::transaction(function () use ($pemesanan) {
            
            // 1. Ubah status pesanan menjadi selesai
            $pemesanan->status = 'selesai';
            $pemesanan->save();

            // 2. Tambah saldo ke Penjual
            if ($pemesanan->penawaran && $pemesanan->penawaran->Pengguna) {
                $seller = $pemesanan->penawaran->Pengguna;
                $pendapatanSeller = $pemesanan->penawaran->harga;
                
                $seller->increment('saldo', $pendapatanSeller);
            }

        });
    }

})->everyMinute();

Schedule::command('pesanan:bersihkan')->hourly();