@extends('template.backend')
@section('content')
<div class="p-4 mx-auto max-w-(--breakpoint-2xl) md:p-6">
    <div class="space-y-5 sm:space-y-6">
        <div class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
            <div class="px-5 py-4 sm:px-6 sm:py-5 flex justify-between items-center">
                <h3 class="text-base font-medium text-gray-800 dark:text-white/90">
                    Daftar Penawaran
                </h3>
            </div>

            <div class="p-5 border-t border-gray-100 dark:border-gray-800 sm:p-6">
                <!-- ====== Table Start ====== -->
                <div class="overflow-hidden rounded-xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
                    <div class="max-w-full overflow-x-auto">
                        <table class="w-full min-w-max table-auto text-left">

                            <!-- table header start -->
                            <thead>
                                <tr class="border-b border-gray-100 dark:border-gray-800 bg-gray-50 dark:bg-white/[0.02]">
                                    <th class="px-5 py-3 sm:px-6">
                                        <p class="font-medium text-gray-500 text-sm dark:text-gray-400">Kode Penawaran</p>
                                    </th>
                                    <th class="px-5 py-3 sm:px-6">
                                        <p class="font-medium text-gray-500 text-sm dark:text-gray-400">Pengguna</p>
                                    </th>
                                    <th class="px-5 py-3 sm:px-6">
                                        <p class="font-medium text-gray-500 text-sm dark:text-gray-400">Judul</p>
                                    </th>
                                    <th class="px-5 py-3 sm:px-6">
                                        <p class="font-medium text-gray-500 text-sm dark:text-gray-400">Nama Game</p>
                                    </th>
                                    <th class="px-5 py-3 sm:px-6">
                                        <p class="font-medium text-gray-500 text-sm dark:text-gray-400">Harga</p>
                                    </th>
                                    <th class="px-5 py-3 sm:px-6 text-center">
                                        <p class="font-medium text-gray-500 text-sm dark:text-gray-400">Status</p>
                                    </th>
                                </tr>
                            </thead>
                            <!-- table header end -->

                            <!-- table body start -->
                            <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                                @forelse($penawarans as $item)
                                
                                {{-- PERUBAHAN ADA DI BARIS TR INI --}}
                                <tr onclick="window.location.href='{{ route('admin.penawaran.detail', \Vinkla\Hashids\Facades\Hashids::encode($item->id_penawaran)) }}'" 
                                    class="hover:bg-gray-50 dark:hover:bg-gray-800/50 transition-colors cursor-pointer group">

                                    {{-- Kode Penawaran --}}
                                    <td class="px-5 py-4 sm:px-6">
                                        <p class="text-gray-500 text-sm dark:text-gray-400 font-semibold group-hover:text-brand transition-colors">
                                            {{ $item->kode_penawaran }}
                                        </p>
                                    </td>

                                    {{-- Pengguna / Seller --}}
                                    <td class="px-5 py-4 sm:px-6">
                                        <p class="text-gray-500 text-sm dark:text-gray-400">
                                            {{ $item->Pengguna->username ?? 'Tidak Diketahui' }}
                                        </p>
                                    </td>

                                    {{-- Judul (Dibatasi 2 baris agar tabel tidak terlalu tinggi) --}}
                                    <td class="px-5 py-4 sm:px-6">
                                        <div class="w-64 sm:w-72">
                                            <p class="text-gray-500 text-sm dark:text-gray-400 line-clamp-2" title="{{ $item->judul }}">
                                                {{ $item->judul }}
                                            </p>
                                        </div>
                                    </td>

                                    {{-- Jenis / Nama Game --}}
                                    <td class="px-5 py-4 sm:px-6">
                                        <p class="text-gray-500 text-sm dark:text-gray-400">
                                            {{ $item->jenis ?? '-' }}
                                        </p>
                                    </td>

                                    {{-- Harga --}}
                                    <td class="px-5 py-4 sm:px-6 whitespace-nowrap">
                                        <p class="text-gray-500 font-medium text-sm dark:text-gray-400">
                                            IDR {{ number_format($item->harga, 0, ',', '.') }}
                                        </p>
                                    </td>

                                    {{-- Status Dinamis --}}
                                    <td class="px-5 py-4 sm:px-6 text-center">
                                        @if(strtolower($item->status) === 'active' || strtolower($item->status) === 'aktif')
                                        <span class="inline-flex rounded-full px-2.5 py-1 text-xs font-medium bg-success-50 text-success-600 dark:bg-success-500/15 dark:text-success-500">
                                            Active
                                        </span>
                                        @elseif(strtolower($item->status) === 'sold' || strtolower($item->status) === 'terjual')
                                        <span class="inline-flex rounded-full px-2.5 py-1 text-xs font-medium bg-error-50 text-error-600 dark:bg-error-500/15 dark:text-error-500">
                                            Sold
                                        </span>
                                        @else
                                        <span class="inline-flex rounded-full px-2.5 py-1 text-xs font-medium bg-warning-50 text-warning-600 dark:bg-warning-500/15 dark:text-orange-400">
                                            {{ ucfirst($item->status) }}
                                        </span>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="px-5 py-8 text-center text-sm text-gray-500 dark:text-gray-400">
                                        Belum ada data penawaran.
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
                @if(isset($penawarans) && $penawarans->hasPages())
                <div class="mt-6 flex justify-center">
                    {{ $penawarans->onEachSide(1)->links('vendor.pagination.custom') }}
                </div>
                @endif

            </div>
        </div>
    </div>
</div>
@endsection