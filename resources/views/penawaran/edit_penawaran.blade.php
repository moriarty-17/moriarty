@extends('template.frontend')
@section('content')
<div class="mx-auto w-full max-w-[1440px] px-4 py-6 sm:px-6 sm:py-8 lg:px-8 lg:py-12">
    <div
        class="mx-auto w-full max-w-[983px] overflow-hidden rounded-2xl border border-line bg-navy shadow-2xl shadow-ink/30">
        <header class="relative border-b border-line bg-card">
            <div class="absolute inset-x-0 top-0 h-px bg-brand/70"></div>

            <div class="px-5 py-7 sm:px-8 sm:py-8 lg:px-10 lg:py-9">
                <div class="flex items-center gap-4">
                    <div class="h-10 w-1 shrink-0 rounded-full bg-brand shadow-[0_0_18px_rgba(23,105,232,0.20)]"></div>

                    <div>
                        <h1 class="text-2xl font-semibold tracking-tight text-light sm:text-[28px]">
                            Edit Penawaran
                        </h1>

                        <p class="mt-2 text-sm leading-6 text-muted">
                            Perbarui informasi penawaran Anda di bawah ini.
                        </p>
                    </div>
                </div>
            </div>
        </header>

        <form action="{{ route('seller.penawaran.update', \Vinkla\Hashids\Facades\Hashids::encode($penawaran->id_penawaran)) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            @if ($errors->any())
            <div class="mx-5 mt-6 sm:mx-8 lg:mx-10">
                <div class="rounded-xl border border-bad/40 bg-bad/10 px-4 py-3.5">
                    <div class="flex gap-3">
                        <div class="mt-0.5 shrink-0 text-bad">
                            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="12" cy="12" r="10" />
                                <path d="M12 8v4" />
                                <path d="M12 16h.01" />
                            </svg>
                        </div>

                        <ul class="min-w-0 space-y-1 text-sm text-bad">
                            @foreach ($errors->all() as $error)
                            <li class="leading-5">
                                {{ $error }}
                            </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
            @endif

            <div class="px-5 py-7 sm:px-8 sm:py-9 lg:px-10 lg:py-10">

                <div class="max-w-[623px]">
                    <label for="game" class="mb-3 block text-sm font-medium text-light">
                        Tentukan Game
                    </label>

                    <div class="relative sm:max-w-[380px]">
                        <select name="jenis" id="game"
                            class="min-h-11 w-full appearance-none rounded-lg border border-line bg-panel px-3.5 py-2.5 pr-10 text-sm font-medium text-light outline-none ring-0 transition-all duration-300 ease-out hover:-translate-y-[1px] hover:border-sky/60 hover:bg-card hover:shadow-[0_8px_24px_rgba(56,189,248,0.10)] active:translate-y-0 active:border-sky active:bg-panel active:outline-none active:ring-0 active:shadow-[0_0_0_3px_rgba(56,189,248,0.10)] focus:border-sky focus:bg-panel focus:outline-none focus:ring-0 focus:shadow-[0_0_0_3px_rgba(56,189,248,0.16),0_8px_28px_rgba(56,189,248,0.12)] focus-visible:border-sky focus-visible:outline-none focus-visible:ring-0 focus-visible:shadow-[0_0_0_3px_rgba(56,189,248,0.16),0_8px_28px_rgba(56,189,248,0.12)]">
                            <option value="">Pilih</option>
                            <option value="Genshin Impact" {{ old('jenis', $penawaran->jenis) == 'Genshin Impact' ? 'selected' : '' }}>Genshin Impact</option>
                            <option value="Honkai Star Rail" {{ old('jenis', $penawaran->jenis) == 'Honkai Star Rail' ? 'selected' : '' }}>Honkai Star Rail</option>
                            <option value="Neverness to Everness" {{ old('jenis', $penawaran->jenis) == 'Neverness to Everness' ? 'selected' : '' }}>Neverness to Everness</option>
                            <option value="Zenless Zone Zero" {{ old('jenis', $penawaran->jenis) == 'Zenless Zone Zero' ? 'selected' : '' }}>Zenless Zone Zero</option>
                            <option value="Wuthering Waves" {{ old('jenis', $penawaran->jenis) == 'Wuthering Waves' ? 'selected' : '' }}>Wuthering Waves</option>
                        </select>

                        <span
                            class="pointer-events-none absolute right-3.5 top-1/2 -translate-y-1/2 text-muted transition-all duration-300">
                            <svg class="h-4 w-4 transition-transform duration-300" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="m6 9 6 6 6-6" />
                            </svg>
                        </span>
                    </div>
                </div>

                <div class="mt-8 max-w-[623px]">
                    <label for="judul" class="mb-3 block text-sm font-medium text-light">
                        Judul Penawaran
                    </label>

                    <textarea id="judul" name="judul" rows="3"
                        placeholder="Contoh: Genshin Impact AR 60 — Full Character & Weapon"
                        class="w-full resize-y rounded-lg border border-line bg-panel px-3.5 py-3 text-sm font-medium leading-6 text-light outline-none ring-0 placeholder:text-muted transition-all duration-300 ease-out hover:-translate-y-[1px] hover:border-sky/60 hover:bg-card hover:shadow-[0_8px_24px_rgba(56,189,248,0.10)] active:translate-y-0 active:border-sky active:bg-panel active:outline-none active:ring-0 active:shadow-[0_0_0_3px_rgba(56,189,248,0.10)] focus:border-sky focus:bg-panel focus:outline-none focus:ring-0 focus:shadow-[0_0_0_3px_rgba(56,189,248,0.16),0_8px_28px_rgba(56,189,248,0.12)] focus-visible:border-sky focus-visible:outline-none focus-visible:ring-0 focus-visible:shadow-[0_0_0_3px_rgba(56,189,248,0.16),0_8px_28px_rgba(56,189,248,0.12)]">{{ old('judul', $penawaran->judul) }}</textarea>
                </div>

                <div class="mt-8">
                    <span class="mb-3 block text-sm font-medium text-light">
                        Gambar Saat Ini
                    </span>

                    {{-- Container untuk menyimpan ID gambar yang akan dihapus --}}
                    <div id="deleted_images_container"></div>

                    {{-- Menampilkan Gambar yang Sudah Ada --}}
                    @if(isset($penawaran->gambarPenawaran) && $penawaran->gambarPenawaran->count() > 0)
                    <div class="mb-5 grid grid-cols-2 gap-4 sm:grid-cols-3 lg:grid-cols-4">
                        @foreach($penawaran->gambarPenawaran as $gbr)
                        <div class="existing-image-container relative aspect-video overflow-hidden rounded-xl border border-line bg-panel" style="position: relative;">
                            <img src="{{ asset('assets/resource/penawaran/' . $gbr->gambar) }}"
                                alt="Gambar Penawaran"
                                class="h-full w-full object-cover">

                            {{-- Tombol Hapus (X) Putih Hitam Simpel --}}
                            <button type="button"
                                onclick="hapusGambar(this, {{ $gbr->id_gambar }})"
                                title="Hapus Gambar"
                                style="position: absolute; top: 8px; right: 8px; z-index: 50; width: 26px; height: 26px; background-color: #ffffff; color: #000000; border-radius: 50%; display: flex; align-items: center; justify-content: center; cursor: pointer; border: none; box-shadow: 0 2px 5px rgba(0,0,0,0.2);">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="width: 14px; height: 14px;">
                                    <path d="M18 6L6 18M6 6l12 12"></path>
                                </svg>
                            </button>
                        </div>
                        @endforeach
                    </div>
                    @else
                    <div class="mb-5 rounded-xl border border-dashed border-line bg-panel/50 px-4 py-6 text-center text-sm text-muted">
                        Belum ada gambar yang di-upload.
                    </div>
                    @endif

                    <span class="mb-3 mt-6 block text-sm font-medium text-light">
                        Upload Gambar Baru <span class="text-xs font-normal text-muted">(Biarkan kosong jika tidak ingin menambah gambar)</span>
                    </span>

                    <label id="upload-area" for="ktp_image"
                        class="group relative flex min-h-[190px] w-full cursor-pointer flex-col items-center justify-center overflow-hidden rounded-xl border border-dashed border-line bg-panel px-5 py-8 text-center outline-none ring-0 transition-all duration-300 ease-out hover:-translate-y-[1px] hover:border-sky/70 hover:bg-card hover:shadow-[0_10px_30px_rgba(56,189,248,0.12)] active:translate-y-0 active:border-sky active:bg-panel active:outline-none active:ring-0 active:shadow-[0_0_0_3px_rgba(56,189,248,0.10)] focus:border-sky focus:outline-none focus:ring-0 focus:shadow-[0_0_0_3px_rgba(56,189,248,0.16),0_8px_28px_rgba(56,189,248,0.12)] focus-visible:border-sky focus-visible:outline-none focus-visible:ring-0 focus-visible:shadow-[0_0_0_3px_rgba(56,189,248,0.16),0_8px_28px_rgba(56,189,248,0.12)] focus-within:border-sky focus-within:bg-panel focus-within:outline-none focus-within:ring-0 focus-within:shadow-[0_0_0_3px_rgba(56,189,248,0.16),0_8px_28px_rgba(56,189,248,0.12)]">
                        <div id="upload-placeholder" class="flex flex-col items-center justify-center">
                            <span
                                class="mb-4 flex h-12 w-12 items-center justify-center rounded-xl border border-line bg-card text-muted shadow-[0_4px_14px_rgba(4,17,31,0.18)] transition-all duration-300 ease-out group-hover:-translate-y-0.5 group-hover:scale-105 group-hover:border-sky group-hover:bg-panel group-hover:text-sky group-hover:shadow-[0_8px_22px_rgba(56,189,248,0.16)] group-active:translate-y-0 group-active:border-sky group-active:bg-panel group-active:text-sky group-active:shadow-[0_0_0_3px_rgba(56,189,248,0.10)]">
                                <svg class="h-5 w-5 transition-transform duration-300 group-hover:-translate-y-0.5 group-active:translate-y-0"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7"
                                    stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M12 16V4" />
                                    <path d="m7 9 5-5 5 5" />
                                    <path d="M4 16v2a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2v-2" />
                                </svg>
                            </span>

                            <p
                                class="text-sm font-medium text-light transition-colors duration-300 group-hover:text-sky">
                                Pilih beberapa gambar baru
                            </p>

                            <p class="mt-2 text-xs text-muted">
                                JPG atau PNG
                                <span class="mx-1 text-line">•</span>
                                Maks. 2MB per file
                            </p>
                        </div>

                        <div id="ktp_preview" class="hidden w-full grid-cols-2 gap-3 p-2 sm:grid-cols-3 lg:grid-cols-4">
                        </div>

                        <input type="file" id="ktp_image" name="gambar[]" accept="image/png,image/jpeg" multiple
                            class="sr-only">
                    </label>
                </div>

                {{-- Script untuk menangani tombol X gambar --}}
                <script>
                    function hapusGambar(btnElement, idGambar) {
                        // Konfirmasi sebelum menghapus dari layar
                        if (confirm('Tandai gambar ini untuk dihapus?')) {
                            // 1. Buat elemen input hidden baru
                            const input = document.createElement('input');
                            input.type = 'hidden';
                            input.name = 'hapus_gambar[]'; // Menjadi array di PHP
                            input.value = idGambar;

                            // 2. Masukkan ke dalam form
                            document.getElementById('deleted_images_container').appendChild(input);

                            // 3. Hapus elemen gambar dari tampilan
                            btnElement.closest('.existing-image-container').remove();
                        }
                    }
                </script>

                <div class="mt-8 max-w-[623px]">
                    <label for="deskripsi" class="mb-3 block text-sm font-medium text-light">
                        Deskripsi
                    </label>

                    <textarea name="deskripsi" id="deskripsi" rows="6"
                        placeholder="Jelaskan detail akun, progress, item, character, weapon, atau informasi lain yang relevan..."
                        class="w-full resize-y rounded-lg border border-line bg-panel px-3.5 py-3 text-sm font-medium leading-6 text-light outline-none ring-0 placeholder:text-muted transition-all duration-300 ease-out hover:-translate-y-[1px] hover:border-sky/60 hover:bg-card hover:shadow-[0_8px_24px_rgba(56,189,248,0.10)] active:translate-y-0 active:border-sky active:bg-panel active:outline-none active:ring-0 active:shadow-[0_0_0_3px_rgba(56,189,248,0.10)] focus:border-sky focus:bg-panel focus:outline-none focus:ring-0 focus:shadow-[0_0_0_3px_rgba(56,189,248,0.16),0_8px_28px_rgba(56,189,248,0.12)] focus-visible:border-sky focus-visible:outline-none focus-visible:ring-0 focus-visible:shadow-[0_0_0_3px_rgba(56,189,248,0.16),0_8px_28px_rgba(56,189,248,0.12)]">{{ old('deskripsi', $penawaran->deskripsi) }}</textarea>
                </div>

                <div class="mt-8 max-w-[623px]">
                    <label for="akun" class="mb-3 block text-sm font-medium text-light">
                        Informasi Penawaran
                        <span class="font-normal text-muted">
                            (Email &amp; Password Akun)
                        </span>
                    </label>

                    <textarea name="informasi_penawaran" id="akun" rows="4" placeholder="Masukkan Email &amp; Password..."
                        class="w-full resize-y rounded-lg border border-line bg-panel px-3.5 py-3 text-sm font-medium leading-6 text-light outline-none ring-0 placeholder:text-muted transition-all duration-300 ease-out hover:-translate-y-[1px] hover:border-sky/60 hover:bg-card hover:shadow-[0_8px_24px_rgba(56,189,248,0.10)] active:translate-y-0 active:border-sky active:bg-panel active:outline-none active:ring-0 active:shadow-[0_0_0_3px_rgba(56,189,248,0.10)] focus:border-sky focus:bg-panel focus:outline-none focus:ring-0 focus:shadow-[0_0_0_3px_rgba(56,189,248,0.16),0_8px_28px_rgba(56,189,248,0.12)] focus-visible:border-sky focus-visible:outline-none focus-visible:ring-0 focus-visible:shadow-[0_0_0_3px_rgba(56,189,248,0.16),0_8px_28px_rgba(56,189,248,0.12)]">{{ old('informasi_penawaran', $penawaran->informasi_penawaran) }}</textarea>
                </div>

                <div class="mt-8 max-w-[385px]">
                    <label for="harga" class="mb-3 block text-sm font-medium text-light">
                        Harga
                    </label>

                    <input name="harga" id="harga" type="text" placeholder="Masukkan Harga..."
                        value="{{ old('harga', $penawaran->harga) }}"
                        class="min-h-11 w-full rounded-lg border border-line bg-panel px-3.5 py-2.5 text-sm font-medium text-light outline-none ring-0 placeholder:text-muted transition-all duration-300 ease-out hover:-translate-y-[1px] hover:border-sky/60 hover:bg-card hover:shadow-[0_8px_24px_rgba(56,189,248,0.10)] active:translate-y-0 active:border-sky active:bg-panel active:outline-none active:ring-0 active:shadow-[0_0_0_3px_rgba(56,189,248,0.10)] focus:border-sky focus:bg-panel focus:outline-none focus:ring-0 focus:shadow-[0_0_0_3px_rgba(56,189,248,0.16),0_8px_28px_rgba(56,189,248,0.12)] focus-visible:border-sky focus-visible:outline-none focus-visible:ring-0 focus-visible:shadow-[0_0_0_3px_rgba(56,189,248,0.16),0_8px_28px_rgba(56,189,248,0.12)]">
                </div>

            </div>

            <div class="border-t border-line bg-card px-5 py-5 sm:px-8 sm:py-6 lg:px-10">
                <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">

                    <p class="text-xs leading-5 text-muted">
                        Pastikan seluruh informasi sudah benar sebelum menyimpan perubahan.
                    </p>

                    <button type="submit"
                        class="btn-animasi inline-flex min-h-11 w-full shrink-0 items-center justify-center rounded-lg bg-brand px-7 py-2.5 text-sm font-semibold text-white shadow-lg shadow-brand/10 transition-all duration-300 ease-out hover:-translate-y-0.5 hover:bg-brand/90 hover:shadow-xl hover:shadow-brand/20 active:translate-y-0 active:bg-brand/80 active:shadow-[inset_0_2px_5px_rgba(4,17,31,0.25)] focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-sky sm:w-auto">
                        Simpan Perubahan
                    </button>

                </div>
            </div>
        </form>
    </div>
</div>
@endsection