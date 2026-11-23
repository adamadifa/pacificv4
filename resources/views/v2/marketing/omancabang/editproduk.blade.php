<input type="hidden" name="minggu_ke_edit_omancabang" value="{{ $minggu_ke }}">

<div class="space-y-3">
    <!-- INFO SUMMARY STRIP -->
    <div class="rounded-xl border border-slate-200/80 dark:border-slate-800 bg-slate-50/70 dark:bg-slate-900/40 p-3 sm:p-3.5">
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 text-xs">
            <div>
                <span class="block text-[10.5px] font-bold uppercase tracking-wider text-slate-400 dark:text-slate-500 mb-0.5">Produk</span>
                <span class="font-bold font-mono-num text-[#284c9a] dark:text-blue-400 block text-xs truncate" title="{{ $produk->nama_produk }}">
                    {{ $produk->kode_produk }} - {{ $produk->nama_produk }}
                </span>
            </div>
            <div class="sm:border-l sm:border-slate-200/80 sm:dark:border-slate-800 sm:pl-3.5">
                <span class="block text-[10.5px] font-bold uppercase tracking-wider text-slate-400 dark:text-slate-500 mb-0.5">Minggu Ke</span>
                <span class="inline-flex items-center px-2 py-0.5 rounded-md font-bold text-xs bg-blue-50 text-[#284c9a] dark:bg-blue-950/60 dark:text-blue-400 border border-blue-200/60 dark:border-blue-900/50">
                    Minggu ke {{ $minggu_ke }}
                </span>
            </div>
            <div class="sm:border-l sm:border-slate-200/80 sm:dark:border-slate-800 sm:pl-3.5">
                <span class="block text-[10.5px] font-bold uppercase tracking-wider text-slate-400 dark:text-slate-500 mb-0.5">Bulan</span>
                <span class="font-bold text-slate-800 dark:text-slate-100 text-xs">
                    {{ $namabulan[$bulan] }}
                </span>
            </div>
            <div class="sm:border-l sm:border-slate-200/80 sm:dark:border-slate-800 sm:pl-3.5">
                <span class="block text-[10.5px] font-bold uppercase tracking-wider text-slate-400 dark:text-slate-500 mb-0.5">Tahun</span>
                <span class="font-bold font-mono-num text-slate-800 dark:text-slate-100 text-xs">
                    {{ $tahun }}
                </span>
            </div>
        </div>
    </div>

    <!-- TABEL EDIT KUANTITI CABANG -->
    <div class="rounded-xl border border-slate-200/80 dark:border-slate-800 overflow-hidden shadow-2xs">
        <div class="overflow-x-auto max-h-[300px]">
            <table class="w-full text-xs text-left border-collapse whitespace-nowrap">
                <thead class="sticky top-0 z-10">
                    <tr class="bg-[#284c9a] text-white">
                        <th colspan="2" class="px-3.5 py-2 bg-[#284c9a] text-white border-0">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-1.5">
                                    <i data-lucide="building-2" class="w-3.5 h-3.5 text-blue-200"></i>
                                    <span class="text-xs font-bold text-white">Rincian Permintaan Per Cabang</span>
                                </div>
                                <span class="text-[10.5px] text-white/70 font-mono-num">{{ count($data) }} Cabang</span>
                            </div>
                        </th>
                    </tr>
                    <tr class="bg-[#1e3a78] text-white text-[11px] border-none">
                        <th class="py-2 px-3 font-bold uppercase tracking-wider text-white/90 border-none">NAMA CABANG</th>
                        <th class="py-2 px-3 text-right w-44 font-bold uppercase tracking-wider text-white/90 border-none">JUMLAH ORDER</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800/80 bg-white dark:bg-slate-900">
                    @foreach ($data as $d)
                        <tr class="hover:bg-slate-50/80 dark:hover:bg-slate-800/50 transition">
                            <td class="py-2 px-3 font-semibold text-slate-800 dark:text-slate-100">
                                {{ textUpperCase($d->nama_cabang) }}
                                <input type="hidden" name="kode_oman_edit_omancabang[]" value="{{ $d->kode_oman }}">
                                <input type="hidden" name="kode_produk_edit_omancabang[]" value="{{ $d->kode_produk }}">
                            </td>
                            <td class="py-2 px-3 text-right">
                                <input type="text" name="jumlah_edit_omancabang[]"
                                    class="money jumlah w-36 h-[30px] px-2 text-right font-mono-num font-bold text-xs rounded-md border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-1 focus:ring-brand-500 focus:border-brand-500 transition"
                                    placeholder="0" value="{{ formatAngka($d->jumlah) }}">
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- ACTION BUTTON -->
    <div class="pt-2 border-t border-slate-200/80 dark:border-slate-800 flex items-center justify-end gap-2">
        <button type="button" class="btn-close-modal h-[34px] px-4 rounded-lg bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-600 dark:text-slate-300 text-xs font-bold transition cursor-pointer">
            Batal
        </button>
        <button type="button" id="updateomanCabang"
            class="inline-flex items-center justify-center gap-1.5 h-[34px] px-4 rounded-lg bg-[#284c9a] hover:bg-[#1e3a78] active:bg-[#152a56] text-white text-xs font-bold transition shadow-xs cursor-pointer">
            <i data-lucide="check" class="w-3.5 h-3.5"></i>
            <span>Simpan Perubahan</span>
        </button>
    </div>
</div>

<script>
    $(function() {
        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }

        if (typeof $.fn.maskMoney !== 'undefined') {
            $(".money").maskMoney({
                thousands: '.',
                decimal: ',',
                precision: 0,
                allowZero: true
            });
        }
    });
</script>
