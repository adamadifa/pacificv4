<form action="{{ route('laporankeuangan.cetakrekapledger') }}" id="formLapRekapledger" target="_blank" method="POST" class="space-y-3.5" autocomplete="off">
    @csrf

    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 items-start">
        <div class="field-group">
            <label for="dari_rekapledger" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                Dari Tanggal <span class="text-rose-500">*</span>
            </label>
            <div class="relative">
                <div class="input-prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10 transition-colors">
                    <i data-lucide="calendar" class="w-4 h-4"></i>
                </div>
                <input type="text" name="dari" id="dari_rekapledger" placeholder="Pilih Tanggal" readonly
                    class="flatpickr-date w-full h-[36px] pl-9 pr-3 text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-2 focus:ring-[#284c9a]/20 focus:border-[#284c9a] transition cursor-pointer">
            </div>
        </div>

        <div class="field-group">
            <label for="sampai_rekapledger" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                Sampai Tanggal <span class="text-rose-500">*</span>
            </label>
            <div class="relative">
                <div class="input-prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10 transition-colors">
                    <i data-lucide="calendar-check" class="w-4 h-4"></i>
                </div>
                <input type="text" name="sampai" id="sampai_rekapledger" placeholder="Pilih Tanggal" readonly
                    class="flatpickr-date w-full h-[36px] pl-9 pr-3 text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-2 focus:ring-[#284c9a]/20 focus:border-[#284c9a] transition cursor-pointer">
            </div>
        </div>
    </div>

    <div class="pt-3 border-t border-slate-100 dark:border-slate-800 flex items-center gap-2">
        <button type="submit" name="submitButton"
            class="flex-1 h-[36px] inline-flex items-center justify-center gap-2 px-4 rounded-lg bg-[#284c9a] hover:bg-[#1e3a78] text-white text-xs font-bold transition shadow-xs cursor-pointer">
            <i data-lucide="printer" class="w-4 h-4"></i>
            <span>Cetak Laporan</span>
        </button>
        <button type="submit" name="exportButton"
            class="h-[36px] inline-flex items-center justify-center gap-2 px-4 rounded-lg bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold transition shadow-xs cursor-pointer" title="Export Excel">
            <i data-lucide="download" class="w-4 h-4"></i>
            <span class="hidden sm:inline">Export Excel</span>
        </button>
    </div>
</form>

@push('myscript')
<script>
    $(function() {
        const $form = $('#formLapRekapledger');
        $form.find('.flatpickr-date').flatpickr({ dateFormat: 'Y-m-d' });
        lapKeuHelper.bindLiveCleanup($form);

        $form.on('submit', function(e) {
            const $dari = $form.find('#dari_rekapledger');
            const $sampai = $form.find('#sampai_rekapledger');
            let hasError = false;
            let $firstError = null;

            if (!$dari.val().trim()) {
                lapKeuHelper.showError($dari, 'Periode dari wajib diisi');
                hasError = true; if (!$firstError) $firstError = $dari;
            }
            if (!$sampai.val().trim()) {
                lapKeuHelper.showError($sampai, 'Periode sampai wajib diisi');
                hasError = true; if (!$firstError) $firstError = $sampai;
            } else if ($dari.val() && new Date($dari.val()) > new Date($sampai.val())) {
                lapKeuHelper.showError($sampai, 'Periode tidak valid');
                hasError = true; if (!$firstError) $firstError = $sampai;
            }

            if (hasError) {
                e.preventDefault();
                if ($firstError) $firstError.focus();
                return false;
            }
            lapKeuHelper.triggerPrintSpinner($form);
        });
    });
</script>
@endpush
