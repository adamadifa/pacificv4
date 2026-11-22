@forelse ($detailbufferstok as $d)
    <tr class="hover:bg-slate-50/70 dark:hover:bg-slate-800/50 transition duration-100">
        <input type="hidden" name="kode_produk[]" value="{{ $d->kode_produk }}">
        
        <!-- Kode Produk -->
        <td class="py-1.5 px-3.5 border-r border-slate-100 dark:border-slate-800/60">
            <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[11px] font-black font-mono-num bg-brand-50 dark:bg-brand-950/60 text-brand-700 dark:text-brand-300 border border-brand-200/60 dark:border-brand-800/60">
                {{ $d->kode_produk }}
            </span>
        </td>

        <!-- Nama Produk -->
        <td class="py-1.5 px-4 font-semibold text-slate-800 dark:text-slate-100 border-r border-slate-100 dark:border-slate-800/60">
            <div class="flex items-center gap-2">
                <div class="w-1.5 h-1.5 rounded-full bg-blue-500 shrink-0"></div>
                <span>{{ $d->nama_produk }}</span>
            </div>
        </td>

        <!-- Buffer Stok -->
        <td class="py-1.5 px-4 text-center border-r border-slate-100 dark:border-slate-800/60">
            <input type="text" name="jumlah_buffer[]" value="{{ $d->jumlah_buffer }}"
                class="w-full max-w-[130px] h-[32px] px-2.5 text-right text-xs font-bold font-mono-num rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white focus:outline-hidden focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition"
                placeholder="0">
        </td>

        <!-- Max. Stok -->
        <td class="py-1.5 px-4 text-center">
            <input type="text" name="jumlah_max[]" value="{{ $d->jumlah_max }}"
                class="w-full max-w-[130px] h-[32px] px-2.5 text-right text-xs font-bold font-mono-num rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white focus:outline-hidden focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition"
                placeholder="0">
        </td>
    </tr>
@empty
    <tr>
        <td colspan="4" class="py-12 text-center text-slate-400">
            <i data-lucide="inbox" class="w-8 h-8 mx-auto text-slate-300 dark:text-slate-600 mb-2"></i>
            <span class="text-xs font-semibold">Tidak ada produk ditemukan untuk cabang ini</span>
        </td>
    </tr>
@endforelse
