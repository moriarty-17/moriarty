<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pembayaran;
use App\Models\Pemesanan;
use App\Models\Penawaran;
use App\Models\Pencairan;
use Illuminate\Pagination\LengthAwarePaginator;
use Vinkla\Hashids\Facades\Hashids;
use Midtrans\Config;
use Midtrans\Snap;
use Illuminate\Support\Facades\DB;
use Srmklive\PayPal\Services\PayPal as PayPalClient;
use Illuminate\Support\Str;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class PembayaranController extends Controller
{
    public function sellerDashboard(Request $request)
    {
        $user = auth()->user();
        $idPengguna = $user->id_pengguna ?? $user->id;

        // --- 1. Data Ringkasan (Summary) dengan Filter Waktu ---
        $queryAktif = \App\Models\Penawaran::where('id_pengguna', $idPengguna)->where('status', 'aktif');
        $queryTerjual = \App\Models\Penawaran::where('id_pengguna', $idPengguna)->where('status', 'sold');

        $filterWaktu = $request->input('waktu', 'semua');

        if ($filterWaktu === '30_hari') {
            $queryAktif->where('created_at', '>=', now()->subDays(30));
            $queryTerjual->where('updated_at', '>=', now()->subDays(30));
        } elseif ($filterWaktu === '90_hari') {
            $queryAktif->where('created_at', '>=', now()->subDays(90));
            $queryTerjual->where('updated_at', '>=', now()->subDays(90));
        } elseif ($filterWaktu === '1_tahun') {
            $queryAktif->where('created_at', '>=', now()->subYear());
            $queryTerjual->where('updated_at', '>=', now()->subYear());
        }

        $penawaranAktif = $queryAktif->count();
        $totalPenjualan = $queryTerjual->count();

        // --- 2. Data Penawaran Saya (Menggunakan Pagination 'hal') ---
        $queryPenawaran = \App\Models\Penawaran::with('gambarPenawaran')->where('id_pengguna', $idPengguna);

        if ($request->filled('status')) {
            $queryPenawaran->where('status', $request->status);
        }
        if ($request->get('sort') == 'terlama') {
            $queryPenawaran->orderBy('id_penawaran', 'asc');
        } else {
            $queryPenawaran->orderBy('id_penawaran', 'desc');
        }

        $penawarans = $queryPenawaran->paginate(10, ['*'], 'hal')->appends($request->query());

        // --- 3. Data Mutasi Wallet ---
        $mutasiWallet = [];

        // Pemasukan dari akun terjual (90%)
        $penjualanSold = \App\Models\Pemesanan::whereHas('penawaran', function ($q) use ($idPengguna) {
            $q->where('id_pengguna', $idPengguna);
        })->with(['penawaran', 'pembayaran'])->get();

        foreach ($penjualanSold as $sold) {
            $hargaKotor = $sold->pembayaran->harga ?? $sold->penawaran->harga ?? 0;
            $mutasiWallet[] = [
                'tanggal'    => $sold->waktu_pemesanan ?? $sold->created_at ?? '-',
                'tipe'       => 'masuk',
                'nominal'    => (float) $hargaKotor * 0.9, // Diperbaiki format perkaliannya
                'keterangan' => $sold->kode_penawaran,
                'status'     => $sold->status ?? 'selesai'
            ];
        }

        // Pengeluaran dari penarikan saldo (Withdraw)
        $pencairans = \Illuminate\Support\Facades\DB::table('pencairan')
            ->where('id_pengguna', $idPengguna)
            ->get();

        foreach ($pencairans as $wd) {
            $mutasiWallet[] = [
                'tanggal'    => $wd->waktu_pencairan ?? '-',
                'tipe'       => 'keluar',
                'nominal'    => (float) $wd->jumlah_pencairan,
                'keterangan' => ($wd->kode_pencairan ?? 'WD-' . $wd->id_pencairan),
                'status'     => $wd->status ?? 'diproses'
            ];
        }

        // Urutkan mutasi dari yang terbaru
        usort($mutasiWallet, function ($a, $b) {
            return strtotime($b['tanggal']) <=> strtotime($a['tanggal']);
        });

        // FITUR FILTER MASUK / KELUAR
        if ($request->filled('tipe')) {
            $mutasiWallet = array_filter($mutasiWallet, function ($item) use ($request) {
                return $item['tipe'] === $request->tipe;
            });
        }

        // MEMBUAT PAGINATION UNTUK ARRAY MUTASI WALLET (Menggunakan 'hal')
        $currentPage = \Illuminate\Pagination\LengthAwarePaginator::resolveCurrentPage('hal');
        $perPage = 10;

        // Ambil sebagian data sesuai halaman aktif (dan re-index array hasil filter)
        $currentItems = array_slice(array_values($mutasiWallet), ($currentPage - 1) * $perPage, $perPage); // Diperbaiki format pengurangannya

        $mutasiPaginated = new \Illuminate\Pagination\LengthAwarePaginator($currentItems, count($mutasiWallet), $perPage, $currentPage, [
            'path' => \Illuminate\Pagination\LengthAwarePaginator::resolveCurrentPath(),
            'pageName' => 'hal',
            'query' => $request->query()
        ]);

        // --- 4. Data Pesanan Masuk (Tab Pesanan) (Menggunakan Pagination 'hal') ---
        // Relasi 'pengguna' langsung dimasukkan di awal agar tidak perlu mendeklarasikan ulang variabel
        $queryPesanan = \App\Models\Pemesanan::with(['penawaran', 'pembayaran', 'pengguna'])
            ->whereHas('penawaran', function ($q) use ($idPengguna) {
                $q->where('id_pengguna', $idPengguna);
            });

        // Filter berdasarkan Start Date dan End Date menggunakan waktu jam spesifik agar presisi
        if ($request->filled('start_date')) {
            $queryPesanan->where('waktu_pemesanan', '>=', $request->start_date . ' 00:00:00');
        }

        if ($request->filled('end_date')) {
            $queryPesanan->where('waktu_pemesanan', '<=', $request->end_date . ' 23:59:59');
        }

        // Urutkan pesanan dari yang paling baru
        $queryPesanan->orderBy('waktu_pemesanan', 'desc');

        $pesanans = $queryPesanan->paginate(10, ['*'], 'hal')->appends($request->query());

        return view('general.seller_dashboard', [
            'penawaranAktif' => $penawaranAktif,
            'totalPenjualan' => $totalPenjualan,
            'penawarans'     => $penawarans,
            'mutasiWallet'   => $mutasiPaginated,
            'pesanans'       => $pesanans
        ]);
    }

    // Menampilkan form edit penawaran
    public function editPenawaran($encodedId)
    {
        $id = \Vinkla\Hashids\Facades\Hashids::decode($encodedId)[0] ?? null;
        $penawaran = \App\Models\Penawaran::findOrFail($id);

        // --- TAMBAHKAN KODE DEBUG INI ---
        $idPemilik = $penawaran->id_pengguna;
        $idLogin = auth()->user()->id_pengguna ?? auth()->id();
        return view('penawaran.edit_penawaran', compact('penawaran'));
    }

    // Memproses update penawaran
    public function updatePenawaran(Request $request, $encodedId)
    {
        $id = \Vinkla\Hashids\Facades\Hashids::decode($encodedId)[0] ?? null;
        $penawaran = \App\Models\Penawaran::findOrFail($id);

        // Validasi dan update data sesuai kebutuhan form edit Anda
        $request->validate([
            'judul' => 'required|string|max:255',
            'harga' => 'required|numeric|min:0',
        ]);

        $penawaran->update([
            'judul' => $request->judul,
            'harga' => $request->harga,
            // tambahkan field lain yang bisa diedit
        ]);

        return redirect()->to('seller-dashboard?page=penawaran')->with('success', 'Penawaran berhasil diperbarui.');
    }

    // Fitur Jeda / Toggle Status (Aktif <-> Dijeda)
    public function toggleJedaPenawaran($encodedId)
    {
        $id = \Vinkla\Hashids\Facades\Hashids::decode($encodedId)[0] ?? null;
        $penawaran = \App\Models\Penawaran::findOrFail($id);

        if ($penawaran->status == 'aktif') {
            $penawaran->update(['status' => 'dijeda']);
            $msg = 'Penawaran berhasil dijeda.';
        } elseif ($penawaran->status == 'dijeda') {
            $penawaran->update(['status' => 'aktif']);
            $msg = 'Penawaran kembali diaktifkan.';
        } else {
            return redirect()->back()->with('error', 'Penawaran dengan status sold tidak dapat diubah.');
        }

        return redirect()->to('seller-dashboard?page=penawaran')->with('success', $msg);
    }

    // Menghapus penawaran
    public function hapusPenawaran($encodedId)
    {
        $id = \Vinkla\Hashids\Facades\Hashids::decode($encodedId)[0] ?? null;
        $penawaran = \App\Models\Penawaran::findOrFail($id);

        // Hapus relasi gambar jika ada, lalu hapus data penawaran
        $penawaran->delete();

        return redirect()->to('seller-dashboard?page=penawaran')->with('success', 'Penawaran berhasil dihapus.');
    }
    // Menampilkan halaman detail penawaran
    public function index($hashed_id)
    {
        // 1. Dekripsi (pecah) ID acak menjadi angka asli
        $decoded = Hashids::decode($hashed_id);

        // 2. Jika URL dimanipulasi atau tidak valid, tampilkan 404
        if (empty($decoded)) {
            abort(404, 'Penawaran tidak valid atau tidak ditemukan.');
        }

        // 3. Ambil ID aslinya (berada di index ke-0)
        $idAsli = $decoded[0];

        // 4. Cari ke database menggunakan ID yang sudah kembali jadi angka
        $penawaran = Penawaran::with([
            'Pengguna',
            'gambarPenawaran' => function ($query) {
                $query->orderBy('id_gambar', 'asc');
            }
        ])->findOrFail($idAsli);

        $user = auth()->user();

        // 5. KEAMANAN: Cegah seller membeli penawarannya sendiri
        if ($user && $penawaran->id_pengguna == ($user->id_pengguna ?? $user->id)) {
            return redirect()->back()->with('error', 'Anda tidak dapat membeli penawaran Anda sendiri.');
        }

        return view('pembayaran.pembayaran', compact('penawaran'));
    }

    public function bayar($kode_pembayaran)
    {
        // Ambil data pembayaran
        $pembayaran = Pembayaran::where('kode_pembayaran', $kode_pembayaran)->firstOrFail();
        $penawaran = Penawaran::where('kode_penawaran', $pembayaran->kode_penawaran)->first();
        $hargaAsli = $penawaran ? $penawaran->harga : 0;
        $totalHarga = $pembayaran->harga;

        // Cegah user masuk ke halaman bayar jika sudah lunas atau batal
        if ($pembayaran->status !== 'pending') {
            return redirect('/riwayat-pemesanan')->with('error', 'Pembayaran ini sudah tidak dapat dilanjutkan (Status: ' . $pembayaran->status . ').');
        }

        // =========================================================================
        // PERBAIKAN: JIKA METODE QRIS (MIDTRANS) & WAKTU SUDAH LEBIH DARI 14 MENIT
        // =========================================================================
        if ($pembayaran->metode_pembayaran == 'qris') {
            $waktuDibuat = \Carbon\Carbon::parse($pembayaran->waktu_pembayaran);

            // Cek apakah usia pembayaran sudah lebih dari 14 menit (mendekati limit QRIS)
            if (now()->diffInMinutes($waktuDibuat) > 14) {

                // 1. Buat kode pembayaran baru untuk menghindari penolakan order_id dari Midtrans
                $kodeBaru = 'PAY-' . time() . '-' . rand(100, 999);

                // 2. Setup konfigurasi Midtrans
                Config::$serverKey = config('midtrans.server_key');
                Config::$isProduction = config('midtrans.is_production');
                Config::$isSanitized = config('midtrans.is_sanitized', true);
                Config::$is3ds = config('midtrans.is_3ds', true);

                $user = auth()->user();
                $params = [
                    'transaction_details' => [
                        'order_id' => $kodeBaru,
                        'gross_amount' => $totalHarga,
                    ],
                    'customer_details' => [
                        'first_name' => $user->username ?? 'Buyer',
                        'email' => $user->email ?? 'buyer@example.com',
                    ],
                    'enabled_payments' => ['gopay', 'qris'],
                    'custom_expiry' => [
                        'start_time' => date("Y-m-d H:i:s O"),
                        'unit'       => 'hour',
                        'duration'   => 24
                    ]
                ];

                try {
                    // 3. Minta token baru
                    $snapTokenBaru = Snap::getSnapToken($params);

                   // 4. Update kode dan token di database secara paksa (Bypass $fillable)
                    \Illuminate\Support\Facades\DB::table('pembayaran')
                        ->where('kode_pembayaran', $kode_pembayaran)
                        ->update([
                            'kode_pembayaran'  => $kodeBaru,
                            'snap_token'       => $snapTokenBaru,
                            'waktu_pembayaran' => \Carbon\Carbon::now(),
                        ]);

                    // 5. Redirect pengguna ke URL yang baru (mengganti kode lama di URL dengan kode baru)
                    $newUrl = str_replace($kode_pembayaran, $kodeBaru, request()->fullUrl());
                    return redirect($newUrl);
                } catch (\Exception $e) {
                    return redirect('/riwayat-pemesanan')->with('error', 'Gagal memuat ulang QRIS Midtrans: ' . $e->getMessage());
                }
            }

            // Jika usianya MASIH DI BAWAH 14 menit, langsung tampilkan token lama
            $snapToken = $pembayaran->snap_token;
            return view('pembayaran.bayar', compact('pembayaran', 'penawaran', 'hargaAsli', 'totalHarga', 'snapToken'));
        }

        // =========================================================================
        // JIKA METODE PEMBAYARAN PAYPAL
        // =========================================================================
        if ($pembayaran->metode_pembayaran == 'paypal') {
            $paypalLink = $pembayaran->paypal_link;

            // (Opsional) Hitung ulang USD untuk paypal jika diperlukan view
            $kurs = config('paypal.exchange_rate', 16000);
            $hargaUSD = number_format($totalHarga / $kurs, 2, '.', '');

            return view('pembayaran.bayar', compact('pembayaran', 'penawaran', 'hargaAsli', 'totalHarga', 'paypalLink', 'hargaUSD'));
        }

        // Kembalikan ke halaman bayar untuk metode di luar sistem di atas (jika ada)
        return view('pembayaran.bayar', compact('pembayaran', 'penawaran', 'hargaAsli', 'totalHarga'));
    }

    public function invoice($hashed_id)
    {
        // 1. Dekripsi ID Invoice
        $decoded = \Vinkla\Hashids\Facades\Hashids::decode($hashed_id);
        if (empty($decoded)) {
            abort(404, 'Invoice tidak valid atau tidak ditemukan.');
        }

        $idAsli = $decoded[0];

        // 2. Lanjutkan query dengan ID asli
        $pemesanan = Pemesanan::with(['penawaran.Pengguna', 'pembayaran'])
            ->where('id_pemesanan', $idAsli)
            ->first();

        if (!$pemesanan) {
            $pemesanan = Pemesanan::with(['penawaran.Pengguna', 'pembayaran'])
                ->where('id_pembayaran', $idAsli)
                ->firstOrFail();
        }

        // KEAMANAN TAMBAHAN: Pastikan yang buka invoice hanya pembeli atau penjualnya
        $user = auth()->user();
        $isPembeli = $pemesanan->id_user == ($user->id_pengguna ?? $user->id);
        $isPenjual = $pemesanan->penawaran->id_pengguna == ($user->id_pengguna ?? $user->id);

        if (!$isPembeli && !$isPenjual && $user->role !== 'admin') {
            abort(403, 'Anda tidak memiliki akses ke invoice ini.');
        }

        return view('pembayaran.invoice_pembayaran', compact('pemesanan'));
    }

    // Fungsi checkout untuk membuat Snap Token & menyimpan data awal ke database
    public function checkout(Request $request)
    {
        $user = auth()->user();
        $penawaran = Penawaran::where('kode_penawaran', $request->kode_penawaran)->firstOrFail();

        // Hitung total harga termasuk fee 10%
        $hargaAsli = (float) $penawaran->harga;
        $totalHarga = (int) ($hargaAsli * 1.10); // Diperbaiki dari "1.10);" menjadi perkalian normal
        $metode = $request->input('metode', 'qris');

        $kodePembayaran = 'PAY-' . time() . '-' . rand(100, 999);

        // ==========================================
        // 1. JIKA METODE PEMBAYARAN ADALAH PAYPAL
        // ==========================================
        if ($metode === 'paypal') {
            // Menggunakan kurs dari config/paypal.php (default: 16000 jika belum diset)
            $kurs = config('paypal.exchange_rate', 16000);
            $hargaUSD = number_format($totalHarga / $kurs, 2, '.', '');

            // Buat record pembayaran awal
            $pembayaran = Pembayaran::create([
                'kode_pembayaran'   => $kodePembayaran,
                'kode_penawaran'    => $penawaran->kode_penawaran,
                'id_pengguna'       => $user->id_pengguna ?? $user->id,
                'harga'             => $totalHarga, // Tetap tersimpan dalam IDR
                'metode_pembayaran' => 'paypal',
                'status'            => 'pending',
                'waktu_pembayaran'  => \DB::raw('NOW()'),
            ]);

            // MEMBUAT DATA PESANAN AWAL (STATUS PENDING)
            Pemesanan::create([
                'id_user'         => $user->id_pengguna ?? $user->id,
                'kode_penawaran'  => $penawaran->kode_penawaran,
                'id_pembayaran'   => $pembayaran->id_pembayaran ?? $pembayaran->id,
                'status'          => 'pending',
                'waktu_pemesanan' => \DB::raw('NOW()'),
            ]);

            $provider = new PayPalClient;
            $provider->setApiCredentials(config('paypal'));
            $provider->getAccessToken();

            $response = $provider->createOrder([
                "intent" => "CAPTURE",
                "application_context" => [
                    "return_url" => route('paypal.success', ['kode' => $kodePembayaran]),
                    "cancel_url" => route('paypal.cancel', ['kode' => $kodePembayaran]),
                ],
                "purchase_units" => [
                    [
                        "reference_id" => $kodePembayaran,
                        "amount" => [
                            "currency_code" => "USD",
                            "value" => (string) $hargaUSD,
                        ]
                    ]
                ]
            ]);

            if (isset($response['id']) && $response['id'] != null) {
                foreach ($response['links'] as $link) {
                    if ($link['rel'] === 'approve') {
                        $paypalLink = $link['href'];

                        // Simpan paypal link ke database agar bisa diakses kembali saat lanjut bayar
                        $pembayaran->update(['paypal_link' => $paypalLink]);

                        return view('pembayaran.bayar', compact('pembayaran', 'penawaran', 'totalHarga', 'paypalLink', 'hargaUSD'));
                    }
                }
            }

            return back()->with('error', 'Gagal menghubungkan ke PayPal: ' . ($response['message'] ?? 'Periksa kredensial.'));
        }

        // ==========================================
        // 2. JIKA METODE PEMBAYARAN ADALAH QRIS (MIDTRANS)
        // ==========================================
        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized = config('midtrans.is_sanitized', true);
        Config::$is3ds = config('midtrans.is_3ds', true);

        $params = [
            'transaction_details' => [
                'order_id' => $kodePembayaran,
                'gross_amount' => $totalHarga,
            ],
            'customer_details' => [
                'first_name' => $user->username ?? 'Buyer',
                'email' => $user->email ?? 'buyer@example.com',
            ],
            'enabled_payments' => ['gopay', 'qris'],
            // ==========================================
            // PENAMBAHAN: Custom Expiry 24 Jam
            // ==========================================
            'custom_expiry' => [
                'start_time' => date("Y-m-d H:i:s O"),
                'unit'       => 'hour',
                'duration'   => 24
            ]
        ];

        try {
            $snapToken = Snap::getSnapToken($params);

            // Simpan snap_token langsung saat Create agar tidak hilang
            $pembayaran = Pembayaran::create([
                'kode_pembayaran'   => $kodePembayaran,
                'kode_penawaran'    => $request->kode_penawaran,
                'id_pengguna'       => $user->id_pengguna ?? $user->id,
                'harga'             => $totalHarga,
                'metode_pembayaran' => 'qris',
                'status'            => 'pending',
                'waktu_pembayaran'  => \DB::raw('NOW()'),
                'snap_token'        => $snapToken, // Simpan token di sini
            ]);

            // MEMBUAT DATA PESANAN AWAL (STATUS PENDING)
            Pemesanan::create([
                'id_user'         => $user->id_pengguna ?? $user->id,
                'kode_penawaran'  => $request->kode_penawaran,
                'id_pembayaran'   => $pembayaran->id_pembayaran ?? $pembayaran->id,
                'status'          => 'pending',
                'waktu_pemesanan' => \DB::raw('NOW()'),
            ]);

            return view('pembayaran.bayar', compact('snapToken', 'pembayaran', 'penawaran', 'totalHarga'));
        } catch (\Exception $e) {
            return back()->with('error', 'Midtrans Error: ' . $e->getMessage());
        }
    }

    // Fungsi Callback / Webhook otomatis dari Server Midtrans
    public function callback(Request $request)
    {
        $serverKey = config('midtrans.server_key');

        $hashed = hash("sha512", $request->order_id . $request->status_code . $request->gross_amount . $serverKey);
        if ($hashed !== $request->signature_key) {
            return response()->json(['message' => 'Invalid signature'], 403);
        }

        $transactionStatus = $request->transaction_status;
        $type = $request->payment_type;
        $fraud = $request->fraud_status;

        $statusPembayaran = 'pending';

        if ($transactionStatus == 'capture') {
            if ($type == 'credit_card') {
                $statusPembayaran = ($fraud == 'challenge') ? 'challenge' : 'completed';
            }
        } elseif ($transactionStatus == 'settlement') {
            $statusPembayaran = 'completed';
        } elseif ($transactionStatus == 'pending') {
            $statusPembayaran = 'pending';
        } elseif (in_array($transactionStatus, ['deny', 'expire', 'cancel'])) {
            $statusPembayaran = 'gagal';
        }

        $pembayaran = Pembayaran::where('kode_pembayaran', $request->order_id)->first();

        if ($pembayaran) {
            $sudahLunas = in_array(strtolower($pembayaran->status), ['completed', 'lunas']);

            $pembayaran->update([
                'status' => $statusPembayaran
            ]);

            if (in_array($statusPembayaran, ['completed', 'lunas'])) {
                $cekPemesanan = Pemesanan::where('id_pembayaran', $pembayaran->id_pembayaran)->first();

                // UPDATE STATUS PESANAN MENJADI DIPROSES
                if ($cekPemesanan) {
                    $cekPemesanan->update(['status' => 'diproses']);
                } else {
                    // Fallback jika karena alasan tertentu pesanan belum terbuat
                    Pemesanan::create([
                        'id_user'        => $pembayaran->id_pengguna,
                        'kode_penawaran' => $pembayaran->kode_penawaran,
                        'id_pembayaran'  => $pembayaran->id_pembayaran,
                        'status'         => 'diproses',
                    ]);
                }

                if (!$sudahLunas) {
                    $penawaran = Penawaran::where('kode_penawaran', $pembayaran->kode_penawaran)->first();

                    if ($penawaran) {
                        $penawaran->update(['status' => 'sold']);
                        // LOGIKA SALDO DIHAPUS DARI SINI
                    }
                }
            }
        }

        return response()->json(['message' => 'Callback received successfully']);
    }

    // Menampilkan riwayat pemesanan dengan filter status
    public function pemesanan(Request $request)
    {
        $user = auth()->user();
        $userId = $user->id ?? $user->id_pengguna ?? null;
        $status = $request->query('status');

        // =========================================================================
        // FITUR BARU: PEMBERSIHAN OTOMATIS PESANAN "BELUM BAYAR" JIKA AKUN SOLD
        // =========================================================================
        // 1. Cari pesanan milik user ini yang status penawarannya sudah 'sold'
        // 2. TAPI status pembayarannya belum lunas / tidak ada
        $pesananHangus = Pemesanan::where('id_user', $userId)
            ->whereHas('penawaran', function ($q) {
                $q->where('status', 'sold'); // Cek jika akun sudah laku terjual
            })
            ->where(function ($q) {
                $q->whereDoesntHave('pembayaran')
                    ->orWhereHas('pembayaran', function ($qPembayaran) {
                        $qPembayaran->whereNotIn('status', ['lunas', 'Lunas', 'completed', 'Completed']);
                    });
            })->get();

        // 3. Hapus data yang hangus tersebut
        foreach ($pesananHangus as $hangus) {
            if ($hangus->pembayaran) {
                $hangus->pembayaran->delete(); // Hapus data pembayaran dulu agar tidak error foreign key
            }
            $hangus->delete(); // Hapus riwayat pesanannya
        }
        // =========================================================================


        // MENGAMBIL DATA RIWAYAT (Setelah dibersihkan)
        $query = Pemesanan::with(['pembayaran', 'penawaran.gambarPenawaran'])
            ->where('id_user', $userId);

        // LOGIKA FILTER BARU
        if ($status == 'belum_bayar') {

            // Cek tabel pembayaran: cari yang belum lunas atau belum ada data pembayarannya
            $query->where(function ($q) {
                $q->whereHas('pembayaran', function ($qPembayaran) {
                    $qPembayaran->whereNotIn('status', ['lunas', 'Lunas', 'completed', 'Completed']);
                })->orWhereDoesntHave('pembayaran');
            });
        } elseif ($status == 'dikirim') {

            // Cek tabel pemesanan: status dikirim
            $query->where('status', 'dikirim'); // Sesuaikan case-nya (misal 'Dikirim') jika di database huruf besar

        } elseif ($status == 'selesai') {

            // Cek tabel pemesanan: status selesai
            $query->where('status', 'selesai'); // Sesuaikan case-nya (misal 'Selesai') jika di database huruf besar

        }

        // --- INI BAGIAN YANG DIUBAH ---
        // get() diganti menjadi paginate(), lalu ditambah withQueryString()
        $pemesanans = $query->latest('waktu_pemesanan')->paginate(10)->withQueryString();

        return view('pembayaran.riwayat_pemesanan', compact('pemesanans', 'status'));
    }

    public function downloadFaktur(Request $request)
    {
        $user = auth()->user();
        $userId = $user->id ?? $user->id_pengguna ?? null;
        $status = $request->query('status');

        // =========================================================
        // PERBAIKAN: Tangkap input 'periode' (contoh: "2026-10")
        // =========================================================
        $periodeReq = $request->query('periode', Carbon::now()->format('Y-m'));

        // Pecah string "2026-10" menjadi variabel tahun dan bulan
        $parts = explode('-', $periodeReq);
        $tahun = $parts[0];
        $bulan = $parts[1];
        // =========================================================

        $query = Pemesanan::with(['pembayaran', 'penawaran'])->where('id_user', $userId);

        // Filter berdasarkan bulan dan tahun pilihan user
        $query->whereMonth('waktu_pemesanan', $bulan)
            ->whereYear('waktu_pemesanan', $tahun);

        if ($status == 'belum_bayar') {
            $query->where(function ($q) {
                $q->whereHas('pembayaran', function ($qPembayaran) {
                    $qPembayaran->whereNotIn('status', ['lunas', 'Lunas', 'completed', 'Completed']);
                })->orWhereDoesntHave('pembayaran');
            });
        } elseif ($status == 'dikirim') {
            $query->where('status', 'dikirim');
        } elseif ($status == 'selesai') {
            $query->where('status', 'selesai');
        }

        // Ambil data
        $pemesanans = $query->latest('waktu_pemesanan')->get();

        // Format periode berdasarkan pilihan user (Misal: "October 2026")
        $periode = Carbon::createFromDate($tahun, $bulan, 1)->format('F Y');

        // Generate PDF
        $pdf = Pdf::loadView('pembayaran.faktur_pdf', compact('pemesanans', 'user', 'periode'));

        // Nama file menyesuaikan bulan pilihan
        return $pdf->download('Faktur_Pesanan_' . Carbon::createFromDate($tahun, $bulan, 1)->format('M_Y') . '.pdf');
    }

    public function downloadFakturSeller(Request $request)
    {
        $user = auth()->user();
        $userId = $user->id ?? $user->id_pengguna ?? null;

        // Tangkap input 'periode' dari custom dropdown modal (contoh: "2026-10")
        $periodeReq = $request->query('periode', \Carbon\Carbon::now()->format('Y-m'));

        // Pecah string "2026-10" menjadi variabel tahun dan bulan
        $parts = explode('-', $periodeReq);
        $tahun = $parts[0];
        $bulan = $parts[1];

        // =========================================================================
        // LOGIKA PENJUAL: Tarik data pesanan yang mana 'penawaran'-nya milik user ini
        // =========================================================================
        $query = Pemesanan::with(['pembayaran', 'penawaran'])
            ->whereHas('penawaran', function ($q) use ($userId) {
                // Pastikan 'id_pengguna' adalah nama kolom relasi penjual di tabel penawaran Anda
                $q->where('id_pengguna', $userId);
            })
            // Filter berdasarkan bulan dan tahun pilihan
            ->whereMonth('waktu_pemesanan', $bulan)
            ->whereYear('waktu_pemesanan', $tahun);

        // Ambil data
        $pemesanans = $query->latest('waktu_pemesanan')->get();

        // Format periode untuk ditampilkan di PDF (Misal: "October 2026")
        $periode = \Carbon\Carbon::createFromDate($tahun, $bulan, 1)->format('F Y');

        // =========================================================================
        // RENDER PDF
        // (Anda bisa menggunakan view 'faktur_pdf' yang sama, 
        //  atau menduplikatnya menjadi 'faktur_seller_pdf' jika ingin teksnya dibedakan)
        // =========================================================================
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('pembayaran.faktur_seller_pdf', compact('pemesanans', 'user', 'periode'));

        // Unduh dengan nama file khusus Penjualan
        return $pdf->download('Faktur_Penjualan_' . \Carbon\Carbon::createFromDate($tahun, $bulan, 1)->format('M_Y') . '.pdf');
    }

    public function unduhFakturSatuan($kode_pembayaran)
    {
        $pemesanan = Pembayaran::with('penawaran.Pengguna')->where('kode_pembayaran', $kode_pembayaran)->firstOrFail();

        // Pastikan hanya pemilik pesanan yang bisa mengunduh (Keamanan)
        if ($pemesanan->pemesanan->id_user !== auth()->id()) {
            abort(403, 'Akses ditolak.');
        }
        $pdf = Pdf::loadView('pembayaran.faktur_satuan', compact('pemesanan'));

        // Mengunduh otomatis dengan nama file yang rapi
        return $pdf->download('Faktur_' . $kode_pembayaran . '.pdf');
    }

    public function laporanWebsite(Request $request)
    {
        // 1. Tangkap parameter periode (Format dari form: YYYY-MM)
        $periode = $request->input('periode'); 

        // 2. Query dasar: Ambil pesanan yang berstatus selesai/sukses
        $query = Pemesanan::with(['pengguna', 'penawaran.pengguna', 'pembayaran'])
                    ->whereIn('status', ['selesai', 'sukses']); // Sesuaikan status sukses di DB Anda

        // 3. Filter berdasarkan bulan dan tahun (jika periode dipilih)
        if ($periode) {
            $parsedDate = Carbon::parse($periode . '-01');
            $query->whereYear('waktu_pemesanan', $parsedDate->year)
                  ->whereMonth('waktu_pemesanan', $parsedDate->month);
        }

        // 4. Eksekusi query, ambil data terbaru
        $pemesanans = $query->orderBy('waktu_pemesanan', 'desc')->get();

        // 5. Hitung Ringkasan Data (Sesuai dengan rumus di PDF)
        $totalTransaksiSukses = $pemesanans->count();
        $totalPendapatan = $pemesanans->sum(function($item) {
            // Menghitung pajak 5% dari harga
            return ($item->pembayaran->harga ?? 0) * 0.1; 
        });

        // 6. PROSES EKSPOR PDF
        if ($request->input('export') === 'pdf') {
            // Arahkan ke file blade PDF Anda (Sesuaikan 'admin.laporan_pdf' dengan nama/lokasi file blade PDF Anda)
            $pdf = Pdf::loadView('admin.laporan_pdf', [ 
                'pemesanans' => $pemesanans,
                'totalTransaksiSukses' => $totalTransaksiSukses,
                'totalPendapatan' => $totalPendapatan,
                'periode' => $periode
            ]);
            
            // Nama file dinamis sesuai bulan
            $namaFile = 'Laporan_Platform_Moriarty_' . ($periode ? $periode : 'Semua_Waktu') . '.pdf';
            
            return $pdf->download($namaFile);
        }

        // 7. Jika bukan PDF, kembalikan ke tampilan halaman web laporan biasa
        // (Sesuaikan 'admin.laporan' dengan nama file blade halaman laporan Anda)
        return view('admin.laporan', compact('pemesanans', 'totalTransaksiSukses', 'totalPendapatan', 'periode'));
    }

    // Fungsi untuk konfirmasi pesanan selesai oleh pembeli
    public function konfirmasiPesanan($hashed_id)
    {
        $decoded = \Vinkla\Hashids\Facades\Hashids::decode($hashed_id);
        if (empty($decoded)) {
            return back()->with('error', 'Pesanan tidak valid.');
        }

        $idAsli = $decoded[0];
        $userId = auth()->id() ?? auth()->user()->id_pengguna;

        // Ambil data pemesanan beserta relasinya
        $pemesanan = Pemesanan::with(['penawaran.Pengguna'])->where('id_pemesanan', $idAsli)
            ->where('id_user', $userId) // Pastikan hanya pembeli asli yang bisa konfirmasi
            ->firstOrFail();

        // Cegah double konfirmasi
        if (strtolower($pemesanan->status) == 'selesai') {
            return back()->with('error', 'Pesanan ini sudah dikonfirmasi.');
        }

        DB::beginTransaction();
        try {
            // 1. Ubah status pesanan menjadi selesai
            $pemesanan->update(['status' => 'selesai']);

            // 2. Tambahkan saldo ke seller (Harga asli penawaran dikurangi 10% fee)
            $seller = $pemesanan->penawaran->Pengguna;
            if ($seller) {
                // Harga asli penawaran tanpa fee admin
                $pendapatanSeller = $pemesanan->penawaran->harga;
                $seller->increment('saldo', $pendapatanSeller);
            }

            DB::commit();
            return back()->with('success', 'Pesanan berhasil dikonfirmasi sebagai selesai. Dana telah diteruskan ke penjual.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan sistem saat memproses konfirmasi.');
        }
    }

    // Penanganan AJAX saat Snap Sukses
    public function suksesSnapAjax($kode)
    {
        DB::beginTransaction();

        try {
            $pembayaran = Pembayaran::where('kode_pembayaran', $kode)->first();

            if (!$pembayaran) {
                DB::rollBack();
                return response()->json(['status' => 'not_found'], 404);
            }

            $sudahLunas = in_array(strtolower($pembayaran->status), ['completed', 'lunas']);

            $pembayaran->update(['status' => 'completed']);

            $idUserPembeli = auth()->id() ?? (auth()->check() ? auth()->user()->id_pengguna : $pembayaran->id_pengguna);

            $pemesanan = Pemesanan::where('id_pembayaran', $pembayaran->id_pembayaran)->first();

            // UPDATE STATUS PESANAN MENJADI DIPROSES
            if ($pemesanan) {
                $pemesanan->update(['status' => 'diproses']);
            } else {
                // Fallback jika tidak ada
                $waktuDariPembayaran = $pembayaran->waktu_pembayaran ?? DB::table('pembayaran')->where('id_pembayaran', $pembayaran->id_pembayaran)->value('waktu_pembayaran');

                $pemesanan = Pemesanan::create([
                    'id_user'          => $idUserPembeli,
                    'kode_penawaran'   => $pembayaran->kode_penawaran,
                    'id_pembayaran'    => $pembayaran->id_pembayaran,
                    'status'           => 'diproses',
                    'waktu_pemesanan'  => $waktuDariPembayaran ?: date('Y-m-d H:i:s'),
                ]);
            }

            if (!$sudahLunas) {
                $penawaran = Penawaran::where('kode_penawaran', $pembayaran->kode_penawaran)->first();

                if ($penawaran) {
                    $penawaran->update(['status' => 'sold']);
                    // LOGIKA SALDO DIHAPUS DARI SINI
                }
            }

            DB::commit();

            return response()->json([
                'status'       => 'success',
                'id_pemesanan' => \Vinkla\Hashids\Facades\Hashids::encode($pemesanan->id_pemesanan),
                'message'      => 'Pembayaran lunas dan pesanan sedang diproses!'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'status'  => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function paypalSuccess(Request $request)
    {
        $provider = new PayPalClient;
        $provider->setApiCredentials(config('paypal'));
        $provider->getAccessToken();

        $response = $provider->capturePaymentOrder($request->query('token'));

        if (isset($response['status']) && $response['status'] === 'COMPLETED') {
            $kode = $request->query('kode');
            $pembayaran = Pembayaran::where('kode_pembayaran', $kode)->firstOrFail();

            \DB::beginTransaction();
            try {
                $pembayaran->update([
                    'status' => 'completed',
                    'waktu_pembayaran' => \DB::raw('NOW()'),
                ]);

                $pemesanan = Pemesanan::where('id_pembayaran', $pembayaran->id_pembayaran)->first();

                // UPDATE STATUS PESANAN MENJADI DIPROSES
                if ($pemesanan) {
                    $pemesanan->update(['status' => 'diproses']);
                } else {
                    $idUserPembeli = auth()->id() ?? auth()->user()->id_pengguna ?? $pembayaran->id_pengguna;
                    $pemesanan = Pemesanan::create([
                        'id_user'         => $idUserPembeli,
                        'kode_penawaran'  => $pembayaran->kode_penawaran,
                        'id_pembayaran'   => $pembayaran->id_pembayaran,
                        'status'          => 'diproses',
                        'waktu_pemesanan' => $pembayaran->waktu_pembayaran ?? \DB::raw('NOW()'),
                    ]);
                }

                $penawaran = Penawaran::where('kode_penawaran', $pembayaran->kode_penawaran)->first();
                if ($penawaran) {
                    $penawaran->update(['status' => 'sold']);
                    // LOGIKA SALDO DIHAPUS DARI SINI
                }

                \DB::commit();

                return redirect()->route('pembayaran.invoice', ['id' => \Vinkla\Hashids\Facades\Hashids::encode($pemesanan->id_pemesanan)])
                    ->with('success', 'Pembayaran via PayPal berhasil diproses!');
            } catch (\Exception $e) {
                \DB::rollBack();
                return redirect('/riwayat-pemesanan')->with('error', 'Gagal mencatat pesanan: ' . $e->getMessage());
            }
        }

        return redirect('/riwayat-pemesanan')->with('error', 'Pembayaran PayPal belum selesai.');
    }
}
