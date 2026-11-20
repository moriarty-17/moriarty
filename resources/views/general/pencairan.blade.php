@extends('template.frontend')

@section('content')
    <div class="mx-auto w-full max-w-[1440px] px-4 py-8 sm:px-8 sm:py-10 lg:py-12">
        <div class="mx-auto w-full max-w-[983px] rounded-[10px] border-4 border-line bg-card p-5 sm:p-8 lg:p-14">

            <div class="flex flex-wrap items-center justify-between gap-4 border-b border-line pb-5">
                <div>
                    <h1 class="text-2xl font-bold text-light sm:text-3xl">Withdraw Saldo</h1>
                    <p class="mt-1 text-sm text-muted">Cairkan pendapatan penjualan akun Anda ke rekening atau e-wallet pilihan.</p>
                </div>
                <div class="text-left sm:text-right">
                    <span class="block text-xs font-medium text-muted">Saldo Tersedia</span>
                    <span class="text-xl font-extrabold text-sky sm:text-2xl">
                        IDR {{ number_format(auth()->user()->saldo ?? 0, 0, ',', '.') }}
                    </span>
                </div>
            </div>

            {{-- Notifikasi Error / Success --}}
            @if(session('success'))
                <div class="mt-6 rounded-lg border border-ok/40 bg-ok/15 p-4 text-sm font-semibold text-ok">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="mt-6 rounded-lg border border-bad/40 bg-bad/15 p-4 text-sm font-semibold text-bad">
                    {{ session('error') }}
                </div>
            @endif

            <form action="{{ route('seller.withdraw.proses') }}" method="POST" class="mt-8">
                @csrf

                <div>
                    <label class="mb-2 block text-sm font-semibold text-light sm:text-[15px]" for="metode">
                        Pilih Metode Penarikan
                    </label>
                    <select id="metode" name="metode" required
                        class="w-full max-w-[356px] rounded-[8px] border border-line bg-panel px-4 py-2.5 text-sm font-medium text-light transition-all duration-300 ease-out hover:border-sky/60 hover:bg-card focus:border-sky focus:bg-panel focus:outline-none focus:ring-2 focus:ring-sky/20 sm:text-[15px]">
                        <option value="paypal" selected>Paypal (Instant Payout)</option>
                    </select>
                </div>

                <div class="mt-7 grid gap-6 sm:mt-8 sm:grid-cols-2 sm:gap-8">
                    <div>
                        <label id="label-tujuan" class="mb-2 block text-sm font-semibold text-light sm:text-[15px]" for="tujuan">
                            Email Akun PayPal
                        </label>
                        <input id="tujuan" name="email_paypal" type="email" required
                            placeholder="nama-akun@example.com"
                            value="{{ old('email_paypal') }}"
                            class="w-full rounded-[8px] border border-line bg-panel px-3.5 py-3 text-sm font-medium text-light placeholder:text-muted transition-all duration-300 ease-out hover:border-sky/60 hover:bg-card focus:border-sky focus:bg-panel focus:outline-none focus:ring-2 focus:ring-sky/20 sm:px-4 sm:py-[11px] sm:text-[15px]">
                        <span id="hint-tujuan" class="mt-1.5 block text-xs text-muted">
                            Pastikan email ini aktif dan terdaftar pada akun PayPal Anda.
                        </span>
                    </div>

                    <div>
                        <label class="mb-2 block text-sm font-semibold text-light sm:text-[15px]" for="jumlah">
                            Jumlah Penarikan (IDR)
                        </label>
                        <input id="jumlah" name="jumlah" type="number" min="16000" required
                            placeholder="Contoh: 100000"
                            value="{{ old('jumlah') }}"
                            class="w-full rounded-[8px] border border-line bg-panel px-3.5 py-3 text-sm font-medium text-light placeholder:text-muted transition-all duration-300 ease-out hover:border-sky/60 hover:bg-card focus:border-sky focus:bg-panel focus:outline-none focus:ring-2 focus:ring-sky/20 sm:px-4 sm:py-[11px] sm:text-[15px]">
                        <span class="mt-1.5 block text-xs text-muted">
                            Min. penarikan IDR 16.000 (~$1.00 USD). Kurs acuan: 1 USD ≈ IDR {{ number_format(config('paypal.exchange_rate', 16000), 0, ',', '.') }}
                        </span>
                    </div>
                </div>

                <div class="mt-8 flex flex-col gap-4 sm:mt-10 sm:flex-row sm:items-center">
                    <button type="submit"
                        class="inline-flex min-h-[46px] w-full max-w-[284px] items-center justify-center rounded-[10px] bg-brand px-6 py-2.5 text-lg font-semibold text-white shadow-lg shadow-brand/20 transition-all duration-300 ease-out hover:-translate-y-0.5 hover:bg-brand/90 hover:shadow-brand/35 focus:outline-none active:translate-y-0 sm:text-xl">
                        Cairkan Sekarang
                    </button>
                    <a href="{{ url('seller-dashboard?page=wallet') }}" 
                        class="text-center text-sm font-medium text-muted hover:text-light transition-colors">
                        Kembali ke Wallet
                    </a>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const selectMetode = document.getElementById('metode');
            const labelTujuan = document.getElementById('label-tujuan');
            const inputTujuan = document.getElementById('tujuan');
            const hintTujuan = document.getElementById('hint-tujuan');

            selectMetode.addEventListener('change', function () {
                if (this.value === 'paypal') {
                    labelTujuan.textContent = 'Email Akun PayPal';
                    inputTujuan.type = 'email';
                    inputTujuan.name = 'email_paypal';
                    inputTujuan.placeholder = 'nama-akun@example.com';
                    hintTujuan.textContent = 'Pastikan email ini aktif dan terdaftar pada akun PayPal Anda.';
                } else if (this.value === 'transfer_bank') {
                    labelTujuan.textContent = 'Nama Bank & Nomor Rekening';
                    inputTujuan.type = 'text';
                    inputTujuan.name = 'email_paypal'; // Tetap gunakan name tujuan
                    inputTujuan.placeholder = 'BCA - 1234567890 a.n Anda';
                    hintTujuan.textContent = 'Format: [Nama Bank] - [Nomor Rekening] a.n [Nama Pemilik].';
                } else {
                    labelTujuan.textContent = 'Nomor E-Wallet / OVO / GoPay';
                    inputTujuan.type = 'text';
                    inputTujuan.name = 'email_paypal';
                    inputTujuan.placeholder = '081234567890';
                    hintTujuan.textContent = 'Pastikan nomor HP sudah terhubung ke akun e-wallet Anda.';
                }
            });
        });
    </script>
@endsection