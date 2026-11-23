<form action="{{ route('ajuanfaktur.update', Crypt::encrypt($ajuanfaktur->no_pengajuan)) }}" id="formEditposisi" method="POST">
    @csrf
    @method('PUT')

    <div class="space-y-3">
        <!-- INFORMASI SINGKAT AJUAN -->
        <div class="rounded-lg border border-slate-200 dark:border-slate-800 p-2.5 bg-slate-50/50 dark:bg-slate-900/50 flex items-center justify-between">
            <div>
                <span class="text-[10px] uppercase font-bold text-slate-400 block">No. Pengajuan:</span>
                <span class="font-mono-num font-bold text-xs text-[#284c9a] dark:text-blue-400">{{ $ajuanfaktur->no_pengajuan }}</span>
            </div>
            <div class="text-right">
                <span class="text-[10px] uppercase font-bold text-slate-400 block">Status Saat Ini:</span>
                <span class="px-2 py-0.5 rounded text-[10.5px] font-bold bg-slate-200 dark:bg-slate-800 text-slate-700 dark:text-slate-300">
                    {{ $ajuanfaktur->posisi_ajuan ? textUpperCase($ajuanfaktur->posisi_ajuan) : 'Belum Ditentukan' }}
                </span>
            </div>
        </div>

        <!-- POSISI AJUAN BARU -->
        <div class="field-group">
            <label for="posisi_ajuan_edit" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                Pilih Posisi Ajuan Baru <span class="text-rose-500">*</span>
            </label>
            <div class="relative">
                <div class="input-prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10 transition-colors">
                    <i data-lucide="user-check" class="w-3.5 h-3.5"></i>
                </div>
                <select name="posisi_ajuan" id="posisi_ajuan_edit"
                    class="w-full h-[34px] pl-8 pr-7 text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-2 focus:ring-[#284c9a]/20 focus:border-[#284c9a] transition appearance-none cursor-pointer">
                    <option value="">Pilih Posisi Approval</option>
                    @foreach ($roles_approve as $role)
                        <option value="{{ $role }}" {{ $ajuanfaktur->posisi_ajuan == $role ? 'selected' : '' }}>
                            {{ textUpperCase($role) }}
                        </option>
                    @endforeach
                </select>
                <div class="absolute inset-y-0 right-0 pr-2 flex items-center pointer-events-none text-slate-400">
                    <i data-lucide="chevron-down" class="w-3.5 h-3.5"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- TOMBOL AKSI -->
    <div class="flex items-center justify-end gap-2 pt-3 mt-3 border-t border-slate-100 dark:border-slate-800">
        <button type="button" class="btn-close-modal h-[34px] px-3.5 rounded-lg border border-slate-200 dark:border-slate-700 hover:bg-slate-100 dark:hover:bg-slate-800 text-slate-600 dark:text-slate-300 text-xs font-bold transition cursor-pointer">
            Batal
        </button>
        <button type="submit" id="submitEditposisi"
            class="h-[34px] px-4 rounded-lg bg-[#284c9a] hover:bg-[#1e3a78] active:bg-[#152a56] text-white text-xs font-bold transition flex items-center justify-center gap-1.5 shadow-xs cursor-pointer">
            <i data-lucide="send" class="w-3.5 h-3.5"></i>
            <span id="btnEditPosisiText">Simpan Perubahan</span>
        </button>
    </div>
</form>

<script>
    $(function() {
        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }

        const $form = $("#formEditposisi");

        $form.on('submit', function(e) {
            const $posisi = $form.find("#posisi_ajuan_edit");
            if (!$posisi.val()) {
                e.preventDefault();
                $posisi.addClass('!border-rose-500 !ring-1 !ring-rose-500');
                $posisi.closest('.field-group').find('.input-prefix-icon').addClass('text-rose-500').removeClass('text-slate-400');
                return false;
            }

            const $btn = $form.find('#submitEditposisi');
            $btn.prop('disabled', true).addClass('opacity-75 cursor-not-allowed pointer-events-none');
            $form.find('#btnEditPosisiText').html(`
                <svg class="animate-spin -ml-1 mr-2 h-3.5 w-3.5 text-white inline-block" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                Menyimpan...
            `);
        });

        $form.find("#posisi_ajuan_edit").on('change', function() {
            $(this).removeClass('!border-rose-500 !ring-1 !ring-rose-500');
            $(this).closest('.field-group').find('.input-prefix-icon').removeClass('text-rose-500').addClass('text-slate-400');
        });
    });
</script>
