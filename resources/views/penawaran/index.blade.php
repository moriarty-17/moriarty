@extends('template.frontend')
@section('content')
<div class="mx-auto max-w-[1266px] px-4 py-10 sm:px-8 lg:py-14">

    {{-- BUNGKUS SEMUA FILTER DALAM 1 FORM --}}
    <form method="GET" action="{{ url('penawaran') }}" id="filterForm">

        {{-- JUDUL HALAMAN SEKALIGUS DROPDOWN PILIH GAME --}}
        <div class="relative inline-block">
            <select name="game" onchange="document.getElementById('filterForm').submit();"
                class="appearance-none bg-card border-2 border-line hover:border-sky focus:border-sky rounded-[10px] pl-5 pr-11 py-2.5 text-base sm:text-lg font-bold text-light focus:outline-none cursor-pointer transition-all shadow-md">
                <option value="" class="bg-ink text-light">Semua Game</option>
                <option value="Genshin Impact" class="bg-ink text-light" {{ request('game') == 'Genshin Impact' ? 'selected' : '' }}>Genshin Impact</option>
                <option value="Honkai Star Rail" class="bg-ink text-light" {{ request('game') == 'Honkai Star Rail' ? 'selected' : '' }}>Honkai Star Rail</option>
                <option value="Neverness to Everness" class="bg-ink text-light" {{ request('game') == 'Neverness to Everness' ? 'selected' : '' }}>Neverness to Everness</option>
                <option value="Zenless Zone Zero" class="bg-ink text-light" {{ request('game') == 'Zenless Zone Zero' ? 'selected' : '' }}>Zenless Zone Zero</option>
                <option value="Wuthering Waves" class="bg-ink text-light" {{ request('game') == 'Wuthering Waves' ? 'selected' : '' }}>Wuthering Waves</option>
            </select>

            <!-- Ikon Panah Bawah -->
            <span class="absolute right-4 top-1/2 -translate-y-1/2 pointer-events-none text-sky">
                <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"></path>
                </svg>
            </span>
        </div>

        <p class="mt-3 max-w-[430px] text-md font-semibold text-muted lg:text-md">
            Temukan berbagai pilihan akun game terbaik dari seller terpercaya.
        </p>

        <div class="mt-10 flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">

            {{-- Input Pencarian --}}
            <!-- Tambahkan 'group' dan 'hover:border-sky/70' pada bungkus utama -->
            <div class="group flex w-full h-11 max-w-[468px] items-center gap-3 rounded-[10px] border-2 border-muted px-4 hover:border-sky/70 focus-within:border-sky transition-colors bg-panel/50">

                <!-- Tambahkan 'transition-colors', 'group-hover:text-light', dan 'group-focus-within:text-sky' pada ikon -->
                <span class="h-5 w-5 shrink-0 text-muted transition-colors group-hover:text-light group-focus-within:text-sky">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round">
                        <circle cx="11" cy="11" r="7" />
                        <path d="M20 20l-3.5-3.5" />
                    </svg>
                </span>

                <label class="sr-only" for="q">Cari akun</label>
                <input id="q" name="q" type="search" placeholder="Masukkan Kata Kunci..." value="{{ request('q') }}"
                    class="w-full bg-transparent text-base font-medium text-light placeholder:text-muted focus:outline-none">
                <button type="submit" class="hidden"></button>
            </div>

            {{-- Dropdown Urutkan Harga --}}
            <div class="relative w-full lg:w-[228px]">
                <label class="sr-only" for="sort_harga">Urutkan harga</label>
                <select id="sort_harga" name="sort_harga" onchange="document.getElementById('filterForm').submit();"
                    class="appearance-none w-full rounded-[10px] border-2 border-muted bg-transparent pl-4 pr-10 py-2 text-base font-medium text-muted hover:text-light focus:outline-none focus:border-sky hover:border-sky/70 transition-colors cursor-pointer">
                    <option value="" class="bg-ink">Urutkan Harga</option>
                    <option value="termurah" class="bg-ink" {{ request('sort_harga') == 'termurah' ? 'selected' : '' }}>Termurah</option>
                    <option value="termahal" class="bg-ink" {{ request('sort_harga') == 'termahal' ? 'selected' : '' }}>Termahal</option>
                </select>
                <!-- Ikon Panah Bawah -->
                <span class="absolute right-4 top-1/2 -translate-y-1/2 pointer-events-none text-muted">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </span>
            </div>

        </div>

        {{-- BARIS INFO ITEM & FILTER REKOMENDASI --}}
        <div class="mt-6 flex flex-wrap items-center justify-between gap-4 border-b border-line pb-6">
            <p class="text-sm font-medium text-muted sm:text-base">
                {{ number_format($totalItem ?? 0, 0, ',', '.') }} Item Ditemukan
            </p>
        </div>
    </form>

    {{-- GRID PRODUK DINAMIS (DIKEMBALIKAN KE BENTUK ASLI ANDA) --}}
    <div class="mt-8 grid gap-8 lg:grid-cols-3 lg:gap-x-9 lg:gap-y-[34px]">
        @forelse($penawarans ?? [] as $item)
        @php
        $img = $item->gambarPenawaran->sortBy('id_gambar')->first();
        $thumb = $img ? asset('assets/resource/penawaran/' . $img->gambar) : asset('assets/img/listing.jpg');
        @endphp
        <a href="{{ url('penawaran/' . \Vinkla\Hashids\Facades\Hashids::encode($item->id_penawaran))}}"
            class="flex gap-4 rounded-[20px] border-[3px] border-line hover:border-sky/70 bg-card p-1 transition-colors">

            {{-- Dimensi gambar dikembalikan ke ukuran awal Anda --}}
            <img src="{{ $thumb }}" alt="{{ $item->judul }}"
                class="h-[168px] w-[177px] shrink-0 rounded-[18px] object-cover">

            <div class="flex min-w-0 flex-1 flex-col justify-between py-2 pr-3">
                <p class="line-clamp-4 text-base font-xs leading-[25px] text-light">{{ $item->judul }}</p>
                <div>
                    {{-- Container overflow-hidden agar elemen didalamnya tidak melebar melebihi batas card --}}
                    <div class="flex items-center justify-between gap-1 overflow-hidden">

                        <span onclick="event.preventDefault(); window.location.href='{{ route('profil.seller', \Vinkla\Hashids\Facades\Hashids::encode($item->id_pengguna ?? $item->Pengguna->id_pengguna ?? $item->Pengguna->id)) }}';"
                            class="group flex min-w-0 items-center gap-1 text-xs font-semibold text-light hover:text-sky transition-colors cursor-pointer z-10 relative">

                            <span class="truncate group-hover:underline">{{ $item->Pengguna->username ?? 'Seller' }}</span>
                            <span class="h-[11px] w-[11px] shrink-0 text-verify">
                                <svg viewBox="0 0 24 24" fill="currentColor">
                                    <path d="M12 1l2.6 2.1 3.3-.4 1.1 3.2 2.9 1.7-1 3.2 1 3.2-2.9 1.7-1.1 3.2-3.3-.4L12 21l-2.6-2.1-3.3.4-1.1-3.2L2.1 14l1-3.2-1-3.2 2.9-1.7 1.1-3.2 3.3.4L12 1zm-1.2 14.3 5.7-5.7-1.6-1.6-4.1 4.1-2-2-1.6 1.6 3.6 3.6z" />
                                </svg>
                            </span>

                        </span>

                        @php
                        // Mencari ID penjual (pemilik penawaran)
                        $idPenjual = $item->id_pengguna ?? $item->id_user;

                        // Menghitung rata-rata dan total ulasan dari tabel ulasan
                        $avgRating = \App\Models\Ulasan::where('id_penjual', $idPenjual)->avg('rating') ?? 0;
                        $totalUlasan = \App\Models\Ulasan::where('id_penjual', $idPenjual)->count();
                        @endphp

                        <span class="shrink-0 whitespace-nowrap text-xs font-semibold flex items-center gap-1 {{ $avgRating > 0 ? 'text-light' : 'text-muted' }}">
                            <!-- Ikon Bintang -->
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 text-yellow-400" viewBox="0 0 20 20" fill="currentColor">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                            </svg>

                            <!-- Angka Rating (contoh: 4.8) -->
                            <span>{{ number_format($avgRating, 1) }}</span>

                            <!-- Total Ulasan dalam kurung -->
                            <span class="font-normal opacity-70">({{ $totalUlasan }})</span>
                        </span>
                    </div>

                    <p class="mt-1 text-base font-bold text-sky">IDR {{ number_format($item->harga, 0, ',', '.') }}</p>
                </div>
            </div>
        </a>
        @empty
        <div class="col-span-full py-10 text-center">
            <p class="text-lg font-medium text-muted">Maaf, tidak ada penawaran yang sesuai dengan pencarian Anda.</p>
        </div>
        @endforelse
    </div>

    {{-- PAGINATION LARAVEL --}}
    @if(isset($penawarans) && $penawarans->hasPages())
    <div class="mt-12 flex justify-center">
        {{-- Ubah ke onEachSide(1) dan panggil view custom yang sudah dibuat --}}
        {{ $penawarans->onEachSide(1)->links('vendor.pagination.custom') }}
    </div>
    @endif

</div>
@endsection