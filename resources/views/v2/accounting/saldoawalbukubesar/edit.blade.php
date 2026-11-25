@extends('v2.layouts.app')
@section('titlepage', 'Edit Saldo Awal Buku Besar')

@section('navigasi')
    <div class="flex items-center gap-1.5 text-xs">
        <span class="text-slate-400">Accounting</span>
        <i data-lucide="chevron-right" class="w-3 h-3 text-slate-400"></i>
        <a href="{{ route('saldoawalbukubesar.index') }}" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 transition">Saldo Awal Buku Besar</a>
        <i data-lucide="chevron-right" class="w-3 h-3 text-slate-400"></i>
        <span class="text-brand-600 dark:text-brand-400 font-bold">Edit Saldo Awal</span>
    </div>
@endsection

@section('content')
<div class="space-y-3.5 w-full max-w-4xl">

    <!-- PAGE TITLE BAR -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2.5">
        <div>
            <h3 class="text-base font-black text-slate-900 dark:text-white leading-tight">Edit Saldo Awal Buku Besar</h3>
            <p class="text-[11px] text-slate-400 mt-0.5">Kelola rincian akun saldo awal buku besar periode {{ $nama_bulan[$saldoawalbukubesar->bulan] ?? $saldoawalbukubesar->bulan }} {{ $saldoawalbukubesar->tahun }}.</p>
        </div>
        <a href="{{ route('saldoawalbukubesar.index') }}"
            class="h-[34px] px-3 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 hover:bg-slate-50 dark:hover:bg-slate-800 text-slate-600 dark:text-slate-300 text-xs font-bold transition flex items-center gap-1.5 w-fit">
            <i data-lucide="arrow-left" class="w-3.5 h-3.5"></i>
            <span>Kembali</span>
        </a>
    </div>

    <form action="{{ route('saldoawalbukubesar.store') }}" method="POST" id="formEditsaldoawal" autocomplete="off" class="space-y-3.5">
        @csrf
        <input type="hidden" name="bulan" id="bulan_edit" value="{{ $saldoawalbukubesar->bulan }}">
        <input type="hidden" name="tahun" id="tahun_edit" value="{{ $saldoawalbukubesar->tahun }}">

        <!-- RINGKASAN PERIODE -->
        <div class="rounded-xl border border-slate-200/80 dark:border-slate-800 bg-white dark:bg-slate-900 p-3 sm:p-3.5 shadow-2xs">
            <div class="flex items-center justify-between pb-2 mb-2.5 border-b border-slate-200/80 dark:border-slate-800">
                <div class="flex items-center gap-1.5">
                    <i data-lucide="info" class="w-3.5 h-3.5 text-[#284c9a] dark:text-blue-400"></i>
                    <h5 class="text-xs font-bold text-slate-700 dark:text-slate-200">Informasi Periode</h5>
                </div>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-2.5">
                <div class="p-2.5 rounded-lg bg-slate-50 dark:bg-slate-800/50 border border-slate-200/60 dark:border-slate-800">
                    <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Kode Saldo Awal</p>
                    <p class="text-xs font-bold font-mono-num text-slate-800 dark:text-slate-100 mt-0.5">{{ $saldoawalbukubesar->kode_saldo_awal }}</p>
                </div>
                <div class="p-2.5 rounded-lg bg-slate-50 dark:bg-slate-800/50 border border-slate-200/60 dark:border-slate-800">
                    <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Bulan</p>
                    <p class="text-xs font-bold text-slate-800 dark:text-slate-100 mt-0.5">{{ $nama_bulan[$saldoawalbukubesar->bulan] ?? $saldoawalbukubesar->bulan }}</p>
                </div>
                <div class="p-2.5 rounded-lg bg-slate-50 dark:bg-slate-800/50 border border-slate-200/60 dark:border-slate-800">
                    <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Tahun</p>
                    <p class="text-xs font-bold font-mono-num text-slate-800 dark:text-slate-100 mt-0.5">{{ $saldoawalbukubesar->tahun }}</p>
                </div>
            </div>
        </div>

        <!-- CARD TAMBAH AKUN -->
        <div class="rounded-xl border border-slate-200/80 dark:border-slate-800 bg-white dark:bg-slate-900 p-3 sm:p-3.5 shadow-2xs">
            <div class="flex items-center gap-1.5 mb-2.5">
                <i data-lucide="plus-circle" class="w-3.5 h-3.5 text-[#284c9a] dark:text-blue-400"></i>
                <h5 class="text-xs font-bold text-slate-700 dark:text-slate-200">Tambah Akun Saldo Awal</h5>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-12 gap-2.5 items-start">
                <!-- Pilih Akun (Select2 V2) -->
                <div class="sm:col-span-6">
                    <label for="kode_akun_edit" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                        Akun CoA <span class="text-rose-500">*</span>
                    </label>
                    <div class="relative select2-container-v2">
                        <div class="prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-20">
                            <i data-lucide="layers" class="w-4 h-4"></i>
                        </div>
                        <select name="kode_akun_input" id="kode_akun_edit" class="select2Kodeakun w-full">
                            <option value="">Pilih Akun</option>
                            @foreach ($coa as $d)
                                <option value="{{ $d['kode_akun'] }}">{{ $d['kode_akun'] }} - {{ $d['nama_akun'] }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <!-- Jumlah -->
                <div class="sm:col-span-4">
                    <label for="jumlah_edit" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                        Jumlah <span class="text-rose-500">*</span>
                    </label>
                    <div class="relative">
                        <div class="prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                            <i data-lucide="banknote" class="w-4 h-4"></i>
                        </div>
                        <input type="text" name="jumlah_input" id="jumlah_edit" placeholder="0"
                            class="w-full h-[36px] pl-9 pr-3 text-right font-mono-num rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-950 text-xs font-medium text-slate-700 dark:text-slate-200 focus:outline-none focus:border-[#284c9a] focus:ring-2 focus:ring-[#284c9a]/15 transition">
                    </div>
                </div>

                <!-- Tombol Tambah (Zero Layout Shift) -->
                <div class="sm:col-span-2">
                    <label class="hidden sm:block text-xs font-bold text-transparent mb-1 select-none pointer-events-none">&nbsp;</label>
                    <button type="button" id="addsaldoawal"
                        class="w-full h-[36px] px-3 rounded-lg bg-[#284c9a] hover:bg-[#1e3a78] text-white text-xs font-bold shadow-xs transition flex items-center justify-center gap-1.5 cursor-pointer">
                        <i data-lucide="plus" class="w-3.5 h-3.5"></i>
                        <span>Tambah</span>
                    </button>
                </div>
            </div>
        </div>

        <!-- CARD TABEL RINCIAN SALDO -->
        <div class="rounded-xl border border-slate-200/80 dark:border-slate-800 bg-white dark:bg-slate-900 shadow-2xs overflow-hidden">
            <div class="px-3.5 py-2 bg-[#284c9a] flex items-center justify-between text-white">
                <div class="flex items-center gap-2">
                    <i data-lucide="list-checks" class="w-4 h-4 text-blue-200"></i>
                    <h5 class="text-xs font-bold text-white uppercase tracking-wider">Daftar Saldo Awal Akun</h5>
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
                        @foreach ($detailsaldoawalbukubesar as $d)
                            <tr id="idx-{{ $d->kode_akun }}" class="hover:bg-slate-50/80 dark:hover:bg-slate-800/40 transition-colors">
                                <td class="py-1.5 px-3 text-slate-700 dark:text-slate-200">
                                    <input type="hidden" name="kode_akun[]" value="{{ $d->kode_akun }}" />
                                    {{ $d->nama_akun }}
                                </td>
                                <td class="py-1.5 px-3 text-right font-mono-num font-semibold text-slate-800 dark:text-slate-100">
                                    <input type="hidden" name="jumlah[]" value="{{ $d->jumlah }}" />
                                    {{ formatAngka($d->jumlah) }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div id="error-loaddetailsaldo" class="hidden px-3.5 py-2 bg-rose-50/80 dark:bg-rose-950/30 border-t border-rose-200/80 dark:border-rose-900/60 text-[11px] font-semibold text-rose-600 dark:text-rose-400 items-center gap-1.5">
                <svg class="w-3.5 h-3.5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                <span>Data saldo awal minimal harus memiliki 1 akun.</span>
            </div>

            <!-- Footer Submit (Right-Aligned) -->
            <div class="px-3.5 py-3 bg-slate-50/60 dark:bg-slate-900/60 border-t border-slate-100 dark:border-slate-800 flex items-center justify-end gap-2">
                <a href="{{ route('saldoawalbukubesar.index') }}"
                    class="h-[34px] px-3.5 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 hover:bg-slate-50 dark:hover:bg-slate-800 text-slate-600 dark:text-slate-300 text-xs font-bold transition flex items-center justify-center">
                    Batal
                </a>
                <button type="submit" id="btnSubmitEditSaldoAwal"
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

        const $form = $('#formEditsaldoawal');
        const $kodeAkun = $form.find('#kode_akun_edit');
        const $jumlah = $form.find('#jumlah_edit');
        const $tbody = $form.find('#loaddetailsaldo');
        const $tableError = $form.find('#error-loaddetailsaldo');

        $jumlah.maskMoney();

        $kodeAkun.select2({
            placeholder: 'Pilih Akun',
            allowClear: true,
            dropdownCssClass: 'select2-dropdown-v2'
        });

        function setFieldError($el, msg) {
            const $col = $el.closest('div').parent();
            if ($el.hasClass('select2-hidden-accessible')) {
                $el.next('.select2-container').find('.select2-selection').addClass('!border-rose-500 !ring-1 !ring-rose-500');
            } else {
                $el.addClass('border-rose-500 ring-1 ring-rose-500 focus:border-rose-500 focus:ring-rose-500/20');
            }
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
            if ($el.hasClass('select2-hidden-accessible')) {
                $el.next('.select2-container').find('.select2-selection').removeClass('!border-rose-500 !ring-1 !ring-rose-500');
            } else {
                $el.removeClass('border-rose-500 ring-1 ring-rose-500 focus:border-rose-500 focus:ring-rose-500/20');
            }
            $el.closest('.relative').find('.prefix-icon').removeClass('text-rose-500').addClass('text-slate-400');
            $col.find('.field-error-msg').remove();
        }

        $kodeAkun.on('select2:select change', function() {
            if ($(this).val()) clearFieldError($(this));
        });

        $jumlah.on('input change', function() {
            if ($(this).val()) clearFieldError($(this));
        });

        $('#addsaldoawal').on('click', function(e) {
            e.preventDefault();
            const kode_akun = $kodeAkun.val();
            const selectedData = $kodeAkun.select2('data');
            const nama_akun = selectedData && selectedData[0] ? selectedData[0].text : '';
            const jumlah = $jumlah.val();

            let firstInvalid = null;
            if (!kode_akun) {
                setFieldError($kodeAkun, 'Kode Akun wajib dipilih.');
                firstInvalid = firstInvalid || $kodeAkun;
            }
            if (!jumlah) {
                setFieldError($jumlah, 'Jumlah wajib diisi.');
                firstInvalid = firstInvalid || $jumlah;
            }

            if (firstInvalid) {
                firstInvalid.focus();
                return false;
            }

            const exists = $tbody.find('tr').filter(function() {
                return $(this).attr('id') === `idx-${kode_akun}`;
            }).length > 0;

            if (exists) {
                setFieldError($kodeAkun, 'Kode Akun sudah ada di daftar.');
                $kodeAkun.focus();
                return false;
            }

            $tbody.append(`
                <tr id="idx-${kode_akun}" class="hover:bg-slate-50/80 dark:hover:bg-slate-800/40 transition-colors">
                    <td class="py-1.5 px-3 text-slate-700 dark:text-slate-200">
                        <input type="hidden" name="kode_akun[]" value="${kode_akun}"/>
                        ${nama_akun}
                    </td>
                    <td class="py-1.5 px-3 text-right font-mono-num font-semibold text-slate-800 dark:text-slate-100">
                        <input type="hidden" name="jumlah[]" value="${jumlah}"/>
                        ${jumlah}
                    </td>
                </tr>
            `);

            $tableError.removeClass('flex').addClass('hidden');
            $kodeAkun.val('').trigger('change');
            $jumlah.val('');
        });

        $form.on('submit', function(e) {
            const jmldata = $tbody.find('input[name="kode_akun[]"]').length;
            if (jmldata === 0) {
                e.preventDefault();
                $tableError.removeClass('hidden').addClass('flex');
                $kodeAkun.focus();
                return false;
            }

            const $submitBtn = $('#btnSubmitEditSaldoAwal');
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
