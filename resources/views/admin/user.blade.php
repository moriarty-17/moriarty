@extends('template.backend')
@section('content')
<div class="p-4 mx-auto max-w-(--breakpoint-2xl) md:p-6">
    <div class="space-y-5 sm:space-y-6">
        <div class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
            <div class="px-5 py-4 sm:px-6 sm:py-5 border-b border-gray-100 dark:border-gray-800">
                <h3 class="text-base font-medium text-gray-800 dark:text-white/90">
                    Daftar Pengguna
                </h3>
            </div>

            <div class="p-4 sm:p-6">

                {{-- Notifikasi Sukses --}}
                @if(session('success'))
                <div class="mb-4 p-3 rounded-lg bg-success-50 text-success-600 text-sm font-medium border border-success-200">
                    {{ session('success') }}
                </div>
                @endif

                <!-- ====== Table Start ====== -->
                <div class="overflow-hidden rounded-xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
                    <div class="max-w-full">
                        <table class="w-full table-auto text-left">
                            <!-- table header start -->
                            <thead>
                                <tr class="border-b border-gray-100 dark:border-gray-800 bg-gray-50 dark:bg-white/[0.02]">
                                    <th class="px-3 py-3 sm:px-4 w-12">
                                        <p class="font-medium text-gray-500 text-xs sm:text-sm dark:text-gray-400 text-center">ID</p>
                                    </th>
                                    <th class="px-3 py-3 sm:px-4">
                                        <p class="font-medium text-gray-500 text-xs sm:text-sm dark:text-gray-400">Profil</p>
                                    </th>
                                    <th class="px-3 py-3 sm:px-4">
                                        <p class="font-medium text-gray-500 text-xs sm:text-sm dark:text-gray-400">Pengguna</p>
                                    </th>
                                    <th class="px-3 py-3 sm:px-4 text-center">
                                        <p class="font-medium text-gray-500 text-xs sm:text-sm dark:text-gray-400">Role</p>
                                    </th>
                                    <th class="px-3 py-3 sm:px-4">
                                        <p class="font-medium text-gray-500 text-xs sm:text-sm dark:text-gray-400">Saldo</p>
                                    </th>
                                    <th class="px-3 py-3 sm:px-4 text-center">
                                        <p class="font-medium text-gray-500 text-xs sm:text-sm dark:text-gray-400">Status</p>
                                    </th>
                                    <th class="px-3 py-3 sm:px-4 text-center">
                                        <p class="font-medium text-gray-500 text-xs sm:text-sm dark:text-gray-400">Aksi</p>
                                    </th>
                                </tr>
                            </thead>

                            <!-- table body start -->
                            <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                                @forelse ($pengguna as $view)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50 transition-colors">
                                    <td class="px-3 py-4 sm:px-4 text-center">
                                        <p class="text-gray-500 text-xs sm:text-sm font-semibold dark:text-gray-400">
                                            {{ $view->id_pengguna ?? $view->id }}
                                        </p>
                                    </td>

                                    <td class="px-3 py-4 sm:px-4">
                                        <div class="h-10 w-10 overflow-hidden rounded-full border border-gray-200 bg-gray-100">
                                           <img src="{{ $view->gambar ? asset('assets/resource/profil/' . $view->gambar) : 'https://ui-avatars.com/api/?name='.urlencode($view->username).'&background=random' }}" alt="avatar" class="h-full w-full object-cover" />
                                        </div>
                                    </td>

                                    <td class="px-3 py-4 sm:px-4">
                                        <p class="text-gray-800 text-xs sm:text-sm font-bold dark:text-white/90">
                                            {{ $view->username }}
                                        </p>
                                        <p class="text-gray-500 text-[11px] sm:text-xs dark:text-gray-400">
                                            {{ $view->email }}
                                        </p>
                                    </td>

                                    <td class="px-3 py-4 sm:px-4 text-center">
                                        <span class="rounded-full bg-blue-50 text-gray-500 px-2.5 py-1 text-[10px] sm:text-xs font-semibold uppercase tracking-wider">
                                            {{ $view->role }}
                                        </span>
                                    </td>

                                    <td class="px-3 py-4 sm:px-4 whitespace-nowrap">
                                        <p class="text-gray-500 text-xs sm:text-sm font-medium dark:text-gray-300">
                                            IDR {{ number_format($view->saldo ?? 0, 0, ',', '.') }}
                                        </p>
                                    </td>

                                    <td class="px-3 py-4 sm:px-4 text-center">
                                        @if(strtolower($view->status) === 'banned')
                                        <span class="rounded-full bg-error-50 text-error-600 px-2.5 py-1 text-[10px] sm:text-xs font-semibold">
                                            Banned
                                        </span>
                                        @else
                                        <span class="rounded-full bg-success-50 text-success-600 px-2.5 py-1 text-[10px] sm:text-xs font-semibold">
                                            Aktif
                                        </span>
                                        @endif
                                    </td>

                                    <td class="px-3 py-4 sm:px-4 text-center">
                                        {{-- Jangan izinkan admin nge-ban dirinya sendiri --}}
                                        @if($view->id_pengguna !== auth()->id())
                                        <form action="{{ url('admin/pengguna/toggle-ban', $view->id_pengguna ?? $view->id) }}" method="POST" onsubmit="return confirm('Yakin ingin mengubah status pengguna ini?');">
                                            @csrf
                                            @if(strtolower($view->status) === 'banned')
                                            <button type="submit" class="inline-flex items-center justify-center rounded-lg bg-success-500 px-3 py-1.5 text-xs font-semibold text-white transition hover:bg-success-600">
                                                Unban
                                            </button>
                                            @else
                                            <button type="submit" class="inline-flex items-center justify-center rounded-lg bg-error-500 px-3 py-1.5 text-xs font-semibold text-white transition hover:bg-error-600">
                                                Ban User
                                            </button>
                                            @endif
                                        </form>
                                        @else
                                        <span class="text-xs text-gray-400 italic">Anda</span>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="px-3 py-8 text-center text-sm font-semibold text-gray-500">Belum ada data pengguna.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                <!-- ====== Table End ====== -->

                {{-- Pagination Links --}}
                @if(isset($pengguna) && $pengguna->hasPages())
                <div class="mt-6 flex justify-center">
                    {{ $pengguna->onEachSide(1)->links('vendor.pagination.custom') }}
                </div>
                @endif

            </div>
        </div>
    </div>
</div>
@endsection