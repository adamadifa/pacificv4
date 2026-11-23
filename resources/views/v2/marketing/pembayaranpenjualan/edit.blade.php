<form id="formBayar" method="POST" action="{{ route('pembayaranpenjualan.update', Crypt::encrypt($historibayar->no_bukti)) }}" class="space-y-3" autocomplete="off">
    @csrf
    @method('PUT')

    <!-- Tanggal Pembayaran -->
    <div class="space-y-1">
        <label for="tanggal_bayar_edit" class="block text-xs font-bold text-slate-700 dark:text-slate-200">
            Tanggal Pembayaran <span class="text-rose-500">*</span>
        </label>
        <div class="relative">
            <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                <i data-lucide="calendar" class="w-4 h-4"></i>
            </div>
            <input type="text" id="tanggal_bayar_edit" name="tanggal" readonly
                value="{{ $historibayar->tanggal }}"
                placeholder="Pilih Tanggal Pembayaran"
                class="flatpickr-date w-full h-[36px] pl-9 pr-3 rounded-lg border border-slate-200/80 dark:border-slate-700 bg-white dark:bg-slate-800 text-xs font-semibold text-slate-800 dark:text-slate-100 placeholder:text-slate-400 focus:outline-hidden focus:border-[#284c9a] focus:ring-1 focus:ring-[#284c9a] transition">
        </div>
    </div>

    <!-- Jumlah Bayar -->
    <div class="space-y-1">
        <label for="jumlah_bayar_edit" class="block text-xs font-bold text-slate-700 dark:text-slate-200">
            Jumlah Bayar <span class="text-rose-500">*</span>
        </label>
        <div class="relative">
            <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                <i data-lucide="banknote" class="w-4 h-4"></i>
            </div>
            <input type="text" id="jumlah_bayar_edit" name="jumlah"
                value="{{ formatAngka($historibayar->jumlah) }}"
                placeholder="0"
                class="money-mask w-full h-[36px] pl-9 pr-3 text-right rounded-lg border border-slate-200/80 dark:border-slate-700 bg-white dark:bg-slate-800 text-xs font-bold font-mono-num text-slate-900 dark:text-white placeholder:text-slate-400 focus:outline-hidden focus:border-[#284c9a] focus:ring-1 focus:ring-[#284c9a] transition">
        </div>
    </div>

    <!-- Salesman Penagih -->
    @if ($level_user == 'salesman')
        <input type="hidden" name="kode_salesman" value="{{ Auth::user()->kode_salesman }}" />
    @else
        <div class="space-y-1">
            <label for="kode_salesman_bayar_edit" class="block text-xs font-bold text-slate-700 dark:text-slate-200">
                Salesman Penagih <span class="text-rose-500">*</span>
            </label>
            <div class="relative select2-container-v2">
                <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-20">
                    <i data-lucide="user" class="w-4 h-4"></i>
                </div>
                <select id="kode_salesman_bayar_edit" name="kode_salesman" class="select2-salesman w-full">
                    <option value="">Pilih Salesman Penagih</option>
                    @foreach ($salesman as $s)
                        <option value="{{ $s->kode_salesman }}" {{ $historibayar->kode_salesman == $s->kode_salesman ? 'selected' : '' }}>
                            {{ textUpperCase($s->nama_salesman) }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>
    @endif

    <!-- Agreement Voucher Checkbox -->
    <div class="pt-1">
        <label class="inline-flex items-center gap-2 cursor-pointer select-none">
            <input type="checkbox" id="agreementvoucher" name="agreementvoucher" value="1"
                {{ $historibayar->voucher == 1 ? 'checked' : '' }}
                class="agreementvoucher rounded border-slate-300 text-[#284c9a] focus:ring-[#284c9a] w-4 h-4 cursor-pointer">
            <span class="text-xs font-bold text-slate-700 dark:text-slate-200">Bayar Menggunakan Voucher ?</span>
        </label>
    </div>

    <!-- Jenis Voucher (Conditional) -->
    <div id="voucher_container" class="space-y-1 {{ $historibayar->voucher == 1 ? '' : 'hidden' }}">
        <label for="jenis_voucher_bayar_edit" class="block text-xs font-bold text-slate-700 dark:text-slate-200">
            Pilih Voucher <span class="text-rose-500">*</span>
        </label>
        <div class="relative select2-container-v2">
            <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-20">
                <i data-lucide="tag" class="w-4 h-4"></i>
            </div>
            <select id="jenis_voucher_bayar_edit" name="jenis_voucher" class="select2-voucher w-full">
                <option value="">Pilih Voucher</option>
                @foreach ($jenis_voucher as $v)
                    <option value="{{ $v->id }}" {{ $historibayar->jenis_voucher == $v->id ? 'selected' : '' }}>
                        {{ textUpperCase($v->nama_voucher) }}
                    </option>
                @endforeach
            </select>
        </div>
    </div>

    <!-- Agreement Giro Checkbox -->
    <div class="pt-1">
        <label class="inline-flex items-center gap-2 cursor-pointer select-none">
            <input type="checkbox" id="agreementgiro" name="agreementgiro" value="1"
                {{ $historibayar->giro_to_cash == '1' ? 'checked' : '' }}
                class="agreementgiro rounded border-slate-300 text-[#284c9a] focus:ring-[#284c9a] w-4 h-4 cursor-pointer">
            <span class="text-xs font-bold text-slate-700 dark:text-slate-200">Ganti Giro Ke Cash ?</span>
        </label>
    </div>

    <!-- Giro Ditolak (Conditional) -->
    <div id="giroditolak_container" class="space-y-1 {{ $historibayar->giro_to_cash == '1' ? '' : 'hidden' }}">
        <label for="kode_giro_bayar_edit" class="block text-xs font-bold text-slate-700 dark:text-slate-200">
            Pilih Giro <span class="text-rose-500">*</span>
        </label>
        <div class="relative select2-container-v2">
            <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-20">
                <i data-lucide="wallet" class="w-4 h-4"></i>
            </div>
            <select id="kode_giro_bayar_edit" name="kode_giro" class="select2-giro w-full">
                <option value="">Pilih Giro</option>
                @foreach ($giroditolak as $g)
                    <option value="{{ $g->kode_giro }}" {{ $historibayar->kode_giro == $g->kode_giro ? 'selected' : '' }}>
                        {{ textUpperCase($g->no_giro) }}
                    </option>
                @endforeach
            </select>
        </div>
    </div>

    <!-- Keterangan -->
    <div class="space-y-1">
        <label for="keterangan_bayar_edit" class="block text-xs font-bold text-slate-700 dark:text-slate-200">
            Keterangan
        </label>
        <textarea id="keterangan_bayar_edit" name="keterangan" rows="2"
            placeholder="Keterangan pembayaran (opsional)"
            class="w-full p-2.5 rounded-lg border border-slate-200/80 dark:border-slate-700 bg-white dark:bg-slate-800 text-xs font-medium text-slate-800 dark:text-slate-100 placeholder:text-slate-400 focus:outline-hidden focus:border-[#284c9a] focus:ring-1 focus:ring-[#284c9a] transition">{{ $historibayar->keterangan ?? '' }}</textarea>
    </div>

    <!-- Submit Button -->
    <div class="pt-2">
        <button type="submit" id="btnSimpan"
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

        const $form = $("#formBayar");
        const existingJumlah = parseInt("{{ (int)$historibayar->jumlah }}") || 0;

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

        $form.find('.select2-voucher').select2({
            placeholder: 'Pilih Voucher',
            allowClear: true,
            width: '100%'
        });

        $form.find('.select2-giro').select2({
            placeholder: 'Pilih Giro',
            allowClear: true,
            width: '100%'
        });

        // Toggle Voucher Section
        $form.find('.agreementvoucher').on('change', function() {
            if ($(this).is(':checked')) {
                $form.find("#voucher_container").removeClass('hidden');
            } else {
                $form.find("#voucher_container").addClass('hidden');
                $form.find("#jenis_voucher_bayar_edit").val('').trigger('change');
                clearFieldError($form.find("#jenis_voucher_bayar_edit"));
            }
        });

        // Toggle Giro Section
        $form.find('.agreementgiro').on('change', function() {
            if ($(this).is(':checked')) {
                $form.find("#giroditolak_container").removeClass('hidden');
            } else {
                $form.find("#giroditolak_container").addClass('hidden');
                $form.find("#kode_giro_bayar_edit").val('').trigger('change');
                clearFieldError($form.find("#kode_giro_bayar_edit"));
            }
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

        $form.find('.select2-salesman, .select2-voucher, .select2-giro').on('select2:select select2:clear', function() {
            clearFieldError($(this));
        });

        // Form Submission with Validation
        $form.on('submit', function(e) {
            let hasError = false;
            let $firstErrorField = null;

            // In Edit, sisa_bayar limit includes the previous payment amount
            let sisabayarText = $("#sisabayar").text().trim();
            let currentSisa = parseInt(sisabayarText.replace(/\./g, '')) || 0;
            let maxAllowed = currentSisa + existingJumlah;

            const $tanggal = $form.find("#tanggal_bayar_edit");
            const tanggalVal = $tanggal.val().trim();

            const $jumlah = $form.find("#jumlah_bayar_edit");
            const jumlahRaw = $jumlah.val().trim();
            const jumlahVal = parseInt(jumlahRaw.replace(/\./g, '')) || 0;

            const $salesman = $form.find("#kode_salesman_bayar_edit");
            const salesmanVal = $salesman.length ? $salesman.val() : "auto";

            // 1. Validasi Tanggal
            if (!tanggalVal) {
                showFieldError($tanggal, "Tanggal Pembayaran harus diisi!");
                if (!hasError) { hasError = true; $firstErrorField = $tanggal; }
            } else {
                clearFieldError($tanggal);
            }

            // 2. Validasi Jumlah
            if (!jumlahRaw || jumlahVal <= 0) {
                showFieldError($jumlah, "Jumlah Bayar harus diisi dan lebih dari 0!");
                if (!hasError) { hasError = true; $firstErrorField = $jumlah; }
            } else if (jumlahVal > maxAllowed && maxAllowed > 0) {
                showFieldError($jumlah, `Jumlah Bayar melebihi sisa bayar maksimal (${maxAllowed.toLocaleString('id-ID')})!`);
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

            // 4. Validasi Voucher
            if ($form.find(".agreementvoucher").is(':checked')) {
                const $jenisVoucher = $form.find("#jenis_voucher_bayar_edit");
                if (!$jenisVoucher.val()) {
                    showFieldError($jenisVoucher, "Pilih Voucher terlebih dahulu!");
                    if (!hasError) { hasError = true; $firstErrorField = $jenisVoucher; }
                } else {
                    clearFieldError($jenisVoucher);
                }
            }

            // 5. Validasi Giro Ditolak
            if ($form.find(".agreementgiro").is(':checked')) {
                const $kodeGiro = $form.find("#kode_giro_bayar_edit");
                if (!$kodeGiro.val()) {
                    showFieldError($kodeGiro, "Pilih Giro yang diganti!");
                    if (!hasError) { hasError = true; $firstErrorField = $kodeGiro; }
                } else {
                    clearFieldError($kodeGiro);
                }
            }

            if (hasError) {
                e.preventDefault();
                if ($firstErrorField) {
                    $firstErrorField.focus();
                }
                return false;
            }

            // Double submit prevention
            const $btn = $form.find("#btnSimpan");
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
