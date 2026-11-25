@foreach ($historibayar as $d)
    <tr class="hover:bg-slate-50/80 dark:hover:bg-slate-800/40 transition">
        <td class="py-1.5 px-2.5 text-center font-mono-num font-bold text-slate-800 dark:text-slate-100">{{ $d->no_bukti }}</td>
        <td class="py-1.5 px-2.5 text-center font-mono-num text-slate-600 dark:text-slate-400">{{ date('d-m-Y', strtotime($d->tanggal)) }}</td>
        <td class="py-1.5 px-2.5 text-right font-mono-num font-bold text-emerald-600 dark:text-emerald-400">{{ formatAngka($d->jumlah) }}</td>
        <td class="py-1.5 px-2.5 text-center">
            @can('pembayaranpjp.delete')
                @if (empty($d->kode_potongan))
                    <button type="button" class="btnDeletebayar w-6 h-6 rounded-md bg-rose-50 text-rose-600 hover:bg-rose-100 dark:bg-rose-950/60 dark:text-rose-300 transition inline-flex items-center justify-center cursor-pointer" no_bukti="{{ Crypt::encrypt($d->no_bukti) }}" title="Hapus Pembayaran">
                        <i data-lucide="trash-2" class="w-3 h-3"></i>
                    </button>
                @endif
            @endcan
        </td>
    </tr>
@endforeach
@if (count($historibayar) == 0)
    <tr>
        <td colspan="4" class="py-4 text-center text-slate-400 text-xs">Belum ada pembayaran yang dicatat</td>
    </tr>
@endif
