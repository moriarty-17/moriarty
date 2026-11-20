@extends('template.backend')
@section('content')
<div class="p-4 mx-auto max-w-(--breakpoint-2xl) md:p-6">
    <div class="space-y-5 sm:space-y-6">
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-3">
            
    {{-- Card 1: Total Pengguna --}}
    <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03] md:p-6">
        <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-gray-100 dark:bg-gray-800">
            {{-- Icon: Users/Group --}}
            <svg class="fill-gray-800 dark:fill-white/90" width="28" height="28" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path d="M16 11c1.66 0 2.99-1.34 2.99-3S17.66 5 16 5c-1.66 0-3 1.34-3 3s1.34 3 3 3zm-8 0c1.66 0 2.99-1.34 2.99-3S9.66 5 8 5C6.34 5 5 6.34 5 8s1.34 3 3 3zm0 2c-2.33 0-7 1.17-7 3.5V19h14v-2.5c0-2.33-4.67-3.5-7-3.5zm8 0c-.29 0-.62.02-.97.05 1.16.84 1.97 1.97 1.97 3.45V19h6v-2.5c0-2.33-4.67-3.5-7-3.5z"/>
            </svg>
        </div>
        <div class="mt-5 flex items-end justify-between">
            <div>
                <span class="text-sm text-gray-500 dark:text-gray-400">Total Pengguna</span>
                <h4 class="mt-2 text-title-sm font-bold text-gray-800 dark:text-white/90">
                    {{ number_format($totalPengguna ?? 0, 0, ',', '.') }}
                </h4>
            </div>
        </div>
    </div>

    {{-- Card 2: Total Penawaran --}}
    <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03] md:p-6">
        <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-gray-100 dark:bg-gray-800">
            {{-- Icon: Price Tag / Offer --}}
            <svg class="fill-gray-800 dark:fill-white/90" width="28" height="28" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path d="M21.41 11.58l-9-9C12.05 2.22 11.55 2 11 2H4c-1.1 0-2 .9-2 2v7c0 .55.22 1.05.59 1.42l9 9c.36.36.86.58 1.41.58.55 0 1.05-.22 1.41-.59l7-7c.37-.36.59-.86.59-1.41 0-.55-.23-1.06-.59-1.42zM5.5 7C4.67 7 4 6.33 4 5.5S4.67 4 5.5 4 7 4.67 7 5.5 6.33 7 5.5 7z"/>
            </svg>
        </div>
        <div class="mt-5 flex items-end justify-between">
            <div>
                <span class="text-sm text-gray-500 dark:text-gray-400">Total Penawaran</span>
                <h4 class="mt-2 text-title-sm font-bold text-gray-800 dark:text-white/90">
                    {{ number_format($totalPenawaran ?? 0, 0, ',', '.') }}
                </h4>
            </div>
        </div>
    </div>

    {{-- Card 3: Total Pemesanan --}}
    <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03] md:p-6">
        <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-gray-100 dark:bg-gray-800">
            {{-- Icon: Shopping Cart / Orders --}}
            <svg class="fill-gray-800 dark:fill-white/90" width="28" height="28" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path d="M7 18c-1.1 0-1.99.9-1.99 2S5.9 22 7 22s2-.9 2-2-.9-2-2-2zM1 2v2h2l3.6 7.59-1.35 2.45c-.16.28-.25.61-.25.96 0 1.1.9 2 2 2h12v-2H7.42c-.14 0-.25-.11-.25-.25l.03-.12.9-1.63h7.45c.75 0 1.41-.41 1.75-1.03l3.58-6.49c.08-.14.12-.31.12-.48 0-.55-.45-1-1-1H5.21l-.94-2H1zm16 16c-1.1 0-1.99.9-1.99 2s.89 2 1.99 2 2-.9 2-2-.9-2-2-2z"/>
            </svg>
        </div>
        <div class="mt-5 flex items-end justify-between">
            <div>
                <span class="text-sm text-gray-500 dark:text-gray-400">Total Pemesanan</span>
                <h4 class="mt-2 text-title-sm font-bold text-gray-800 dark:text-white/90">
                    {{ number_format($totalPemesanan ?? 0, 0, ',', '.') }}
                </h4>
            </div>
        </div>
    </div>

    {{-- Card 4: Total Pembayaran --}}
    <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03] md:p-6">
        <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-gray-100 dark:bg-gray-800">
            {{-- Icon: Credit Card / Payment --}}
            <svg class="fill-gray-800 dark:fill-white/90" width="28" height="28" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path d="M20 4H4c-1.11 0-1.99.89-1.99 2L2 18c0 1.11.89 2 2 2h16c1.11 0 2-.89 2-2V6c0-1.11-.89-2-2-2zm0 14H4v-6h16v6zm0-10H4V6h16v2z"/>
            </svg>
        </div>
        <div class="mt-5 flex items-end justify-between">
            <div>
                <span class="text-sm text-gray-500 dark:text-gray-400">Total Pembayaran</span>
                <h4 class="mt-2 text-title-sm font-bold text-gray-800 dark:text-white/90">
                    {{ number_format($totalPembayaran ?? 0, 0, ',', '.') }}
                </h4>
            </div>
        </div>
    </div>

    {{-- Card 5: Total Pencairan (Withdraw) --}}
    <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03] md:p-6">
        <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-gray-100 dark:bg-gray-800">
            {{-- Icon: Bank / Withdraw --}}
            <svg class="fill-gray-800 dark:fill-white/90" width="28" height="28" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path d="M2 20h20v2H2v-2zm10-18l9 5v2H3V7l9-5zM6.5 10v7h2v-7h-2zm5 0v7h2v-7h-2zm5 0v7h2v-7h-2z"/>
            </svg>
        </div>
        <div class="mt-5 flex items-end justify-between">
            <div>
                <span class="text-sm text-gray-500 dark:text-gray-400">Total Pencairan</span>
                <h4 class="mt-2 text-title-sm font-bold text-gray-800 dark:text-white/90">
                    {{ number_format($totalPencairan ?? 0, 0, ',', '.') }}
                </h4>
            </div>
        </div>
    </div>

</div>
    </div>
</div>
@endsection