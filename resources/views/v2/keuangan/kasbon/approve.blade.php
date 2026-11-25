<form action="{{ route('kasbon.approvestore', Crypt::encrypt($kasbon->no_kasbon)) }}" method="POST" id="formApproveKasbon" class="space-y-3.5" autocomplete="off">
    @csrf

    <!-- Ringkasan Data Ajuan Kasbon -->
    <div class="p-3 sm:p-3.5 rounded-xl border border-slate-200/80 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-900/40">
        <div class="flex items-center gap-1.5 pb-2 mb-2.5 border-b border-slate-200/80 dark:border-slate-800">
            <i data-lucide="info" class="w-3.5 h-3.5 text-[#284c9a] dark:text-blue-400"></i>
            <h6 class="text-xs font-bold text-slate-800 dark:text-slate-100">Ringkasan Ajuan Kasbon</h6>
        </div>
        <dl class="space-y-1.5 text-xs">
            <div class="flex justify-between items-center">
                <dt class="text-slate-500 dark:text-slate-400">No. Kasbon</dt>
                <dd class="font-mono-num font-bold text-slate-800 dark:text-slate-100 text-right">{{ $kasbon->no_kasbon }}</dd>
            </div>
            <div class="flex justify-between items-center">
                <dt class="text-slate-500 dark:text-slate-400">Tanggal Pengajuan</dt>
                <dd class="font-mono-num font-semibold text-slate-700 dark:text-slate-300 text-right">{{ DateToIndo($kasbon->tanggal) }}</dd>
            </div>
            <div class="flex justify-between items-center">
                <dt class="text-slate-500 dark:text-slate-400">NIK / Nama Karyawan</dt>
                <dd class="font-semibold text-slate-800 dark:text-slate-100 text-right">{{ $kasbon->nik }} - {{ textUpperCase($kasbon->nama_karyawan) }}</dd>
            </div>
            <div class="flex justify-between items-center">
                <dt class="text-slate-500 dark:text-slate-400">Jabatan & Dept</dt>
                <dd class="font-semibold text-slate-700 dark:text-slate-300 text-right">{{ $kasbon->nama_jabatan }} ({{ textUpperCase($kasbon->nama_dept) }})</dd>
            </div>
            <div class="flex justify-between items-center">
                <dt class="text-slate-500 dark:text-slate-400">Kantor Cabang</dt>
                <dd class="font-semibold text-slate-700 dark:text-slate-300 text-right">{{ textUpperCase($kasbon->nama_cabang) }}</dd>
            </div>
            <div class="flex justify-between items-center pt-1 border-t border-slate-200/60 dark:border-slate-800">
                <dt class="text-slate-500 dark:text-slate-400">Jumlah Kasbon</dt>
                <dd class="font-mono-num font-black text-sm text-[#284c9a] dark:text-blue-400 text-right">{{ formatAngka($kasbon->jumlah) }}</dd>
            </div>
            <div class="flex justify-between items-center">
                <dt class="text-slate-500 dark:text-slate-400">Jatuh Tempo</dt>
                <dd class="font-mono-num font-bold text-amber-600 dark:text-amber-400 text-right">{{ DateToIndo($kasbon->jatuh_tempo) }}</dd>
            </div>
        </dl>
    </div>

    <!-- Input Approval: Tanggal Proses & Debet Rekening -->
    <div class="space-y-3">
        <!-- Tanggal Proses -->
        <div class="space-y-1">
            <label for="tanggal_proses_kasbon" class="block text-xs font-bold text-slate-700 dark:text-slate-200">
                Tanggal Proses <span class="text-rose-500">*</span>
            </label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                    <i data-lucide="calendar" class="w-4 h-4"></i>
                </div>
                <input type="text" id="tanggal_proses_kasbon" name="tanggal" value="{{ date('Y-m-d') }}" readonly placeholder="Pilih Tanggal Proses"
                    class="flatpickr-date w-full h-[36px] pl-9 pr-3 rounded-lg border border-slate-200/80 dark:border-slate-700 bg-white dark:bg-slate-800 text-xs font-semibold text-slate-800 dark:text-slate-100 placeholder:text-slate-400 focus:outline-hidden focus:border-[#284c9a] focus:ring-1 focus:ring-[#284c9a] transition">
            </div>
            <p id="error-tanggal-proses-kasbon" class="hidden text-[11px] font-semibold text-rose-600 dark:text-rose-400 mt-1 pl-1 items-center gap-1">
                <i data-lucide="alert-circle" class="w-3.5 h-3.5 shrink-0"></i>
                <span>Tanggal proses wajib diisi</span>
            </p>
        </div>

        <!-- Debet Rekening Bank -->
        <div class="space-y-1">
            <label for="kode_bank_kasbon_approve" class="block text-xs font-bold text-slate-700 dark:text-slate-200">
                Debet Rekening Bank <span class="text-rose-500">*</span>
            </label>
            <div class="relative select2-container-v2">
                <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-20">
                    <i data-lucide="landmark" class="w-4 h-4"></i>
                </div>
                <select name="kode_bank" id="kode_bank_kasbon_approve" class="select2Kodebankkasbonapprove w-full">
                    <option value="">Pilih Rekening Bank</option>
                    @foreach ($bank as $d)
                        <option value="{{ $d->kode_bank }}">
                            {{ $d->nama_bank }} {{ !empty($d->no_rekening) ? '(' . $d->no_rekening . ')' : '' }}
                        </option>
                    @endforeach
                </select>
            </div>
            <p id="error-bank-proses-kasbon" class="hidden text-[11px] font-semibold text-rose-600 dark:text-rose-400 mt-1 pl-1 items-center gap-1">
                <i data-lucide="alert-circle" class="w-3.5 h-3.5 shrink-0"></i>
                <span>Debet rekening bank wajib dipilih</span>
            </p>
        </div>
    </div>

    <!-- Tombol Aksi Kanan (Right-Aligned) -->
    <div class="flex items-center justify-end gap-2 pt-2 border-t border-slate-100 dark:border-slate-800">
        <button type="button" id="btnCancelApproveKasbonModal"
            class="h-[36px] px-4 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 hover:bg-slate-100 dark:hover:bg-slate-700 text-slate-600 dark:text-slate-300 text-xs font-bold transition cursor-pointer">
            Batal
        </button>
        <button type="submit" id="btnSubmitApproveKasbon"
            class="h-[36px] px-4 rounded-lg bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold transition flex items-center gap-1.5 shadow-xs cursor-pointer">
            <i data-lucide="check-circle" class="w-3.5 h-3.5"></i>
            <span>Setujui (Approve)</span>
        </button>
    </div>
</form>

<script>
    $(function() {
        if (typeof lucide !== 'undefined') lucide.createIcons();

        const $form = $("#formApproveKasbon");

        $form.find(".flatpickr-date").flatpickr({
            dateFormat: "Y-m-d",
            allowInput: false
        });

        const $select2Bank = $form.find('.select2Kodebankkasbonapprove');
        if ($select2Bank.length) {
            $select2Bank.select2({
                placeholder: 'Pilih Rekening Bank',
                allowClear: true,
                dropdownCssClass: 'select2-dropdown-v2'
            });
        }

        $form.find("#tanggal_proses_kasbon").on('input change', function() {
            $(this).removeClass('border-rose-500 ring-1 ring-rose-500');
            $form.find("#error-tanggal-proses-kasbon").addClass('hidden').removeClass('flex');
        });

        $form.find("#kode_bank_kasbon_approve").on('change select2:select', function() {
            $form.find("#error-bank-proses-kasbon").addClass('hidden').removeClass('flex');
            $form.find(".select2-selection").removeClass('!border-rose-500 !ring-1 !ring-rose-500');
        });

        $form.find("#btnCancelApproveKasbonModal").on('click', function() {
            if (typeof modalApproveKasbonHelper !== 'undefined') {
                modalApproveKasbonHelper.close();
            }
        });

        $form.on('submit', function(e) {
            e.preventDefault();
            let hasError = false;
            let $firstError = null;

            const tanggal = $form.find("#tanggal_proses_kasbon").val().trim();
            if (!tanggal) {
                $form.find("#error-tanggal-proses-kasbon").removeClass('hidden').addClass('flex');
                $form.find("#tanggal_proses_kasbon").addClass('border-rose-500 ring-1 ring-rose-500');
                hasError = true;
                if (!$firstError) $firstError = $form.find("#tanggal_proses_kasbon");
            }

            const bank = $form.find("#kode_bank_kasbon_approve").val();
            if (!bank) {
                $form.find("#error-bank-proses-kasbon").removeClass('hidden').addClass('flex');
                $form.find(".select2-selection").addClass('!border-rose-500 !ring-1 !ring-rose-500');
                hasError = true;
                if (!$firstError) $firstError = $form.find("#kode_bank_kasbon_approve");
            }

            if (hasError) {
                if ($firstError) $firstError.focus();
                return false;
            }

            const $btn = $form.find("#btnSubmitApproveKasbon");
            $btn.prop('disabled', true).addClass('opacity-75 cursor-not-allowed pointer-events-none');
            $btn.html(`
                <svg class="animate-spin h-3.5 w-3.5 text-white shrink-0 inline-block" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <span>Memproses...</span>
            `);

            this.submit();
        });
    });
</script>
