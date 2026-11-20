@if ($paginator->hasPages())
<nav class="flex flex-wrap items-center justify-center gap-2 sm:gap-3" role="navigation" aria-label="Pagination Navigation">

    {{-- Tombol Previous (<) --}}
    @if ($paginator->onFirstPage())
    <span class="flex h-10 w-10 cursor-not-allowed items-center justify-center rounded-md text-muted">
        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
            <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
        </svg>
    </span>
    @else
    <a href="{{ $paginator->previousPageUrl() }}" class="flex h-10 w-10 items-center justify-center rounded-md text-light hover:bg-panel hover:text-sky transition-colors">
        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
            <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
        </svg>
    </a>
    @endif

    {{-- Nomor Halaman & Elipsis (...) --}}
    @foreach ($elements as $element)

    {{-- Elipsis --}}
    @if (is_string($element))
    <span class="flex h-10 w-10 items-center justify-center rounded-md bg-panel/50 font-medium text-muted">{{ $element }}</span>
    @endif
    {{-- Array Halaman --}}
    @if (is_array($element))
    @foreach ($element as $page => $url)
    @if ($page == $paginator->currentPage())

    {{-- Halaman Aktif (Diubah menjadi Biru sesuai tema web) --}}
    <span class="flex h-10 w-10 items-center justify-center rounded-md bg-brand font-bold text-white shadow-sm">{{ $page }}</span>

    @else
    {{-- Halaman Tidak Aktif --}}
    <a href="{{ $url }}" class="flex h-10 w-10 items-center justify-center rounded-md bg-panel/50 font-medium text-light hover:bg-panel hover:text-sky transition-colors">{{ $page }}</a>
    @endif
    @endforeach
    @endif
    @endforeach

    {{-- Tombol Next (>) --}}
    @if ($paginator->hasMorePages())
    <a href="{{ $paginator->nextPageUrl() }}" class="flex h-10 w-10 items-center justify-center rounded-md text-light hover:bg-panel hover:text-sky transition-colors">
        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
        </svg>
    </a>
    @else
    <span class="flex h-10 w-10 cursor-not-allowed items-center justify-center rounded-md text-muted">
        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
        </svg>
    </span>
    @endif

    <div class="ml-2 flex items-center gap-2 text-sm font-medium text-light sm:ml-4">
        <span>Go to page:</span>
        <div class="relative flex items-center">
            <select onchange="window.location.href=this.value" class="appearance-none rounded-md bg-panel py-2 pl-3 pr-8 font-bold text-light focus:outline-none focus:ring-2 focus:ring-sky cursor-pointer">
                @for ($i = 1; $i <= $paginator->lastPage(); $i++)
                    <option class="bg-ink font-medium" value="{{ $paginator->url($i) }}" {{ $paginator->currentPage() == $i ? 'selected' : '' }}>
                        {{ $i }}
                    </option>
                    @endfor
            </select>

            <!-- Ikon Panah Dropdown dengan Inline CSS -->
            <span class="text-muted" style="position: absolute; right: 10px; top: 50%; transform: translateY(-50%); pointer-events: none;">
                <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                </svg>
            </span>
        </div>
    </div>

</nav>
@endif