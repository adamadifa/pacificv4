<form action="{{ route('sakasbesarkeuangan.store') }}" method="POST" id="formCreateSaldoKasBesar" class="space-y-3" autocomplete="off">
    @csrf

    @hasanyrole($roles_show_cabang)
        <div class="space-y-1">
            <label for="kode_cabang_skb_create" class="block text-xs font-bold text-slate-700 dark:text-slate-200">
                Cabang <span class="text-rose-500">*</span>
            </label>
            <div class="relative select2-container-v2">
                <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-20">
                    <i data-lucide="building" class="w-4 h-4"></i>
                </div>
                <select name="kode_cabang" id="kode_cabang_skb_create" class="select2Kodecabangskb w-full">
                    <option value="">Pilih Cabang</option>
                    @foreach ($cabang as $d)
                        <option value="{{ $d->kode_cabang }}">{{ textUpperCase($d->nama_cabang) }}</option>
                    @endforeach
                </select>
            </div>
            <p id="error-cabang-skb-create" class="hidden items-center gap-1 text-[11px] font-semibold text-rose-600 dark:text-rose-400 mt-1 pl-1">
                <i data-lucide="alert-circle" class="w-3.5 h-3.5 shrink-0"></i>
                <span>Cabang wajib dipilih</span>
            </p>
        </div>
    @endhasanyrole

    <div class="space-y-1">
        <label for="tanggal_skb_create" class="block text-xs font-bold text-slate-700 dark:text-slate-200">
            Tanggal <span class="text-rose-500">*</span>
        </label>
        <div class="relative">
            <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                <i data-lucide="calendar" class="w-4 h-4"></i>
            </div>
            <input type="text" id="tanggal_skb_create" name="tanggal" value="{{ date('Y-m-d') }}" readonly placeholder="Pilih Tanggal"
                class="flatpickr-date w-full h-[36px] pl-9 pr-3 rounded-lg border border-slate-200/80 dark:border-slate-700 bg-white dark:bg-slate-800 text-xs font-semibold text-slate-800 dark:text-slate-100 placeholder:text-slate-400 focus:outline-hidden focus:border-[#284c9a] focus:ring-1 focus:ring-[#284c9a] transition">
        </div>
        <p id="error-tanggal-skb-create" class="hidden items-center gap-1 text-[11px] font-semibold text-rose-600 dark:text-rose-400 mt-1 pl-1">
            <i data-lucide="alert-circle" class="w-3.5 h-3.5 shrink-0"></i>
            <span>Tanggal wajib diisi</span>
        </p>
    </div>

    <div class="space-y-1">
        <label for="keterangan_skb_create" class="block text-xs font-bold text-slate-700 dark:text-slate-200">
            Keterangan <span class="text-rose-500">*</span>
        </label>
        <div class="relative">
            <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                <i data-lucide="file-text" class="w-4 h-4"></i>
            </div>
            <input type="text" id="keterangan_skb_create" name="keterangan" placeholder="Keterangan saldo kas besar"
                class="w-full h-[36px] pl-9 pr-3 rounded-lg border border-slate-200/80 dark:border-slate-700 bg-white dark:bg-slate-800 text-xs font-medium text-slate-800 dark:text-slate-100 placeholder:text-slate-400 focus:outline-hidden focus:border-[#284c9a] focus:ring-1 focus:ring-[#284c9a] transition">
        </div>
        <p id="error-keterangan-skb-create" class="hidden items-center gap-1 text-[11px] font-semibold text-rose-600 dark:text-rose-400 mt-1 pl-1">
            <i data-lucide="alert-circle" class="w-3.5 h-3.5 shrink-0"></i>
            <span>Keterangan wajib diisi</span>
        </p>
    </div>

    <div class="space-y-1">
        <label for="jumlah_skb_create" class="block text-xs font-bold text-slate-700 dark:text-slate-200">
            Jumlah Saldo <span class="text-rose-500">*</span>
        </label>
        <div class="relative">
            <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                <i data-lucide="dollar-sign" class="w-4 h-4"></i>
            </div>
            <input type="text" id="jumlah_skb_create" name="jumlah" placeholder="0"
                class="money w-full h-[36px] pl-9 pr-3 text-right rounded-lg border border-slate-200/80 dark:border-slate-700 bg-white dark:bg-slate-800 text-xs font-bold text-slate-800 dark:text-slate-100 placeholder:text-slate-400 focus:outline-hidden focus:border-[#284c9a] focus:ring-1 focus:ring-[#284c9a] transition">
        </div>
        <p id="error-jumlah-skb-create" class="hidden items-center gap-1 text-[11px] font-semibold text-rose-600 dark:text-rose-400 mt-1 pl-1">
            <i data-lucide="alert-circle" class="w-3.5 h-3.5 shrink-0"></i>
            <span>Jumlah saldo wajib diisi</span>
        </p>
    </div>

    <div class="flex items-center justify-end gap-2 pt-2 border-t border-slate-100 dark:border-slate-800">
        <button type="button" id="btnCancelSkbCreate"
            class="h-[36px] px-4 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 hover:bg-slate-100 dark:hover:bg-slate-700 text-slate-600 dark:text-slate-300 text-xs font-bold transition cursor-pointer">
            Batal
        </button>
        <button type="submit" id="btnSimpanSkbCreate"
            class="h-[36px] px-4 rounded-lg bg-[#284c9a] hover:bg-[#1e3a78] text-white text-xs font-bold transition flex items-center gap-1.5 shadow-xs cursor-pointer">
            <i data-lucide="send" class="w-3.5 h-3.5"></i>
            <span>Simpan Saldo</span>
        </button>
    </div>
</form>

<script>
    $(function() {
        if (typeof lucide !== 'undefined') lucide.createIcons();

        const $form = $("#formCreateSaldoKasBesar");
        $form.find(".flatpickr-date").flatpickr({ dateFormat: "Y-m-d", allowInput: false });
        $form.find(".money").maskMoney({ thousands: '.', decimal: ',', precision: 0 });

        const $select2Cabang = $form.find('.select2Kodecabangskb');
        if ($select2Cabang.length) {
            $select2Cabang.select2({ placeholder: 'Pilih Cabang', allowClear: true, dropdownCssClass: 'select2-dropdown-v2' });
        }

        function clearError($input, $error) {
            $input.removeClass('border-rose-500 ring-1 ring-rose-500');
            $input.closest('.relative').find('i').removeClass('text-rose-500');
            if ($input.hasClass('select2-hidden-accessible')) {
                $input.next('.select2-container').find('.select2-selection').removeClass('!border-rose-500 !ring-1 !ring-rose-500');
            }
            $error.addClass('hidden').removeClass('flex');
        }

        function showError($input, $error) {
            $input.addClass('border-rose-500 ring-1 ring-rose-500');
            $input.closest('.relative').find('i').addClass('text-rose-500');
            if ($input.hasClass('select2-hidden-accessible')) {
                $input.next('.select2-container').find('.select2-selection').addClass('!border-rose-500 !ring-1 !ring-rose-500');
            }
            $error.removeClass('hidden').addClass('flex');
            if (typeof lucide !== 'undefined') lucide.createIcons();
        }

        $form.find("#kode_cabang_skb_create").on('change select2:select', function() { clearError($(this), $form.find("#error-cabang-skb-create")); });
        $form.find("#tanggal_skb_create").on('input change', function() { clearError($(this), $form.find("#error-tanggal-skb-create")); });
        $form.find("#keterangan_skb_create").on('input change', function() { clearError($(this), $form.find("#error-keterangan-skb-create")); });
        $form.find("#jumlah_skb_create").on('input change keyup', function() { clearError($(this), $form.find("#error-jumlah-skb-create")); });

        $form.find("#btnCancelSkbCreate").on('click', function() {
            if (typeof modalSaldoKasBesarHelper !== 'undefined') modalSaldoKasBesarHelper.close();
        });

        $form.on('submit', function(e) {
            e.preventDefault();
            let hasError = false, $firstError = null;

            const $cabang = $form.find("#kode_cabang_skb_create");
            if ($cabang.length && !$cabang.val()) {
                showError($cabang, $form.find("#error-cabang-skb-create"));
                hasError = true; if (!$firstError) $firstError = $cabang;
            }
            const $tgl = $form.find("#tanggal_skb_create");
            if (!$tgl.val().trim()) {
                showError($tgl, $form.find("#error-tanggal-skb-create"));
                hasError = true; if (!$firstError) $firstError = $tgl;
            }
            const $ket = $form.find("#keterangan_skb_create");
            if (!$ket.val().trim()) {
                showError($ket, $form.find("#error-keterangan-skb-create"));
                hasError = true; if (!$firstError) $firstError = $ket;
            }
            const $jml = $form.find("#jumlah_skb_create");
            if (!$jml.val().trim()) {
                showError($jml, $form.find("#error-jumlah-skb-create"));
                hasError = true; if (!$firstError) $firstError = $jml;
            }

            if (hasError) { if ($firstError) $firstError.focus(); return false; }

            const $btn = $form.find("#btnSimpanSkbCreate");
            $btn.prop('disabled', true).addClass('opacity-75 cursor-not-allowed pointer-events-none');
            $btn.html(`<svg class="animate-spin h-3.5 w-3.5 text-white shrink-0 inline-block" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg><span>Menyimpan...</span>`);
            this.submit();
        });
    });
</script>
