@php
    $roles_show_cabang = config('global.roles_show_cabang');
    $canFilterCabang = auth()->user()->hasAnyRole($roles_show_cabang);
@endphp

<form method="POST" action="{{ route('laporangudangcabang.cetakmutasidpb') }}" id="frmMutasidpb" target="_blank" class="space-y-3.5" autocomplete="off">
    @csrf

    @if ($canFilterCabang)
        <!-- Cabang (Select2 V2) -->
        <div class="field-group">
            <label for="kode_cabang_mutasidpb" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                Cabang <span class="text-rose-500">*</span>
            </label>
            <div class="relative">
                <div class="input-prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-20 transition-colors">
                    <i data-lucide="building-2" class="w-4 h-4"></i>
                </div>
                <select name="kode_cabang_mutasidpb" id="kode_cabang_mutasidpb"
                    class="select2Kodecabangmutasidpb w-full h-[36px] text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100">
                    <option value="">Pilih Cabang</option>
                    @foreach ($cabang as $c)
                        <option value="{{ $c->kode_cabang }}">{{ strtoupper($c->nama_cabang) }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    @else
        <input type="hidden" name="kode_cabang_mutasidpb" id="kode_cabang_mutasidpb" value="{{ auth()->user()->kode_cabang }}">
    @endif

    <!-- Produk (Select2 V2) -->
    <div class="field-group">
        <label for="kode_produk_mutasidpb" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
            Produk <span class="text-rose-500">*</span>
        </label>
        <div class="relative">
            <div class="input-prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-20 transition-colors">
                <i data-lucide="package" class="w-4 h-4"></i>
            </div>
            <select name="kode_produk_mutasidpb" id="kode_produk_mutasidpb"
                class="select2Kodeprodukmutasidpb w-full h-[36px] text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100">
                <option value="">Pilih Produk</option>
                @foreach ($produk as $p)
                    <option value="{{ $p->kode_produk }}">{{ $p->kode_produk }} - {{ $p->nama_produk }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <!-- Tanggal Periode (Dari & Sampai) -->
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 items-start">
        <div class="field-group">
            <label for="dari_mutasidpb" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                Dari Tanggal <span class="text-rose-500">*</span>
            </label>
            <div class="relative">
                <div class="input-prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10 transition-colors">
                    <i data-lucide="calendar" class="w-4 h-4"></i>
                </div>
                <input type="text" name="dari" id="dari_mutasidpb" placeholder="Pilih Tanggal Mulai" readonly
                    class="flatpickr-date w-full h-[36px] pl-9 pr-3 text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition cursor-pointer">
            </div>
        </div>

        <div class="field-group">
            <label for="sampai_mutasidpb" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                Sampai Tanggal <span class="text-rose-500">*</span>
            </label>
            <div class="relative">
                <div class="input-prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10 transition-colors">
                    <i data-lucide="calendar" class="w-4 h-4"></i>
                </div>
                <input type="text" name="sampai" id="sampai_mutasidpb" placeholder="Pilih Tanggal Akhir" readonly
                    class="flatpickr-date w-full h-[36px] pl-9 pr-3 text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition cursor-pointer">
            </div>
        </div>
    </div>

    <!-- ACTION BUTTONS (Cetak & Export) -->
    <div class="flex items-center gap-2.5 pt-3">
        <button type="submit" name="submitButton" id="btnCetakMutasidpb"
            class="flex-1 inline-flex items-center justify-center gap-2 h-[36px] px-4 rounded-lg bg-brand-600 hover:bg-brand-700 active:bg-brand-800 text-white text-xs font-bold transition shadow-xs cursor-pointer">
            <i data-lucide="printer" class="w-4 h-4"></i>
            <span>Cetak Dokumen</span>
        </button>
        <button type="submit" name="exportButton" id="btnExportMutasidpb"
            class="inline-flex items-center justify-center gap-1.5 h-[36px] px-4 rounded-lg bg-emerald-600 hover:bg-emerald-700 active:bg-emerald-800 text-white text-xs font-bold transition shadow-xs cursor-pointer shrink-0">
            <i data-lucide="file-spreadsheet" class="w-4 h-4"></i>
            <span>Export Excel</span>
        </button>
    </div>
</form>

@push('myscript')
<script>
    $(function() {
        const $form = $('#frmMutasidpb');

        // Select2 Cabang
        const $select2Cabang = $form.find('.select2Kodecabangmutasidpb');
        if ($select2Cabang.length) {
            $select2Cabang.select2({
                placeholder: 'Pilih Cabang',
                allowClear: true,
                width: '100%'
            });
        }

        // Select2 Produk
        const $select2Produk = $form.find('.select2Kodeprodukmutasidpb');
        if ($select2Produk.length) {
            $select2Produk.select2({
                placeholder: 'Pilih Produk',
                allowClear: true,
                width: '100%'
            });
        }

        // Inline error handling
        function showError($field, message) {
            clearError($field);
            const $group = $field.closest('.field-group');
            $group.find('.input-prefix-icon').addClass('text-rose-500').removeClass('text-slate-400');
            
            if ($field.hasClass('select2Kodecabangmutasidpb') || $field.hasClass('select2Kodeprodukmutasidpb')) {
                $group.find('.select2-container--default .select2-selection--single').addClass('!border-rose-500 !ring-1 !ring-rose-500');
            } else {
                $field.addClass('border-rose-500 ring-1 ring-rose-500 focus:border-rose-500 focus:ring-rose-500/20');
            }

            const errorHtml = `
                <p class="field-error-msg text-[11px] font-semibold text-rose-600 dark:text-rose-400 mt-1 pl-1 flex items-center gap-1">
                    <svg class="w-3.5 h-3.5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="12" cy="12" r="10"></circle>
                        <line x1="12" y1="8" x2="12" y2="12"></line>
                        <line x1="12" y1="16" x2="12.01" y2="16"></line>
                    </svg>
                    <span>${message}</span>
                </p>
            `;
            $group.append(errorHtml);
        }

        function clearError($field) {
            const $group = $field.closest('.field-group');
            $group.find('.input-prefix-icon').removeClass('text-rose-500').addClass('text-slate-400');
            
            if ($field.hasClass('select2Kodecabangmutasidpb') || $field.hasClass('select2Kodeprodukmutasidpb')) {
                $group.find('.select2-container--default .select2-selection--single').removeClass('!border-rose-500 !ring-1 !ring-rose-500');
            } else {
                $field.removeClass('border-rose-500 ring-1 ring-rose-500 focus:border-rose-500 focus:ring-rose-500/20');
            }
            $group.find('.field-error-msg').remove();
        }

        $form.on('input change', 'input, select', function() {
            clearError($(this));
        });

        $form.find('.select2Kodecabangmutasidpb, .select2Kodeprodukmutasidpb').on('select2:select select2:clear', function() {
            clearError($(this));
        });

        // Form Submit
        $form.on('submit', function(e) {
            let hasError = false;
            let $firstErrorField = null;

            const $kodeCabang = $form.find('#kode_cabang_mutasidpb');
            if ($kodeCabang.length && !$kodeCabang.val()) {
                showError($kodeCabang, 'Silakan pilih cabang terlebih dahulu.');
                hasError = true;
                if (!$firstErrorField) $firstErrorField = $kodeCabang;
            }

            const $kodeProduk = $form.find('#kode_produk_mutasidpb');
            if (!$kodeProduk.val()) {
                showError($kodeProduk, 'Silakan pilih produk terlebih dahulu.');
                hasError = true;
                if (!$firstErrorField) $firstErrorField = $kodeProduk;
            }

            const $dari = $form.find('#dari_mutasidpb');
            if (!$dari.val()) {
                showError($dari, 'Tanggal awal periode harus diisi.');
                hasError = true;
                if (!$firstErrorField) $firstErrorField = $dari;
            }

            const $sampai = $form.find('#sampai_mutasidpb');
            if (!$sampai.val()) {
                showError($sampai, 'Tanggal akhir periode harus diisi.');
                hasError = true;
                if (!$firstErrorField) $firstErrorField = $sampai;
            } else if ($dari.val() && new Date($dari.val()) > new Date($sampai.val())) {
                showError($sampai, 'Tanggal akhir harus lebih besar atau sama dengan tanggal awal.');
                hasError = true;
                if (!$firstErrorField) $firstErrorField = $sampai;
            }

            if (hasError) {
                e.preventDefault();
                if ($firstErrorField) {
                    if ($firstErrorField.hasClass('select2Kodecabangmutasidpb') || $firstErrorField.hasClass('select2Kodeprodukmutasidpb')) {
                        $firstErrorField.select2('open');
                    } else {
                        $firstErrorField.focus();
                    }
                }
                return false;
            }

            // Spinner feedback with temporary timeout
            const $btn = $form.find('#btnCetakMutasidpb');
            const originalHtml = $btn.html();
            $btn.prop('disabled', true).addClass('opacity-75 cursor-not-allowed pointer-events-none');
            $btn.html(`
                <svg class="animate-spin h-3.5 w-3.5 text-white shrink-0 inline-block" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <span>Mencetak...</span>
            `);

            setTimeout(() => {
                $btn.prop('disabled', false).removeClass('opacity-75 cursor-not-allowed pointer-events-none').html(originalHtml);
                if (typeof lucide !== 'undefined') lucide.createIcons();
            }, 2500);
        });
    });
</script>
@endpush
