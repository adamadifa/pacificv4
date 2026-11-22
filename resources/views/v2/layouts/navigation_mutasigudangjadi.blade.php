@php
    $gudang_jadi_mutasi_permission = $gudang_jadi_mutasi_permission ?? [
        'sagudangjadi.index',
        'fsthpgudang.index',
        'suratjalan.index',
        'repackgudangjadi.index',
        'rejectgudangjadi.index',
        'lainnyagudangjadi.index'
    ];
@endphp

@if (auth()->user()->hasAnyPermission($gudang_jadi_mutasi_permission))
    <div class="flex items-center gap-1.5 overflow-x-auto pb-1 scrollbar-none">
        @can('sagudangjadi.index')
            @php $isActive = request()->is(['sagudangjadi', 'sagudangjadi/*']); @endphp
            <a href="{{ route('sagudangjadi.index') }}"
                class="inline-flex items-center gap-2 px-3.5 py-2 rounded-lg font-bold text-xs whitespace-nowrap transition-all duration-150 {{ $isActive ? 'bg-[#284c9a] text-white shadow-xs' : 'bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 text-slate-600 dark:text-slate-300 hover:text-brand-600 dark:hover:text-brand-400 hover:border-brand-200 dark:hover:border-brand-900 hover:bg-brand-50/50 dark:hover:bg-brand-950/30' }}">
                <i data-lucide="database" class="w-4 h-4 {{ $isActive ? 'text-white' : 'text-slate-400' }}"></i>
                <span>Saldo Awal</span>
            </a>
        @endcan

        @can('fsthpgudang.index')
            @php $isActive = request()->is(['fsthpgudang', 'fsthpgudang/*']); @endphp
            <a href="{{ route('fsthpgudang.index') }}"
                class="inline-flex items-center gap-2 px-3.5 py-2 rounded-lg font-bold text-xs whitespace-nowrap transition-all duration-150 {{ $isActive ? 'bg-[#284c9a] text-white shadow-xs' : 'bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 text-slate-600 dark:text-slate-300 hover:text-brand-600 dark:hover:text-brand-400 hover:border-brand-200 dark:hover:border-brand-900 hover:bg-brand-50/50 dark:hover:bg-brand-950/30' }}">
                <i data-lucide="file-check" class="w-4 h-4 {{ $isActive ? 'text-white' : 'text-slate-400' }}"></i>
                <span>FSTHP</span>
            </a>
        @endcan

        @can('suratjalan.index')
            @php $isActive = request()->is(['suratjalan', 'suratjalan/*', 'suratjalancabang', 'suratjalancabang/*']); @endphp
            <a href="{{ route('suratjalan.index') }}"
                class="inline-flex items-center gap-2 px-3.5 py-2 rounded-lg font-bold text-xs whitespace-nowrap transition-all duration-150 {{ $isActive ? 'bg-[#284c9a] text-white shadow-xs' : 'bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 text-slate-600 dark:text-slate-300 hover:text-brand-600 dark:hover:text-brand-400 hover:border-brand-200 dark:hover:border-brand-900 hover:bg-brand-50/50 dark:hover:bg-brand-950/30' }}">
                <i data-lucide="truck" class="w-4 h-4 {{ $isActive ? 'text-white' : 'text-slate-400' }}"></i>
                <span>Surat Jalan</span>
            </a>
        @endcan

        @can('repackgudangjadi.index')
            @php $isActive = request()->is(['repackgudangjadi', 'repackgudangjadi/*']); @endphp
            <a href="{{ route('repackgudangjadi.index') }}"
                class="inline-flex items-center gap-2 px-3.5 py-2 rounded-lg font-bold text-xs whitespace-nowrap transition-all duration-150 {{ $isActive ? 'bg-[#284c9a] text-white shadow-xs' : 'bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 text-slate-600 dark:text-slate-300 hover:text-brand-600 dark:hover:text-brand-400 hover:border-brand-200 dark:hover:border-brand-900 hover:bg-brand-50/50 dark:hover:bg-brand-950/30' }}">
                <i data-lucide="package-open" class="w-4 h-4 {{ $isActive ? 'text-white' : 'text-slate-400' }}"></i>
                <span>Repack</span>
            </a>
        @endcan

        @can('rejectgudangjadi.index')
            @php $isActive = request()->is(['rejectgudangjadi', 'rejectgudangjadi/*']); @endphp
            <a href="{{ route('rejectgudangjadi.index') }}"
                class="inline-flex items-center gap-2 px-3.5 py-2 rounded-lg font-bold text-xs whitespace-nowrap transition-all duration-150 {{ $isActive ? 'bg-[#284c9a] text-white shadow-xs' : 'bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 text-slate-600 dark:text-slate-300 hover:text-brand-600 dark:hover:text-brand-400 hover:border-brand-200 dark:hover:border-brand-900 hover:bg-brand-50/50 dark:hover:bg-brand-950/30' }}">
                <i data-lucide="trash-2" class="w-4 h-4 {{ $isActive ? 'text-white' : 'text-slate-400' }}"></i>
                <span>Reject</span>
            </a>
        @endcan

        @can('lainnyagudangjadi.index')
            @php $isActive = request()->is(['lainnyagudangjadi', 'lainnyagudangjadi/*']); @endphp
            <a href="{{ route('lainnyagudangjadi.index') }}"
                class="inline-flex items-center gap-2 px-3.5 py-2 rounded-lg font-bold text-xs whitespace-nowrap transition-all duration-150 {{ $isActive ? 'bg-[#284c9a] text-white shadow-xs' : 'bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 text-slate-600 dark:text-slate-300 hover:text-brand-600 dark:hover:text-brand-400 hover:border-brand-200 dark:hover:border-brand-900 hover:bg-brand-50/50 dark:hover:bg-brand-950/30' }}">
                <i data-lucide="file-text" class="w-4 h-4 {{ $isActive ? 'text-white' : 'text-slate-400' }}"></i>
                <span>Lainnya</span>
            </a>
        @endcan
    </div>
@endif
