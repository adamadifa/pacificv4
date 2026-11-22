@if (auth()->user()->hasAnyPermission(['sabarangproduksi.index', 'barangmasukproduksi.index', 'barangkeluarproduksi.index']))
    <div class="flex items-center gap-1.5 overflow-x-auto pb-1 scrollbar-none">
        @can('sabarangproduksi.index')
            @php $isActive = request()->is(['sabarangproduksi', 'sabarangproduksi/*']); @endphp
            <a href="{{ route('sabarangproduksi.index') }}"
                class="inline-flex items-center gap-2 px-3.5 py-2 rounded-lg font-bold text-xs whitespace-nowrap transition-all duration-150 {{ $isActive ? 'bg-[#284c9a] text-white shadow-xs' : 'bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 text-slate-600 dark:text-slate-300 hover:text-brand-600 dark:hover:text-brand-400 hover:border-brand-200 dark:hover:border-brand-900 hover:bg-brand-50/50 dark:hover:bg-brand-950/30' }}">
                <i data-lucide="database" class="w-4 h-4 {{ $isActive ? 'text-white' : 'text-slate-400' }}"></i>
                <span>Saldo Awal Barang</span>
            </a>
        @endcan

        @can('barangmasukproduksi.index')
            @php $isActive = request()->is(['barangmasukproduksi', 'barangmasukproduksi/*']); @endphp
            <a href="{{ route('barangmasukproduksi.index') }}"
                class="inline-flex items-center gap-2 px-3.5 py-2 rounded-lg font-bold text-xs whitespace-nowrap transition-all duration-150 {{ $isActive ? 'bg-[#284c9a] text-white shadow-xs' : 'bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 text-slate-600 dark:text-slate-300 hover:text-brand-600 dark:hover:text-brand-400 hover:border-brand-200 dark:hover:border-brand-900 hover:bg-brand-50/50 dark:hover:bg-brand-950/30' }}">
                <i data-lucide="package-plus" class="w-4 h-4 {{ $isActive ? 'text-white' : 'text-slate-400' }}"></i>
                <span>Barang Masuk Produksi</span>
            </a>
        @endcan

        @can('barangkeluarproduksi.index')
            @php $isActive = request()->is(['barangkeluarproduksi', 'barangkeluarproduksi/*']); @endphp
            <a href="{{ route('barangkeluarproduksi.index') }}"
                class="inline-flex items-center gap-2 px-3.5 py-2 rounded-lg font-bold text-xs whitespace-nowrap transition-all duration-150 {{ $isActive ? 'bg-[#284c9a] text-white shadow-xs' : 'bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 text-slate-600 dark:text-slate-300 hover:text-brand-600 dark:hover:text-brand-400 hover:border-brand-200 dark:hover:border-brand-900 hover:bg-brand-50/50 dark:hover:bg-brand-950/30' }}">
                <i data-lucide="package-minus" class="w-4 h-4 {{ $isActive ? 'text-white' : 'text-slate-400' }}"></i>
                <span>Barang Keluar Produksi</span>
            </a>
        @endcan
    </div>
@endif
