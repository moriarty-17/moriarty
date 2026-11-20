@extends('template.backend')
@section('content')
<div class="p-4 mx-auto max-w-(--breakpoint-2xl) md:p-6">
    <div class="space-y-5 sm:space-y-6">
        <div class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
            <div class="px-5 py-4 sm:px-6 sm:py-5">
                <h3 class="text-base font-medium text-gray-800 dark:text-white/90">
                    Daftar Pembayaran
                </h3>
            </div>
            <div class="p-4 border-t border-gray-100 dark:border-gray-800 sm:p-6">
                <!-- ====== Table Start ====== -->
                <div class="overflow-hidden rounded-xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
                    
                    {{-- Dihapus overflow-x-auto agar tidak scroll ke samping --}}
                    <div class="max-w-full">
                        
                        {{-- Dihapus min-w-max agar tabel menyesuaikan lebar frame --}}
                        <table class="w-full table-auto text-left">
                            
                            <!-- table header start -->
                            <thead>
                                <tr class="border-b border-gray-100 dark:border-gray-800 bg-gray-50 dark:bg-white/[0.02]">
                                    <th class="px-2 py-3 sm:px-4">
                                        <p class="font-medium text-gray-500 text-xs sm:text-sm dark:text-gray-400">Kode Pembayaran</p>
                                    </th>
                                    <th class="px-2 py-3 sm:px-4">
                                        <p class="font-medium text-gray-500 text-xs sm:text-sm dark:text-gray-400">Pembeli</p>
                                    </th>
                                    <th class="px-2 py-3 sm:px-4">
                                        <p class="font-medium text-gray-500 text-xs sm:text-sm dark:text-gray-400">Kode Penawaran</p>
                                    </th>
                                    <th class="px-2 py-3 sm:px-4">
                                        <p class="font-medium text-gray-500 text-xs sm:text-sm dark:text-gray-400">Harga</p>
                                    </th>
                                    <th class="px-2 py-3 sm:px-4">
                                        <p class="font-medium text-gray-500 text-xs sm:text-sm dark:text-gray-400">Metode</p>
                                    </th>
                                    <th class="px-2 py-3 sm:px-4 text-center">
                                        <p class="font-medium text-gray-500 text-xs sm:text-sm dark:text-gray-400">Status</p>
                                    </th>
                                    <th class="px-2 py-3 sm:px-4 text-center">
                                        <p class="font-medium text-gray-500 text-xs sm:text-sm dark:text-gray-400">Waktu</p>
                                    </th>
                                </tr>
                            </thead>
                            <!-- table header end -->

                            <!-- table body start -->
                            <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                                @forelse($pembayarans as $item)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50 transition-colors">
                                    
                                    {{-- Kode Pembayaran --}}
                                    <td class="px-2 py-4 sm:px-4">
                                        <p class="text-gray-500 text-xs sm:text-sm dark:text-gray-400 font-semibold whitespace-nowrap">
                                            {{ $item->kode_pembayaran }}
                                        </p>
                                    </td>

                                    {{-- Pembeli (Relasi dari Pemesanan) --}}
                                    <td class="px-2 py-4 sm:px-4">
                                        <p class="text-gray-500 text-xs sm:text-sm dark:text-gray-400 font-medium break-words">
                                            {{ $item->pemesanan->pengguna->username ?? 'Tidak Diketahui' }}
                                        </p>
                                    </td>
                                    
                                    {{-- Kode Penawaran --}}
                                    <td class="px-2 py-4 sm:px-4">
                                        <p class="text-gray-500 text-xs sm:text-sm dark:text-gray-400 break-all lg:break-words">
                                            {{ $item->kode_penawaran ?? '-' }}
                                        </p>
                                    </td>
                                    
                                    {{-- Harga --}}
                                    <td class="px-2 py-4 sm:px-4 whitespace-nowrap">
                                        <p class="text-gray-500 font-medium text-xs sm:text-sm dark:text-gray-400">
                                            IDR {{ number_format($item->harga ?? 0, 0, ',', '.') }}
                                        </p>
                                    </td>
                                    
                                    {{-- Metode Pembayaran --}}
                                    <td class="px-2 py-4 sm:px-4">
                                        <p class="text-gray-500 text-xs sm:text-sm dark:text-gray-400 uppercase">
                                            {{ $item->metode_pembayaran ?? '-' }}
                                        </p>
                                    </td>
                                    
                                    {{-- Status Pembayaran Dinamis --}}
                                    <td class="px-2 py-4 sm:px-4 text-center">
                                        @php
                                            $status = strtolower($item->status);
                                        @endphp
                                        
                                        @if(in_array($status, ['success', 'settlement', 'berhasil']))
                                            <span class="inline-flex rounded-full px-2.5 py-1 text-[10px] sm:text-xs font-medium bg-success-50 text-success-600 dark:bg-success-500/15 dark:text-success-500">
                                                Berhasil
                                            </span>
                                        @elseif(in_array($status, ['pending', 'diproses']))
                                            <span class="inline-flex rounded-full px-2.5 py-1 text-[10px] sm:text-xs font-medium bg-warning-50 text-warning-600 dark:bg-warning-500/15 dark:text-orange-400">
                                                Pending
                                            </span>
                                        @elseif(in_array($status, ['expire', 'cancel', 'deny', 'failed', 'gagal']))
                                            <span class="inline-flex rounded-full px-2.5 py-1 text-[10px] sm:text-xs font-medium bg-error-50 text-error-600 dark:bg-error-500/15 dark:text-error-500">
                                                Gagal
                                            </span>
                                        @else
                                            <span class="inline-flex rounded-full px-2.5 py-1 text-[10px] sm:text-xs font-medium bg-gray-100 text-gray-600 dark:bg-gray-800 dark:text-gray-400">
                                                {{ ucfirst($status) }}
                                            </span>
                                        @endif
                                    </td>
                                    
                                    {{-- Waktu Pembayaran --}}
                                    <td class="px-2 py-4 sm:px-4 text-center">
                                        <p class="text-gray-500 text-[11px] sm:text-xs dark:text-gray-400 leading-tight">
                                            {{ $item->waktu_pembayaran ? \Carbon\Carbon::parse($item->waktu_pembayaran)->translatedFormat('d M Y, H:i') : '-' }}
                                        </p>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="px-5 py-8 text-center text-sm text-gray-500 dark:text-gray-400">
                                        Belum ada data pembayaran.
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
                @if(isset($pembayarans) && $pembayarans->hasPages())
                <div class="mt-6 flex justify-center">
                    {{ $pembayarans->onEachSide(1)->links('vendor.pagination.custom') }}
                </div>
                @endif
                
            </div>
        </div>
    </div>
</div>
@endsection