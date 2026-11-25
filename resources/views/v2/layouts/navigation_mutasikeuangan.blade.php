@if (auth()->user()->hasAnyPermission(['mutasikeuangan.index', 'samutasikeuangan.index']))
    <div class="flex items-center gap-1 border-b border-slate-200 dark:border-slate-800 pb-2 mb-3 overflow-x-auto text-xs font-bold">
        @can('sakasbesarkeuangan.index')
            @php $isActive = request()->is(['sakasbesarkeuangan']); @endphp
            <a href="{{ route('sakasbesarkeuangan.index') }}"
                class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-lg transition-all duration-150 shrink-0 {{ $isActive ? 'bg-[#284c9a] text-white shadow-xs' : 'text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white hover:bg-slate-100 dark:hover:bg-slate-800/60' }}">
                <i data-lucide="wallet" class="w-4 h-4 {{ $isActive ? 'text-white' : 'text-slate-400' }}"></i>
                <span>Saldo Kas Besar</span>
            </a>
        @endcan

        @hasanyrole('manager keuangan')
            @php $isActive = request()->is(['sakasbesarkeuanganpusat']); @endphp
            <a href="{{ route('sakasbesarkeuanganpusat.index') }}"
                class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-lg transition-all duration-150 shrink-0 {{ $isActive ? 'bg-[#284c9a] text-white shadow-xs' : 'text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white hover:bg-slate-100 dark:hover:bg-slate-800/60' }}">
                <i data-lucide="landmark" class="w-4 h-4 {{ $isActive ? 'text-white' : 'text-slate-400' }}"></i>
                <span>Saldo Kas Besar (Keuangan)</span>
            </a>
        @endhasanyrole

        @can('mutasikeuangan.index')
            @php $isActive = request()->is(['mutasikeuangan', 'mutasikeuangan/*']); @endphp
            <a href="{{ route('mutasikeuangan.index') }}"
                class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-lg transition-all duration-150 shrink-0 {{ $isActive ? 'bg-[#284c9a] text-white shadow-xs' : 'text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white hover:bg-slate-100 dark:hover:bg-slate-800/60' }}">
                <i data-lucide="arrow-left-right" class="w-4 h-4 {{ $isActive ? 'text-white' : 'text-slate-400' }}"></i>
                <span>Mutasi Keuangan</span>
            </a>
        @endcan
    </div>
@endif
