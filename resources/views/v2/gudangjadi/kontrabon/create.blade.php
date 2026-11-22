@extends('layouts.app')
@section('titlepage', 'Buat Kontra Bon Angkutan')

@section('navigasi')
    <div class="flex items-center gap-1.5 text-xs">
        <span class="text-slate-400">Home</span>
        <i data-lucide="chevron-right" class="w-3 h-3 text-slate-400"></i>
        <span class="text-slate-400">Gudang Jadi</span>
        <i data-lucide="chevron-right" class="w-3 h-3 text-slate-400"></i>
        <span class="text-slate-400">Kontrabon</span>
        <i data-lucide="chevron-right" class="w-3 h-3 text-slate-400"></i>
        <span class="text-brand-600 dark:text-brand-400 font-bold">Buat Kontrabon</span>
    </div>
@endsection

@section('content')
<div class="space-y-3.5 w-full">

    <!-- PAGE TITLE BAR -->
    <div class="flex items-center justify-between">
        <div>
            <h3 class="text-base font-black text-slate-900 dark:text-white leading-tight">Buat Kontra Bon Angkutan</h3>
            <p class="text-[11px] text-slate-400 mt-0.5">Pembuatan tagihan kontrabon armada angkutan berdasarkan surat jalan.</p>
        </div>
        <a href="{{ route('kontrabonangkutan.index') }}"
            class="inline-flex items-center justify-center gap-1.5 h-[34px] px-3.5 rounded-lg border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 text-slate-700 dark:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-800 text-xs font-bold transition shadow-2xs">
            <i data-lucide="arrow-left" class="w-3.5 h-3.5"></i>
            <span>Kembali</span>
        </a>
    </div>

    <form action="{{ route('kontrabonangkutan.store') }}" method="POST" id="formKontrabon" autocomplete="off">
        @csrf
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-3.5 items-start">
            
            <!-- KOLOM KIRI: DATA UTAMA KONTRABON (3 COLUMNS) -->
            <div class="lg:col-span-3 rounded-xl bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 p-4 shadow-2xs space-y-3">
                <div class="flex items-center gap-2 pb-2 border-b border-slate-100 dark:border-slate-800">
                    <div class="w-7 h-7 rounded-lg bg-blue-50 dark:bg-blue-900/40 text-[#284c9a] dark:text-blue-400 flex items-center justify-center">
                        <i data-lucide="file-text" class="w-4 h-4"></i>
                    </div>
                    <span class="text-xs font-bold uppercase tracking-wider text-slate-800 dark:text-slate-100">Informasi Kontrabon</span>
                </div>

                <!-- No. Kontrabon -->
                <div class="field-group">
                    <label for="no_kontrabon" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                        No. Kontrabon
                    </label>
                    <div class="relative">
                        <div class="input-prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                            <i data-lucide="barcode" class="w-3.5 h-3.5"></i>
                        </div>
                        <input type="text" name="no_kontrabon" id="no_kontrabon" placeholder="(Auto Generated / Manual)"
                            class="w-full h-[36px] pl-8 pr-3 text-xs font-bold font-mono-num rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-[#284c9a] dark:text-blue-400 placeholder-slate-400 focus:outline-hidden focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition">
                    </div>
                </div>

                <!-- Tanggal -->
                <div class="field-group">
                    <label for="tanggal" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                        Tanggal Kontrabon <span class="text-rose-500">*</span>
                    </label>
                    <div class="relative">
                        <div class="input-prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10 transition-colors">
                            <i data-lucide="calendar" class="w-3.5 h-3.5"></i>
                        </div>
                        <input type="text" name="tanggal" id="tanggal" placeholder="Pilih Tanggal" readonly
                            class="flatpickr-date w-full h-[36px] pl-8 pr-3 text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition cursor-pointer">
                    </div>
                </div>

                <!-- Armada Angkutan -->
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
                            @foreach ($angkutan as $a)
                                <option value="{{ $a->kode_angkutan }}">{{ textUpperCase($a->nama_angkutan) }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <!-- KOLOM KANAN: DETAIL SURAT JALAN & TOTAL (9 COLUMNS) -->
            <div class="lg:col-span-9 rounded-xl bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 p-4 shadow-2xs space-y-3.5">
                
                <!-- METRIC STRIP TOTAL KONTRA BON (PACIFIC NAVY) -->
                <div class="rounded-xl overflow-hidden shadow-xs bg-[#284c9a] text-white p-3.5 flex items-center justify-between">
                    <div>
                        <span class="block text-[11px] font-semibold text-white/75 uppercase tracking-wider">Total Tagihan Kontrabon</span>
                        <span class="text-xs text-white/60">Akumulasi tarif dari surat jalan yang dipilih</span>
                    </div>
                    <div class="text-right">
                        <span class="text-xs text-white/75 mr-1 font-mono-num">Rp</span>
                        <span id="grandtotal_text" class="text-2xl font-black font-mono-num text-white">0</span>
                    </div>
                </div>

                <!-- PILIH NO. DOKUMEN SURAT JALAN & TOMBOL TAMBAH (ANTI-LAYOUT SHIFT) -->
                <div class="rounded-lg border border-slate-200/80 dark:border-slate-800 bg-slate-50/60 dark:bg-slate-800/30 p-3">
                    <div class="grid grid-cols-1 sm:grid-cols-12 gap-2.5 items-start">
                        <div class="sm:col-span-9 field-group">
                            <label for="no_dok" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                                Pilih Surat Jalan (No. Dokumen | Tanggal | Tujuan | Tarif)
                            </label>
                            <div class="relative">
                                <div class="input-prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-20">
                                    <i data-lucide="file-text" class="w-3.5 h-3.5"></i>
                                </div>
                                <select name="no_dok" id="no_dok" class="select2Nodokumen w-full">
                                    <option value="">Pilih Angkutan Terlebih Dahulu</option>
                                </select>
                            </div>
                        </div>

                        <div class="sm:col-span-3 flex flex-col">
                            <label class="hidden sm:block text-xs font-bold text-transparent mb-1 select-none pointer-events-none">&nbsp;</label>
                            <button type="button" id="btnTambahitem"
                                class="h-[36px] w-full inline-flex items-center justify-center gap-1 px-3 rounded-lg bg-brand-600 hover:bg-brand-700 active:bg-brand-800 text-white text-xs font-bold transition shadow-xs cursor-pointer">
                                <i data-lucide="plus" class="w-3.5 h-3.5"></i>
                                <span>Tambah Item</span>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- TABEL SURAT JALAN YANG DIPILIH -->
                <div class="rounded-xl border border-slate-200/80 dark:border-slate-800 overflow-hidden shadow-2xs">
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse whitespace-nowrap">
                            <thead>
                                <tr class="bg-[#284c9a] text-white">
                                    <th colspan="5" class="py-2 px-3.5">
                                        <div class="flex items-center gap-1.5 text-xs font-bold uppercase tracking-wider">
                                            <i data-lucide="list" class="w-4 h-4 text-white/80"></i>
                                            <span>Rincian Surat Jalan Terlampir</span>
                                        </div>
                                    </th>
                                </tr>
                                <tr class="bg-[#1e3a78] text-white text-[11px] font-bold uppercase tracking-wider">
                                    <th class="py-1.5 px-3">No. Dokumen</th>
                                    <th class="py-1.5 px-3">Tanggal</th>
                                    <th class="py-1.5 px-3">Tujuan</th>
                                    <th class="py-1.5 px-3 text-right">Tarif</th>
                                    <th class="py-1.5 px-3 text-center w-12">#</th>
                                </tr>
                            </thead>
                            <tbody id="loadsuratjalan" class="divide-y divide-slate-100 dark:divide-slate-800 text-xs text-slate-700 dark:text-slate-300">
                                <!-- Dynamic Rows -->
                            </tbody>
                            <tfoot class="bg-slate-50 dark:bg-slate-800/60 border-t border-slate-200 dark:border-slate-700 text-xs font-bold">
                                <tr>
                                    <td colspan="3" class="py-2 px-3 text-slate-700 dark:text-slate-300 uppercase tracking-wider">Total Tagihan</td>
                                    <td id="grandtotal" class="py-2 px-3 text-right font-mono-num text-slate-900 dark:text-white text-sm">0</td>
                                    <td></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>

                <!-- PERSETUJUAN & SUBMIT BUTTON -->
                <div class="pt-2 border-t border-slate-100 dark:border-slate-800 space-y-2.5">
                    <label class="flex items-center gap-2 cursor-pointer select-none text-xs font-semibold text-slate-700 dark:text-slate-300">
                        <input type="checkbox" name="aggrement" id="defaultCheck3" value="aggrement"
                            class="agreement w-4 h-4 rounded border-slate-300 dark:border-slate-700 text-brand-600 focus:ring-brand-500 transition">
                        <span>Yakin data kontrabon sudah benar dan siap disimpan?</span>
                    </label>

                    <div id="saveButton" class="hidden">
                        <button type="submit" id="btnSimpan"
                            class="w-full h-[36px] inline-flex items-center justify-center gap-1.5 rounded-lg bg-[#284c9a] hover:bg-[#1e3a78] active:bg-[#152a56] text-white text-xs font-bold transition shadow-xs cursor-pointer">
                            <i data-lucide="send" class="w-3.5 h-3.5"></i>
                            <span>Simpan Kontrabon</span>
                        </button>
                    </div>
                </div>

            </div>

        </div>
    </form>

</div>
@endsection

@push('myscript')
<script>
$(function() {
    const $form = $("#formKontrabon");

    // Initialize Flatpickr
    $form.find(".flatpickr-date").flatpickr({
        dateFormat: "Y-m-d"
    });

    function initSelect2($select, placeholder) {
        if ($select.length) {
            $select.select2({
                placeholder: placeholder,
                allowClear: true,
                width: '100%'
            });
        }
    }

    initSelect2($form.find('.select2Kodeangkutan'), 'Pilih Angkutan');
    initSelect2($form.find('.select2Nodokumen'), 'Pilih No. Dokumen');

    function removeSpecialCharacters(str) {
        return (str || '').replace(/[^a-zA-Z0-9]/g, '');
    }

    function convertNumber(number) {
        if (!number) return 0;
        let formatted = (number + '').replace(/\./g, '');
        formatted = formatted.replace(/,/g, '.');
        return parseFloat(formatted) || 0;
    }

    function numberFormat(number) {
        return new Intl.NumberFormat('id-ID').format(number || 0);
    }

    function calculateTotal() {
        let grandTotal = 0;
        $('.totaltarif').each(function() {
            grandTotal += convertNumber($(this).text());
        });
        $('#grandtotal').text(numberFormat(grandTotal));
        $('#grandtotal_text').text(numberFormat(grandTotal));
    }

    // Ajax load surat jalan by angkutan
    function getsuratjalanbyangkutan() {
        const kode_angkutan = $form.find("#kode_angkutan").val();
        if (kode_angkutan) {
            $form.find("#no_dok").load(`/suratjalanangkutan/${kode_angkutan}/getsuratjalanbyangkutan`, function() {
                $form.find('.select2Nodokumen').trigger('change');
            });
        } else {
            $form.find("#no_dok").html('<option value="">Pilih Angkutan Terlebih Dahulu</option>');
        }
    }

    $form.find("#kode_angkutan").on('change', function() {
        getsuratjalanbyangkutan();
        $form.find("#loadsuratjalan").html("");
        calculateTotal();
        clearFieldError($(this));
    });

    // Validasi Interaktif Helpers V2
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

    // Tambah Item Surat Jalan
    $form.find("#btnTambahitem").on('click', function(e) {
        e.preventDefault();
        const no_dok = $form.find("#no_dok").val();
        if (!no_dok) {
            setFieldError($form.find("#no_dok"), "Silakan pilih surat jalan terlebih dahulu");
            return;
        }

        const no_dok_index = removeSpecialCharacters(no_dok);
        if ($('#loadsuratjalan').find('#' + no_dok_index).length > 0) {
            setFieldError($form.find("#no_dok"), "Surat jalan ini sudah ditambahkan ke daftar");
            return;
        }

        const $selectedOption = $form.find("#no_dok option:selected");
        const tanggal = $selectedOption.attr("tanggal") || '-';
        const tujuan = $selectedOption.attr("tujuan") || '-';
        const tarif = parseFloat($selectedOption.attr("tarif")) || 0;

        const tr = `
            <tr id="${no_dok_index}" class="hover:bg-slate-50/80 dark:hover:bg-slate-800/50 transition-colors">
                <input type="hidden" name="no_dok_item[]" value="${no_dok}"/>
                <td class="py-1.5 px-3 font-mono-num font-bold text-[#284c9a] dark:text-blue-400">${no_dok}</td>
                <td class="py-1.5 px-3 font-mono-num">${tanggal}</td>
                <td class="py-1.5 px-3 font-medium text-slate-800 dark:text-slate-100">${tujuan}</td>
                <td class="py-1.5 px-3 text-right font-mono-num font-bold totaltarif">${numberFormat(tarif)}</td>
                <td class="py-1.5 px-3 text-center">
                    <button type="button" data-id="${no_dok_index}"
                        class="delete-item w-6 h-6 rounded-md inline-flex items-center justify-center text-rose-600 hover:text-rose-700 hover:bg-rose-50 dark:hover:bg-rose-950/30 transition cursor-pointer">
                        <i data-lucide="trash-2" class="w-3.5 h-3.5"></i>
                    </button>
                </td>
            </tr>
        `;

        $form.find("#loadsuratjalan").append(tr);
        calculateTotal();
        $form.find("#no_dok").val('').trigger('change');
        clearFieldError($form.find("#no_dok"));
        if (window.lucide) lucide.createIcons();
    });

    // Hapus Item dari Tabel
    $form.on('click', '.delete-item', function(e) {
        e.preventDefault();
        const id = $(this).attr("data-id");
        $(`#${id}`).remove();
        calculateTotal();
    });

    // Agreement Toggle
    $form.find('.agreement').on('change', function() {
        if (this.checked) {
            $form.find("#saveButton").removeClass('hidden');
        } else {
            $form.find("#saveButton").addClass('hidden');
        }
    });

    // Submit Form & Client-side Validation
    $form.on('submit', function(e) {
        let hasError = false;
        let $firstError = null;

        const tanggal = $form.find("#tanggal").val();
        const kode_angkutan = $form.find("#kode_angkutan").val();

        if (!tanggal) {
            setFieldError($form.find("#tanggal"), "Tanggal kontrabon wajib diisi");
            hasError = true;
            $firstError = $form.find("#tanggal");
        }

        if (!kode_angkutan) {
            setFieldError($form.find("#kode_angkutan"), "Armada angkutan wajib dipilih");
            hasError = true;
            if (!$firstError) $firstError = $form.find("#kode_angkutan");
        }

        if ($form.find('#loadsuratjalan tr').length === 0) {
            setFieldError($form.find("#no_dok"), "Daftar surat jalan kontrabon tidak boleh kosong");
            hasError = true;
            if (!$firstError) $firstError = $form.find("#no_dok");
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
});
</script>
@endpush
