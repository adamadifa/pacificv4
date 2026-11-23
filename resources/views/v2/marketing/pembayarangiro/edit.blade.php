<form id="formGiro" method="POST" action="{{ route('pembayarangiro.update', [Crypt::encrypt($no_faktur), Crypt::encrypt($kode_giro)]) }}" enctype="multipart/form-data" class="space-y-3" autocomplete="off">
    @csrf
    @method('PUT')

    <!-- No. Giro -->
    <div class="space-y-1">
        <label for="no_giro_edit" class="block text-xs font-bold text-slate-700 dark:text-slate-200">
            No. Giro <span class="text-rose-500">*</span>
        </label>
        <div class="relative">
            <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                <i data-lucide="barcode" class="w-4 h-4"></i>
            </div>
            <input type="text" id="no_giro_edit" name="no_giro"
                value="{{ $giro->no_giro }}"
                placeholder="Masukkan Nomor Giro"
                class="w-full h-[36px] pl-9 pr-3 rounded-lg border border-slate-200/80 dark:border-slate-700 bg-white dark:bg-slate-800 text-xs font-semibold text-slate-800 dark:text-slate-100 placeholder:text-slate-400 focus:outline-hidden focus:border-[#284c9a] focus:ring-1 focus:ring-[#284c9a] transition">
        </div>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
        <!-- Tanggal Giro -->
        <div class="space-y-1">
            <label for="tanggal_giro_edit" class="block text-xs font-bold text-slate-700 dark:text-slate-200">
                Tanggal Giro <span class="text-rose-500">*</span>
            </label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                    <i data-lucide="calendar" class="w-4 h-4"></i>
                </div>
                <input type="text" id="tanggal_giro_edit" name="tanggal" readonly
                    value="{{ $giro->tanggal }}"
                    placeholder="Pilih Tanggal Giro"
                    class="flatpickr-date w-full h-[36px] pl-9 pr-3 rounded-lg border border-slate-200/80 dark:border-slate-700 bg-white dark:bg-slate-800 text-xs font-semibold text-slate-800 dark:text-slate-100 placeholder:text-slate-400 focus:outline-hidden focus:border-[#284c9a] focus:ring-1 focus:ring-[#284c9a] transition">
            </div>
        </div>

        <!-- Jatuh Tempo -->
        <div class="space-y-1">
            <label for="jatuh_tempo_edit" class="block text-xs font-bold text-slate-700 dark:text-slate-200">
                Jatuh Tempo <span class="text-rose-500">*</span>
            </label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                    <i data-lucide="calendar-clock" class="w-4 h-4"></i>
                </div>
                <input type="text" id="jatuh_tempo_edit" name="jatuh_tempo" readonly
                    value="{{ $giro->jatuh_tempo }}"
                    placeholder="Pilih Tanggal Jatuh Tempo"
                    class="flatpickr-date w-full h-[36px] pl-9 pr-3 rounded-lg border border-slate-200/80 dark:border-slate-700 bg-white dark:bg-slate-800 text-xs font-semibold text-slate-800 dark:text-slate-100 placeholder:text-slate-400 focus:outline-hidden focus:border-[#284c9a] focus:ring-1 focus:ring-[#284c9a] transition">
            </div>
        </div>
    </div>

    <!-- Jumlah Bayar -->
    <div class="space-y-1">
        <label for="jumlah_giro_edit" class="block text-xs font-bold text-slate-700 dark:text-slate-200">
            Jumlah Bayar <span class="text-rose-500">*</span>
        </label>
        <div class="relative">
            <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                <i data-lucide="banknote" class="w-4 h-4"></i>
            </div>
            <input type="text" id="jumlah_giro_edit" name="jumlah"
                value="{{ formatAngka($giro->jumlah) }}"
                placeholder="0"
                class="money-mask w-full h-[36px] pl-9 pr-3 text-right rounded-lg border border-slate-200/80 dark:border-slate-700 bg-white dark:bg-slate-800 text-xs font-bold font-mono-num text-slate-900 dark:text-white placeholder:text-slate-400 focus:outline-hidden focus:border-[#284c9a] focus:ring-1 focus:ring-[#284c9a] transition">
        </div>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
        <!-- Salesman Penagih -->
        @if ($level_user == 'salesman')
            <input type="hidden" name="kode_salesman" value="{{ auth()->user()->kode_salesman }}">
        @else
            <div class="space-y-1">
                <label for="kode_salesman_giro_edit" class="block text-xs font-bold text-slate-700 dark:text-slate-200">
                    Salesman Penagih <span class="text-rose-500">*</span>
                </label>
                <div class="relative select2-container-v2">
                    <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-20">
                        <i data-lucide="user" class="w-4 h-4"></i>
                    </div>
                    <select id="kode_salesman_giro_edit" name="kode_salesman" class="select2-salesman w-full">
                        <option value="">Pilih Salesman Penagih</option>
                        @foreach ($salesman as $s)
                            <option value="{{ $s->kode_salesman }}" {{ $giro->kode_salesman == $s->kode_salesman ? 'selected' : '' }}>
                                {{ textUpperCase($s->nama_salesman) }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
        @endif

        <!-- Bank Pengirim -->
        <div class="space-y-1">
            <label for="bank_pengirim_edit" class="block text-xs font-bold text-slate-700 dark:text-slate-200">
                Bank Pengirim <span class="text-rose-500">*</span>
            </label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                    <i data-lucide="building-2" class="w-4 h-4"></i>
                </div>
                <input type="text" id="bank_pengirim_edit" name="bank_pengirim"
                    value="{{ $giro->bank_pengirim }}"
                    placeholder="Contoh: BCA, MANDIRI, BRI"
                    class="w-full h-[36px] pl-9 pr-3 rounded-lg border border-slate-200/80 dark:border-slate-700 bg-white dark:bg-slate-800 text-xs font-semibold text-slate-800 dark:text-slate-100 placeholder:text-slate-400 focus:outline-hidden focus:border-[#284c9a] focus:ring-1 focus:ring-[#284c9a] transition">
            </div>
        </div>
    </div>

    <!-- Keterangan -->
    <div class="space-y-1">
        <label for="keterangan_giro_edit" class="block text-xs font-bold text-slate-700 dark:text-slate-200">
            Keterangan
        </label>
        <div class="relative">
            <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                <i data-lucide="file-text" class="w-4 h-4"></i>
            </div>
            <input type="text" id="keterangan_giro_edit" name="keterangan"
                value="{{ $giro->keterangan }}"
                placeholder="Keterangan giro (opsional)"
                class="w-full h-[36px] pl-9 pr-3 rounded-lg border border-slate-200/80 dark:border-slate-700 bg-white dark:bg-slate-800 text-xs font-semibold text-slate-800 dark:text-slate-100 placeholder:text-slate-400 focus:outline-hidden focus:border-[#284c9a] focus:ring-1 focus:ring-[#284c9a] transition">
        </div>
    </div>

    <!-- Dokumen Giro Saat Ini (Jika Ada) -->
    @if (!empty($giro->foto) && Storage::disk('public')->exists('/giro/' . $giro->foto))
        <div class="p-2.5 rounded-lg border border-slate-200/80 dark:border-slate-700 bg-slate-50 dark:bg-slate-800/60 flex items-center justify-between gap-3">
            <div class="flex items-center gap-2.5">
                <img src="{{ getfotoGiro($giro->foto) }}" alt="Dokumen Giro" class="w-10 h-10 rounded-lg object-cover border border-slate-200 shadow-2xs">
                <div>
                    <span class="text-xs font-bold text-slate-800 dark:text-slate-100 block">Dokumen Giro Terlampir</span>
                    <a href="{{ getfotoGiro($giro->foto) }}" target="_blank" class="text-[11px] text-[#284c9a] dark:text-blue-400 hover:underline font-semibold flex items-center gap-1">
                        <i data-lucide="external-link" class="w-3 h-3"></i>
                        <span>Lihat Dokumen Asli</span>
                    </a>
                </div>
            </div>
            <span class="text-[10.5px] font-semibold text-slate-400">Tersimpan</span>
        </div>
    @endif

    <!-- Upload Dokumen Giro Baru -->
    <div class="space-y-1">
        <label class="block text-xs font-bold text-slate-700 dark:text-slate-200">
            Upload Dokumen Giro {{ !empty($giro->foto) ? '(Ganti Dokumen)' : '' }}
        </label>
        <label class="relative flex flex-col items-center justify-center border-2 border-dashed border-slate-200 dark:border-slate-700 hover:border-[#284c9a] dark:hover:border-blue-500 rounded-lg p-3 bg-slate-50/50 dark:bg-slate-800/40 cursor-pointer transition group">
            <i data-lucide="image" class="w-5 h-5 text-slate-400 group-hover:text-[#284c9a] transition mb-1"></i>
            <span class="text-xs font-semibold text-slate-600 dark:text-slate-300" id="file_name_label_edit">Pilih file foto/dokumen giro baru</span>
            <span class="text-[10px] text-slate-400">JPG, PNG, PDF maks 2MB</span>
            <input type="file" id="foto_giro_edit" name="foto" accept="image/*,.pdf" class="hidden"
                onchange="document.getElementById('file_name_label_edit').innerText = this.files[0] ? this.files[0].name : 'Pilih file foto/dokumen giro baru'">
        </label>
    </div>

    <!-- Submit Button -->
    <div class="pt-2">
        <button type="submit" id="btnSimpanGiro"
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

        const $form = $("#formGiro");
        const existingJumlah = parseInt("{{ (int)$giro->jumlah }}") || 0;

        // Initialize Flatpickr
        $form.find(".flatpickr-date").flatpickr({
            dateFormat: "Y-m-d",
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

            // In Edit, sisa_bayar limit includes the previous payment amount
            let sisabayarText = $("#sisabayar").text().trim();
            let currentSisa = parseInt(sisabayarText.replace(/\./g, '')) || 0;
            let maxAllowed = currentSisa + existingJumlah;

            const $noGiro = $form.find("#no_giro_edit");
            const noGiroVal = $noGiro.val().trim();

            const $tanggal = $form.find("#tanggal_giro_edit");
            const tanggalVal = $tanggal.val().trim();

            const $jatuhTempo = $form.find("#jatuh_tempo_edit");
            const jatuhTempoVal = $jatuhTempo.val().trim();

            const $jumlah = $form.find("#jumlah_giro_edit");
            const jumlahRaw = $jumlah.val().trim();
            const jumlahVal = parseInt(jumlahRaw.replace(/\./g, '')) || 0;

            const $salesman = $form.find("#kode_salesman_giro_edit");
            const salesmanVal = $salesman.length ? $salesman.val() : "auto";

            const $bank = $form.find("#bank_pengirim_edit");
            const bankVal = $bank.val().trim();

            // 1. Validasi No. Giro
            if (!noGiroVal) {
                showFieldError($noGiro, "No. Giro harus diisi!");
                if (!hasError) { hasError = true; $firstErrorField = $noGiro; }
            } else {
                clearFieldError($noGiro);
            }

            // 2. Validasi Tanggal
            if (!tanggalVal) {
                showFieldError($tanggal, "Tanggal Giro harus diisi!");
                if (!hasError) { hasError = true; $firstErrorField = $tanggal; }
            } else {
                clearFieldError($tanggal);
            }

            // 3. Validasi Jatuh Tempo
            if (!jatuhTempoVal) {
                showFieldError($jatuhTempo, "Tanggal Jatuh Tempo harus diisi!");
                if (!hasError) { hasError = true; $firstErrorField = $jatuhTempo; }
            } else {
                clearFieldError($jatuhTempo);
            }

            // 4. Validasi Jumlah
            if (!jumlahRaw || jumlahVal <= 0) {
                showFieldError($jumlah, "Jumlah Bayar harus diisi dan lebih dari 0!");
                if (!hasError) { hasError = true; $firstErrorField = $jumlah; }
            } else if (jumlahVal > maxAllowed && maxAllowed > 0) {
                showFieldError($jumlah, `Jumlah Bayar melebihi batas bayar maksimal (${maxAllowed.toLocaleString('id-ID')})!`);
                if (!hasError) { hasError = true; $firstErrorField = $jumlah; }
            } else {
                clearFieldError($jumlah);
            }

            // 5. Validasi Salesman
            if ($salesman.length && !salesmanVal) {
                showFieldError($salesman, "Salesman Penagih harus dipilih!");
                if (!hasError) { hasError = true; $firstErrorField = $salesman; }
            } else if ($salesman.length) {
                clearFieldError($salesman);
            }

            // 6. Validasi Bank Pengirim
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
            const $btn = $form.find("#btnSimpanGiro");
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
