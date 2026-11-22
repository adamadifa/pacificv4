@extends('layouts.app')
@section('titlepage', 'Buat Saldo Awal Barang Produksi')

@section('navigasi')
    <div class="flex items-center gap-1.5 text-xs">
        <span class="text-slate-400">Home</span>
        <i data-lucide="chevron-right" class="w-3 h-3 text-slate-400"></i>
        <span class="text-slate-400">Produksi</span>
        <i data-lucide="chevron-right" class="w-3 h-3 text-slate-400"></i>
        <a href="{{ route('sabarangproduksi.index') }}" class="text-slate-400 hover:text-brand-600 transition">Saldo Awal Barang</a>
        <i data-lucide="chevron-right" class="w-3 h-3 text-slate-400"></i>
        <span class="text-brand-600 dark:text-brand-400 font-bold">Buat Saldo Awal</span>
    </div>
@endsection

@section('content')
<div class="space-y-3.5 max-w-4xl">

    <!-- PAGE TITLE BAR -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2.5">
        <div>
            <h3 class="text-base font-black text-slate-900 dark:text-white leading-tight">Buat Saldo Awal Barang Produksi</h3>
            <p class="text-[11px] text-slate-400 mt-0.5">Tentukan periode bulan & tahun kemudian tarik atau isi kuantitas saldo awal bahan/barang produksi.</p>
        </div>

        <a href="{{ route('sabarangproduksi.index') }}"
            class="inline-flex items-center justify-center gap-1.5 h-[34px] px-3.5 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 hover:bg-slate-50 dark:hover:bg-slate-700/60 text-slate-700 dark:text-slate-200 text-xs font-bold transition cursor-pointer shrink-0">
            <i data-lucide="arrow-left" class="w-3.5 h-3.5"></i>
            <span>Kembali</span>
        </a>
    </div>

    <!-- FORM CARD -->
    <div class="rounded-xl bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 p-4 shadow-2xs transition-colors duration-200">
        <form action="{{ route('sabarangproduksi.store') }}" method="POST" id="formCreatesaldoawal" class="space-y-4">
            @csrf

            <!-- PERIODE SELECTION & GET SALDO BUTTON -->
            <div class="p-3.5 rounded-lg border border-slate-200/80 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-900/40 space-y-3">
                <div class="flex items-center gap-2 text-xs font-bold text-slate-700 dark:text-slate-200">
                    <i data-lucide="calendar" class="w-4 h-4 text-brand-600 dark:text-brand-400"></i>
                    <span>Tentukan Periode Saldo Awal</span>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-12 gap-2.5 items-start">
                    <!-- Bulan -->
                    <div class="sm:col-span-5 field-group">
                        <label for="bulan" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                            Bulan <span class="text-rose-500">*</span>
                        </label>
                        <div class="relative">
                            <div class="input-prefix-icon absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400 z-10">
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
                            <div class="input-prefix-icon absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400 z-10">
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

                    <!-- Tombol Get Saldo -->
                    <div class="sm:col-span-3">
                        <label class="block text-xs font-bold invisible mb-1 select-none pointer-events-none">Aksi</label>
                        <button type="button" id="getsaldo"
                            class="w-full h-[36px] inline-flex items-center justify-center gap-1.5 px-4 rounded-lg bg-emerald-600 hover:bg-emerald-700 active:bg-emerald-800 text-white text-xs font-bold transition shadow-xs cursor-pointer">
                            <i data-lucide="database" class="w-3.5 h-3.5"></i>
                            <span>Tarik Saldo</span>
                        </button>
                    </div>
                </div>
            </div>

            <!-- TABEL SALDO AWAL BARANG -->
            <div class="rounded-xl border border-slate-200/80 dark:border-slate-800 overflow-hidden shadow-2xs">
                <div class="overflow-x-auto scrollbar-thin">
                    <table class="w-full text-xs text-left border-collapse whitespace-nowrap">
                        <thead>
                            <tr class="bg-[#284c9a] text-white">
                                <th colspan="4" class="px-4 py-3 bg-[#284c9a] text-white border-0">
                                    <div class="flex items-center gap-2">
                                        <i data-lucide="list" class="w-4 h-4 text-blue-200"></i>
                                        <span class="text-xs font-bold uppercase tracking-wider text-white">Rincian Saldo Awal Barang Produksi</span>
                                    </div>
                                </th>
                            </tr>
                            <tr class="bg-[#1e3a78] text-white text-[11px] border-none">
                                <th class="py-2.5 px-4 font-bold uppercase tracking-wider text-white/90 border-none w-36">Kode Barang</th>
                                <th class="py-2.5 px-4 font-bold uppercase tracking-wider text-white/90 border-none">Nama Barang</th>
                                <th class="py-2.5 px-4 text-center font-bold uppercase tracking-wider text-white/90 border-none w-24">Satuan</th>
                                <th class="py-2.5 px-4 text-right font-bold uppercase tracking-wider text-white/90 border-none w-44">Jumlah Saldo</th>
                            </tr>
                        </thead>
                        <tbody id="loaddetailsaldo" class="divide-y divide-slate-100 dark:divide-slate-800/80 bg-white dark:bg-slate-900">
                            <tr>
                                <td colspan="4" class="py-8 text-center text-slate-400">
                                    <div class="flex flex-col items-center justify-center gap-1.5">
                                        <i data-lucide="database" class="w-6 h-6 text-slate-300 dark:text-slate-600"></i>
                                        <span class="text-xs font-semibold">Tentukan periode dan klik "Tarik Saldo" untuk menampilkan barang</span>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- SUBMIT BUTTON -->
            <div class="pt-2 flex items-center justify-end gap-2 border-t border-slate-100 dark:border-slate-800">
                <a href="{{ route('sabarangproduksi.index') }}"
                    class="inline-flex items-center justify-center h-[36px] px-4 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 hover:bg-slate-50 dark:hover:bg-slate-700/60 text-slate-700 dark:text-slate-200 text-xs font-bold transition cursor-pointer">
                    Batal
                </a>
                <button type="submit" id="btnSubmitSA"
                    class="inline-flex items-center justify-center gap-1.5 h-[36px] px-5 rounded-lg bg-brand-600 hover:bg-brand-700 active:bg-brand-800 text-white text-xs font-bold transition shadow-xs cursor-pointer">
                    <i data-lucide="save" class="w-3.5 h-3.5"></i>
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
        const $form = $("#formCreatesaldoawal");
        const $btnSubmit = $form.find("#btnSubmitSA");

        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }

        // Standard Error State Helpers
        function setFieldError($input, message) {
            const $container = $input.closest('.field-group');
            $input.addClass('border-rose-500 ring-1 ring-rose-500 focus:border-rose-500 focus:ring-rose-500/20')
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
            $input.removeClass('border-rose-500 ring-1 ring-rose-500 focus:border-rose-500 focus:ring-rose-500/20')
                  .addClass('border-slate-200 dark:border-slate-700');
            $container.find('.input-prefix-icon').removeClass('text-rose-500').addClass('text-slate-400');
            $container.find('.field-error-msg').remove();
        }

        // Live Cleanup on change
        $form.find('select, input').on('input change', function() {
            clearFieldError($(this));
        });

        function loaddetailsaldo() {
            const $bulan = $form.find("#bulan");
            const $tahun = $form.find("#tahun");
            const bulan = $bulan.val();
            const tahun = $tahun.val();

            let hasError = false;
            let $firstErrorField = null;

            if (!bulan) {
                setFieldError($bulan, "Pilih bulan terlebih dahulu");
                hasError = true;
                $firstErrorField = $bulan;
            }
            if (!tahun) {
                setFieldError($tahun, "Pilih tahun terlebih dahulu");
                if (!hasError) {
                    hasError = true;
                    $firstErrorField = $tahun;
                }
            }

            if (hasError) {
                $firstErrorField.focus();
                return;
            }

            $("#loaddetailsaldo").html(`
                <tr>
                    <td colspan="4" class="py-8 text-center text-slate-400">
                        <div class="flex items-center justify-center gap-2">
                            <svg class="animate-spin h-5 w-5 text-brand-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            <span class="text-xs font-semibold">Menarik data saldo awal barang...</span>
                        </div>
                    </td>
                </tr>
            `);

            $.ajax({
                type: 'POST',
                url: "{{ route('sabarangproduksi.getdetailsaldo') }}",
                data: {
                    _token: "{{ csrf_token() }}",
                    bulan: bulan,
                    tahun: tahun
                },
                cache: false,
                success: function(respond) {
                    $("#loaddetailsaldo").html(respond);
                    $("#loaddetailsaldo input.noborder-form").addClass("w-32 h-7 px-2 text-xs font-bold text-right font-mono-num rounded border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:border-brand-500");
                },
                error: function(xhr) {
                    Swal.fire("Error", xhr.responseJSON?.message || "Gagal memuat saldo awal", "error");
                }
            });
        }

        $("#getsaldo").on("click", function(e) {
            e.preventDefault();
            loaddetailsaldo();
        });

        $form.on("submit", function(e) {
            let hasError = false;
            let $firstErrorField = null;

            function validateField($field, condition, message) {
                if (condition) {
                    setFieldError($field, message);
                    if (!hasError) {
                        hasError = true;
                        $firstErrorField = $field;
                    }
                } else {
                    clearFieldError($field);
                }
            }

            const $bulan = $form.find("#bulan");
            const $tahun = $form.find("#tahun");

            validateField($bulan, !$bulan.val(), "Bulan wajib dipilih");
            validateField($tahun, !$tahun.val(), "Tahun wajib dipilih");

            if (hasError) {
                e.preventDefault();
                $firstErrorField.focus();
                return false;
            }

            if ($("#loaddetailsaldo input[name='kode_barang_produksi[]']").length === 0) {
                e.preventDefault();
                Swal.fire("Oops!", "Silakan klik tombol Tarik Saldo terlebih dahulu untuk memuat rincian barang!", "warning");
                return false;
            }

            // Prevent Double Submit
            $btnSubmit.prop('disabled', true).addClass('opacity-75 cursor-not-allowed pointer-events-none');
            $btnSubmit.html(`
                <svg class="animate-spin h-3.5 w-3.5 text-white shrink-0 inline-block" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <span>Menyimpan Saldo Awal...</span>
            `);
            return true;
        });
    });
</script>
@endpush
