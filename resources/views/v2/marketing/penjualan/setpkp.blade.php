<form action="{{ route('penjualan.updatesetpkp', Crypt::encrypt($penjualan->no_faktur)) }}" method="POST" id="formSetpkp" class="space-y-3.5" autocomplete="off">
    @csrf
    @method('PUT')

    <!-- EXECUTIVE SUMMARY STRIP -->
    <div class="rounded-xl border border-slate-200/80 dark:border-slate-800 p-3 sm:p-3.5 bg-slate-50/50 dark:bg-slate-900/40">
        <div class="flex items-center justify-between pb-2 mb-2.5 border-b border-slate-200/80 dark:border-slate-800">
            <div class="flex items-center gap-2">
                <div class="w-6 h-6 rounded-md bg-amber-50 dark:bg-amber-950/60 text-amber-600 dark:text-amber-400 flex items-center justify-center shrink-0">
                    <i data-lucide="building" class="w-3.5 h-3.5"></i>
                </div>
                <div>
                    <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 dark:text-slate-500 block leading-none">Penetapan PKP</span>
                    <span class="font-bold font-mono-num text-[#284c9a] dark:text-blue-400 text-xs sm:text-sm">
                        {{ $penjualan->no_faktur }}
                    </span>
                </div>
            </div>
            @if(!empty($penjualan->kode_pkp))
                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10.5px] font-bold bg-emerald-50 dark:bg-emerald-950/50 text-emerald-700 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-900/50">
                    <i data-lucide="check-circle" class="w-3 h-3"></i>
                    <span>PKP: {{ strtoupper($penjualan->kode_pkp) }}</span>
                </span>
            @else
                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10.5px] font-bold bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400 border border-slate-200 dark:border-slate-700">
                    <i data-lucide="minus-circle" class="w-3 h-3"></i>
                    <span>Non-PKP</span>
                </span>
            @endif
        </div>

        <div class="grid grid-cols-2 sm:grid-cols-4 gap-2.5 text-xs">
            <div>
                <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 dark:text-slate-500 block">Tanggal Faktur</span>
                <span class="font-semibold text-slate-800 dark:text-slate-200">{{ DateToIndo($penjualan->tanggal) }}</span>
            </div>
            <div>
                <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 dark:text-slate-500 block">Nama Pelanggan</span>
                <span class="font-semibold text-slate-800 dark:text-slate-200 truncate block">{{ $penjualan->nama_pelanggan }}</span>
            </div>
            <div>
                <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 dark:text-slate-500 block">Cabang Asal</span>
                <span class="font-bold text-slate-800 dark:text-slate-200">{{ strtoupper($penjualan->nama_cabang) }}</span>
            </div>
            <div>
                <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 dark:text-slate-500 block">Status PKP Aktif</span>
                <span class="font-mono-num font-semibold text-slate-700 dark:text-slate-300">
                    {{ !empty($penjualan->kode_pkp) ? $penjualan->kode_pkp : 'Belum Ditentukan' }}
                </span>
            </div>
        </div>
    </div>

    <!-- SELECTION SECTION -->
    <div class="rounded-xl border border-slate-200/80 dark:border-slate-800 p-3 sm:p-3.5 bg-slate-50/50 dark:bg-slate-900/40">
        <div class="text-xs font-bold text-slate-700 dark:text-slate-200 mb-2.5 flex items-center gap-1.5">
            <i data-lucide="shield-check" class="w-3.5 h-3.5 text-[#284c9a] dark:text-blue-400"></i>
            <span>Tentukan Cabang PKP</span>
        </div>

        <div class="field-group">
            <label for="kode_pkp_select" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                Pilih Cabang PKP
            </label>
            <div class="relative">
                <div class="input-prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-20 transition-colors">
                    <i data-lucide="building-2" class="w-3.5 h-3.5"></i>
                </div>
                <select name="kode_pkp" id="kode_pkp_select"
                    class="select2Setpkp select2-has-prefix w-full h-[36px] text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100">
                    <option value="">Non-PKP / Hapus Status PKP (Set NULL)</option>
                    @foreach($cabang as $c)
                        <option value="{{ $c->kode_cabang }}" {{ $penjualan->kode_pkp == $c->kode_cabang ? 'selected' : '' }}>
                            {{ strtoupper($c->kode_cabang) }} - {{ strtoupper($c->nama_cabang) }} {{ !empty($c->nama_pt) ? '(' . strtoupper($c->nama_pt) . ')' : '' }}
                        </option>
                    @endforeach
                </select>
            </div>
            <p class="text-[11px] text-slate-400 dark:text-slate-500 mt-1 pl-1">
                Pilih cabang badan usaha kena pajak yang menerbitkan faktur pajak untuk transaksi ini.
            </p>
        </div>
    </div>

    <!-- ACTION BUTTONS -->
    <div class="flex items-center justify-end gap-2 pt-1 border-t border-slate-100 dark:border-slate-800">
        <button type="button" class="btnCancel h-[34px] px-3.5 rounded-lg border border-slate-200 dark:border-slate-700 hover:bg-slate-100 dark:hover:bg-slate-800 text-slate-600 dark:text-slate-300 text-xs font-semibold transition cursor-pointer">
            Batal
        </button>
        <button type="submit" id="btnSubmitPkp"
            class="h-[34px] px-4 rounded-lg bg-brand-600 hover:bg-brand-700 active:bg-brand-800 text-white text-xs font-bold transition flex items-center gap-1.5 shadow-xs cursor-pointer">
            <i data-lucide="save" class="w-3.5 h-3.5"></i>
            <span>Simpan Cabang PKP</span>
        </button>
    </div>
</form>

<script>
    $(function() {
        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }

        const $form = $("#formSetpkp");
        const $btnSubmit = $form.find("#btnSubmitPkp");

        // Tutup modal via tombol Batal
        $form.find(".btnCancel").on('click', function() {
            $("#modalPenjualan").addClass("hidden").removeClass("flex");
        });

        // Initialize Select2 sesuai standar V2
        const $select2 = $form.find('.select2Setpkp');
        if ($select2.length) {
            $select2.select2({
                placeholder: 'Pilih Cabang PKP (atau kosongkan untuk Non-PKP)',
                allowClear: true,
                width: '100%'
            });
        }

        $form.on('submit', function() {
            $btnSubmit.prop('disabled', true).addClass('opacity-75 cursor-not-allowed pointer-events-none');
            $btnSubmit.html(`
                <svg class="animate-spin h-3.5 w-3.5 text-white shrink-0 inline-block" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <span>Menyimpan...</span>
            `);
        });
    });
</script>
