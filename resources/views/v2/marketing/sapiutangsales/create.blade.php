@extends('v2.layouts.app')
@section('titlepage', 'Buat Saldo Awal Piutang Sales')

@section('content')
@section('navigasi')
    <div class="flex items-center gap-1.5 text-xs">
        <span class="text-slate-400">Home</span>
        <i data-lucide="chevron-right" class="w-3 h-3 text-slate-400"></i>
        <span class="text-slate-400">Marketing</span>
        <i data-lucide="chevron-right" class="w-3 h-3 text-slate-400"></i>
        <a href="{{ route('sapiutangsales.index') }}" class="text-slate-400 hover:text-brand-600 transition">S.A Piutang Sales</a>
        <i data-lucide="chevron-right" class="w-3 h-3 text-slate-400"></i>
        <span class="text-[#284c9a] dark:text-blue-400 font-bold">Buat Saldo Awal</span>
    </div>
@endsection

<form action="{{ route('sapiutangsales.store') }}" method="POST" id="formCreatesaldoawal" class="space-y-4" autocomplete="off">
    @csrf
    <input type="hidden" name="data_saldo" id="data_saldo">

    <!-- PAGE TITLE BAR -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2.5">
        <div>
            <h3 class="text-base font-black text-slate-900 dark:text-white leading-tight">Buat Saldo Awal Piutang Sales</h3>
            <p class="text-[11px] text-slate-400 mt-0.5">Penetapan saldo awal piutang salesman per cabang per periode.</p>
        </div>

        <a href="{{ route('sapiutangsales.index') }}"
            class="inline-flex items-center justify-center gap-1.5 h-[34px] px-3.5 rounded-lg border border-slate-200 dark:border-slate-700 hover:bg-slate-100 dark:hover:bg-slate-800 text-slate-600 dark:text-slate-300 text-xs font-bold transition shadow-2xs cursor-pointer shrink-0">
            <i data-lucide="arrow-left" class="w-3.5 h-3.5"></i>
            <span>Kembali</span>
        </a>
    </div>

    <div class="max-w-5xl space-y-4">
        
        <!-- CARD 1: PILIH PERIODE & CABANG (NO-LABEL, KOMPAK ~36px) -->
        <div class="rounded-xl border border-slate-200/80 dark:border-slate-800 bg-white dark:bg-slate-900 p-3.5 sm:p-4 shadow-2xs space-y-3">
            <div class="flex items-center gap-1.5 pb-2 border-b border-slate-100 dark:border-slate-800 text-xs font-bold text-slate-700 dark:text-slate-200">
                <i data-lucide="calendar" class="w-3.5 h-3.5 text-[#284c9a] dark:text-blue-400"></i>
                <span>Pilih Periode & Cabang</span>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-12 gap-2.5 items-start">
                
                <!-- Bulan (No-label) -->
                <div class="sm:col-span-3 field-group">
                    <div class="relative">
                        <div class="input-prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                            <i data-lucide="calendar" class="w-3.5 h-3.5"></i>
                        </div>
                        <select name="bulan" id="bulan"
                            class="w-full h-[36px] pl-8 pr-3 text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition cursor-pointer">
                            <option value="">Pilih Bulan</option>
                            @foreach ($list_bulan as $d)
                                <option value="{{ $d['kode_bulan'] }}">{{ $d['nama_bulan'] }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <!-- Tahun (No-label) -->
                <div class="sm:col-span-3 field-group">
                    <div class="relative">
                        <div class="input-prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                            <i data-lucide="calendar-days" class="w-3.5 h-3.5"></i>
                        </div>
                        <select name="tahun" id="tahun"
                            class="w-full h-[36px] pl-8 pr-3 text-xs font-mono-num font-semibold rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition cursor-pointer">
                            <option value="">Pilih Tahun</option>
                            @for ($t = $start_year; $t <= date('Y'); $t++)
                                <option value="{{ $t }}">{{ $t }}</option>
                            @endfor
                        </select>
                    </div>
                </div>

                <!-- Cabang (No-label) -->
                <div class="sm:col-span-3 field-group">
                    <div class="relative">
                        <div class="input-prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                            <i data-lucide="building-2" class="w-3.5 h-3.5"></i>
                        </div>
                        <select name="kode_cabang" id="kode_cabang"
                            class="w-full h-[36px] pl-8 pr-3 text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition cursor-pointer">
                            <option value="">Pilih Cabang</option>
                            @foreach ($cabang as $d)
                                <option value="{{ $d->kode_cabang }}">{{ $d->nama_cabang }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <!-- Button Get Saldo -->
                <div class="sm:col-span-3">
                    <button type="button" id="getsaldo"
                        class="w-full h-[36px] px-3 bg-emerald-600 hover:bg-emerald-700 active:bg-emerald-800 text-white font-bold rounded-lg text-xs flex items-center justify-center gap-1.5 transition shadow-2xs cursor-pointer">
                        <i data-lucide="calculator" class="w-3.5 h-3.5"></i>
                        <span>Get Saldo</span>
                    </button>
                </div>
            </div>
        </div>

        <!-- CARD 2: TABEL RINCIAN SALDO AKHIR PIUTANG SALES (KOMPAK ~36px) -->
        <div class="rounded-xl border border-slate-200/80 dark:border-slate-800 bg-white dark:bg-slate-900 overflow-hidden shadow-2xs">
            <div class="overflow-x-auto max-h-[50vh] overflow-y-auto">
                <table class="w-full text-xs text-left border-separate border-spacing-0 whitespace-nowrap">
                    <thead class="sticky top-0 z-10">
                        <!-- Baris 1: Strip Header Pacific Navy -->
                        <tr class="bg-[#284c9a] text-white">
                            <th colspan="3" class="px-3.5 py-2 bg-[#284c9a] text-white border-0">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center gap-1.5">
                                        <i data-lucide="layers" class="w-3.5 h-3.5 text-blue-200"></i>
                                        <span class="text-xs font-bold text-white">Rincian Saldo Akhir Piutang Sales</span>
                                    </div>
                                    <span class="text-[10.5px] text-white/70 font-mono-num hidden" id="badgeCounter">
                                        0 Salesman
                                    </span>
                                </div>
                            </th>
                        </tr>

                        <!-- Baris 2: Header Kolom -->
                        <tr class="bg-[#1e3a78] text-white text-[11px]">
                            <th class="py-2 px-3 font-bold uppercase tracking-wider text-white/90 border-b border-blue-900 w-36">KODE SALES</th>
                            <th class="py-2 px-3 font-bold uppercase tracking-wider text-white/90 border-b border-blue-900 min-w-[200px]">NAMA SALESMAN</th>
                            <th class="py-2 px-3 text-right font-bold uppercase tracking-wider text-white/90 border-b border-blue-900 w-44">SALDO</th>
                        </tr>
                    </thead>
                    <tbody id="loaddetailsaldo" class="divide-y divide-slate-100 dark:divide-slate-800/80 bg-white dark:bg-slate-900">
                        <tr>
                            <td colspan="3" class="py-12 px-4 text-center text-slate-400">
                                <div class="flex flex-col items-center justify-center gap-2">
                                    <div class="w-10 h-10 rounded-full bg-slate-100 dark:bg-slate-800 text-slate-400 flex items-center justify-center">
                                        <i data-lucide="database" class="w-5 h-5"></i>
                                    </div>
                                    <span class="text-xs font-semibold text-slate-500 dark:text-slate-400">Pilih periode, cabang, dan klik tombol "Get Saldo" untuk menampilkan data rincian piutang</span>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- TOTAL DISPLAY BANNER & SUBMIT -->
            <div class="p-4 bg-slate-50/80 dark:bg-slate-800/40 border-t border-slate-200/80 dark:border-slate-800 space-y-3">
                <!-- Total Banner Pacific Navy -->
                <div class="rounded-xl bg-[#284c9a] text-white p-4 shadow-sm flex items-center justify-between">
                    <div class="flex items-center gap-2.5">
                        <div class="w-10 h-10 rounded-lg bg-white/10 flex items-center justify-center text-white">
                            <i data-lucide="calculator" class="w-5 h-5"></i>
                        </div>
                        <div>
                            <span class="block text-[11px] font-bold text-white/70 uppercase tracking-wider">Total Piutang</span>
                            <span class="text-xs text-white/50">Akumulasi saldo akhir piutang salesman</span>
                        </div>
                    </div>
                    <div class="text-right">
                        <span class="text-xs text-white/60 font-semibold block">Total Nominal</span>
                        <span class="text-xl font-black font-mono-num tracking-tight">Rp. <span id="total_piutang">0</span></span>
                    </div>
                </div>

                <!-- Submit Button -->
                <button type="submit" id="btnSimpan"
                    class="w-full h-[38px] rounded-lg bg-[#284c9a] hover:bg-[#1e3a78] active:bg-[#152a56] text-white text-xs font-bold transition flex items-center justify-center gap-1.5 shadow-2xs cursor-pointer">
                    <i data-lucide="save" class="w-4 h-4"></i>
                    <span>Simpan Saldo Awal</span>
                </button>
            </div>
        </div>
    </div>
</form>

@endsection

@push('myscript')
<script>
    $(function() {
        const $form = $("#formCreatesaldoawal");
        const $btnSimpan = $("#btnSimpan");
        const $btnGetSaldo = $("#getsaldo");

        // Helper Error State Interaktif (Standar V2 GEMINI.md - Anti Swal Alert)
        function showFieldError($field, message) {
            clearFieldError($field);
            $field.addClass('!border-rose-500 !ring-1 !ring-rose-500');
            $field.closest('.field-group').find('.input-prefix-icon').addClass('text-rose-500').removeClass('text-slate-400');
            
            const errorHtml = `
                <p class="field-error-msg text-[11px] font-semibold text-rose-600 dark:text-rose-400 mt-1 pl-1 flex items-center gap-1">
                    <svg class="w-3.5 h-3.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span>${message}</span>
                </p>
            `;
            $field.closest('.field-group').append(errorHtml);
        }

        function clearFieldError($field) {
            $field.removeClass('!border-rose-500 !ring-1 !ring-rose-500');
            $field.closest('.field-group').find('.input-prefix-icon').removeClass('text-rose-500').addClass('text-slate-400');
            $field.closest('.field-group').find('.field-error-msg').remove();
        }

        // Live cleanup saat user memilih option
        $("#bulan, #tahun, #kode_cabang").on('change', function() {
            clearFieldError($(this));
        });

        // Hitung total piutang
        function calculateTotal() {
            let total = 0;
            let count = 0;
            $('.saldo_akhir').each(function() {
                let val = parseFloat($(this).val()) || 0;
                total += val;
                count++;
            });
            $('#total_piutang').text(new Intl.NumberFormat('id-ID').format(total));
            if (count > 0) {
                $('#badgeCounter').text(`${count} Salesman`).removeClass('hidden');
            } else {
                $('#badgeCounter').addClass('hidden');
            }
        }

        // Load Detail Saldo via AJAX
        function loaddetailsaldo() {
            const $bulan = $("#bulan");
            const $tahun = $("#tahun");
            const $kodeCabang = $("#kode_cabang");

            let hasError = false;
            let $firstErrorField = null;

            if (!$bulan.val()) {
                showFieldError($bulan, "Silakan pilih bulan");
                hasError = true;
                if (!$firstErrorField) $firstErrorField = $bulan;
            }

            if (!$tahun.val()) {
                showFieldError($tahun, "Silakan pilih tahun");
                hasError = true;
                if (!$firstErrorField) $firstErrorField = $tahun;
            }

            if (!$kodeCabang.val()) {
                showFieldError($kodeCabang, "Silakan pilih cabang");
                hasError = true;
                if (!$firstErrorField) $firstErrorField = $kodeCabang;
            }

            if (hasError) {
                $firstErrorField.focus();
                return false;
            }

            const originalBtnHtml = $btnGetSaldo.html();
            $btnGetSaldo.prop('disabled', true).addClass('opacity-75 cursor-not-allowed pointer-events-none').html(`
                <svg class="animate-spin h-3.5 w-3.5 text-white shrink-0 inline-block" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <span>Memuat...</span>
            `);

            $("#loaddetailsaldo").html(`
                <tr>
                    <td colspan="3" class="py-8 text-center">
                        <div class="flex items-center justify-center gap-2 text-slate-500">
                            <div class="w-5 h-5 border-2 border-[#284c9a] border-t-transparent rounded-full animate-spin"></div>
                            <span class="text-xs font-semibold">Mengambil data saldo akhir piutang...</span>
                        </div>
                    </td>
                </tr>
            `);

            $.ajax({
                type: "POST",
                url: "{{ route('sapiutangsales.getdetailsaldo') }}",
                data: {
                    _token: "{{ csrf_token() }}",
                    bulan: $bulan.val(),
                    tahun: $tahun.val(),
                    kode_cabang: $kodeCabang.val()
                },
                cache: false,
                success: function(respond) {
                    $("#loaddetailsaldo").html(respond);
                    calculateTotal();
                    if (typeof lucide !== 'undefined') {
                        lucide.createIcons();
                    }
                },
                error: function() {
                    $("#loaddetailsaldo").html(`
                        <tr>
                            <td colspan="3" class="py-6 px-4 text-center text-rose-500 font-semibold text-xs">
                                Terjadi kesalahan saat memuat data. Silakan coba lagi.
                            </td>
                        </tr>
                    `);
                },
                complete: function() {
                    $btnGetSaldo.prop('disabled', false).removeClass('opacity-75 cursor-not-allowed pointer-events-none').html(originalBtnHtml);
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

        // Submit form
        $form.on('submit', function(e) {
            const hasRows = $('.saldo_akhir').length > 0;
            
            if (!hasRows) {
                e.preventDefault();
                Swal.fire({
                    title: "Perhatian",
                    text: "Silakan Get Saldo terlebih dahulu untuk memuat rincian saldo awal!",
                    icon: "warning",
                    confirmButtonColor: "#284c9a"
                });
                return false;
            }

            // Serialize data to JSON
            let data = [];
            $('.saldo_akhir').each(function() {
                let kode_salesman = $(this).closest('tr').find('.kode_salesman').val();
                let saldo_akhir = $(this).val();
                data.push({
                    kode_salesman: kode_salesman,
                    saldo_akhir: saldo_akhir
                });
            });
            $('#data_saldo').val(JSON.stringify(data));

            // Anti double submit
            $btnSimpan.prop('disabled', true).addClass('opacity-75 cursor-not-allowed pointer-events-none').html(`
                <svg class="animate-spin h-3.5 w-3.5 text-white shrink-0 inline-block" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <span>Menyimpan Saldo Awal...</span>
            `);
        });

        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }
    });
</script>
@endpush
