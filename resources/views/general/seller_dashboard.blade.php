@extends('template.frontend')
@section('content')

{{-- CSS Khusus untuk mengatasi Tailwind yang tidak ter-compile --}}
<style>
    /* Memaksa lebar sidebar di mode desktop menjadi 260px */
    @media (min-width: 1024px) {
        .sidebar-fixed {
            width: 260px !important;
            flex: 0 0 260px !important;
        }
    }

    /* Mencegah tabel merusak layout, memberi scroll khusus hanya untuk tabel */
    .table-container {
        width: 100%;
        overflow-x: auto;
    }

    /* Pagination kustom jika bawaan Tailwind pecah */
    .pagination {
        display: flex;
        gap: 0.5rem;
        justify-content: center;
        margin-top: 1.5rem;
    }

    .pagination li {
        list-style: none;
    }

    .pagination li a,
    .pagination li span {
        padding: 0.5rem 1rem;
        border-radius: 0.375rem;
        background: #1e293b;
        color: #94a3b8;
        font-size: 0.875rem;
        border: 1px solid #334155;
    }

    .pagination li.active span {
        background: #0ea5e9;
        color: white;
        border-color: #0ea5e9;
    }
</style>

<div class="mx-auto flex max-w-[1440px] flex-col gap-6 px-4 py-8 sm:px-8 lg:flex-row lg:gap-[14px] lg:py-8 overflow-x-hidden">

    {{-- SIDEBAR --}}
    <aside class="h-fit w-full shrink-0 rounded-[10px] border-4 border-line bg-ink p-6 sidebar-fixed">
        <div class="flex items-center gap-3 border-b border-sky/60 pb-4">
            {{-- PERBAIKAN ROUTE GAMBAR PROFIL DI SINI --}}
            <img src="{{ auth()->user()->gambar ? asset('assets/resource/profil/' . auth()->user()->gambar) : asset('assets/img/avatar.png') }}" alt=""
                class="h-[35px] w-[35px] rounded-full object-cover object-top ring-1 ring-sky">
            <span class="flex items-center gap-1 text-base font-semibold text-muted truncate">
                {{ auth()->user()->username ?? 'Seller' }}
                <span class="h-5 w-5 shrink-0 text-verify">
                    <svg viewBox="0 0 24 24" fill="currentColor">
                        <path d="M12 1l2.6 2.1 3.3-.4 1.1 3.2 2.9 1.7-1 3.2 1 3.2-2.9 1.7-1.1 3.2-3.3-.4L12 21l-2.6-2.1-3.3.4-1.1-3.2L2.1 14l1-3.2-1-3.2 2.9-1.7 1.1-3.2 3.3.4L12 1zm-1.2 14.3 5.7-5.7-1.6-1.6-4.1 4.1-2-2-1.6 1.6 3.6 3.6z" />
                    </svg>
                </span>
            </span>
        </div>

        <nav class="mt-6 flex flex-col gap-1">
            <a href="?page=summary"
                class="{{ !request()->query('page') || request()->query('page') === 'summary' ? 'flex items-center gap-3 rounded-md px-2 py-[6px] text-[15px] font-semibold transition-colors bg-sky/15 text-sky font-bold' : 'flex items-center gap-3 rounded-md px-2 py-[6px] text-[15px] font-semibold transition-colors text-muted hover:text-light' }}">
                <span class="h-5 w-5"><svg viewBox="0 0 24 24" fill="currentColor">
                        <path d="M12 3a10 10 0 0 0-8.7 15h17.4A10 10 0 0 0 12 3zm1.4 11.6a2 2 0 1 1-2.6-2.9l5.3-4.2-2.7 7.1z" />
                    </svg></span>Dashboard
            </a>

            <a href="?page=penawaran"
                class="{{ request()->query('page') === 'penawaran' ? 'flex items-center gap-3 rounded-md px-2 py-[6px] text-[15px] font-semibold transition-colors bg-sky/15 text-sky font-bold' : 'flex items-center gap-3 rounded-md px-2 py-[6px] text-[15px] font-semibold transition-colors text-muted hover:text-light' }}">
                <span class="h-5 w-5"><svg viewBox="0 0 24 24" fill="currentColor">
                        <path d="M11.6 2H4.5A2.5 2.5 0 0 0 2 4.5v7.1c0 .7.3 1.3.7 1.8l8.4 8.4c1 1 2.6 1 3.5 0l6.6-6.6c1-1 1-2.6 0-3.5l-8.4-8.4c-.5-.5-1.1-.8-1.8-.8zM6.8 8a1.8 1.8 0 1 1 0-3.5 1.8 1.8 0 0 1 0 3.5z" />
                    </svg></span>Penawaran
            </a>

            {{-- MENU PESANAN BARU --}}
            <a href="?page=pesanan"
                class="{{ request()->query('page') === 'pesanan' ? 'flex items-center gap-3 rounded-md px-2 py-[6px] text-[15px] font-semibold transition-colors bg-sky/15 text-sky font-bold' : 'flex items-center gap-3 rounded-md px-2 py-[6px] text-[15px] font-semibold transition-colors text-muted hover:text-light' }}">
                <span class="h-5 w-5"><svg viewBox="0 0 24 24" fill="currentColor">
                        <path d="M19 3h-4.18C14.4 1.84 13.3 1 12 1c-1.3 0-2.4.84-2.82 2H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-7 0c.55 0 1 .45 1 1s-.45 1-1 1-1-.45-1-1 .45-1 1-1zm2 14H7v-2h7v2zm3-4H7v-2h10v2zm0-4H7V7h10v2z" />
                    </svg></span>Pesanan
            </a>

            <a href="?page=wallet"
                class="{{ request()->query('page') === 'wallet' ? 'flex items-center gap-3 rounded-md px-2 py-[6px] text-[15px] font-semibold transition-colors bg-sky/15 text-sky font-bold' : 'flex items-center gap-3 rounded-md px-2 py-[6px] text-[15px] font-semibold transition-colors text-muted hover:text-light' }}">
                <span class="h-5 w-5"><svg viewBox="0 0 24 24" fill="currentColor">
                        <path d="M20 6H5.5a1.5 1.5 0 0 1 0-3H20V1H5.5A3.5 3.5 0 0 0 2 4.5v15A3.5 3.5 0 0 0 5.5 23H20a2 2 0 0 0 2-2V8a2 2 0 0 0-2-2zm-3 9.5a1.5 1.5 0 1 1 0-3 1.5 1.5 0 0 1 0 3z" />
                    </svg></span>Wallet
            </a>
        </nav>
    </aside>

    {{-- MAIN CONTENT --}}
    <div class="min-w-0 flex-1">

        {{-- 1. TAB DASHBOARD --}}
        @if (!request()->query('page') || request()->query('page') === 'summary')
        <section class="w-full rounded-[10px] border-4 border-line p-6 lg:p-8">
            <div class="flex flex-wrap items-center justify-between gap-4">
                <h1 class="text-xl font-semibold text-light">Dashboard</h1>

                {{-- Dropdown Filter Waktu --}}
                <form action="" method="GET" class="flex items-center gap-3">
                    <label for="filter_waktu" class="text-sm font-medium text-muted">Periode:</label>
                    <div class="relative">
                        <select name="waktu" id="filter_waktu" onchange="this.form.submit()"
                            class="min-h-10 cursor-pointer appearance-none rounded-lg border border-line bg-panel py-2 pl-4 pr-10 text-sm font-semibold text-light outline-none ring-0 transition-all focus:border-sky">
                            <option value="semua" {{ request('waktu') == 'semua' || !request('waktu') ? 'selected' : '' }}>Semua Waktu</option>
                            <option value="30_hari" {{ request('waktu') == '30_hari' ? 'selected' : '' }}>30 Hari Terakhir</option>
                            <option value="90_hari" {{ request('waktu') == '90_hari' ? 'selected' : '' }}>90 Hari Terakhir</option>
                            <option value="1_tahun" {{ request('waktu') == '1_tahun' ? 'selected' : '' }}>1 Tahun Terakhir</option>
                        </select>
                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-muted">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </div>
                    </div>
                </form>
            </div>

            <div class="mt-8 w-full">
                <div class="grid gap-6 sm:grid-cols-2 xl:grid-cols-3">

                    {{-- Card 1: Penjualan --}}
                    <div class="flex items-center justify-between rounded-[10px] border-[3px] border-line bg-card p-5 transition-transform hover:-translate-y-1 hover:shadow-lg">
                        <div>
                            <p class="text-lg font-semibold text-muted">Penjualan</p>
                            <p class="mt-1 text-[26px] font-bold text-white">{{ $totalPenjualan ?? 0 }}</p>
                        </div>
                        <div class="flex h-14 w-14 items-center justify-center rounded-full bg-brand/10 text-brand">
                            <svg class="h-7 w-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="9" cy="21" r="1"></circle>
                                <circle cx="20" cy="21" r="1"></circle>
                                <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path>
                            </svg>
                        </div>
                    </div>

                    {{-- Card 2: Penawaran Aktif --}}
                    <div class="flex items-center justify-between rounded-[10px] border-[3px] border-line bg-card p-5 transition-transform hover:-translate-y-1 hover:shadow-lg">
                        <div>
                            <p class="text-lg font-semibold text-muted">Penawaran Aktif</p>
                            <p class="mt-1 text-[26px] font-bold text-white">{{ $penawaranAktif ?? 0 }}</p>
                        </div>
                        <div class="flex h-14 w-14 items-center justify-center rounded-full bg-ok/10 text-ok">
                            <svg class="h-7 w-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"></path>
                                <line x1="7" y1="7" x2="7.01" y2="7"></line>
                            </svg>
                        </div>
                    </div>

                    {{-- Card 3: Pendapatan --}}
                    <div class="flex items-center justify-between rounded-[10px] border-[3px] border-line bg-card p-5 transition-transform hover:-translate-y-1 hover:shadow-lg">
                        <div>
                            <p class="text-lg font-semibold text-muted">Pendapatan</p>
                            <p class="mt-1 text-[26px] font-bold text-sky">IDR {{ number_format(auth()->user()->saldo ?? 0, 0, ',', '.') }}</p>
                        </div>
                        <div class="flex h-14 w-14 items-center justify-center rounded-full bg-sky/10 text-sky">
                            <svg class="h-7 w-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <rect x="2" y="5" width="20" height="14" rx="2"></rect>
                                <line x1="2" y1="10" x2="22" y2="10"></line>
                            </svg>
                        </div>
                    </div>

                </div>
            </div>
        </section>

        {{-- 2. TAB PENAWARAN --}}
        @elseif(request()->query('page') === 'penawaran')
        <section class="w-full rounded-[10px] border-4 border-line p-6 lg:p-8">
            <div class="flex flex-wrap items-center justify-between gap-4">
                <h1 class="text-xl font-semibold text-light">Penawaran Saya</h1>
                <a href="{{ url('buat-penawaran') }}"
                    class="inline-flex items-center justify-center rounded-[5px] bg-brand px-6 py-2 font-semibold text-white transition-colors hover:bg-brand/85 text-lg btn-animasi">Buat Penawaran</a>
            </div>

            <div class="mt-8 flex flex-wrap items-center gap-4">
                <form method="GET" action="{{ url('seller-dashboard') }}" class="flex flex-wrap gap-4 w-full">
                    <input type="hidden" name="page" value="penawaran">
                    <div class="relative">
                        <select name="status" onchange="this.form.submit()" class="min-h-10 cursor-pointer appearance-none rounded-lg border border-line bg-panel py-2 pl-4 pr-10 text-sm font-semibold text-light outline-none ring-0 transition-all focus:border-sky">
                            <option value="">Semua Status</option>
                            <option value="aktif" {{ request('status') == 'aktif' ? 'selected' : '' }}>Aktif</option>
                            <option value="dijeda" {{ request('status') == 'dijeda' ? 'selected' : '' }}>Dijeda</option>
                            <option value="sold" {{ request('status') == 'sold' ? 'selected' : '' }}>Sold</option>
                        </select>
                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-muted">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="relative">
                        <select name="sort" onchange="this.form.submit()" class="min-h-10 cursor-pointer appearance-none rounded-lg border border-line bg-panel py-2 pl-4 pr-10 text-sm font-semibold text-light outline-none ring-0 transition-all focus:border-sky">
                            <option value="terbaru" {{ request('sort') == 'terbaru' ? 'selected' : '' }}>Terbaru</option>
                            <option value="terlama" {{ request('sort') == 'terlama' ? 'selected' : '' }}>Terlama</option>
                        </select>
                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-muted">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </div>
                    </div>

                </form>
            </div>

            <div class="mt-8 table-container">
                <table class="w-full border-collapse text-left whitespace-nowrap" style="min-width: 800px;">
                    <thead>
                        <tr class="border-[3px] border-line">
                            <th scope="col" class="px-3 py-[13px] text-center text-[15px] font-semibold text-muted">Penawaran</th>
                            <th scope="col" class="px-3 py-[13px] text-center text-[15px] font-semibold text-muted">Judul</th>
                            <th scope="col" class="px-3 py-[13px] text-center text-[15px] font-semibold text-muted">Game</th>
                            <th scope="col" class="px-3 py-[13px] text-center text-[15px] font-semibold text-muted">Harga</th>
                            <th scope="col" class="px-3 py-[13px] text-center text-[15px] font-semibold text-muted">Status</th>
                            <th scope="col" class="px-3 py-[13px] text-center text-[15px] font-semibold text-muted">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($penawarans ?? [] as $item)
                        @php
                        $img = $item->gambarPenawaran->sortBy('id_gambar')->first();
                        $thumb = $img ? asset('assets/resource/penawaran/' . $img->gambar) : asset('assets/img/listing-wide.jpg');
                        $encodedId = \Vinkla\Hashids\Facades\Hashids::encode($item->id_penawaran);
                        @endphp
                        <tr class="border-[3px] border-line hover:bg-card">
                            <td class="px-3 py-3 text-center text-xs font-semibold text-light">
                                <img src="{{ $thumb }}" alt="{{ $item->judul }}" class="h-[60px] w-[90px] rounded-sm object-cover mx-auto">
                            </td>
                            <td class="px-3 py-3 text-center text-xs font-semibold text-light">
                                <span class="block max-w-[204px] mx-auto leading-5 overflow-hidden text-ellipsis">{{ $item->judul }}</span>
                            </td>
                            <td class="px-3 py-3 text-center text-xs font-semibold text-light">{{ $item->jenis ?? 'Game' }}</td>
                            <td class="px-3 py-3 text-center text-xs font-semibold text-light">IDR {{ number_format($item->harga, 0, ',', '.') }}</td>
                            <td class="px-3 py-3 text-center text-xs font-semibold text-light">
                                <span class="inline-flex min-w-[90px] items-center justify-center rounded-[5px] px-3 py-[2px] text-xs font-semibold 
                        {{ $item->status == 'sold' ? 'bg-bad/[0.28] text-[#EEEEEE]' : ($item->status == 'dijeda' ? 'bg-yellow-500/[0.28] text-yellow-400' : 'bg-ok/[0.28] text-ok') }}">
                                    {{ ucfirst($item->status) }}
                                </span>
                            </td>
                            <td class="px-3 py-3 text-center text-xs font-semibold text-light">
                                <div class="flex items-center justify-center gap-3">
                                    <!-- Tombol Lihat -->
                                    <a href="{{ url('penawaran/' . $encodedId) }}"
                                        class="flex h-7 w-7 items-center justify-center rounded-lg shadow-sm transition-all duration-300 hover:-translate-y-1 hover:shadow-lg focus:outline-none"
                                        style="background-color: var(--color-sky); color: var(--color-navy);"
                                        title="Lihat">
                                        <svg viewBox="0 0 24 24" fill="currentColor" class="h-5 w-5">
                                            <path d="M12 4.5C7 4.5 2.73 7.61 1 12c1.73 4.39 6 7.5 11 7.5s9.27-3.11 11-7.5c-1.73-4.39-6-7.5-11-7.5zM12 17c-2.76 0-5-2.24-5-5s2.24-5 5-5 5 2.24 5 5-2.24 5-5 5zm0-8c-1.66 0-3 1.34-3 3s1.34 3 3 3 3-1.34 3-3-1.34-3-3-3z" />
                                        </svg>
                                    </a>

                                    @if($item->status != 'sold')
                                    <!-- Tombol Edit (Ikon Pensil sudah diperkecil ke h-4 w-4) -->
                                    <a href="{{ route('seller.penawaran.edit', $encodedId) }}"
                                        class="flex h-7 w-7 items-center justify-center rounded-lg shadow-sm transition-all duration-300 hover:-translate-y-1 hover:shadow-lg focus:outline-none"
                                        style="background-color: var(--color-warn); color: var(--color-navy);"
                                        title="Edit">
                                        <svg viewBox="0 0 24 24" fill="currentColor" class="h-4 w-4">
                                            <path d="M3 17.25V21h3.75L17.81 9.94l-3.75-3.75L3 17.25zM20.71 7.04c.39-.39.39-1.02 0-1.41l-2.34-2.34c-.39-.39-1.02-.39-1.41 0l-1.83 1.83 3.75 3.75 1.83-1.83z" />
                                        </svg>
                                    </a>

                                    <!-- Tombol Jeda / Aktifkan -->
                                    <form action="{{ route('seller.penawaran.jeda', $encodedId) }}" method="POST" class="m-0 flex p-0">
                                        @csrf
                                        <button type="submit"
                                            class="flex h-7 w-7 cursor-pointer items-center justify-center rounded-lg shadow-sm transition-all duration-300 hover:-translate-y-1 hover:shadow-lg focus:outline-none"
                                            style="background-color: var(--color-brand); color: var(--color-light);"
                                            title="{{ $item->status == 'dijeda' ? 'Aktifkan' : 'Jeda' }}">
                                            @if($item->status == 'dijeda')
                                            <!-- Ikon Play -->
                                            <svg viewBox="0 0 24 24" fill="currentColor" class="h-5 w-5 translate-x-[1px]">
                                                <path d="M8 5v14l11-7z" />
                                            </svg>
                                            @else
                                            <!-- Ikon Pause -->
                                            <svg viewBox="0 0 24 24" fill="currentColor" class="h-5 w-5">
                                                <path d="M6 19h4V5H6v14zm8-14v14h4V5h-4z" />
                                            </svg>
                                            @endif
                                        </button>
                                    </form>
                                    @endif

                                    <!-- Tombol Hapus -->
                                    <form action="{{ route('seller.penawaran.hapus', $encodedId) }}" method="POST" class="m-0 flex p-0" onsubmit="return confirm('Yakin ingin menghapus penawaran ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="flex h-7 w-7 cursor-pointer items-center justify-center rounded-lg shadow-sm transition-all duration-300 hover:-translate-y-1 hover:shadow-lg focus:outline-none"
                                            style="background-color: var(--color-bad); color: var(--color-light);"
                                            title="Hapus">
                                            <svg viewBox="0 0 24 24" fill="currentColor" class="h-5 w-5">
                                                <path d="M6 19c0 1.1.9 2 2 2h8c1.1 0 2-.9 2-2V7H6v12zM19 4h-3.5l-1-1h-5l-1 1H5v2h14V4z" />
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr class="border-[3px] border-line">
                            <td colspan="6" class="px-3 py-8 text-center text-sm font-semibold text-muted">Belum ada penawaran.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination (Sudah Ditambahkan appends) --}}
            @if(isset($penawarans) && $penawarans->hasPages())
            <div class="mt-6 flex justify-center">
                {{ $penawarans->appends(request()->query())->onEachSide(1)->links('vendor.pagination.custom') }}
            </div>
            @endif
        </section>

        {{-- 3. TAB PESANAN (BARU) --}}
        @elseif(request()->query('page') === 'pesanan')
        <section class="min-w-0 w-full flex-1 rounded-[10px] border-4 border-line p-6 lg:p-8">
            <div class="flex flex-wrap items-center justify-between gap-4">
                <h1 class="text-xl font-semibold text-light">Pesanan Masuk</h1>
            </div>

            <div class="mt-8 flex flex-wrap items-end justify-between gap-4">
                {{-- Form Filter Start Date & End Date --}}
                <form method="GET" action="{{ url('seller-dashboard') }}" class="flex flex-wrap items-end gap-4">
                    <input type="hidden" name="page" value="pesanan">

                    <div class="flex flex-col gap-1">
                        <label for="start_date" class="text-xs font-semibold text-muted">Tanggal Mulai:</label>
                        <input type="date" name="start_date" id="start_date" value="{{ request('start_date') }}"
                            class="rounded-sm border-[3px] border-line bg-card px-4 py-[6px] text-[15px] font-medium text-muted focus:border-brand focus:outline-none">
                    </div>

                    <div class="flex flex-col gap-1">
                        <label for="end_date" class="text-xs font-semibold text-muted">Tanggal Akhir:</label>
                        <input type="date" name="end_date" id="end_date" value="{{ request('end_date') }}"
                            class="rounded-sm border-[3px] border-line bg-card px-4 py-[6px] text-[15px] font-medium text-muted focus:border-brand focus:outline-none">
                    </div>

                    <button type="submit" class="btn-animasi inline-flex h-[42px] items-center justify-center rounded-[5px] bg-brand px-6 font-semibold text-white transition-colors hover:bg-brand/85 text-[15px]">
                        Terapkan Filter
                    </button>

                    @if(request('start_date') || request('end_date'))
                    <a href="{{ url('seller-dashboard?page=pesanan') }}" class="inline-flex h-[42px] items-center justify-center rounded-[5px] bg-line px-6 font-semibold text-light transition-colors hover:bg-line/80 text-[15px]">
                        Reset
                    </a>
                    @endif
                </form>

                <button type="button" onclick="document.getElementById('modal-faktur-seller').style.display='flex'"
                    style="cursor: pointer;" class="border border-[14px] border-muted ml-auto flex h-[42px] items-center justify-center gap-2 rounded-[5px] bg-sky px-6 text-[15px] font-semibold text-white shadow-sm transition-colors hover:bg-sky/90">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                    </svg>
                    Unduh Faktur
                </button>
            </div>

            <div class="mt-8 table-container">
                <table class="w-full border-collapse text-left whitespace-nowrap" style="min-width: 800px;">
                    <thead>
                        <tr class="border-[3px] border-line">
                            <th scope="col" class="px-3 py-[13px] text-center text-[15px] font-semibold text-muted">Penawaran</th>
                            <th scope="col" class="px-3 py-[13px] text-center text-[15px] font-semibold text-muted">Tanggal</th>
                            <th scope="col" class="px-3 py-[13px] text-center text-[15px] font-semibold text-muted">Pembeli</th>
                            <th scope="col" class="px-3 py-[13px] text-center text-[15px] font-semibold text-muted">Harga</th>
                            <th scope="col" class="px-3 py-[13px] text-center text-[15px] font-semibold text-muted">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($pesanans ?? [] as $pesanan)
                        <tr class="border-[3px] border-line hover:bg-card">

                            {{-- CSS Murni (Inline) membiarkan teks turun baris (multiline) --}}
                            <td class="px-3 py-3 text-center text-xs font-semibold text-light">
                                <span style="display: block; max-width: 250px; margin: 0 auto; white-space: normal; line-height: 1.4;">
                                    {{ $pesanan->penawaran->judul ?? '-' }}
                                </span>
                            </td>

                            <td class="px-3 py-3 text-center text-xs font-semibold text-light">
                                {{ \Carbon\Carbon::parse($pesanan->waktu_pemesanan ?? $pesanan->created_at)->translatedFormat('d M Y, H:i') }}
                            </td>

                            <td class="px-3 py-3 text-center text-xs font-semibold text-light">
                                {{ $pesanan->pengguna->nama ?? $pesanan->pengguna->username ?? 'User ID: ' . ($pesanan->id_user ?? $pesanan->id_pengguna) }}
                            </td>

                            <td class="px-3 py-3 text-center text-xs font-semibold text-light">
                                IDR {{ number_format($pesanan->pembayaran->harga ?? $pesanan->penawaran->harga ?? 0, 0, ',', '.') }}
                            </td>
                            <td class="px-3 py-3 text-center text-xs font-semibold text-light">
                                <span class="inline-flex min-w-[90px] items-center justify-center rounded-[5px] px-2 py-[2px] text-xs font-semibold {{ in_array(strtolower($pesanan->status), ['selesai', 'completed', 'sukses']) ? 'bg-ok/[0.28] text-ok' : (in_array(strtolower($pesanan->status), ['batal', 'failed', 'gagal']) ? 'bg-bad/[0.28] text-bad' : 'bg-warn/[0.28] text-warn') }}">
                                    {{ ucfirst($pesanan->status) }}
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr class="border-[3px] border-line">
                            <td colspan="5" class="px-3 py-8 text-center text-sm font-semibold text-muted">Belum ada pesanan pada periode ini.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            @if(isset($pesanans) && $pesanans->hasPages())
            <div class="mt-6 flex justify-center">
                {{ $pesanans->onEachSide(1)->links('vendor.pagination.custom') }}
            </div>
            @endif
        </section>

        {{-- 4. TAB WALLET --}}
        @elseif(request()->query('page') === 'wallet')
        <section class="min-w-0 w-full flex-1 rounded-[10px] border-4 border-line p-6 lg:p-8">
            <div class="flex flex-wrap items-center justify-between gap-4">
                <h1 class="text-xl font-semibold text-light">Wallet</h1>
                <div class="flex items-center gap-4">
                    <span class="text-xl font-semibold text-sky">IDR {{ number_format(auth()->user()->saldo ?? 0, 0, ',', '.') }}</span>
                    <a href="{{ url('seller/withdraw') }}"
                        class="inline-flex items-center justify-center rounded-[5px] bg-brand px-6 py-2 font-semibold text-white transition-colors hover:bg-brand/85 text-base btn-animasi">Withdraw</a>
                </div>
            </div>

            <div class="mt-8 flex flex-wrap items-center gap-4">
                <form method="GET" action="{{ url('seller-dashboard') }}" class="flex flex-wrap gap-4 w-full">
                    <input type="hidden" name="page" value="wallet">

                    <div class="relative">
                        <select name="tipe" onchange="this.form.submit()" class="min-h-10 cursor-pointer appearance-none rounded-lg border border-line bg-panel py-2 pl-4 pr-10 text-sm font-semibold text-light outline-none ring-0 transition-all focus:border-sky">
                            <option value="">Semua Mutasi</option>
                            <option value="masuk" {{ request('tipe') == 'masuk' ? 'selected' : '' }}>Masuk</option>
                            <option value="keluar" {{ request('tipe') == 'keluar' ? 'selected' : '' }}>Keluar</option>
                        </select>
                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-muted">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </div>
                    </div>
                </form>
            </div>

            <div class="mt-8 table-container">
                <table class="w-full border-collapse text-left whitespace-nowrap" style="min-width: 700px;">
                    <thead>
                        <tr class="border-[3px] border-line">
                            <th scope="col" class="px-3 py-[13px] text-center text-[15px] font-semibold text-muted">Tanggal</th>
                            <th scope="col" class="px-3 py-[13px] text-center text-[15px] font-semibold text-muted">Perubahan Saldo</th>
                            <th scope="col" class="px-3 py-[13px] text-center text-[15px] font-semibold text-muted">Kode Pesanan & Pencairan</th>
                            <th scope="col" class="px-3 py-[13px] text-center text-[15px] font-semibold text-muted">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($mutasiWallet ?? [] as $mutasi)
                        <tr class="border-[3px] border-line hover:bg-card">
                            <td class="px-3 py-3 text-center text-xs font-semibold text-light">
                                {{ \Carbon\Carbon::parse($mutasi['tanggal'])->translatedFormat('d M Y, H:i') }}
                            </td>

                            {{-- Nominal Plus/Minus --}}
                            <td class="px-3 py-3 text-center text-xs font-semibold {{ $mutasi['tipe'] == 'masuk' ? 'text-ok' : 'text-bad' }}">
                                {{ $mutasi['tipe'] == 'masuk' ? '+' : '-' }}IDR {{ number_format($mutasi['nominal'], 0, ',', '.') }}
                            </td>

                            {{-- Keterangan berupa Kode --}}
                            <td class="px-3 py-3 text-center text-xs font-semibold text-light">
                                {{ $mutasi['keterangan'] }}
                            </td>

                            {{-- Status --}}
                            <td class="px-3 py-3 text-center text-xs font-semibold text-light">
                                <span class="inline-flex min-w-[90px] items-center justify-center rounded-[5px] px-2 py-[2px] text-xs font-semibold {{ in_array(strtolower($mutasi['status']), ['selesai', 'completed', 'sukses']) ? 'bg-ok/[0.28] text-ok' : 'bg-warn/[0.28] text-warn' }}">
                                    {{ ucfirst($mutasi['status']) }}
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr class="border-[3px] border-line">
                            <td colspan="4" class="px-3 py-8 text-center text-sm font-semibold text-muted">Belum ada transaksi di wallet.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            @if(isset($mutasiWallet) && $mutasiWallet->hasPages())
            <div class="mt-6 flex justify-center">
                {{-- Memanggil desain custom dan menampilkan 1 angka di kiri/kanan --}}
                {{ $mutasiWallet->onEachSide(1)->links('vendor.pagination.custom') }}
            </div>
            @endif
        </section>
        @endif
    </div>
</div>
<div id="modal-faktur-seller" style="display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0; width: 100vw; height: 100vh; z-index: 9999; background-color: rgba(0, 0, 0, 0.6); backdrop-filter: blur(4px); align-items: center; justify-content: center; padding: 16px; box-sizing: border-box;">

    <!-- Kotak Modal -->
    <div style="width: 100%; max-width: 420px; background-color: var(--color-card, #1e293b); border: 1px solid var(--color-line, #334155); border-radius: 12px; padding: 24px; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25); position: relative;">

        <!-- Header Modal -->
        <div style="margin-bottom: 24px; display: flex; align-items: center; justify-content: space-between; gap: 8px;">
            <h3 style="font-size: 16px; font-weight: 600; line-height: 1.25; color: var(--color-light, #f8fafc); margin: 0;">Unduh faktur penjualan bulanan Anda</h3>
            <button type="button" onclick="document.getElementById('modal-faktur-seller').style.display='none'" style="color: var(--color-muted, #94a3b8); background: transparent; border: none; cursor: pointer; padding: 0; outline: none; flex-shrink: 0;">
                <svg xmlns="http://www.w3.org/2000/svg" style="height: 20px; width: 20px;" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                </svg>
            </button>
        </div>

        {{-- Form Aksi --}}
        <form action="{{ route('seller.faktur.download') }}" method="GET">
            <!-- DROPDOWN BULAN & TAHUN -->
            <div style="margin-bottom: 32px; position: relative;">
                <label style="display: block; margin-bottom: 8px; font-size: 13px; font-weight: 500; color: var(--color-light, #f8fafc);">Pilih satu bulan</label>

                @php
                $start = \Carbon\Carbon::create(2026, 10, 1);
                $now = \Carbon\Carbon::now()->startOfMonth();
                $defaultVal = $now->format('Y-m');
                $defaultLabel = $now->translatedFormat('F Y');
                @endphp

                <input type="hidden" name="periode" id="input_periode_seller" value="{{ $defaultVal }}">

                <!-- Tombol Dropdown -->
                <button id="btn_toggle_seller" type="button" onclick="toggleDropdownPeriodeSeller()" style="display: flex; width: 100%; align-items: center; justify-content: space-between; border-radius: 8px; padding: 12px 16px; font-size: 14px; color: var(--color-light, #f8fafc); background-color: var(--color-panel, #0f172a); border: 2px solid var(--color-brand, #3b82f6); cursor: pointer; outline: none;">
                    <span id="text_periode_seller" style="opacity: 0.9;">{{ $defaultLabel }}</span>
                    <svg style="height: 16px; width: 16px; color: var(--color-muted, #94a3b8); pointer-events: none;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>

                <!-- Menu Dropdown -->
                <ul id="menu_periode_seller" style="position: absolute; left: 0; top: 105%; z-index: 50; margin-top: 4px; display: none; width: 100%; max-height: 180px; overflow-y: auto; border-radius: 8px; box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1); background-color: var(--color-panel, #0f172a); border: 1px solid var(--color-line, #334155); padding: 0; list-style: none;">
                    @php
                    $loopDate = $now->copy();
                    $first = true;
                    @endphp

                    @while($loopDate->gte($start))
                    @php
                    $val = $loopDate->format('Y-m');
                    $lbl = $loopDate->translatedFormat('F Y');
                    @endphp
                    <li onclick="pilihPeriodeSeller('{{ $val }}', '{{ $lbl }}')" onmouseover="this.style.backgroundColor='var(--color-line, #334155)'" onmouseout="this.style.backgroundColor='transparent'" style="display: flex; cursor: pointer; align-items: center; justify-content: space-between; padding: 12px 16px; font-size: 14px; color: var(--color-light, #f8fafc); border-bottom: 1px solid var(--color-line, #334155); transition: background-color 0.2s;">
                        <span>{{ $lbl }}</span>
                        @if($first)
                        <!-- Centang Biru -->
                        <svg style="height: 16px; width: 16px; color: var(--color-brand, #3b82f6);" fill="none" stroke="currentColor" viewBox="0 0 24 24" id="check-icon-{{ $val }}">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        @endif
                    </li>
                    @php
                    $loopDate->subMonth();
                    $first = false;
                    @endphp
                    @endwhile
                </ul>
            </div>

            <!-- Tombol Aksi -->
            <div style="display: flex; justify-content: flex-end; gap: 12px; margin-top: 16px;">
                <button type="button" onclick="document.getElementById('modal-faktur-seller').style.display='none'"
                    style="border-radius: 8px; padding: 10px 20px; font-size: 14px; font-weight: 600; color: var(--color-muted, #94a3b8); background-color: transparent; border: none; cursor: pointer; transition: opacity 0.2s;" onmouseover="this.style.opacity='0.8'" onmouseout="this.style.opacity='1'">
                    Batal
                </button>
                <button type="submit" onclick="document.getElementById('modal-faktur-seller').style.display='none'"
                    style="border-radius: 8px; background-color: var(--color-brand, #3b82f6); padding: 10px 24px; font-size: 14px; font-weight: bold; color: #ffffff; border: none; cursor: pointer; box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05); transition: transform 0.2s;" onmouseover="this.style.transform='scale(1.05)'" onmouseout="this.style.transform='scale(1)'">
                    Unduh faktur
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
<script>
    // JS Manual untuk Toggle Dropdown Inline
    function toggleDropdownPeriodeSeller() {
        const menu = document.getElementById('menu_periode_seller');
        if (menu.style.display === 'none') {
            menu.style.display = 'block';
        } else {
            menu.style.display = 'none';
        }
    }

    function pilihPeriodeSeller(val, label) {
        document.getElementById('input_periode_seller').value = val;
        document.getElementById('text_periode_seller').innerText = label;
        document.getElementById('menu_periode_seller').style.display = 'none';
    }

    // Tutup dropdown jika klik di luar elemen
    document.addEventListener('click', function(event) {
        const menu = document.getElementById('menu_periode_seller');
        const button = document.getElementById('btn_toggle_seller');

        if (menu && button && menu.style.display === 'block') {
            if (!button.contains(event.target) && !menu.contains(event.target)) {
                menu.style.display = 'none';
            }
        }
    });
</script>