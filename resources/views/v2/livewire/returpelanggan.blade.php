<div class="space-y-3">
    <div class="relative max-w-sm">
        <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
            <i data-lucide="barcode" class="w-4 h-4"></i>
        </div>
        <input type="text" id="nofaktur_search_retur" name="nofaktur_search" placeholder="Cari No. Faktur..." autocomplete="off"
            aria-autocomplete="none" wire:model.live.debounce.200ms="nofaktur_search"
            class="w-full h-[36px] pl-9 pr-3 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-950 text-xs font-medium text-slate-700 dark:text-slate-200 placeholder:text-slate-400 focus:outline-none focus:border-[#284c9a] focus:ring-2 focus:ring-[#284c9a]/15 transition">
    </div>

    @if ($dataretur->isEmpty())
        <div class="flex flex-col items-center justify-center py-8 text-center rounded-xl border border-dashed border-slate-200 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-900/40">
            <p class="text-xs font-bold text-slate-600 dark:text-slate-300">Belum ada data retur</p>
        </div>
    @else
        <div class="space-y-2">
            @foreach ($dataretur as $d)
                <a href="{{ route('sfa.showretur', Crypt::encrypt($d->no_retur)) }}"
                    class="block rounded-lg border border-slate-200/80 dark:border-slate-800 bg-white dark:bg-slate-900 p-3 transition hover:border-[#284c9a]/40 hover:shadow-xs">
                    <div class="flex items-start justify-between gap-2">
                        <div class="min-w-0">
                            <span class="text-xs font-bold text-slate-800 dark:text-slate-100 font-mono-num block">{{ $d->no_retur }}</span>
                            <span class="text-[11px] text-slate-500 dark:text-slate-400 font-mono-num block">{{ $d->no_faktur }}</span>
                            <p class="text-[11px] text-slate-400 mt-0.5">{{ DateToIndo($d->tanggal) }}</p>
                            <p class="text-xs font-black text-slate-900 dark:text-white font-mono-num mt-1">Rp {{ formatAngka($d->total_retur) }}</p>
                        </div>

                        <span class="px-2 py-0.5 rounded-md text-[10px] font-bold shrink-0 {{ $d->jenis_retur == 'PF' ? 'bg-rose-100 text-rose-700 dark:bg-rose-900/60 dark:text-rose-300' : 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/60 dark:text-emerald-300' }}">
                            {{ $d->jenis_retur == 'PF' ? 'POTONG FAKTUR' : 'GANTI BARANG' }}
                        </span>
                    </div>
                </a>
            @endforeach
        </div>

        @if ($dataretur->hasPages())
            <div class="pt-2">
                {{ $dataretur->links() }}
            </div>
        @endif
    @endif
</div>
