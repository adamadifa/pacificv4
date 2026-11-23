<!-- INFORMASI FAKTUR & PELANGGAN STRIP -->
<div class="rounded-xl border border-slate-200/80 dark:border-slate-800 bg-slate-50/70 dark:bg-slate-800/40 p-3 sm:p-3.5 space-y-2.5 mb-3.5">
    <div class="flex items-center justify-between pb-2 mb-1 border-b border-slate-200/80 dark:border-slate-800">
        <div class="flex items-center gap-1.5">
            <i data-lucide="file-text" class="w-3.5 h-3.5 text-[#284c9a] dark:text-blue-400"></i>
            <span class="text-xs font-bold text-slate-800 dark:text-slate-100">Informasi Faktur & Pelanggan</span>
        </div>
        <span class="font-mono-num font-bold text-xs text-[#284c9a] dark:text-blue-400">
            {{ $faktur->no_faktur }}
        </span>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 text-xs">
        <div class="flex items-start justify-between sm:justify-start sm:gap-3">
            <span class="text-slate-400 w-24 shrink-0">Tanggal</span>
            <span class="font-semibold text-slate-700 dark:text-slate-200">{{ DateToIndo($faktur->tanggal) }}</span>
        </div>
        <div class="flex items-start justify-between sm:justify-start sm:gap-3">
            <span class="text-slate-400 w-24 shrink-0">Jenis Transaksi</span>
            @if ($faktur->jenis_transaksi == 'T')
                <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[10.5px] font-bold bg-emerald-50 text-emerald-700 dark:bg-emerald-950/60 dark:text-emerald-300">
                    Tunai
                </span>
            @else
                <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[10.5px] font-bold bg-amber-50 text-amber-700 dark:bg-amber-950/60 dark:text-amber-300">
                    Kredit
                </span>
            @endif
        </div>
        <div class="flex items-start justify-between sm:justify-start sm:gap-3 sm:col-span-2">
            <span class="text-slate-400 w-24 shrink-0">Pelanggan</span>
            <span class="font-bold text-slate-900 dark:text-white">
                <span class="font-mono-num text-[#284c9a] dark:text-blue-400">{{ $faktur->kode_pelanggan }}</span> - {{ textUpperCase($faktur->nama_pelanggan) }}
            </span>
        </div>
        <div class="flex items-start justify-between sm:justify-start sm:gap-3 sm:col-span-2">
            <span class="text-slate-400 w-24 shrink-0">Alamat</span>
            <span class="text-slate-600 dark:text-slate-300 leading-relaxed text-[11.5px]">{{ $faktur->alamat_pelanggan ?: '-' }}</span>
        </div>
    </div>
</div>

<form action="{{ route('visitpelanggan.update', Crypt::encrypt($visit->kode_visit)) }}" method="POST" id="frmvisitpelanggan" class="space-y-3" autocomplete="off">
    @csrf
    @method('PUT')

    <!-- Tanggal Visit -->
    <div class="space-y-1">
        <label for="tanggal_visit_edit" class="block text-xs font-bold text-slate-700 dark:text-slate-200">
            Tanggal <span class="text-rose-500">*</span>
        </label>
        <div class="relative">
            <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                <i data-lucide="calendar" class="w-4 h-4"></i>
            </div>
            <input type="text" id="tanggal_visit_edit" name="tanggal" readonly
                value="{{ $visit->tanggal }}"
                placeholder="Pilih Tanggal Visit"
                class="flatpickr-date w-full h-[36px] pl-9 pr-3 rounded-lg border border-slate-200/80 dark:border-slate-700 bg-white dark:bg-slate-800 text-xs font-semibold text-slate-800 dark:text-slate-100 placeholder:text-slate-400 focus:outline-hidden focus:border-[#284c9a] focus:ring-1 focus:ring-[#284c9a] transition">
        </div>
    </div>

    <!-- Jenis Kunjungan -->
    <div class="space-y-1">
        <label class="block text-xs font-bold text-slate-700 dark:text-slate-200">
            Jenis Kunjungan <span class="text-rose-500">*</span>
        </label>
        <div class="grid grid-cols-2 gap-2.5">
            <!-- OTD -->
            <label for="jenis_kunjungan_otd_edit"
                class="visit-type-label relative flex items-center gap-2.5 p-2.5 rounded-lg border border-slate-200/80 dark:border-slate-700 bg-white dark:bg-slate-800 hover:bg-slate-50 dark:hover:bg-slate-700/50 cursor-pointer transition select-none">
                <input type="radio" name="jenis_kunjungan" id="jenis_kunjungan_otd_edit" value="OTD"
                    {{ (isset($visit) && $visit->jenis_kunjungan == 'OTD') ? 'checked' : '' }}
                    class="visit-type-radio w-4 h-4 text-[#284c9a] border-slate-300 focus:ring-[#284c9a] cursor-pointer">
                <div>
                    <span class="block text-xs font-bold text-slate-800 dark:text-slate-100">OTD</span>
                    <span class="block text-[10.5px] text-slate-400 font-medium">On The Desk</span>
                </div>
            </label>

            <!-- OTS -->
            <label for="jenis_kunjungan_ots_edit"
                class="visit-type-label relative flex items-center gap-2.5 p-2.5 rounded-lg border border-slate-200/80 dark:border-slate-700 bg-white dark:bg-slate-800 hover:bg-slate-50 dark:hover:bg-slate-700/50 cursor-pointer transition select-none">
                <input type="radio" name="jenis_kunjungan" id="jenis_kunjungan_ots_edit" value="OTS"
                    {{ (!isset($visit) || !$visit->jenis_kunjungan || $visit->jenis_kunjungan == 'OTS') ? 'checked' : '' }}
                    class="visit-type-radio w-4 h-4 text-[#284c9a] border-slate-300 focus:ring-[#284c9a] cursor-pointer">
                <div>
                    <span class="block text-xs font-bold text-slate-800 dark:text-slate-100">OTS</span>
                    <span class="block text-[10.5px] text-slate-400 font-medium">On the Spot</span>
                </div>
            </label>
        </div>
    </div>

    <!-- Hasil Konfirmasi -->
    <div class="space-y-1">
        <label for="hasil_konfirmasi_visit_edit" class="block text-xs font-bold text-slate-700 dark:text-slate-200">
            Hasil Konfirmasi <span class="text-rose-500">*</span>
        </label>
        <textarea id="hasil_konfirmasi_visit_edit" name="hasil_konfirmasi" rows="2"
            placeholder="Masukkan hasil konfirmasi..."
            class="w-full p-2.5 rounded-lg border border-slate-200/80 dark:border-slate-700 bg-white dark:bg-slate-800 text-xs font-medium text-slate-800 dark:text-slate-100 placeholder:text-slate-400 focus:outline-hidden focus:border-[#284c9a] focus:ring-1 focus:ring-[#284c9a] transition">{{ $visit->hasil_konfirmasi }}</textarea>
    </div>

    <!-- Note -->
    <div class="space-y-1">
        <label for="note_visit_edit" class="block text-xs font-bold text-slate-700 dark:text-slate-200">
            Note <span class="text-rose-500">*</span>
        </label>
        <textarea id="note_visit_edit" name="note" rows="2"
            placeholder="Masukkan catatan visit..."
            class="w-full p-2.5 rounded-lg border border-slate-200/80 dark:border-slate-700 bg-white dark:bg-slate-800 text-xs font-medium text-slate-800 dark:text-slate-100 placeholder:text-slate-400 focus:outline-hidden focus:border-[#284c9a] focus:ring-1 focus:ring-[#284c9a] transition">{{ $visit->note }}</textarea>
    </div>

    <!-- Saran / Keluhan -->
    <div class="space-y-1">
        <label for="saran_visit_edit" class="block text-xs font-bold text-slate-700 dark:text-slate-200">
            Saran / Keluhan <span class="text-rose-500">*</span>
        </label>
        <textarea id="saran_visit_edit" name="saran" rows="2"
            placeholder="Masukkan saran atau keluhan pelanggan..."
            class="w-full p-2.5 rounded-lg border border-slate-200/80 dark:border-slate-700 bg-white dark:bg-slate-800 text-xs font-medium text-slate-800 dark:text-slate-100 placeholder:text-slate-400 focus:outline-hidden focus:border-[#284c9a] focus:ring-1 focus:ring-[#284c9a] transition">{{ $visit->saran }}</textarea>
    </div>

    <!-- Action OM -->
    <div class="space-y-1">
        <label for="act_om_visit_edit" class="block text-xs font-bold text-slate-700 dark:text-slate-200">
            Action OM <span class="text-rose-500">*</span>
        </label>
        <textarea id="act_om_visit_edit" name="act_om" rows="2"
            placeholder="Masukkan tindakan tindak lanjut Operation Manager..."
            class="w-full p-2.5 rounded-lg border border-slate-200/80 dark:border-slate-700 bg-white dark:bg-slate-800 text-xs font-medium text-slate-800 dark:text-slate-100 placeholder:text-slate-400 focus:outline-hidden focus:border-[#284c9a] focus:ring-1 focus:ring-[#284c9a] transition">{{ $visit->act_om }}</textarea>
    </div>

    <!-- Submit Button -->
    <div class="pt-2">
        <button type="submit" id="btnSimpanVisit"
            class="w-full h-[36px] rounded-lg bg-[#284c9a] hover:bg-[#1e3a78] active:bg-[#152a56] text-white text-xs font-bold shadow-xs transition flex items-center justify-center gap-1.5 cursor-pointer">
            <i data-lucide="send" class="w-3.5 h-3.5"></i>
            <span class="btn-text">Simpan</span>
        </button>
    </div>
</form>

<script>
    $(document).ready(function() {
        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }

        const $form = $("#frmvisitpelanggan");

        // Initialize Flatpickr
        $form.find(".flatpickr-date").flatpickr({
            dateFormat: "Y-m-d",
            allowInput: false
        });

        // Toggle radio card styling
        function updateRadioStyles() {
            $form.find(".visit-type-label").removeClass("!border-[#284c9a] !ring-1 !ring-[#284c9a] bg-blue-50/50 dark:bg-blue-950/30");
            const $checked = $form.find(".visit-type-radio:checked");
            if ($checked.length) {
                $checked.closest(".visit-type-label").addClass("!border-[#284c9a] !ring-1 !ring-[#284c9a] bg-blue-50/50 dark:bg-blue-950/30");
            }
        }
        updateRadioStyles();
        $form.find(".visit-type-radio").on("change", function() {
            updateRadioStyles();
            clearFieldError($(this));
        });

        // Error helper functions
        function showFieldError($el, message) {
            clearFieldError($el);
            const $parent = $el.closest('.space-y-1');
            $parent.find('input[type="text"], textarea').addClass('!border-rose-500 !ring-1 !ring-rose-500');
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
            $parent.find('input[type="text"], textarea').removeClass('!border-rose-500 !ring-1 !ring-rose-500');
            $parent.find('i[data-lucide]').removeClass('text-rose-500').addClass('text-slate-400');
            $parent.find('.field-error-msg').remove();
        }

        // Live cleanup on change/input
        $form.on('input change', 'input, textarea', function() {
            clearFieldError($(this));
        });

        // Form Submission with Validation
        $form.on('submit', function(e) {
            let hasError = false;
            let $firstErrorField = null;

            const $tanggal = $form.find("#tanggal_visit_edit");
            const tanggalVal = $tanggal.val().trim();

            const $jenis = $form.find('input[name="jenis_kunjungan"]:checked');

            const $hasil = $form.find("#hasil_konfirmasi_visit_edit");
            const hasilVal = $hasil.val().trim();

            const $note = $form.find("#note_visit_edit");
            const noteVal = $note.val().trim();

            const $saran = $form.find("#saran_visit_edit");
            const saranVal = $saran.val().trim();

            const $actOm = $form.find("#act_om_visit_edit");
            const actOmVal = $actOm.val().trim();

            // 1. Validasi Tanggal
            if (!tanggalVal) {
                showFieldError($tanggal, "Tanggal Visit harus diisi!");
                if (!hasError) { hasError = true; $firstErrorField = $tanggal; }
            } else {
                clearFieldError($tanggal);
            }

            // 2. Validasi Jenis Kunjungan
            if (!$jenis.length) {
                const $radioContainer = $form.find('input[name="jenis_kunjungan"]').first();
                showFieldError($radioContainer, "Jenis Kunjungan harus dipilih!");
                if (!hasError) { hasError = true; $firstErrorField = $radioContainer; }
            }

            // 3. Validasi Hasil Konfirmasi
            if (!hasilVal) {
                showFieldError($hasil, "Hasil Konfirmasi harus diisi!");
                if (!hasError) { hasError = true; $firstErrorField = $hasil; }
            } else {
                clearFieldError($hasil);
            }

            // 4. Validasi Note
            if (!noteVal) {
                showFieldError($note, "Note harus diisi!");
                if (!hasError) { hasError = true; $firstErrorField = $note; }
            } else {
                clearFieldError($note);
            }

            // 5. Validasi Saran / Keluhan
            if (!saranVal) {
                showFieldError($saran, "Saran / Keluhan harus diisi!");
                if (!hasError) { hasError = true; $firstErrorField = $saran; }
            } else {
                clearFieldError($saran);
            }

            // 6. Validasi Action OM
            if (!actOmVal) {
                showFieldError($actOm, "Action OM harus diisi!");
                if (!hasError) { hasError = true; $firstErrorField = $actOm; }
            } else {
                clearFieldError($actOm);
            }

            if (hasError) {
                e.preventDefault();
                if ($firstErrorField) {
                    $firstErrorField.focus();
                }
                return false;
            }

            // Double submit prevention
            const $btn = $form.find("#btnSimpanVisit");
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
