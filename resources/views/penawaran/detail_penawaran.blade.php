@extends('template.frontend')

@section('content')
<div class="mx-auto mt-8 w-full max-w-[1400px] px-4 py-8 sm:mt-10 sm:px-8 sm:py-10 lg:mt-14 lg:px-[41px] lg:py-10">

    {{-- JUDUL PENAWARAN --}}
    <h1 class="max-w-[737px] text-xl font-semibold leading-[1.35] text-light sm:text-2xl lg:text-xl">
        {{ $penawaran->judul }}
    </h1>

    <div class="mt-7 grid gap-10 lg:mt-8 lg:grid-cols-[minmax(0,763px)_minmax(0,512px)] lg:gap-[80px]">

        {{-- KOLOM KIRI: SLIDER GAMBAR, THUMBNAIL, & DESKRIPSI --}}
        <div class="min-w-0" x-data="{
            active: 0,
            expanded: false,
            modalOpen: false,
            isZoomed: false,
            images: [
                @foreach ($penawaran->gambarPenawaran as $gambar)
                    '{{ asset('assets/resource/penawaran/' . $gambar->gambar) }}',
                @endforeach
            ]
        }" @keydown.escape.window="modalOpen = false; isZoomed = false">

            {{-- GAMBAR UTAMA --}}
            <div class="relative overflow-hidden rounded-[10px] bg-panel group">

                {{-- Tampilkan jika ada gambar --}}
                <template x-if="images.length > 0">
                    <div class="relative cursor-pointer" @click="modalOpen = true; isZoomed = false">
                        <img :src="images[active]" alt="{{ $penawaran->judul }}" class="aspect-[753/424] w-full object-cover transition-transform duration-300 group-hover:scale-[1.01]">

                        {{-- Overlay Hint Zoom --}}
                        <div class="absolute inset-0 flex items-center justify-center bg-black/30 opacity-0 transition-opacity duration-300 group-hover:opacity-100">
                            <span class="inline-flex items-center gap-2 rounded-lg bg-black/60 px-4 py-2 text-sm text-white backdrop-blur-sm">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v6m3-3H7" />
                                </svg>
                                Klik untuk memperbesar
                            </span>
                        </div>
                    </div>
                </template>

                <template x-if="images.length === 0">
                    <img src="{{ asset('assets/img/listing.jpg') }}" alt="Tidak ada gambar" class="aspect-[753/424] w-full object-cover">
                </template>

                <div class="pointer-events-none absolute inset-0 shadow-[inset_0_0_100px_#04111F]"></div>

                <button
                    type="button"
                    x-show="images.length > 1"
                    @click.stop="active = active === 0 ? images.length - 1 : active - 1"
                    aria-label="Gambar sebelumnya"
                    class="absolute left-3 top-1/2 z-10 flex h-12 w-9 -translate-y-1/2 items-center justify-center text-light transition-opacity hover:opacity-70 sm:left-5 sm:h-16 sm:w-10 lg:left-6 lg:h-20 lg:w-8">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="h-7 w-7 sm:h-8 sm:w-8">
                        <path d="M15 5l-7 7 7 7" />
                    </svg>
                </button>

                <button
                    type="button"
                    x-show="images.length > 1"
                    @click.stop="active = active === images.length - 1 ? 0 : active + 1"
                    aria-label="Gambar berikutnya"
                    class="absolute right-3 top-1/2 z-10 flex h-12 w-9 -translate-y-1/2 items-center justify-center text-light transition-opacity hover:opacity-70 sm:right-5 sm:h-16 sm:w-10 lg:right-6 lg:h-20 lg:w-8">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="h-7 w-7 sm:h-8 sm:w-8">
                        <path d="M9 5l7 7-7 7" />
                    </svg>
                </button>
            </div>

            <div x-show="images.length > 0" class="mt-4 relative">
                <div class="flex gap-3 overflow-x-auto pb-2 scrollbar-thin scrollbar-thumb-sky/40 scrollbar-track-transparent">
                    @foreach ($penawaran->gambarPenawaran as $index => $gambar)
                    <button
                        type="button"
                        @click="active = {{ $index }}"
                        class="relative shrink-0 overflow-hidden rounded-xl border-2 transition-all focus:outline-none"
                        :class="active === {{ $index }} ? 'border-sky opacity-100 scale-[0.96]' : 'border-transparent opacity-60 hover:opacity-100'">
                        {{-- Fixed Square Size: h-20 w-20 (80x80px) & h-24 w-24 (96x96px) --}}
                        <img
                            src="{{ asset('assets/resource/penawaran/' . $gambar->gambar) }}"
                            alt="Thumbnail {{ $index + 1 }}"
                            class="h-20 w-20 sm:h-24 sm:w-24 aspect-square object-cover rounded-lg">
                    </button>
                    @endforeach
                </div>
            </div>

            <template x-teleport="body">
                <div
                    x-show="modalOpen"
                    x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="opacity-0"
                    x-transition:enter-end="opacity-100"
                    x-transition:leave="transition ease-in duration-200"
                    x-transition:leave-start="opacity-100"
                    x-transition:leave-end="opacity-0"
                    class="fixed inset-0 z-50 flex items-center justify-center bg-black/80 p-4 backdrop-blur-md"
                    style="display: none;">
                    <div class="absolute inset-0" @click="modalOpen = false; isZoomed = false"></div>

                    <button
                        @click="modalOpen = false; isZoomed = false"
                        class="absolute top-5 right-5 z-50 rounded-full bg-white/10 p-2.5 text-white hover:bg-white/20 transition-colors"
                        title="Tutup (Esc)">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>

                    <div class="absolute top-5 left-5 z-50 text-xs sm:text-sm font-medium text-white/90 bg-black/50 px-3 py-1.5 rounded-full border border-white/10">
                        <span x-text="active + 1"></span> / <span x-text="images.length"></span>
                    </div>

                    <div class="relative z-10 max-h-[80vh] max-w-[85vw] overflow-auto flex items-center justify-center rounded-xl transition-all">
                        <img
                            :src="images[active]"
                            alt="Preview View"
                            @click="isZoomed = !isZoomed"
                            class="rounded-lg object-contain shadow-2xl transition-all duration-300 cursor-zoom-in"
                            :class="isZoomed ? 'max-w-none max-h-none scale-125 cursor-zoom-out' : 'max-h-[75vh] max-w-[80vw]'">
                    </div>

                    <button
                        x-show="images.length > 1"
                        @click.stop="active = active === 0 ? images.length - 1 : active - 1; isZoomed = false"
                        class="absolute left-4 top-1/2 z-20 -translate-y-1/2 rounded-full bg-white/10 p-3 text-white hover:bg-white/20 transition-colors">
                        <svg class="h-6 w-6 sm:h-8 sm:w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                        </svg>
                    </button>

                    <button
                        x-show="images.length > 1"
                        @click.stop="active = active === images.length - 1 ? 0 : active + 1; isZoomed = false"
                        class="absolute right-4 top-1/2 z-20 -translate-y-1/2 rounded-full bg-white/10 p-3 text-white hover:bg-white/20 transition-colors">
                        <svg class="h-6 w-6 sm:h-8 sm:w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </button>
                </div>
            </template>

            <section class="mt-10 rounded-[10px] border-2 border-sky p-4 sm:mt-12 sm:p-5 lg:mt-14">
                <h2 class="text-lg font-semibold text-white">
                    Deskripsi
                </h2>

                <div
                    class="mt-3 text-sm font-medium leading-7 text-white sm:text-base transition-all duration-300"
                    :class="expanded ? 'max-h-none overflow-visible' : 'max-h-[270px] overflow-hidden'">
                    {!! nl2br(e($penawaran->deskripsi)) !!}
                </div>

                <button
                    type="button"
                    @click="expanded = !expanded"
                    class="mt-4 inline-flex items-center gap-2 text-base font-medium text-white transition-colors hover:text-sky sm:text-lg">
                    <span x-text="expanded ? 'Read Less' : 'Read More'">Lihat Selengkapnya...</span>

                    <span class="h-4 w-4 text-muted transition-transform duration-300" :class="expanded ? 'rotate-180' : ''">
                        <svg viewBox="0 0 24 24" fill="currentColor">
                            <path d="M12 16L5 8h14z" />
                        </svg>
                    </span>
                </button>
            </section>
        </div>

        <div class="min-w-0">

            <div class="flex items-center gap-4 rounded-[10px] border-2 border-sky p-4 lg:mt-9">
                {{-- 1. Penyesuaian Route Gambar Profil Penjual --}}
                <img
                    src="{{ $penawaran->Pengguna->gambar ? asset('assets/resource/profil/' . $penawaran->Pengguna->gambar) : asset('assets/img/avatar.png') }}"
                    alt="Avatar Penjual"
                    class="h-11 w-11 shrink-0 rounded-full object-cover object-top ring-1 ring-sky sm:h-12 sm:w-12">

                <div class="min-w-0">
                    <p class="flex flex-wrap items-center gap-2 text-sm font-semibold text-muted">
                        {{ $penawaran->Pengguna->nama ?? $penawaran->Pengguna->username ?? 'Penjual' }}

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

            <div class="mt-6 w-full rounded-[10px] border-2 border-sky p-5 sm:mt-8 sm:p-6 lg:p-8">
                <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between sm:gap-4">
                    <p class="text-lg font-bold text-light sm:text-xl lg:text-xl">
                        Harga
                    </p>

                    <p class="text-lg font-bold text-light sm:text-xl lg:text-xl">
                        Rp {{ number_format((float) $penawaran->harga, 0, ',', '.') }}
                    </p>
                </div>

                <a
                    href="{{ url('pembayaran', \Vinkla\Hashids\Facades\Hashids::encode($penawaran->id_penawaran)) }}"
                    class="btn-animasi mt-6 flex min-h-11 w-full items-center justify-center rounded-[10px] bg-brand px-4 py-2 text-base font-semibold text-white transition-all duration-300 hover:bg-brand/90 hover:shadow-lg hover:shadow-brand/20 sm:text-lg">
                    Beli Sekarang
                </a>

                <ul class="mt-7 space-y-5 sm:mt-8">
                    <li class="flex items-center gap-4">
                        <span class="h-6 w-6 shrink-0 text-brand">
                            <svg viewBox="0 0 24 24" fill="currentColor">
                                <path d="M9 12a5 5 0 1 0 0-10 5 5 0 0 0 0 10zm2.3 2H6.7C3 14 0 17 0 20.7c0 .7.6 1.3 1.3 1.3h11.1a8.6 8.6 0 0 1-1.6-4.8V14zM19 11l-5 2v4.2c0 2.8 1.9 5.4 5 6.3 3.1-.9 5-3.5 5-6.3V13l-5-2z" />
                            </svg>
                        </span>

                        <span class="text-sm font-semibold text-muted sm:text-base">
                            Jaminan Uang Kembali
                        </span>
                    </li>

                    <li class="flex items-center gap-4">
                        <span class="h-6 w-6 shrink-0 text-brand">
                            <svg viewBox="0 0 24 24" fill="currentColor">
                                <path d="M13.5 1.5 4 13.2h6.1L9.4 22.5 19.5 10h-6.6l.6-8.5z" />
                            </svg>
                        </span>

                        <span class="min-w-0 text-sm font-semibold text-muted sm:text-base">
                            Proses Cepat
                        </span>
                    </li>

                    <li class="flex items-center gap-4">
                        <span class="h-6 w-6 shrink-0 text-brand">
                            <svg viewBox="0 0 24 24" fill="currentColor">
                                <path d="M12 2C6.9 2 3 6 3 11v6a3 3 0 0 0 3 3h2v-8H5.2v-1c0-3.8 3-6.8 6.8-6.8s6.8 3 6.8 6.8v1H16v8h2.2a3 3 0 0 0 .8-.1V21a1 1 0 0 1-1 1h-4v2h4a3 3 0 0 0 3-3v-10c0-5-3.9-9-9-9z" />
                            </svg>
                        </span>

                        <span class="text-sm font-semibold text-muted sm:text-base">
                            24/7 Bantuan
                        </span>
                    </li>
                </ul>
            </div>

            {{-- BADGE KEUNGGULAN --}}
            <div class="mt-5 grid grid-cols-1 gap-3 sm:grid-cols-3 sm:gap-4 lg:mt-6">
                <span class="flex min-h-[38px] items-center justify-center rounded-[10px] border-2 border-sky bg-sky/[0.28] px-3 py-2 text-center text-sm font-medium leading-5 text-white sm:text-sm">
                    Akses Email Sepenuhnya
                </span>

                <span class="flex min-h-[38px] items-center justify-center rounded-[10px] border-2 border-sky bg-sky/[0.28] px-3 py-2 text-center text-sm font-medium leading-5 text-white sm:text-sm">
                    Email Asli
                </span>

                <span class="flex min-h-[38px] items-center justify-center rounded-[10px] border-2 border-sky bg-sky/[0.28] px-3 py-2 text-center text-sm font-medium leading-5 text-white sm:text-sm">
                    Penjual Terverifikasi
                </span>
            </div>

        </div>
    </div>
</div>
@endsection