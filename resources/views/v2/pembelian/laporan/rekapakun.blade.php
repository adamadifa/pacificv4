<form action="{{ route('laporanpembelian.cetakrekapakun') }}" method="POST" id="formLapRekapAkun" target="_blank" class="space-y-3.5" autocomplete="off">
    @csrf

    <!-- PPN -->
    <div class="field-group">
        <label for="ppn_rekapakun" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
            Kategori PPN
        </label>
        <div class="relative">
            <div class="input-prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10 transition-colors">
                <i data-lucide="percent" class="w-4 h-4"></i>
            </div>
            <select name="ppn" id="ppn_rekapakun"
                class="w-full h-[36px] pl-9 pr-7 text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-2 focus:ring-[#284c9a]/20 focus:border-[#284c9a] transition appearance-none cursor-pointer">
                <option value="">Semua (PPN & Non PPN)</option>
                <option value="1">PPN</option>
                <option value="0">NON PPN</option>
            </select>
            <div class="absolute inset-y-0 right-0 pr-2.5 flex items-center pointer-events-none text-slate-400">
                <i data-lucide="chevron-down" class="w-4 h-4"></i>
            </div>
        </div>
    </div>

    <!-- Periode Tanggal Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 items-start">
        <!-- Dari -->
        <div class="field-group">
            <label for="dari_rekapakun" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                Dari Tanggal <span class="text-rose-500">*</span>
            </label>
            <div class="relative">
                <div class="input-prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10 transition-colors">
                    <i data-lucide="calendar" class="w-4 h-4"></i>
                </div>
                <input type="text" name="dari" id="dari_rekapakun" placeholder="Pilih Tanggal" readonly
                    class="flatpickr-date w-full h-[36px] pl-8 pr-3 text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-2 focus:ring-[#284c9a]/20 focus:border-[#284c9a] transition cursor-pointer">
            </div>
        </div>

        <!-- Sampai -->
        <div class="field-group">
            <label for="sampai_rekapakun" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                Sampai Tanggal <span class="text-rose-500">*</span>
            </label>
            <div class="relative">
                <div class="input-prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10 transition-colors">
                    <i data-lucide="calendar-check" class="w-4 h-4"></i>
                </div>
                <input type="text" name="sampai" id="sampai_rekapakun" placeholder="Pilih Tanggal" readonly
                    class="flatpickr-date w-full h-[36px] pl-8 pr-3 text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-2 focus:ring-[#284c9a]/20 focus:border-[#284c9a] transition cursor-pointer">
            </div>
        </div>
    </div>

    <!-- Tombol Cetak & Export -->
    <div class="pt-3 border-t border-slate-100 dark:border-slate-800 flex items-center gap-2">
        <button type="submit" name="submitButton" id="btnCetakRekapAkun"
            class="flex-1 h-[36px] inline-flex items-center justify-center gap-2 px-4 rounded-lg bg-[#284c9a] hover:bg-[#1e3a78] active:bg-[#152852] text-white text-xs font-bold transition shadow-xs cursor-pointer">
            <i data-lucide="printer" class="w-4 h-4"></i>
            <span class="btn-text">Cetak Laporan</span>
        </button>

        <button type="submit" name="exportButton" id="btnExportRekapAkun"
            class="h-[36px] inline-flex items-center justify-center gap-2 px-4 rounded-lg bg-emerald-600 hover:bg-emerald-700 active:bg-emerald-800 text-white text-xs font-bold transition shadow-xs cursor-pointer"
            title="Download Excel">
            <i data-lucide="download" class="w-4 h-4"></i>
            <span class="btn-text hidden sm:inline">Export Excel</span>
        </button>
    </div>
</form>

<script>
    $(function() {
        const $form = $('#formLapRekapAkun');

        // Flatpickr
        $form.find(".flatpickr-date").flatpickr({
            dateFormat: "Y-m-d"
        });

        function showError($field, message) {
            $field.addClass('border-rose-500 ring-1 ring-rose-500');
            const $group = $field.closest('.field-group');
            $group.find('.input-prefix-icon').addClass('text-rose-500');
            $group.find('.field-error-msg').remove();
            $group.append(`
                <p class="field-error-msg text-[11px] font-semibold text-rose-600 dark:text-rose-400 mt-1 pl-1 flex items-center gap-1">
                    <i data-lucide="alert-circle" class="w-3.5 h-3.5 shrink-0"></i>
                    <span>${message}</span>
                </p>
            `);
            if (typeof lucide !== 'undefined') lucide.createIcons();
            $field.focus();
        }

        function clearError($field) {
            $field.removeClass('border-rose-500 ring-1 ring-rose-500');
            const $group = $field.closest('.field-group');
            $group.find('.input-prefix-icon').removeClass('text-rose-500');
            $group.find('.field-error-msg').remove();
        }

        // Live cleanup on change
        $form.on('input change', 'input, select', function() {
            clearError($(this));
        });

        // Form Submission with interactive inline validation
        $form.on('submit', function(e) {
            const $dari = $form.find('#dari_rekapakun');
            const $sampai = $form.find('#sampai_rekapakun');
            const dariVal = $dari.val().trim();
            const sampaiVal = $sampai.val().trim();
            let hasError = false;

            if (!dariVal) {
                showError($dari, 'Periode tanggal mulai wajib diisi');
                hasError = true;
            }

            if (!sampaiVal) {
                showError($sampai, 'Periode tanggal akhir wajib diisi');
                hasError = true;
            } else if (dariVal && sampaiVal && new Date(dariVal) > new Date(sampaiVal)) {
                showError($sampai, 'Tanggal akhir harus lebih besar atau sama dengan tanggal awal');
                hasError = true;
            }

            if (hasError) {
                e.preventDefault();
                return false;
            }

            // Interactive temporary spinner (timeout 2.5s for target=_blank)
            const $btnSubmit = $form.find('#btnCetakRekapAkun');
            const $btnExport = $form.find('#btnExportRekapAkun');
            const originalSubmitHtml = $btnSubmit.html();
            const originalExportHtml = $btnExport.html();

            $btnSubmit.prop('disabled', true).addClass('opacity-75 pointer-events-none');
            $btnExport.prop('disabled', true).addClass('opacity-75 pointer-events-none');

            $btnSubmit.html(`
                <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white inline-block" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <span>Mencetak...</span>
            `);

            setTimeout(function() {
                $btnSubmit.prop('disabled', false).removeClass('opacity-75 pointer-events-none').html(originalSubmitHtml);
                $btnExport.prop('disabled', false).removeClass('opacity-75 pointer-events-none').html(originalExportHtml);
                if (typeof lucide !== 'undefined') lucide.createIcons();
            }, 2500);
        });
    });
</script>
