<form action="{{ route('ajuanlimit.update', Crypt::encrypt($ajuanlimit->no_pengajuan)) }}" method="POST" id="formEditAjuanLimit" class="space-y-3.5">
    @method('PUT')
    @csrf

    <div class="field-group">
        <label class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">No. Pengajuan</label>
        <div class="relative">
            <div class="input-prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                <i data-lucide="barcode" class="w-3.5 h-3.5"></i>
            </div>
            <input type="text" name="no_pengajuan" id="no_pengajuan_edit" value="{{ $ajuanlimit->no_pengajuan }}" disabled
                class="w-full h-[34px] pl-8 pr-2.5 text-xs font-mono-num font-bold rounded-lg border border-slate-200 dark:border-slate-700 bg-slate-100 dark:bg-slate-800 text-slate-500 cursor-not-allowed">
        </div>
    </div>

    @if ($level_user == 'super admin')
        <div class="field-group">
            <label for="posisi_ajuan_edit" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                Posisi Wewenang Approval <span class="text-rose-500">*</span>
            </label>
            <div class="relative">
                <div class="input-prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10 transition-colors">
                    <i data-lucide="shield-check" class="w-3.5 h-3.5"></i>
                </div>
                <select name="posisi_ajuan" id="posisi_ajuan_edit"
                    class="w-full h-[34px] pl-8 pr-7 text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-2 focus:ring-[#284c9a]/20 focus:border-[#284c9a] transition appearance-none cursor-pointer">
                    <option value="">Pilih Posisi Ajuan</option>
                    @foreach ($roles_approve as $role)
                        <option value="{{ $role }}" {{ $ajuanlimit->posisi_ajuan == $role ? 'selected' : '' }}>
                            {{ textUpperCase($role) }}
                        </option>
                    @endforeach
                    <option value="" {{ $ajuanlimit->posisi_ajuan == null && $ajuanlimit->status == '1' ? 'selected' : '' }}>
                        SUDAH DISETUJUI (SELESAI)
                    </option>
                </select>
                <div class="absolute inset-y-0 right-0 pr-2 flex items-center pointer-events-none text-slate-400">
                    <i data-lucide="chevron-down" class="w-3.5 h-3.5"></i>
                </div>
            </div>
        </div>
    @else
        <div class="rounded-lg p-2.5 bg-amber-50 dark:bg-amber-950/40 border border-amber-200 dark:border-amber-900/60 text-amber-800 dark:text-amber-300 text-xs flex items-center gap-2">
            <i data-lucide="alert-triangle" class="w-4 h-4 shrink-0 text-amber-500"></i>
            <span>Anda tidak memiliki akses untuk mengubah posisi ajuan ini.</span>
        </div>
    @endif

    <div class="pt-2 border-t border-slate-200/80 dark:border-slate-800 flex items-center justify-end gap-2">
        <button type="button" class="btn-close-modal h-[34px] px-4 rounded-lg bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-200 text-xs font-bold transition cursor-pointer">
            Batal
        </button>
        <button type="submit" id="btnUpdatePosisi"
            class="inline-flex items-center justify-center gap-1.5 h-[34px] px-4 rounded-lg bg-[#284c9a] hover:bg-[#1e3a78] active:bg-[#152a56] text-white text-xs font-bold transition shadow-xs cursor-pointer">
            <i data-lucide="save" class="w-3.5 h-3.5"></i>
            <span id="btnUpdatePosisiText">Update Posisi</span>
        </button>
    </div>
</form>

<script>
    $(function() {
        const $form = $("#formEditAjuanLimit");

        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
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

        $form.on('change', '#posisi_ajuan_edit', function() {
            clearError($(this));
        });

        $form.on('submit', function(e) {
            const $posisi = $form.find("#posisi_ajuan_edit");
            if ($posisi.length && !$posisi.val()) {
                // Posisi boleh kosong hanya jika sudah disetujui
                const isDisetujui = $posisi.find("option:selected").text().includes("SUDAH DISETUJUI");
                if (!isDisetujui) {
                    showError($posisi, "Silakan pilih posisi wewenang approval");
                    e.preventDefault();
                    $posisi.focus();
                    return false;
                }
            }

            const $btn = $form.find("#btnUpdatePosisi");
            $btn.prop('disabled', true).addClass('opacity-75 cursor-not-allowed pointer-events-none');
            $form.find("#btnUpdatePosisiText").html(`
                <svg class="animate-spin -ml-1 mr-2 h-3.5 w-3.5 text-white inline-block" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                Menyimpan...
            `);
        });
    });
</script>
