<div class="space-y-3">
    <!-- FILTER BAR (NO-LABEL COMPACT V2, MOBILE-FIRST) -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-2.5">
        <div class="relative">
            <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                <i data-lucide="barcode" class="w-3.5 h-3.5"></i>
            </div>
            <input type="text" id="nofaktur_search" name="nofaktur_search" placeholder="Cari No. Faktur..." autocomplete="off"
                aria-autocomplete="none" wire:model.live.debounce.200ms="nofaktur_search"
                class="w-full h-[36px] pl-8 pr-3 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-xs font-medium text-slate-700 dark:text-slate-200 placeholder:text-slate-400 focus:outline-none focus:border-[#284c9a] focus:ring-2 focus:ring-[#284c9a]/15 transition">
        </div>

        <div class="relative">
            <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                <i data-lucide="user" class="w-3.5 h-3.5"></i>
            </div>
            <input type="text" id="nama_pelanggan" name="nama_pelanggan" placeholder="Nama Pelanggan..." autocomplete="off"
                aria-autocomplete="none" wire:model.live.debounce.200ms="nama_pelanggan"
                class="w-full h-[36px] pl-8 pr-3 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-xs font-medium text-slate-700 dark:text-slate-200 placeholder:text-slate-400 focus:outline-none focus:border-[#284c9a] focus:ring-2 focus:ring-[#284c9a]/15 transition">
        </div>

        <div class="grid grid-cols-2 gap-2.5 sm:col-span-2">
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                    <i data-lucide="calendar" class="w-3.5 h-3.5"></i>
                </div>
                <input type="text" id="dari" name="dari" placeholder="Dari Tanggal" autocomplete="off" readonly
                    wire:model.live.debounce.200ms="dari"
                    class="flatpickr-date w-full h-[36px] pl-8 pr-3 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-xs font-medium text-slate-700 dark:text-slate-200 placeholder:text-slate-400 focus:outline-none focus:border-[#284c9a] focus:ring-2 focus:ring-[#284c9a]/15 transition cursor-pointer">
            </div>

            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                    <i data-lucide="calendar" class="w-3.5 h-3.5"></i>
                </div>
                <input type="text" id="sampai" name="sampai" placeholder="Sampai Tanggal" autocomplete="off" readonly
                    wire:model.live.debounce.200ms="sampai"
                    class="flatpickr-date w-full h-[36px] pl-8 pr-3 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-xs font-medium text-slate-700 dark:text-slate-200 placeholder:text-slate-400 focus:outline-none focus:border-[#284c9a] focus:ring-2 focus:ring-[#284c9a]/15 transition cursor-pointer">
            </div>
        </div>
    </div>

    <!-- PENJUALAN CARDS GRID -->
    @if ($datapenjualan->isEmpty())
        <div class="flex flex-col items-center justify-center py-10 text-center rounded-xl border border-dashed border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900">
            <div class="w-10 h-10 rounded-full bg-slate-100 dark:bg-slate-800 text-slate-400 flex items-center justify-center mb-2">
                <i data-lucide="receipt" class="w-5 h-5"></i>
            </div>
            <p class="text-xs font-bold text-slate-600 dark:text-slate-300">Data penjualan tidak ditemukan</p>
            <p class="text-[11px] text-slate-400 mt-0.5">Coba ubah filter no. faktur, nama pelanggan, atau rentang tanggal.</p>
        </div>
    @else
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-2.5">
            @foreach ($datapenjualan as $d)
                @php
                    $isLunas = $d->status == '1';
                    $totalnetto = $d->total_bruto - $d->potongan - $d->potongan_istimewa - $d->penyesuaian + $d->ppn;
                @endphp
                <a wire:key="{{ $d->no_faktur }}" href="{{ route('sfa.showpenjualan', Crypt::encrypt($d->no_faktur)) }}"
                    class="group block rounded-xl border p-3 transition-all duration-150 shadow-2xs hover:shadow-md bg-white dark:bg-slate-900 {{ $isLunas ? 'border-slate-200/80 dark:border-slate-800 hover:border-[#284c9a]/40' : 'border-rose-300/80 dark:border-rose-900/70 hover:border-rose-400' }}">
                    <div class="flex items-start justify-between gap-2">
                        <div class="min-w-0 flex-1">
                            <div class="flex items-center gap-1.5 flex-wrap">
                                <span class="text-[11px] font-bold font-mono-num text-[#284c9a] dark:text-blue-400">
                                    {{ $d->no_faktur }}
                                </span>
                                @if ($d->status_batal == '1')
                                    <span class="px-1.5 py-0.5 rounded text-[10px] font-bold bg-rose-100 dark:bg-rose-900/60 text-rose-700 dark:text-rose-300">
                                        Batal
                                    </span>
                                @endif
                            </div>

                            <h6 class="text-xs font-bold text-slate-800 dark:text-slate-100 truncate mt-0.5 group-hover:text-[#284c9a] dark:group-hover:text-blue-400 transition-colors">
                                {{ textUpperCase($d->nama_pelanggan) }}
                            </h6>

                            <p class="text-[11px] text-slate-400 mt-0.5 flex items-center gap-1">
                                <i data-lucide="calendar" class="w-3 h-3 shrink-0"></i>
                                <span>{{ DateToIndo($d->tanggal) }}</span>
                            </p>
                        </div>

                        <div class="flex flex-col items-end gap-1.5 shrink-0">
                            <span class="px-2 py-0.5 rounded-md text-[10px] font-bold {{ $d->jenis_transaksi == 'T' ? 'bg-emerald-50 dark:bg-emerald-950/50 text-emerald-700 dark:text-emerald-300' : 'bg-blue-50 dark:bg-blue-950/50 text-[#284c9a] dark:text-blue-300' }}">
                                {{ $d->jenis_transaksi == 'T' ? 'TUNAI' : 'KREDIT' }}
                            </span>
                            <span class="text-xs font-black font-mono-num text-slate-800 dark:text-slate-100">
                                {{ formatAngka($totalnetto) }}
                            </span>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>
    @endif
</div>
