<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pengguna;
use App\Models\Pencairan;
use Srmklive\PayPal\Services\PayPal as PayPalClient;
use Illuminate\Support\Facades\DB;

class PencairanController extends Controller
{
    /**
     * Menampilkan halaman withdraw / riwayat pencairan seller
     */
    public function indexWithdraw()
    {
        $user = auth()->user();
        $idPengguna = $user->id_pengguna ?? $user->id;

        $seller = Pengguna::findOrFail($idPengguna);

        // Ambil riwayat pencairan milik seller ini
        $riwayatPencairan = Pencairan::where('id_pengguna', $idPengguna)
            ->orderBy('id_pencairan', 'desc')
            ->get();

        return view('general.pencairan', compact('seller', 'riwayatPencairan'));
    }

    /**
     * Proses otomatis Payout ke PayPal seketika dari form
     */

    public function prosesPencairanOtomatis(Request $request)
    {
        $request->validate([
            'jumlah'       => 'required|numeric|min:16000',
            'email_paypal' => 'required|email',
        ]);

        $user = auth()->user();
        $idPengguna = $user->id_pengguna ?? $user->id;
        $seller = \App\Models\Pengguna::findOrFail($idPengguna);

        $jumlahTarikIDR = (float) $request->jumlah;

        if ($seller->saldo < $jumlahTarikIDR) {
            return redirect()->route('seller.withdraw')->with('error', 'Saldo tidak mencukupi.');
        }

        $kurs = config('paypal.exchange_rate', 16000);
        $jumlahUSD = number_format($jumlahTarikIDR / $kurs, 2, '.', '');

        if ((float)$jumlahUSD < 1.00) {
            return redirect()->route('seller.withdraw')->with('error', 'Nominal penarikan minimal $1 USD.');
        }

        $provider = new \Srmklive\PayPal\Services\PayPal;
        $provider->setApiCredentials(config('paypal'));
        $provider->getAccessToken();

        $batchId = 'WD-' . time() . '-' . rand(100, 999);

        $payoutPayload = [
            "sender_batch_header" => [
                "sender_batch_id" => $batchId,
                "email_subject"   => "Pencairan Saldo dari Moriarty"
            ],
            "items" => [
                [
                    "recipient_type" => "EMAIL",
                    "amount" => [
                        "value"    => (string) $jumlahUSD,
                        "currency" => "USD"
                    ],
                    "receiver"       => $request->email_paypal,
                    "note"           => "Pencairan saldo IDR " . $jumlahTarikIDR,
                    "sender_item_id" => "ITEM-" . $batchId
                ]
            ]
        ];

        \Illuminate\Support\Facades\DB::beginTransaction();
        
        try {
            // 1. Kurangi saldo seller
            $seller->decrement('saldo', $jumlahTarikIDR);

            // 2. Tembak API Payouts PayPal
            $response = $provider->createBatchPayout($payoutPayload);

            if (isset($response['batch_header']['batch_status'])) {
                $statusPayPal = $response['batch_header']['batch_status'];
                $payoutBatchId = $response['batch_header']['payout_batch_id'] ?? $batchId;

                // 3. Catat ke database pencairan
                \Illuminate\Support\Facades\DB::table('pencairan')->insert([
                    'id_pengguna'      => $seller->id_pengguna,
                    'kode_pencairan'   => $payoutBatchId,
                    'jumlah_pencairan' => $jumlahTarikIDR,
                    'metode_pencairan' => 'paypal',
                    'alamat_pencairan' => $request->email_paypal,
                    'status'           => ($statusPayPal === 'SUCCESS' || $statusPayPal === 'PENDING') ? 'selesai' : 'diproses',
                    'waktu_pencairan'  => \Illuminate\Support\Facades\DB::raw('NOW()'),
                ]);

                \Illuminate\Support\Facades\DB::commit();

                // Redirect sukses langsung ke halaman wallet
                return redirect()->to('seller-dashboard?page=wallet')
                                 ->with('success', 'Pencairan dana sebesar IDR ' . number_format($jumlahTarikIDR, 0, ',', '.') . ' berhasil ditransfer ke PayPal!');
            }

            // Jika respons PayPal gagal/tidak ada status batch
            \Illuminate\Support\Facades\DB::rollBack();
            return redirect()->route('seller.withdraw')->with('error', 'Gagal memproses ke PayPal: ' . json_encode($response));

        } catch (\Exception $e) {
            // Penangkap error agar transaksi di-rollback jika terjadi kendala
            \Illuminate\Support\Facades\DB::rollBack();
            return redirect()->route('seller.withdraw')->with('error', 'Terjadi error: ' . $e->getMessage());
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

                $idUserPembeli = auth()->id() ?? auth()->user()->id_pengguna ?? $pembayaran->id_pengguna;

                // Buat record pesanan
                $pemesanan = Pemesanan::create([
                    'id_user'         => $idUserPembeli,
                    'kode_penawaran'  => $pembayaran->kode_penawaran,
                    'id_pembayaran'   => $pembayaran->id_pembayaran,
                    'status'          => 'diproses',
                    'waktu_pemesanan' => $pembayaran->waktu_pembayaran ?? \DB::raw('NOW()'),
                ]);

                // Update status penawaran dan increment saldo seller (90%)
                $penawaran = Penawaran::where('kode_penawaran', $pembayaran->kode_penawaran)->first();
                if ($penawaran) {
                    $penawaran->update(['status' => 'sold']);

                    $seller = \App\Models\Pengguna::find($penawaran->id_pengguna);
                    if ($seller) {
                        // Saldo seller yang bertambah TETAP DALAM BENTUK IDR (90% dari harga asli IDR)
                        $pendapatanSellerIDR = (float) $penawaran->harga * 0.9;
                        $seller->increment('saldo', $pendapatanSellerIDR);
                    }
                }

                \DB::commit();

                return redirect()->route('pembayaran.invoice', ['id' => $pemesanan->id_pemesanan])
                    ->with('success', 'Pembayaran via PayPal berhasil diproses!');
            } catch (\Exception $e) {
                \DB::rollBack();
                return redirect('/riwayat-pemesanan')->with('error', 'Gagal mencatat pesanan: ' . $e->getMessage());
            }
        }

        return redirect('/pembayaran')->with('error', 'Pembayaran PayPal belum selesai.');
    }

    public function paypalCancel(Request $request)
    {
        return redirect('/pembayaran')->with('error', 'Pembayaran PayPal dibatalkan.');
    }
}
