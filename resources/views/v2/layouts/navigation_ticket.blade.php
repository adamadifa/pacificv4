<div class="flex items-center gap-2 overflow-x-auto pb-1 scrollbar-none">
    @php $isTicket = request()->is(['ticket', 'ticket/*']) && !request()->is(['ticket/config', 'ticket/config/*']); @endphp
    <a href="{{ route('ticket.index') }}" class="inline-flex items-center gap-2 px-3.5 py-2 rounded-lg font-bold text-xs whitespace-nowrap transition-all duration-150 {{ $isTicket ? 'bg-[#284c9a] text-white shadow-xs' : 'bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 text-slate-600 dark:text-slate-300 hover:text-[#284c9a] dark:hover:text-blue-400 hover:border-blue-200 dark:hover:border-blue-900 hover:bg-blue-50/50 dark:hover:bg-blue-950/30' }}">
        <i data-lucide="ticket" class="w-4 h-4 {{ $isTicket ? 'text-white' : 'text-slate-400' }}"></i>
        <span>Maintenance & Penambahan Fitur</span>
    </a>

    @if (Route::has('ticketcategory.index'))
        @php $isCategory = request()->is(['ticketcategory', 'ticketcategory/*']); @endphp
        <a href="{{ route('ticketcategory.index') }}" class="inline-flex items-center gap-2 px-3.5 py-2 rounded-lg font-bold text-xs whitespace-nowrap transition-all duration-150 {{ $isCategory ? 'bg-[#284c9a] text-white shadow-xs' : 'bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 text-slate-600 dark:text-slate-300 hover:text-[#284c9a] dark:hover:text-blue-400 hover:border-blue-200 dark:hover:border-blue-900 hover:bg-blue-50/50 dark:hover:bg-blue-950/30' }}">
            <i data-lucide="tags" class="w-4 h-4 {{ $isCategory ? 'text-white' : 'text-slate-400' }}"></i>
            <span>Kategori Tiket</span>
        </a>
    @endif

    @if (Route::has('ticketconfig.index'))
        @php $isConfig = request()->is(['ticket/config', 'ticket/config/*']); @endphp
        <a href="{{ route('ticketconfig.index') }}" class="inline-flex items-center gap-2 px-3.5 py-2 rounded-lg font-bold text-xs whitespace-nowrap transition-all duration-150 {{ $isConfig ? 'bg-[#284c9a] text-white shadow-xs' : 'bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 text-slate-600 dark:text-slate-300 hover:text-[#284c9a] dark:hover:text-blue-400 hover:border-blue-200 dark:hover:border-blue-900 hover:bg-blue-50/50 dark:hover:bg-blue-950/30' }}">
            <i data-lucide="settings-2" class="w-4 h-4 {{ $isConfig ? 'text-white' : 'text-slate-400' }}"></i>
            <span>Konfigurasi Approval</span>
        </a>
    @endif
</div>
