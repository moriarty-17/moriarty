@extends('template.backend')
@section('content')
<div class="p-4 mx-auto max-w-(--breakpoint-2xl) md:p-6">
    <div class="space-y-5 sm:space-y-6">
        <div class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
            <div class="px-5 py-4 sm:px-6 sm:py-5">
                <h3 class="text-base font-medium text-gray-800 dark:text-white/90">
                    Daftar Pemesanan (Transaksi)
                </h3>
            </div>
            
            <div class="p-4 border-t border-gray-100 dark:border-gray-800 sm:p-6">
                <!-- ====== Table Start ====== -->
                <div class="overflow-hidden rounded-xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
                    
                    {{-- Dihapus overflow-x-auto agar tidak scroll kecuali di HP --}}
                    <div class="max-w-full">
                        
                        {{-- Dihapus min-w-max agar tabel mau menyesuaikan lebar frame --}}
                        <table class="w-full table-auto text-left">
                            
                            <!-- table header start -->
                            <thead>
                                <tr class="border-b border-gray-100 dark:border-gray-800 bg-gray-50 dark:bg-white/[0.02]">
                                    {{-- Padding diperkecil (px-2/px-3) agar 8 kolom muat --}}
                                    <th class="px-2 py-3 sm:px-3">
                                        <p class="font-medium text-gray-500 text-xs sm:text-sm dark:text-gray-400">Kode Pesanan</p>
                                    </th>
                                    <th class="px-2 py-3 sm:px-3">
                                        <p class="font-medium text-gray-500 text-xs sm:text-sm dark:text-gray-400">Pembeli</p>
                                    </th>
                                    <th class="px-2 py-3 sm:px-3">
                                        <p class="font-medium text-gray-500 text-xs sm:text-sm dark:text-gray-400">Penjual</p>
                                    </th>
                                    <th class="px-2 py-3 sm:px-3">
                                        <p class="font-medium text-gray-500 text-xs sm:text-sm dark:text-gray-400">Kd. Penawaran</p>
                                    </th>
                                    <th class="px-2 py-3 sm:px-3">
                                        <p class="font-medium text-gray-500 text-xs sm:text-sm dark:text-gray-400">Kd. Pembayaran</p>
                                    </th>
                                    <th class="px-2 py-3 sm:px-3">
                                        <p class="font-medium text-gray-500 text-xs sm:text-sm dark:text-gray-400">Harga</p>
                                    </th>
                                    <th class="px-2 py-3 sm:px-3 text-center">
                                        <p class="font-medium text-gray-500 text-xs sm:text-sm dark:text-gray-400">Status</p>
                                    </th>
                                    <th class="px-2 py-3 sm:px-3 text-center">
                                        <p class="font-medium text-gray-500 text-xs sm:text-sm dark:text-gray-400">Waktu</p>
                                    </th>
                                </tr>
                            </thead>
                            <!-- table header end -->

                            <!-- table body start -->
                            <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                                @forelse($pemesanans as $item)
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50 transition-colors">
                                        
                                        {{-- Kode Pemesanan --}}
                                        <td class="px-2 py-4 sm:px-3">
                                            <p class="text-gray-500 text-xs sm:text-sm dark:text-gray-300 font-semibold uppercase whitespace-nowrap">
                                                {{ \Vinkla\Hashids\Facades\Hashids::encode($item->id_pemesanan) }}
                                            </p>
                                        </td>
                                        
                                        {{-- Pembeli --}}
                                        <td class="px-2 py-4 sm:px-3">
                                            <p class="text-gray-500 text-xs sm:text-sm dark:text-gray-400 break-words">
                                                {{ $item->pengguna->username ?? 'Tidak Diketahui' }}
                                            </p>
                                        </td>

                                        {{-- Penjual --}}
                                        <td class="px-2 py-4 sm:px-3">
                                            <p class="text-gray-500 text-xs sm:text-sm dark:text-gray-400 break-words">
                                                {{ $item->penawaran->pengguna->username ?? 'Tidak Diketahui' }}
                                            </p>
                                        </td>
                                        
                                        {{-- Kode Penawaran (Bisa turun baris jika sangat panjang) --}}
                                        <td class="px-2 py-4 sm:px-3">
                                            <p class="text-gray-500 text-xs sm:text-sm dark:text-gray-400 break-all lg:break-words">
                                                {{ $item->kode_penawaran }}
                                            </p>
                                        </td>
                                        
                                        {{-- Kode Pembayaran (Bisa turun baris) --}}
                                        <td class="px-2 py-4 sm:px-3">
                                            <p class="text-gray-500 text-xs sm:text-sm dark:text-gray-400 break-all lg:break-words">
                                                {{ $item->pembayaran->kode_pembayaran ?? '-' }}
                                            </p>
                                        </td>
                                        
                                        {{-- Harga --}}
                                        <td class="px-2 py-4 sm:px-3 whitespace-nowrap">
                                            <p class="text-gray-500 font-medium text-xs sm:text-sm dark:text-gray-400">
                                                IDR {{ number_format($item->penawaran->harga ?? 0, 0, ',', '.') }}
                                            </p>
                                        </td>
                                        
                                        {{-- Status --}}
                                        <td class="px-2 py-4 sm:px-3 text-center">
                                            @if(strtolower($item->status) === 'selesai' || strtolower($item->status) === 'completed')
                                                <span class="inline-flex rounded-full px-2 py-1 text-[10px] sm:text-xs font-medium bg-success-50 text-success-600 dark:bg-success-500/15 dark:text-success-500">
                                                    Selesai
                                                </span>
                                            @elseif(strtolower($item->status) === 'diproses' || strtolower($item->status) === 'pending')
                                                <span class="inline-flex rounded-full px-2 py-1 text-[10px] sm:text-xs font-medium bg-warning-50 text-warning-600 dark:bg-warning-500/15 dark:text-orange-400">
                                                    Diproses
                                                </span>
                                            @else
                                                <span class="inline-flex rounded-full px-2 py-1 text-[10px] sm:text-xs font-medium bg-error-50 text-error-600 dark:bg-error-500/15 dark:text-error-500">
                                                    {{ ucfirst($item->status) }}
                                                </span>
                                            @endif
                                        </td>
                                        
                                        {{-- Waktu Pembayaran --}}
                                        <td class="px-2 py-4 sm:px-3 text-center">
                                            <p class="text-gray-500 text-[11px] sm:text-xs dark:text-gray-400 leading-tight">
                                                {{ $item->waktu_pemesanan ? \Carbon\Carbon::parse($item->waktu_pemesanan)->translatedFormat('d M Y, H:i') : '-' }}
                                            </p>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="px-5 py-8 text-center text-sm text-gray-500 dark:text-gray-400">
                                            Belum ada data pemesanan.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                            <!-- table body end -->
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
@endsection