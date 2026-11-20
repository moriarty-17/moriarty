@extends('template.frontend')
@section('content') 
<div class="mx-auto w-full max-w-[1440px] px-4 py-10 sm:px-8 sm:py-14 lg:py-20"> <div class="mx-auto w-full max-w-[512px] rounded-[14px] border border-line bg-ink p-5 sm:p-7 lg:rounded-[16px] lg:border-4 lg:p-10">

        <h1 class="text-center text-xl font-semibold text-light sm:text-2xl">
            Masuk
        </h1>

        <form class="mt-6 sm:mt-7" action="{{ route('login') }}" method="POST">
            @csrf

            <div class="mt-5 sm:mt-6">
                <label
                    class="mb-2 block text-sm font-semibold text-light sm:text-[15px]"
                    for="login"
                >
                    Email/Username
                </label>

                <input
                    id="login"
                    name="login"
                    type="text"
                    value="{{ old('login') }}"
                    placeholder="Masukkan Email atau Username..."
                    class="w-full rounded-[8px] border border-line bg-panel px-3.5 py-3 text-sm font-medium text-light placeholder:text-muted transition-all duration-300 ease-out hover:border-sky/60 hover:bg-card hover:shadow-[0_8px_24px_rgba(56,189,248,0.10)] active:border-sky active:shadow-[0_0_0_3px_rgba(56,189,248,0.10)] focus:border-sky focus:bg-panel focus:outline-none focus:ring-0 focus:shadow-[0_0_0_3px_rgba(56,189,248,0.16),0_8px_28px_rgba(56,189,248,0.12)] sm:px-[13px] sm:py-[11px] sm:text-[15px]"
                    required
                >
            </div>

            <div class="mt-5 sm:mt-6">
                <label
                    class="mb-2 block text-sm font-semibold text-light sm:text-[15px]"
                    for="password"
                >
                    Password
                </label>

                <input
                    id="password"
                    name="password"
                    type="password"
                    placeholder="Masukkan Password..."
                    class="w-full rounded-[8px] border border-line bg-panel px-3.5 py-3 text-sm font-medium text-light placeholder:text-muted transition-all duration-300 ease-out hover:border-sky/60 hover:bg-card hover:shadow-[0_8px_24px_rgba(56,189,248,0.10)] active:border-sky active:shadow-[0_0_0_3px_rgba(56,189,248,0.10)] focus:border-sky focus:bg-panel focus:outline-none focus:ring-0 focus:shadow-[0_0_0_3px_rgba(56,189,248,0.16),0_8px_28px_rgba(56,189,248,0.12)] sm:px-[13px] sm:py-[11px] sm:text-[15px]"
                    required
                >
            </div>

            @error('login')
                <p class="mt-3 text-sm leading-5 text-red-400">
                    {{ $message }}
                </p>
            @enderror

            <button
                type="submit"
                class="btn-animasi mt-7 inline-flex min-h-12 w-full items-center justify-center rounded-[10px] bg-brand px-6 py-3 text-base font-semibold text-white shadow-lg shadow-brand/10 transition-all duration-300 ease-out hover:-translate-y-0.5 hover:bg-brand/90 hover:shadow-lg hover:shadow-brand/25 focus:outline-none focus-visible:ring-2 focus-visible:ring-sky/60 active:translate-y-0 sm:mt-8 sm:text-lg"
            >
                Masuk
            </button>
        </form>

        <p class="mt-5 text-center text-sm font-medium leading-6 text-muted sm:mt-6 sm:text-[15px]">
            Tidak punya akun?
            <a
                href="{{ url('/sign-up') }}"
                class="text-sky transition-colors duration-200 hover:text-light hover:underline"
            >
                Daftar
            </a>
        </p>

    </div>
</div>

@endsection
