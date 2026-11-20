<?php 
namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Pemesanan;
use Carbon\Carbon;

class BersihkanPesananKadaluarsa extends Command
{
    // Nama perintah yang akan dijalankan
    protected $signature = 'pesanan:bersihkan';

    // Deskripsi perintah
    protected $description = 'Menghapus pemesanan dan pembayaran yang belum lunas melebihi 24 jam';

    public function handle()
    {
        // 1. Tetapkan batas waktu 24 jam
        $batasWaktu = Carbon::now()->subHours(24);

        // 2. GANTI 'waktu_pemesanan' menjadi 'created_at' agar lebih akurat sejak data dibuat
        $pemesanans = Pemesanan::where('waktu_pemesanan', '<', $batasWaktu)
            ->where(function($query) {
                // Hapus jika tidak ada pembayaran sama sekali
                $query->whereDoesntHave('pembayaran')
                      // ATAU hapus jika ada pembayaran tapi statusnya BUKAN lunas
                      ->orWhereHas('pembayaran', function($q) {
                          // Tambahkan semua kemungkinan kata "lunas" di database Anda ke dalam array ini
                          $q->whereNotIn('status', ['lunas', 'completed', 'sukses', 'success', 'settlement', 'berhasil']);
                      });
            })->get();

        $jumlahDihapus = 0;

        foreach ($pemesanans as $pesanan) {
            // Hapus pembayaran dulu agar tidak kena error foreign key
            if ($pesanan->pembayaran) {
                $pesanan->pembayaran->delete();
            }
            
            // Hapus pemesanannya
            $pesanan->delete();
            $jumlahDihapus++;
        }

        $this->info("Berhasil menghapus {$jumlahDihapus} pesanan kadaluarsa.");
    }
}