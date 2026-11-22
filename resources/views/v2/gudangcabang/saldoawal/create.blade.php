@extends('layouts.app')
@section('titlepage', 'Buat Saldo Awal Gudang Cabang')

@section('navigasi')
    <div class="flex items-center gap-1.5 text-xs">
        <span class="text-slate-400">Home</span>
        <i data-lucide="chevron-right" class="w-3 h-3 text-slate-400"></i>
        <span class="text-slate-400">Gudang Cabang</span>
        <i data-lucide="chevron-right" class="w-3 h-3 text-slate-400"></i>
        <a href="{{ route('sagudangcabang.index') }}" class="text-slate-400 hover:text-brand-600">Saldo Awal</a>
        <i data-lucide="chevron-right" class="w-3 h-3 text-slate-400"></i>
        <span class="text-brand-600 dark:text-brand-400 font-bold">Buat Saldo Awal</span>
    </div>
@endsection

@section('content')
<div class="space-y-4 max-w-4xl">

    <!-- PAGE TITLE BAR -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2.5">
        <div>
            <h3 class="text-base font-black text-slate-900 dark:text-white leading-tight">Buat Saldo Awal Gudang Cabang</h3>
            <p class="text-[11px] text-slate-400 mt-0.5">Tetapkan saldo awal fisik persediaan barang gudang cabang (Good Stok / Bad Stok).</p>
        </div>
        <a href="{{ route('sagudangcabang.index') }}"
            class="inline-flex items-center justify-center gap-1.5 h-[34px] px-3.5 rounded-lg border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 hover:bg-slate-50 dark:hover:bg-slate-800/60 text-slate-700 dark:text-slate-300 text-xs font-bold transition shadow-2xs cursor-pointer shrink-0">
            <i data-lucide="arrow-left" class="w-3.5 h-3.5"></i>
            <span>Kembali</span>
        </a>
    </div>

    @php
        $roles_show_cabang = config('global.roles_show_cabang');
        $canFilterCabang = auth()->user()->hasAnyRole($roles_show_cabang);
    @endphp

    <!-- MAIN FORM CARD CONTAINER -->
    <div class="rounded-xl bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 p-5 shadow-2xs transition-colors duration-200">
        <form action="{{ route('sagudangcabang.store') }}" method="POST" id="formCreatesaldoawal" class="space-y-5" autocomplete="off">
            @csrf

            <!-- BARIS PARAMETER & GET SALDO (ANTI-LAYOUT SHIFT: ITEMS-START + SPACER LABEL) -->
            <div class="rounded-lg border border-slate-200/80 dark:border-slate-800 p-4">
                <div class="grid grid-cols-1 sm:grid-cols-12 gap-3 items-start">
                    
                    @if ($canFilterCabang)
                        <!-- Cabang (Select2 V2) -->
                        <div class="sm:col-span-12 field-group">
                            <label for="kode_cabang" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                                Cabang <span class="text-rose-500">*</span>
                            </label>
                            <div class="relative">
                                <div class="input-prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-20 transition-colors">
                                    <i data-lucide="building-2" class="w-4 h-4"></i>
                                </div>
                                <select name="kode_cabang" id="kode_cabang"
                                    class="select2Kodecabang w-full h-[36px] text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100">
                                    <option value="">Pilih Cabang</option>
                                    @foreach ($cabang as $c)
                                        <option value="{{ $c->kode_cabang }}">{{ strtoupper($c->nama_cabang) }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    @endif

                    <!-- Bulan -->
                    <div class="sm:col-span-4 field-group">
                        <label for="bulan" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                            Bulan <span class="text-rose-500">*</span>
                        </label>
                        <div class="relative">
                            <div class="input-prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10 transition-colors">
                                <i data-lucide="calendar" class="w-4 h-4"></i>
                            </div>
                            <select name="bulan" id="bulan"
                                class="w-full h-[36px] pl-9 pr-7 text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition appearance-none cursor-pointer">
                                <option value="">Pilih Bulan</option>
                                @foreach ($list_bulan as $d)
                                    <option value="{{ $d['kode_bulan'] }}">{{ $d['nama_bulan'] }}</option>
                                @endforeach
                            </select>
                            <div class="absolute inset-y-0 right-0 pr-2.5 flex items-center pointer-events-none text-slate-400">
                                <i data-lucide="chevron-down" class="w-4 h-4"></i>
                            </div>
                        </div>
                    </div>

                    <!-- Tahun -->
                    <div class="sm:col-span-3 field-group">
                        <label for="tahun" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                            Tahun <span class="text-rose-500">*</span>
                        </label>
                        <div class="relative">
                            <div class="input-prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10 transition-colors">
                                <i data-lucide="calendar-days" class="w-4 h-4"></i>
                            </div>
                            <select name="tahun" id="tahun"
                                class="w-full h-[36px] pl-9 pr-7 text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition appearance-none cursor-pointer">
                                <option value="">Pilih Tahun</option>
                                @for ($t = $start_year; $t <= date('Y'); $t++)
                                    <option value="{{ $t }}" {{ date('Y') == $t ? 'selected' : '' }}>{{ $t }}</option>
                                @endfor
                            </select>
                            <div class="absolute inset-y-0 right-0 pr-2.5 flex items-center pointer-events-none text-slate-400">
                                <i data-lucide="chevron-down" class="w-4 h-4"></i>
                            </div>
                        </div>
                    </div>

                    <!-- Kondisi (GS / BS) -->
                    <div class="sm:col-span-3 field-group">
                        <label for="kondisi" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                            Kondisi <span class="text-rose-500">*</span>
                        </label>
                        <div class="relative">
                            <div class="input-prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10 transition-colors">
                                <i data-lucide="box" class="w-4 h-4"></i>
                            </div>
                            <select name="kondisi" id="kondisi"
                                class="w-full h-[36px] pl-9 pr-7 text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition appearance-none cursor-pointer">
                                <option value="">Pilih Kondisi</option>
                                <option value="GS">Good Stok (GS)</option>
                                <option value="BS">Bad Stok (BS)</option>
                            </select>
                            <div class="absolute inset-y-0 right-0 pr-2.5 flex items-center pointer-events-none text-slate-400">
                                <i data-lucide="chevron-down" class="w-4 h-4"></i>
                            </div>
                        </div>
                    </div>

                    <!-- Tombol Get Saldo (Anti-Layout Shift with Spacer Label) -->
                    <div class="sm:col-span-2">
                        <label class="hidden sm:block text-xs font-bold text-transparent mb-1 select-none pointer-events-none">&nbsp;</label>
                        <button type="button" id="getsaldo"
                            class="w-full h-[36px] inline-flex items-center justify-center gap-1.5 px-3 rounded-lg bg-emerald-600 hover:bg-emerald-700 active:bg-emerald-800 text-white text-xs font-bold transition shadow-xs cursor-pointer">
                            <i data-lucide="download-cloud" class="w-4 h-4"></i>
                            <span>Get Saldo</span>
                        </button>
                    </div>

                </div>
            </div>

            <!-- DETAIL SALDO AWAL TABLE -->
            <div class="rounded-xl border border-slate-200/80 dark:border-slate-800 overflow-hidden shadow-2xs">
                <div class="overflow-x-auto">
                    <table class="w-full text-xs text-left border-collapse whitespace-nowrap">
                        <thead>
                            <!-- Row 1: Header Strip (colspan=6) -->
                            <tr class="bg-[#284c9a] text-white">
                                <th colspan="6" class="px-4 py-2 bg-[#284c9a] text-white border-0">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center gap-2">
                                            <i data-lucide="boxes" class="w-3.5 h-3.5 text-blue-200"></i>
                                            <span class="text-xs font-bold uppercase tracking-wider text-white">Rincian Saldo Awal Gudang Cabang</span>
                                        </div>
                                        <span id="labelTotalItem" class="text-[11px] text-white/70 font-mono-num">Siap dimuat</span>
                                    </div>
                                </th>
                            </tr>
                            <!-- Row 2: Column Titles -->
                            <tr class="bg-[#1e3a78] text-white text-[11px] border-none">
                                <th class="py-2 px-3 text-center w-12 font-bold uppercase tracking-wider text-white/90 border-none">NO</th>
                                <th class="py-2 px-3 text-center w-28 font-bold uppercase tracking-wider text-white/90 border-none">KODE</th>
                                <th class="py-2 px-3 font-bold uppercase tracking-wider text-white/90 border-none">NAMA PRODUK</th>
                                <th class="py-2 px-3 text-right font-bold uppercase tracking-wider text-white/90 border-none w-28">DUS</th>
                                <th class="py-2 px-3 text-right font-bold uppercase tracking-wider text-white/90 border-none w-28">PACK</th>
                                <th class="py-2 px-3 text-right font-bold uppercase tracking-wider text-white/90 border-none w-28">PCS</th>
                            </tr>
                        </thead>
                        <tbody id="loaddetailsaldo" class="divide-y divide-slate-100 dark:divide-slate-800/80 bg-white dark:bg-slate-900">
                            <tr>
                                <td colspan="6" class="py-12 text-center text-slate-400">
                                    <div class="flex flex-col items-center justify-center gap-2">
                                        <i data-lucide="inbox" class="w-8 h-8 text-slate-300 dark:text-slate-600"></i>
                                        <p class="text-xs">Silakan pilih parameter di atas lalu klik tombol <b>Get Saldo</b> untuk memuat daftar produk.</p>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- ACTION BUTTONS -->
            <div class="flex items-center justify-end gap-2.5 pt-2">
                <a href="{{ route('sagudangcabang.index') }}"
                    class="inline-flex items-center justify-center h-[36px] px-4 rounded-lg border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 hover:bg-slate-50 dark:hover:bg-slate-800/60 text-slate-700 dark:text-slate-300 text-xs font-bold transition cursor-pointer">
                    Batal
                </a>
                <button type="submit" id="btnSubmit"
                    class="inline-flex items-center justify-center gap-2 h-[36px] px-5 rounded-lg bg-brand-600 hover:bg-brand-700 active:bg-brand-800 text-white text-xs font-bold transition shadow-xs cursor-pointer">
                    <i data-lucide="save" class="w-4 h-4"></i>
                    <span>Simpan Saldo Awal</span>
                </button>
            </div>

        </form>
    </div>

</div>
@endsection

@push('myscript')
<script>
    $(function() {
        const $form = $('#formCreatesaldoawal');
        const $btnSubmit = $('#btnSubmit');
        const $btnGetSaldo = $('#getsaldo');

        // Initialize Select2 if present
        const $select2Cabang = $('.select2Kodecabang');
        if ($select2Cabang.length) {
            $select2Cabang.select2({
                placeholder: 'Pilih Cabang',
                allowClear: true,
                width: '100%'
            });
        }

        // Inline error helpers
        function setFieldError($input, message) {
            const $fieldGroup = $input.closest('.field-group');
            $fieldGroup.addClass('has-error');

            if ($input.hasClass('select2-hidden-accessible')) {
                $fieldGroup.find('.select2-selection').addClass('!border-rose-500 !ring-1 !ring-rose-500');
            } else {
                $input.addClass('!border-rose-500 !ring-1 !ring-rose-500');
            }

            $fieldGroup.find('.input-prefix-icon').addClass('!text-rose-500');
            $fieldGroup.find('.field-error-msg').remove();
            $fieldGroup.append(`
                <p class="field-error-msg text-[11px] font-semibold text-rose-600 dark:text-rose-400 mt-1 pl-1 flex items-center gap-1">
                    <svg class="w-3.5 h-3.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <circle cx="12" cy="12" r="10" stroke-width="2"/>
                        <line x1="12" y1="8" x2="12" y2="12" stroke-width="2"/>
                        <circle cx="12" cy="16" r="1" fill="currentColor"/>
                    </svg>
                    <span>${message}</span>
                </p>
            `);
        }

        function clearFieldError($input) {
            const $fieldGroup = $input.closest('.field-group');
            $fieldGroup.removeClass('has-error');

            if ($input.hasClass('select2-hidden-accessible')) {
                $fieldGroup.find('.select2-selection').removeClass('!border-rose-500 !ring-1 !ring-rose-500');
            } else {
                $input.removeClass('!border-rose-500 !ring-1 !ring-rose-500');
            }

            $fieldGroup.find('.input-prefix-icon').removeClass('!text-rose-500');
            $fieldGroup.find('.field-error-msg').remove();
        }

        // Live cleanup
        $form.find('select, input').on('input change select2:select', function() {
            clearFieldError($(this));
        });

        // Load detail saldo via AJAX
        function loaddetailsaldo() {
            let hasError = false;
            let $firstError = null;

            const $kodeCabang = $form.find('#kode_cabang');
            const $bulan = $form.find('#bulan');
            const $tahun = $form.find('#tahun');
            const $kondisi = $form.find('#kondisi');

            if ($kodeCabang.length && !$kodeCabang.val()) {
                setFieldError($kodeCabang, 'Silakan pilih cabang terlebih dahulu');
                hasError = true;
                if (!$firstError) $firstError = $kodeCabang;
            }

            if (!$bulan.val()) {
                setFieldError($bulan, 'Silakan pilih bulan');
                hasError = true;
                if (!$firstError) $firstError = $bulan;
            }

            if (!$tahun.val()) {
                setFieldError($tahun, 'Silakan pilih tahun');
                hasError = true;
                if (!$firstError) $firstError = $tahun;
            }

            if (!$kondisi.val()) {
                setFieldError($kondisi, 'Silakan pilih kondisi stok (GS / BS)');
                hasError = true;
                if (!$firstError) $firstError = $kondisi;
            }

            if (hasError) {
                if ($firstError) {
                    if ($firstError.hasClass('select2-hidden-accessible')) {
                        $firstError.select2('open');
                    } else {
                        $firstError.focus();
                    }
                }
                return false;
            }

            // Loading state on button & table
            $btnGetSaldo.prop('disabled', true).addClass('opacity-75 cursor-not-allowed');
            $btnGetSaldo.html(`
                <svg class="animate-spin h-3.5 w-3.5 text-white shrink-0 inline-block" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <span>Memuat...</span>
            `);

            $('#loaddetailsaldo').html(`
                <tr>
                    <td colspan="6" class="py-12 text-center text-slate-400">
                        <div class="flex flex-col items-center justify-center gap-2">
                            <div class="w-7 h-7 border-2 border-brand-500 border-t-transparent rounded-full animate-spin"></div>
                            <span class="text-xs font-medium">Memuat data produk...</span>
                        </div>
                    </td>
                </tr>
            `);

            $.ajax({
                type: 'POST',
                url: '/sagudangcabang/getdetailsaldo',
                data: {
                    _token: "{{ csrf_token() }}",
                    bulan: $bulan.val(),
                    tahun: $tahun.val(),
                    kondisi: $kondisi.val(),
                    kode_cabang: $kodeCabang.length ? $kodeCabang.val() : null
                },
                cache: false,
                success: function(respond) {
                    $('#loaddetailsaldo').html(respond);
                    const countItems = $('#loaddetailsaldo tr').length;
                    $('#labelTotalItem').text(`${countItems} produk dimuat`);
                    if (typeof lucide !== 'undefined') {
                        lucide.createIcons();
                    }
                },
                error: function() {
                    $('#loaddetailsaldo').html(`
                        <tr>
                            <td colspan="6" class="py-8 text-center text-rose-500">
                                Gagal memuat data saldo awal. Silakan coba kembali.
                            </td>
                        </tr>
                    `);
                },
                complete: function() {
                    $btnGetSaldo.prop('disabled', false).removeClass('opacity-75 cursor-not-allowed');
                    $btnGetSaldo.html(`
                        <i data-lucide="download-cloud" class="w-4 h-4"></i>
                        <span>Get Saldo</span>
                    `);
                    if (typeof lucide !== 'undefined') {
                        lucide.createIcons();
                    }
                }
            });
        }

        $btnGetSaldo.on('click', function(e) {
            e.preventDefault();
            loaddetailsaldo();
        });

        // Form Submit
        $form.on('submit', function(e) {
            let hasError = false;
            let $firstError = null;

            const $kodeCabang = $form.find('#kode_cabang');
            const $bulan = $form.find('#bulan');
            const $tahun = $form.find('#tahun');
            const $kondisi = $form.find('#kondisi');

            if ($kodeCabang.length && !$kodeCabang.val()) {
                setFieldError($kodeCabang, 'Silakan pilih cabang');
                hasError = true;
                if (!$firstError) $firstError = $kodeCabang;
            }

            if (!$bulan.val()) {
                setFieldError($bulan, 'Silakan pilih bulan');
                hasError = true;
                if (!$firstError) $firstError = $bulan;
            }

            if (!$tahun.val()) {
                setFieldError($tahun, 'Silakan pilih tahun');
                hasError = true;
                if (!$firstError) $firstError = $tahun;
            }

            if (!$kondisi.val()) {
                setFieldError($kondisi, 'Silakan pilih kondisi stok');
                hasError = true;
                if (!$firstError) $firstError = $kondisi;
            }

            const totalRows = $('#loaddetailsaldo tr .kode_produk').length;
            if (totalRows === 0) {
                hasError = true;
                Swal.fire({
                    title: 'Rincian Belum Dimuat',
                    text: 'Silakan klik tombol Get Saldo terlebih dahulu untuk memuat daftar produk!',
                    icon: 'warning',
                    confirmButtonColor: '#284c9a'
                });
            }

            if (hasError) {
                e.preventDefault();
                if ($firstError) {
                    if ($firstError.hasClass('select2-hidden-accessible')) {
                        $firstError.select2('open');
                    } else {
                        $firstError.focus();
                    }
                }
                return false;
            }

            // Button disable & spinner
            $btnSubmit.prop('disabled', true).addClass('opacity-75 cursor-not-allowed pointer-events-none');
            $btnSubmit.html(`
                <svg class="animate-spin h-3.5 w-3.5 text-white shrink-0 inline-block" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <span>Menyimpan Saldo...</span>
            `);
        });
    });
</script>
@endpush
