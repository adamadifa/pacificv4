@forelse ($detailtemp as $d)
    <tr class="hover:bg-slate-50/80 dark:hover:bg-slate-800/50 transition duration-150">
        <td class="py-1.5 px-3 font-mono-num font-bold text-slate-700 dark:text-slate-300">{{ $d->kode_produk }}</td>
        <td class="py-1.5 px-3 font-semibold text-slate-900 dark:text-white">{{ $d->nama_produk }}</td>
        <td class="py-1.5 px-3 text-center">
            <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 border border-slate-200/80 dark:border-slate-700">
                Shift {{ $d->shift }}
            </span>
        </td>
        <td class="py-1.5 px-3 text-right font-mono-num font-bold text-brand-600 dark:text-brand-400">
            {{ formatRupiah($d->jumlah) }}
        </td>
        <td class="py-1.5 px-3 text-center">
            <button type="button" data-id="{{ $d->id }}"
                class="delete-temp-bpbj w-6 h-6 rounded-md bg-rose-50 dark:bg-rose-950/40 text-rose-600 dark:text-rose-400 hover:bg-rose-100 dark:hover:bg-rose-900/60 transition inline-flex items-center justify-center cursor-pointer shadow-2xs"
                title="Hapus Rincian">
                <i data-lucide="trash-2" class="w-3 h-3"></i>
            </button>
        </td>
    </tr>
@empty
    <tr>
        <td colspan="5" class="py-6 text-center text-slate-400">
            <span class="text-xs font-semibold">Belum ada rincian yang ditambahkan untuk produk ini</span>
        </td>
    </tr>
@endforelse
