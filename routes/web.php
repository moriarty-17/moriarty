<?php

use App\Http\Controllers\BackendController;
use App\Http\Controllers\PembayaranController;
use App\Http\Controllers\PenawaranController;
use App\Http\Controllers\PenggunaController;
use App\Http\Controllers\PencairanController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ReviewController;


Route::get('/', function () {
    return view('general.index');
});

// ... rute frontend lainnya ...

// KELOMPOK ROUTE BACKEND (HANYA BISA DIAKSES ADMIN)
Route::middleware(['auth', 'admin'])->group(function () {
    // Pastikan letaknya di dalam grup route admin
    Route::get('/be/penawaran/{id}/detail', [BackendController::class, 'detailPenawaran'])->name('admin.penawaran.detail');
    Route::get('/be/dashboard', [BackendController::class, 'index']);
    Route::get('/be/pengguna', [BackendController::class, 'pengguna']);
    Route::get('/be/laporan', [BackendController::class, 'laporan']);
    Route::get('/be/penawaran', [BackendController::class, 'penawaran']);
    Route::get('/be/penawaran_gambar', [BackendController::class, 'penawaran_gambar']);
    Route::get('/be/pembayaran', [BackendController::class, 'pembayaran']);
    Route::get('/be/pemesanan', [BackendController::class, 'pemesanan']);
    Route::get('/be/pencairan', [BackendController::class, 'pencairan']);
    Route::post('/admin/pengguna/toggle-ban/{id}', [\App\Http\Controllers\BackendController::class, 'toggleBan']);
     Route::get('/unduh-faktur-pendapatan', [PembayaranController::class, 'laporanWebsite'])->name('admin.laporan');
});

// Penawaran
Route::get('/penawaran', [PenawaranController::class, 'index']);
Route::get('/penawaran/{id_penawaran}', [PenawaranController::class, 'detail_penawaran'])->name('penawaran.detail_penawaran');

// Autentikasi Pengguna
Route::get('/sign-in', [PenggunaController::class, 'index']);
Route::get('/sign-up', [PenggunaController::class, 'sign_up']);
Route::post('/simpan_pengguna', [PenggunaController::class, 'store']);
Route::post('/login', [PenggunaController::class, 'login'])->name('login');
Route::post('/midtrans-callback', [PembayaranController::class, 'callback']);


// User Login Protected
Route::middleware(['auth'])->group(function () {
    Route::get('/pemesanan', [PembayaranController::class, 'pemesanan'])->name('pemesanan.index');
    Route::get('/riwayat-pemesanan', [PembayaranController::class, 'pemesanan']);
    Route::post('/pemesanan/konfirmasi/{id}', [PembayaranController::class, 'konfirmasiPesanan'])->name('pemesanan.konfirmasi');
    Route::post('/pembayaran/sukses-snap/{kode}', [PembayaranController::class, 'suksesSnapAjax']);
    Route::post('/bayar', [PembayaranController::class, 'checkout'])->name('pembayaran.checkout');
    Route::get('/review/{id_pemesanan}', [ReviewController::class, 'create'])->name('general.review');
    Route::post('/review/{id_pemesanan}', [ReviewController::class, 'store'])->name('general.review.store');
    Route::get('/unduh-faktur/{kode_pembayaran}', [PembayaranController::class, 'unduhFakturSatuan']);
    Route::post('/logout', [PenggunaController::class, 'logout'])->name('logout');
    // Pembayaran & Midtrans
    Route::get('/pembayaran/{kode_pembayaran}/bayar', [App\Http\Controllers\PembayaranController::class, 'bayar'])->name('pembayaran.bayar');
    Route::get('/pembayaran/invoice/{id}', [PembayaranController::class, 'invoice'])->name('pembayaran.invoice');
    Route::get('/pembayaran/{id_penawaran}', [PembayaranController::class, 'index']);

    // PayPal Callback Routes
    Route::get('/paypal/success', [PembayaranController::class, 'paypalSuccess'])->name('paypal.success');
    Route::get('/paypal/cancel', [PembayaranController::class, 'paypalCancel'])->name('paypal.cancel');
    Route::get('/pemesanan/faktur/download', [PembayaranController::class, 'downloadFaktur'])->name('pemesanan.faktur.download');
});

// Payout / Withdraw Seller
Route::middleware(['auth', 'seller'])->group(function () {
    Route::get('/seller/penawaran/{id}/edit', [PembayaranController::class, 'editPenawaran'])->name('seller.penawaran.edit');
    Route::put('/seller/penawaran/{id}', [PembayaranController::class, 'updatePenawaran'])->name('seller.penawaran.update');
    Route::post('/seller/penawaran/{id}/jeda', [PembayaranController::class, 'toggleJedaPenawaran'])->name('seller.penawaran.jeda');
    Route::delete('/seller/penawaran/{id}', [PembayaranController::class, 'hapusPenawaran'])->name('seller.penawaran.hapus');
    Route::get('/seller-dashboard', [PembayaranController::class, 'sellerDashboard'])->name('seller.dashboard');
    Route::get('/seller/withdraw', [PencairanController::class, 'indexWithdraw'])->name('seller.withdraw');
    Route::post('/seller/withdraw/proses', [PencairanController::class, 'prosesPencairanOtomatis'])->name('seller.withdraw.proses');
    Route::get('/buat-penawaran', [PenawaranController::class, 'buat_penawaran']);
    Route::post('/simpan_penawaran', [PenawaranController::class, 'store']);
    Route::get('/seller/faktur/download', [PembayaranController::class, 'downloadFakturSeller'])->name('seller.faktur.download');
});

Route::middleware(['auth'])->group(function () {
    // ... route lainnya
    Route::get('/profil', [App\Http\Controllers\ProfilController::class, 'index'])->name('profil.index');
    Route::get('/review/{hash_id}', [App\Http\Controllers\ReviewController::class, 'create'])->name('general.review');
    Route::put('/profil/update', [App\Http\Controllers\ProfilController::class, 'update'])->name('profil.update');
});
Route::get('/seller/{hash_id}', [PenggunaController::class, 'show'])->name('profil.seller');
Route::get('/cari-penjual', [PenggunaController::class, 'cariPenjual']);
Route::get('/api/cari-seller-live', [PenggunaController::class, 'cariSellerLive']);
