@forelse ($produk as $d)
    @php
        $jumlah = explode('|', convertToduspackpcsv2($d->isi_pcs_dus, $d->isi_pcs_pack, $d->saldo_akhir));
        $jumlah_dus = $jumlah[0];
        $jumlah_pack = $jumlah[1];
        $jumlah_pcs = $jumlah[2];
    @endphp
    <tr class="hover:bg-slate-50/80 dark:hover:bg-slate-800/50 transition border-b border-slate-100 dark:border-slate-800/80">
        <td class="py-1.5 px-3 text-center text-slate-400 font-mono-num">{{ $loop->iteration }}</td>
        <td class="py-1.5 px-3">
            <input type="hidden" class="kode_produk" name="kode_produk[]" value="{{ $d->kode_produk }}">
            <input type="hidden" class="isi_pcs_dus" name="isi_pcs_dus[]" value="{{ $d->isi_pcs_dus }}">
            <input type="hidden" class="isi_pcs_pack" name="isi_pcs_pack[]" value="{{ $d->isi_pcs_pack }}">
            <span class="font-mono-num font-bold text-[#284c9a] dark:text-blue-400">{{ $d->kode_produk }}</span>
        </td>
        <td class="py-1.5 px-3 font-medium text-slate-800 dark:text-slate-200">
            {{ $d->nama_produk }}
        </td>
        <td class="py-1.5 px-3 text-right">
            @if ($readonly)
                <input type="hidden" name="jml_dus[]" value="{{ formatAngka($jumlah_dus) }}">
                <span class="font-mono-num font-bold text-slate-700 dark:text-slate-200">{{ formatAngka($jumlah_dus) }}</span>
            @else
                <input type="text" name="jml_dus[]" value="{{ formatAngka($jumlah_dus) }}"
                    class="money w-20 text-right px-2 py-1 text-xs font-mono-num font-bold rounded border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:border-brand-500 focus:ring-1 focus:ring-brand-500 transition">
            @endif
        </td>
        <td class="py-1.5 px-3 text-right">
            @if ($readonly)
                <input type="hidden" name="jml_pack[]" value="{{ formatAngka($jumlah_pack) }}">
                <span class="font-mono-num font-bold text-slate-700 dark:text-slate-200">{{ formatAngka($jumlah_pack) }}</span>
            @else
                <input type="text" name="jml_pack[]" value="{{ formatAngka($jumlah_pack) }}"
                    class="money w-20 text-right px-2 py-1 text-xs font-mono-num font-bold rounded border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:border-brand-500 focus:ring-1 focus:ring-brand-500 transition">
            @endif
        </td>
        <td class="py-1.5 px-3 text-right">
            @if ($readonly)
                <input type="hidden" name="jml_pcs[]" value="{{ formatAngka($jumlah_pcs) }}">
                <span class="font-mono-num font-bold text-slate-700 dark:text-slate-200">{{ formatAngka($jumlah_pcs) }}</span>
            @else
                <input type="text" name="jml_pcs[]" value="{{ formatAngka($jumlah_pcs) }}"
                    class="money w-20 text-right px-2 py-1 text-xs font-mono-num font-bold rounded border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:border-brand-500 focus:ring-1 focus:ring-brand-500 transition">
            @endif
        </td>
    </tr>
@empty
    <tr>
        <td colspan="6" class="py-8 text-center text-slate-400">Tidak ada produk ditemukan.</td>
    </tr>
@endforelse

<script>
    if (typeof jQuery !== 'undefined' && typeof $.fn.maskMoney !== 'undefined') {
        $(".money").maskMoney({
            thousands: '.',
            decimal: ',',
            precision: 0,
            allowZero: true
        });
    }
</script>
