<div class="overflow-x-auto">
    <table class="w-full text-xs text-left border-collapse font-sans whitespace-nowrap" id="tabelharga">
        <thead>
            <tr class="bg-[#1e3a78] text-white text-[11px]">
                <th class="py-2.5 px-3.5 text-center w-20 font-bold uppercase tracking-wider text-white border-0">KODE</th>
                <th class="py-2.5 px-4 font-bold uppercase tracking-wider text-white border-0 min-w-[200px]">NAMA PRODUK</th>
                <th class="py-2.5 px-3.5 text-right font-bold uppercase tracking-wider text-white border-0">RETUR / DUS</th>
                <th class="py-2.5 px-3.5 text-right font-bold uppercase tracking-wider text-white border-0">RETUR / PACK</th>
                <th class="py-2.5 px-3.5 text-right font-bold uppercase tracking-wider text-white border-0">RETUR / PCS</th>
                <th class="py-2.5 px-3.5 text-center w-24 font-bold uppercase tracking-wider text-white border-0">KATEGORI</th>
                <th class="py-2.5 px-3.5 text-center w-14 font-bold uppercase tracking-wider text-white border-0">PILIH</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-100 dark:divide-slate-800/80 bg-white dark:bg-slate-900">
            @foreach ($harga as $d)
                <tr class="hover:bg-slate-50/70 dark:hover:bg-slate-800/50 transition">
                    <td class="py-1.5 px-3.5 text-center">
                        <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[11px] font-black font-mono-num bg-brand-50 dark:bg-brand-950/60 text-brand-700 dark:text-brand-300 border border-brand-200/60 dark:border-brand-800/60">
                            {{ $d->kode_harga }}
                        </span>
                    </td>
                    <td class="py-1.5 px-4 font-semibold text-slate-800 dark:text-slate-100">
                        {{ $d->nama_produk }}
                    </td>
                    <td class="py-1.5 px-3.5 text-right font-mono-num font-bold text-rose-600 dark:text-rose-400">
                        {{ formatAngka($d->harga_retur_dus) }}
                    </td>
                    <td class="py-1.5 px-3.5 text-right font-mono-num font-bold text-amber-600 dark:text-amber-400">
                        {{ formatAngka($d->harga_retur_pack) }}
                    </td>
                    <td class="py-1.5 px-3.5 text-right font-mono-num font-bold text-orange-600 dark:text-orange-400">
                        {{ formatAngka($d->harga_retur_pcs) }}
                    </td>
                    <td class="py-1.5 px-3.5 text-center">
                        <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[10px] font-bold bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 border border-slate-200 dark:border-slate-700">
                            {{ $d->kode_kategori_salesman }}
                        </span>
                    </td>
                    <td class="py-1.5 px-3 text-center">
                        <a href="javascript:void(0)" class="pilihProduk inline-flex items-center justify-center w-6 h-6 rounded-md bg-brand-50 dark:bg-brand-950/60 text-brand-600 dark:text-brand-400 hover:bg-[#284c9a] hover:text-white transition shadow-2xs"
                            kode_harga="{{ $d->kode_harga }}"
                            nama_produk="{{ $d->nama_produk }}"
                            harga_dus="{{ formatAngka($d->harga_retur_dus) }}"
                            harga_pack="{{ formatAngka($d->harga_retur_pack) }}"
                            harga_pcs="{{ formatAngka($d->harga_retur_pcs) }}"
                            isi_pcs_dus="{{ $d->isi_pcs_dus }}"
                            isi_pcs_pack="{{ $d->isi_pcs_pack }}"
                            kode_kategori_diskon="{{ $d->kode_kategori_diskon }}"
                            title="Pilih Produk">
                            <i data-lucide="external-link" class="w-3 h-3"></i>
                        </a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

<script>
    $(function() {
        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }
        if ($.fn.DataTable) {
            if ($.fn.DataTable.isDataTable('#tabelharga')) {
                $('#tabelharga').DataTable().destroy();
            }
            $("#tabelharga").DataTable({
                bAutoWidth: false,
                order: [[1, 'asc']],
                language: {
                    search: "",
                    searchPlaceholder: "Cari produk...",
                    lengthMenu: "Tampilkan _MENU_ data",
                    info: "Menampilkan _START_ - _END_ dari _TOTAL_ produk",
                    infoEmpty: "Menampilkan 0 data",
                    emptyTable: "Tidak ada data harga retur produk untuk pelanggan ini",
                    paginate: {
                        first: "«",
                        previous: "‹",
                        next: "›",
                        last: "»"
                    }
                },
                drawCallback: function() {
                    if (typeof lucide !== 'undefined') {
                        lucide.createIcons();
                    }
                }
            });
        }
    });
</script>
