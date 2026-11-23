@forelse ($detailtemp as $d)
    <tr class="hover:bg-slate-50/80 dark:hover:bg-slate-800/50 transition">
        <td class="py-1.5 px-3">
            <span class="font-mono-num font-bold text-[#284c9a] dark:text-blue-400">{{ $d->kode_produk }}</span>
        </td>
        <td class="py-1.5 px-3 font-semibold text-slate-800 dark:text-slate-100">{{ $d->nama_produk }}</td>
        <td class="py-1.5 px-3 text-right font-mono-num font-bold text-slate-900 dark:text-slate-100">{{ formatAngka($d->jumlah) }}</td>
        <td class="py-1.5 px-3 text-center">
            <button type="button" class="btnDeleteTemp w-6 h-6 rounded-md bg-rose-50 hover:bg-rose-100 text-rose-600 dark:bg-rose-950/50 dark:hover:bg-rose-900/50 dark:text-rose-400 inline-flex items-center justify-center transition cursor-pointer mx-auto"
                data-id="{{ $d->id }}" title="Hapus Item">
                <i data-lucide="trash-2" class="w-3 h-3"></i>
            </button>
        </td>
    </tr>
@empty
    <tr id="emptyRow">
        <td colspan="4" class="py-6 text-center text-slate-400 dark:text-slate-500">
            <div class="flex flex-col items-center justify-center gap-1.5">
                <i data-lucide="inbox" class="w-6 h-6 stroke-1 text-slate-300 dark:text-slate-600"></i>
                <span class="text-xs font-medium">Belum ada produk yang ditambahkan</span>
            </div>
        </td>
    </tr>
@endforelse
