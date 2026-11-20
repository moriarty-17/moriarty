@extends('template.frontend')

@section('content')
<div class="mx-auto w-full max-w-[800px] px-4 py-8 sm:px-8 sm:py-10 lg:py-12">
    <div class="rounded-[10px] border-4 border-line bg-card p-6 sm:p-8 lg:p-10 shadow-sm">
        
        <!-- Header -->
        <div class="mb-8 border-b border-line pb-4">
            <h1 class="text-2xl font-semibold text-light sm:text-[28px]">
                Beri Ulasan Pesanan
            </h1>
            <p class="mt-2 text-sm text-muted">
                Bagaimana pengalaman transaksi Anda? Ulasan Anda akan sangat membantu pembeli lain.
            </p>
        </div>

        <!-- Ringkasan Produk yang Direview -->
        @php
            // Logika gambar yang sama amannya dengan halaman riwayat pesanan
            $gambarUtama = asset('assets/img/listing-wide.jpg');
            if ($pemesanan->penawaran && isset($pemesanan->penawaran->gambarPenawaran)) {
                $gambarRecord = $pemesanan->penawaran->gambarPenawaran->first();
                if ($gambarRecord) {
                    $namaFileGambar = $gambarRecord->path_gambar ?? $gambarRecord->gambar ?? null;
                    if ($namaFileGambar) {
                        $gambarUtama = Str::startsWith($namaFileGambar, 'http') 
                            ? $namaFileGambar 
                            : asset('assets/resource/penawaran/' . $namaFileGambar);
                    }
                }
            }
        @endphp

        <div class="mb-8 flex flex-col items-start gap-4 sm:flex-row sm:items-center rounded-lg border border-line bg-panel p-4">
            <img src="{{ $gambarUtama }}" alt="Gambar Produk" class="h-20 w-36 min-w-[144px] rounded object-cover border border-white/10 shadow-sm">
            <div class="flex flex-col">
                <span class="text-xs font-bold text-sky tracking-wide mb-1">
                    PSN-{{ $pemesanan->id_pemesanan }}
                </span>
                <h3 class="text-sm font-medium leading-snug text-light line-clamp-2">
                    {{ $pemesanan->penawaran->judul_penawaran ?? $pemesanan->penawaran->judul ?? 'Penawaran Game' }}
                </h3>
            </div>
        </div>

        <!-- Form Ulasan -->
        <form action="{{ route('general.review.store', \Vinkla\Hashids\Facades\Hashids::encode($pemesanan->id_pemesanan)) }}" method="POST">
            @csrf

            <!-- Interaktif Rating Bintang -->
            <div class="mb-6">
                <label class="block text-sm font-semibold text-light mb-3">Penilaian Kualitas & Layanan</label>
                
                <!-- Input hidden untuk menyimpan nilai angka rating (Default: 5) -->
                <input type="hidden" name="rating" id="rating_input" value="5" required>
                
                <div class="flex items-center gap-2" id="star_container">
                    @for($i = 1; $i <= 5; $i++)
                        <button type="button" data-val="{{ $i }}" class="star-btn focus:outline-none transition-transform hover:scale-110">
                            <!-- Bintang SVG -->
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-10 h-10 text-yellow-400 star-svg transition-colors">
                                <path fill-rule="evenodd" d="M10.788 3.21c.448-1.077 1.976-1.077 2.424 0l2.082 5.006 5.404.434c1.164.093 1.636 1.545.749 2.305l-4.117 3.527 1.257 5.273c.271 1.136-.964 2.033-1.96 1.425L12 18.354 7.373 21.18c-.996.608-2.231-.29-1.96-1.425l1.257-5.273-4.117-3.527c-.887-.76-.415-2.212.749-2.305l5.404-.434 2.082-5.005Z" clip-rule="evenodd" />
                            </svg>
                        </button>
                    @endfor
                </div>
                <p id="rating_text" class="mt-2 text-xs font-medium text-sky">Sempurna!</p>
            </div>

            <!-- Teks Ulasan -->
            <div class="mb-8">
                <label for="komentar" class="block text-sm font-semibold text-light mb-2">Tulis Ulasan Anda <span class="text-muted font-normal">(Opsional)</span></label>
                <textarea name="komentar" id="komentar" rows="5" 
                    placeholder="Ceritakan pengalaman Anda bertransaksi dengan penjual ini (Kecepatan, Keramahan, Kesesuaian Akun, dll)..." 
                    class="w-full rounded-md border border-line bg-panel px-4 py-3 text-sm text-light placeholder-muted focus:border-sky focus:outline-none focus:ring-1 focus:ring-sky transition-colors"></textarea>
            </div>

            <!-- Tombol Aksi -->
            <div class="flex items-center justify-end gap-4">
                <a href="{{ route('pemesanan.index', ['status' => 'selesai']) }}" class="text-sm font-semibold text-muted hover:text-light transition-colors">
                    Batal
                </a>
                <button type="submit" class="inline-flex min-w-[120px] items-center justify-center rounded-sm border border-[2px] border-sky bg-sky/10 px-6 py-2.5 text-sm font-semibold text-sky hover:bg-sky hover:text-white transition-colors shadow-sm">
                    Kirim Ulasan
                </button>
            </div>
        </form>

    </div>
</div>

<!-- Logika JavaScript Bintang -->
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const stars = document.querySelectorAll('.star-btn');
        const svgs = document.querySelectorAll('.star-svg');
        const ratingInput = document.getElementById('rating_input');
        const ratingText = document.getElementById('rating_text');
        
        let currentRating = 5; // Bawaan 5 Bintang

        const textDesc = {
            1: "Sangat Buruk",
            2: "Buruk",
            3: "Cukup",
            4: "Bagus",
            5: "Sempurna!"
        };

        // Fungsi untuk mewarnai bintang
        function highlightStars(rating) {
            svgs.forEach((svg, index) => {
                if (index < rating) {
                    // Aktif: Warna kuning emas
                    svg.classList.remove('text-muted');
                    svg.classList.add('text-yellow-400');
                } else {
                    // Tidak Aktif: Warna redup
                    svg.classList.remove('text-yellow-400');
                    svg.classList.add('text-muted');
                }
            });
            ratingText.innerText = textDesc[rating] || "";
        }

        // Event Listeners untuk interaksi hover & klik
        stars.forEach((star, index) => {
            const val = parseInt(star.getAttribute('data-val'));

            // Saat kursor masuk
            star.addEventListener('mouseenter', () => {
                highlightStars(val);
            });

            // Saat kursor keluar (kembali ke nilai yang dipilih)
            star.addEventListener('mouseleave', () => {
                highlightStars(currentRating);
            });

            // Saat diklik (simpan nilai)
            star.addEventListener('click', () => {
                currentRating = val;
                ratingInput.value = currentRating;
                highlightStars(currentRating);
                
                // Animasi klik (opsional kecil)
                star.classList.add('scale-90');
                setTimeout(() => star.classList.remove('scale-90'), 150);
            });
        });
    });
</script>
@endsection