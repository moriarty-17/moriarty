@extends('template.frontend')

@section('content')
<div class="mx-auto w-full max-w-[1400px] px-4 py-8 sm:px-8 sm:py-10 lg:px-[43px] lg:py-12">
    <div class="grid grid-cols-1 gap-8 lg:grid-cols-2 lg:gap-10">

        <!-- KARTU KIRI: DETAIL PRODUCT & INFORMASI AKUN -->
        <div class="rounded-[10px] border-4 border-line bg-card p-5 sm:p-6 lg:p-8">

            <div class="flex flex-col gap-5 sm:flex-row sm:items-center">

                @php
                // Logika penarikan gambar penawaran (Menangani relasi gambar_penawaran atau kolom langsung)
                $gambarUtama = asset('assets/img/listing-wide.jpg'); // Fallback default

                // 1. Cek dari relasi gambarPenawaran (seperti di halaman profil seller)
                if (isset($pemesanan->penawaran->gambarPenawaran) && $pemesanan->penawaran->gambarPenawaran->isNotEmpty()) {
                $gambarRecord = $pemesanan->penawaran->gambarPenawaran->first();
                $namaFile = $gambarRecord->path_gambar ?? $gambarRecord->gambar ?? null;

                if ($namaFile) {
                $gambarUtama = Str::startsWith($namaFile, 'http') ? $namaFile : asset('assets/resource/penawaran/' . $namaFile);
                }
                }
                // 2. Cek jika gambar disimpan langsung di kolom 'gambar' tabel penawaran
                elseif (isset($pemesanan->penawaran->gambar)) {
                $namaFile = $pemesanan->penawaran->gambar;
                $gambarUtama = Str::startsWith($namaFile, 'http') ? $namaFile : asset('assets/resource/penawaran/' . $namaFile);
                }
                @endphp

                <!-- Gambar Penawaran -->
                <img
                    src="{{ $gambarUtama }}"
                    alt="Gambar Produk"
                    class="h-[174px] w-full shrink-0 rounded-sm object-cover sm:w-[309px]">

                <!-- Tambahkan flex-1 agar teks menyesuaikan sisa ruang dan tidak menabrak batas layar -->
                <div class="min-w-0 flex-1">

                    <!-- Judul: Gunakan line-clamp-2 untuk memotong setelah 2 baris (bisa diganti 'truncate' jika ingin 1 baris saja) -->
                    <p class="text-xl font-semibold leading-[1.5] text-light line-clamp-4" title="{{ $pemesanan->penawaran->judul ?? 'Judul Penawaran' }}">
                        {{ $pemesanan->penawaran->judul ?? 'Judul Penawaran' }}
                    </p>

                    <div class="mt-4 flex items-center gap-3">
                        <!-- Profil Seller -->
                        <img
                            {{-- PERBAIKAN 1: Route Gambar Profil (Sudah benar) --}}
                            src="{{ $pemesanan->penawaran->Pengguna->gambar ? asset('assets/resource/profil/' . $pemesanan->penawaran->Pengguna->gambar) : asset('assets/img/avatar.png') }}"
                            alt="Seller Avatar"
                            class="h-10 w-10 shrink-0 rounded-full object-cover object-top ring-1 ring-sky">

                        <div class="min-w-0">
                            <p class="flex flex-wrap items-center gap-1 text-base font-semibold text-muted">
                                {{ $pemesanan->penawaran->Pengguna->username ?? 'Seller' }}

                                <span class="h-5 w-5 shrink-0 text-verify">
                                    <svg viewBox="0 0 24 24" fill="currentColor">
                                        <path d="M12 1l2.6 2.1 3.3-.4 1.1 3.2 2.9 1.7-1 3.2 1 3.2-2.9 1.7-1.1 3.2-3.3-.4L12 21l-2.6-2.1-3.3.4-1.1-3.2L2.1 14l1-3.2-1-3.2 2.9-1.7 1.1-3.2 3.3.4L12 1zm-1.2 14.3 5.7-5.7-4.1 4.1-2-2-1.6 1.6 3.6 3.6z" />
                                    </svg>
                                </span>
                            </p>

                            <p class="mt-1 flex flex-wrap items-center gap-2 text-base text-muted">
                                Terverifikasi
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="my-6 border-t border-line sm:my-7"></div>

            <section>
                <h2 class="text-xl font-semibold text-light">
                    Informasi Akun
                </h2>

                <div class="mt-5 rounded-[8px] border border-line bg-panel p-4 sm:p-5">
                    <p class="break-words text-base font-medium leading-8 text-light sm:text-lg sm:leading-10">
                        {!! nl2br(e($pemesanan->penawaran->informasi_penawaran ?? 'Informasi akun belum tersedia.')) !!}
                    </p>
                </div>
            </section>

        </div>

        <!-- KARTU KANAN: HARGA & DETAIL GAME -->
        <div class="flex flex-col gap-4">
            <div class="h-fit rounded-[10px] border-4 border-line bg-card p-5 sm:p-6 lg:p-8">

                @php
                $hargaAsli = (float) ($pemesanan->penawaran->harga ?? 0);
                $fee = $hargaAsli * 0.10;
                $total = $hargaAsli + $fee;
                @endphp

                <div>
                    <div class="flex items-center justify-between gap-4 py-[10px]">
                        <span class="text-xl font-medium text-light">Harga</span>
                        <span class="text-right text-xl font-bold text-light">IDR {{ number_format($hargaAsli, 0, ',', '.') }}</span>
                    </div>

                    <div class="flex items-center justify-between gap-4 border-y border-line py-[10px]">
                        <span class="text-xl font-medium text-light">Pajak</span>
                        <span class="text-right text-xl font-bold text-light">IDR {{ number_format($fee, 0, ',', '.') }} (10%)</span>
                    </div>

                    <div class="flex items-center justify-between gap-4 py-[10px]">
                        <span class="text-xl font-medium text-light">Total</span>
                        <span class="text-right text-xl font-bold text-sky">IDR {{ number_format($total, 0, ',', '.') }}</span>
                    </div>
                </div>

                <div class="my-6 border-t border-line sm:my-7"></div>

                <div>
                    <div class="flex items-center justify-between gap-4 py-[10px]">
                        <span class="text-xl font-medium text-light">Game / Jenis</span>
                        <span class="max-w-[65%] text-right text-xl font-medium text-light">{{ $pemesanan->penawaran->jenis ?? '-' }}</span>
                    </div>

                    <div class="flex items-center justify-between gap-4 py-[10px]">
                        <span class="text-xl font-medium text-light">Pembeli</span>
                        <span class="text-right text-xl font-medium text-light">{{ auth()->user()->username ?? 'Buyer' }}</span>
                    </div>
                </div>

            </div>

            <!-- TOMBOL UNDUH FAKTUR -->
            <a href="{{ url('/unduh-faktur/' . $pemesanan->pembayaran->kode_pembayaran) }}" target="_blank"
                class="cursor-pointer btn-animasi flex w-full items-center justify-center gap-2 rounded-[10px] bg-brand px-4 py-3.5 text-base font-bold text-white transition-all duration-300 hover:bg-brand/90 hover:shadow-lg hover:shadow-brand/20 focus:outline-none focus:ring-2 focus:ring-sky/50">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5">
                    <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                    <polyline points="7 10 12 15 17 10"></polyline>
                    <line x1="12" y1="15" x2="12" y2="3"></line>
                </svg>
                Unduh Faktur Pesanan
            </a>
        </div>

    </div>
</div>
@endsection