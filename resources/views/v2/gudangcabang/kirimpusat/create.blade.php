@php
    $roles_show_cabang = config('global.roles_show_cabang');
    $canFilterCabang = auth()->user()->hasAnyRole($roles_show_cabang);
@endphp

<form action="{{ route('kirimpusat.store') }}" method="POST" id="formKirimpusat" class="space-y-4" autocomplete="off">
    @csrf

    <div class="rounded-xl border border-slate-200/80 dark:border-slate-800 p-4">
        <div class="grid grid-cols-1 sm:grid-cols-12 gap-3 items-start">
            
            <!-- Tanggal -->
            <div class="sm:col-span-4 field-group">
                <label for="tanggal_create" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                    Tanggal <span class="text-rose-500">*</span>
                </label>
                <div class="relative">
                    <div class="input-prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10 transition-colors">
                        <i data-lucide="calendar" class="w-4 h-4"></i>
                    </div>
                    <input type="text" name="tanggal" id="tanggal_create" placeholder="Pilih Tanggal" readonly
                        class="flatpickr-date-modal w-full h-[36px] pl-9 pr-3 text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition cursor-pointer">
                </div>
            </div>

            @if ($canFilterCabang)
                <!-- Cabang (Select2 V2) -->
                <div class="sm:col-span-4 field-group">
                    <label for="kode_cabang_create" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                        Cabang <span class="text-rose-500">*</span>
                    </label>
                    <div class="relative">
                        <div class="input-prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-20 transition-colors">
                            <i data-lucide="building-2" class="w-4 h-4"></i>
                        </div>
                        <select name="kode_cabang" id="kode_cabang_create"
                            class="select2KodecabangModal w-full h-[36px] text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100">
                            <option value="">Pilih Cabang</option>
                            @foreach ($cabang as $c)
                                <option value="{{ $c->kode_cabang }}">{{ strtoupper($c->nama_cabang) }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            @else
                <input type="hidden" name="kode_cabang" id="kode_cabang_create" value="{{ auth()->user()->kode_cabang }}">
            @endif

            <!-- Keterangan -->
            <div class="{{ $canFilterCabang ? 'sm:col-span-4' : 'sm:col-span-8' }} field-group">
                <label for="keterangan_create" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                    Keterangan
                </label>
                <div class="relative">
                    <div class="input-prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10 transition-colors">
                        <i data-lucide="file-text" class="w-4 h-4"></i>
                    </div>
                    <input type="text" name="keterangan" id="keterangan_create" placeholder="Keterangan pengiriman (opsional)"
                        class="w-full h-[36px] pl-9 pr-3 text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition">
                </div>
            </div>

        </div>
    </div>

    <!-- DETAIL ITEMS TABLE -->
    <div class="rounded-xl border border-slate-200/80 dark:border-slate-800 overflow-hidden shadow-2xs">
        <div class="overflow-x-auto max-h-[380px]">
            <table class="w-full text-xs text-left border-collapse whitespace-nowrap">
                <thead class="sticky top-0 z-10">
                    <tr class="bg-[#284c9a] text-white">
                        <th colspan="6" class="px-4 py-2 bg-[#284c9a] text-white border-0">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-2">
                                    <i data-lucide="boxes" class="w-3.5 h-3.5 text-blue-200"></i>
                                    <span class="text-xs font-bold uppercase tracking-wider text-white">Rincian Barang Kirim Pusat</span>
                                </div>
                                <span class="text-[11px] text-white/70 font-mono-num">{{ count($produk) }} produk</span>
                            </div>
                        </th>
                    </tr>
                    <tr class="bg-[#1e3a78] text-white text-[11px] border-none">
                        <th class="py-2 px-3 text-center w-12 font-bold uppercase tracking-wider text-white/90 border-none">NO</th>
                        <th class="py-2 px-3 text-center w-28 font-bold uppercase tracking-wider text-white/90 border-none">KODE</th>
                        <th class="py-2 px-3 font-bold uppercase tracking-wider text-white/90 border-none">NAMA PRODUK</th>
                        <th class="py-2 px-3 text-right font-bold uppercase tracking-wider text-white/90 border-none w-28">DUS</th>
                        <th class="py-2 px-3 text-right font-bold uppercase tracking-wider text-white/90 border-none w-28">PACK</th>
                        <th class="py-2 px-3 text-right font-bold uppercase tracking-wider text-white/90 border-none w-28">PCS</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800/80 bg-white dark:bg-slate-900">
                    @foreach ($produk as $d)
                        <tr class="hover:bg-slate-50/80 dark:hover:bg-slate-800/50 transition">
                            <td class="py-1.5 px-3 text-center text-slate-400 font-mono-num">{{ $loop->iteration }}</td>
                            <td class="py-1.5 px-3">
                                <input type="hidden" class="kode_produk" name="kode_produk[]" value="{{ $d->kode_produk }}">
                                <input type="hidden" class="isi_pcs_dus" name="isi_pcs_dus[]" value="{{ $d->isi_pcs_dus }}">
                                <input type="hidden" class="isi_pcs_pack" name="isi_pcs_pack[]" value="{{ $d->isi_pcs_pack }}">
                                <span class="font-mono-num font-bold text-[#284c9a] dark:text-blue-400">{{ $d->kode_produk }}</span>
                            </td>
                            <td class="py-1.5 px-3 font-medium text-slate-800 dark:text-slate-200">
                                {{ $d->nama_produk }}
                            </td>
                            <td class="py-1.5 px-3 text-right">
                                <input type="text" name="jml_dus[]"
                                    class="money jml_dus w-22 text-right px-2 py-1 text-xs font-mono-num font-bold rounded border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:border-brand-500">
                            </td>
                            <td class="py-1.5 px-3 text-right">
                                <input type="text" name="jml_pack[]" {{ empty($d->isi_pcs_pack) ? 'readonly' : '' }}
                                    class="money jml_pack w-22 text-right px-2 py-1 text-xs font-mono-num font-bold rounded border border-slate-200 dark:border-slate-700 {{ empty($d->isi_pcs_pack) ? 'bg-slate-100 dark:bg-slate-800/60 text-slate-400 cursor-not-allowed' : 'bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:border-brand-500' }}">
                            </td>
                            <td class="py-1.5 px-3 text-right">
                                <input type="text" name="jml_pcs[]"
                                    class="money jml_pcs w-22 text-right px-2 py-1 text-xs font-mono-num font-bold rounded border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:border-brand-500">
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- ACTION BUTTONS -->
    <div class="flex items-center justify-end gap-2.5 pt-2">
        <button type="button" class="btn-close-modal h-[36px] px-4 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-700 dark:bg-slate-800 dark:hover:bg-slate-700 dark:text-slate-200 text-xs font-bold transition cursor-pointer">
            Batal
        </button>
        <button type="submit" id="btnSubmitKirimpusat"
            class="inline-flex items-center justify-center gap-2 h-[36px] px-5 rounded-lg bg-brand-600 hover:bg-brand-700 active:bg-brand-800 text-white text-xs font-bold transition shadow-xs cursor-pointer">
            <i data-lucide="send" class="w-4 h-4"></i>
            <span>Simpan Kirim Pusat</span>
        </button>
    </div>

</form>

<script>
    $(function() {
        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }

        const $form = $('#formKirimpusat');
        const $btnSubmit = $('#btnSubmitKirimpusat');

        // Masking
        if (typeof $.fn.maskMoney !== 'undefined') {
            $form.find(".money").maskMoney({
                thousands: '.',
                decimal: ',',
                precision: 0,
                allowZero: true
            });
        }

        // Flatpickr with date range constraint
        $form.find(".flatpickr-date-modal").flatpickr({
            dateFormat: "Y-m-d",
            enable: [{
                from: "{{ $start_periode }}",
                to: "{{ $end_periode }}"
            }]
        });

        // Select2 V2
        const $select2Cabang = $form.find('.select2KodecabangModal');
        if ($select2Cabang.length) {
            $select2Cabang.select2({
                placeholder: 'Pilih Cabang',
                allowClear: true,
                width: '100%'
            });
        }

        // Inline error handling
        function showError($field, message) {
            clearError($field);
            const $group = $field.closest('.field-group');
            $group.find('.input-prefix-icon').addClass('text-rose-500').removeClass('text-slate-400');
            
            if ($field.hasClass('select2KodecabangModal')) {
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
            
            if ($field.hasClass('select2KodecabangModal')) {
                $group.find('.select2-container--default .select2-selection--single').removeClass('!border-rose-500 !ring-1 !ring-rose-500');
            } else {
                $field.removeClass('border-rose-500 ring-1 ring-rose-500 focus:border-rose-500 focus:ring-rose-500/20');
            }
            $group.find('.field-error-msg').remove();
        }

        // Live cleanup
        $form.on('input change', 'input, select', function() {
            clearError($(this));
        });

        $form.find('.select2KodecabangModal').on('select2:select select2:clear', function() {
            clearError($(this));
        });

        // Form Submit Validation
        $form.on('submit', function(e) {
            let hasError = false;
            let $firstErrorField = null;

            const $tanggal = $form.find('#tanggal_create');
            if (!$tanggal.val()) {
                showError($tanggal, 'Tanggal pengiriman harus diisi.');
                hasError = true;
                if (!$firstErrorField) $firstErrorField = $tanggal;
            }

            const $kodeCabang = $form.find('#kode_cabang_create');
            if ($kodeCabang.length && !$kodeCabang.val()) {
                showError($kodeCabang, 'Cabang harus dipilih.');
                hasError = true;
                if (!$firstErrorField) $firstErrorField = $kodeCabang;
            }

            if (hasError) {
                e.preventDefault();
                if ($firstErrorField) {
                    if ($firstErrorField.hasClass('select2KodecabangModal')) {
                        $firstErrorField.select2('open');
                    } else {
                        $firstErrorField.focus();
                    }
                }
                return false;
            }

            // Valid -> show spinner & disable
            $btnSubmit.prop('disabled', true).addClass('opacity-75 cursor-not-allowed pointer-events-none');
            $btnSubmit.html(`
                <svg class="animate-spin h-3.5 w-3.5 text-white shrink-0 inline-block" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <span>Menyimpan...</span>
            `);
        });
    });
</script>
