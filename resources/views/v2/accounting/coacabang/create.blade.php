<form action="{{ route('coacabang.store') }}" method="POST" id="formCreateCoaCabang" class="space-y-3" autocomplete="off">
    @csrf
    <input type="hidden" name="kode_cabang" id="kode_cabang_create_hidden" value="{{ request('kode_cabang') }}">

    <!-- Cabang -->
    <div class="space-y-1">
        <label class="block text-xs font-bold text-slate-700 dark:text-slate-200">
            Cabang
        </label>
        <div class="relative">
            <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                <i data-lucide="building" class="w-4 h-4"></i>
            </div>
            <input type="text" readonly
                value="{{ $cabang_selected ? $cabang_selected->kode_cabang . ' - ' . textUpperCase($cabang_selected->nama_cabang) : request('kode_cabang') }}"
                class="w-full h-[36px] pl-9 pr-3 rounded-lg border border-slate-200/80 dark:border-slate-700 bg-slate-100 dark:bg-slate-800 text-xs font-bold text-slate-600 dark:text-slate-300 cursor-not-allowed">
        </div>
    </div>

    <!-- Kode Akun -->
    <div class="space-y-1">
        <label for="kode_akun_coacabang_create" class="block text-xs font-bold text-slate-700 dark:text-slate-200">
            Kode Akun <span class="text-rose-500">*</span>
        </label>
        <div class="relative select2-container-v2">
            <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-20">
                <i data-lucide="layers" class="w-4 h-4"></i>
            </div>
            <select name="kode_akun" id="kode_akun_coacabang_create" class="select2KodeakunCoaCabang w-full">
                <option value="">Pilih Kode Akun</option>
                @foreach ($coa as $d)
                    <option value="{{ $d->kode_akun }}">{{ $d->kode_akun }} - {{ $d->nama_akun }}</option>
                @endforeach
            </select>
        </div>
        <p id="error-akun-coacabang-create" class="hidden items-center gap-1 text-[11px] font-semibold text-rose-600 dark:text-rose-400 mt-1 pl-1">
            <i data-lucide="alert-circle" class="w-3.5 h-3.5 shrink-0"></i>
            <span>Kode akun wajib dipilih</span>
        </p>
    </div>

    <!-- Tombol Aksi Kanan (Right-Aligned) -->
    <div class="flex items-center justify-end gap-2 pt-2 border-t border-slate-100 dark:border-slate-800">
        <button type="button" id="btnCancelCoaCabangCreate"
            class="h-[36px] px-4 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 hover:bg-slate-100 dark:hover:bg-slate-700 text-slate-600 dark:text-slate-300 text-xs font-bold transition cursor-pointer">
            Batal
        </button>
        <button type="submit" id="btnSimpanCoaCabangCreate"
            class="h-[36px] px-4 rounded-lg bg-[#284c9a] hover:bg-[#1e3a78] text-white text-xs font-bold transition flex items-center gap-1.5 shadow-xs cursor-pointer">
            <i data-lucide="send" class="w-3.5 h-3.5"></i>
            <span>Simpan</span>
        </button>
    </div>
</form>

<script>
    $(function() {
        if (typeof lucide !== 'undefined') lucide.createIcons();

        const $form = $("#formCreateCoaCabang");

        const $select2Akun = $form.find('.select2KodeakunCoaCabang');
        if ($select2Akun.length) {
            $select2Akun.select2({
                placeholder: 'Pilih Kode Akun',
                allowClear: true,
                dropdownCssClass: 'select2-dropdown-v2'
            });
        }

        $form.find("#kode_akun_coacabang_create").on('change select2:select', function() {
            $form.find("#error-akun-coacabang-create").addClass('hidden').removeClass('flex');
            $form.find(".select2-selection").removeClass('!border-rose-500 !ring-1 !ring-rose-500');
        });

        $form.find("#btnCancelCoaCabangCreate").on('click', function() {
            if (typeof modalCoaCabangHelper !== 'undefined') {
                modalCoaCabangHelper.close();
            }
        });

        $form.on('submit', function(e) {
            e.preventDefault();
            const kode_akun = $form.find("#kode_akun_coacabang_create").val();

            if (!kode_akun) {
                $form.find("#error-akun-coacabang-create").removeClass('hidden').addClass('flex');
                $form.find(".select2-selection").addClass('!border-rose-500 !ring-1 !ring-rose-500');
                $form.find("#kode_akun_coacabang_create").focus();
                return false;
            }

            const $btn = $form.find("#btnSimpanCoaCabangCreate");
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
