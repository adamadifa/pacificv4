@forelse ($detailtemp as $d)
    <tr class="hover:bg-slate-50/80 dark:hover:bg-slate-800/50 transition duration-150">
        <td class="py-1.5 px-3 text-center font-bold text-slate-500 dark:text-slate-400">{{ $loop->iteration }}</td>
        <td class="py-1.5 px-3 font-mono-num font-bold text-slate-700 dark:text-slate-300">{{ $d->kode_barang_produksi }}</td>
        <td class="py-1.5 px-3 font-semibold text-slate-900 dark:text-white">{{ $d->nama_barang }}</td>
        <td class="py-1.5 px-3 text-center">
            <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 border border-slate-200/80 dark:border-slate-700">
                {{ $d->satuan }}
            </span>
        </td>
        <td class="py-1.5 px-3 text-slate-600 dark:text-slate-400">{{ $d->keterangan ?? '-' }}</td>
        <td class="py-1.5 px-3 text-right font-mono-num font-bold text-brand-600 dark:text-brand-400">
            {{ formatAngkaDesimal($d->jumlah) }}
        </td>
        <td class="py-1.5 px-3 text-right font-mono-num font-bold text-emerald-600 dark:text-emerald-400">
            {{ formatAngkaDesimal($d->jumlah_berat) }}
        </td>
        <td class="py-1.5 px-3 text-center">
            <div class="flex items-center justify-center gap-1">
                <button type="button" id="{{ Crypt::encrypt($d->id) }}"
                    class="edit w-6 h-6 rounded-md bg-amber-50 dark:bg-amber-950/40 text-amber-600 dark:text-amber-400 hover:bg-amber-100 dark:hover:bg-amber-900/60 transition inline-flex items-center justify-center cursor-pointer shadow-2xs"
                    title="Edit Barang">
                    <i data-lucide="edit-3" class="w-3 h-3"></i>
                </button>
                <button type="button" id="{{ $d->id }}"
                    class="delete w-6 h-6 rounded-md bg-rose-50 dark:bg-rose-950/40 text-rose-600 dark:text-rose-400 hover:bg-rose-100 dark:hover:bg-rose-900/60 transition inline-flex items-center justify-center cursor-pointer shadow-2xs"
                    title="Hapus Barang">
                    <i data-lucide="trash-2" class="w-3 h-3"></i>
                </button>
            </div>
        </td>
    </tr>
@empty
    <tr>
        <td colspan="8" class="py-6 text-center text-slate-400">
            <span class="text-xs font-semibold">Belum ada barang dalam bukti ini</span>
        </td>
    </tr>
@endforelse
