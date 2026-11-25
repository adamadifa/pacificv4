<form action="{{ route('slipgaji.store') }}" method="POST" id="formSlipGajiCreate" autocomplete="off" class="space-y-2.5">
    @csrf

    <div>
        <label for="bulan_create" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
            Bulan <span class="text-rose-500">*</span>
        </label>
        <div class="relative">
            <div class="prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                <i data-lucide="calendar" class="w-4 h-4"></i>
            </div>
            <select name="bulan" id="bulan_create"
                class="w-full h-[36px] pl-9 pr-8 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-950 text-xs font-medium text-slate-700 dark:text-slate-200 focus:outline-none focus:border-[#284c9a] focus:ring-2 focus:ring-[#284c9a]/15 transition">
                <option value="">Pilih Bulan</option>
                @foreach ($list_bulan as $d)
                    <option value="{{ $d['kode_bulan'] }}">{{ $d['nama_bulan'] }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <div>
        <label for="tahun_create" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
            Tahun <span class="text-rose-500">*</span>
        </label>
        <div class="relative">
            <div class="prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                <i data-lucide="calendar-range" class="w-4 h-4"></i>
            </div>
            <select name="tahun" id="tahun_create"
                class="w-full h-[36px] pl-9 pr-8 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-950 text-xs font-medium text-slate-700 dark:text-slate-200 focus:outline-none focus:border-[#284c9a] focus:ring-2 focus:ring-[#284c9a]/15 transition">
                <option value="">Pilih Tahun</option>
                @for ($t = $start_year; $t <= date('Y'); $t++)
                    <option value="{{ $t }}">{{ $t }}</option>
                @endfor
            </select>
        </div>
    </div>

    <div>
        <label for="status_create" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
            Status <span class="text-rose-500">*</span>
        </label>
        <div class="relative">
            <div class="prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                <i data-lucide="check-circle-2" class="w-4 h-4"></i>
            </div>
            <select name="status" id="status_create"
                class="w-full h-[36px] pl-9 pr-8 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-950 text-xs font-medium text-slate-700 dark:text-slate-200 focus:outline-none focus:border-[#284c9a] focus:ring-2 focus:ring-[#284c9a]/15 transition">
                <option value="">Pilih Status</option>
                <option value="1">Publish</option>
                <option value="0">Pending</option>
            </select>
        </div>
    </div>

    <div class="flex items-center justify-end gap-2 pt-2 border-t border-slate-100 dark:border-slate-800">
        <button type="button" onclick="window.closeModalSlipGaji && window.closeModalSlipGaji()"
            class="h-[36px] px-4 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-xs font-bold text-slate-600 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-700 transition cursor-pointer">
            Batal
        </button>
        <button type="submit" id="btnSimpanSlipGajiCreate"
            class="h-[36px] px-4 rounded-lg bg-[#284c9a] hover:bg-[#1e3a78] text-white text-xs font-bold shadow-xs transition flex items-center justify-center gap-1.5 cursor-pointer">
            <i data-lucide="save" class="w-3.5 h-3.5"></i>
            <span>Buat Slip Gaji</span>
        </button>
    </div>
</form>

<script>
    $(function() {
        const $form = $('#formSlipGajiCreate');

        function clearError($el) {
            $el.removeClass('border-rose-500 ring-1 ring-rose-500 focus:border-rose-500 focus:ring-rose-500/20');
            const $rel = $el.closest('.relative');
            $rel.find('.prefix-icon').removeClass('text-rose-500').addClass('text-slate-400');
            $rel.siblings('.field-error-msg').remove();
        }

        function showError($el, msg) {
            clearError($el);
            $el.addClass('border-rose-500 ring-1 ring-rose-500 focus:border-rose-500 focus:ring-rose-500/20');
            const $rel = $el.closest('.relative');
            $rel.find('.prefix-icon').removeClass('text-slate-400').addClass('text-rose-500');
            $rel.after(`
                <p class="field-error-msg text-[11px] font-semibold text-rose-600 dark:text-rose-400 mt-1 pl-1 flex items-center gap-1">
                    <svg class="w-3.5 h-3.5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>
                    <span>${msg}</span>
                </p>
            `);
        }

        $form.find('select').on('change', function() { clearError($(this)); });

        $form.on('submit', function(e) {
            const $bulan = $form.find('#bulan_create');
            const $tahun = $form.find('#tahun_create');
            const $status = $form.find('#status_create');
            let firstError = null;

            if (!$bulan.val()) { showError($bulan, 'Bulan wajib dipilih.'); if (!firstError) firstError = $bulan; }
            if (!$tahun.val()) { showError($tahun, 'Tahun wajib dipilih.'); if (!firstError) firstError = $tahun; }
            if ($status.val() === '') { showError($status, 'Status wajib dipilih.'); if (!firstError) firstError = $status; }

            if (firstError) { e.preventDefault(); firstError.focus(); return false; }

            const $btn = $form.find('#btnSimpanSlipGajiCreate');
            $btn.prop('disabled', true).addClass('opacity-75 cursor-not-allowed pointer-events-none');
            $btn.html(`<svg class="animate-spin h-3.5 w-3.5 text-white shrink-0 inline-block" viewBox="0 0 24 24" fill="none"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg><span>Menyimpan...</span>`);
        });
    });
</script>
