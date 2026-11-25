<form action="{{ route('coacabang.update', Crypt::encrypt($coaCabang->id)) }}" method="POST" id="formEditCoaCabang" class="space-y-3" autocomplete="off">
    @csrf
    @method('PUT')

    <!-- Cabang -->
    <div class="space-y-1">
        <label for="kode_cabang_coacabang_edit" class="block text-xs font-bold text-slate-700 dark:text-slate-200">
            Cabang <span class="text-rose-500">*</span>
        </label>
        <div class="relative select2-container-v2">
            <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-20">
                <i data-lucide="building" class="w-4 h-4"></i>
            </div>
            <select name="kode_cabang" id="kode_cabang_coacabang_edit" class="select2CabangCoaEdit w-full">
                <option value="">Pilih Cabang</option>
                @foreach ($cabang as $d)
                    <option value="{{ $d->kode_cabang }}" @selected($coaCabang->kode_cabang == $d->kode_cabang)>
                        {{ $d->kode_cabang }} - {{ textUpperCase($d->nama_cabang) }}
                    </option>
                @endforeach
            </select>
        </div>
        <p id="error-cabang-coacabang-edit" class="hidden items-center gap-1 text-[11px] font-semibold text-rose-600 dark:text-rose-400 mt-1 pl-1">
            <i data-lucide="alert-circle" class="w-3.5 h-3.5 shrink-0"></i>
            <span>Cabang wajib dipilih</span>
        </p>
    </div>

    <!-- Kode Akun -->
    <div class="space-y-1">
        <label for="kode_akun_coacabang_edit" class="block text-xs font-bold text-slate-700 dark:text-slate-200">
            Kode Akun <span class="text-rose-500">*</span>
        </label>
        <div class="relative select2-container-v2">
            <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-20">
                <i data-lucide="layers" class="w-4 h-4"></i>
            </div>
            <select name="kode_akun" id="kode_akun_coacabang_edit" class="select2KodeakunCoaEdit w-full">
                <option value="">Pilih Kode Akun</option>
                @foreach ($coa as $d)
                    <option value="{{ $d->kode_akun }}" @selected($coaCabang->kode_akun == $d->kode_akun)>
                        {{ $d->kode_akun }} - {{ $d->nama_akun }}
                    </option>
                @endforeach
            </select>
        </div>
        <p id="error-akun-coacabang-edit" class="hidden items-center gap-1 text-[11px] font-semibold text-rose-600 dark:text-rose-400 mt-1 pl-1">
            <i data-lucide="alert-circle" class="w-3.5 h-3.5 shrink-0"></i>
            <span>Kode akun wajib dipilih</span>
        </p>
    </div>

    <!-- Tombol Aksi Kanan (Right-Aligned) -->
    <div class="flex items-center justify-end gap-2 pt-2 border-t border-slate-100 dark:border-slate-800">
        <button type="button" id="btnCancelCoaCabangEdit"
            class="h-[36px] px-4 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 hover:bg-slate-100 dark:hover:bg-slate-700 text-slate-600 dark:text-slate-300 text-xs font-bold transition cursor-pointer">
            Batal
        </button>
        <button type="submit" id="btnSimpanCoaCabangEdit"
            class="h-[36px] px-4 rounded-lg bg-[#284c9a] hover:bg-[#1e3a78] text-white text-xs font-bold transition flex items-center gap-1.5 shadow-xs cursor-pointer">
            <i data-lucide="save" class="w-3.5 h-3.5"></i>
            <span>Simpan Perubahan</span>
        </button>
    </div>
</form>

<script>
    $(function() {
        if (typeof lucide !== 'undefined') lucide.createIcons();

        const $form = $("#formEditCoaCabang");

        const $select2Cabang = $form.find('.select2CabangCoaEdit');
        if ($select2Cabang.length) {
            $select2Cabang.select2({
                placeholder: 'Pilih Cabang',
                allowClear: true,
                dropdownCssClass: 'select2-dropdown-v2'
            });
        }

        const $select2Akun = $form.find('.select2KodeakunCoaEdit');
        if ($select2Akun.length) {
            $select2Akun.select2({
                placeholder: 'Pilih Kode Akun',
                allowClear: true,
                dropdownCssClass: 'select2-dropdown-v2'
            });
        }

        $form.find("#kode_cabang_coacabang_edit").on('change select2:select', function() {
            $form.find("#error-cabang-coacabang-edit").addClass('hidden').removeClass('flex');
            $(this).next('.select2-container').find('.select2-selection').removeClass('!border-rose-500 !ring-1 !ring-rose-500');
        });

        $form.find("#kode_akun_coacabang_edit").on('change select2:select', function() {
            $form.find("#error-akun-coacabang-edit").addClass('hidden').removeClass('flex');
            $(this).next('.select2-container').find('.select2-selection').removeClass('!border-rose-500 !ring-1 !ring-rose-500');
        });

        $form.find("#btnCancelCoaCabangEdit").on('click', function() {
            if (typeof modalCoaCabangHelper !== 'undefined') {
                modalCoaCabangHelper.close();
            }
        });

        $form.on('submit', function(e) {
            e.preventDefault();
            let hasError = false;
            let $firstError = null;

            const $cabang = $form.find("#kode_cabang_coacabang_edit");
            if (!$cabang.val()) {
                $form.find("#error-cabang-coacabang-edit").removeClass('hidden').addClass('flex');
                $cabang.next('.select2-container').find('.select2-selection').addClass('!border-rose-500 !ring-1 !ring-rose-500');
                hasError = true;
                if (!$firstError) $firstError = $cabang;
            }

            const $akun = $form.find("#kode_akun_coacabang_edit");
            if (!$akun.val()) {
                $form.find("#error-akun-coacabang-edit").removeClass('hidden').addClass('flex');
                $akun.next('.select2-container').find('.select2-selection').addClass('!border-rose-500 !ring-1 !ring-rose-500');
                hasError = true;
                if (!$firstError) $firstError = $akun;
            }

            if (hasError) {
                if ($firstError) $firstError.focus();
                return false;
            }

            const $btn = $form.find("#btnSimpanCoaCabangEdit");
            $btn.prop('disabled', true).addClass('opacity-75 cursor-not-allowed pointer-events-none');
            $btn.html(`
                <svg class="animate-spin h-3.5 w-3.5 text-white shrink-0 inline-block" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <span>Menyimpan...</span>
            `);

            this.submit();
        });
    });
</script>
