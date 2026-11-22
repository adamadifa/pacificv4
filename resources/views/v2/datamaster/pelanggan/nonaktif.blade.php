<div class="space-y-3">
    <!-- Action Bar: Check All / Uncheck All -->
    <div class="flex items-center justify-between pb-2 border-b border-slate-100 dark:border-slate-800">
        <div class="flex items-center gap-2">
            <button type="button" id="checkall"
                class="inline-flex items-center justify-center gap-1.5 h-7 px-3 rounded-lg bg-brand-50 hover:bg-brand-100 text-brand-700 dark:bg-brand-950/50 dark:text-brand-300 text-xs font-bold transition cursor-pointer">
                <i data-lucide="check-square" class="w-3.5 h-3.5"></i>
                <span>Pilih Semua</span>
            </button>
            <button type="button" id="uncheckall"
                class="inline-flex items-center justify-center gap-1.5 h-7 px-3 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-700 dark:bg-slate-800 dark:text-slate-300 text-xs font-bold transition cursor-pointer">
                <i data-lucide="square" class="w-3.5 h-3.5"></i>
                <span>Batalkan Semua</span>
            </button>
        </div>
        <span class="text-[11px] font-semibold text-slate-400">Total {{ count($pelanggan) }} outlet pasif</span>
    </div>

    <!-- Table List Nonaktif -->
    <form action="{{ route('pelanggan.updatenonaktifpelanggan') }}" method="POST" id="formNonaktifMassal" class="space-y-3">
        @csrf
        <div class="overflow-x-auto rounded-lg border border-slate-200/80 dark:border-slate-800 max-h-[50vh] overflow-y-auto">
            <table class="w-full text-xs text-left border-collapse whitespace-nowrap">
                <thead class="bg-[#1e3a78] text-white text-[11px] sticky top-0 z-10">
                    <tr>
                        <th class="py-2.5 px-3 text-center w-10">#</th>
                        <th class="py-2.5 px-3 text-center w-10">NO</th>
                        <th class="py-2.5 px-3 w-32 font-bold uppercase tracking-wider text-white/90">KODE</th>
                        <th class="py-2.5 px-4 font-bold uppercase tracking-wider text-white/90">NAMA PELANGGAN</th>
                        <th class="py-2.5 px-3 text-center w-36 font-bold uppercase tracking-wider text-white/90">PENJUALAN TERAKHIR</th>
                        <th class="py-2.5 px-3 text-center w-28 font-bold uppercase tracking-wider text-white/90">LAMA PASIF</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800/80 bg-white dark:bg-slate-900">
                    @forelse ($pelanggan as $key => $d)
                        <tr class="hover:bg-slate-50/70 dark:hover:bg-slate-800/50 transition">
                            <td class="py-1.5 px-3 text-center">
                                <input type="checkbox" class="checkpelanggan w-4 h-4 rounded text-rose-600 focus:ring-rose-500 border-slate-300 cursor-pointer" name="kode_pelanggan[]" value="{{ $d->kode_pelanggan }}">
                            </td>
                            <td class="py-1.5 px-3 text-center font-mono-num text-slate-400 text-[11px]">
                                {{ $key + 1 }}
                            </td>
                            <td class="py-1.5 px-3 font-mono-num font-bold text-brand-600 text-xs">
                                {{ $d->kode_pelanggan }}
                            </td>
                            <td class="py-1.5 px-4 font-semibold text-slate-800 dark:text-slate-100 text-xs">
                                {{ $d->nama_pelanggan }}
                            </td>
                            <td class="py-1.5 px-3 text-center font-mono-num text-slate-600 text-xs">
                                {{ $d->tanggal ? date('d/m/Y', strtotime($d->tanggal)) : 'Belum Ada' }}
                            </td>
                            <td class="py-1.5 px-3 text-center font-semibold text-rose-600 text-xs">
                                {{ konversiHariKeBulan($d->jmlhari) }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-8 text-center text-slate-400 text-xs">
                                Tidak ada data pelanggan yang memenuhi kriteria penonaktifan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <button type="submit" id="btnSubmitNonaktif"
            class="w-full h-[36px] rounded-lg bg-rose-600 hover:bg-rose-700 active:bg-rose-800 text-white font-bold text-xs flex items-center justify-center gap-1.5 shadow-xs transition cursor-pointer">
            <i data-lucide="user-x" class="w-3.5 h-3.5"></i>
            <span>Konfirmasi Nonaktifkan Pelanggan Terpilih</span>
        </button>
    </form>
</div>

<script>
    $(function() {
        if (typeof lucide !== 'undefined') lucide.createIcons();

        $("#checkall").on("click", function() {
            $(".checkpelanggan").prop("checked", true);
        });

        $("#uncheckall").on("click", function() {
            $(".checkpelanggan").prop("checked", false);
        });

        $("#formNonaktifMassal").on("submit", function(e) {
            const count = $(".checkpelanggan:checked").length;
            if (count === 0) {
                e.preventDefault();
                alert("Pilih minimal satu pelanggan yang akan dinonaktifkan!");
                return false;
            }
            if (!confirm(`Yakin ingin menonaktifkan ${count} pelanggan terpilih?`)) {
                e.preventDefault();
                return false;
            }
        });
    });
</script>
