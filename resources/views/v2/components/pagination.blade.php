@php
    $hasTotal = method_exists($paginator, 'total');
    $totalCount = $hasTotal ? $paginator->total() : $paginator->count();
    $firstItem = method_exists($paginator, 'firstItem') ? $paginator->firstItem() : 1;
    $lastItem = method_exists($paginator, 'lastItem') ? $paginator->lastItem() : $paginator->count();
    $currentPage = method_exists($paginator, 'currentPage') ? $paginator->currentPage() : 1;
@endphp

@if ($paginator->hasPages())
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 text-xs">
        <!-- Info Kiri: Menampilkan X - Y dari Z data -->
        <div class="text-slate-500 dark:text-slate-400 font-medium">
            Menampilkan
            <span class="font-bold text-slate-800 dark:text-slate-100 font-mono-num">{{ $firstItem }}</span>
            -
            <span class="font-bold text-slate-800 dark:text-slate-100 font-mono-num">{{ $lastItem }}</span>
            @if ($hasTotal)
                dari
                <span class="font-bold text-slate-800 dark:text-slate-100 font-mono-num">{{ $totalCount }}</span>
                data
            @endif
        </div>

        <!-- Tombol Navigasi Halaman -->
        <nav role="navigation" aria-label="Pagination" class="flex items-center gap-1 flex-wrap justify-center sm:justify-end">
            {{-- Tombol Halaman Sebelumnya --}}
            @if ($paginator->onFirstPage())
                <span class="inline-flex items-center justify-center h-7 min-w-[28px] px-2 rounded-lg border border-slate-200/60 dark:border-slate-800/60 bg-slate-50 dark:bg-slate-900 text-slate-300 dark:text-slate-600 cursor-not-allowed select-none transition" aria-disabled="true">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                        <polyline points="15 18 9 12 15 6"></polyline>
                    </svg>
                </span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="inline-flex items-center justify-center h-7 min-w-[28px] px-2 rounded-lg border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 hover:text-slate-900 dark:hover:text-white transition shadow-2xs cursor-pointer">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                        <polyline points="15 18 9 12 15 6"></polyline>
                    </svg>
                </a>
            @endif

            {{-- Elemen Nomor Halaman (hanya jika ada $elements) --}}
            @if (isset($elements))
                @foreach ($elements as $element)
                    {{-- Separator "..." --}}
                    @if (is_string($element))
                        <span class="inline-flex items-center justify-center h-7 min-w-[24px] px-1 text-slate-400 font-medium select-none text-xs">
                            {{ $element }}
                        </span>
                    @endif

                    {{-- Array of Links --}}
                    @if (is_array($element))
                        @foreach ($element as $page => $url)
                            @if ($page == $currentPage)
                                <span aria-current="page" class="inline-flex items-center justify-center h-7 min-w-[28px] px-2 rounded-lg bg-[#284c9a] text-white font-bold font-mono-num shadow-xs cursor-default text-xs">
                                    {{ $page }}
                                </span>
                            @else
                                <a href="{{ $url }}" class="inline-flex items-center justify-center h-7 min-w-[28px] px-2 rounded-lg border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 hover:text-slate-900 dark:hover:text-white font-semibold font-mono-num transition shadow-2xs text-xs cursor-pointer">
                                    {{ $page }}
                                </a>
                            @endif
                        @endforeach
                    @endif
                @endforeach
            @endif

            {{-- Tombol Halaman Berikutnya --}}
            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}" rel="next" class="inline-flex items-center justify-center h-7 min-w-[28px] px-2 rounded-lg border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 hover:text-slate-900 dark:hover:text-white transition shadow-2xs cursor-pointer">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                        <polyline points="9 18 15 12 9 6"></polyline>
                    </svg>
                </a>
            @else
                <span class="inline-flex items-center justify-center h-7 min-w-[28px] px-2 rounded-lg border border-slate-200/60 dark:border-slate-800/60 bg-slate-50 dark:bg-slate-900 text-slate-300 dark:text-slate-600 cursor-not-allowed select-none transition" aria-disabled="true">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                        <polyline points="9 18 15 12 9 6"></polyline>
                    </svg>
                </span>
            @endif
        </nav>
    </div>
@elseif ($totalCount > 0)
    <div class="flex items-center justify-between text-xs">
        <div class="text-slate-500 dark:text-slate-400 font-medium">
            Menampilkan
            <span class="font-bold text-slate-800 dark:text-slate-100 font-mono-num">{{ $firstItem }}</span>
            -
            <span class="font-bold text-slate-800 dark:text-slate-100 font-mono-num">{{ $lastItem }}</span>
            @if ($hasTotal)
                dari
                <span class="font-bold text-slate-800 dark:text-slate-100 font-mono-num">{{ $totalCount }}</span>
                data
            @endif
        </div>
    </div>
@endif
