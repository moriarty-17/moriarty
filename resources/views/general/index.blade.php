@extends('template.frontend')

@section('content')
{{-- ==========================================
     1. HERO SECTION (KODE ASLI ANDA 100%)
     ========================================== --}}
<section class="relative isolate overflow-hidden"> <img
        src="{{ asset('') }}assets/img/hero.jpg"
        alt=""
        class="absolute inset-0 -z-10 h-[622px] w-full object-cover sm:h-[622px] lg:h-[667px]">

    <div class="absolute inset-0 -z-10 bg-gradient-to-bl from-transparent via-ink/80 to-ink/90"></div>

    <div
        class="mx-auto flex min-h-[420px] max-w-[1440px] flex-col justify-center px-4 py-24 sm:px-8 lg:min-h-[667px] lg:px-[81px]">
        <h1 class="max-w-[600px] text-3xl font-semibold leading-tight text-white sm:text-4xl lg:text-4xl">
            Jual Beli Akun Game, Aman, Cepat, dan Terpercaya
        </h1>

        <p class="mt-8 max-w-[407px] text-base leading-7 text-white sm:text-lg lg:text-xl lg:leading-8">
            Temukan akun game impianmu atau jual akun dengan mudah dan aman di Moriarty.
        </p>

        <a
            href="{{ url('/penawaran') }}"
            class="btn-animasi mt-9 inline-flex min-h-11 w-fit items-center rounded-lg bg-brand px-[6px] py-[6px] text-lg text-white shadow-lg shadow-brand/10 transition-all duration-300 ease-out transform hover:-translate-y-1 hover:opacity-90 hover:shadow-xl focus:outline-none focus-visible:ring-2 focus-visible:ring-sky/60 active:translate-y-0 active:scale-95">
            <span class="px-3">
                Jelajahi Penawaran
            </span>
        </a> 
    </div>
</section>

{{-- Pastikan mb-20 tetap ada agar tidak menabrak footer --}}
<section class="mb-20 bg-ink !pt-12 !pb-24 sm:!pt-24 sm:!pb-32 lg:!pt-32 lg:!pb-48">
    <div class="mx-auto max-w-[1266px] px-4 sm:px-8">

        <h2 class="text-2xl font-semibold text-white sm:text-3xl">
            Pilih Game Favoritmu
        </h2>

        {{-- ==========================================
             2. GAME LIST (KODE ASLI ANDA 100%)
             ========================================== --}}
        <div class="mt-10 grid grid-cols-2 gap-6 sm:mt-12 sm:grid-cols-3 lg:mt-14 lg:grid-cols-5 lg:gap-[50px]">
            <!-- Genshin Impact -->
            <a href="{{ url('/penawaran') }}?jenis=Genshin+Impact" class="group block text-center outline-none">
                <div class="relative aspect-square w-full overflow-hidden rounded-[20px] border border-line bg-card shadow-[inset_0_0_60px_rgba(56,189,248,0.45)] transition-all duration-300 ease-out group-hover:-translate-y-1 group-hover:border-sky/80 group-hover:shadow-[inset_0_0_60px_rgba(56,189,248,0.55),0_12px_30px_rgba(56,189,248,0.12)] group-focus-visible:border-sky group-focus-visible:shadow-[0_0_0_3px_rgba(56,189,248,0.14)]">
                    <img src="{{ asset('') }}assets/img/game-genshin.png" alt="Genshin Impact" class="h-full w-full object-cover transition-transform duration-500 ease-out group-hover:scale-[1.035]">
                </div>
                <p class="mt-4 text-base font-semibold text-white transition-colors duration-300 group-hover:text-sky sm:text-lg lg:text-xl">
                    Genshin Impact
                </p>
            </a>

            <!-- Honkai Star Rail -->
            <a href="{{ url('/penawaran') }}?jenis=Honkai+Star+Rail" class="group block text-center outline-none">
                <div class="relative aspect-square w-full overflow-hidden rounded-[20px] border border-line bg-card shadow-[inset_0_0_60px_rgba(56,189,248,0.45)] transition-all duration-300 ease-out group-hover:-translate-y-1 group-hover:border-sky/80 group-hover:shadow-[inset_0_0_60px_rgba(56,189,248,0.55),0_12px_30px_rgba(56,189,248,0.12)] group-focus-visible:border-sky group-focus-visible:shadow-[0_0_0_3px_rgba(56,189,248,0.14)]">
                    <img src="{{ asset('') }}assets/img/game-hsr.png" alt="Honkai Star Rail" class="h-full w-full object-cover transition-transform duration-500 ease-out group-hover:scale-[1.035]">
                </div>
                <p class="mt-4 text-base font-semibold text-white transition-colors duration-300 group-hover:text-sky sm:text-lg lg:text-xl">
                    Honkai Star Rail
                </p>
            </a>

            <!-- Neverness to Everness -->
            <a href="{{ url('/penawaran') }}?jenis=Neverness+to+Everness" class="group block text-center outline-none">
                <div class="relative aspect-square w-full overflow-hidden rounded-[20px] border border-line bg-card shadow-[inset_0_0_60px_rgba(56,189,248,0.45)] transition-all duration-300 ease-out group-hover:-translate-y-1 group-hover:border-sky/80 group-hover:shadow-[inset_0_0_60px_rgba(56,189,248,0.55),0_12px_30px_rgba(56,189,248,0.12)] group-focus-visible:border-sky group-focus-visible:shadow-[0_0_0_3px_rgba(56,189,248,0.14)]">
                    <img src="{{ asset('') }}assets/img/game-nte.jpg" alt="Neverness to Everness" class="h-full w-full object-cover transition-transform duration-500 ease-out group-hover:scale-[1.035]">
                </div>
                <p class="mt-4 text-base font-semibold text-white transition-colors duration-300 group-hover:text-sky sm:text-lg lg:text-xl">
                    Neverness to Everness
                </p>
            </a>

            <!-- Zenless Zone Zero -->
            <a href="{{ url('/penawaran') }}?jenis=Zenless+Zone+Zero" class="group block text-center outline-none">
                <div class="relative aspect-square w-full overflow-hidden rounded-[20px] border border-line bg-card shadow-[inset_0_0_60px_rgba(56,189,248,0.45)] transition-all duration-300 ease-out group-hover:-translate-y-1 group-hover:border-sky/80 group-hover:shadow-[inset_0_0_60px_rgba(56,189,248,0.55),0_12px_30px_rgba(56,189,248,0.12)] group-focus-visible:border-sky group-focus-visible:shadow-[0_0_0_3px_rgba(56,189,248,0.14)]">
                    <img src="{{ asset('') }}assets/img/game-zzz.png" alt="Zenless Zone Zero" class="h-full w-full object-cover transition-transform duration-500 ease-out group-hover:scale-[1.035]">
                </div>
                <p class="mt-4 text-base font-semibold text-white transition-colors duration-300 group-hover:text-sky sm:text-lg lg:text-xl">
                    Zenless Zone Zero
                </p>
            </a>

            <!-- Wuthering Waves -->
            <a href="{{ url('/penawaran') }}?jenis=Wuthering+Waves" class="group block text-center outline-none">
                <div class="relative aspect-square w-full overflow-hidden rounded-[20px] border border-line bg-card shadow-[inset_0_0_60px_rgba(56,189,248,0.45)] transition-all duration-300 ease-out group-hover:-translate-y-1 group-hover:border-sky/80 group-hover:shadow-[inset_0_0_60px_rgba(56,189,248,0.55),0_12px_30px_rgba(56,189,248,0.12)] group-focus-visible:border-sky group-focus-visible:shadow-[0_0_0_3px_rgba(56,189,248,0.14)]">
                    <img src="{{ asset('') }}assets/img/game-ww.png" alt="Wuthering Waves" class="h-full w-full object-cover transition-transform duration-500 ease-out group-hover:scale-[1.035]">
                </div>
                <p class="mt-4 text-base font-semibold text-white transition-colors duration-300 group-hover:text-sky sm:text-lg lg:text-xl">
                    Wuthering Waves
                </p>
            </a>
        </div>

        {{-- ==========================================
             3. CARA KERJA (RE-DESIGN MODERN & FIXED)
             ========================================== --}}
        <div class="mt-28 sm:mt-36 lg:mt-48 pb-20 lg:pb-32">
            <div class="flex flex-col items-center text-center">
                <h2 class="text-2xl font-semibold text-white sm:text-3xl lg:text-4xl">
                    Bagaimana Cara Kerjanya?
                </h2>
                <p class="mt-4 max-w-2xl text-base text-muted">Kami menghubungkan pembeli dan penjual melalui sistem yang aman, transparan, dan otomatis.</p>
            </div>

            <div class="mt-12 grid gap-6 sm:grid-cols-2 lg:grid-cols-4 lg:gap-8">

                {{-- Card 1 --}}
                <div class="group relative flex flex-col items-center text-center overflow-hidden rounded-[16px] border border-line bg-card p-6 transition-all duration-300 hover:-translate-y-2 hover:border-sky/50 hover:shadow-[0_8px_30px_rgba(56,189,248,0.15)]">
                    {{-- Glow Effect background --}}
                    <div class="absolute -right-6 -top-6 h-24 w-24 rounded-full bg-sky/10 blur-2xl transition-all duration-300 group-hover:bg-sky/25"></div>

                    {{-- Icon Wrapper (Diberikan ukuran paten h-16 w-16 shrink-0 flex-none) --}}
                    <div class="relative z-10 mb-6 flex h-16 w-16 shrink-0 flex-none items-center justify-center rounded-2xl bg-panel ring-1 ring-line transition-all duration-300 group-hover:ring-sky/50 group-hover:bg-sky/10">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="h-8 w-8 shrink-0 text-sky">
                            <path d="M12 1.5 3.5 5v6.2c0 5.3 3.6 9.7 8.5 11.3 4.9-1.6 8.5-6 8.5-11.3V5L12 1.5zm-1 14.3-3.8-3.8 1.6-1.6 2.2 2.2 5-5 1.6 1.6-6.6 6.6z" />
                        </svg>
                    </div>

                    <h3 class="relative z-10 text-lg font-bold text-white sm:text-xl">100% Aman</h3>
                    <p class="relative z-10 mt-3 text-sm leading-relaxed text-muted">Dana ditahan oleh sistem kami hingga akun diterima dan diverifikasi dengan aman oleh pembeli.</p>
                </div>

                {{-- Card 2 --}}
                <div class="group relative flex flex-col items-center text-center overflow-hidden rounded-[16px] border border-line bg-card p-6 transition-all duration-300 hover:-translate-y-2 hover:border-sky/50 hover:shadow-[0_8px_30px_rgba(56,189,248,0.15)]">
                    <div class="absolute -right-6 -top-6 h-24 w-24 rounded-full bg-sky/10 blur-2xl transition-all duration-300 group-hover:bg-sky/25"></div>

                    <div class="relative z-10 mb-6 flex h-16 w-16 shrink-0 flex-none items-center justify-center rounded-2xl bg-panel ring-1 ring-line transition-all duration-300 group-hover:ring-sky/50 group-hover:bg-sky/10">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="h-8 w-8 shrink-0 text-sky">
                            <path d="M13.5 1.5 4 13.2h6.1L9.4 22.5 19.5 10h-6.6l.6-8.5z" />
                        </svg>
                    </div>

                    <h3 class="relative z-10 text-lg font-bold text-white sm:text-xl">Proses Cepat</h3>
                    <p class="relative z-10 mt-3 text-sm leading-relaxed text-muted">Sistem konfirmasi dan pengiriman otomatis. Transaksi selesai dalam hitungan menit 24/7.</p>
                </div>

                {{-- Card 3 --}}
                <div class="group relative flex flex-col items-center text-center overflow-hidden rounded-[16px] border border-line bg-card p-6 transition-all duration-300 hover:-translate-y-2 hover:border-sky/50 hover:shadow-[0_8px_30px_rgba(56,189,248,0.15)]">
                    <div class="absolute -right-6 -top-6 h-24 w-24 rounded-full bg-sky/10 blur-2xl transition-all duration-300 group-hover:bg-sky/25"></div>

                    <div class="relative z-10 mb-6 flex h-16 w-16 shrink-0 flex-none items-center justify-center rounded-2xl bg-panel ring-1 ring-line transition-all duration-300 group-hover:ring-sky/50 group-hover:bg-sky/10">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="h-8 w-8 shrink-0 text-sky">
                            <path d="M8.5 11.5a4.25 4.25 0 1 0 0-8.5 4.25 4.25 0 0 0 0 8.5zM1 20.2C1 16.4 4.2 13.3 8.5 13.3c1.4 0 2.7.3 3.8.9v6H1v-.0zM22.6 12.9l-4.1-4.1a1.5 1.5 0 0 0-1-.4h-3a1.5 1.5 0 0 0-1.5 1.5v3c0 .4.1.7.4 1l4.1 4.1c.6.6 1.5.6 2.1 0l3-3c.6-.6.6-1.5 0-2.1zm-6.4-1.4a1.1 1.1 0 1 1 0-2.2 1.1 1.1 0 0 1 0 2.2z" />
                        </svg>
                    </div>

                    <h3 class="relative z-10 text-lg font-bold text-white sm:text-xl">Seller Terpercaya</h3>
                    <p class="relative z-10 mt-3 text-sm leading-relaxed text-muted">Setiap penjual melewati verifikasi identitas yang ketat dan sistem ulasan real-time.</p>
                </div>

                {{-- Card 4 --}}
                <div class="group relative flex flex-col items-center text-center overflow-hidden rounded-[16px] border border-line bg-card p-6 transition-all duration-300 hover:-translate-y-2 hover:border-sky/50 hover:shadow-[0_8px_30px_rgba(56,189,248,0.15)]">
                    <div class="absolute -right-6 -top-6 h-24 w-24 rounded-full bg-sky/10 blur-2xl transition-all duration-300 group-hover:bg-sky/25"></div>

                    <div class="relative z-10 mb-6 flex h-16 w-16 shrink-0 flex-none items-center justify-center rounded-2xl bg-panel ring-1 ring-line transition-all duration-300 group-hover:ring-sky/50 group-hover:bg-sky/10">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="h-8 w-8 shrink-0 text-sky">
                            <path d="M12 2C6.9 2 3 6 3 11v6a3 3 0 0 0 3 3h2v-8H5.2v-1c0-3.8 3-6.8 6.8-6.8s6.8 3 6.8 6.8v1H16v8h2.2a3 3 0 0 0 .8-.1V21a1 1 0 0 1-1 1h-4v2h4a3 3 0 0 0 3-3v-10c0-5-3.9-9-9-9z" />
                        </svg>
                    </div>

                    <h3 class="relative z-10 text-lg font-bold text-white sm:text-xl">Layanan Bantuan</h3>
                    <p class="relative z-10 mt-3 text-sm leading-relaxed text-muted">Tim support kami selalu siap sedia membantu kendala Anda dengan cepat dan responsif.</p>
                </div>
            </div>

        </div>

        {{-- Spacer fisik paksa agar tidak bisa tertimpa atau menempel ke footer --}}
        <div style="height: 120px; width: 100%; clear: both;" class="block"></div>
</section>

@endsection