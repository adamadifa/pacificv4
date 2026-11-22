@foreach ($barang as $d)
    <tr class="hover:bg-slate-50/80 dark:hover:bg-slate-800/50">
        <td class="py-1.5 px-3 text-center font-bold font-mono-num text-[#284c9a]">
            <input type="hidden" name="kode_barang[]" value="{{ $d->kode_barang }}">
            {{ $d->kode_barang }}
        </td>
        <td class="py-1.5 px-3 font-medium text-slate-800 dark:text-slate-200">
            {{ $d->nama_barang }}
        </td>
        <td class="py-1.5 px-3 text-slate-600 dark:text-slate-400">
            {{ $d->nama_kategori }}
        </td>
        <td class="py-1.5 px-3 text-right font-mono-num">
            <input type="text" name="qty_unit[]" value="{{ formatAngkaDesimal($d->saldo_unit) }}"
                class="number-separator w-28 text-right bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-md px-2 py-1 text-xs focus:ring-1 focus:ring-brand-500 focus:outline-hidden">
        </td>
        <td class="py-1.5 px-3 text-right font-mono-num">
            <input type="text" name="qty_berat[]" value="{{ formatAngkaDesimal($d->saldo_berat) }}"
                class="number-separator w-28 text-right bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-md px-2 py-1 text-xs focus:ring-1 focus:ring-brand-500 focus:outline-hidden">
        </td>
    </tr>
@endforeach
