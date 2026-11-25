<div class="space-y-3">
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2.5">
        <div class="relative">
            <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                <i data-lucide="barcode" class="w-4 h-4"></i>
            </div>
            <input type="text" id="nofaktur_search_penjualan" name="nofaktur_search" placeholder="Cari No. Faktur..." autocomplete="off"
                aria-autocomplete="none" wire:model.live.debounce.200ms="nofaktur_search"
                class="w-full h-[36px] pl-9 pr-3 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-950 text-xs font-medium text-slate-700 dark:text-slate-200 placeholder:text-slate-400 focus:outline-none focus:border-[#284c9a] focus:ring-2 focus:ring-[#284c9a]/15 transition">
        </div>

        <div class="relative">
            <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                <i data-lucide="check-circle-2" class="w-4 h-4"></i>
            </div>
            <select name="status_lunas_search" id="status_lunas_search" wire:model.live="status_lunas_search"
                class="w-full h-[36px] pl-9 pr-8 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-950 text-xs font-medium text-slate-700 dark:text-slate-200 focus:outline-none focus:border-[#284c9a] focus:ring-2 focus:ring-[#284c9a]/15 transition">
                <option value="">Semua Status</option>
                <option value="1">Lunas</option>
                <option value="0">Belum Lunas</option>
            </select>
        </div>
    </div>

    @if ($datapenjualan->isEmpty())
        <div class="flex flex-col items-center justify-center py-8 text-center rounded-xl border border-dashed border-slate-200 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-900/40">
            <p class="text-xs font-bold text-slate-600 dark:text-slate-300">Belum ada data penjualan</p>
        </div>
    @else
        <div class="space-y-2">
            @foreach ($datapenjualan as $d)
                @php
                    $isLunas = $d->status == '1';
                    $totalnetto = $d->total_bruto - $d->potongan - $d->potongan_istimewa - $d->penyesuaian + $d->ppn;
                @endphp
                <a href="{{ route('sfa.showpenjualan', Crypt::encrypt($d->no_faktur)) }}"
                    class="block rounded-lg border p-3 transition hover:shadow-xs {{ $isLunas ? 'border-blue-200/80 dark:border-blue-900/60 bg-white dark:bg-slate-900' : 'border-rose-200/80 dark:border-rose-900/60 bg-rose-50/30 dark:bg-rose-950/20' }}">
                    <div class="flex items-start justify-between gap-2">
                        <div class="min-w-0">
                            <div class="flex items-center gap-1.5 flex-wrap">
                                <span class="text-xs font-bold text-slate-800 dark:text-slate-100 font-mono-num">{{ $d->no_faktur }}</span>
                                @if ($d->status_batal == '1')
                                    <span class="px-1.5 py-0.5 rounded text-[10px] font-bold bg-rose-100 text-rose-700 dark:bg-rose-900/60 dark:text-rose-300">BATAL</span>
                                @endif
                            </div>
                            <p class="text-[11px] text-slate-400 mt-0.5">{{ DateToIndo($d->tanggal) }}</p>
                            <p class="text-xs font-black text-slate-900 dark:text-white font-mono-num mt-1">Rp {{ formatAngka($totalnetto) }}</p>
                        </div>

                        <div class="flex flex-col items-end gap-1 shrink-0">
                            <span class="px-2 py-0.5 rounded-md text-[10px] font-bold {{ $d->jenis_transaksi == 'T' ? 'bg-emerald-50 text-emerald-700 dark:bg-emerald-950/60 dark:text-emerald-300' : 'bg-blue-50 text-[#284c9a] dark:bg-blue-950/60 dark:text-blue-300' }}">
                                {{ $d->jenis_transaksi == 'T' ? 'TUNAI' : 'KREDIT' }}
                            </span>
                            <span class="px-2 py-0.5 rounded-md text-[10px] font-bold {{ $isLunas ? 'bg-slate-100 text-slate-600 dark:bg-slate-800 dark:text-slate-300' : 'bg-rose-100 text-rose-700 dark:bg-rose-900/60 dark:text-rose-300' }}">
                                {{ $isLunas ? 'LUNAS' : 'BELUM LUNAS' }}
                            </span>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>

        @if ($datapenjualan->hasPages())
            <div class="pt-2">
                {{ $datapenjualan->links() }}
            </div>
        @endif
    @endif
</div>
