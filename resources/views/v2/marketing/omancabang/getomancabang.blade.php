@if ($detail->isEmpty())
    <tr>
        <td colspan="7" class="py-8 text-center text-slate-400 dark:text-slate-500">
            <div class="flex flex-col items-center justify-center gap-1.5">
                <i data-lucide="inbox" class="w-6 h-6 stroke-1 text-slate-300 dark:text-slate-600"></i>
                <span class="text-xs font-medium">Data OMAN cabang belum tersedia untuk periode ini</span>
            </div>
        </td>
    </tr>
@else
    @foreach ($detail as $d)
        <tr class="hover:bg-slate-50/80 dark:hover:bg-slate-800/50 transition">
            <td class="py-2 px-3 text-center">
                <input type="hidden" name="kode_produk[]" value="{{ $d->kode_produk }}">
                <span class="font-mono-num font-bold text-[#284c9a] dark:text-blue-400">{{ $d->kode_produk }}</span>
            </td>
            <td class="py-2 px-3 font-semibold text-slate-800 dark:text-slate-100">
                {{ $d->nama_produk }}
            </td>
            <td class="py-2 px-3 text-center">
                <a href="#" class="editOmancabang inline-flex items-center justify-center px-2 py-0.5 rounded-md font-mono-num font-bold text-xs text-brand-600 dark:text-blue-400 hover:bg-brand-50 dark:hover:bg-brand-950/50 hover:underline transition"
                    kode_produk="{{ $d->kode_produk }}" minggu_ke="1" bulan="{{ $bulan }}" tahun="{{ $tahun }}" title="Klik untuk edit rincian cabang">
                    {{ formatAngka($d->minggu_1) }}
                </a>
                <input type="hidden" id="jmlm1" name="jmlm1[]" class="jmlm1 text-end"
                    value="{{ formatAngka($d->minggu_1) }}">
            </td>
            <td class="py-2 px-3 text-center">
                <a href="#" class="editOmancabang inline-flex items-center justify-center px-2 py-0.5 rounded-md font-mono-num font-bold text-xs text-brand-600 dark:text-blue-400 hover:bg-brand-50 dark:hover:bg-brand-950/50 hover:underline transition"
                    kode_produk="{{ $d->kode_produk }}" minggu_ke="2" bulan="{{ $bulan }}" tahun="{{ $tahun }}" title="Klik untuk edit rincian cabang">
                    {{ formatAngka($d->minggu_2) }}
                </a>
                <input type="hidden" id="jmlm2" name="jmlm2[]" class="jmlm2 text-end"
                    value="{{ formatAngka($d->minggu_2) }}">
            </td>
            <td class="py-2 px-3 text-center">
                <a href="#" class="editOmancabang inline-flex items-center justify-center px-2 py-0.5 rounded-md font-mono-num font-bold text-xs text-brand-600 dark:text-blue-400 hover:bg-brand-50 dark:hover:bg-brand-950/50 hover:underline transition"
                    kode_produk="{{ $d->kode_produk }}" minggu_ke="3" bulan="{{ $bulan }}" tahun="{{ $tahun }}" title="Klik untuk edit rincian cabang">
                    {{ formatAngka($d->minggu_3) }}
                </a>
                <input type="hidden" id="jmlm3" name="jmlm3[]" class="jmlm3 text-end"
                    value="{{ formatAngka($d->minggu_3) }}">
            </td>
            <td class="py-2 px-3 text-center">
                <a href="#" class="editOmancabang inline-flex items-center justify-center px-2 py-0.5 rounded-md font-mono-num font-bold text-xs text-brand-600 dark:text-blue-400 hover:bg-brand-50 dark:hover:bg-brand-950/50 hover:underline transition"
                    kode_produk="{{ $d->kode_produk }}" minggu_ke="4" bulan="{{ $bulan }}" tahun="{{ $tahun }}" title="Klik untuk edit rincian cabang">
                    {{ formatAngka($d->minggu_4) }}
                </a>
                <input type="hidden" id="jmlm4" name="jmlm4[]" class="jmlm4 text-end"
                    value="{{ formatAngka($d->minggu_4) }}">
            </td>
            <td class="py-2 px-3 text-center font-mono-num font-bold text-slate-900 dark:text-slate-100 bg-slate-50/50 dark:bg-slate-800/30">
                {{ formatAngka($d->total) }}
                <input type="hidden" id="subtotal" name="subtotal[]" class="subtotal text-end"
                    value="{{ formatAngka($d->total) }}" readonly>
            </td>
        </tr>
    @endforeach
@endif
