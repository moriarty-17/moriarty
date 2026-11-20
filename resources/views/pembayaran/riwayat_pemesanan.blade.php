@extends('template.frontend')

@section('content')
<div class="mx-auto w-full max-w-[1400px] px-4 py-8 sm:px-8 sm:py-10 lg:py-12">
    <div class="rounded-[10px] border-4 border-line p-4 sm:p-6 lg:p-10">
        <h1 class="text-2xl font-semibold text-light lg:text-[28px]">
            Riwayat Pesanan
        </h1>

        <!-- TOMBOL FILTER STATUS & UNDUH FAKTUR -->
        <div class="mt-6 flex flex-wrap items-center gap-[5px] sm:mt-8">
            <a href="{{ route('pemesanan.index') }}"
                class="flex h-11 min-w-[100px] items-center justify-center rounded-sm {{ request('status') == null ? 'border-[3px] border-brand text-brand' : 'text-muted hover:text-light' }} px-4 text-sm font-semibold sm:h-12 sm:text-[15px] transition-colors">
                Semua
            </a>

            <a href="{{ route('pemesanan.index', ['status' => 'belum_bayar']) }}"
                class="flex h-11 min-w-[100px] items-center justify-center rounded-sm {{ request('status') == 'belum_bayar' ? 'border-[3px] border-brand text-brand' : 'text-muted hover:text-light' }} px-4 text-sm font-semibold sm:h-12 sm:text-[15px] transition-colors">
                Belum Bayar
            </a>

            <a href="{{ route('pemesanan.index', ['status' => 'dikirim']) }}"
                class="flex h-11 min-w-[100px] items-center justify-center rounded-sm {{ request('status') == 'dikirim' ? 'border-[3px] border-brand text-brand' : 'text-muted hover:text-light' }} px-4 text-sm font-semibold sm:h-12 sm:text-[15px] transition-colors">
                Dikirim
            </a>

            <a href="{{ route('pemesanan.index', ['status' => 'selesai']) }}"
                class="flex h-11 min-w-[100px] items-center justify-center rounded-sm {{ request('status') == 'selesai' ? 'border-[3px] border-brand text-brand' : 'text-muted hover:text-light' }} px-4 text-sm font-semibold sm:h-12 sm:text-[15px] transition-colors">
                Selesai
            </a>
        </div>

        <div class="mt-6 overflow-hidden rounded-lg border border-line bg-card shadow-sm">
            <div class="overflow-x-auto">
                <!-- Menggunakan table-fixed agar lebar kolom konsisten -->
                <table class="w-full text-left border-collapse table-fixed min-w-[920px]">

                    <!-- DAFTAR ITEM (TBODY) -->
                    <tbody class="dark:divide-gray-800/60 text-xs sm:text-sm">
                        @forelse($pemesanans as $item)
                        @php
                        // =========================================================================
                        // PENANGANAN GAMBAR DARI TABEL gambar_penawaran (PATH: assets/resource/penawaran/)
                        // =========================================================================
                        $gambarUtama = asset('assets/img/listing-wide.jpg'); // Fallback default

                        if ($item->penawaran && isset($item->penawaran->gambarPenawaran)) {
                        // Mengambil data gambar pertama dari relasi gambarPenawaran
                        $gambarRecord = $item->penawaran->gambarPenawaran->first();

                        if ($gambarRecord) {
                        // Sesuaikan 'path_gambar' dengan nama kolom di tabel gambar_penawaran Anda
                        $namaFileGambar = $gambarRecord->path_gambar ?? $gambarRecord->gambar ?? null;

                        if ($namaFileGambar) {
                        if (Str::startsWith($namaFileGambar, 'http')) {
                        $gambarUtama = $namaFileGambar;
                        } else {
                        // Mengarah langsung ke folder public/assets/resource/penawaran/
                        $gambarUtama = asset('assets/resource/penawaran/' . $namaFileGambar);
                        }
                        }
                        }
                        }

                        // LOGIKA STATUS: Penentu Aksi
                        $statusPembayaran = $item->pembayaran ? strtolower($item->pembayaran->status) : 'pending';
                        $statusPesanan = strtolower($item->status);
                        $isLunas = in_array($statusPembayaran, ['lunas', 'completed']);
                        @endphp

                        <!-- BARIS ITEM (TR) -->
                        <tr @if($isLunas || $statusPesanan=='selesai' || $statusPesanan=='batal' )
                            onclick="window.location.href='{{ route('pembayaran.invoice', \Vinkla\Hashids\Facades\Hashids::encode($item->id_pemesanan)) }}'"
                            class="cursor-pointer hover:bg-panel/40 transition-colors border-b border-line last:border-0"
                            @else
                            class="hover:bg-panel/40 transition-colors border-b border-line last:border-0"
                            @endif>

                            <!-- Kolom 1: ID & Gambar (Lebar Tetap: 180px) -->
                            <td class="py-4 px-4 align-middle whitespace-nowrap" style="width: 180px !important;">
                                <div class="flex flex-col gap-2">
                                    <span class="text-xs font-bold text-light tracking-wide">
                                        PSN-{{ $item->id_pemesanan }}
                                    </span>
                                    {{-- Pembungkus gambar dipaksa menggunakan inline style agar kebal CSS global --}}
                                    <div style="width: 160px !important; height: 95px !important; min-width: 160px !important; max-width: 160px !important; overflow: hidden; border-radius: 0.375rem; border: 1px solid rgba(255, 255, 255, 0.1);">
                                        <img src="{{ $gambarUtama }}" alt="Gambar Produk"
                                            style="width: 100% !important; height: 100% !important; object-fit: cover !important; display: block !important;">
                                    </div>
                                </div>
                            </td>

                            <!-- Kolom 2: Judul Penawaran (DIPERPENDEK - Lebar Maksimal Dibatasi 260px) -->
                            <td class="py-4 px-4 align-middle w-[260px] max-w-[260px]">
                                <div class="w-[260px] max-w-[260px]">
                                    <p class="text-xs sm:text-sm font-medium leading-snug text-light line-clamp-2 break-words overflow-hidden"
                                        title="{{ $item->penawaran->judul_penawaran ?? $item->penawaran->judul ?? 'Penawaran Game' }}">
                                        {{ $item->penawaran->judul_penawaran ?? $item->penawaran->judul ?? 'Penawaran Game' }}
                                    </p>
                                </div>
                            </td>

                            <!-- Kolom 3: Waktu Pemesanan (Lebar Tetap: 140px) -->
                            <td class="py-4 px-4 align-middle text-center whitespace-nowrap w-[160px]">
                                <span class="text-xs sm:text-sm font-semibold text-light">
                                    {{ \Carbon\Carbon::parse($item->waktu_pemesanan)->format('d M Y, H:i') }}
                                </span>
                            </td>

                            <!-- Kolom 4: Harga (Lebar Tetap: 160px) -->
                            <td class="py-4 px-4 align-middle text-center whitespace-nowrap w-[160px]">
                                <span class="text-xs sm:text-sm font-semibold text-light">
                                    IDR {{ number_format($item->pembayaran->harga ?? 0, 0, ',', '.') }}
                                </span>
                            </td>

                            <!-- Kolom 5: AKSI (Sisa ruang dialokasikan kemari) -->
                            <td class="py-4 px-6 align-middle text-center whitespace-nowrap w-[180px]" onclick="event.stopPropagation()">

                                {{-- KONDISI 1: Belum Bayar --}}
                                @if(!$isLunas)
                                @if($item->pembayaran)
                                <a href="{{ route('pembayaran.bayar', $item->pembayaran->kode_pembayaran) }}"
                                    class="inline-flex min-w-[100px] items-center justify-center rounded-sm border border-[2px] text-[12px] border-sky px-4 py-2 font-semibold text-sky hover:bg-sky hover:text-white transition-colors shadow-sm">
                                    Lanjut Bayar
                                </a>
                                @else
                                <span class="inline-flex min-w-[100px] items-center justify-center rounded-sm border border-[2px] text-[12px] px-4 py-2 font-semibold text-sky">
                                    Menunggu
                                </span>
                                @endif

                                {{-- KONDISI 2: Dikirim / Konfirmasi --}}
                                @elseif($isLunas && $statusPesanan != 'selesai')
                                <form action="{{ route('pemesanan.konfirmasi', \Vinkla\Hashids\Facades\Hashids::encode($item->id_pemesanan)) }}" method="POST" class="inline-block">
                                    @csrf
                                    <button type="submit"
                                        class="inline-flex min-w-[100px] cursor-pointer items-center justify-center rounded-sm border border-[2px] border-warn/80 px-4 py-2 text-xs font-semibold text-warn shadow-sm hover:bg-warn/10 transition-all">
                                        Konfirmasi
                                    </button>
                                </form>

                                {{-- KONDISI 3: Selesai / Beri Ulasan --}}
                                @elseif($isLunas && $statusPesanan == 'selesai')
                                <div class="inline-flex items-center justify-center gap-1.5">
                                    <a href="{{ route('general.review', \Vinkla\Hashids\Facades\Hashids::encode($item->id_pemesanan)) }}"
                                        class="inline-flex min-w-[100px] items-center justify-center rounded-sm border border-[2px] border-sky px-4 py-2 text-[12px] font-semibold text-sky hover:text-white hover:bg-sky transition-colors shadow-sm">
                                        Beri Ulasan
                                    </a>
                                </div>
                                @endif

                            </td>

                        </tr>
                        @empty
                        <!-- Tampilan Data Kosong -->
                        <tr onclick="event.stopPropagation()">
                            <!-- Colspan diubah menjadi 5 karena ada tambahan 1 kolom baru -->
                            <td colspan="5" class="py-16 text-center text-muted text-sm cursor-default">
                                Belum ada riwayat pesanan.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- PAGINATION CUSTOM --}}
        @if(isset($pemesanans) && $pemesanans->hasPages())
        <div class="mt-6 flex justify-center">
            {{ $pemesanans->onEachSide(1)->links('vendor.pagination.custom') }}
        </div>
        @endif

    </div>
</div>
@endsection
<!-- Script JavaScript untuk mengontrol Custom Dropdown -->
<script>
    function toggleDropdownPeriode() {
        const menu = document.getElementById('menu_periode');
        menu.classList.toggle('hidden');
    }

    function pilihPeriode(value, label) {
        // Ubah value pada form input hidden dan ubah teks pada tombol
        document.getElementById('input_periode').value = value;
        document.getElementById('text_periode').innerText = label;
        // Sembunyikan menu
        document.getElementById('menu_periode').classList.add('hidden');
    }

    // Otomatis menutup dropdown jika user mengklik area lain di luar kotak dropdown
    document.addEventListener('click', function(event) {
        const menu = document.getElementById('menu_periode');
        const button = document.getElementById('text_periode').parentElement;

        // Cek jika klik terjadi di luar tombol dan di luar menu
        if (!button.contains(event.target) && !menu.contains(event.target)) {
            menu.classList.add('hidden');
        }
    });
</script>