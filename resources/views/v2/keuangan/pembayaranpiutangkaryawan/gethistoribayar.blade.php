@forelse ($historibayar as $d)
    @php
        $tgl = explode('-', $d->tanggal);
        $bulan = $tgl[1];
        $tahun = $tgl[0];

        if ($bulan == 1) {
            $bln = 12;
            $thn = $tahun - 1;
        } else {
            $bln = $bulan - 1;
            $thn = $tahun;
        }
        $tanggal = $thn . '-' . $bln . '-01';
    @endphp
    <tr class="hover:bg-slate-50/80 dark:hover:bg-slate-800/40 transition">
        <td class="py-1.5 px-3 text-center font-mono-num font-bold text-slate-800 dark:text-slate-100">{{ $d->no_bukti }}</td>
        <td class="py-1.5 px-3 text-center font-mono-num text-slate-600 dark:text-slate-400">{{ date('d-m-Y', strtotime($tanggal)) }}</td>
        <td class="py-1.5 px-3 text-right font-mono-num font-bold text-emerald-600 dark:text-emerald-400">{{ formatAngka($d->jumlah) }}</td>
        <td class="py-1.5 px-3 text-slate-700 dark:text-slate-300">
            @if ($d->jenis_bayar == '1')
                Potong Gaji
            @elseif ($d->jenis_bayar == '2')
                Potong Komisi
            @elseif ($d->jenis_bayar == '3')
                Titipan Pelanggan
            @else
                Lainnya
            @endif
        </td>
        <td class="py-1.5 px-3 text-center">
            <button type="button" class="btnDeletebayar w-6 h-6 rounded-md bg-rose-50 text-rose-600 hover:bg-rose-100 dark:bg-rose-950/60 dark:text-rose-300 transition inline-flex items-center justify-center cursor-pointer" no_bukti="{{ Crypt::encrypt($d->no_bukti) }}" title="Hapus Pembayaran">
                <i data-lucide="trash-2" class="w-3 h-3"></i>
            </button>
        </td>
    </tr>
@empty
    <tr>
        <td colspan="5" class="py-6 text-center text-slate-400 text-xs">Belum ada histori pembayaran</td>
    </tr>
@endforelse
