@php
    $oman_permissions = ['omancabang.index', 'oman.index'];
@endphp

@if (auth()->user()->hasAnyPermission($oman_permissions))
    <div class="flex items-center gap-1.5 overflow-x-auto pb-1 scrollbar-none">
        @can('omancabang.index')
            @php $isActive = request()->is(['omancabang', 'omancabang/*']); @endphp
            <a href="{{ route('omancabang.index') }}"
                class="inline-flex items-center gap-2 px-3.5 py-2 rounded-lg font-bold text-xs whitespace-nowrap transition-all duration-150 {{ $isActive ? 'bg-[#284c9a] text-white shadow-xs' : 'bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 text-slate-600 dark:text-slate-300 hover:text-brand-600 dark:hover:text-brand-400 hover:border-brand-200 dark:hover:border-brand-900 hover:bg-brand-50/50 dark:hover:bg-brand-950/30' }}">
                <i data-lucide="building-2" class="w-4 h-4 {{ $isActive ? 'text-white' : 'text-slate-400' }}"></i>
                <span>OMAN Cabang</span>
            </a>
        @endcan

        @can('oman.index')
            @php $isActive = request()->is(['oman', 'oman/*']); @endphp
            <a href="{{ route('oman.index') }}"
                class="inline-flex items-center gap-2 px-3.5 py-2 rounded-lg font-bold text-xs whitespace-nowrap transition-all duration-150 {{ $isActive ? 'bg-[#284c9a] text-white shadow-xs' : 'bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 text-slate-600 dark:text-slate-300 hover:text-brand-600 dark:hover:text-brand-400 hover:border-brand-200 dark:hover:border-brand-900 hover:bg-brand-50/50 dark:hover:bg-brand-950/30' }}">
                <i data-lucide="layers" class="w-4 h-4 {{ $isActive ? 'text-white' : 'text-slate-400' }}"></i>
                <span>OMAN Marketing (Pusat)</span>
            </a>
        @endcan
    </div>
@endif
