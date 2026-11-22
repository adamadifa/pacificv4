@php
    $bulan_pilihan = collect($list_bulan)->map(function ($item) {
        return (object) $item;
    });

    $tahun_pilihan = [];
    for ($t = $start_year; $t <= date('Y'); $t++) {
        $tahun_pilihan[] = (object) ['tahun' => $t];
    }
@endphp

<form action="{{ route('laporanmtc.cetakbahanbakar') }}" method="POST" id="formLapBahanBakar" target="_blank" class="space-y-3.5" autocomplete="off">
    @csrf

    <!-- Pilih Barang -->
    <div class="field-group">
        <label for="kode_barang_lap" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
            Pilih Barang <span class="text-rose-500">*</span>
        </label>
        <div class="relative">
            <div class="input-prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10 transition-colors">
                <i data-lucide="box" class="w-4 h-4"></i>
            </div>
            <select name="kode_barang" id="kode_barang_lap"
                class="w-full h-[36px] pl-9 pr-7 text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition appearance-none cursor-pointer">
                <option value="">Pilih Jenis Bahan Bakar</option>
                @foreach ($barang as $b)
                    <option value="{{ $b->kode_barang }}">{{ $b->kode_barang }} - {{ $b->nama_barang }}</option>
                @endforeach
            </select>
            <div class="absolute inset-y-0 right-0 pr-2.5 flex items-center pointer-events-none text-slate-400">
                <i data-lucide="chevron-down" class="w-4 h-4"></i>
            </div>
        </div>
    </div>

    <!-- Bulan & Tahun Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 items-start">
        
        <!-- Bulan -->
        <div class="field-group">
            <label for="bulan_lap" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                Bulan <span class="text-rose-500">*</span>
            </label>
            <div class="relative">
                <div class="input-prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10 transition-colors">
                    <i data-lucide="calendar" class="w-4 h-4"></i>
                </div>
                <select name="bulan" id="bulan_lap"
                    class="w-full h-[36px] pl-9 pr-7 text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition appearance-none cursor-pointer">
                    <option value="">Pilih Bulan</option>
                    @foreach ($bulan_pilihan as $bln)
                        <option value="{{ $bln->kode_bulan }}" {{ date('m') == $bln->kode_bulan ? 'selected' : '' }}>
                            {{ $bln->nama_bulan }}
                        </option>
                    @endforeach
                </select>
                <div class="absolute inset-y-0 right-0 pr-2.5 flex items-center pointer-events-none text-slate-400">
                    <i data-lucide="chevron-down" class="w-4 h-4"></i>
                </div>
            </div>
        </div>

        <!-- Tahun -->
        <div class="field-group">
            <label for="tahun_lap" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                Tahun <span class="text-rose-500">*</span>
            </label>
            <div class="relative">
                <div class="input-prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10 transition-colors">
                    <i data-lucide="calendar-days" class="w-4 h-4"></i>
                </div>
                <select name="tahun" id="tahun_lap"
                    class="w-full h-[36px] pl-9 pr-7 text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition appearance-none cursor-pointer">
                    <option value="">Pilih Tahun</option>
                    @foreach ($tahun_pilihan as $thn)
                        <option value="{{ $thn->tahun }}" {{ date('Y') == $thn->tahun ? 'selected' : '' }}>
                            {{ $thn->tahun }}
                        </option>
                    @endforeach
                </select>
                <div class="absolute inset-y-0 right-0 pr-2.5 flex items-center pointer-events-none text-slate-400">
                    <i data-lucide="chevron-down" class="w-4 h-4"></i>
                </div>
            </div>
        </div>

    </div>

    <!-- Tombol Cetak & Export -->
    <div class="pt-3 border-t border-slate-100 dark:border-slate-800 flex items-center gap-2">
        <button type="submit" name="submitButton" id="btnCetakBahanBakar"
            class="flex-1 h-[36px] inline-flex items-center justify-center gap-2 px-4 rounded-lg bg-[#284c9a] hover:bg-blue-800 active:bg-blue-900 text-white text-xs font-bold transition shadow-xs cursor-pointer">
            <i data-lucide="printer" class="w-4 h-4"></i>
            <span class="btn-text">Cetak Laporan</span>
        </button>

        <button type="submit" name="exportButton" id="btnExportBahanBakar"
            class="h-[36px] inline-flex items-center justify-center gap-2 px-4 rounded-lg bg-emerald-600 hover:bg-emerald-700 active:bg-emerald-800 text-white text-xs font-bold transition shadow-xs cursor-pointer"
            title="Download Excel">
            <i data-lucide="download" class="w-4 h-4"></i>
            <span class="btn-text hidden sm:inline">Export Excel</span>
        </button>
    </div>
</form>

<script>
    $(function() {
        const $form = $('#formLapBahanBakar');

        function showError($field, message) {
            $field.addClass('border-rose-500 ring-1 ring-rose-500');
            const $group = $field.closest('.field-group');
            $group.find('.input-prefix-icon').addClass('text-rose-500');
            $group.find('.field-error-msg').remove();
            $group.append(`
                <p class="field-error-msg text-[11px] font-semibold text-rose-600 dark:text-rose-400 mt-1 pl-1 flex items-center gap-1">
                    <svg class="w-3 h-3 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                    </svg>
                    <span>${message}</span>
                </p>
            `);
        }

        function clearError($field) {
            $field.removeClass('border-rose-500 ring-1 ring-rose-500');
            const $group = $field.closest('.field-group');
            $group.find('.input-prefix-icon').removeClass('text-rose-500');
            $group.find('.field-error-msg').remove();
        }

        // Live cleanup on change
        $form.on('input change', 'select', function() {
            clearError($(this));
        });

        // Form Submission with validation & temporary 2.5s spinner
        $form.on('submit', function(e) {
            const $barang = $form.find('#kode_barang_lap');
            const $bulan = $form.find('#bulan_lap');
            const $tahun = $form.find('#tahun_lap');
            let hasError = false;

            if (!$barang.val()) {
                showError($barang, 'Pilih jenis bahan bakar');
                hasError = true;
            }

            if (!$bulan.val()) {
                showError($bulan, 'Pilih periode bulan');
                hasError = true;
            }

            if (!$tahun.val()) {
                showError($tahun, 'Pilih periode tahun');
                hasError = true;
            }

            if (hasError) {
                e.preventDefault();
                return false;
            }

            // Interactive temporary spinner (timeout 2.5s because target=_blank)
            const $btnSubmit = $form.find('#btnCetakBahanBakar');
            const $btnExport = $form.find('#btnExportBahanBakar');
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
                if (typeof lucide !== 'undefined') {
                    lucide.createIcons();
                }
            }, 2500);
        });
    });
</script>
