<form action="{{ route('laporankeuangan.cetakpinjaman') }}" id="formLapPinjaman" target="_blank" method="POST" class="space-y-3.5" autocomplete="off">
    @csrf

    @hasanyrole($roles_show_cabang_pjp)
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 items-start">
            <div class="field-group">
                <label for="kode_cabang_pinjaman" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                    Cabang
                </label>
                <div class="relative select2-container-v2">
                    <div class="input-prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-20 transition-colors">
                        <i data-lucide="building" class="w-4 h-4"></i>
                    </div>
                    <select name="kode_cabang_pinjaman" id="kode_cabang_pinjaman" class="select2Kodecabangpinjaman w-full">
                        <option value="">Semua Cabang</option>
                        @foreach ($cabang_pjp as $d)
                            <option value="{{ $d->kode_cabang }}">{{ textUpperCase($d->nama_cabang) }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="field-group">
                <label for="kode_dept_pinjaman" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                    Departemen
                </label>
                <div class="relative select2-container-v2">
                    <div class="input-prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-20 transition-colors">
                        <i data-lucide="briefcase" class="w-4 h-4"></i>
                    </div>
                    <select name="kode_dept_pinjaman" id="kode_dept_pinjaman" class="select2Kodedeptpinjaman w-full">
                        <option value="">Semua Departemen</option>
                        @foreach ($departemen_pjp as $d)
                            <option value="{{ $d->kode_dept }}">{{ textUpperCase($d->nama_dept) }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
    @endhasanyrole

    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 items-start">
        <div class="field-group">
            <label for="dari_pinjaman" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                Dari Tanggal <span class="text-rose-500">*</span>
            </label>
            <div class="relative">
                <div class="input-prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10 transition-colors">
                    <i data-lucide="calendar" class="w-4 h-4"></i>
                </div>
                <input type="text" name="dari" id="dari_pinjaman" placeholder="Pilih Tanggal" readonly
                    class="flatpickr-date w-full h-[36px] pl-9 pr-3 text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-2 focus:ring-[#284c9a]/20 focus:border-[#284c9a] transition cursor-pointer">
            </div>
        </div>

        <div class="field-group">
            <label for="sampai_pinjaman" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                Sampai Tanggal <span class="text-rose-500">*</span>
            </label>
            <div class="relative">
                <div class="input-prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10 transition-colors">
                    <i data-lucide="calendar-check" class="w-4 h-4"></i>
                </div>
                <input type="text" name="sampai" id="sampai_pinjaman" placeholder="Pilih Tanggal" readonly
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
        const $form = $('#formLapPinjaman');
        $form.find('.flatpickr-date').flatpickr({ dateFormat: 'Y-m-d' });
        if ($.fn.select2) {
            $form.find('.select2Kodecabangpinjaman').select2({ placeholder: 'Semua Cabang', allowClear: true, dropdownCssClass: 'select2-dropdown-v2' });
            $form.find('.select2Kodedeptpinjaman').select2({ placeholder: 'Semua Departemen', allowClear: true, dropdownCssClass: 'select2-dropdown-v2' });
        }

        lapKeuHelper.bindLiveCleanup($form);

        $form.on('submit', function(e) {
            const $dari = $form.find('#dari_pinjaman');
            const $sampai = $form.find('#sampai_pinjaman');
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
                lapKeuHelper.showError($sampai, 'Periode sampai harus lebih besar dari periode dari');
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
