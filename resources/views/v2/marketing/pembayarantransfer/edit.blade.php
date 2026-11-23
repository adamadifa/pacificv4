<form id="formTransfer" method="POST" action="{{ route('pembayarantransfer.update', [Crypt::encrypt($no_faktur), Crypt::encrypt($kode_transfer)]) }}" class="space-y-3" autocomplete="off">
    @csrf
    @method('PUT')

    <!-- Tanggal Transfer -->
    <div class="space-y-1">
        <label for="tanggal_transfer_edit" class="block text-xs font-bold text-slate-700 dark:text-slate-200">
            Tanggal Transfer <span class="text-rose-500">*</span>
        </label>
        <div class="relative">
            <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                <i data-lucide="calendar" class="w-4 h-4"></i>
            </div>
            <input type="text" id="tanggal_transfer_edit" name="tanggal" readonly
                value="{{ $transfer->tanggal }}"
                placeholder="Pilih Tanggal Transfer"
                class="flatpickr-date w-full h-[36px] pl-9 pr-3 rounded-lg border border-slate-200/80 dark:border-slate-700 bg-white dark:bg-slate-800 text-xs font-semibold text-slate-800 dark:text-slate-100 placeholder:text-slate-400 focus:outline-hidden focus:border-[#284c9a] focus:ring-1 focus:ring-[#284c9a] transition">
        </div>
    </div>

    <!-- Jumlah Bayar -->
    <div class="space-y-1">
        <label for="jumlah_transfer_edit" class="block text-xs font-bold text-slate-700 dark:text-slate-200">
            Jumlah Bayar <span class="text-rose-500">*</span>
        </label>
        <div class="relative">
            <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                <i data-lucide="banknote" class="w-4 h-4"></i>
            </div>
            <input type="text" id="jumlah_transfer_edit" name="jumlah"
                value="{{ formatAngka($transfer->jumlah) }}"
                placeholder="0"
                class="money-mask w-full h-[36px] pl-9 pr-3 text-right rounded-lg border border-slate-200/80 dark:border-slate-700 bg-white dark:bg-slate-800 text-xs font-bold font-mono-num text-slate-900 dark:text-white placeholder:text-slate-400 focus:outline-hidden focus:border-[#284c9a] focus:ring-1 focus:ring-[#284c9a] transition">
        </div>
    </div>

    <!-- Salesman Penagih -->
    @if ($level_user == 'salesman')
        <input type="hidden" name="kode_salesman" value="{{ auth()->user()->kode_salesman }}">
    @else
        <div class="space-y-1">
            <label for="kode_salesman_transfer_edit" class="block text-xs font-bold text-slate-700 dark:text-slate-200">
                Salesman Penagih <span class="text-rose-500">*</span>
            </label>
            <div class="relative select2-container-v2">
                <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-20">
                    <i data-lucide="user" class="w-4 h-4"></i>
                </div>
                <select id="kode_salesman_transfer_edit" name="kode_salesman" class="select2-salesman w-full">
                    <option value="">Pilih Salesman Penagih</option>
                    @foreach ($salesman as $s)
                        <option value="{{ $s->kode_salesman }}" {{ $transfer->kode_salesman == $s->kode_salesman ? 'selected' : '' }}>
                            {{ textUpperCase($s->nama_salesman) }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>
    @endif

    <!-- Bank Pengirim -->
    <div class="space-y-1">
        <label for="bank_pengirim_transfer_edit" class="block text-xs font-bold text-slate-700 dark:text-slate-200">
            Bank Pengirim <span class="text-rose-500">*</span>
        </label>
        <div class="relative">
            <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                <i data-lucide="building-2" class="w-4 h-4"></i>
            </div>
            <input type="text" id="bank_pengirim_transfer_edit" name="bank_pengirim"
                value="{{ $transfer->bank_pengirim }}"
                placeholder="Contoh: BCA, MANDIRI, BRI, BNI"
                class="w-full h-[36px] pl-9 pr-3 rounded-lg border border-slate-200/80 dark:border-slate-700 bg-white dark:bg-slate-800 text-xs font-semibold text-slate-800 dark:text-slate-100 placeholder:text-slate-400 focus:outline-hidden focus:border-[#284c9a] focus:ring-1 focus:ring-[#284c9a] transition">
        </div>
    </div>

    <!-- Keterangan -->
    <div class="space-y-1">
        <label for="keterangan_transfer_edit" class="block text-xs font-bold text-slate-700 dark:text-slate-200">
            Keterangan
        </label>
        <div class="relative">
            <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                <i data-lucide="file-text" class="w-4 h-4"></i>
            </div>
            <input type="text" id="keterangan_transfer_edit" name="keterangan"
                value="{{ $transfer->keterangan }}"
                placeholder="Keterangan transfer (opsional)"
                class="w-full h-[36px] pl-9 pr-3 rounded-lg border border-slate-200/80 dark:border-slate-700 bg-white dark:bg-slate-800 text-xs font-semibold text-slate-800 dark:text-slate-100 placeholder:text-slate-400 focus:outline-hidden focus:border-[#284c9a] focus:ring-1 focus:ring-[#284c9a] transition">
        </div>
    </div>

    <!-- Submit Button -->
    <div class="pt-2">
        <button type="submit" id="btnSimpanTransfer"
            class="w-full h-[36px] rounded-lg bg-[#284c9a] hover:bg-[#1e3a78] active:bg-[#152a56] text-white text-xs font-bold shadow-xs transition flex items-center justify-center gap-1.5 cursor-pointer">
            <i data-lucide="send" class="w-3.5 h-3.5"></i>
            <span class="btn-text">Submit</span>
        </button>
    </div>
</form>

<script>
    $(document).ready(function() {
        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }

        const $form = $("#formTransfer");
        const existingJumlah = parseInt("{{ (int)$transfer->jumlah }}") || 0;

        // Initialize Flatpickr
        $form.find(".flatpickr-date").flatpickr({
            dateFormat: "Y-m-d",
            @if (!empty($start_periode))
                minDate: "{{ $start_periode }}",
            @endif
            @if (!empty($end_periode))
                maxDate: "{{ $end_periode }}",
            @else
                maxDate: "{{ date('Y-m-d') }}",
            @endif
            allowInput: false
        });

        // Initialize Money Mask
        if ($.fn.maskMoney) {
            $form.find(".money-mask").maskMoney({
                thousands: '.',
                decimal: ',',
                precision: 0,
                allowZero: true
            });
        }

        // Initialize Select2
        $form.find('.select2-salesman').select2({
            placeholder: 'Pilih Salesman Penagih',
            allowClear: true,
            width: '100%'
        });

        // Error helper functions
        function showFieldError($el, message) {
            clearFieldError($el);
            const $parent = $el.closest('.space-y-1');
            $parent.find('.relative').first().find('input, select, textarea, .select2-selection').addClass('!border-rose-500 !ring-1 !ring-rose-500');
            $parent.find('i[data-lucide]').addClass('text-rose-500').removeClass('text-slate-400');
            $parent.append(`
                <p class="field-error-msg text-[11px] font-semibold text-rose-600 dark:text-rose-400 mt-1 pl-1 flex items-center gap-1">
                    <svg class="w-3.5 h-3.5 shrink-0 inline" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                    <span>${message}</span>
                </p>
            `);
        }

        function clearFieldError($el) {
            const $parent = $el.closest('.space-y-1');
            $parent.find('.relative').first().find('input, select, textarea, .select2-selection').removeClass('!border-rose-500 !ring-1 !ring-rose-500');
            $parent.find('i[data-lucide]').removeClass('text-rose-500').addClass('text-slate-400');
            $parent.find('.field-error-msg').remove();
        }

        // Live cleanup on change/input
        $form.on('input change', 'input, select, textarea', function() {
            clearFieldError($(this));
        });

        $form.find('.select2-salesman').on('select2:select select2:clear', function() {
            clearFieldError($(this));
        });

        // Form Submission with Validation
        $form.on('submit', function(e) {
            let hasError = false;
            let $firstErrorField = null;

            // In Edit, sisa_bayar limit includes previous payment amount
            let sisabayarText = $("#sisabayar").text().trim();
            let currentSisa = parseInt(sisabayarText.replace(/\./g, '')) || 0;
            let maxAllowed = currentSisa + existingJumlah;

            const $tanggal = $form.find("#tanggal_transfer_edit");
            const tanggalVal = $tanggal.val().trim();

            const $jumlah = $form.find("#jumlah_transfer_edit");
            const jumlahRaw = $jumlah.val().trim();
            const jumlahVal = parseInt(jumlahRaw.replace(/\./g, '')) || 0;

            const $salesman = $form.find("#kode_salesman_transfer_edit");
            const salesmanVal = $salesman.length ? $salesman.val() : "auto";

            const $bank = $form.find("#bank_pengirim_transfer_edit");
            const bankVal = $bank.val().trim();

            // 1. Validasi Tanggal
            if (!tanggalVal) {
                showFieldError($tanggal, "Tanggal Transfer harus diisi!");
                if (!hasError) { hasError = true; $firstErrorField = $tanggal; }
            } else {
                clearFieldError($tanggal);
            }

            // 2. Validasi Jumlah
            if (!jumlahRaw || jumlahVal <= 0) {
                showFieldError($jumlah, "Jumlah Bayar harus diisi dan lebih dari 0!");
                if (!hasError) { hasError = true; $firstErrorField = $jumlah; }
            } else if (jumlahVal > maxAllowed && maxAllowed > 0) {
                showFieldError($jumlah, `Jumlah Bayar melebihi batas bayar maksimal (${maxAllowed.toLocaleString('id-ID')})!`);
                if (!hasError) { hasError = true; $firstErrorField = $jumlah; }
            } else {
                clearFieldError($jumlah);
            }

            // 3. Validasi Salesman
            if ($salesman.length && !salesmanVal) {
                showFieldError($salesman, "Salesman Penagih harus dipilih!");
                if (!hasError) { hasError = true; $firstErrorField = $salesman; }
            } else if ($salesman.length) {
                clearFieldError($salesman);
            }

            // 4. Validasi Bank Pengirim
            if (!bankVal) {
                showFieldError($bank, "Bank Pengirim harus diisi!");
                if (!hasError) { hasError = true; $firstErrorField = $bank; }
            } else {
                clearFieldError($bank);
            }

            if (hasError) {
                e.preventDefault();
                if ($firstErrorField) {
                    $firstErrorField.focus();
                }
                return false;
            }

            // Double submit prevention
            const $btn = $form.find("#btnSimpanTransfer");
            $btn.prop('disabled', true).addClass('opacity-75 cursor-not-allowed pointer-events-none');
            $btn.html(`
                <svg class="animate-spin h-3.5 w-3.5 text-white shrink-0 inline-block" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <span>Menyimpan...</span>
            `);
            return true;
        });
    });
</script>
