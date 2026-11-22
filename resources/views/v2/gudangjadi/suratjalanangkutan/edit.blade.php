<form action="{{ route('suratjalanangkutan.update', Crypt::encrypt($suratjalanangkutan->no_dok)) }}" id="formSuratjalanangkutan" method="POST" autocomplete="off" class="space-y-3">
    @method('PUT')
    @csrf

    <!-- NO. DOKUMEN (READONLY) -->
    <div class="field-group">
        <label for="no_dok" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
            No. Dokumen Surat Jalan
        </label>
        <div class="relative">
            <div class="input-prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                <i data-lucide="barcode" class="w-3.5 h-3.5"></i>
            </div>
            <input type="text" name="no_dok" id="no_dok" readonly value="{{ $suratjalanangkutan->no_dok }}"
                class="w-full h-[36px] pl-8 pr-3 text-xs font-bold font-mono-num rounded-lg border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 text-[#284c9a] dark:text-blue-400 cursor-not-allowed">
        </div>
    </div>

    <!-- TUJUAN & ANGKUTAN -->
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2.5 items-start">
        <!-- Tujuan -->
        <div class="field-group">
            <label for="kode_tujuan" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                Tujuan Pengiriman <span class="text-rose-500">*</span>
            </label>
            <div class="relative">
                <div class="input-prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-20">
                    <i data-lucide="map-pin" class="w-3.5 h-3.5"></i>
                </div>
                <select name="kode_tujuan" id="kode_tujuan" class="select2Kodetujuan w-full">
                    <option value="">Pilih Tujuan</option>
                    @foreach ($tujuan as $d)
                        <option value="{{ $d->kode_tujuan }}" {{ $suratjalanangkutan->kode_tujuan == $d->kode_tujuan ? 'selected' : '' }}>
                            {{ textUpperCase($d->tujuan) }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>

        <!-- Angkutan -->
        <div class="field-group">
            <label for="kode_angkutan" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                Armada Angkutan <span class="text-rose-500">*</span>
            </label>
            <div class="relative">
                <div class="input-prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-20">
                    <i data-lucide="truck" class="w-3.5 h-3.5"></i>
                </div>
                <select name="kode_angkutan" id="kode_angkutan" class="select2Kodeangkutan w-full">
                    <option value="">Pilih Angkutan</option>
                    @foreach ($angkutan as $d)
                        <option value="{{ $d->kode_angkutan }}" {{ $suratjalanangkutan->kode_angkutan == $d->kode_angkutan ? 'selected' : '' }}>
                            {{ textUpperCase($d->nama_angkutan) }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>

    <!-- NO. POLISI -->
    <div class="field-group">
        <label for="no_polisi" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
            No. Polisi <span class="text-rose-500">*</span>
        </label>
        <div class="relative">
            <div class="input-prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10 transition-colors">
                <i data-lucide="car" class="w-3.5 h-3.5"></i>
            </div>
            <input type="text" name="no_polisi" id="no_polisi" value="{{ $suratjalanangkutan->no_polisi }}" placeholder="Contoh: D 1234 ABC"
                class="w-full h-[36px] pl-8 pr-3 text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 placeholder-slate-400 focus:outline-hidden focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition">
        </div>
    </div>

    <!-- TARIF, TEPUNG, BS -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-2.5 items-start">
        <!-- Tarif -->
        <div class="field-group">
            <label for="tarif" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                Tarif Angkutan <span class="text-rose-500">*</span>
            </label>
            <div class="relative">
                <div class="input-prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10 transition-colors">
                    <i data-lucide="banknote" class="w-3.5 h-3.5"></i>
                </div>
                <input type="text" name="tarif" id="tarif" value="{{ formatAngka($suratjalanangkutan->tarif) }}" placeholder="0"
                    class="money w-full h-[36px] pl-8 pr-3 text-right text-xs font-bold font-mono-num rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition">
            </div>
        </div>

        <!-- Tepung -->
        <div class="field-group">
            <label for="tepung" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                Tepung
            </label>
            <div class="relative">
                <div class="input-prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10 transition-colors">
                    <i data-lucide="coins" class="w-3.5 h-3.5"></i>
                </div>
                <input type="text" name="tepung" id="tepung" value="{{ formatAngka($suratjalanangkutan->tepung) }}" placeholder="0"
                    class="money w-full h-[36px] pl-8 pr-3 text-right text-xs font-bold font-mono-num rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition">
            </div>
        </div>

        <!-- BS -->
        <div class="field-group">
            <label for="bs" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                Biaya Lain (BS)
            </label>
            <div class="relative">
                <div class="input-prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10 transition-colors">
                    <i data-lucide="wallet" class="w-3.5 h-3.5"></i>
                </div>
                <input type="text" name="bs" id="bs" value="{{ formatAngka($suratjalanangkutan->bs) }}" placeholder="0"
                    class="money w-full h-[36px] pl-8 pr-3 text-right text-xs font-bold font-mono-num rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition">
            </div>
        </div>
    </div>

    <!-- TOMBOL SUBMIT -->
    <div class="pt-2">
        <button type="submit" id="btnSimpan"
            class="w-full h-[36px] inline-flex items-center justify-center gap-1.5 rounded-lg bg-[#284c9a] hover:bg-[#1e3a78] active:bg-[#152a56] text-white text-xs font-bold transition shadow-xs cursor-pointer">
            <i data-lucide="send" class="w-3.5 h-3.5"></i>
            <span>Simpan Perubahan</span>
        </button>
    </div>
</form>

<script>
$(function() {
    const $form = $("#formSuratjalanangkutan");

    $form.find(".money").maskMoney({ thousands: '.', decimal: ',', precision: 2 });

    function initSelect2($select, placeholder) {
        if ($select.length) {
            $select.select2({
                placeholder: placeholder,
                allowClear: true,
                width: '100%'
            });
        }
    }

    initSelect2($form.find('.select2Kodetujuan'), 'Pilih Tujuan');
    initSelect2($form.find('.select2Kodeangkutan'), 'Pilih Angkutan');

    // Validasi Interaktif V2 Helpers
    function setFieldError($field, message) {
        const $group = $field.closest('.field-group');
        $group.addClass('has-error');
        $field.addClass('is-invalid !border-rose-500 !ring-1 !ring-rose-500');
        $group.find('.select2-selection').addClass('is-invalid !border-rose-500 !ring-1 !ring-rose-500');
        $group.find('.select2-container').addClass('is-invalid');
        $group.find('.input-prefix-icon').addClass('!text-rose-500');
        $group.find('.field-error-msg').remove();
        $group.append(`
            <p class="field-error-msg text-[11px] font-semibold text-rose-600 dark:text-rose-400 mt-1 pl-1 flex items-center gap-1">
                <svg class="w-3.5 h-3.5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <circle cx="12" cy="12" r="10"></circle>
                    <line x1="12" y1="8" x2="12" y2="12"></line>
                    <line x1="12" y1="16" x2="12.01" y2="16"></line>
                </svg>
                <span>${message}</span>
            </p>
        `);
    }

    function clearFieldError($field) {
        const $group = $field.closest('.field-group');
        $group.removeClass('has-error');
        $field.removeClass('is-invalid !border-rose-500 !ring-1 !ring-rose-500');
        $group.find('.select2-selection').removeClass('is-invalid !border-rose-500 !ring-1 !ring-rose-500');
        $group.find('.select2-container').removeClass('is-invalid');
        $group.find('.input-prefix-icon').removeClass('!text-rose-500');
        $group.find('.field-error-msg').remove();
    }

    $form.find("input, select").on('input change select2:select select2:clear', function() {
        clearFieldError($(this));
    });

    $form.on('submit', function(e) {
        let hasError = false;
        let $firstError = null;

        const kode_tujuan = $form.find("#kode_tujuan").val();
        const kode_angkutan = $form.find("#kode_angkutan").val();
        const no_polisi = $form.find("#no_polisi").val();
        const tarif = $form.find("#tarif").val();

        if (!kode_tujuan) {
            setFieldError($form.find("#kode_tujuan"), "Tujuan wajib dipilih");
            hasError = true;
            $firstError = $form.find("#kode_tujuan");
        }

        if (!kode_angkutan) {
            setFieldError($form.find("#kode_angkutan"), "Armada angkutan wajib dipilih");
            hasError = true;
            if (!$firstError) $firstError = $form.find("#kode_angkutan");
        }

        if (!no_polisi) {
            setFieldError($form.find("#no_polisi"), "No. Polisi wajib diisi");
            hasError = true;
            if (!$firstError) $firstError = $form.find("#no_polisi");
        }

        if (!tarif || tarif === "0") {
            setFieldError($form.find("#tarif"), "Tarif angkutan wajib diisi");
            hasError = true;
            if (!$firstError) $firstError = $form.find("#tarif");
        }

        if (hasError) {
            e.preventDefault();
            if ($firstError) $firstError.focus();
            return false;
        }

        const $btn = $form.find("#btnSimpan");
        $btn.prop('disabled', true).addClass('opacity-75 cursor-not-allowed pointer-events-none');
        $btn.html(`
            <svg class="animate-spin h-3.5 w-3.5 text-white shrink-0 inline-block" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            <span>Menyimpan...</span>
        `);
    });

    if (window.lucide) lucide.createIcons();
});
</script>
