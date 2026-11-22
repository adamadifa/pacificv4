@extends('layouts.app')
@section('titlepage', 'Buat Saldo Awal Gudang Jadi')

@section('navigasi')
    <div class="flex items-center gap-1.5 text-xs">
        <span class="text-slate-400">Home</span>
        <i data-lucide="chevron-right" class="w-3 h-3 text-slate-400"></i>
        <span class="text-slate-400">Gudang Jadi</span>
        <i data-lucide="chevron-right" class="w-3 h-3 text-slate-400"></i>
        <a href="{{ route('sagudangjadi.index') }}" class="text-slate-400 hover:text-brand-600">Saldo Awal</a>
        <i data-lucide="chevron-right" class="w-3 h-3 text-slate-400"></i>
        <span class="text-brand-600 dark:text-brand-400 font-bold">Buat Saldo Awal</span>
    </div>
@endsection

@section('content')
<div class="space-y-4 max-w-4xl">

    <!-- PAGE TITLE BAR -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2.5">
        <div>
            <h3 class="text-base font-black text-slate-900 dark:text-white leading-tight">Buat Saldo Awal Gudang Jadi</h3>
            <p class="text-[11px] text-slate-400 mt-0.5">Tetapkan saldo awal fisik persediaan barang jadi pada awal periode.</p>
        </div>
        <a href="{{ route('sagudangjadi.index') }}"
            class="inline-flex items-center justify-center gap-1.5 h-[34px] px-3.5 rounded-lg border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 hover:bg-slate-50 dark:hover:bg-slate-800/60 text-slate-700 dark:text-slate-300 text-xs font-bold transition shadow-2xs cursor-pointer shrink-0">
            <i data-lucide="arrow-left" class="w-3.5 h-3.5"></i>
            <span>Kembali</span>
        </a>
    </div>

    <!-- MAIN FORM CARD CONTAINER -->
    <div class="rounded-xl bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 p-5 shadow-2xs transition-colors duration-200">
        <form action="{{ route('sagudangjadi.store') }}" method="POST" id="formCreatesaldoawal" class="space-y-5" autocomplete="off">
            @csrf

            <!-- BARIS PERIODE & GET SALDO (ANTI-LAYOUT SHIFT: ITEMS-START + SPACER LABEL) -->
            <div class="rounded-lg border border-slate-200/80 dark:border-slate-800 bg-slate-50/60 dark:bg-slate-800/30 p-4">
                <div class="grid grid-cols-1 sm:grid-cols-12 gap-3 items-start">
                    
                    <!-- Bulan -->
                    <div class="sm:col-span-5 field-group">
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
                    <div class="sm:col-span-4 field-group">
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
                                    <option value="{{ $t }}">{{ $t }}</option>
                                @endfor
                            </select>
                            <div class="absolute inset-y-0 right-0 pr-2.5 flex items-center pointer-events-none text-slate-400">
                                <i data-lucide="chevron-down" class="w-4 h-4"></i>
                            </div>
                        </div>
                    </div>

                    <!-- Tombol Get Saldo (Anti-Layout Shift with Spacer Label) -->
                    <div class="sm:col-span-3">
                        <label class="hidden sm:block text-xs font-bold text-transparent mb-1 select-none pointer-events-none">&nbsp;</label>
                        <button type="button" id="getsaldo"
                            class="w-full h-[36px] inline-flex items-center justify-center gap-2 px-3 rounded-lg bg-emerald-600 hover:bg-emerald-700 active:bg-emerald-800 text-white text-xs font-bold transition shadow-xs cursor-pointer">
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
                            <!-- Row 1: Header Strip (colspan=3) -->
                            <tr class="bg-[#284c9a] text-white">
                                <th colspan="3" class="px-4 py-2.5 bg-[#284c9a] text-white border-0">
                                    <div class="flex items-center gap-2">
                                        <i data-lucide="boxes" class="w-4 h-4 text-blue-200"></i>
                                        <span class="text-xs font-bold uppercase tracking-wider text-white">Rincian Kuantitas Produk</span>
                                    </div>
                                </th>
                            </tr>
                            <!-- Row 2: Column Headers -->
                            <tr class="bg-[#1e3a78] text-white text-[11px] border-none">
                                <th class="py-2.5 px-3 text-center w-28 font-bold uppercase tracking-wider text-white/90 border-none">KODE</th>
                                <th class="py-2.5 px-3 font-bold uppercase tracking-wider text-white/90 border-none">NAMA PRODUK</th>
                                <th class="py-2.5 px-3 text-right font-bold uppercase tracking-wider text-white/90 border-none w-36">JUMLAH</th>
                            </tr>
                        </thead>
                        <tbody id="loaddetailsaldo" class="divide-y divide-slate-100 dark:divide-slate-800/80 bg-white dark:bg-slate-900">
                            <tr>
                                <td colspan="3" class="py-8 text-center text-slate-400">
                                    <div class="flex flex-col items-center justify-center gap-1.5">
                                        <i data-lucide="inbox" class="w-7 h-7 text-slate-300 dark:text-slate-600"></i>
                                        <span class="text-xs font-semibold">Silakan pilih bulan & tahun lalu klik "Get Saldo"</span>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- SUBMIT BUTTON -->
            <div class="pt-2 border-t border-slate-100 dark:border-slate-800 flex justify-end">
                <button type="submit" id="btnSubmit"
                    class="h-[36px] inline-flex items-center justify-center gap-2 px-6 rounded-lg bg-[#284c9a] hover:bg-blue-800 active:bg-blue-900 text-white text-xs font-bold transition shadow-xs cursor-pointer">
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
        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }

        const $form = $("#formCreatesaldoawal");
        const $bulan = $form.find("#bulan");
        const $tahun = $form.find("#tahun");
        const $getsaldoBtn = $("#getsaldo");
        const $submitBtn = $("#btnSubmit");

        // Rule 5 Compliant Interactive Validation
        function setFieldError($field, message) {
            clearFieldError($field);
            const $group = $field.closest('.field-group');
            $group.addClass('has-error');
            $field.addClass('border-rose-500 ring-1 ring-rose-500 focus:border-rose-500 focus:ring-rose-500/20 is-invalid');
            $group.find('.input-prefix-icon').addClass('text-rose-500').removeClass('text-slate-400');
            $group.append(`
                <p class="field-error-msg text-[11px] font-semibold text-rose-600 dark:text-rose-400 mt-1 pl-1 flex items-center gap-1">
                    <svg class="w-3.5 h-3.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <circle cx="12" cy="12" r="10" stroke-width="2"/>
                        <line x1="12" y1="8" x2="12" y2="12" stroke-width="2"/>
                        <line x1="12" y1="16" x2="12.01" y2="16" stroke-width="2"/>
                    </svg>
                    <span>${message}</span>
                </p>
            `);
        }

        function clearFieldError($field) {
            const $group = $field.closest('.field-group');
            $group.removeClass('has-error');
            $field.removeClass('border-rose-500 ring-1 ring-rose-500 focus:border-rose-500 focus:ring-rose-500/20 is-invalid');
            $group.find('.input-prefix-icon').removeClass('text-rose-500').addClass('text-slate-400');
            $group.find('.field-error-msg').remove();
        }

        $bulan.on('change', function() { clearFieldError($(this)); });
        $tahun.on('change', function() { clearFieldError($(this)); });

        function validatePeriode() {
            let hasError = false;
            if (!$bulan.val()) {
                setFieldError($bulan, 'Silakan pilih bulan terlebih dahulu');
                hasError = true;
            }
            if (!$tahun.val()) {
                setFieldError($tahun, 'Silakan pilih tahun terlebih dahulu');
                hasError = true;
            }
            return !hasError;
        }

        // Get Saldo Handler
        $getsaldoBtn.on("click", function(e) {
            e.preventDefault();
            if (!validatePeriode()) {
                return false;
            }

            const bulan = $bulan.val();
            const tahun = $tahun.val();
            const origText = $getsaldoBtn.html();

            $getsaldoBtn.prop('disabled', true).addClass('opacity-75 cursor-not-allowed pointer-events-none');
            $getsaldoBtn.html(`
                <svg class="animate-spin h-3.5 w-3.5 text-white shrink-0 inline-block mr-1.5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <span>Mengambil...</span>
            `);

            $("#loaddetailsaldo").html(`
                <tr>
                    <td colspan="3" class="py-8 text-center text-slate-400">
                        <div class="flex items-center justify-center gap-2">
                            <svg class="animate-spin h-5 w-5 text-[#284c9a]" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            <span class="text-xs font-semibold">Mengambil data saldo awal...</span>
                        </div>
                    </td>
                </tr>
            `);

            $.ajax({
                type: 'POST',
                url: '{{ route("sagudangjadi.getdetailsaldo") }}',
                data: {
                    _token: "{{ csrf_token() }}",
                    bulan: bulan,
                    tahun: tahun
                },
                cache: false,
                success: function(respond) {
                    $getsaldoBtn.prop('disabled', false).removeClass('opacity-75 cursor-not-allowed pointer-events-none');
                    $getsaldoBtn.html(origText);
                    $("#loaddetailsaldo").html(respond);
                    if (typeof lucide !== 'undefined') {
                        lucide.createIcons();
                    }
                },
                error: function() {
                    $getsaldoBtn.prop('disabled', false).removeClass('opacity-75 cursor-not-allowed pointer-events-none');
                    $getsaldoBtn.html(origText);
                    $("#loaddetailsaldo").html(`
                        <tr>
                            <td colspan="3" class="py-6 text-center text-rose-500 font-semibold text-xs">
                                Gagal mengambil data saldo awal. Silakan coba lagi.
                            </td>
                        </tr>
                    `);
                    if (typeof lucide !== 'undefined') {
                        lucide.createIcons();
                    }
                }
            });
        });

        // Form Submit Handler
        $form.on("submit", function(e) {
            if (!validatePeriode()) {
                e.preventDefault();
                return false;
            }

            if ($("#loaddetailsaldo tr").length === 0 || $("#loaddetailsaldo input[name='kode_produk[]']").length === 0) {
                e.preventDefault();
                Swal.fire({
                    title: "Perhatian",
                    text: "Silakan klik 'Get Saldo' terlebih dahulu untuk memuat daftar produk!",
                    icon: "warning",
                    confirmButtonText: "OK",
                    confirmButtonColor: "#284c9a"
                });
                return false;
            }

            // Anti double submit
            $submitBtn.prop('disabled', true).addClass('opacity-75 cursor-not-allowed pointer-events-none');
            $submitBtn.html(`
                <svg class="animate-spin h-3.5 w-3.5 text-white shrink-0 inline-block mr-1.5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <span>Menyimpan...</span>
            `);
        });
    });
</script>
@endpush
