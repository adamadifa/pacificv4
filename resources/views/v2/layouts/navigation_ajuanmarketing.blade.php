@if (auth()->check() && auth()->user()->hasAnyPermission(['ajuanlimit.index', 'ajuanfaktur.index']))
    <div class="flex items-center gap-1.5 overflow-x-auto pb-1 scrollbar-none">
        @can('ajuanlimit.index')
            @php $isActive = request()->is(['ajuanlimit', 'ajuanlimit/*']); @endphp
            <a href="{{ route('ajuanlimit.index', ['status' => '0', 'posisi_ajuan' => auth()->user()->hasRole('super admin') ? '' : auth()->user()->getRoleNames()->first()]) }}"
                class="inline-flex items-center gap-2 px-3.5 py-2 rounded-lg font-bold text-xs whitespace-nowrap transition-all duration-150 {{ $isActive ? 'bg-[#284c9a] text-white shadow-xs' : 'bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 text-slate-600 dark:text-slate-300 hover:text-brand-600 dark:hover:text-brand-400 hover:border-brand-200 dark:hover:border-brand-900 hover:bg-brand-50/50 dark:hover:bg-brand-950/30' }}">
                <i data-lucide="credit-card" class="w-4 h-4 {{ $isActive ? 'text-white' : 'text-slate-400' }}"></i>
                <span>Ajuan Limit Kredit</span>
                @if (!empty($notifikasi_limitkredit))
                    <span class="inline-flex items-center px-1.5 py-0.5 rounded-full text-[10px] font-bold bg-rose-500 text-white font-mono-num">
                        {{ $notifikasi_limitkredit }}
                    </span>
                @endif
            </a>
        @endcan

        @can('ajuanfaktur.index')
            @php $isActive = request()->is(['ajuanfaktur', 'ajuanfaktur/*']); @endphp
            <a href="{{ route('ajuanfaktur.index') }}"
                class="inline-flex items-center gap-2 px-3.5 py-2 rounded-lg font-bold text-xs whitespace-nowrap transition-all duration-150 {{ $isActive ? 'bg-[#284c9a] text-white shadow-xs' : 'bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 text-slate-600 dark:text-slate-300 hover:text-brand-600 dark:hover:text-brand-400 hover:border-brand-200 dark:hover:border-brand-900 hover:bg-brand-50/50 dark:hover:bg-brand-950/30' }}">
                <i data-lucide="receipt" class="w-4 h-4 {{ $isActive ? 'text-white' : 'text-slate-400' }}"></i>
                <span>Ajuan Faktur Kredit</span>
                @if (!empty($notifikasi_ajuanfaktur))
                    <span class="inline-flex items-center px-1.5 py-0.5 rounded-full text-[10px] font-bold bg-rose-500 text-white font-mono-num">
                        {{ $notifikasi_ajuanfaktur }}
                    </span>
                @endif
            </a>
        @endcan
    </div>
@endif
