<form action="{{ route('laporankeuangan.cetakkartupiutangkaryawan') }}" id="formLapKartupiutangekskaryawan" target="_blank" method="POST" class="space-y-3.5" autocomplete="off">
    @csrf
    <input type="hidden" name="status_aktif_piutangkaryawan" value="EK">

    @hasanyrole($roles_show_cabang_pjp)
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 items-start">
            <div class="field-group">
                <label for="kode_cabang_kartupiutangekskaryawan" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                    Cabang
                </label>
                <div class="relative select2-container-v2">
                    <div class="input-prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-20 transition-colors">
                        <i data-lucide="building" class="w-4 h-4"></i>
                    </div>
                    <select name="kode_cabang_kartupiutangkaryawan" id="kode_cabang_kartupiutangekskaryawan" class="select2Kodecabangkartupiutangekskaryawan w-full">
                        <option value="">Semua Cabang</option>
                        @foreach ($cabang_pjp as $d)
                            <option value="{{ $d->kode_cabang }}">{{ textUpperCase($d->nama_cabang) }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="field-group">
                <label for="kode_dept_kartupiutangekskaryawan" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                    Departemen
                </label>
                <div class="relative select2-container-v2">
                    <div class="input-prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-20 transition-colors">
                        <i data-lucide="briefcase" class="w-4 h-4"></i>
                    </div>
                    <select name="kode_dept_kartupiutangkaryawan" id="kode_dept_kartupiutangekskaryawan" class="select2Kodedeptkartupiutangekskaryawan w-full">
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
            <label for="bulan_kartupiutangekskaryawan" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                Bulan <span class="text-rose-500">*</span>
            </label>
            <div class="relative">
                <div class="input-prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10 transition-colors">
                    <i data-lucide="calendar" class="w-4 h-4"></i>
                </div>
                <select name="bulan" id="bulan_kartupiutangekskaryawan"
                    class="w-full h-[36px] pl-9 pr-7 text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-2 focus:ring-[#284c9a]/20 focus:border-[#284c9a] transition appearance-none cursor-pointer">
                    <option value="">Pilih Bulan</option>
                    @foreach ($list_bulan as $d)
                        <option value="{{ $d['kode_bulan'] }}">{{ $d['nama_bulan'] }}</option>
                    @endforeach
                </select>
                <div class="absolute inset-y-0 right-0 pr-2.5 flex items-center pointer-events-none text-slate-400">
                    <i data-lucide="chevron-down" class="w-4 h-4"></i>
                </div>
            </div>
        </div>

        <div class="field-group">
            <label for="tahun_kartupiutangekskaryawan" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                Tahun <span class="text-rose-500">*</span>
            </label>
            <div class="relative">
                <div class="input-prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10 transition-colors">
                    <i data-lucide="calendar-days" class="w-4 h-4"></i>
                </div>
                <select name="tahun" id="tahun_kartupiutangekskaryawan"
                    class="w-full h-[36px] pl-9 pr-7 text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-2 focus:ring-[#284c9a]/20 focus:border-[#284c9a] transition appearance-none cursor-pointer">
                    <option value="">Pilih Tahun</option>
                    @for ($t = $start_year; $t <= date('Y'); $t++)
                        <option value="{{ $t }}">{{ $t }}</option>
                    @endfor
                </select>
                <div class="absolute inset-y-0 right-0 pr-2.5 flex items-center pointer-events-none text-slate-400">
                    <i data-lucide="chevron-down" class="w-4 h-4"></i>
                </div>
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
        const $form = $('#formLapKartupiutangekskaryawan');
        if ($.fn.select2) {
            $form.find('.select2Kodecabangkartupiutangekskaryawan').select2({ placeholder: 'Semua Cabang', allowClear: true, dropdownCssClass: 'select2-dropdown-v2' });
            $form.find('.select2Kodedeptkartupiutangekskaryawan').select2({ placeholder: 'Semua Departemen', allowClear: true, dropdownCssClass: 'select2-dropdown-v2' });
        }

        lapKeuHelper.bindLiveCleanup($form);

        $form.on('submit', function(e) {
            const $bulan = $form.find('#bulan_kartupiutangekskaryawan');
            const $tahun = $form.find('#tahun_kartupiutangekskaryawan');
            let hasError = false;
            let $firstError = null;

            if (!$bulan.val()) {
                lapKeuHelper.showError($bulan, 'Bulan wajib dipilih');
                hasError = true; if (!$firstError) $firstError = $bulan;
            }
            if (!$tahun.val()) {
                lapKeuHelper.showError($tahun, 'Tahun wajib dipilih');
                hasError = true; if (!$firstError) $firstError = $tahun;
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
