@extends('template.frontend')
@section('content')
<div class="mx-auto w-full max-w-[1440px] px-4 py-6 sm:px-6 sm:py-8 lg:px-8 lg:py-12">
    <div class="mx-auto w-full max-w-[983px] overflow-hidden rounded-2xl border border-line bg-navy shadow-2xl shadow-ink/30">
        <header class="relative border-b border-line bg-card">
            <div class="absolute inset-x-0 top-0 h-px bg-brand/70"></div>

            <div class="px-5 py-7 sm:px-8 sm:py-8 lg:px-10 lg:py-9">
                <div class="flex items-center gap-4">
                    <div class="h-10 w-1 shrink-0 rounded-full bg-brand shadow-[0_0_18px_rgba(23,105,232,0.20)]"></div>
                    <div>
                        <h1 class="text-2xl font-semibold tracking-tight text-light sm:text-[28px]">
                            Pengaturan Akun
                        </h1>
                        <p class="mt-2 text-sm leading-6 text-muted">
                            Kelola informasi login dan gambar profil Anda.
                        </p>
                    </div>
                </div>
            </div>
        </header>

        <form action="{{ url('/profil/update') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            {{-- Alert Pesan Sesuai Setup CSS Global --}}
            @if(session('success'))
            <div class="mx-5 mt-6 flex items-center gap-3 px-4 py-3.5 shadow-lg sm:mx-8 lg:mx-10"
                style="background-color: color-mix(in srgb, var(--color-ok) 12%, transparent); border: 1px solid color-mix(in srgb, var(--color-ok) 35%, transparent);">
                <svg class="h-5 w-5 shrink-0" style="color: var(--color-ok);" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                    <polyline points="22 4 12 14.01 9 11.01"></polyline>
                </svg>
                <p class="text-sm font-medium" style="color: var(--color-ok);">{{ session('success') }}</p>
            </div>
            @endif

            @if(session('error'))
            <div class="mx-5 mt-6 flex items-center gap-3 rounded-xl px-4 py-3.5 shadow-lg sm:mx-8 lg:mx-10"
                style="background-color: color-mix(in srgb, var(--color-bad) 12%, transparent); border: 1px solid color-mix(in srgb, var(--color-bad) 35%, transparent);">
                <svg class="h-5 w-5 shrink-0" style="color: var(--color-bad);" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="10"></circle>
                    <line x1="12" y1="8" x2="12" y2="12"></line>
                    <line x1="12" y1="16" x2="12.01" y2="16"></line>
                </svg>
                <p class="text-sm font-medium" style="color: var(--color-bad);">{{ session('error') }}</p>
            </div>
            @endif

            @if ($errors->any())
            <div class="mx-5 mt-6 flex items-start gap-3 rounded-xl px-4 py-3.5 shadow-lg sm:mx-8 lg:mx-10"
                style="background-color: color-mix(in srgb, var(--color-bad) 12%, transparent); border: 1px solid color-mix(in srgb, var(--color-bad) 35%, transparent);">
                <svg class="h-5 w-5 shrink-0 mt-0.5" style="color: var(--color-bad);" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"></path>
                    <line x1="12" y1="9" x2="12" y2="13"></line>
                    <line x1="12" y1="17" x2="12.01" y2="17"></line>
                </svg>
                <div>
                    <p class="text-sm font-semibold mb-1" style="color: var(--color-bad);">Terjadi beberapa kesalahan:</p>
                    <ul class="list-inside list-disc text-sm space-y-0.5" style="color: var(--color-bad);">
                        @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
            @endif

            <div class="px-5 py-7 sm:px-8 sm:py-9 lg:px-10 lg:py-10">

                {{-- Bagian Gambar Profil --}}
                <div class="mb-10 flex flex-col items-center justify-center text-center">
                    <div class="relative mb-3">
                        @php
                        $user = auth()->user();
                        $avatar = ($user->gambar && file_exists(public_path('assets/resource/profil/' . $user->gambar)))
                        ? asset('assets/resource/profil/' . $user->gambar)
                        : asset('assets/img/default-avatar.png');
                        @endphp

                        {{-- Container diunci ukurannya (h-24 w-24) dan aspect-square --}}
                        <div class="h-20 w-20 aspect-square overflow-hidden rounded-full border-2 border-line bg-panel shadow-md">
                            {{-- Tag img dipaksa memenuhi container dengan object-cover agar tidak melar --}}
                            <img src="{{ $avatar }}" id="preview_avatar" alt="Gambar Profil" class="h-full w-full object-cover">
                        </div>

                        <label for="foto_profil" class="absolute bottom-0 right-0 flex h-7 w-7 cursor-pointer items-center justify-center rounded-full bg-brand text-white shadow-md transition-transform hover:scale-110" title="Ganti Foto">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-3.5 w-3.5">
                                <path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z"></path>
                                <circle cx="12" cy="13" r="4"></circle>
                            </svg>
                            <input type="file" id="foto_profil" name="gambar" accept="image/*" class="sr-only cursor-pointer" onchange="previewImage(event)">
                        </label>
                    </div>

                    <h3 class="text-base font-semibold text-light">{{ $user->username ?? 'Username' }}</h3>
                    <p class="mt-0.5 text-xs text-muted">Format: JPG, PNG. Maksimal: 2MB.</p>
                </div>

                {{-- Form Baris --}}
                <div class="space-y-8">

                    {{-- Email --}}
                    <div class="grid grid-cols-1 items-start gap-2 sm:grid-cols-12 sm:gap-4">
                        <label class="text-sm font-medium text-light sm:col-span-3 sm:pt-3">
                            Email:
                        </label>
                        <div class="sm:col-span-9">
                            <div class="flex items-center gap-3">
                                <input name="email" id="input_email" type="email" value="{{ old('email', $user->email ?? '') }}" disabled
                                    class="min-h-11 w-full rounded-lg border border-line bg-panel/40 px-3.5 py-2.5 text-sm font-medium text-muted outline-none ring-0 transition-all disabled:cursor-not-allowed">
                                <button type="button" onclick="unlockField('input_email', this)"
                                    class="shrink-0 cursor-pointer text-xs font-bold text-sky transition-colors hover:underline px-2">
                                    EDIT
                                </button>
                            </div>
                            <p class="mt-1.5 text-xs text-ok flex items-center gap-1">
                                <span class="inline-block h-1.5 w-1.5 rounded-full bg-ok"></span> Terverifikasi Email ini ditautkan ke akun Anda.
                            </p>
                        </div>
                    </div>

                    <div class="border-t border-line"></div>

                    {{-- Nama Pengguna (Username) --}}
                    <div class="grid grid-cols-1 items-start gap-2 sm:grid-cols-12 sm:gap-4">
                        <label class="text-sm font-medium text-light sm:col-span-3 sm:pt-3">
                            Nama pengguna:
                        </label>
                        <div class="sm:col-span-9">
                            <div class="flex items-center gap-3">
                                <input name="username" id="input_username" type="text" value="{{ old('username', $user->username ?? '') }}" disabled
                                    class="min-h-11 w-full rounded-lg border border-line bg-panel/40 px-3.5 py-2.5 text-sm font-medium text-muted outline-none ring-0 transition-all disabled:cursor-not-allowed">
                                <button type="button" onclick="unlockField('input_username', this)"
                                    class="shrink-0 cursor-pointer text-xs font-bold text-sky transition-colors hover:underline px-2">
                                    EDIT
                                </button>
                            </div>
                            <p class="mt-1.5 text-xs text-muted">
                                Nama yang dapat dilihat oleh pengguna lainnya. Anda dapat mengubah nama pengguna setiap 90 hari sekali.
                            </p>
                        </div>
                    </div>

                    <div class="border-t border-line"></div>

                    {{-- Kata Sandi (Password) --}}
                    <div class="grid grid-cols-1 items-start gap-2 sm:grid-cols-12 sm:gap-4">
                        <label class="text-sm font-medium text-light sm:col-span-3 sm:pt-3">
                            Kata sandi:
                        </label>
                        <div class="sm:col-span-9">
                            <div id="password_container" class="hidden space-y-3 mb-3">
                                <input name="password" type="password" placeholder="Masukkan kata sandi baru..."
                                    class="min-h-11 w-full rounded-lg border border-line bg-panel px-3.5 py-2.5 text-sm font-medium text-light outline-none ring-0 focus:border-sky">
                            </div>

                            <button type="button" id="btn_ubah_sandi" onclick="togglePasswordInput()"
                                class="inline-flex cursor-pointer items-center justify-center rounded-lg bg-brand px-4 py-2.5 text-xs font-bold text-white shadow-md transition-all duration-300 hover:bg-brand/90">
                                Ubah kata sandi
                            </button>

                            <p class="mt-2 text-xs text-muted">
                                Kata sandi hanya dapat diubah jika Anda menggunakan alur login email/kata sandi.
                            </p>
                        </div>
                    </div>

                    {{-- ============================================================== --}}
                    {{-- FITUR BARU: TRANSFORMASI SELLER (Hanya Muncul Jika Role = Pembeli) --}}
                    {{-- ============================================================== --}}
                    @if(strtolower($user->role) === 'buyer')
                    <div class="border-t border-line"></div>

                    <div class="grid grid-cols-1 items-start gap-2 sm:grid-cols-12 sm:gap-4">
                        <div class="sm:col-span-9">
                            <div id="seller_container" class="hidden space-y-3 mb-3">
                                <div class="rounded-lg border border-dashed border-line bg-panel/30 p-4">
                                    <label for="input_identitas" class="mb-2 block cursor-pointer text-xs font-semibold text-light">Unggah Gambar Identitas (KTP/ID)</label>
                                    <input name="identitas" id="input_identitas" type="file" accept="image/*" onchange="previewIdentitas(event)"
                                        class="block w-full cursor-pointer text-xs text-muted file:mr-4 file:cursor-pointer file:rounded-md file:border-0 file:bg-sky file:px-4 file:py-2 file:text-xs file:font-semibold file:text-white transition-all hover:file:bg-sky/80 focus:outline-none">
                                    <p class="mt-2 text-[11px] text-muted">Pastikan identitas terlihat jelas untuk mempercepat proses verifikasi.</p>

                                    <div id="preview_identitas_container" class="hidden mt-4">
                                        <p class="mb-2 text-xs font-medium text-light">Pratinjau Identitas:</p>

                                        {{-- Pembungkus utama dipaksa ukurannya via inline CSS agar kebal dari CSS global --}}
                                        <div class="relative overflow-hidden rounded-lg border border-line bg-panel/50 shadow-sm"
                                            style="width: 240px !important; height: 150px !important; max-width: 100%;">
                                            {{-- Gambar menggunakan absolute agar patuh 100% pada ukuran pembungkusnya --}}
                                            <img id="preview_identitas" src="" alt="Pratinjau Identitas" class="absolute inset-0 h-full w-full object-cover">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <button type="button" id="btn_upgrade_seller" onclick="toggleSellerInput()"
                                class="inline-flex cursor-pointer items-center justify-center rounded-lg px-4 py-2.5 text-xs font-bold shadow-sm transition-all duration-300 hover:scale-[1.02] active:scale-95"
                                style="background-color: color-mix(in srgb, var(--color-ok) 15%, transparent); color: var(--color-ok); border: 1px solid color-mix(in srgb, var(--color-ok) 35%, transparent);">
                                Upgrade Jadi Penjual
                            </button>

                            <p class="mt-2 text-xs text-muted">
                                Saat ini Anda terdaftar sebagai <strong>Pembeli</strong>. Unggah identitas Anda untuk mulai berjualan.
                            </p>
                        </div>
                    </div>
                    @endif
                    {{-- ============================================================== --}}

                </div>
            </div>

            <div class="border-t border-line bg-card px-5 py-5 sm:px-8 sm:py-6 lg:px-10">
                <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                    <p class="text-xs leading-5 text-muted">
                        Pastikan data akun Anda sudah benar sebelum menyimpan perubahan.
                    </p>

                    <button type="submit"
                        class="inline-flex cursor-pointer min-h-11 w-full shrink-0 items-center justify-center rounded-lg bg-brand px-7 py-2.5 text-sm font-semibold text-white shadow-lg shadow-brand/10 transition-all duration-300 hover:bg-brand/90 hover:shadow-brand/20 active:scale-95 sm:w-auto">
                        Simpan Perubahan
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
    function previewImage(event) {
        const reader = new FileReader();
        reader.onload = function() {
            const output = document.getElementById('preview_avatar');
            output.src = reader.result;
            // Memastikan class CSS tetap terjaga saat gambar baru dimuat
            output.className = "h-20 w-20 object-cover";
        }
        if (event.target.files[0]) {
            reader.readAsDataURL(event.target.files[0]);
        }
    }

    // FUNGSI PREVIEW IDENTITAS
    function previewIdentitas(event) {
        const container = document.getElementById('preview_identitas_container');
        const output = document.getElementById('preview_identitas');
        const file = event.target.files[0];

        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                output.src = e.target.result;
                container.classList.remove('hidden');
            }
            reader.readAsDataURL(file);
        } else {
            output.src = '';
            container.classList.add('hidden');
        }
    }

    function unlockField(fieldId, btnElement) {
        const input = document.getElementById(fieldId);
        if (input.disabled) {
            input.disabled = false;
            input.classList.remove('bg-panel/40', 'text-muted');
            input.classList.add('bg-panel', 'text-light', 'border-sky');
            input.focus();
            btnElement.textContent = 'BATAL';
        } else {
            input.disabled = true;
            input.classList.add('bg-panel/40', 'text-muted');
            input.classList.remove('bg-panel', 'text-light', 'border-sky');
            btnElement.textContent = 'EDIT';
        }
    }

    function togglePasswordInput() {
        const container = document.getElementById('password_container');
        const btn = document.getElementById('btn_ubah_sandi');
        if (container.classList.contains('hidden')) {
            container.classList.remove('hidden');
            btn.textContent = 'Tutup Ubah Sandi';
            btn.classList.replace('bg-brand', 'bg-bad');
        } else {
            container.classList.add('hidden');
            btn.textContent = 'Ubah kata sandi';
            btn.classList.replace('bg-bad', 'bg-brand');
        }
    }

    // SCRIPT FUNGSI TOGGLE UNTUK FITUR SELLER
    function toggleSellerInput() {
        const container = document.getElementById('seller_container');
        const btn = document.getElementById('btn_upgrade_seller');

        if (container.classList.contains('hidden')) {
            container.classList.remove('hidden');
            btn.textContent = 'Batal Upgrade';

            // Ubah gaya tombol menjadi merah (batal) menggunakan color-mix
            btn.style.backgroundColor = 'color-mix(in srgb, var(--color-bad) 15%, transparent)';
            btn.style.color = 'var(--color-bad)';
            btn.style.borderColor = 'color-mix(in srgb, var(--color-bad) 35%, transparent)';
        } else {
            container.classList.add('hidden');
            btn.textContent = 'Upgrade Jadi Penjual';

            // Kembalikan gaya tombol menjadi hijau menggunakan color-mix
            btn.style.backgroundColor = 'color-mix(in srgb, var(--color-ok) 15%, transparent)';
            btn.style.color = 'var(--color-ok)';
            btn.style.borderColor = 'color-mix(in srgb, var(--color-ok) 35%, transparent)';

            // Reset input file dan hilangkan pratinjau jika batal
            const fileInput = container.querySelector('input[type="file"]');
            if (fileInput) {
                fileInput.value = '';
                document.getElementById('preview_identitas_container').classList.add('hidden');
                document.getElementById('preview_identitas').src = '';
            }
        }
    }
</script>
@endsection