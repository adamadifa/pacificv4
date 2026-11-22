<div class="overflow-x-auto scrollbar-thin rounded-lg border border-slate-200 dark:border-slate-800">
    <table class="w-full text-xs text-left border-collapse font-sans" id="tabelharga">
        <thead>
            <tr class="bg-[#284c9a] text-white">
                <th class="py-2.5 px-3 text-center w-12 font-bold uppercase tracking-wider text-white border-0">KODE</th>
                <th class="py-2.5 px-4 font-bold uppercase tracking-wider text-white border-0">NAMA PRODUK</th>
                <th class="py-2.5 px-3 text-right font-bold uppercase tracking-wider text-white border-0">HARGA / DUS</th>
                <th class="py-2.5 px-3 text-right font-bold uppercase tracking-wider text-white border-0">HARGA / PACK</th>
                <th class="py-2.5 px-3 text-right font-bold uppercase tracking-wider text-white border-0">HARGA / PCS</th>
                <th class="py-2.5 px-3 text-center font-bold uppercase tracking-wider text-white border-0">KATEGORI</th>
                <th class="py-2.5 px-3 text-center w-12 font-bold uppercase tracking-wider text-white border-0">PILIH</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-100 dark:divide-slate-800/80 bg-white dark:bg-slate-900">
            @forelse ($harga as $d)
                <tr class="hover:bg-slate-50/70 dark:hover:bg-slate-800/50 transition">
                    <td class="py-1.5 px-3 text-center">
                        <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[11px] font-black font-mono-num bg-brand-50 dark:bg-brand-950/60 text-brand-700 dark:text-brand-300 border border-brand-200/60 dark:border-brand-800/60">
                            {{ $d->kode_harga }}
                        </span>
                    </td>
                    <td class="py-1.5 px-3.5 font-semibold text-slate-800 dark:text-slate-100">
                        {{ $d->nama_produk }}
                    </td>
                    <td class="py-1.5 px-3 text-right font-mono-num font-bold text-brand-600 dark:text-brand-400">
                        {{ formatAngka($d->harga_dus) }}
                    </td>
                    <td class="py-1.5 px-3 text-right font-mono-num font-bold text-sky-600 dark:text-sky-400">
                        {{ formatAngka($d->harga_pack) }}
                    </td>
                    <td class="py-1.5 px-3 text-right font-mono-num font-bold text-emerald-600 dark:text-emerald-400">
                        {{ formatAngka($d->harga_pcs) }}
                    </td>
                    <td class="py-1.5 px-3 text-center">
                        <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[10px] font-bold bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 border border-slate-200 dark:border-slate-700">
                            {{ $d->kode_kategori_salesman }}
                        </span>
                    </td>
                    <td class="py-1.5 px-3 text-center">
                        <a href="javascript:void(0)" class="pilihProduk inline-flex items-center justify-center w-6 h-6 rounded-md bg-brand-50 dark:bg-brand-950/60 text-brand-600 dark:text-brand-400 hover:bg-brand-600 hover:text-white transition shadow-2xs"
                            kode_harga="{{ $d->kode_harga }}"
                            nama_produk="{{ $d->nama_produk }}"
                            harga_dus="{{ formatAngka($d->harga_dus) }}"
                            harga_pack="{{ formatAngka($d->harga_pack) }}"
                            harga_pcs="{{ formatAngka($d->harga_pcs) }}"
                            isi_pcs_dus="{{ $d->isi_pcs_dus }}"
                            isi_pcs_pack="{{ $d->isi_pcs_pack }}"
                            kode_kategori_diskon="{{ $d->kode_kategori_diskon }}"
                            kode_produk="{{ $d->kode_produk }}"
                            title="Pilih Produk">
                            <i data-lucide="external-link" class="w-3 h-3"></i>
                        </a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="py-8 text-center text-slate-400">
                        <span class="text-xs">Tidak ada data harga produk</span>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<script>
    $(function() {
        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }
        if ($.fn.DataTable && !$.fn.DataTable.isDataTable('#tabelharga')) {
            $("#tabelharga").DataTable({
                bAutoWidth: false,
                order: [[1, 'asc']]
            });
        }
    });
</script>
