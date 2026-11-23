<form action="{{ route('ajuanlimit.adjuststore', Crypt::encrypt($ajuanlimit->no_pengajuan)) }}" id="formAdjustlimit" method="POST" class="space-y-3.5">
    @csrf

    <!-- DATA PENGAJUAN SUMMARY -->
    <div class="rounded-xl border border-slate-200/80 dark:border-slate-800 p-3 bg-slate-50/50 dark:bg-slate-900/40 space-y-1.5 text-xs">
        <div class="flex items-center gap-1.5 pb-2 border-b border-slate-100 dark:border-slate-800 text-xs font-bold text-slate-800 dark:text-slate-100">
            <i data-lucide="file-text" class="w-3.5 h-3.5 text-[#284c9a] dark:text-blue-400"></i>
            <span>Data Pengajuan Limit</span>
        </div>
        <div class="flex justify-between py-1 border-b border-slate-50 dark:border-slate-800/50">
            <span class="text-slate-400">No. Pengajuan</span>
            <span class="font-mono-num font-bold text-[#284c9a] dark:text-blue-400">{{ $ajuanlimit->no_pengajuan }}</span>
        </div>
        <div class="flex justify-between py-1 border-b border-slate-50 dark:border-slate-800/50">
            <span class="text-slate-400">Tanggal Pengajuan</span>
            <span class="font-semibold text-slate-800 dark:text-slate-200">{{ DateToIndo($ajuanlimit->tanggal) }}</span>
        </div>
        <div class="flex justify-between py-1 border-b border-slate-50 dark:border-slate-800/50">
            <span class="text-slate-400">Pelanggan</span>
            <span class="font-bold text-slate-800 dark:text-slate-100">{{ $ajuanlimit->kode_pelanggan }} - {{ $ajuanlimit->nama_pelanggan }}</span>
        </div>
        <div class="flex justify-between py-1 border-b border-slate-50 dark:border-slate-800/50">
            <span class="text-slate-400">Jumlah Ajuan Awal</span>
            <span class="font-mono-num font-black text-slate-800 dark:text-slate-200 text-sm">
                Rp {{ number_format($ajuanlimit->jumlah, 0, ',', '.') }}
            </span>
        </div>
        <div class="flex justify-between py-1">
            <span class="text-slate-400">Tenor LJT Awal</span>
            <span class="font-mono-num font-bold text-slate-800 dark:text-slate-200">{{ $ajuanlimit->ljt }} Hari</span>
        </div>
    </div>

    <!-- FORM REKOMENDASI PENYESUAIAN -->
    <div class="rounded-xl border border-blue-200 dark:border-blue-900/60 bg-blue-50/40 dark:bg-blue-950/30 p-3 space-y-2.5">
        <div class="flex items-center gap-1.5 pb-2 border-b border-blue-100 dark:border-blue-900/50 text-xs font-bold text-[#284c9a] dark:text-blue-400">
            <i data-lucide="sliders" class="w-3.5 h-3.5"></i>
            <span>Penyesuaian (Rekomendasi Wewenang)</span>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-2.5 items-start">
            <!-- Jumlah Rekomendasi -->
            <div class="field-group">
                <label for="jumlah_rekomendasi" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                    Limit Rekomendasi <span class="text-rose-500">*</span>
                </label>
                <div class="relative">
                    <div class="input-prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10 transition-colors">
                        <i data-lucide="coins" class="w-3.5 h-3.5"></i>
                    </div>
                    <input type="text" name="jumlah_rekomendasi" id="jumlah_rekomendasi" placeholder="0"
                        value="{{ !empty($ajuanlimit->jumlah_rekomendasi) ? number_format($ajuanlimit->jumlah_rekomendasi, 0, ',', '.') : number_format($ajuanlimit->jumlah, 0, ',', '.') }}"
                        class="money w-full h-[34px] pl-8 pr-2.5 text-xs text-right font-mono-num font-bold rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-2 focus:ring-[#284c9a]/20 focus:border-[#284c9a] transition">
                </div>
            </div>

            <!-- LJT Rekomendasi -->
            <div class="field-group">
                <label for="ljt_rekomendasi" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                    Tenor LJT Rekomendasi <span class="text-rose-500">*</span>
                </label>
                <div class="relative">
                    <div class="input-prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10 transition-colors">
                        <i data-lucide="clock" class="w-3.5 h-3.5"></i>
                    </div>
                    <select name="ljt_rekomendasi" id="ljt_rekomendasi"
                        class="w-full h-[34px] pl-8 pr-7 text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-2 focus:ring-[#284c9a]/20 focus:border-[#284c9a] transition appearance-none cursor-pointer">
                        <option value="">Pilih LJT</option>
                        <option value="14" {{ ($ajuanlimit->ljt_rekomendasi ?? $ajuanlimit->ljt) == '14' ? 'selected' : '' }}>14 Hari</option>
                        <option value="30" {{ ($ajuanlimit->ljt_rekomendasi ?? $ajuanlimit->ljt) == '30' ? 'selected' : '' }}>30 Hari</option>
                        <option value="45" {{ ($ajuanlimit->ljt_rekomendasi ?? $ajuanlimit->ljt) == '45' ? 'selected' : '' }}>45 Hari</option>
                    </select>
                    <div class="absolute inset-y-0 right-0 pr-2 flex items-center pointer-events-none text-slate-400">
                        <i data-lucide="chevron-down" class="w-3.5 h-3.5"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- ACTION BUTTONS -->
    <div class="pt-2 border-t border-slate-200/80 dark:border-slate-800 flex items-center justify-end gap-2">
        <button type="button" class="btn-close-modal h-[34px] px-4 rounded-lg bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-200 text-xs font-bold transition cursor-pointer">
            Batal
        </button>
        <button type="submit" id="btnSimpanAdjust"
            class="inline-flex items-center justify-center gap-1.5 h-[34px] px-4 rounded-lg bg-[#284c9a] hover:bg-[#1e3a78] active:bg-[#152a56] text-white text-xs font-bold transition shadow-xs cursor-pointer">
            <i data-lucide="save" class="w-3.5 h-3.5"></i>
            <span id="btnSimpanAdjustText">Simpan Penyesuaian</span>
        </button>
    </div>
</form>

<script>
    $(function() {
        const $form = $("#formAdjustlimit");

        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }

        if ($.fn.maskMoney) {
            $form.find(".money").maskMoney({
                thousands: '.',
                decimal: ',',
                precision: 0
            });
        } else if ($.fn.mask) {
            $form.find(".money").mask("#.##0", {
                reverse: true
            });
        }

        function showError($field, message) {
            $field.addClass('!border-rose-500 !ring-1 !ring-rose-500 focus:!border-rose-500 focus:!ring-rose-500/20');
            const $group = $field.closest('.field-group');
            $group.find('.input-prefix-icon').addClass('text-rose-500').removeClass('text-slate-400');
            $group.find('.field-error-msg').remove();
            $group.append(`
                <p class="field-error-msg text-[11px] font-semibold text-rose-600 dark:text-rose-400 mt-1 pl-1 flex items-center gap-1">
                    <svg class="w-3.5 h-3.5 shrink-0" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                    </svg>
                    <span>${message}</span>
                </p>
            `);
        }

        function clearError($field) {
            $field.removeClass('!border-rose-500 !ring-1 !ring-rose-500 focus:!border-rose-500 focus:!ring-rose-500/20');
            const $group = $field.closest('.field-group');
            $group.find('.input-prefix-icon').removeClass('text-rose-500').addClass('text-slate-400');
            $group.find('.field-error-msg').remove();
        }

        $form.on('input change', 'input, select', function() {
            clearError($(this));
        });

        $form.on('submit', function(e) {
            let hasError = false;
            let $firstError = null;
            const $jml = $form.find('#jumlah_rekomendasi');
            const $ljt = $form.find('#ljt_rekomendasi');

            let jmlVal = parseInt(($jml.val() || '0').replace(/\./g, '')) || 0;
            if (jmlVal <= 0) {
                showError($jml, "Limit rekomendasi harus lebih dari 0");
                hasError = true;
                if (!$firstError) $firstError = $jml;
            }

            if (!$ljt.val()) {
                showError($ljt, "Tenor LJT rekomendasi wajib dipilih");
                hasError = true;
                if (!$firstError) $firstError = $ljt;
            }

            if (hasError) {
                e.preventDefault();
                if ($firstError) $firstError.focus();
                return false;
            }

            const $btn = $form.find('#btnSimpanAdjust');
            $btn.prop('disabled', true).addClass('opacity-75 cursor-not-allowed pointer-events-none');
            $form.find('#btnSimpanAdjustText').html(`
                <svg class="animate-spin -ml-1 mr-2 h-3.5 w-3.5 text-white inline-block" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                Menyimpan...
            `);
        });
    });
</script>
