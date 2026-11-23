@php
    $targetkomisi_permissions = ['targetkomisi.index', 'ratiodriverhelper.index'];
@endphp

@if (auth()->user()->hasAnyPermission($targetkomisi_permissions))
    <div class="flex items-center gap-1.5 overflow-x-auto pb-1 scrollbar-none">
        @can('targetkomisi.index')
            @php $isActive = request()->is(['targetkomisi', 'targetkomisi/*']); @endphp
            <a href="{{ route('targetkomisi.index') }}"
                class="inline-flex items-center gap-2 px-3.5 py-2 rounded-lg font-bold text-xs whitespace-nowrap transition-all duration-150 {{ $isActive ? 'bg-[#284c9a] text-white shadow-xs' : 'bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 text-slate-600 dark:text-slate-300 hover:text-brand-600 dark:hover:text-brand-400 hover:border-brand-200 dark:hover:border-brand-900 hover:bg-brand-50/50 dark:hover:bg-brand-950/30' }}">
                <i data-lucide="crosshair" class="w-4 h-4 {{ $isActive ? 'text-white' : 'text-slate-400' }}"></i>
                <span>Target Komisi</span>
                @if (!empty($notifikasi_target))
                    <span class="inline-flex items-center justify-center px-1.5 py-0.5 rounded-full text-[10px] font-bold bg-rose-500 text-white leading-none">
                        {{ $notifikasi_target }}
                    </span>
                @endif
            </a>
        @endcan

        @can('ratiodriverhelper.index')
            @php $isActive = request()->is(['settingkomisidriverhelper', 'settingkomisidriverhelper/*']); @endphp
            <a href="{{ route('settingkomisidriverhelper.index') }}"
                class="inline-flex items-center gap-2 px-3.5 py-2 rounded-lg font-bold text-xs whitespace-nowrap transition-all duration-150 {{ $isActive ? 'bg-[#284c9a] text-white shadow-xs' : 'bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 text-slate-600 dark:text-slate-300 hover:text-brand-600 dark:hover:text-brand-400 hover:border-brand-200 dark:hover:border-brand-900 hover:bg-brand-50/50 dark:hover:bg-brand-950/30' }}">
                <i data-lucide="settings" class="w-4 h-4 {{ $isActive ? 'text-white' : 'text-slate-400' }}"></i>
                <span>Setting Komisi Driver Helper</span>
            </a>
        @endcan
    </div>
@endif
