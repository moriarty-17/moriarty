@extends('template.backend')
@section('content')
<div class="p-4 mx-auto max-w-(--breakpoint-2xl) md:p-6">
    
    {{-- Tombol Kembali --}}
    <div class="mb-5 sm:mb-6">
        <a href="{{ url('/be/penawaran') }}" class="inline-flex items-center text-sm font-medium text-gray-500 hover:text-brand dark:text-gray-400 dark:hover:text-brand transition-colors">
            <svg class="mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Kembali ke Daftar Penawaran
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-5 sm:gap-6">
        
        {{-- KOLOM KIRI: Informasi Detail & Gambar --}}
        <div class="lg:col-span-2 space-y-5 sm:space-y-6">
            
            {{-- Card Galeri Gambar --}}
            <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03] sm:p-6">
                <h3 class="mb-4 text-base font-medium text-gray-800 dark:text-white/90">
                    Galeri Gambar (Moderasi)
                </h3>
                
                @if($penawaran->gambarPenawaran && $penawaran->gambarPenawaran->count() > 0)
                    <div class="grid grid-cols-2 sm:grid-cols-3 xl:grid-cols-4 gap-4">
                        @foreach($penawaran->gambarPenawaran as $gambar)
                            <div class="relative overflow-hidden rounded-xl border border-gray-100 dark:border-gray-800 group cursor-pointer" 
                                 onclick="bukaPopupGambar('{{ asset('assets/resource/penawaran/' . $gambar->gambar) }}')">
                                
                                <img src="{{ asset('assets/resource/penawaran/' . $gambar->gambar) }}" 
                                     alt="Gambar Penawaran" 
                                     class="aspect-square w-full object-cover transition-transform duration-300 group-hover:scale-110">
                                
                                <div class="absolute inset-0 bg-black/0 group-hover:bg-black/30 transition-colors flex items-center justify-center">
                                    <svg class="w-8 h-8 text-white opacity-0 group-hover:opacity-100 transition-opacity" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7"></path>
                                    </svg>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="rounded-xl border border-dashed border-gray-300 bg-gray-50 p-6 text-center dark:border-gray-700 dark:bg-gray-800/50">
                        <p class="text-sm text-gray-500 dark:text-gray-400">Tidak ada gambar yang dilampirkan oleh penjual.</p>
                    </div>
                @endif
            </div>

            {{-- Card Deskripsi Penawaran --}}
            <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03] sm:p-6">
                <h3 class="mb-4 text-base font-medium text-gray-800 dark:text-white/90">
                    Deskripsi Produk
                </h3>
                <div class="prose prose-sm max-w-none text-gray-500 dark:text-gray-400">
                    {!! nl2br(e($penawaran->deskripsi)) !!}
                </div>
            </div>

        </div>

        {{-- KOLOM KANAN: Informasi Seller & Ringkasan --}}
        <div class="space-y-5 sm:space-y-6">
            
            {{-- Card Info Utama --}}
            <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03] sm:p-6">
                <h3 class="mb-4 text-base font-medium text-gray-800 dark:text-white/90">
                    Informasi Akun
                </h3>
                
                <div class="space-y-4">
                    <div>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Judul Penawaran</p>
                        <p class="font-medium text-gray-800 dark:text-white/90">{{ $penawaran->judul }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Kode Penawaran</p>
                        <p class="font-medium text-gray-800 dark:text-white/90">{{ $penawaran->kode_penawaran }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Nama Game / Kategori</p>
                        <p class="font-medium text-gray-800 dark:text-white/90">{{ $penawaran->jenis ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Harga</p>
                        <p class="text-lg font-bold text-brand">IDR {{ number_format($penawaran->harga, 0, ',', '.') }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mb-1">Status</p>
                        @if(strtolower($penawaran->status) === 'active' || strtolower($penawaran->status) === 'aktif')
                            <span class="inline-flex rounded-full px-3 py-1 text-xs font-medium bg-success-50 text-success-600 dark:bg-success-500/15 dark:text-success-500">Active</span>
                        @elseif(strtolower($penawaran->status) === 'sold' || strtolower($penawaran->status) === 'terjual')
                            <span class="inline-flex rounded-full px-3 py-1 text-xs font-medium bg-error-50 text-error-600 dark:bg-error-500/15 dark:text-error-500">Sold</span>
                        @else
                            <span class="inline-flex rounded-full px-3 py-1 text-xs font-medium bg-warning-50 text-warning-600 dark:bg-warning-500/15 dark:text-orange-400">{{ ucfirst($penawaran->status) }}</span>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Card Info Penjual --}}
            <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03] sm:p-6">
                <h3 class="mb-4 text-base font-medium text-gray-800 dark:text-white/90">
                    Informasi Penjual
                </h3>
                
                <div class="flex items-center gap-4 mb-4">
                    <div class="h-12 w-12 rounded-full bg-brand/10 flex items-center justify-center text-brand font-bold text-lg">
                        {{ strtoupper(substr($penawaran->Pengguna->username ?? 'U', 0, 1)) }}
                    </div>
                    <div>
                        <p class="font-medium text-gray-800 dark:text-white/90">{{ $penawaran->Pengguna->username ?? 'Tidak Diketahui' }}</p>
                        <p class="text-sm text-gray-500 dark:text-gray-400">{{ $penawaran->Pengguna->email ?? '-' }}</p>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<!-- ============================================== -->
<!-- POP-UP / MODAL GAMBAR (Ukuran Card Login)      -->
<!-- ============================================== -->
<div id="modalGambar" class="fixed inset-0 z-[9999] hidden bg-black/80 p-4 transition-opacity flex-col items-center justify-center">
    
    <!-- Area klik di luar card untuk menutup popup -->
    <div class="absolute inset-0 cursor-pointer" onclick="tutupPopupGambar()"></div>

    <!-- KOTAK KECIL (Mirip form login) -->
    <div class="relative w-full max-w-md bg-white dark:bg-gray-900 rounded-2xl shadow-2xl p-4 sm:p-5 border border-gray-200 dark:border-gray-800 z-10 scale-95 transform transition-transform animate-fade-in-up">
        
        <!-- Header Popup -->
        <div class="flex items-center justify-between mb-4">
            <h4 class="text-sm font-semibold text-gray-800 dark:text-white/90">Preview Gambar</h4>
            <button onclick="tutupPopupGambar()" class="text-gray-400 hover:text-bad transition-colors p-1 bg-gray-100 hover:bg-bad/10 rounded-lg dark:bg-gray-800">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>

        <!-- Gambar Preview (diperkecil agar muat di kotak) -->
        <div class="bg-gray-100 dark:bg-gray-800 rounded-xl overflow-hidden flex items-center justify-center p-2 mb-4">
            <img id="gambarAsli" src="" alt="Preview" class="max-h-[45vh] w-auto object-contain rounded-lg">
        </div>
        
        <!-- Tombol Resolusi Asli (Gaya tombol form login) -->
        <a id="linkGambarAsli" href="" target="_blank" class="w-full flex justify-center items-center rounded-xl bg-brand py-2.5 px-4 text-sm font-semibold text-white shadow-lg shadow-brand/20 transition-all hover:-translate-y-0.5 hover:bg-brand/90 hover:shadow-brand/30">
            <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
            </svg>
            Lihat Resolusi Asli
        </a>
    </div>
</div>

<!-- SCRIPT UNTUK MODAL GAMBAR -->
<script>
    function bukaPopupGambar(srcGambar) {
        document.getElementById('gambarAsli').src = srcGambar;
        document.getElementById('linkGambarAsli').href = srcGambar;
        
        document.getElementById('modalGambar').classList.remove('hidden');
        document.getElementById('modalGambar').classList.add('flex');
        document.body.style.overflow = 'hidden'; 
    }

    function tutupPopupGambar() {
        document.getElementById('modalGambar').classList.add('hidden');
        document.getElementById('modalGambar').classList.remove('flex');
        document.body.style.overflow = 'auto'; 
    }

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && !document.getElementById('modalGambar').classList.contains('hidden')) {
            tutupPopupGambar();
        }
    });
</script>
@endsection