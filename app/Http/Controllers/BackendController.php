<?php

namespace App\Http\Controllers;

use App\Models\Pengguna;
use Illuminate\Http\Request;

class BackendController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $totalPengguna   = \App\Models\Pengguna::count();
        $totalPemesanan  = \App\Models\Pemesanan::count();
        $totalPenawaran  = \App\Models\Penawaran::count();
        $totalPencairan  = \App\Models\Pencairan::count();
        $totalPembayaran = \App\Models\Pembayaran::where('status', 'completed')->count();
        $totalPendapatan = \App\Models\Pembayaran::where('status', 'completed')->sum('harga');

        // Ambil data untuk tabel utama (misal: pemesanan terbaru atau pencairan)
        $pemesanans = \App\Models\Pemesanan::with(['pengguna', 'penawaran.pengguna', 'pembayaran'])
            ->latest('waktu_pemesanan')
            ->paginate(10);

        return view('admin.dashboard', compact(
            'totalPengguna',
            'totalPemesanan',
            'totalPenawaran',
            'totalPencairan',
            'totalPendapatan',
            'totalPembayaran',
            'pemesanans'
        ));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function pengguna()
    {
        // Menggunakan orderBy pada kolom primary key (ID) sebagai pengganti latest()
        // Jika nama kolom ID di database Anda adalah 'id', ubah 'id_pengguna' menjadi 'id'
        $data['pengguna'] = Pengguna::orderBy('id_pengguna', 'desc')->paginate(10);
        
        return view('admin.user', $data);
    }
    public function penawaran()
    {
        $penawarans = \App\Models\Penawaran::with('Pengguna')
            ->orderBy('id_penawaran', 'desc')
            ->paginate(10); // Menampilkan 10 baris per halaman

        return view('admin.penawaran', compact('penawarans'));
    }
    public function pembayaran()
    {
        $pembayarans = \App\Models\Pembayaran::with('pemesanan.Pengguna')
            ->orderBy('id_pembayaran', 'desc')
            ->paginate(10);

        return view('admin.pembayaran', compact('pembayarans'));
    }
    public function pemesanan()
    {
        $pemesanans = \App\Models\Pemesanan::with(['Pengguna', 'penawaran.Pengguna', 'pembayaran'])
            ->orderBy('id_pemesanan', 'desc')
            ->paginate(10);

        return view('admin.pemesanan', compact('pemesanans'));
    }
    public function pencairan()
    {
        $pencairans = \App\Models\Pencairan::with('Pengguna')
            ->orderBy('id_pencairan', 'desc')
            ->paginate(10);

        return view('admin.pencairan', compact('pencairans'));
    }
    public function penawaran_gambar()
    {
        return view('admin.penawaran_gambar');
    }

    public function detailPenawaran($hashed_id)
    {
        // Dekripsi ID menggunakan Hashids
        $decoded = \Vinkla\Hashids\Facades\Hashids::decode($hashed_id);

        if (empty($decoded)) {
            abort(404, 'Penawaran tidak ditemukan.');
        }

        $idAsli = $decoded[0];

        // Ambil data penawaran beserta relasi Penjual dan Gambar
        $penawaran = \App\Models\Penawaran::with(['Pengguna', 'gambarPenawaran'])->findOrFail($idAsli);

        return view('admin.detail_penawaran', compact('penawaran'));
    }

    public function laporan(Request $request)
    {
        // 1. DATA UNTUK TABEL PENDAPATAN (Filter Status: Selesai)
        $queryPesanan = \App\Models\Pemesanan::with(['penawaran', 'pembayaran', 'pengguna'])
            ->where('status', 'selesai');

        // Filter berdasarkan Tanggal Mulai
        if ($request->filled('start_date')) {
            $queryPesanan->where('waktu_pemesanan', '>=', $request->start_date . ' 00:00:00');
        }

        // Filter berdasarkan Tanggal Akhir
        if ($request->filled('end_date')) {
            $queryPesanan->where('waktu_pemesanan', '<=', $request->end_date . ' 23:59:59');
        }

        // 2. DATA UNTUK SUMMARY CARDS
        $summaryQuery = clone $queryPesanan;
        $totalTransaksiSukses = $summaryQuery->count();
        $totalPendapatan = $summaryQuery->get()->sum(function ($item) {
            return ($item->pembayaran->harga ?? 0) * 0.1;
        });

        // =========================================================
        // FITUR BARU: EKSPOR LAPORAN KE PDF
        // =========================================================
        if ($request->query('export') === 'pdf') {
            // Ambil semua data pesanan tanpa dibatasi pagination (get all)
            $pemesanansPdf = $summaryQuery->orderBy('waktu_pemesanan', 'desc')->get();

            // Generate PDF menggunakan package Laravel DomPDF (Pastikan sudah terinstall)
            $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('admin.laporan_pdf', [
                'pemesanans'           => $pemesanansPdf,
                'totalTransaksiSukses' => $totalTransaksiSukses,
                'totalPendapatan'      => $totalPendapatan,
                'start_date'           => $request->start_date,
                'end_date'             => $request->end_date,
            ]);

            // Mengatur ukuran kertas jadi A4 landscape agar tabel lega
            $pdf->setPaper('A4', 'landscape');

            // Kembalikan sebagai unduhan
            return $pdf->download('Laporan_Pendapatan_Moriarty_' . date('Y-m-d') . '.pdf');
        }
        // =========================================================

        // 3. PAGINATION & URUTAN UNTUK TABEL BROWSER
        $queryPesanan->orderBy('waktu_pemesanan', 'desc');
        $pesanans = $queryPesanan->paginate(10)->appends($request->query());

        // 4. RETURN KE VIEW
        return view('admin.laporan', [
            'pemesanans'           => $pesanans,
            'totalTransaksiSukses' => $totalTransaksiSukses,
            'totalPendapatan'      => $totalPendapatan
        ]);
    }

    public function toggleBan($id)
    {
        // Cari data pengguna
        $pengguna = \App\Models\Pengguna::findOrFail($id);

        // Cek status saat ini dan balikkan nilainya
        if (strtolower($pengguna->status) === 'banned') {
            $pengguna->status = 'aktif';
            $pesan = 'Pengguna berhasil di-unban dan dapat login kembali.';
        } else {
            $pengguna->status = 'banned';
            $pesan = 'Pengguna berhasil di-banned!';
        }

        $pengguna->save();

        return back()->with('success', $pesan);
    }



    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
