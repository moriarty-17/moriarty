@extends('template.frontend')

@section('content')
<div class="mx-auto w-full max-w-[1200px] px-4 py-8 sm:px-8 sm:py-10 lg:py-12">

    <!-- BAGIAN 1: PROFIL SELLER -->
    <div class="rounded-t-[10px] border-4 border-b-0 border-line bg-card p-8 flex flex-col items-center justify-center text-center shadow-sm">

        <!-- Gambar Profil -->
        <div class="h-20 w-20 sm:h-28 sm:w-28 overflow-hidden rounded-full border-4 border-panel bg-line shadow-md mb-4 relative">
            @php
            // 1. Sesuaikan nama kolom menjadi 'gambar' sesuai dengan tabel pengguna
            $fotoProfil = $seller->gambar;

            // 2. Tambahkan garis miring (/) setelah nama folder profil
            if ($fotoProfil) {
            $urlFoto = Str::startsWith($fotoProfil, 'http')
            ? $fotoProfil
            : asset('assets/resource/profil/' . $fotoProfil);
            } else {
            $urlFoto = asset('assets/img/default-avatar.png');
            }
            @endphp
            <img src="{{ $urlFoto }}" alt="Avatar Penjual" class="h-full w-full object-cover">
        </div>

        <!-- Nama Seller & Badge -->
        <h1 class="text-xl sm:text-2xl font-bold text-light mb-1">
            {{ $seller->nama ?? $seller->name ?? $seller->username ?? 'Penjual' }}
        </h1>

        <!-- Statistik Bintang & Ulasan -->
        <div class="flex items-center gap-1.5 mt-2">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-yellow-400" viewBox="0 0 20 20" fill="currentColor">
                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
            </svg>
            <span class="text-sm font-semibold text-light">{{ number_format($avgRating, 1) }}</span>
            <span class="text-sm text-muted">({{ $totalUlasan }} Ulasan)</span>
        </div>
    </div>

    <!-- BAGIAN 2: NAVBAR / TAB (Penawaran & Ulasan) -->
    <div class="border-x-4 border-line bg-panel flex items-center justify-center gap-2 sm:gap-8 px-4 border-b border-line shadow-sm">
        <a href="{{ route('profil.seller', ['hash_id' => \Vinkla\Hashids\Facades\Hashids::encode($seller->id ?? $seller->id_pengguna), 'tab' => 'penawaran']) }}"
            class="px-6 py-4 text-sm sm:text-[15px] font-semibold transition-colors border-b-2 {{ $tab == 'penawaran' ? 'border-brand text-brand' : 'border-transparent text-muted hover:text-light' }}">
            Penawaran
        </a>
        <a href="{{ route('profil.seller', ['hash_id' => \Vinkla\Hashids\Facades\Hashids::encode($seller->id ?? $seller->id_pengguna), 'tab' => 'ulasan']) }}"
            class="px-6 py-4 text-sm sm:text-[15px] font-semibold transition-colors border-b-2 {{ $tab == 'ulasan' ? 'border-brand text-brand' : 'border-transparent text-muted hover:text-light' }}">
            Ulasan Penjual
        </a>
    </div>

    <!-- BAGIAN 3: KONTEN DINAMIS -->
    <div class="rounded-b-[10px] border-4 border-t-0 border-line bg-card p-6 sm:p-10 shadow-sm min-h-[400px]">

        {{-- ==================== TAB 1: PENAWARAN (KATALOG PRODUK) ==================== --}}
        @if($tab == 'penawaran')
        <div class="grid grid-cols-2 gap-4 sm:grid-cols-3 lg:grid-cols-4 lg:gap-6 ">
            @forelse($penawarans as $item)
            <a href="{{ route('penawaran.detail_penawaran', \Vinkla\Hashids\Facades\Hashids::encode($item->id_penawaran)) }}" class="group flex flex-col overflow-hidden rounded-lg border-[3px] border-line hover:border-sky/70 bg-panel transition-all hover:-translate-y-1 hover:shadow-lg hover:shadow-brand/20" style="height: 280px !important;">

                @php
                // Logika penarikan gambar penawaran dari relasi
                $gambarUtama = asset('assets/img/listing-wide.jpg'); // Gambar fallback default

                if ($item->gambarPenawaran && $item->gambarPenawaran->isNotEmpty()) {
                $gambarRecord = $item->gambarPenawaran->first();

                $namaFileGambar = $gambarRecord->path_gambar ?? $gambarRecord->gambar ?? null;

                if ($namaFileGambar) {
                $gambarUtama = Str::startsWith($namaFileGambar, 'http')
                ? $namaFileGambar
                : asset('assets/resource/penawaran/' . $namaFileGambar);
                }
                }
                @endphp

                <!-- Container Gambar: Tinggi Tetap 140px -->
                <div class="relative w-full shrink-0 bg-line border-b-2 border-line group-hover:border-brand transition-colors" style="height: 140px !important;">
                    <img src="{{ $gambarUtama }}" alt="Produk" class="h-full w-full object-cover">
                </div>

                <!-- Container Teks -->
                <div class="flex flex-col flex-grow p-3 sm:p-4">
                    <!-- line-clamp-2 akan memotong teks yang lebih dari 2 baris menjadi titik-titik (...) -->
                    <h3 class="text-xs sm:text-sm font-medium text-light line-clamp-2 group-hover:text-brand transition-colors" title="{{ $item->judul_penawaran ?? $item->judul }}">
                        {{ $item->judul_penawaran ?? $item->judul }}
                    </h3>

                    <!-- mt-auto menempelkan harga persis di dasar kartu -->
                    <p class="mt-auto text-[13px] sm:text-sm font-bold text-sky">
                        IDR {{ number_format($item->harga, 0, ',', '.') }}
                    </p>
                </div>
            </a>
            @empty
            <div class="col-span-full py-16 text-center text-muted">
                Penjual ini belum memiliki penawaran yang aktif.
            </div>
            @endforelse
        </div>

        {{-- ==================== TAB 2: ULASAN ==================== --}}
        @elseif($tab == 'ulasan')
        <div class="flex flex-col gap-6">
            @forelse($ulasans as $ulasan)
            <!-- Item Ulasan -->
            <div class="rounded-lg border border-line bg-panel p-5 transition-colors hover:border-white/10">
                <div class="flex flex-col sm:flex-row sm:items-start justify-between gap-4">

                    <div class="flex items-center gap-1">
                        @php
                        // Pastikan rating benar-benar dibaca sebagai angka
                        $ratingUlasan = (int) ($ulasan->rating ?? 0);
                        @endphp

                        @for($i = 1; $i <= 5; $i++)
                            @if($i <=$ratingUlasan)
                            <!-- Bintang Aktif (Kuning) -->
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="#FBBF24" class="w-4 h-4">
                                <path fill-rule="evenodd" d="M10.788 3.21c.448-1.077 1.976-1.077 2.424 0l2.082 5.006 5.404.434c1.164.093 1.636 1.545.749 2.305l-4.117 3.527 1.257 5.273c.271 1.136-.964 2.033-1.96 1.425L12 18.354 7.373 21.18c-.996.608-2.231-.29-1.96-1.425l1.257-5.273-4.117-3.527c-.887-.76-.415-2.212.749-2.305l5.404-.434 2.082-5.005Z" clip-rule="evenodd" />
                            </svg>
                            @else
                            <!-- Bintang Mati (Abu-abu / Redup) -->
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="#374151" class="w-4 h-4 opacity-50">
                                <path fill-rule="evenodd" d="M10.788 3.21c.448-1.077 1.976-1.077 2.424 0l2.082 5.006 5.404.434c1.164.093 1.636 1.545.749 2.305l-4.117 3.527 1.257 5.273c.271 1.136-.964 2.033-1.96 1.425L12 18.354 7.373 21.18c-.996.608-2.231-.29-1.96-1.425l1.257-5.273-4.117-3.527c-.887-.76-.415-2.212.749-2.305l5.404-.434 2.082-5.005Z" clip-rule="evenodd" />
                            </svg>
                            @endif
                            @endfor
                    </div>

                    <!-- Badge Produk yang Dibeli -->
                    <div class="sm:text-right">
                        <span class="inline-flex items-center justify-center rounded-sm bg-card border border-white/5 px-2 py-1 text-[11px] font-medium text-muted line-clamp-1 max-w-[200px]">
                            Barang: {{ $ulasan->pemesanan->penawaran->judul ?? 'Penawaran' }}
                        </span>
                    </div>
                </div>

                <!-- Teks Komentar -->
                @if($ulasan->komentar)
                <div class="mt-4 pt-4 border-t border-line text-sm text-light/90 leading-relaxed">
                    "{{ $ulasan->komentar }}"
                </div>
                @endif
            </div>
            @empty
            <div class="py-16 text-center text-muted">
                Penjual ini belum memiliki ulasan.
            </div>
            @endforelse
        </div>
        @endif

    </div>
</div>
@endsection