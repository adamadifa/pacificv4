@if (auth()->user()->hasAnyPermission(['dashboard.marketing', 'dashboard.gudang', 'dashboard.produksi', 'dashboard.generalaffair', 'dashboard.grafik']) || auth()->user()->hasRole(['super admin', 'direktur', 'gm marketing', 'regional sales manager', 'manager keuangan', 'regional operation manager']))
<div class="flex items-center gap-2 overflow-x-auto pb-1 scrollbar-none">
    @can('dashboard.marketing')
        @php $isActive = request()->is(['dashboard', 'dashboard/marketing']); @endphp
        <a href="{{ route('dashboard.marketing') }}" class="inline-flex items-center gap-2 px-3.5 py-2 rounded-lg font-bold text-xs whitespace-nowrap transition-all duration-150 {{ $isActive ? 'bg-brand-600 text-white shadow-xs' : 'bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 text-slate-600 dark:text-slate-300 hover:text-brand-600 dark:hover:text-brand-400 hover:border-brand-200 dark:hover:border-brand-900 hover:bg-brand-50/50 dark:hover:bg-brand-950/30' }}">
            <i data-lucide="line-chart" class="w-4 h-4 {{ $isActive ? 'text-white' : 'text-slate-400' }}"></i>
            <span>Marketing</span>
        </a>
    @endcan

    @if (auth()->user()->hasRole(['super admin', 'direktur', 'regional operation manager', 'rom']) || auth()->user()->can('dashboard.grafik'))
        @php $isActive = request()->routeIs('dashboard.grafik'); @endphp
        <a href="{{ route('dashboard.grafik') }}" class="inline-flex items-center gap-2 px-3.5 py-2 rounded-lg font-bold text-xs whitespace-nowrap transition-all duration-150 {{ $isActive ? 'bg-brand-600 text-white shadow-xs' : 'bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 text-slate-600 dark:text-slate-300 hover:text-brand-600 dark:hover:text-brand-400 hover:border-brand-200 dark:hover:border-brand-900 hover:bg-brand-50/50 dark:hover:bg-brand-950/30' }}">
            <i data-lucide="bar-chart-3" class="w-4 h-4 {{ $isActive ? 'text-white' : 'text-slate-400' }}"></i>
            <span>Grafik Cash In</span>
        </a>

        @php $isActive = request()->routeIs('dashboard.grafik.qty'); @endphp
        <a href="{{ route('dashboard.grafik.qty') }}" class="inline-flex items-center gap-2 px-3.5 py-2 rounded-lg font-bold text-xs whitespace-nowrap transition-all duration-150 {{ $isActive ? 'bg-brand-600 text-white shadow-xs' : 'bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 text-slate-600 dark:text-slate-300 hover:text-brand-600 dark:hover:text-brand-400 hover:border-brand-200 dark:hover:border-brand-900 hover:bg-brand-50/50 dark:hover:bg-brand-950/30' }}">
            <i data-lucide="boxes" class="w-4 h-4 {{ $isActive ? 'text-white' : 'text-slate-400' }}"></i>
            <span>Grafik Qty Produk</span>
        </a>
    @endif

    @can('dashboard.gudang')
        @php $isActive = request()->is(['dashboard/gudang*']); @endphp
        <a href="{{ route('dashboard.gudang') }}" class="inline-flex items-center gap-2 px-3.5 py-2 rounded-lg font-bold text-xs whitespace-nowrap transition-all duration-150 {{ $isActive ? 'bg-brand-600 text-white shadow-xs' : 'bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 text-slate-600 dark:text-slate-300 hover:text-brand-600 dark:hover:text-brand-400 hover:border-brand-200 dark:hover:border-brand-900 hover:bg-brand-50/50 dark:hover:bg-brand-950/30' }}">
            <i data-lucide="warehouse" class="w-4 h-4 {{ $isActive ? 'text-white' : 'text-slate-400' }}"></i>
            <span>Gudang</span>
        </a>
    @endcan

    @can('dashboard.hrd')
        @php $isActive = request()->is(['dashboard/hrd*']); @endphp
        <a href="{{ route('dashboard.hrd') }}" class="inline-flex items-center gap-2 px-3.5 py-2 rounded-lg font-bold text-xs whitespace-nowrap transition-all duration-150 {{ $isActive ? 'bg-brand-600 text-white shadow-xs' : 'bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 text-slate-600 dark:text-slate-300 hover:text-brand-600 dark:hover:text-brand-400 hover:border-brand-200 dark:hover:border-brand-900 hover:bg-brand-50/50 dark:hover:bg-brand-950/30' }}">
            <i data-lucide="users" class="w-4 h-4 {{ $isActive ? 'text-white' : 'text-slate-400' }}"></i>
            <span>HRD</span>
        </a>
    @endcan

    @can('dashboard.produksi')
        @php $isActive = request()->is(['dashboard/produksi*']); @endphp
        <a href="{{ route('dashboard.produksi') }}" class="inline-flex items-center gap-2 px-3.5 py-2 rounded-lg font-bold text-xs whitespace-nowrap transition-all duration-150 {{ $isActive ? 'bg-brand-600 text-white shadow-xs' : 'bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 text-slate-600 dark:text-slate-300 hover:text-brand-600 dark:hover:text-brand-400 hover:border-brand-200 dark:hover:border-brand-900 hover:bg-brand-50/50 dark:hover:bg-brand-950/30' }}">
            <i data-lucide="factory" class="w-4 h-4 {{ $isActive ? 'text-white' : 'text-slate-400' }}"></i>
            <span>Produksi</span>
        </a>
    @endcan

    @can('dashboard.generalaffair')
        @php $isActive = request()->is(['dashboard/generalaffair*']); @endphp
        <a href="{{ route('dashboard.generalaffair') }}" class="inline-flex items-center gap-2 px-3.5 py-2 rounded-lg font-bold text-xs whitespace-nowrap transition-all duration-150 {{ $isActive ? 'bg-brand-600 text-white shadow-xs' : 'bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 text-slate-600 dark:text-slate-300 hover:text-brand-600 dark:hover:text-brand-400 hover:border-brand-200 dark:hover:border-brand-900 hover:bg-brand-50/50 dark:hover:bg-brand-950/30' }}">
            <i data-lucide="building" class="w-4 h-4 {{ $isActive ? 'text-white' : 'text-slate-400' }}"></i>
            <span>General Affair</span>
        </a>
    @endcan
</div>
@endif
