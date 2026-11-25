@if (auth()->user()->hasAnyPermission([
            'setoranpenjualan.index',
            'setorantransfer.index',
            'setorangiro.index',
            'setoranpusat.index',
            'logamtokertas.index',
            'sakasbesar.index',
        ]))
    <div class="flex items-center gap-1.5 overflow-x-auto pb-1 scrollbar-none">
        @can('sakasbesar.index')
            @php $isActive = request()->is(['sakasbesar', 'sakasbesar/*']); @endphp
            <a href="{{ route('sakasbesar.index') }}"
                class="inline-flex items-center gap-2 px-3.5 py-2 rounded-lg font-bold text-xs whitespace-nowrap transition-all duration-150 {{ $isActive ? 'bg-[#284c9a] text-white shadow-xs' : 'bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 text-slate-600 dark:text-slate-300 hover:text-brand-600 dark:hover:text-brand-400 hover:border-brand-200 dark:hover:border-brand-900 hover:bg-brand-50/50 dark:hover:bg-brand-950/30' }}">
                <i data-lucide="wallet" class="w-4 h-4 {{ $isActive ? 'text-white' : 'text-slate-400' }}"></i>
                <span>Saldo Awal</span>
            </a>
        @endcan

        @can('setoranpenjualan.index')
            @php $isActive = request()->is(['setoranpenjualan', 'setoranpenjualan/*']); @endphp
            <a href="{{ route('setoranpenjualan.index') }}"
                class="inline-flex items-center gap-2 px-3.5 py-2 rounded-lg font-bold text-xs whitespace-nowrap transition-all duration-150 {{ $isActive ? 'bg-[#284c9a] text-white shadow-xs' : 'bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 text-slate-600 dark:text-slate-300 hover:text-brand-600 dark:hover:text-brand-400 hover:border-brand-200 dark:hover:border-brand-900 hover:bg-brand-50/50 dark:hover:bg-brand-950/30' }}">
                <i data-lucide="shopping-bag" class="w-4 h-4 {{ $isActive ? 'text-white' : 'text-slate-400' }}"></i>
                <span>Setoran Penjualan</span>
            </a>
        @endcan

        @can('setorantransfer.index')
            @php $isActive = request()->is(['setorantransfer', 'setorantransfer/*']); @endphp
            <a href="{{ route('setorantransfer.index') }}"
                class="inline-flex items-center gap-2 px-3.5 py-2 rounded-lg font-bold text-xs whitespace-nowrap transition-all duration-150 {{ $isActive ? 'bg-[#284c9a] text-white shadow-xs' : 'bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 text-slate-600 dark:text-slate-300 hover:text-brand-600 dark:hover:text-brand-400 hover:border-brand-200 dark:hover:border-brand-900 hover:bg-brand-50/50 dark:hover:bg-brand-950/30' }}">
                <i data-lucide="arrow-down-left" class="w-4 h-4 {{ $isActive ? 'text-white' : 'text-slate-400' }}"></i>
                <span>Setoran Transfer</span>
            </a>
        @endcan

        @can('setorangiro.index')
            @php $isActive = request()->is(['setorangiro', 'setorangiro/*']); @endphp
            <a href="{{ route('setorangiro.index') }}"
                class="inline-flex items-center gap-2 px-3.5 py-2 rounded-lg font-bold text-xs whitespace-nowrap transition-all duration-150 {{ $isActive ? 'bg-[#284c9a] text-white shadow-xs' : 'bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 text-slate-600 dark:text-slate-300 hover:text-brand-600 dark:hover:text-brand-400 hover:border-brand-200 dark:hover:border-brand-900 hover:bg-brand-50/50 dark:hover:bg-brand-950/30' }}">
                <i data-lucide="file-check-2" class="w-4 h-4 {{ $isActive ? 'text-white' : 'text-slate-400' }}"></i>
                <span>Setoran Giro</span>
            </a>
        @endcan

        @can('setoranpusat.index')
            @php $isActive = request()->is(['setoranpusat', 'setoranpusat/*']); @endphp
            <a href="{{ route('setoranpusat.index') }}"
                class="inline-flex items-center gap-2 px-3.5 py-2 rounded-lg font-bold text-xs whitespace-nowrap transition-all duration-150 {{ $isActive ? 'bg-[#284c9a] text-white shadow-xs' : 'bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 text-slate-600 dark:text-slate-300 hover:text-brand-600 dark:hover:text-brand-400 hover:border-brand-200 dark:hover:border-brand-900 hover:bg-brand-50/50 dark:hover:bg-brand-950/30' }}">
                <i data-lucide="landmark" class="w-4 h-4 {{ $isActive ? 'text-white' : 'text-slate-400' }}"></i>
                <span>Setoran Pusat</span>
            </a>
        @endcan

        @can('logamtokertas.index')
            @php $isActive = request()->is(['logamtokertas', 'logamtokertas/*']); @endphp
            <a href="{{ route('logamtokertas.index') }}"
                class="inline-flex items-center gap-2 px-3.5 py-2 rounded-lg font-bold text-xs whitespace-nowrap transition-all duration-150 {{ $isActive ? 'bg-[#284c9a] text-white shadow-xs' : 'bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 text-slate-600 dark:text-slate-300 hover:text-brand-600 dark:hover:text-brand-400 hover:border-brand-200 dark:hover:border-brand-900 hover:bg-brand-50/50 dark:hover:bg-brand-950/30' }}">
                <i data-lucide="coins" class="w-4 h-4 {{ $isActive ? 'text-white' : 'text-slate-400' }}"></i>
                <span>Ganti Logam Ke Kertas</span>
            </a>
        @endcan
    </div>
@endif
