@extends('template.backend')
@section('content')
<div class="p-4 mx-auto max-w-(--breakpoint-2xl) md:p-6">
    <div class="space-y-5 sm:space-y-6">

        {{-- ====== KARTU RINGKASAN PENDAPATAN (Baru) ====== --}}
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3">
            <!-- Card Pendapatan Platform -->
            <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03]">
                <div class="flex items-center gap-4">
                    <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-success-50 text-success-600 dark:bg-success-500/15 dark:text-success-500">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Pendapatan (Pajak)</p>
                        <h4 class="text-xl font-bold text-gray-800 dark:text-white/90">
                            IDR {{ number_format($totalPendapatan ?? 0, 0, ',', '.') }}
                        </h4>
                    </div>
                </div>
            </div>

            <!-- Card Total Transaksi Sukses -->
            <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03]">
                <div class="flex items-center gap-4">
                    <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-brand/10 text-brand dark:bg-brand/20">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Pesanan Selesai</p>
                        <h4 class="text-xl font-bold text-gray-800 dark:text-white/90">
                            {{ number_format($totalTransaksiSukses ?? 0, 0, ',', '.') }} Transaksi
                        </h4>
                    </div>
                </div>
            </div>
        </div>
        {{-- ====== END KARTU RINGKASAN ====== --}}


        <div class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
            <div class="flex flex-wrap items-center justify-between gap-4 px-5 py-4 sm:px-6 sm:py-5 border-b border-gray-100 dark:border-gray-800">
                <h3 class="text-base font-medium text-gray-800 dark:text-white/90">
                    Rincian Pendapatan Platform
                </h3>
            </div>

            <div class="p-4 sm:p-6">

                {{-- ====== Filter Section Start ====== --}}
                <div class="mb-6 flex flex-wrap items-center gap-4">
                    <form method="GET" action="{{ url()->current() }}" class="flex flex-wrap items-end gap-4 w-full">

                        <div class="flex flex-col gap-1">
                            <label for="start_date" class="text-xs font-semibold text-gray-500 dark:text-gray-400">Tanggal Mulai:</label>
                            <input type="date" name="start_date" id="start_date" value="{{ request('start_date') }}"
                                onclick="this.showPicker()"
                                class="cursor-pointer rounded-lg border border-gray-200 bg-gray-50 px-4 py-2 text-sm font-medium text-gray-700 focus:border-brand-500 focus:ring-brand-500 dark:border-gray-700 dark:bg-gray-800/50 dark:text-gray-300 dark:focus:border-brand-500">
                        </div>

                        <div class="flex flex-col gap-1">
                            <label for="end_date" class="text-xs font-semibold text-gray-500 dark:text-gray-400">Tanggal Akhir:</label>
                            <input type="date" name="end_date" id="end_date" value="{{ request('end_date') }}"
                                onclick="this.showPicker()"
                                class="cursor-pointer rounded-lg border border-gray-200 bg-gray-50 px-4 py-2 text-sm font-medium text-gray-700 focus:border-brand-500 focus:ring-brand-500 dark:border-gray-700 dark:bg-gray-800/50 dark:text-gray-300 dark:focus:border-brand-500">
                        </div>

                        <button type="submit" class="inline-flex h-9 items-center justify-center rounded-lg bg-brand px-5 text-sm font-semibold text-white transition-colors hover:bg-brand/90 focus:outline-none">
                            Terapkan Filter
                        </button>

                        @if(request('start_date') || request('end_date'))
                        <a href="{{ url()->current() }}" class="text-sm font-semibold text-white transition-colors hover:text-gray-200 hover:underline">
                            Reset
                        </a>
                        @endif

                        {{-- Tombol Pemanggil Pop-up Laporan Website --}}
                        <button type="button" onclick="document.getElementById('modal-faktur-website').style.display='flex'" class="ml-auto inline-flex h-9 items-center justify-center gap-2 rounded-lg bg-brand px-5 text-sm font-semibold text-white transition-colors hover:bg-brand/90 focus:outline-none">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                            </svg>
                            Unduh Faktur
                        </button>
                    </form>
                </div>
                {{-- ====== Filter Section End ====== --}}

                <!-- ====== Table Start ====== -->
                <div class="overflow-hidden rounded-xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
                    <div class="max-w-full">
                        <table class="w-full table-auto text-left">
                            <thead>
                                <tr class="border-b border-gray-100 dark:border-gray-800 bg-gray-50 dark:bg-white/[0.02]">
                                    <th class="px-3 py-3">
                                        <p class="font-medium text-gray-500 text-xs sm:text-sm dark:text-gray-400">Kd. Pesanan</p>
                                    </th>
                                    <th class="px-3 py-3">
                                        <p class="font-medium text-gray-500 text-xs sm:text-sm dark:text-gray-400">Pihak Transaksi</p>
                                    </th>
                                    <th class="px-3 py-3">
                                        <p class="font-medium text-gray-500 text-xs sm:text-sm dark:text-gray-400">Nilai Pesanan</p>
                                    </th>
                                    <th class="px-3 py-3 bg-success-50/50 dark:bg-success-500/5">
                                        <p class="font-bold text-success-600 text-xs sm:text-sm dark:text-success-500">Pajak Platform</p>
                                    </th>
                                    <th class="px-3 py-3 text-center">
                                        <p class="font-medium text-gray-500 text-xs sm:text-sm dark:text-gray-400">Waktu Selesai</p>
                                    </th>
                                </tr>
                            </thead>

                            <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                                @forelse($pemesanans as $item)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50 transition-colors">

                                    {{-- Kode Pesanan --}}
                                    <td class="px-3 py-4">
                                        <p class="text-gray-500 text-xs sm:text-sm dark:text-gray-300 font-semibold uppercase whitespace-nowrap">
                                            {{ \Vinkla\Hashids\Facades\Hashids::encode($item->id_pemesanan) }}
                                        </p>
                                    </td>

                                    {{-- Pihak Transaksi (Digabung agar ringkas) --}}
                                    <td class="px-3 py-4">
                                        <div class="flex flex-col gap-1 text-xs sm:text-sm">
                                            <span class="text-gray-500 text-[11px] sm:text-xs dark:text-gray-400 leading-tight"><span class="text-gray-400">B:</span> {{ $item->pengguna->username ?? '-' }}</span>
                                            <span class="text-gray-500 text-[11px] sm:text-xs dark:text-gray-400 leading-tight"><span class="text-gray-400">S:</span> {{ $item->penawaran->pengguna->username ?? '-' }}</span>
                                        </div>
                                    </td>

                                    {{-- Nilai Transaksi Asli --}}
                                    <td class="px-3 py-4 whitespace-nowrap">
                                        <p class="text-gray-500 font-medium text-xs sm:text-sm dark:text-gray-400">
                                            IDR {{ number_format($item->pembayaran->harga ?? 0, 0, ',', '.') }}
                                        </p>
                                    </td>

                                    {{-- PENDAPATAN PLATFORM (Contoh: Potongan 5% / 0.05 dari total transaksi) --}}
                                    {{-- Sesuaikan perhitungan ini dengan kolom 'pajak' yang ada di database Anda --}}
                                    <td class="px-3 py-4 whitespace-nowrap bg-success-50/20 dark:bg-success-500/5">
                                        <p class="text-success-600 font-bold text-xs sm:text-sm dark:text-success-500">
                                            + IDR {{ number_format(($item->pembayaran->harga ?? 0) * 0.1, 0, ',', '.') }}
                                        </p>
                                    </td>

                                    {{-- Waktu --}}
                                    <td class="px-3 py-4 text-center">
                                        <p class="text-gray-500 text-[11px] sm:text-xs dark:text-gray-400 leading-tight">
                                            {{ $item->waktu_pemesanan ? \Carbon\Carbon::parse($item->waktu_pemesanan)->translatedFormat('d M Y, H:i') : '-' }}
                                        </p>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="px-5 py-8 text-center text-sm text-gray-500 dark:text-gray-400">
                                        Belum ada data pendapatan.
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                <!-- ====== Table End ====== -->

                {{-- Pagination Links --}}
                @if(isset($pemesanans) && $pemesanans->hasPages())
                <div class="mt-6 flex justify-center">
                    {{ $pemesanans->onEachSide(1)->links('vendor.pagination.custom') }}
                </div>
                @endif

            </div>
        </div>
    </div>
</div>
@push('modals')
<!-- MODAL PILIH PERIODE LAPORAN WEBSITE (FULL INLINE CSS) -->
<div id="modal-faktur-website" style="display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0; width: 100vw; height: 100vh; z-index: 9999; background-color: rgba(0, 0, 0, 0.6); backdrop-filter: blur(4px); align-items: center; justify-content: center; padding: 16px; box-sizing: border-box;">

    <!-- Kotak Modal -->
    <div style="width: 100%; max-width: 420px; background-color: var(--color-card, #1e293b); border: 1px solid var(--color-line, #334155); border-radius: 12px; padding: 24px; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25); position: relative;">

        <!-- Header Modal -->
        <div style="margin-bottom: 24px; display: flex; align-items: center; justify-content: space-between; gap: 8px;">
            <h3 style="font-size: 16px; font-weight: 600; line-height: 1.25; color: var(--color-light, #f8fafc); margin: 0;">Unduh Laporan Website Bulanan</h3>
            <button type="button" onclick="document.getElementById('modal-faktur-website').style.display='none'" style="color: var(--color-muted, #94a3b8); background: transparent; border: none; cursor: pointer; padding: 0; outline: none; flex-shrink: 0;">
                <svg xmlns="http://www.w3.org/2000/svg" style="height: 20px; width: 20px;" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                </svg>
            </button>
        </div>

        {{-- Arahkan ke Route Controller Laporan Website Anda --}}
        <form action="{{ route('admin.laporan') }}" method="GET">
            <!-- Input hidden ini wajib ada sebagai pengganti ?export=pdf di URL -->
            <input type="hidden" name="export" value="pdf">
            <div style="margin-bottom: 32px; position: relative;">
                <label style="display: block; margin-bottom: 8px; font-size: 13px; font-weight: 500; color: var(--color-light, #f8fafc);">Pilih satu bulan</label>

                @php
                $start = \Carbon\Carbon::create(2026, 10, 1);
                $now = \Carbon\Carbon::now()->startOfMonth();
                $defaultVal = $now->format('Y-m');
                $defaultLabel = $now->translatedFormat('F Y');
                @endphp

                <input type="hidden" name="periode" id="input_periode_web" value="{{ $defaultVal }}">

                <!-- Tombol Dropdown -->
                <button id="btn_toggle_web" type="button" onclick="toggleDropdownPeriodeWeb()" style="display: flex; width: 100%; align-items: center; justify-content: space-between; border-radius: 8px; padding: 12px 16px; font-size: 14px; color: var(--color-light, #f8fafc); background-color: var(--color-panel, #0f172a); border: 2px solid var(--color-brand, #3b82f6); cursor: pointer; outline: none;">
                    <span id="text_periode_web" style="opacity: 0.9;">{{ $defaultLabel }}</span>
                    <svg style="height: 16px; width: 16px; color: var(--color-muted, #94a3b8); pointer-events: none;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>

                <!-- Menu Dropdown -->
                <ul id="menu_periode_web" style="position: absolute; left: 0; top: 105%; z-index: 50; margin-top: 4px; display: none; width: 100%; max-height: 180px; overflow-y: auto; border-radius: 8px; box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1); background-color: var(--color-panel, #0f172a); border: 1px solid var(--color-line, #334155); padding: 0; list-style: none;">
                    @php
                    $loopDate = $now->copy();
                    $first = true;
                    @endphp

                    @while($loopDate->gte($start))
                    @php
                    $val = $loopDate->format('Y-m');
                    $lbl = $loopDate->translatedFormat('F Y');
                    @endphp
                    <li onclick="pilihPeriodeWeb('{{ $val }}', '{{ $lbl }}')" onmouseover="this.style.backgroundColor='var(--color-line, #334155)'" onmouseout="this.style.backgroundColor='transparent'" style="display: flex; cursor: pointer; align-items: center; justify-content: space-between; padding: 12px 16px; font-size: 14px; color: var(--color-light, #f8fafc); border-bottom: 1px solid var(--color-line, #334155); transition: background-color 0.2s;">
                        <span>{{ $lbl }}</span>
                        @if($first)
                        <!-- Centang Biru -->
                        <svg style="height: 16px; width: 16px; color: var(--color-brand, #3b82f6);" fill="none" stroke="currentColor" viewBox="0 0 24 24" id="check-icon-web-{{ $val }}">
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
                <button type="button" onclick="document.getElementById('modal-faktur-website').style.display='none'"
                    style="border-radius: 8px; padding: 10px 20px; font-size: 14px; font-weight: 600; color: var(--color-muted, #94a3b8); background-color: transparent; border: none; cursor: pointer; transition: opacity 0.2s;" onmouseover="this.style.opacity='0.8'" onmouseout="this.style.opacity='1'">
                    Batal
                </button>
                <button type="submit" onclick="document.getElementById('modal-faktur-website').style.display='none'"
                    style="border-radius: 8px; background-color: var(--color-brand, #3b82f6); padding: 10px 24px; font-size: 14px; font-weight: bold; color: #ffffff; border: none; cursor: pointer; box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05); transition: transform 0.2s;" onmouseover="this.style.transform='scale(1.05)'" onmouseout="this.style.transform='scale(1)'">
                    Unduh Laporan
                </button>
            </div>
        </form>
    </div>
</div>
<script>
    // JS Khusus untuk Dropdown Laporan Website (ID dan Fungsi Berbeda agar tidak bentrok)
    function toggleDropdownPeriodeWeb() {
        const menu = document.getElementById('menu_periode_web');
        if (menu.style.display === 'none' || menu.style.display === '') {
            menu.style.display = 'block';
        } else {
            menu.style.display = 'none';
        }
    }

    function pilihPeriodeWeb(val, label) {
        document.getElementById('input_periode_web').value = val;
        document.getElementById('text_periode_web').innerText = label;
        document.getElementById('menu_periode_web').style.display = 'none';
    }

    // Tutup dropdown jika klik di luar elemen khusus form web
    document.addEventListener('click', function(event) {
        const menu = document.getElementById('menu_periode_web');
        const button = document.getElementById('btn_toggle_web');

        if (menu && button && menu.style.display === 'block') {
            if (!button.contains(event.target) && !menu.contains(event.target)) {
                menu.style.display = 'none';
            }
        }
    });
</script>
@endpush
@endsection
