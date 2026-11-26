@extends('layouts.app')
@section('titlepage', 'Input Pengiriman Driver')

@section('navigasi')
    <div class="flex items-center gap-1.5 text-xs">
        <a href="{{ route('pengirimandriver.index') }}" class="text-slate-400 hover:text-[#284c9a] transition">Pengiriman Driver</a>
        <i data-lucide="chevron-right" class="w-3 h-3 text-slate-400"></i>
        <span class="text-brand-600 dark:text-brand-400 font-bold">Input</span>
    </div>
@endsection

@section('content')
<div class="space-y-3">
    <!-- PAGE HEADER -->
    <div class="flex items-center justify-between gap-2">
        <div>
            <h3 class="text-base font-black text-slate-900 dark:text-white leading-tight">Input Pengiriman Driver / Helper</h3>
            <p class="text-[11px] text-slate-400 mt-0.5">Lembar kerja pencatatan produk yang dikirim oleh 1 driver dan helper ke pelanggan.</p>
        </div>
        <a href="{{ route('pengirimandriver.index') }}"
            class="h-[34px] px-3 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-600 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-800 text-xs font-bold inline-flex items-center gap-1.5 shrink-0 transition">
            <i data-lucide="arrow-left" class="w-3.5 h-3.5"></i>
            <span>Kembali</span>
        </a>
    </div>

    <form action="{{ route('pengirimandriver.store') }}" method="POST" id="formPengirimanDriver" autocomplete="off" class="space-y-3">
        @csrf

        <!-- HEADER LEMBAR KERJA -->
        <div class="bg-white dark:bg-slate-900 rounded-xl border border-slate-200/80 dark:border-slate-800 shadow-2xs p-3 sm:p-3.5">
            <div class="flex items-center gap-2 pb-2 mb-2.5 border-b border-slate-100 dark:border-slate-800">
                <i data-lucide="clipboard-list" class="w-3.5 h-3.5 text-[#284c9a] dark:text-blue-400"></i>
                <h4 class="text-xs font-bold text-slate-700 dark:text-slate-200">Informasi Pengiriman</h4>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-2.5">
                <div>
                    <label for="tanggal" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                        Tanggal <span class="text-rose-500">*</span>
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                            <i data-lucide="calendar" class="w-3.5 h-3.5"></i>
                        </div>
                        <input type="text" name="tanggal" id="tanggal" value="{{ date('Y-m-d') }}" placeholder="Pilih Tanggal" readonly
                            class="flatpickr-date w-full h-[36px] pl-8 pr-3 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-xs font-medium text-slate-700 dark:text-slate-200 focus:outline-none focus:border-[#284c9a] focus:ring-2 focus:ring-[#284c9a]/15 transition cursor-pointer">
                    </div>
                </div>

                @if ($isPst)
                    <div>
                        <label for="kode_cabang" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                            Cabang <span class="text-rose-500">*</span>
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                                <i data-lucide="building-2" class="w-3.5 h-3.5"></i>
                            </div>
                            <select name="kode_cabang" id="kode_cabang"
                                class="w-full h-[36px] pl-8 pr-3 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-xs font-medium text-slate-700 dark:text-slate-200 focus:outline-none focus:border-[#284c9a] focus:ring-2 focus:ring-[#284c9a]/15 transition">
                                <option value="">Pilih Cabang</option>
                                @foreach ($cabang as $c)
                                    <option value="{{ $c->kode_cabang }}">{{ textUpperCase($c->nama_cabang) }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                @else
                    <input type="hidden" name="kode_cabang" id="kode_cabang" value="{{ $userCabang }}">
                @endif

                <div>
                    <label for="kode_salesman" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                        Nama Sales <span class="text-rose-500">*</span>
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-20">
                            <i data-lucide="user-check" class="w-3.5 h-3.5"></i>
                        </div>
                        <select name="kode_salesman" id="kode_salesman" class="select2Salesman w-full">
                            <option value="">Pilih Salesman</option>
                            @foreach ($salesman as $s)
                                <option value="{{ $s->kode_salesman }}">{{ $s->nama_salesman }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div>
                    <label for="kode_driver" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                        Driver (1 Orang) <span class="text-rose-500">*</span>
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-20">
                            <i data-lucide="truck" class="w-3.5 h-3.5"></i>
                        </div>
                        <select name="kode_driver" id="kode_driver" class="select2Driver w-full">
                            <option value="">Pilih Driver</option>
                            @foreach ($drivers as $dr)
                                <option value="{{ $dr->kode_driver_helper }}">{{ $dr->nama_driver_helper }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="sm:col-span-2">
                    <label for="kode_helper" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                        Helper (Bisa Lebih Dari 1)
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-20">
                            <i data-lucide="users" class="w-3.5 h-3.5"></i>
                        </div>
                        <select name="kode_helper[]" id="kode_helper" multiple class="select2Helper w-full">
                            @foreach ($helpers as $hl)
                                <option value="{{ $hl->kode_driver_helper }}">{{ $hl->nama_driver_helper }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <!-- TABEL LEMBAR KERJA PRODUK (QTY) -->
        <div class="bg-white dark:bg-slate-900 rounded-xl border border-slate-200/80 dark:border-slate-800 shadow-2xs overflow-hidden">
            <div class="bg-[#284c9a] px-3.5 py-2 flex items-center justify-between gap-2">
                <div class="flex items-center gap-2">
                    <i data-lucide="table" class="w-3.5 h-3.5 text-blue-200"></i>
                    <h4 class="text-xs font-bold text-white uppercase tracking-wider">Rincian Produk Dikirim</h4>
                </div>
                <button type="button" id="btnAddRow"
                    class="h-[28px] px-3 rounded-lg bg-white/15 hover:bg-white/25 text-white text-xs font-bold inline-flex items-center gap-1 transition">
                    <i data-lucide="plus" class="w-3.5 h-3.5"></i>
                    <span>Tambah Baris</span>
                </button>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse whitespace-nowrap" id="tableWorksheet">
                    <thead>
                        <tr class="bg-[#1e3a78]">
                            <th class="py-2 px-2.5 text-[11px] font-bold uppercase tracking-wider text-white/90 text-center w-10">No</th>
                            <th class="py-2 px-2.5 text-[11px] font-bold uppercase tracking-wider text-white/90 min-w-[230px]">Toko / Pelanggan</th>
                            @foreach ($produk as $p)
                                <th class="py-2 px-1.5 text-[11px] font-bold uppercase tracking-wider text-white/90 text-center min-w-[58px]"
                                    title="{{ $p->nama_produk }}">
                                    {{ $p->kode_produk }}
                                </th>
                            @endforeach
                            <th class="py-2 px-2 text-[11px] font-bold uppercase tracking-wider text-white/90 text-center w-10">Hapus</th>
                        </tr>
                    </thead>
                    <tbody id="worksheetBody" class="divide-y divide-slate-100 dark:divide-slate-800 text-xs">
                        @for ($i = 0; $i < 5; $i++)
                            <tr class="worksheet-row hover:bg-slate-50/70 dark:hover:bg-slate-800/30">
                                <td class="py-1.5 px-2.5 text-center font-mono-num font-bold text-slate-500 row-number">{{ $i + 1 }}</td>
                                <td class="py-1.5 px-2">
                                    <select name="rows[{{ $i }}][kode_pelanggan]" class="select2Pelanggan w-full"></select>
                                </td>
                                @foreach ($produk as $p)
                                    <td class="py-1.5 px-1">
                                        <input type="text" inputmode="decimal" name="rows[{{ $i }}][produk][{{ $p->kode_produk }}]"
                                            class="qty-input w-14 h-[30px] px-1.5 rounded-md border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-xs font-mono-num text-center text-slate-700 dark:text-slate-200 focus:outline-none focus:border-[#284c9a]"
                                            data-kode="{{ $p->kode_produk }}">
                                    </td>
                                @endforeach
                                <td class="py-1.5 px-2 text-center">
                                    <button type="button" class="btn-remove-row w-6 h-6 rounded-md bg-rose-50 dark:bg-rose-950/50 text-rose-600 hover:bg-rose-100 inline-flex items-center justify-center transition">
                                        <i data-lucide="x" class="w-3 h-3"></i>
                                    </button>
                                </td>
                            </tr>
                        @endfor
                    </tbody>
                    <tfoot>
                        <tr class="bg-slate-50 dark:bg-slate-800/60 border-t border-slate-200 dark:border-slate-700">
                            <td colspan="2" class="py-2 px-3 text-xs font-black text-slate-700 dark:text-slate-200 text-right uppercase">Total</td>
                            @foreach ($produk as $p)
                                <td class="py-2 px-1.5 text-center font-mono-num text-xs font-black text-[#284c9a] dark:text-blue-400 col-total"
                                    data-total-kode="{{ $p->kode_produk }}">0</td>
                            @endforeach
                            <td></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>

        <!-- SUBMIT BAR -->
        <div class="bg-white dark:bg-slate-900 rounded-xl border border-slate-200/80 dark:border-slate-800 shadow-2xs p-3 sm:p-3.5">
            <div class="flex items-center justify-end gap-2">
                <a href="{{ route('pengirimandriver.index') }}"
                    class="h-[36px] px-4 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-600 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-800 text-xs font-bold inline-flex items-center justify-center transition">
                    Batal
                </a>
                <button type="submit" id="btnSimpan"
                    class="h-[36px] px-5 rounded-lg bg-[#284c9a] hover:bg-[#1e3a78] text-white text-xs font-bold shadow-xs transition inline-flex items-center justify-center gap-1.5">
                    <i data-lucide="save" class="w-3.5 h-3.5"></i>
                    <span>Simpan Pengiriman</span>
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

        const $form = $("#formPengirimanDriver");
        let rowIndex = 5;
        const produkList = @json($produk->pluck('kode_produk'));

        if (typeof flatpickr !== 'undefined') {
            flatpickr(".flatpickr-date", {
                dateFormat: "Y-m-d",
                allowInput: false
            });
        }

        $('.select2Salesman').select2({ placeholder: 'Pilih Salesman', width: '100%' });
        $('.select2Driver').select2({ placeholder: 'Pilih Driver', width: '100%' });
        $('.select2Helper').select2({ placeholder: 'Pilih Helper (bisa lebih dari 1)', width: '100%' });

        function initPelangganSelect2($el) {
            $el.select2({
                placeholder: 'Cari kode / nama pelanggan...',
                allowClear: true,
                width: '220px',
                ajax: {
                    url: "{{ route('pengirimandriver.getpelanggan') }}",
                    dataType: 'json',
                    delay: 250,
                    data: function(params) {
                        return {
                            q: params.term,
                            kode_cabang: $('#kode_cabang').val(),
                            kode_salesman: $('#kode_salesman').val()
                        };
                    },
                    processResults: function(data) {
                        return { results: data.results };
                    },
                    cache: true
                }
            });
        }

        initPelangganSelect2($('.select2Pelanggan'));

        @if ($isPst)
        $('#kode_cabang').on('change', function() {
            const kodeCabang = $(this).val();
            const $salesman = $('#kode_salesman');
            const $driver = $('#kode_driver');
            const $helper = $('#kode_helper');

            $salesman.html('<option value="">Pilih Salesman</option>').trigger('change');
            $driver.html('<option value="">Pilih Driver</option>').trigger('change');
            $helper.html('').trigger('change');
            $('.select2Pelanggan').val(null).trigger('change');

            if (!kodeCabang) return;

            $.get("{{ route('pengirimandriver.getbycabang') }}", { kode_cabang: kodeCabang }, function(res) {
                res.salesman.forEach(function(s) {
                    $salesman.append(`<option value="${s.kode_salesman}">${s.nama_salesman}</option>`);
                });
                res.drivers.forEach(function(d) {
                    $driver.append(`<option value="${d.kode_driver_helper}">${d.nama_driver_helper}</option>`);
                });
                res.helpers.forEach(function(h) {
                    $helper.append(`<option value="${h.kode_driver_helper}">${h.nama_driver_helper}</option>`);
                });
                $salesman.trigger('change');
                $driver.trigger('change');
                $helper.trigger('change');
            });
        });
        @endif

        $('#kode_salesman').on('change', function() {
            $('.select2Pelanggan').val(null).trigger('change');
        });

        function formatIndoDecimalInput(val) {
            let clean = (val || '').toString().replace(/[^0-9,]/g, '');
            const commaIdx = clean.indexOf(',');
            let intPart = commaIdx !== -1 ? clean.slice(0, commaIdx) : clean;
            let decPart = commaIdx !== -1 ? clean.slice(commaIdx + 1).replace(/,/g, '').slice(0, 2) : null;

            intPart = intPart.replace(/^0+(?=\d)/, '');
            intPart = intPart.replace(/\B(?=(\d{3})+(?!\d))/g, '.');

            if (decPart !== null) {
                return (intPart === '' ? '0' : intPart) + ',' + decPart;
            }
            return intPart;
        }

        function parseIndoNumber(val) {
            const clean = (val || '').toString().replace(/\./g, '').replace(',', '.');
            const num = parseFloat(clean);
            return isNaN(num) ? 0 : num;
        }

        function formatIndoNumber(num) {
            if (!num) return '0';
            const rounded = Number.isInteger(num) ? num.toString() : parseFloat(num.toFixed(2)).toString();
            const parts = rounded.split('.');
            parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, '.');
            return parts.join(',');
        }

        function updateTotals() {
            produkList.forEach(function(kode) {
                let sum = 0;
                $(`.qty-input[data-kode="${kode}"]`).each(function() {
                    sum += parseIndoNumber($(this).val());
                });
                $(`.col-total[data-total-kode="${kode}"]`).text(formatIndoNumber(sum));
            });
        }

        $(document).on('input', '.qty-input', function() {
            const formatted = formatIndoDecimalInput($(this).val());
            $(this).val(formatted);
            updateTotals();
        });

        function renumberRows() {
            $('#worksheetBody .worksheet-row').each(function(idx) {
                $(this).find('.row-number').text(idx + 1);
            });
        }

        $('#btnAddRow').on('click', function() {
            let produkCols = '';
            produkList.forEach(function(kode) {
                produkCols += `<td class="py-1.5 px-1">
                    <input type="text" inputmode="decimal" name="rows[${rowIndex}][produk][${kode}]"
                        class="qty-input w-14 h-[30px] px-1.5 rounded-md border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-xs font-mono-num text-center text-slate-700 dark:text-slate-200 focus:outline-none focus:border-[#284c9a]"
                        data-kode="${kode}">
                </td>`;
            });

            const newRow = $(`<tr class="worksheet-row hover:bg-slate-50/70 dark:hover:bg-slate-800/30">
                <td class="py-1.5 px-2.5 text-center font-mono-num font-bold text-slate-500 row-number"></td>
                <td class="py-1.5 px-2">
                    <select name="rows[${rowIndex}][kode_pelanggan]" class="select2Pelanggan w-full"></select>
                </td>
                ${produkCols}
                <td class="py-1.5 px-2 text-center">
                    <button type="button" class="btn-remove-row w-6 h-6 rounded-md bg-rose-50 dark:bg-rose-950/50 text-rose-600 hover:bg-rose-100 inline-flex items-center justify-center transition">
                        <i data-lucide="x" class="w-3 h-3"></i>
                    </button>
                </td>
            </tr>`);

            $('#worksheetBody').append(newRow);
            initPelangganSelect2(newRow.find('.select2Pelanggan'));
            rowIndex++;
            renumberRows();
            if (typeof lucide !== 'undefined') lucide.createIcons();
        });

        $(document).on('click', '.btn-remove-row', function() {
            if ($('#worksheetBody .worksheet-row').length > 1) {
                $(this).closest('tr').remove();
                renumberRows();
                updateTotals();
            }
        });

        function setFieldError($el, msg) {
            const $wrap = $el.closest('.relative');
            $el.addClass('border-rose-500 ring-1 ring-rose-500 focus:border-rose-500 focus:ring-rose-500/20');
            if ($el.hasClass('select2-hidden-accessible')) {
                $el.next('.select2-container').find('.select2-selection').addClass('!border-rose-500 !ring-1 !ring-rose-500');
            }
            $wrap.find('> div:first-child').removeClass('text-slate-400').addClass('text-rose-500');
            $wrap.parent().find('.field-error-msg').remove();
            $wrap.after(`<p class="field-error-msg text-[11px] font-semibold text-rose-600 dark:text-rose-400 mt-1 pl-1 flex items-center gap-1"><svg class="w-3 h-3 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg><span>${msg}</span></p>`);
        }

        function clearFieldError($el) {
            const $wrap = $el.closest('.relative');
            $el.removeClass('border-rose-500 ring-1 ring-rose-500 focus:border-rose-500 focus:ring-rose-500/20');
            if ($el.hasClass('select2-hidden-accessible')) {
                $el.next('.select2-container').find('.select2-selection').removeClass('!border-rose-500 !ring-1 !ring-rose-500');
            }
            $wrap.find('> div:first-child').removeClass('text-rose-500').addClass('text-slate-400');
            $wrap.parent().find('.field-error-msg').remove();
        }

        $form.find('input, select').on('input change select2:select', function() {
            clearFieldError($(this));
        });

        $form.on('submit', function(e) {
            let firstError = null;
            const required = [
                @if ($isPst)
                { id: '#kode_cabang', msg: 'Cabang wajib dipilih.' },
                @endif
                { id: '#tanggal', msg: 'Tanggal wajib diisi.' },
                { id: '#kode_salesman', msg: 'Salesman wajib dipilih.' },
                { id: '#kode_driver', msg: 'Driver wajib dipilih.' }
            ];

            required.forEach(function(f) {
                const $el = $form.find(f.id);
                if (!($el.val() || '').trim()) {
                    setFieldError($el, f.msg);
                    if (!firstError) firstError = $el;
                }
            });

            if (firstError) {
                e.preventDefault();
                firstError.focus();
                return false;
            }

            const $btn = $("#btnSimpan");
            $btn.prop('disabled', true).addClass('opacity-75 cursor-not-allowed pointer-events-none');
            $btn.html(`<svg class="animate-spin h-3.5 w-3.5 text-white shrink-0 inline-block" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path></svg><span>Menyimpan...</span>`);
        });
    });
</script>
@endpush
