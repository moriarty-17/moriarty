@extends('template.frontend')

@section('content')
<div class="mx-auto flex w-full max-w-[1400px] flex-col gap-8 px-4 py-10 sm:px-8 lg:flex-row lg:gap-10 lg:px-[42px] lg:py-12">

    <div class="w-full min-w-0 lg:w-[820px] lg:shrink-0">
        <div class="flex w-full flex-col gap-5 rounded-sm border-[3px] border-line bg-card p-5 sm:flex-row sm:items-center">

            @php
            $gambarUtama = $penawaran->gambarPenawaran->sortBy('id_gambar')->first();
            $pathGambar = $gambarUtama ? asset('assets/resource/penawaran/' . $gambarUtama->gambar) : asset('assets/img/listing-wide.jpg');
            @endphp

            <img
                src="{{ $pathGambar }}"
                alt="{{ $penawaran->judul }}"
                class="h-[174px] w-full shrink-0 rounded-sm object-cover sm:w-[309px]">

            <div class="min-w-0">
                <!-- Opsi 2: Menjadikan maksimal 2 baris (Line Clamp) -->
                <p class="text-lg font-semibold leading-[1.5] text-light sm:text-xl line-clamp-4">
                    {{ $penawaran->judul }}
                </p>

                <div class="mt-4 flex items-center gap-3">
                    {{-- Penyesuaian Route Gambar Profil Penjual --}}
                    <img
                        src="{{ $penawaran->Pengguna->gambar ? asset('assets/resource/profil/' . $penawaran->Pengguna->gambar) : asset('assets/img/avatar.png') }}"
                        alt="Avatar Penjual"
                        class="h-10 w-10 shrink-0 rounded-full object-cover object-top ring-1 ring-sky">

                    <div class="min-w-0">
                        <p class="flex flex-wrap items-center gap-1 text-sm font-semibold text-muted sm:text-base">
                            {{ $penawaran->Pengguna->username ?? 'SirMoriarty' }}

                            <span class="h-5 w-5 shrink-0 text-verify">
                                <svg viewBox="0 0 24 24" fill="currentColor">
                                    <path d="M12 1l2.6 2.1 3.3-.4 1.1 3.2 2.9 1.7-1 3.2 1 3.2-2.9 1.7-1.1 3.2-3.3-.4L12 21l-2.6-2.1-3.3.4-1.1-3.2L2.1 14l1-3.2-1-3.2 2.9-1.7 1.1-3.2 3.3.4L12 1zm-1.2 14.3 5.7-5.7-1.6-1.6-4.1 4.1-2-2-1.6 1.6 3.6 3.6z" />
                                </svg>
                            </span>
                        </p>

                        @php
                        // Mencari ID penjual dari variabel penawaran
                        $idPenjual = $penawaran->id_pengguna ?? $penawaran->id_user;

                        // Menghitung rata-rata dan total ulasan
                        $avgRating = \App\Models\Ulasan::where('id_penjual', $idPenjual)->avg('rating') ?? 0;
                        $totalUlasan = \App\Models\Ulasan::where('id_penjual', $idPenjual)->count();
                        @endphp

                        <p class="mt-2 flex flex-wrap items-center gap-2 text-sm text-muted">
                            <span class="flex items-center gap-1 {{ $avgRating > 0 ? 'text-light' : 'text-muted' }}">
                                <!-- Ikon Bintang -->
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-yellow-400" viewBox="0 0 20 20" fill="currentColor">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                </svg>

                                <!-- Angka Rating -->
                                <span class="font-bold">{{ number_format($avgRating, 1) }}</span>

                                <!-- Total Ulasan -->
                                <span class="font-normal opacity-80 text-muted">({{ $totalUlasan }} Ulasan)</span>
                            </span>
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <fieldset class="mt-10">
            <legend class="sr-only">Metode pembayaran</legend>

            <div class="flex flex-wrap items-center gap-6 sm:gap-10">
                <!-- Opsi PayPal (diberi form="form-bayar") -->
                <label class="flex cursor-pointer items-center gap-3">
                    <input
                        type="radio"
                        name="metode"
                        value="paypal"
                        form="form-bayar"
                        class="h-4 w-4 shrink-0 accent-brand cursor-pointer">
                    <span class="text-lg font-semibold text-white sm:text-xl">
                        Paypal
                    </span>
                    <img
                        src="{{ asset('assets/img/paypal.png') }}"
                        alt="PayPal"
                        class="h-10 w-10 shrink-0 object-contain">
                </label>

                <!-- Opsi Midtrans / Qris (diberi form="form-bayar") -->
                <label class="flex cursor-pointer items-center gap-3">
                    <input
                        type="radio"
                        name="metode"
                        value="qris"
                        form="form-bayar"
                        checked
                        class="h-4 w-4 shrink-0 accent-brand cursor-pointer">
                    <span class="text-lg font-semibold text-white sm:text-xl">
                        Qris
                    </span>
                    <img
                        src="{{ asset('assets/img/qris.png') }}"
                        alt="QRIS"
                        class="h-10 w-10 shrink-0 object-contain">
                </label>
            </div>
        </fieldset>
    </div>

    @php
    $hargaAsli = (float) $penawaran->harga;
    $fee = $hargaAsli * 0.10;
    $totalHarga = $hargaAsli + $fee;
    @endphp

    <aside class="h-fit w-full rounded-[10px] border-4 border-line bg-card p-5 sm:p-6 lg:w-[400px] lg:shrink-0 lg:p-8">
        <div class="flex items-center justify-between gap-4 py-[10px]">
            <span class="text-lg font-medium text-light">Harga</span>
            <span class="text-right text-lg font-bold text-light">
                IDR {{ number_format($hargaAsli, 0, ',', '.') }}
            </span>
        </div>

        <div class="flex items-center justify-between gap-4 border-y border-line py-[10px]">
            <span class="text-lg font-medium text-light">Pajak</span>
            <span class="text-right text-lg font-bold text-light">
                IDR {{ number_format($fee, 0, ',', '.') }} (10%)
            </span>
        </div>

        <div class="flex items-center justify-between gap-4 py-[10px]">
            <span class="text-lg font-medium text-light">Total</span>
            <span class="text-right text-lg font-bold text-sky">
                IDR {{ number_format($totalHarga, 0, ',', '.') }}
            </span>
        </div>

        <!-- Form diberi ID agar input radio di luar form tetap terhubung -->
        <form id="form-bayar" action="{{ url('bayar') }}" method="POST">
            @csrf
            <input type="hidden" name="kode_penawaran" value="{{ $penawaran->kode_penawaran }}">
            <input type="hidden" name="harga" value="{{ $totalHarga }}">

            <button
                type="submit"
                class="mt-8 flex h-[40px] w-full items-center justify-center rounded-[10px] bg-brand text-xl font-semibold text-white transition-colors hover:bg-brand/85 cursor-pointer">
                Bayar
            </button>
        </form>

        <p class="mx-auto mt-6 max-w-[294px] text-center text-sm font-normal leading-5 text-white sm:text-md">
            Dengan melakukan pembayaran, saya menyetujui kebijakan &amp; perarturan ini
        </p>
    </aside>
</div>
@endsection