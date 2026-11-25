@if (auth()->user()->hasAnyPermission(['kasbon.index', 'pembayarankasbon.index']))
    <div class="flex items-center gap-1 border-b border-slate-200 dark:border-slate-800 pb-2 mb-3 overflow-x-auto text-xs font-bold">
        @can('kasbon.index')
            @php $isActive = request()->is(['kasbon', 'kasbon/*']); @endphp
            <a href="{{ route('kasbon.index') }}"
                class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-lg transition-all duration-150 shrink-0 {{ $isActive ? 'bg-[#284c9a] text-white shadow-xs' : 'text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white hover:bg-slate-100 dark:hover:bg-slate-800/60' }}">
                <i data-lucide="wallet" class="w-4 h-4 {{ $isActive ? 'text-white' : 'text-slate-400' }}"></i>
                <span>Kasbon</span>
            </a>
        @endcan

        @can('pembayarankasbon.index')
            @php $isActive = request()->is(['pembayarankasbon', 'pembayarankasbon/*']); @endphp
            <a href="{{ route('pembayarankasbon.index') }}"
                class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-lg transition-all duration-150 shrink-0 {{ $isActive ? 'bg-[#284c9a] text-white shadow-xs' : 'text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white hover:bg-slate-100 dark:hover:bg-slate-800/60' }}">
                <i data-lucide="receipt" class="w-4 h-4 {{ $isActive ? 'text-white' : 'text-slate-400' }}"></i>
                <span>Pembayaran</span>
            </a>
        @endcan
    </div>
@endif
