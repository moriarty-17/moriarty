<!DOCTYPE html>
<html lang="id" class="scroll-smooth">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Beranda &middot; Moriarty</title>

    <link rel="icon" href="{{ asset('') }}assets/img/logo.png">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <!-- <script src="https://cdn.tailwindcss.com"></script> -->
    <style>
        /* Class kustom untuk semua tombol yang butuh animasi hover */
        .btn-animasi {
            transition: all 0.3s ease-out !important;
        }

        .btn-animasi:hover {
            transform: translateY(-4px) !important;
            /* Bergerak ke atas */
            filter: brightness(0.9) !important;
            /* Sedikit lebih gelap/efek hover */
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.15) !important;
            /* Bayangan membesar */
        }

        .btn-animasi:active {
            transform: translateY(0) scale(0.96) !important;
            /* Efek ditekan */
        }
    </style>
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <link rel="icon" href="{{ asset('') }}assets/favicon.ico">

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('[data-dropdown]').forEach(function(dropdown) {
                const trigger = dropdown.querySelector('[data-dropdown-trigger]');
                const menu = dropdown.querySelector('[data-dropdown-menu]');

                if (!trigger || !menu) {
                    return;
                }

                trigger.addEventListener('click', function(event) {
                    event.stopPropagation();

                    const isOpen = !menu.hasAttribute('hidden');

                    document.querySelectorAll('[data-dropdown-menu]').forEach(function(otherMenu) {
                        otherMenu.setAttribute('hidden', '');
                    });

                    document.querySelectorAll('[data-dropdown-trigger]').forEach(function(
                        otherTrigger) {
                        otherTrigger.setAttribute('aria-expanded', 'false');
                    });

                    if (!isOpen) {
                        menu.removeAttribute('hidden');
                        trigger.setAttribute('aria-expanded', 'true');
                    }
                });
            });

            document.addEventListener('click', function() {
                document.querySelectorAll('[data-dropdown-menu]').forEach(function(menu) {
                    menu.setAttribute('hidden', '');
                });

                document.querySelectorAll('[data-dropdown-trigger]').forEach(function(trigger) {
                    trigger.setAttribute('aria-expanded', 'false');
                });
            });

            document.querySelectorAll('[data-dropdown-menu]').forEach(function(menu) {
                menu.addEventListener('click', function(event) {
                    event.stopPropagation();
                });
            });

            const imageInput = document.getElementById('ktp_image');
            const imagePreview = document.getElementById('ktp_preview');
            const uploadPlaceholder = document.getElementById('upload-placeholder');

            if (imageInput && imagePreview && uploadPlaceholder) {
                imageInput.addEventListener('change', function(event) {
                    imagePreview.innerHTML = '';

                    const files = Array.from(event.target.files);

                    if (files.length === 0) {
                        imagePreview.classList.add('hidden');
                        imagePreview.classList.remove('grid');
                        uploadPlaceholder.classList.remove('hidden');
                        return;
                    }

                    uploadPlaceholder.classList.add('hidden');
                    imagePreview.classList.remove('hidden');
                    imagePreview.classList.add('grid');

                    files.forEach(function(file) {
                        if (!file.type.startsWith('image/')) {
                            return;
                        }

                        if (file.size > 2 * 1024 * 1024) {
                            alert(file.name + ' melebihi ukuran 2MB.');
                            return;
                        }

                        const reader = new FileReader();

                        reader.onload = function(e) {
                            const wrapper = document.createElement('div');

                            wrapper.className =
                                'relative overflow-hidden rounded-lg border border-line bg-card transition-all duration-300 hover:border-sky/70 hover:shadow-lg hover:shadow-sky/10';

                            const img = document.createElement('img');

                            img.src = e.target.result;
                            img.alt = 'Preview gambar';
                            img.className =
                                'h-[150px] w-full object-cover transition-transform duration-300 hover:scale-[1.02]';

                            wrapper.appendChild(img);
                            imagePreview.appendChild(wrapper);
                        };

                        reader.readAsDataURL(file);
                    });
                });
            }
        });
    </script>
</head>

<body class="min-h-screen bg-ink font-sans text-light antialiased">

    <header class="sticky top-0 z-40">
        <div class="border-b border-line bg-panel">
            <div
                class="mx-auto flex min-h-[72px] w-full max-w-[1440px] items-center gap-3 px-4 py-3 sm:min-h-[78px] sm:gap-4 sm:px-8 sm:py-4 lg:min-h-[82px] lg:gap-6 lg:px-[43px] lg:py-[18px]">

                <a href="{{ url('/') }}"
                    class="flex shrink-0 items-center gap-2.5 transition-opacity duration-300 hover:opacity-90 sm:gap-3 lg:gap-3.5">
                    <img src="{{ asset('') }}assets/img/logo.png" alt="Moriarty"
                        class="h-8 w-8 object-contain sm:h-9 sm:w-9 lg:h-[48px] lg:w-[48px]">
                    <span
                        class="text-xl font-bold tracking-tight text-light sm:text-[22px] lg:text-[25px] xl:text-[26px]">
                        MORIARTY
                    </span>
                </a>

                <!-- BUNGKUSAN UTAMA (Mengatur lebar search bar dan pop-up agar sama persis) -->
                <div class="relative mx-auto w-full max-w-[420px] sm:max-w-[520px] md:max-w-[600px] lg:max-w-[650px] xl:max-w-[720px]">

                    <!-- 1. SEARCH BAR (Diubah dari <button> menjadi <div> berisi <input>) -->
                    <!-- Ditambahkan focus-within agar saat diketik, efek hover-nya tetap menyala -->
                    <div class="group flex h-10 min-w-0 w-full items-center gap-2.5 rounded-full border border-searchline bg-searchbg px-3 transition-all duration-300 ease-out focus-within:border-sky/60 focus-within:shadow-[0_0_20px_rgba(56,189,248,0.08)] hover:border-sky/60 hover:shadow-[0_0_20px_rgba(56,189,248,0.08)] sm:h-11 sm:gap-3 sm:px-3.5 lg:h-11 lg:px-4">

                        <!-- Icon Kaca Pembesar -->
                        <span class="flex h-5 w-5 shrink-0 items-center justify-center text-muted transition-colors duration-300 group-focus-within:text-sky group-hover:text-sky">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" class="h-[18px] w-[18px]">
                                <circle cx="11" cy="11" r="7" />
                                <path d="M20 20l-3.5-3.5" />
                            </svg>
                        </span>

                        <!-- Input Tempat Mengetik (ID disamakan dengan JS Anda) -->
                        <input type="text" id="modal-search-input" placeholder="Cari nama penjual..." autocomplete="off"
                            class="h-full w-full bg-transparent text-xs font-medium text-light outline-none ring-0 placeholder:text-muted focus:border-0 focus:outline-none focus:ring-0 sm:text-sm lg:text-[14px]">
                    </div>

                    <!-- 2. POP-UP HASIL PENCARIAN (Muncul tepat di bawah search bar) -->
                    <!-- Menggunakan 'absolute top-full mt-2' agar posisinya menggantung ke bawah -->
                    <div id="search-modal" style="z-index: 99999;" class="absolute left-0 top-full mt-2 hidden w-full overflow-hidden rounded-xl border border-line bg-card shadow-2xl transition-opacity duration-300">

                        <div class="flex max-h-[50vh] flex-col overflow-y-auto custom-scrollbar bg-card py-2" id="modal-search-results">
                            <div class="px-4 py-3 text-xs font-bold uppercase tracking-wider text-muted sm:px-6">Hasil Pencarian</div>

                            <div id="search-results-list" class="flex flex-col">
                                <!-- Template Baris Data Disuntikkan via JS -->
                                <div id="search-empty-state" class="px-4 py-8 text-center text-sm text-muted sm:px-6">
                                    Ketik nama penjual untuk memulai pencarian...
                                </div>
                            </div>
                        </div>

                    </div>

                </div>

                @auth
                <div class="relative ml-auto shrink-0" data-dropdown>
                    <button type="button" data-dropdown-trigger aria-expanded="false" aria-haspopup="true"
                        class="flex h-10 w-10 items-center justify-center rounded-full border border-line bg-card p-0.5 outline-none transition-all duration-300 ease-out hover:border-sky/70 hover:shadow-[0_0_0_3px_rgba(56,189,248,0.08)] focus:border-sky focus:outline-none focus-visible:ring-2 focus-visible:ring-sky/60 focus-visible:ring-offset-0 sm:h-11 sm:w-11 lg:h-12 lg:w-12">
                        <img src="{{ Auth::user()->gambar ? asset('assets/resource/profil/' . Auth::user()->gambar) : asset('assets/img/avatar.png') }}"
                            alt="Akun saya" class="h-full w-full rounded-full object-cover object-top">
                    </button>

                    <div data-dropdown-menu hidden
                        class="absolute right-0 z-50 mt-3 w-[278px] max-w-[calc(100vw-2rem)] rounded-[11px] border border-line bg-card p-2.5 shadow-2xl shadow-ink/40 backdrop-blur-md">
                        @if (Auth::user()->role === 'seller')
                        <a href="{{ url('seller-dashboard') }}"
                            class="block rounded-md border border-line bg-panel px-3 py-2.5 text-sm font-semibold text-light outline-none transition-all duration-300 ease-out hover:border-sky/60 hover:bg-searchbg hover:text-sky focus:border-sky focus:outline-none focus-visible:ring-2 focus-visible:ring-sky/30">
                            Dashboard Seller
                        </a>
                        @endif
                        <a href="{{ url('/profil') }}"
                            class="mt-1.5 block rounded-md border border-line bg-panel px-3 py-2.5 text-sm font-semibold text-light outline-none transition-all duration-300 ease-out hover:border-sky/60 hover:bg-searchbg hover:text-sky focus:border-sky focus:outline-none focus-visible:ring-2 focus-visible:ring-sky/30">
                            Profil
                        </a>
                        <a href="{{ url('riwayat-pemesanan') }}"
                            class="mt-1.5 block rounded-md border border-line bg-panel px-3 py-2.5 text-sm font-semibold text-light outline-none transition-all duration-300 ease-out hover:border-sky/60 hover:bg-searchbg hover:text-sky focus:border-sky focus:outline-none focus-visible:ring-2 focus-visible:ring-sky/30">
                            Riwayat Pemesanan
                        </a>

                        <form action="{{ url('logout') }}" method="POST">
                            @csrf
                            <button type="submit"
                                class="mt-1.5 flex min-h-10 w-full items-center justify-center rounded-md border border-bad/30 bg-bad/10 px-3 py-2 text-sm font-semibold text-bad outline-none transition-all duration-300 ease-out hover:border-bad/50 hover:bg-bad/20 focus:border-bad focus:outline-none focus-visible:ring-2 focus-visible:ring-bad/25">
                                Keluar
                            </button>
                        </form>
                    </div>
                </div>
                @else
                <a href="{{ url('/sign-in') }}"
                    class="btn-animasi ml-auto inline-flex min-h-10 shrink-0 items-center justify-center rounded-full bg-brand px-3.5 py-2 text-xs font-medium text-white outline-none transition-all duration-300 ease-out hover:-translate-y-0.5 hover:bg-brand/90 hover:shadow-lg hover:shadow-brand/20 focus:outline-none focus-visible:ring-2 focus-visible:ring-sky/60 active:translate-y-0 sm:px-4 sm:text-sm lg:px-5">
                    Masuk
                </a>
                @endauth
            </div>
        </div>

        <!-- NAVBAR SEMULA DENGAN EFEK HOVER BLOK YANG MENARIK -->
        <nav class="border-b border-line bg-strip shadow-strip">
            <div class="mx-auto flex max-w-[1440px] items-center justify-center gap-3 overflow-x-auto px-4 py-3 sm:gap-4 sm:px-8 sm:py-3.5 lg:gap-6 lg:px-[43px] lg:py-2">

                <!-- Menu Beranda -->
                <a href="{{ url('/') }}"
                    class="shrink-0 rounded-lg px-3.5 py-1.5 text-sm font-semibold transition-all duration-300 sm:text-[15px] {{ request()->is('/') ? 'text-sky bg-sky/10' : 'text-light hover:bg-panel hover:text-sky' }}">
                    Beranda
                </a>

                <!-- Menu Semua Game -->
                <a href="{{ url('penawaran') }}"
                    class="shrink-0 rounded-lg px-3.5 py-1.5 text-sm font-semibold transition-all duration-300 sm:text-[15px] {{ request()->is('penawaran*') ? 'text-sky bg-sky/10' : 'text-light hover:bg-panel hover:text-sky' }}">
                    Semua Game
                </a>

                <!-- Menu Tentang -->
                <a href="#tentang"
                    class="shrink-0 rounded-lg px-3.5 py-1.5 text-sm font-semibold text-light transition-all duration-300 hover:bg-panel hover:text-sky sm:text-[15px]">
                    Tentang
                </a>

                <!-- Menu Bantuan -->
                <a href="#bantuan"
                    class="shrink-0 rounded-lg px-3.5 py-1.5 text-sm font-semibold text-light transition-all duration-300 hover:bg-panel hover:text-sky sm:text-[15px]">
                    Bantuan
                </a>
            </div>
        </nav>
    </header>

    <main>
        @yield('content')
    </main>

    <footer class="mt-20 bg-navy sm:mt-24 lg:mt-28">
        <div id="tentang" style="scroll-margin-top: 120px;" class="scroll-mt-32 mx-auto max-w-[1266px] px-4 py-14 sm:px-8 sm:py-16 lg:py-20">

            <!-- FRAME 1: ABOUT US & MORIARTY (Grid 2 Kolom) -->
            <div class="grid gap-12 lg:grid-cols-2 lg:gap-24">

                <!-- Kolom Kiri: About -->
                <div class="max-w-[604px]">
                    <h2 class="text-lg font-semibold text-white tracking-wide">
                        Tentang Kita?
                    </h2>
                    <p class="mt-6 text-sm leading-[1.7] text-slate-300 sm:mt-8 sm:text-lg lg:text-sm">
                        Moriarty adalah marketplace game yang bikin jual beli akun game jadi simpel, aman, dan transparan. Kami menghubungkan para gamer dan penjual lewat platform praktis yang dirancang khusus demi pengalaman yang lancar dan terpercaya.
                    </p>
                </div>

                <!-- Kolom Kanan: Logo & Tagline -->
                <div class="lg:pl-10">
                    <div class="flex items-center gap-3">
                        <img src="{{ asset('') }}assets/img/logo.png" alt="Moriarty"
                            class="h-11 w-11 object-contain sm:h-[48px] sm:w-[48px]">

                        <span class="text-xl font-bold text-light tracking-tight">
                            MORIARTY
                        </span>
                    </div>

                    <p class="mt-2 max-w-[380px] text-lg font-semibold leading-7 text-slate-300 sm:text-lg">
                        Bergabunglah bersama kami hari ini untuk meningkatkan pengalaman gaming Anda!
                    </p>
                </div>

            </div>

            <!-- Tambahkan style="margin-top: 80px;" agar jarak atasnya mutlak ada -->
            <div id="bantuan" style="margin-top: 80px;" class="pt-10 pb-10 scroll-mt-28">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-8 lg:gap-12">

                    <!-- TEKS BANTUAN -->
                    <div class="w-full sm:w-2/3 lg:w-1/2">
                        <h3 class="text-lg font-bold text-light sm:text-xl">
                            Butuh Bantuan? Hubungi Kami
                        </h3>
                        <p class="mt-4 text-sm leading-[1.8] text-slate-300 sm:text-base">
                            Tim dukungan kami berdedikasi untuk memberikan pengalaman transaksi yang aman dan nyaman.
                            Jika Anda memiliki pertanyaan seputar pembelian, keluhan pesanan, atau menemukan kendala teknis lainnya,
                            jangan ragu untuk segera menghubungi kami. Kami siap membantu Anda 24/7 melalui saluran resmi di samping.
                        </p>
                    </div>

                    <!-- SOSIAL MEDIA -->
                    <div class="flex items-center gap-3 text-white shrink-0">
                        <!-- Discord -->
                        <a href="https://discord.com/users/563682893067583489" target="_blank" aria-label="Discord"
                            class="flex h-11 w-11 items-center justify-center rounded-xl bg-panel text-white transition-all duration-300 hover:bg-sky/20 hover:text-sky shadow-sm">
                            <svg viewBox="0 0 24 24" fill="currentColor" class="h-5 w-5">
                                <path d="M19.6 5.3A16.7 16.7 0 0 0 15.5 4l-.3.5c1.4.4 2.6 1 3.7 1.7a13 13 0 0 0-11-.5l-.7.5c1-.7 2.3-1.3 3.7-1.7L10.6 4C9.1 4.2 7.7 4.7 6.4 5.3 3.6 9.5 2.9 13.6 3.2 17.6a16.9 16.9 0 0 0 5.2 2.6l1.1-1.8c-.6-.2-1.2-.5-1.8-.9l.4-.3a12 12 0 0 0 10 0l.4.3c-.6.4-1.2.7-1.8.9l1.1 1.8c1.9-.6 3.7-1.5 5.2-2.6.4-4.6-.7-8.7-3.4-12.3zM9.3 15.2c-1 0-1.9-.9-1.9-2.1s.8-2.1 1.9-2.1 1.9 1 1.9 2.1-.9 2.1-1.9 2.1zm5.4 0c-1 0-1.9-.9-1.9-2.1s.8-2.1 1.9-2.1 1.9 1 1.9 2.1-.9 2.1-1.9 2.1z" />
                            </svg>
                        </a>

                        <!-- Telegram -->
                        <a href="https://wa.me/6285642913447" target="_blank" aria-label="Telegram"
                            class="flex h-11 w-11 items-center justify-center rounded-xl bg-panel text-white transition-all duration-300 hover:bg-sky/20 hover:text-sky shadow-sm">
                            <svg viewBox="0 0 24 24" fill="currentColor" class="h-5 w-5">
                                <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z" />
                            </svg>
                        </a>

                        <!-- Email -->
                        <a href="mailto:gozilagaming2@gmail" aria-label="Email"
                            class="flex h-11 w-11 items-center justify-center rounded-xl bg-panel text-white transition-all duration-300 hover:bg-sky/20 hover:text-sky shadow-sm">
                            <svg viewBox="0 0 24 24" fill="currentColor" class="h-5 w-5">
                                <path d="M2 6a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2l-10 6L2 6zm0 2.4V18a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V8.4l-10 6-10-6z" />
                            </svg>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- FRAME 3: COPYRIGHT FOOTER (Tetap sama) -->
        <div class="bg-strip px-4 py-3 text-center">
            <p class="text-xs text-muted sm:text-sm">
                &copy; 2026. The moriarty.gg website is operated by MRT Processing Imaginary.
            </p>
        </div>
    </footer>


    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Kita hanya butuh 3 elemen utama sekarang
            const searchModal = document.getElementById('search-modal');
            const searchInput = document.getElementById('modal-search-input');
            const resultsList = document.getElementById('search-results-list');
            let typingTimer;

            // Fungsi Buka Dropdown
            const openDropdown = () => {
                searchModal.classList.remove('hidden');
            };

            // Fungsi Tutup Dropdown
            const closeDropdown = () => {
                searchModal.classList.add('hidden');
            };

            // 1. Munculkan Dropdown saat input diklik atau saat mulai mengetik
            searchInput.addEventListener('focus', openDropdown);
            searchInput.addEventListener('click', openDropdown);

            // 2. Tutup Dropdown jika klik sembarang di luar area pencarian
            document.addEventListener('click', (e) => {
                if (!searchInput.contains(e.target) && !searchModal.contains(e.target)) {
                    closeDropdown();
                }
            });

            // 3. Tutup pakai tombol ESC di keyboard
            document.addEventListener('keydown', (e) => {
                if (e.key === 'Escape' && !searchModal.classList.contains('hidden')) {
                    closeDropdown();
                    searchInput.blur(); // Hilangkan kursor dari input
                }
            });

            // 4. Proses Live Search saat Mengetik
            searchInput.addEventListener('input', function() {
                openDropdown(); // Pastikan pop-up terbuka saat user mengetik

                clearTimeout(typingTimer);
                const keyword = this.value.trim();

                if (keyword.length >= 2) {
                    resultsList.innerHTML = `<div class="px-4 py-4 text-sm text-muted sm:px-6">Mencari...</div>`;

                    typingTimer = setTimeout(() => {
                        fetchLiveSellers(keyword);
                    }, 300);
                } else {
                    resultsList.innerHTML = `<div class="px-4 py-8 text-center text-sm text-muted sm:px-6">Ketik nama penjual untuk memulai pencarian...</div>`;
                }
            });

            // 5. Fetch Data ke API Backend
            function fetchLiveSellers(keyword) {
                fetch(`/api/cari-seller-live?keyword=${encodeURIComponent(keyword)}`)
                    .then(response => response.json())
                    .then(data => {
                        resultsList.innerHTML = '';

                        if (data.length > 0) {
                            data.forEach(seller => {
                                const profileUrl = `/seller/${seller.hash_id}`;

                                // Visual baris hasil pencarian
                                const itemHtml = `
                            <a href="${profileUrl}" class="z-[9999] flex items-center gap-4 px-4 py-3 transition-colors hover:bg-panel sm:px-6">
                                <div class="flex h-10 w-10 shrink-0 items-center justify-center text-light">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" class="h-6 w-6">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 21v-7.5a.75.75 0 0 1 .75-.75h3a.75.75 0 0 1 .75.75V21m-4.5 0H2.36m11.14 0H18m0 0h3.64m-1.39 0V9.349M3.75 21V9.349m0 0a3.001 3.001 0 0 0 3.75-.615A2.999 2.999 0 0 0 9.75 9.75c.896 0 1.7-.393 2.25-1.016a2.999 2.999 0 0 0 2.25 1.016c.896 0 1.7-.393 2.25-1.015a3.001 3.001 0 0 0 3.75.614m-16.5 0a3.004 3.004 0 0 1-.621-4.72l1.189-1.19A1.5 1.5 0 0 1 5.378 3h13.243a1.5 1.5 0 0 1 1.06.44l1.19 1.189a3 3 0 0 1-.621 4.72M6.75 18h3.75a.75.75 0 0 0 .75-.75V13.5a.75.75 0 0 0-.75-.75H6.75a.75.75 0 0 0-.75.75v3.75c0 .414.336.75.75.75Z" />
                                    </svg>
                                </div>
                                <div class="flex flex-col">
                                    <span class="text-sm font-semibold text-light">${seller.username}</span>
                                    <span class="text-[11px] text-brand">di toko Moriarty</span>
                                </div>
                            </a>
                            `;
                                resultsList.insertAdjacentHTML('beforeend', itemHtml);
                            });
                        } else {
                            resultsList.innerHTML = `
                        <div class="z-[9999] px-4 py-8 text-center text-sm text-muted sm:px-6">
                            Tidak ada penjual bernama "${keyword}"
                        </div>
                        `;
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        resultsList.innerHTML = `<div class="px-4 py-4 text-sm text-bad sm:px-6">Terjadi kesalahan teknis.</div>`;
                    });
            }
        });
    </script>
</body>

</html>