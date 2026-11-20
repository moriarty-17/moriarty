@extends('template.frontend')
@section('content')
<div class="mx-auto w-full max-w-[1440px] px-4 py-10 sm:px-8 sm:py-14 lg:py-20">
    <div
        class="mx-auto w-full max-w-[512px] rounded-[14px] border border-line bg-ink p-5 sm:p-7 lg:rounded-[16px] lg:border-4 lg:p-10">
        <h1 class="text-center text-xl font-semibold text-light sm:text-2xl">
            Daftar
        </h1>

        <div class="mt-6 flex justify-center sm:mt-7">
            <div
                class="mb-6 grid w-full max-w-[260px] grid-cols-2 gap-1 rounded-[8px] border border-line bg-panel p-1 sm:mb-8 sm:max-w-[280px]">
                <a href="?page=general"
                    class="{{ !request()->query('page') || request()->query('page') === 'general'
                            ? 'border-brand bg-brand/10 text-brand shadow-[0_0_0_1px_rgba(23,105,232,0.10)]'
                            : 'border-transparent text-muted hover:border-sky/40 hover:bg-card hover:text-light' }}
                        flex h-10 items-center justify-center rounded-[6px] border-2 px-3 text-sm font-semibold transition-all duration-300 sm:h-11 sm:text-[15px]">
                    Sebagai Pembeli
                </a>

                <a href="?page=seller"
                    class="{{ request()->query('page') === 'seller'
                            ? 'border-brand bg-brand/10 text-brand shadow-[0_0_0_1px_rgba(23,105,232,0.10)]'
                            : 'border-transparent text-muted hover:border-sky/40 hover:bg-card hover:text-light' }}
                        flex h-10 items-center justify-center rounded-[6px] border-2 px-3 text-sm font-semibold transition-all duration-300 sm:h-11 sm:text-[15px]">
                    Sebagai Penjual
                </a>
            </div>
        </div>

        @if (!request()->query('page') || request()->query('page') === 'general')
        <form action="{{ url('/simpan_pengguna') }}" method="POST">
            @csrf

            <div class="mt-5 sm:mt-6">
                <label class="mb-2 block text-sm font-semibold text-light sm:text-[15px]" for="username_seller">
                    Username
                </label>

                <input id="username_seller" type="text" name="username" placeholder="Masukkan Username..."
                    class="w-full rounded-[8px] border border-line bg-panel px-3.5 py-3 text-sm font-medium text-light placeholder:text-muted transition-all duration-300 ease-out hover:border-sky/60 hover:bg-card hover:shadow-[0_8px_24px_rgba(56,189,248,0.10)] active:border-sky active:shadow-[0_0_0_3px_rgba(56,189,248,0.10)] focus:border-sky focus:bg-panel focus:outline-none focus:ring-0 focus:shadow-[0_0_0_3px_rgba(56,189,248,0.16),0_8px_28px_rgba(56,189,248,0.12)] sm:px-[13px] sm:py-[11px] sm:text-[15px]">
            </div>

            <div class="mt-5 sm:mt-6">
                <label class="mb-2 block text-sm font-semibold text-light sm:text-[15px]" for="email_seller">
                    Email
                </label>

                <input id="email_seller" type="email" name="email" placeholder="Masukkan Email..."
                    class="w-full rounded-[8px] border border-line bg-panel px-3.5 py-3 text-sm font-medium text-light placeholder:text-muted transition-all duration-300 ease-out hover:border-sky/60 hover:bg-card hover:shadow-[0_8px_24px_rgba(56,189,248,0.10)] active:border-sky active:shadow-[0_0_0_3px_rgba(56,189,248,0.10)] focus:border-sky focus:bg-panel focus:outline-none focus:ring-0 focus:shadow-[0_0_0_3px_rgba(56,189,248,0.16),0_8px_28px_rgba(56,189,248,0.12)] sm:px-[13px] sm:py-[11px] sm:text-[15px]">
            </div>

            <div class="mt-5 sm:mt-6">
                <label class="mb-2 block text-sm font-semibold text-light sm:text-[15px]" for="password_seller">
                    Password
                </label>

                <input id="password_seller" type="password" name="password" placeholder="Masukkan Password..."
                    class="w-full rounded-[8px] border border-line bg-panel px-3.5 py-3 text-sm font-medium text-light placeholder:text-muted transition-all duration-300 ease-out hover:border-sky/60 hover:bg-card hover:shadow-[0_8px_24px_rgba(56,189,248,0.10)] active:border-sky active:shadow-[0_0_0_3px_rgba(56,189,248,0.10)] focus:border-sky focus:bg-panel focus:outline-none focus:ring-0 focus:shadow-[0_0_0_3px_rgba(56,189,248,0.16),0_8px_28px_rgba(56,189,248,0.12)] sm:px-[13px] sm:py-[11px] sm:text-[15px]">

                <input type="text" name="role" value="buyer" hidden>
            </div>

            <button type="submit"
                class="cursor-pointer btn-animasi mt-7 inline-flex min-h-12 w-full items-center justify-center rounded-[10px] bg-brand px-6 py-3 text-base font-semibold text-white shadow-lg shadow-brand/10 transition-all duration-300 ease-out hover:-translate-y-0.5 hover:bg-brand/90 hover:shadow-lg hover:shadow-brand/25 focus:outline-none focus-visible:ring-2 focus-visible:ring-sky/60 active:translate-y-0 sm:mt-8 sm:text-lg">
                Daftar
            </button>
        </form>
        @elseif(request()->query('page') === 'seller')
        <form action="{{ url('/simpan_pengguna') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="mt-5 sm:mt-6">
                <label class="mb-2 block text-sm font-semibold text-light sm:text-[15px]" for="username_general">
                    Username
                </label>

                <input id="username_general" type="text" name="username" placeholder="Masukkan Username..."
                    class="w-full rounded-[8px] border border-line bg-panel px-3.5 py-3 text-sm font-medium text-light placeholder:text-muted transition-all duration-300 ease-out hover:border-sky/60 hover:bg-card hover:shadow-[0_8px_24px_rgba(56,189,248,0.10)] active:border-sky active:shadow-[0_0_0_3px_rgba(56,189,248,0.10)] focus:border-sky focus:bg-panel focus:outline-none focus:ring-0 focus:shadow-[0_0_0_3px_rgba(56,189,248,0.16),0_8px_28px_rgba(56,189,248,0.12)] sm:px-[13px] sm:py-[11px] sm:text-[15px]">
            </div>

            <div class="mt-5 sm:mt-6">
                <label class="mb-2 block text-sm font-semibold text-light sm:text-[15px]" for="email_general">
                    Email
                </label>

                <input id="email_general" type="email" name="email" placeholder="Masukkan Email..."
                    class="w-full rounded-[8px] border border-line bg-panel px-3.5 py-3 text-sm font-medium text-light placeholder:text-muted transition-all duration-300 ease-out hover:border-sky/60 hover:bg-card hover:shadow-[0_8px_24px_rgba(56,189,248,0.10)] active:border-sky active:shadow-[0_0_0_3px_rgba(56,189,248,0.10)] focus:border-sky focus:bg-panel focus:outline-none focus:ring-0 focus:shadow-[0_0_0_3px_rgba(56,189,248,0.16),0_8px_28px_rgba(56,189,248,0.12)] sm:px-[13px] sm:py-[11px] sm:text-[15px]">
            </div>

            <div class="mt-5 sm:mt-6">
                <label class="mb-2 block text-sm font-semibold text-light sm:text-[15px]" for="password_general">
                    Password
                </label>

                <input id="password_general" type="password" name="password" placeholder="Masukkan Password..."
                    class="w-full rounded-[8px] border border-line bg-panel px-3.5 py-3 text-sm font-medium text-light placeholder:text-muted transition-all duration-300 ease-out hover:border-sky/60 hover:bg-card hover:shadow-[0_8px_24px_rgba(56,189,248,0.10)] active:border-sky active:shadow-[0_0_0_3px_rgba(56,189,248,0.10)] focus:border-sky focus:bg-panel focus:outline-none focus:ring-0 focus:shadow-[0_0_0_3px_rgba(56,189,248,0.16),0_8px_28px_rgba(56,189,248,0.12)] sm:px-[13px] sm:py-[11px] sm:text-[15px]">
            </div>

            <div class="mt-5 sm:mt-6">
                <span class="mb-2 block text-sm font-semibold text-light sm:text-[15px]">
                    Upload Gambar KTP
                </span>

                {{-- Pancingan agar Tailwind me-render class h-[150px] yang dipanggil oleh JS --}}
                <div class="hidden h-[150px]"></div>

                <label
                    class="group relative flex min-h-[145px] w-full cursor-pointer flex-col items-center justify-center overflow-hidden rounded-[10px] border border-line bg-panel p-2.5 text-center transition-all duration-300 ease-out hover:border-sky/60 hover:bg-card hover:shadow-[0_8px_24px_rgba(56,189,248,0.10)] focus-within:border-sky focus-within:bg-panel focus-within:shadow-[0_0_0_3px_rgba(56,189,248,0.16),0_8px_28px_rgba(56,189,248,0.12)] sm:min-h-[157px]">

                    {{-- Preview Gambar (absolute dihapus agar gambar tidak gepeng dan mengikuti kotak label) --}}
                    <div id="ktp_preview" class="hidden w-full"></div>

                    {{-- Dibungkus dengan ID upload-placeholder --}}
                    <div id="upload-placeholder" class="flex flex-col items-center justify-center gap-3 py-3">
                        <span
                            class="flex h-8 w-8 items-center justify-center text-muted transition-colors duration-300 group-hover:text-sky group-focus-within:text-sky">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round" class="h-8 w-8">
                                <path d="M12 16V4" />
                                <path d="m7 9 5-5 5 5" />
                                <path d="M4 16v2a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2v-2" />
                            </svg>
                        </span>
                        <span class="text-xs font-medium leading-5 text-muted sm:text-[15px]">
                            Format JPG, PNG Maks 2mb
                        </span>
                    </div>

                    <input type="file" id="ktp_image" name="identitas" accept="image/png,image/jpeg"
                        class="sr-only">

                    <input type="text" name="role" value="seller" hidden>
                </label>
            </div>

            <button type="submit"
                class="cursor-pointer btn-animasi mt-7 inline-flex min-h-12 w-full items-center justify-center rounded-[10px] bg-brand px-6 py-3 text-base font-semibold text-white shadow-lg sm:mt-8 sm:text-lg">
                Daftar
            </button>
        </form>
        @endif

        <p class="mt-5 text-center text-sm font-medium leading-6 text-muted sm:mt-6 sm:text-[15px]">
            Sudah punya akun?
            <a href="{{ url('/sign-in') }}"
                class="text-sky transition-colors duration-200 hover:text-light hover:underline">
                Masuk
            </a>
        </p>
    </div>
</div>
@endsection