<form action="{{ route('ajuantransfer.update', Crypt::encrypt($ajuantransfer->no_pengajuan)) }}" method="POST" id="formEditAjuantransferdana" class="space-y-3" autocomplete="off">
    @csrf
    @method('PUT')

    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2.5 items-start">
        <!-- Tanggal -->
        <div class="space-y-1">
            <label for="tanggal_edit_ajuan" class="block text-xs font-bold text-slate-700 dark:text-slate-200">
                Tanggal <span class="text-rose-500">*</span>
            </label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                    <i data-lucide="calendar" class="w-4 h-4"></i>
                </div>
                <input type="text" id="tanggal_edit_ajuan" name="tanggal" value="{{ $ajuantransfer->tanggal }}" readonly placeholder="Pilih Tanggal"
                    class="flatpickr-date w-full h-[36px] pl-9 pr-3 rounded-lg border border-slate-200/80 dark:border-slate-700 bg-white dark:bg-slate-800 text-xs font-semibold text-slate-800 dark:text-slate-100 placeholder:text-slate-400 focus:outline-hidden focus:border-[#284c9a] focus:ring-1 focus:ring-[#284c9a] transition">
            </div>
            <p id="error-tanggal-edit-ajuan" class="hidden text-[11px] font-semibold text-rose-600 dark:text-rose-400 mt-1 pl-1 items-center gap-1">
                <i data-lucide="alert-circle" class="w-3.5 h-3.5 shrink-0"></i>
                <span>Tanggal pengajuan wajib diisi</span>
            </p>
        </div>

        <!-- Cabang (Jika Multi-Cabang) -->
        @hasanyrole($roles_show_cabang)
            <div class="space-y-1">
                <label for="kode_cabang_edit_ajuan" class="block text-xs font-bold text-slate-700 dark:text-slate-200">
                    Cabang <span class="text-rose-500">*</span>
                </label>
                <div class="relative select2-container-v2">
                    <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-20">
                        <i data-lucide="building-2" class="w-4 h-4"></i>
                    </div>
                    <select name="kode_cabang" id="kode_cabang_edit_ajuan" class="select2KodeCabangEdit w-full">
                        <option value="">Pilih Cabang</option>
                        @foreach ($cabang as $c)
                            <option value="{{ $c->kode_cabang }}" {{ $ajuantransfer->kode_cabang == $c->kode_cabang ? 'selected' : '' }}>
                                {{ textUpperCase($c->nama_cabang) }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <p id="error-cabang-edit-ajuan" class="hidden text-[11px] font-semibold text-rose-600 dark:text-rose-400 mt-1 pl-1 items-center gap-1">
                    <i data-lucide="alert-circle" class="w-3.5 h-3.5 shrink-0"></i>
                    <span>Cabang wajib dipilih</span>
                </p>
            </div>
        @endhasanyrole
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2.5 items-start">
        <!-- Nama Penerima -->
        <div class="space-y-1">
            <label for="nama_penerima_edit_ajuan" class="block text-xs font-bold text-slate-700 dark:text-slate-200">
                Nama Penerima <span class="text-rose-500">*</span>
            </label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                    <i data-lucide="user" class="w-4 h-4"></i>
                </div>
                <input type="text" id="nama_penerima_edit_ajuan" name="nama" value="{{ $ajuantransfer->nama }}" placeholder="Nama penerima transfer"
                    class="w-full h-[36px] pl-9 pr-3 rounded-lg border border-slate-200/80 dark:border-slate-700 bg-white dark:bg-slate-800 text-xs font-semibold text-slate-800 dark:text-slate-100 placeholder:text-slate-400 focus:outline-hidden focus:border-[#284c9a] focus:ring-1 focus:ring-[#284c9a] transition">
            </div>
            <p id="error-nama-edit-ajuan" class="hidden text-[11px] font-semibold text-rose-600 dark:text-rose-400 mt-1 pl-1 items-center gap-1">
                <i data-lucide="alert-circle" class="w-3.5 h-3.5 shrink-0"></i>
                <span>Nama penerima wajib diisi</span>
            </p>
        </div>

        <!-- Nama Bank -->
        <div class="space-y-1">
            <label for="nama_bank_edit_ajuan" class="block text-xs font-bold text-slate-700 dark:text-slate-200">
                Nama Bank <span class="text-rose-500">*</span>
            </label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                    <i data-lucide="landmark" class="w-4 h-4"></i>
                </div>
                <input type="text" id="nama_bank_edit_ajuan" name="nama_bank" value="{{ $ajuantransfer->nama_bank }}" placeholder="Contoh: BCA / Mandiri / BRI"
                    class="w-full h-[36px] pl-9 pr-3 rounded-lg border border-slate-200/80 dark:border-slate-700 bg-white dark:bg-slate-800 text-xs font-semibold text-slate-800 dark:text-slate-100 placeholder:text-slate-400 focus:outline-hidden focus:border-[#284c9a] focus:ring-1 focus:ring-[#284c9a] transition">
            </div>
            <p id="error-bank-edit-ajuan" class="hidden text-[11px] font-semibold text-rose-600 dark:text-rose-400 mt-1 pl-1 items-center gap-1">
                <i data-lucide="alert-circle" class="w-3.5 h-3.5 shrink-0"></i>
                <span>Nama bank wajib diisi</span>
            </p>
        </div>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2.5 items-start">
        <!-- No. Rekening -->
        <div class="space-y-1">
            <label for="no_rekening_edit_ajuan" class="block text-xs font-bold text-slate-700 dark:text-slate-200">
                No. Rekening
            </label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                    <i data-lucide="credit-card" class="w-4 h-4"></i>
                </div>
                <input type="text" id="no_rekening_edit_ajuan" name="no_rekening" value="{{ $ajuantransfer->no_rekening }}" placeholder="Nomor rekening tujuan"
                    class="w-full h-[36px] pl-9 pr-3 rounded-lg border border-slate-200/80 dark:border-slate-700 bg-white dark:bg-slate-800 text-xs font-mono-num font-semibold text-slate-800 dark:text-slate-100 placeholder:text-slate-400 focus:outline-hidden focus:border-[#284c9a] focus:ring-1 focus:ring-[#284c9a] transition">
            </div>
        </div>

        <!-- Jumlah Nominal -->
        <div class="space-y-1">
            <label for="jumlah_edit_ajuan" class="block text-xs font-bold text-slate-700 dark:text-slate-200">
                Jumlah Ajuan (Rp) <span class="text-rose-500">*</span>
            </label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                    <i data-lucide="banknote" class="w-4 h-4"></i>
                </div>
                <input type="text" id="jumlah_edit_ajuan" name="jumlah" value="{{ formatAngka($ajuantransfer->jumlah) }}" placeholder="0"
                    class="money-mask w-full h-[36px] pl-9 pr-3 text-right rounded-lg border border-slate-200/80 dark:border-slate-700 bg-white dark:bg-slate-800 text-xs font-bold font-mono-num text-slate-900 dark:text-white placeholder:text-slate-400 focus:outline-hidden focus:border-[#284c9a] focus:ring-1 focus:ring-[#284c9a] transition">
            </div>
            <p id="error-jumlah-edit-ajuan" class="hidden text-[11px] font-semibold text-rose-600 dark:text-rose-400 mt-1 pl-1 items-center gap-1">
                <i data-lucide="alert-circle" class="w-3.5 h-3.5 shrink-0"></i>
                <span>Jumlah ajuan harus lebih besar dari 0</span>
            </p>
        </div>
    </div>

    <!-- Keterangan -->
    <div class="space-y-1">
        <label for="keterangan_edit_ajuan" class="block text-xs font-bold text-slate-700 dark:text-slate-200">
            Keterangan <span class="text-rose-500">*</span>
        </label>
        <div class="relative">
            <textarea id="keterangan_edit_ajuan" name="keterangan" rows="2" placeholder="Keperluan transfer dana"
                class="w-full p-2.5 rounded-lg border border-slate-200/80 dark:border-slate-700 bg-white dark:bg-slate-800 text-xs font-medium text-slate-800 dark:text-slate-100 placeholder:text-slate-400 focus:outline-hidden focus:border-[#284c9a] focus:ring-1 focus:ring-[#284c9a] transition">{{ $ajuantransfer->keterangan }}</textarea>
        </div>
        <p id="error-keterangan-edit-ajuan" class="hidden text-[11px] font-semibold text-rose-600 dark:text-rose-400 mt-1 pl-1 items-center gap-1">
            <i data-lucide="alert-circle" class="w-3.5 h-3.5 shrink-0"></i>
            <span>Keterangan ajuan wajib diisi</span>
        </p>
    </div>

    <!-- Tombol Aksi (Right-Aligned) -->
    <div class="flex items-center justify-end gap-2 pt-2 border-t border-slate-100 dark:border-slate-800">
        <button type="button" id="btnCancelEditAjuan"
            class="h-[34px] px-3.5 rounded-lg border border-slate-200 dark:border-slate-700 hover:bg-slate-100 dark:hover:bg-slate-800 text-slate-600 dark:text-slate-300 text-xs font-bold transition cursor-pointer">
            Batal
        </button>
        <button type="submit" id="btnUpdateAjuan"
            class="h-[34px] px-4 rounded-lg bg-[#284c9a] hover:bg-[#1e3a78] text-white text-xs font-bold transition shadow-xs cursor-pointer inline-flex items-center gap-1.5">
            <i data-lucide="save" class="w-3.5 h-3.5"></i>
            <span>Simpan Perubahan</span>
        </button>
    </div>
</form>

<script>
    $(function() {
        const $form = $("#formEditAjuantransferdana");

        if (typeof lucide !== 'undefined') lucide.createIcons();

        $form.find(".flatpickr-date").flatpickr({
            dateFormat: "Y-m-d",
            allowInput: false
        });

        $form.find(".money-mask").maskMoney({
            thousands: '.',
            decimal: ',',
            precision: 0,
            allowZero: true
        });

        $form.find('.select2KodeCabangEdit').select2({
            placeholder: 'Pilih Cabang',
            allowClear: true,
            dropdownCssClass: 'select2-dropdown-v2'
        });

        function parseMoney(val) {
            if (!val) return 0;
            return parseInt(val.toString().replace(/\./g, '')) || 0;
        }

        function clearError($input, $error) {
            $input.removeClass('border-rose-500 ring-1 ring-rose-500 focus:border-rose-500 focus:ring-rose-500/20');
            $input.closest('.relative').find('i').removeClass('text-rose-500');
            $error.addClass('hidden').removeClass('flex');
        }

        function showError($input, $error) {
            $input.addClass('border-rose-500 ring-1 ring-rose-500 focus:border-rose-500 focus:ring-rose-500/20');
            $input.closest('.relative').find('i').addClass('text-rose-500');
            $error.removeClass('hidden').addClass('flex');
            if (typeof lucide !== 'undefined') lucide.createIcons();
        }

        $form.find("#tanggal_edit_ajuan").on('input change', function() {
            clearError($(this), $form.find("#error-tanggal-edit-ajuan"));
        });
        $form.find("#kode_cabang_edit_ajuan").on('change', function() {
            clearError($form.find('.select2-container-v2'), $form.find("#error-cabang-edit-ajuan"));
        });
        $form.find("#nama_penerima_edit_ajuan").on('input change', function() {
            clearError($(this), $form.find("#error-nama-edit-ajuan"));
        });
        $form.find("#nama_bank_edit_ajuan").on('input change', function() {
            clearError($(this), $form.find("#error-bank-edit-ajuan"));
        });
        $form.find("#jumlah_edit_ajuan").on('input keyup change', function() {
            clearError($(this), $form.find("#error-jumlah-edit-ajuan"));
        });
        $form.find("#keterangan_edit_ajuan").on('input change', function() {
            clearError($(this), $form.find("#error-keterangan-edit-ajuan"));
        });

        $form.find("#btnCancelEditAjuan").on('click', function() {
            if (typeof modalAjuantransfer !== 'undefined') {
                modalAjuantransfer.close();
            } else {
                $("#modalAjuantransfer").addClass('hidden');
                $('body').removeClass('overflow-hidden');
            }
        });

        $form.on('submit', function(e) {
            e.preventDefault();
            let hasError = false;
            let $firstError = null;

            const tanggal = $form.find("#tanggal_edit_ajuan").val();
            const $cabangSelect = $form.find("#kode_cabang_edit_ajuan");
            const nama = $form.find("#nama_penerima_edit_ajuan").val().trim();
            const bank = $form.find("#nama_bank_edit_ajuan").val().trim();
            const jumlah = parseMoney($form.find("#jumlah_edit_ajuan").val());
            const keterangan = $form.find("#keterangan_edit_ajuan").val().trim();

            if (!tanggal) {
                showError($form.find("#tanggal_edit_ajuan"), $form.find("#error-tanggal-edit-ajuan"));
                hasError = true;
                if (!$firstError) $firstError = $form.find("#tanggal_edit_ajuan");
            }

            if ($cabangSelect.length && !$cabangSelect.val()) {
                showError($form.find('.select2-container-v2'), $form.find("#error-cabang-edit-ajuan"));
                hasError = true;
                if (!$firstError) $firstError = $cabangSelect;
            }

            if (!nama) {
                showError($form.find("#nama_penerima_edit_ajuan"), $form.find("#error-nama-edit-ajuan"));
                hasError = true;
                if (!$firstError) $firstError = $form.find("#nama_penerima_edit_ajuan");
            }

            if (!bank) {
                showError($form.find("#nama_bank_edit_ajuan"), $form.find("#error-bank-edit-ajuan"));
                hasError = true;
                if (!$firstError) $firstError = $form.find("#nama_bank_ajuan");
            }

            if (jumlah <= 0) {
                showError($form.find("#jumlah_edit_ajuan"), $form.find("#error-jumlah-edit-ajuan"));
                hasError = true;
                if (!$firstError) $firstError = $form.find("#jumlah_edit_ajuan");
            }

            if (!keterangan) {
                showError($form.find("#keterangan_edit_ajuan"), $form.find("#error-keterangan-edit-ajuan"));
                hasError = true;
                if (!$firstError) $firstError = $form.find("#keterangan_edit_ajuan");
            }

            if (hasError) {
                if ($firstError) $firstError.focus();
                return false;
            }

            const $btn = $form.find("#btnUpdateAjuan");
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
