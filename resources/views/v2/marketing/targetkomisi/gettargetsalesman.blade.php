@forelse ($salesman as $d)
    <tr class="group hover:bg-slate-50/80 dark:hover:bg-slate-800/50 transition">
        <!-- Kode Salesman (Sticky/Frozen Left-0) -->
        <td class="sticky left-0 z-10 w-[85px] min-w-[85px] max-w-[85px] bg-white dark:bg-slate-900 group-hover:bg-slate-50 dark:group-hover:bg-slate-800 py-1.5 px-3 text-center border-b border-r border-slate-100 dark:border-slate-800">
            <input type="hidden" name="kode_salesman[]" value="{{ $d->kode_salesman }}">
            <span class="inline-flex items-center px-2 py-0.5 rounded-md font-mono-num font-bold text-[11px] bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 border border-slate-200 dark:border-slate-700">
                {{ $d->kode_salesman }}
            </span>
        </td>

        <!-- Nama Salesman (Sticky/Frozen Left-85px with Elevation Shadow) -->
        <td class="sticky left-[85px] z-10 w-[180px] min-w-[180px] max-w-[180px] bg-white dark:bg-slate-900 group-hover:bg-slate-50 dark:group-hover:bg-slate-800 py-1.5 px-3 font-semibold text-slate-800 dark:text-slate-100 border-b border-slate-100 dark:border-slate-800 border-r-2 border-slate-200 dark:border-slate-700 shadow-[3px_0_6px_-2px_rgba(0,0,0,0.12)] truncate" title="{{ $d->nama_salesman }}">
            {{ $d->nama_salesman }}
        </td>

        <!-- Kolom Input Produk -->
        @foreach ($produk as $p)
            <td class="py-1.5 px-2 text-center border-b border-slate-100 dark:border-slate-800">
                <input type="text"
                    class="money w-20 h-[28px] px-2 text-right font-mono-num font-bold text-xs rounded border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-1 focus:ring-brand-500 focus:border-brand-500 transition"
                    name="{{ $p->kode_produk }}[]"
                    placeholder="0">
            </td>
        @endforeach
    </tr>
@empty
    <tr>
        <td colspan="{{ count($produk) + 2 }}" class="py-8 text-center text-slate-400 dark:text-slate-500">
            <div class="flex flex-col items-center justify-center gap-1.5">
                <i data-lucide="users" class="w-6 h-6 stroke-1 text-slate-300 dark:text-slate-600"></i>
                <span class="text-xs font-medium">Tidak ada data salesman aktif untuk cabang ini</span>
            </div>
        </td>
    </tr>
@endforelse

<script>
    if (typeof $.fn.maskMoney !== 'undefined') {
        $(".money").maskMoney({
            thousands: '.',
            decimal: ',',
            precision: 0,
            allowZero: true
        });
    }
    if (typeof lucide !== 'undefined') {
        lucide.createIcons();
    }
</script>
