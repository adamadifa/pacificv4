@extends('v2.layouts.app')
@section('titlepage', 'Buat Saldo Awal Buku Besar')

@section('navigasi')
    <div class="flex items-center gap-1.5 text-xs">
        <span class="text-slate-400">Accounting</span>
        <i data-lucide="chevron-right" class="w-3 h-3 text-slate-400"></i>
        <a href="{{ route('saldoawalbukubesar.index') }}" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 transition">Saldo Awal Buku Besar</a>
        <i data-lucide="chevron-right" class="w-3 h-3 text-slate-400"></i>
        <span class="text-brand-600 dark:text-brand-400 font-bold">Buat Saldo Awal</span>
    </div>
@endsection

@section('content')
<div class="space-y-3.5 w-full max-w-4xl">

    <!-- PAGE TITLE BAR -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2.5">
        <div>
            <h3 class="text-base font-black text-slate-900 dark:text-white leading-tight">Buat Saldo Awal Buku Besar</h3>
            <p class="text-[11px] text-slate-400 mt-0.5">Tarik saldo akhir periode sebelumnya sebagai saldo awal buku besar.</p>
        </div>
        <a href="{{ route('saldoawalbukubesar.index') }}"
            class="h-[34px] px-3 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 hover:bg-slate-50 dark:hover:bg-slate-800 text-slate-600 dark:text-slate-300 text-xs font-bold transition flex items-center gap-1.5 w-fit">
            <i data-lucide="arrow-left" class="w-3.5 h-3.5"></i>
            <span>Kembali</span>
        </a>
    </div>

    <form action="{{ route('saldoawalbukubesar.store') }}" method="POST" id="formCreatesaldoawal" autocomplete="off" class="space-y-3.5">
        @csrf

        <!-- CARD PERIODE & GET SALDO -->
        <div class="rounded-xl border border-slate-200/80 dark:border-slate-800 bg-white dark:bg-slate-900 p-3 sm:p-3.5 shadow-2xs">
            <div class="flex items-center gap-1.5 mb-2.5">
                <i data-lucide="calendar-range" class="w-3.5 h-3.5 text-[#284c9a] dark:text-blue-400"></i>
                <h5 class="text-xs font-bold text-slate-700 dark:text-slate-200">Periode Saldo Awal</h5>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-12 gap-2.5 items-start">
                <!-- Bulan -->
                <div class="sm:col-span-5">
                    <label for="bulan_create" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                        Bulan <span class="text-rose-500">*</span>
                    </label>
                    <div class="relative">
                        <div class="prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                            <i data-lucide="calendar" class="w-4 h-4"></i>
                        </div>
                        <select name="bulan" id="bulan_create"
                            class="w-full h-[36px] pl-9 pr-8 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-950 text-xs font-medium text-slate-700 dark:text-slate-200 focus:outline-none focus:border-[#284c9a] focus:ring-2 focus:ring-[#284c9a]/15 transition">
                            <option value="">Pilih Bulan</option>
                            @foreach ($list_bulan as $d)
                                <option value="{{ $d['kode_bulan'] }}">{{ $d['nama_bulan'] }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <!-- Tahun -->
                <div class="sm:col-span-5">
                    <label for="tahun_create" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                        Tahun <span class="text-rose-500">*</span>
                    </label>
                    <div class="relative">
                        <div class="prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                            <i data-lucide="calendar-days" class="w-4 h-4"></i>
                        </div>
                        <select name="tahun" id="tahun_create"
                            class="w-full h-[36px] pl-9 pr-8 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-950 text-xs font-medium text-slate-700 dark:text-slate-200 focus:outline-none focus:border-[#284c9a] focus:ring-2 focus:ring-[#284c9a]/15 transition">
                            <option value="">Pilih Tahun</option>
                            @for ($t = $start_year; $t <= date('Y'); $t++)
                                <option value="{{ $t }}">{{ $t }}</option>
                            @endfor
                        </select>
                    </div>
                </div>

                <!-- Tombol Get Saldo (Zero Layout Shift) -->
                <div class="sm:col-span-2">
                    <label class="hidden sm:block text-xs font-bold text-transparent mb-1 select-none pointer-events-none">&nbsp;</label>
                    <button type="button" id="getsaldo"
                        class="w-full h-[36px] px-3 rounded-lg bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold shadow-xs transition flex items-center justify-center gap-1.5 cursor-pointer">
                        <i id="getsaldo-icon" data-lucide="refresh-cw" class="w-3.5 h-3.5 shrink-0"></i>
                        <svg id="getsaldo-loading" class="hidden animate-spin h-3.5 w-3.5 text-white shrink-0" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        <span id="getsaldo-text">Get Saldo</span>
                    </button>
                </div>
            </div>
        </div>

        <!-- CARD TABEL RINCIAN SALDO -->
        <div class="rounded-xl border border-slate-200/80 dark:border-slate-800 bg-white dark:bg-slate-900 shadow-2xs overflow-hidden">
            <div class="px-3.5 py-2 bg-[#284c9a] flex items-center justify-between text-white">
                <div class="flex items-center gap-2">
                    <i data-lucide="list-checks" class="w-4 h-4 text-blue-200"></i>
                    <h5 class="text-xs font-bold text-white uppercase tracking-wider">Rincian Saldo Awal Akun</h5>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-xs text-left whitespace-nowrap">
                    <thead class="bg-[#1e3a78] text-white/90 text-[11px] font-bold uppercase tracking-wider">
                        <tr>
                            <th class="py-2 px-3">NAMA AKUN</th>
                            <th class="py-2 px-3 text-right w-44">JUMLAH</th>
                        </tr>
                    </thead>
                    <tbody id="loaddetailsaldo" class="divide-y divide-slate-100 dark:divide-slate-800">
                        <tr id="empty-row">
                            <td colspan="2" class="py-10 px-4 text-center">
                                <div class="flex flex-col items-center justify-center gap-1.5 text-slate-400">
                                    <i data-lucide="inbox" class="w-8 h-8 text-slate-300 dark:text-slate-600"></i>
                                    <span class="text-xs font-semibold">Pilih Bulan dan Tahun lalu klik Get Saldo untuk memuat data</span>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Inline Error Tabel Kosong -->
            <div id="error-loaddetailsaldo" class="hidden px-3.5 py-2 bg-rose-50/80 dark:bg-rose-950/30 border-t border-rose-200/80 dark:border-rose-900/60 text-[11px] font-semibold text-rose-600 dark:text-rose-400 items-center gap-1.5">
                <svg class="w-3.5 h-3.5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                <span>Data saldo awal belum dimuat. Silakan klik tombol Get Saldo terlebih dahulu.</span>
            </div>

            <!-- Footer Submit (Right-Aligned) -->
            <div class="px-3.5 py-3 bg-slate-50/60 dark:bg-slate-900/60 border-t border-slate-100 dark:border-slate-800 flex items-center justify-end gap-2">
                <a href="{{ route('saldoawalbukubesar.index') }}"
                    class="h-[34px] px-3.5 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 hover:bg-slate-50 dark:hover:bg-slate-800 text-slate-600 dark:text-slate-300 text-xs font-bold transition flex items-center justify-center">
                    Batal
                </a>
                <button type="submit" id="btnSubmitSaldoAwal"
                    class="h-[34px] px-4 rounded-lg bg-[#284c9a] hover:bg-[#1e3a78] text-white text-xs font-bold shadow-xs transition flex items-center justify-center gap-1.5 cursor-pointer">
                    <i data-lucide="save" class="w-3.5 h-3.5"></i>
                    <span>Simpan Saldo Awal</span>
                </button>
            </div>
        </div>
    </form>
</div>
@endsection

@push('myscript')
<script>
    $(function() {
        if (typeof lucide !== 'undefined') lucide.createIcons();

        const $form = $('#formCreatesaldoawal');
        const $bulan = $form.find('#bulan_create');
        const $tahun = $form.find('#tahun_create');
        const $tbody = $form.find('#loaddetailsaldo');
        const $tableError = $form.find('#error-loaddetailsaldo');

        function setFieldError($el, msg) {
            const $col = $el.closest('div').parent();
            $el.addClass('border-rose-500 ring-1 ring-rose-500 focus:border-rose-500 focus:ring-rose-500/20');
            $el.closest('.relative').find('.prefix-icon').removeClass('text-slate-400').addClass('text-rose-500');
            $col.find('.field-error-msg').remove();
            $col.append(`
                <p class="field-error-msg text-[11px] font-semibold text-rose-600 dark:text-rose-400 mt-1 pl-1 flex items-center gap-1">
                    <svg class="w-3.5 h-3.5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                    <span>${msg}</span>
                </p>
            `);
        }

        function clearFieldError($el) {
            const $col = $el.closest('div').parent();
            $el.removeClass('border-rose-500 ring-1 ring-rose-500 focus:border-rose-500 focus:ring-rose-500/20');
            $el.closest('.relative').find('.prefix-icon').removeClass('text-rose-500').addClass('text-slate-400');
            $col.find('.field-error-msg').remove();
        }

        function validatePeriode() {
            let firstInvalid = null;
            if (!$bulan.val()) {
                setFieldError($bulan, 'Bulan wajib dipilih.');
                firstInvalid = firstInvalid || $bulan;
            }
            if (!$tahun.val()) {
                setFieldError($tahun, 'Tahun wajib dipilih.');
                firstInvalid = firstInvalid || $tahun;
            }
            if (firstInvalid) {
                firstInvalid.focus();
                return false;
            }
            return true;
        }

        $bulan.add($tahun).on('change input', function() {
            if ($(this).val()) clearFieldError($(this));
        });

        $('#getsaldo').on('click', function(e) {
            e.preventDefault();
            if (!validatePeriode()) return false;

            const $btn = $(this);
            const bulan = $bulan.val();
            const tahun = $tahun.val();

            $btn.prop('disabled', true).addClass('opacity-75 cursor-not-allowed pointer-events-none');
            $('#getsaldo-icon').addClass('hidden');
            $('#getsaldo-loading').removeClass('hidden');
            $('#getsaldo-text').text('Memuat...');
            $tableError.removeClass('flex').addClass('hidden');

            $tbody.html(`
                <tr>
                    <td colspan="2" class="py-10 px-4 text-center">
                        <div class="flex flex-col items-center justify-center gap-2">
                            <svg class="animate-spin h-6 w-6 text-[#284c9a]" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            <span class="text-xs font-semibold text-slate-500">Memuat saldo akhir periode sebelumnya...</span>
                        </div>
                    </td>
                </tr>
            `);

            $.ajax({
                type: 'POST',
                url: "{{ route('saldoawalbukubesar.getsaldo') }}",
                data: {
                    _token: "{{ csrf_token() }}",
                    bulan: bulan,
                    tahun: tahun
                },
                cache: false,
                success: function(respond) {
                    $tbody.html(respond);
                },
                error: function(xhr) {
                    let errorMessage = 'Terjadi kesalahan saat mengambil saldo!';
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMessage = xhr.responseJSON.message;
                    } else if (xhr.responseText) {
                        try {
                            const response = JSON.parse(xhr.responseText);
                            if (response.message) errorMessage = response.message;
                        } catch (err) {}
                    }

                    Swal.fire({
                        title: 'Oops!',
                        text: errorMessage,
                        icon: 'error',
                    });

                    $tbody.html(`
                        <tr id="empty-row">
                            <td colspan="2" class="py-10 px-4 text-center text-xs font-semibold text-slate-400">
                                Tidak ada data saldo yang dimuat.
                            </td>
                        </tr>
                    `);
                },
                complete: function() {
                    $btn.prop('disabled', false).removeClass('opacity-75 cursor-not-allowed pointer-events-none');
                    $('#getsaldo-icon').removeClass('hidden');
                    $('#getsaldo-loading').addClass('hidden');
                    $('#getsaldo-text').text('Get Saldo');
                }
            });
        });

        $form.on('submit', function(e) {
            let hasError = !validatePeriode();
            const jmldata = $tbody.find('input[name="kode_akun[]"]').length;

            if (jmldata === 0) {
                $tableError.removeClass('hidden').addClass('flex');
                hasError = true;
            } else {
                $tableError.removeClass('flex').addClass('hidden');
            }

            if (hasError) {
                e.preventDefault();
                return false;
            }

            const $submitBtn = $('#btnSubmitSaldoAwal');
            $submitBtn.prop('disabled', true)
                .addClass('opacity-75 cursor-not-allowed pointer-events-none')
                .html(`
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
