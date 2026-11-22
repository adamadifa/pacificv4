@extends('layouts.app')
@section('titlepage', 'Buat Saldo Awal Harga Gudang Bahan')

@section('navigasi')
    <div class="flex items-center gap-1.5 text-xs">
        <span class="text-slate-400">Home</span>
        <i data-lucide="chevron-right" class="w-3 h-3 text-slate-400"></i>
        <span class="text-slate-400">Gudang Bahan</span>
        <i data-lucide="chevron-right" class="w-3 h-3 text-slate-400"></i>
        <a href="{{ route('sahargagb.index') }}" class="text-slate-400 hover:text-brand-600 transition">Saldo Awal Harga</a>
        <i data-lucide="chevron-right" class="w-3 h-3 text-slate-400"></i>
        <span class="text-brand-600 dark:text-brand-400 font-bold">Buat Saldo Awal Harga</span>
    </div>
@endsection

@section('content')
<div class="space-y-3.5 max-w-5xl">

    <!-- PAGE TITLE BAR -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2.5">
        <div>
            <h3 class="text-base font-black text-slate-900 dark:text-white leading-tight">Buat Saldo Awal Harga Gudang Bahan</h3>
            <p class="text-[11px] text-slate-400 mt-0.5">Generate dan sesuaikan saldo awal nilai harga bahan per periode bulan dan tahun.</p>
        </div>

        <a href="{{ route('sahargagb.index') }}"
            class="inline-flex items-center justify-center gap-1.5 h-[34px] px-3.5 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 hover:bg-slate-50 dark:hover:bg-slate-700/60 text-slate-700 dark:text-slate-200 text-xs font-bold transition cursor-pointer shrink-0">
            <i data-lucide="arrow-left" class="w-3.5 h-3.5"></i>
            <span>Kembali</span>
        </a>
    </div>

    <form action="{{ route('sahargagb.store') }}" method="POST" id="formCreatesaldoawalharga" class="space-y-3.5">
        @csrf

        <!-- PARAMETER CARD -->
        <div class="rounded-xl bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 p-4 shadow-2xs space-y-3">
            <div class="flex items-center gap-2 pb-2.5 border-b border-slate-100 dark:border-slate-800 text-xs font-bold text-slate-800 dark:text-slate-100">
                <i data-lucide="calendar" class="w-4 h-4 text-brand-600 dark:text-brand-400"></i>
                <span>Pilih Periode Saldo</span>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-12 gap-3 items-start">
                <!-- Bulan -->
                <div class="sm:col-span-5 field-group">
                    <label for="bulan" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                        Bulan <span class="text-rose-500">*</span>
                    </label>
                    <div class="relative">
                        <div class="input-prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
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
                <div class="sm:col-span-4 field-group">
                    <label for="tahun" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                        Tahun <span class="text-rose-500">*</span>
                    </label>
                    <div class="relative">
                        <div class="input-prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                            <i data-lucide="calendar-days" class="w-4 h-4"></i>
                        </div>
                        <select name="tahun" id="tahun"
                            class="w-full h-[36px] pl-9 pr-7 text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition appearance-none cursor-pointer">
                            <option value="">Pilih Tahun</option>
                            @for ($t = $start_year; $t <= date('Y'); $t++)
                                <option value="{{ $t }}">{{ $t }}</option>
                            @endfor
                        </select>
                        <div class="absolute inset-y-0 right-0 pr-2.5 flex items-center pointer-events-none text-slate-400">
                            <i data-lucide="chevron-down" class="w-4 h-4"></i>
                        </div>
                    </div>
                </div>

                <!-- Tombol Get Saldo -->
                <div class="sm:col-span-3">
                    <label class="hidden sm:block text-xs font-bold text-transparent mb-1 select-none pointer-events-none">&nbsp;</label>
                    <button type="button" id="getsaldo"
                        class="w-full h-[36px] inline-flex items-center justify-center gap-1.5 px-4 rounded-lg bg-emerald-600 hover:bg-emerald-700 active:bg-emerald-800 text-white text-xs font-bold transition shadow-xs cursor-pointer">
                        <i data-lucide="download-cloud" class="w-4 h-4"></i>
                        <span>Get Saldo</span>
                    </button>
                </div>
            </div>
        </div>

        <!-- DETAIL ITEMS TABLE (STANDAR DUA BARIS THEAD V2) -->
        <div class="rounded-xl bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 shadow-2xs overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-xs text-left border-collapse whitespace-nowrap" id="tabledetail">
                    <thead>
                        <!-- Row 1: Title Strip (colspan=4) -->
                        <tr class="bg-[#284c9a] text-white">
                            <th colspan="4" class="px-4 py-2.5 bg-[#284c9a] text-white border-0">
                                <div class="flex items-center gap-2">
                                    <i data-lucide="list" class="w-4 h-4 text-blue-200"></i>
                                    <span class="text-xs font-bold uppercase tracking-wider text-white">Rincian Saldo Awal Harga Barang</span>
                                </div>
                            </th>
                        </tr>
                        <!-- Row 2: Column Titles -->
                        <tr class="bg-[#1e3a78] text-white text-[11px] border-none">
                            <th class="py-2.5 px-3 text-center w-16 font-bold uppercase tracking-wider text-white/90 border-none">KODE</th>
                            <th class="py-2.5 px-3 font-bold uppercase tracking-wider text-white/90 border-none">NAMA BARANG</th>
                            <th class="py-2.5 px-3 font-bold uppercase tracking-wider text-white/90 border-none w-28">SATUAN</th>
                            <th class="py-2.5 px-3 text-right font-bold uppercase tracking-wider text-white/90 border-none w-44">HARGA</th>
                        </tr>
                    </thead>
                    <tbody id="loaddetailsaldo" class="divide-y divide-slate-100 dark:divide-slate-800/80 bg-white dark:bg-slate-900">
                        <tr id="emptyRow">
                            <td colspan="4" class="py-10 text-center text-slate-400">
                                <div class="flex flex-col items-center justify-center gap-1.5">
                                    <i data-lucide="package-search" class="w-7 h-7 text-slate-300 dark:text-slate-600"></i>
                                    <span class="text-xs font-semibold">Pilih bulan & tahun lalu klik tombol "Get Saldo"</span>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- FOOTER ACTION -->
            <div class="p-3.5 border-t border-slate-100 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-900/50 flex justify-end">
                <button type="submit" id="btnSimpan"
                    class="inline-flex items-center justify-center gap-1.5 h-[36px] px-6 rounded-lg bg-brand-600 hover:bg-brand-700 active:bg-brand-800 text-white text-xs font-bold transition shadow-xs cursor-pointer disabled:opacity-50">
                    <i data-lucide="send" class="w-4 h-4"></i>
                    <span>Simpan Saldo Awal Harga</span>
                </button>
            </div>
        </div>
    </form>
</div>
@endsection

@push('myscript')
<script>
    $(function() {
        const form = $("#formCreatesaldoawalharga");

        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }

        // Error State Visual Helpers
        function markFieldError($input, message) {
            const $container = $input.closest('.field-group');
            $container.addClass('has-error');
            $input.addClass('border-rose-500 ring-1 ring-rose-500 focus:border-rose-500 focus:ring-rose-500/20 is-invalid')
                  .removeClass('border-slate-200 dark:border-slate-700');
            $container.find('.input-prefix-icon').addClass('text-rose-500').removeClass('text-slate-400');

            $container.find('.field-error-msg').remove();
            $container.append(`
                <p class="field-error-msg text-[11px] font-semibold text-rose-600 dark:text-rose-400 mt-1 pl-1 flex items-center gap-1">
                    <svg class="w-3 h-3 text-rose-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                        <circle cx="12" cy="12" r="10"></circle>
                        <line x1="12" y1="8" x2="12" y2="12"></line>
                        <line x1="12" y1="16" x2="12.01" y2="16"></line>
                    </svg>
                    <span>${message}</span>
                </p>
            `);
        }

        function clearFieldError($input) {
            const $container = $input.closest('.field-group');
            $container.removeClass('has-error');
            $input.removeClass('border-rose-500 ring-1 ring-rose-500 focus:border-rose-500 focus:ring-rose-500/20 is-invalid')
                  .addClass('border-slate-200 dark:border-slate-700');
            $container.find('.input-prefix-icon').removeClass('text-rose-500').addClass('text-slate-400');
            $container.find('.field-error-msg').remove();
        }

        // Live error cleanup on change/input
        form.find('select, input').on('change input', function() {
            clearFieldError($(this));
        });

        function loaddetailsaldo() {
            const $bulan = form.find("#bulan");
            const $tahun = form.find("#tahun");
            const bulan = $bulan.val();
            const tahun = $tahun.val();

            let hasError = false;
            let $firstError = null;

            if (!bulan) {
                markFieldError($bulan, "Silakan pilih bulan terlebih dahulu");
                hasError = true;
                if (!$firstError) $firstError = $bulan;
            }

            if (!tahun) {
                markFieldError($tahun, "Silakan pilih tahun terlebih dahulu");
                hasError = true;
                if (!$firstError) $firstError = $tahun;
            }

            if (hasError) {
                if ($firstError) $firstError.focus();
                return false;
            }

            form.find("#loaddetailsaldo").html(`
                <tr>
                    <td colspan="4" class="py-10 text-center text-slate-400">
                        <div class="flex flex-col items-center justify-center gap-2">
                            <svg class="animate-spin h-6 w-6 text-brand-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            <span class="text-xs font-semibold">Mengambil data saldo awal harga...</span>
                        </div>
                    </td>
                </tr>
            `);

            $.ajax({
                type: "POST",
                url: "{{ route('sahargagb.getdetailsaldo') }}",
                data: {
                    _token: "{{ csrf_token() }}",
                    bulan: bulan,
                    tahun: tahun
                },
                cache: false,
                success: function(respond) {
                    if (respond === '1') {
                        Swal.fire({
                            title: "Peringatan",
                            text: "Saldo Bulan Sebelumnya Belum di input!",
                            icon: "warning",
                            confirmButtonText: "OK"
                        });
                        form.find("#loaddetailsaldo").html(`
                            <tr id="emptyRow">
                                <td colspan="4" class="py-8 text-center text-rose-500 font-semibold">
                                    Saldo Bulan Sebelumnya Belum di input!
                                </td>
                            </tr>
                        `);
                    } else {
                        form.find("#loaddetailsaldo").html(respond);
                        if (typeof easyNumberSeparator !== 'undefined') {
                            easyNumberSeparator({
                                selector: '.money, .number-separator',
                                separator: '.',
                                decimalSeparator: ',',
                            });
                        }
                    }
                },
                error: function() {
                    Swal.fire({
                        title: "Error",
                        text: "Gagal memuat detail saldo awal harga!",
                        icon: "error",
                        confirmButtonText: "OK"
                    });
                }
            });
        }

        form.find("#getsaldo").on("click", function(e) {
            e.preventDefault();
            loaddetailsaldo();
        });

        form.on("submit", function(e) {
            const $bulan = form.find("#bulan");
            const $tahun = form.find("#tahun");
            const bulan = $bulan.val();
            const tahun = $tahun.val();

            let hasError = false;
            let $firstError = null;

            if (!bulan) {
                markFieldError($bulan, "Pilih bulan terlebih dahulu");
                hasError = true;
                if (!$firstError) $firstError = $bulan;
            }

            if (!tahun) {
                markFieldError($tahun, "Pilih tahun terlebih dahulu");
                hasError = true;
                if (!$firstError) $firstError = $tahun;
            }

            if (hasError) {
                e.preventDefault();
                if ($firstError) $firstError.focus();
                return false;
            }

            if (form.find('#loaddetailsaldo tr').length === 0 || form.find('#emptyRow').length > 0) {
                e.preventDefault();
                Swal.fire({
                    title: "Peringatan",
                    text: "Silakan Get Saldo Terlebih Dahulu!",
                    icon: "warning",
                    confirmButtonText: "OK"
                });
                return false;
            }

            const $btn = form.find("#btnSimpan");
            $btn.prop("disabled", true).addClass("opacity-75 cursor-not-allowed pointer-events-none");
            $btn.html(`
                <svg class="animate-spin h-4 w-4 text-white shrink-0 inline-block" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <span>Menyimpan...</span>
            `);
        });
    });
</script>
@endpush