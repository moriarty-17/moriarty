<?php

namespace App\Http\Controllers;

use App\Models\Pemesanan;
use App\Models\Ulasan;
use Illuminate\Http\Request;
use Vinkla\Hashids\Facades\Hashids;

class ReviewController extends Controller
{
    public function create($hash_id)
    {
        // 1. Decode Hash ID
        $id_pemesanan = Hashids::decode($hash_id)[0] ?? null;
        if (!$id_pemesanan) abort(404);

        // 2. Ambil data pesanan
        $pemesanan = Pemesanan::with('penawaran.gambarPenawaran')->findOrFail($id_pemesanan);

        // 3. VALIDASI KEAMANAN (PERBAIKAN NAMA KOLOM)
        // Pastikan menggunakan 'id_user' (atau sesuaikan dengan nama kolom foreign key user di tabel pemesanan)
        $userId = auth()->user()->id ?? auth()->user()->id_pengguna ?? auth()->id();
        
        if ($pemesanan->id_user != $userId || strtolower($pemesanan->status) != 'selesai') {
            // Jika Anda ingin melihat kenapa gagal, aktifkan (uncomment) fitur dd() di bawah ini untuk debug sementara:
            // dd('Gagal Validasi:', 'ID User di Pesanan = ' . $pemesanan->id_user, 'ID Login = ' . $userId, 'Status = ' . $pemesanan->status);
            
            return redirect()->route('pemesanan.index')->with('error', 'Pesanan tidak valid untuk di-review.');
        }

        // 4. Cek apakah sudah pernah direview
        $sudahReview = Ulasan::where('id_pemesanan', $id_pemesanan)->exists();
        if ($sudahReview) {
            return redirect()->route('pemesanan.index')->with('info', 'Anda sudah memberikan ulasan untuk pesanan ini.');
        }

        // 5. Lolos validasi, arahkan ke form review
        // Pastikan nama file blade-nya benar (resources/views/review/create.blade.php)
        return view('general.review', compact('pemesanan')); 
    }

   public function store(Request $request, $hash_id)
    {
        // 1. Validasi Input dari Form
        $request->validate([
            'rating'   => 'required|integer|min:1|max:5',
            'komentar' => 'nullable|string|max:1000',
        ]);

        // 2. Decode Hash ID Pemesanan
        $id_pemesanan = Hashids::decode($hash_id)[0] ?? null;
        if (!$id_pemesanan) abort(404);

        // 3. Ambil data pesanan beserta penawarannya (untuk mendapatkan ID Penjual)
        $pemesanan = Pemesanan::with('penawaran')->findOrFail($id_pemesanan);
        $userId = auth()->user()->id ?? auth()->user()->id_pengguna ?? auth()->id();

        // 4. Validasi Keamanan (Pastikan pembeli yang sah dan pesanan sudah selesai)
        if ($pemesanan->id_user != $userId || strtolower($pemesanan->status) != 'selesai') {
            return redirect()->route('pemesanan.index')->with('error', 'Akses ditolak. Anda tidak bisa mereview pesanan ini.');
        }

        // Cek apakah sudah pernah direview (mencegah double submit)
        if (Ulasan::where('id_pemesanan', $id_pemesanan)->exists()) {
            return redirect()->route('pemesanan.index')->with('info', 'Ulasan sudah pernah diberikan.');
        }

        // 5. Tentukan ID Penjual (Diambil dari relasi penawaran)
        // Sesuaikan 'id_user' dengan nama kolom foreign key penjual di tabel penawaran Anda
        $id_penjual = $pemesanan->penawaran->id_user ?? $pemesanan->penawaran->id_pengguna;

        if (!$id_penjual) {
            return redirect()->back()->with('error', 'Data penjual tidak ditemukan.');
        }

        // 6. Simpan Data Ulasan ke Database
        Ulasan::create([
            'id_pemesanan' => $id_pemesanan,
            'id_pembeli'   => $userId,
            'id_penjual'   => $id_penjual,
            'rating'       => $request->rating,
            'komentar'     => $request->komentar,
        ]);

        // 7. Redirect ke halaman Profil Seller di Tab 'ulasan'
        return redirect()->route('profil.seller', [
            'hash_id' => \Vinkla\Hashids\Facades\Hashids::encode($id_penjual),
            'tab'     => 'ulasan'
        ])->with('success', 'Terima kasih! Ulasan Anda berhasil dikirim.');
    }
}