@extends('v2.layouts.app')
@section('titlepage', 'Buat Saldo Awal Piutang')

@section('content')
@section('navigasi')
    <div class="flex items-center gap-1.5 text-xs">
        <span class="text-slate-400">Home</span>
        <i data-lucide="chevron-right" class="w-3 h-3 text-slate-400"></i>
        <span class="text-slate-400">Marketing</span>
        <i data-lucide="chevron-right" class="w-3 h-3 text-slate-400"></i>
        <a href="{{ route('sapiutang.index') }}" class="text-slate-400 hover:text-brand-600 transition">Saldo Awal Piutang</a>
        <i data-lucide="chevron-right" class="w-3 h-3 text-slate-400"></i>
        <span class="text-brand-600 dark:text-brand-400 font-bold">Buat Saldo Awal</span>
    </div>
@endsection

<form action="{{ route('sapiutang.store') }}" method="POST" id="formCreatesaldoawal" class="space-y-4" autocomplete="off">
    @csrf
    <input type="hidden" name="data_saldo" id="data_saldo">

    <!-- PAGE TITLE BAR -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2.5">
        <div>
            <h3 class="text-base font-black text-slate-900 dark:text-white leading-tight">Buat Saldo Awal Piutang</h3>
            <p class="text-[11px] text-slate-400 mt-0.5">Penetapan saldo awal piutang pelanggan per periode bulan dan tahun.</p>
        </div>

        <a href="{{ route('sapiutang.index') }}"
            class="inline-flex items-center justify-center gap-1.5 h-[34px] px-3.5 rounded-lg border border-slate-200 dark:border-slate-700 hover:bg-slate-100 dark:hover:bg-slate-800 text-slate-600 dark:text-slate-300 text-xs font-bold transition shadow-2xs cursor-pointer shrink-0">
            <i data-lucide="arrow-left" class="w-3.5 h-3.5"></i>
            <span>Kembali</span>
        </a>
    </div>

    <div class="max-w-5xl space-y-4">
        
        <!-- CARD 1: PILIH PERIODE & GET SALDO (NO-LABEL, KOMPAK ~36px) -->
        <div class="rounded-xl border border-slate-200/80 dark:border-slate-800 bg-white dark:bg-slate-900 p-3.5 sm:p-4 shadow-2xs space-y-3">
            <div class="flex items-center gap-1.5 pb-2 border-b border-slate-100 dark:border-slate-800 text-xs font-bold text-slate-700 dark:text-slate-200">
                <i data-lucide="calendar" class="w-3.5 h-3.5 text-[#284c9a] dark:text-blue-400"></i>
                <span>Pilih Periode Saldo Awal</span>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-12 gap-2.5 items-start">
                
                <!-- Bulan -->
                <div class="sm:col-span-5 field-group">
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

                <!-- Tahun -->
                <div class="sm:col-span-4 field-group">
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

                <!-- Tombol Get Saldo -->
                <div class="sm:col-span-3">
                    <button type="button" id="getsaldo"
                        class="w-full h-[36px] px-4 rounded-lg bg-emerald-600 hover:bg-emerald-700 active:bg-emerald-800 text-white text-xs font-bold transition flex items-center justify-center gap-1.5 shadow-2xs cursor-pointer">
                        <i data-lucide="refresh-cw" class="w-3.5 h-3.5"></i>
                        <span>Get Saldo</span>
                    </button>
                </div>

            </div>
        </div>

        <!-- CARD 2: TABEL RINCIAN SALDO AKHIR PIUTANG (KOMPAK ~36px) -->
        <div class="rounded-xl border border-slate-200/80 dark:border-slate-800 bg-white dark:bg-slate-900 overflow-hidden shadow-2xs">
            <div class="overflow-x-auto max-h-[50vh] overflow-y-auto">
                <table class="w-full text-xs text-left border-separate border-spacing-0 whitespace-nowrap">
                    <thead class="sticky top-0 z-10">
                        <!-- Baris 1: Strip Header Pacific Navy -->
                        <tr class="bg-[#284c9a] text-white">
                            <th colspan="4" class="px-3.5 py-2 bg-[#284c9a] text-white border-0">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center gap-1.5">
                                        <i data-lucide="layers" class="w-3.5 h-3.5 text-blue-200"></i>
                                        <span class="text-xs font-bold text-white">Rincian Saldo Akhir Piutang</span>
                                    </div>
                                    <span class="text-[10.5px] text-white/70 font-mono-num" id="counter_piutang">
                                        0 Faktur Terdata
                                    </span>
                                </div>
                            </th>
                        </tr>

                        <!-- Baris 2: Header Kolom -->
                        <tr class="bg-[#1e3a78] text-white text-[11px]">
                            <th class="py-2 px-3 font-bold uppercase tracking-wider text-white/90 border-b border-blue-900 w-36">NO. FAKTUR</th>
                            <th class="py-2 px-3 font-bold uppercase tracking-wider text-white/90 border-b border-blue-900 w-28">TANGGAL</th>
                            <th class="py-2 px-3 font-bold uppercase tracking-wider text-white/90 border-b border-blue-900 min-w-[200px]">PELANGGAN</th>
                            <th class="py-2 px-3 text-right font-bold uppercase tracking-wider text-white/90 border-b border-blue-900 w-36">SALDO</th>
                        </tr>
                    </thead>
                    <tbody id="loaddetailsaldo" class="divide-y divide-slate-100 dark:divide-slate-800/80 bg-white dark:bg-slate-900">
                        <tr>
                            <td colspan="4" class="py-12 px-4 text-center text-slate-400">
                                <div class="flex flex-col items-center justify-center gap-2">
                                    <div class="w-10 h-10 rounded-full bg-slate-100 dark:bg-slate-800 text-slate-400 flex items-center justify-center">
                                        <i data-lucide="database" class="w-5 h-5"></i>
                                    </div>
                                    <span class="text-xs font-semibold text-slate-500 dark:text-slate-400">Pilih periode dan klik tombol "Get Saldo" untuk menampilkan data rincian piutang</span>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- TOTAL DISPLAY BANNER & SUBMIT -->
            <div class="p-4 bg-slate-50/80 dark:bg-slate-800/40 border-t border-slate-200/80 dark:border-slate-800 space-y-3">
                <div class="rounded-xl bg-[#284c9a] text-white p-4 shadow-sm flex items-center justify-between">
                    <div class="flex items-center gap-2.5">
                        <div class="w-10 h-10 rounded-lg bg-white/10 flex items-center justify-center text-white">
                            <i data-lucide="calculator" class="w-5 h-5"></i>
                        </div>
                        <div>
                            <span class="text-[10.5px] uppercase tracking-wider font-bold text-white/70 block">Total Saldo Awal Piutang</span>
                            <span class="text-xs text-white/90">Akumulasi seluruh faktur piutang berjalan</span>
                        </div>
                    </div>
                    <div class="text-right">
                        <h4 class="text-xl sm:text-2xl font-black font-mono-num text-white" id="total_piutang">0</h4>
                    </div>
                </div>

                <button type="submit" id="btnSimpan"
                    class="w-full h-[40px] rounded-lg bg-[#284c9a] hover:bg-blue-800 active:bg-blue-900 text-white text-xs font-bold transition flex items-center justify-center gap-2 shadow-2xs cursor-pointer">
                    <i data-lucide="send" class="w-4 h-4"></i>
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
        function calculateTotal() {
            let total = 0;
            let count = 0;
            $('.jumlah-piutang').each(function() {
                let val = $(this).val();
                total += parseFloat(val) || 0;
                count++;
            });
            $('#total_piutang').text('Rp ' + new Intl.NumberFormat('id-ID').format(total));
            $('#counter_piutang').text(count + ' Faktur Terdata');
        }

        // Inline validation helpers
        function clearFieldError($input) {
            $input.removeClass('border-rose-500 ring-1 ring-rose-500 focus:border-rose-500');
            $input.closest('.relative').find('.input-prefix-icon').removeClass('text-rose-500').addClass('text-slate-400');
            $input.closest('.field-group').find('.field-error-msg').remove();
        }

        function setFieldError($input, message) {
            clearFieldError($input);
            $input.addClass('border-rose-500 ring-1 ring-rose-500 focus:border-rose-500');
            $input.closest('.relative').find('.input-prefix-icon').removeClass('text-slate-400').addClass('text-rose-500');
            
            const errorHtml = `
                <p class="field-error-msg text-[11px] font-semibold text-rose-600 dark:text-rose-400 mt-1 pl-1 flex items-center gap-1">
                    <svg class="w-3.5 h-3.5 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                    </svg>
                    <span>${message}</span>
                </p>
            `;
            $input.closest('.field-group').append(errorHtml);
        }

        // Live cleanup
        $('select').on('change', function() {
            clearFieldError($(this));
        });

        function buttonDisable() {
            $("#btnSimpan").prop('disabled', true).addClass('opacity-75 cursor-not-allowed pointer-events-none');
            $("#btnSimpan").html(`
                <svg class="animate-spin h-4 w-4 text-white shrink-0 inline-block" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <span>Menyimpan Saldo Awal...</span>
            `);
        }

        function loaddetailsaldo() {
            var bulan = $("#bulan").val();
            var tahun = $("#tahun").val();
            let hasError = false;

            if (!bulan) {
                setFieldError($('#bulan'), 'Pilih bulan terlebih dahulu');
                hasError = true;
            }

            if (!tahun) {
                setFieldError($('#tahun'), 'Pilih tahun terlebih dahulu');
                hasError = true;
            }

            if (hasError) {
                return false;
            }

            const $btn = $("#getsaldo");
            $btn.prop('disabled', true).addClass('opacity-75 pointer-events-none');
            $btn.html(`
                <svg class="animate-spin h-3.5 w-3.5 text-white inline-block shrink-0" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <span>Memuat...</span>
            `);

            $.ajax({
                type: "POST",
                url: "{{ route('sapiutang.getdetailsaldo') }}",
                data: {
                    _token: "{{ csrf_token() }}",
                    bulan: bulan,
                    tahun: tahun
                },
                cache: false,
                beforeSend: function() {
                    $("#loaddetailsaldo").html(`
                        <tr>
                            <td colspan="4" class="py-12 text-center">
                                <div class="flex flex-col items-center justify-center gap-2">
                                    <svg class="animate-spin h-5 w-5 text-[#284c9a]" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                    <span class="text-xs font-semibold text-slate-500">Menghitung rincian saldo piutang berjalan...</span>
                                </div>
                            </td>
                        </tr>
                    `);
                },
                success: function(respond) {
                    $btn.prop('disabled', false).removeClass('opacity-75 pointer-events-none');
                    $btn.html(`
                        <i data-lucide="refresh-cw" class="w-3.5 h-3.5"></i>
                        <span>Get Saldo</span>
                    `);
                    $("#loaddetailsaldo").html(respond);
                    calculateTotal();
                    if (typeof lucide !== 'undefined') {
                        lucide.createIcons();
                    }
                },
                error: function() {
                    $btn.prop('disabled', false).removeClass('opacity-75 pointer-events-none');
                    $btn.html(`
                        <i data-lucide="refresh-cw" class="w-3.5 h-3.5"></i>
                        <span>Get Saldo</span>
                    `);
                    $("#loaddetailsaldo").html(`
                        <tr>
                            <td colspan="4" class="py-8 text-center text-rose-500 font-semibold text-xs">
                                Gagal memuat data saldo piutang. Silakan coba lagi.
                            </td>
                        </tr>
                    `);
                    if (typeof lucide !== 'undefined') {
                        lucide.createIcons();
                    }
                }
            });
        }

        $("#getsaldo").click(function(e) {
            e.preventDefault();
            loaddetailsaldo();
        });

        $("#formCreatesaldoawal").submit(function(e) {
            const form = $("#formCreatesaldoawal");
            const $tbody = form.find('#loaddetailsaldo');

            if ($tbody.find('.jumlah-piutang').length === 0) {
                e.preventDefault();
                Swal.fire({
                    title: "Perhatian!",
                    text: "Silakan klik tombol 'Get Saldo' terlebih dahulu untuk memuat rincian data piutang!",
                    icon: "warning",
                    confirmButtonColor: '#284c9a',
                    confirmButtonText: 'OK'
                });
                return false;
            }

            // Serialize data to JSON
            let data = [];
            $('.jumlah-piutang').each(function() {
                let no_faktur = $(this).closest('tr').find('.no_faktur').val();
                let jumlah = $(this).val();
                data.push({
                    no_faktur: no_faktur,
                    jumlah: jumlah
                });
            });
            $('#data_saldo').val(JSON.stringify(data));

            buttonDisable();
        });

        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }
    });
</script>
@endpush
