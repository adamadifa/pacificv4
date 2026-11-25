<form action="{{ route('laporankeuangan.cetakledger') }}" id="formLapLedger" target="_blank" method="POST" class="space-y-3.5" autocomplete="off">
    @csrf

    <div class="field-group">
        <label for="kode_bank_ledgers" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
            Bank / Ledger
        </label>
        <div class="relative select2-container-v2">
            <div class="input-prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-20 transition-colors">
                <i data-lucide="landmark" class="w-4 h-4"></i>
            </div>
            <select name="kode_bank_ledger" id="kode_bank_ledgers" class="select2Kodebankledgers w-full">
                <option value="">Ledger</option>
                @foreach ($bank as $d)
                    <option {{ Request('kode_bank_search') == $d->kode_bank ? 'selected' : '' }} value="{{ $d->kode_bank }}">
                        {{ $d->nama_bank }} ({{ $d->no_rekening }})
                    </option>
                @endforeach
            </select>
        </div>
    </div>

    <div class="field-group">
        <label for="formatlaporan_ledger" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
            Format Laporan <span class="text-rose-500">*</span>
        </label>
        <div class="relative">
            <div class="input-prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10 transition-colors">
                <i data-lucide="file-spreadsheet" class="w-4 h-4"></i>
            </div>
            <select name="formatlaporan" id="formatlaporan_ledger"
                class="w-full h-[36px] pl-9 pr-7 text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-2 focus:ring-[#284c9a]/20 focus:border-[#284c9a] transition appearance-none cursor-pointer">
                <option value="">Pilih Format Laporan</option>
                <option value="1">Detail</option>
                <option value="2">Rekap</option>
            </select>
            <div class="absolute inset-y-0 right-0 pr-2.5 flex items-center pointer-events-none text-slate-400">
                <i data-lucide="chevron-down" class="w-4 h-4"></i>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 items-start" id="coa_ledger_group" style="display: none;">
        <div class="field-group">
            <label for="kode_akun_dari_ledger" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                Dari Akun
            </label>
            <div class="relative select2-container-v2">
                <div class="input-prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-20 transition-colors">
                    <i data-lucide="layers" class="w-4 h-4"></i>
                </div>
                <select name="kode_akun_dari" id="kode_akun_dari_ledger" class="select2Kodeakundari w-full">
                    <option value="">Semua Akun</option>
                    @foreach ($coa as $d)
                        <option value="{{ $d->kode_akun }}">{{ $d->kode_akun }} {{ $d->nama_akun }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="field-group">
            <label for="kode_akun_sampai_ledger" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                Sampai Akun
            </label>
            <div class="relative select2-container-v2">
                <div class="input-prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-20 transition-colors">
                    <i data-lucide="layers" class="w-4 h-4"></i>
                </div>
                <select name="kode_akun_sampai" id="kode_akun_sampai_ledger" class="select2Kodeakunsampai w-full">
                    <option value="">Semua Akun</option>
                    @foreach ($coa as $d)
                        <option value="{{ $d->kode_akun }}">{{ $d->kode_akun }} {{ $d->nama_akun }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 items-start">
        <div class="field-group">
            <label for="dari_ledger" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                Dari Tanggal <span class="text-rose-500">*</span>
            </label>
            <div class="relative">
                <div class="input-prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10 transition-colors">
                    <i data-lucide="calendar" class="w-4 h-4"></i>
                </div>
                <input type="text" name="dari" id="dari_ledger" placeholder="Pilih Tanggal" readonly
                    class="flatpickr-date w-full h-[36px] pl-9 pr-3 text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-2 focus:ring-[#284c9a]/20 focus:border-[#284c9a] transition cursor-pointer">
            </div>
        </div>

        <div class="field-group">
            <label for="sampai_ledger" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                Sampai Tanggal <span class="text-rose-500">*</span>
            </label>
            <div class="relative">
                <div class="input-prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10 transition-colors">
                    <i data-lucide="calendar-check" class="w-4 h-4"></i>
                </div>
                <input type="text" name="sampai" id="sampai_ledger" placeholder="Pilih Tanggal" readonly
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
        const $form = $('#formLapLedger');
        $form.find('.flatpickr-date').flatpickr({ dateFormat: 'Y-m-d' });

        if ($.fn.select2) {
            $form.find('.select2Kodebankledgers').select2({ placeholder: 'Ledger', allowClear: true, dropdownCssClass: 'select2-dropdown-v2' });
            $form.find('.select2Kodeakundari').select2({ placeholder: 'Semua Akun', allowClear: true, dropdownCssClass: 'select2-dropdown-v2' });
            $form.find('.select2Kodeakunsampai').select2({ placeholder: 'Semua Akun', allowClear: true, dropdownCssClass: 'select2-dropdown-v2' });
        }

        function toggleCoaLedger() {
            if ($form.find('#formatlaporan_ledger').val() == '1') {
                $form.find('#coa_ledger_group').show();
            } else {
                $form.find('#coa_ledger_group').hide();
            }
        }
        toggleCoaLedger();
        $form.find('#formatlaporan_ledger').on('change', toggleCoaLedger);

        lapKeuHelper.bindLiveCleanup($form);

        $form.on('submit', function(e) {
            const $fmt = $form.find('#formatlaporan_ledger');
            const $dari = $form.find('#dari_ledger');
            const $sampai = $form.find('#sampai_ledger');
            let hasError = false;
            let $firstError = null;

            if (!$fmt.val()) {
                lapKeuHelper.showError($fmt, 'Jenis laporan wajib dipilih');
                hasError = true; if (!$firstError) $firstError = $fmt;
            }
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
