@extends('layouts.app')
@section('titlepage', 'Input Service Kendaraan')

@section('navigasi')
    <div class="flex items-center gap-1.5 text-xs">
        <span class="text-slate-400">Home</span>
        <i data-lucide="chevron-right" class="w-3 h-3 text-slate-400"></i>
        <span class="text-slate-400">General Affair</span>
        <i data-lucide="chevron-right" class="w-3 h-3 text-slate-400"></i>
        <a href="{{ route('servicekendaraan.index') }}" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-200">Service Kendaraan</a>
        <i data-lucide="chevron-right" class="w-3 h-3 text-slate-400"></i>
        <span class="text-brand-600 dark:text-brand-400 font-bold">Input Service</span>
    </div>
@endsection

@section('content')
<div class="space-y-3.5 w-full">

    <!-- PAGE TITLE BAR -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2.5">
        <div>
            <h3 class="text-base font-black text-slate-900 dark:text-white leading-tight">Input Service Kendaraan Baru</h3>
            <p class="text-[11px] text-slate-400 mt-0.5">Catat invoice service, bengkel rekanan, dan rincian suku cadang atau jasa perawatan.</p>
        </div>

        <div class="flex items-center gap-2">
            <a href="{{ route('servicekendaraan.index') }}"
                class="inline-flex items-center justify-center gap-1.5 h-[34px] px-3.5 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 hover:bg-slate-50 dark:hover:bg-slate-700/60 text-slate-700 dark:text-slate-200 text-xs font-bold transition shadow-2xs cursor-pointer">
                <i data-lucide="arrow-left" class="w-3.5 h-3.5"></i>
                <span>Kembali</span>
            </a>
        </div>
    </div>

    <form action="{{ route('servicekendaraan.store') }}" method="POST" id="formServicekendaraan">
        @csrf

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-3.5 items-start">
            
            <!-- LEFT PANEL: INFORMASI SERVICE (4 COLS) -->
            <div class="lg:col-span-4 space-y-3">
                <div class="rounded-xl border border-slate-200/80 dark:border-slate-800 bg-white dark:bg-slate-900 p-3 shadow-2xs space-y-2.5">
                    
                    <div class="flex items-center gap-2 pb-2 border-b border-slate-200/80 dark:border-slate-800">
                        <i data-lucide="wrench" class="w-4 h-4 text-[#284c9a] dark:text-blue-400"></i>
                        <h4 class="text-xs font-bold text-slate-800 dark:text-slate-100">Informasi Service</h4>
                    </div>

                    <!-- No. Invoice -->
                    <div>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                                <i data-lucide="receipt" class="w-3.5 h-3.5"></i>
                            </div>
                            <input type="text" name="no_invoice" id="no_invoice" placeholder="No. Invoice Service *"
                                class="w-full h-[36px] pl-8 pr-3 text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 placeholder:text-slate-400 focus:outline-hidden focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition uppercase">
                        </div>
                    </div>

                    <!-- Tanggal Service -->
                    <div>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                                <i data-lucide="calendar" class="w-3.5 h-3.5"></i>
                            </div>
                            <input type="text" name="tanggal" id="tanggal" placeholder="Tanggal Service *" readonly
                                class="flatpickr-date w-full h-[36px] pl-8 pr-3 text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 placeholder:text-slate-400 focus:outline-hidden focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition cursor-pointer">
                        </div>
                    </div>

                    <!-- Kendaraan -->
                    <div>
                        <div class="relative select2-container-v2">
                            <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-20">
                                <i data-lucide="truck" class="w-3.5 h-3.5"></i>
                            </div>
                            <select name="kode_kendaraan" id="kode_kendaraan" class="select2Kendaraan w-full">
                                <option value="">Pilih Kendaraan *</option>
                                @foreach ($kendaraan as $d)
                                    <option value="{{ $d->kode_kendaraan }}">{{ $d->no_polisi }} — {{ $d->merek }} {{ $d->tipe_kendaraan }} {{ $d->tipe }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- Bengkel + Tombol Tambah -->
                    <div class="flex items-start gap-2">
                        <div class="flex-1 min-w-0">
                            <div class="relative select2-container-v2">
                                <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-20">
                                    <i data-lucide="store" class="w-3.5 h-3.5"></i>
                                </div>
                                <select name="kode_bengkel" id="kode_bengkel" class="select2Kodebengkel w-full">
                                    <option value="">Pilih Bengkel *</option>
                                </select>
                            </div>
                        </div>
                        <button type="button" id="tambahbengkel" title="Tambah Bengkel Baru"
                            class="w-[36px] h-[36px] rounded-lg bg-[#284c9a] hover:bg-[#1e3a78] text-white flex items-center justify-center transition shadow-xs shrink-0 cursor-pointer">
                            <i data-lucide="plus" class="w-4 h-4"></i>
                        </button>
                    </div>

                    <!-- Keterangan -->
                    <div>
                        <div class="relative">
                            <div class="absolute top-2.5 left-0 pl-2.5 flex items-start pointer-events-none text-slate-400 z-10">
                                <i data-lucide="file-text" class="w-3.5 h-3.5"></i>
                            </div>
                            <textarea name="keterangan" id="keterangan" rows="3" placeholder="Keterangan service (opsional)"
                                class="w-full pl-8 pr-3 py-2 text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 placeholder:text-slate-400 focus:outline-hidden focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition resize-none"></textarea>
                        </div>
                    </div>

                </div>
            </div>

            <!-- RIGHT PANEL: DETAIL ITEM SERVICE & TOTAL (8 COLS) -->
            <div class="lg:col-span-8 space-y-3">
                
                <!-- CARD FORM TAMBAH ITEM -->
                <div class="rounded-xl border border-slate-200/80 dark:border-slate-800 bg-white dark:bg-slate-900 p-3 shadow-2xs space-y-2">
                    
                    <div class="flex items-center justify-between pb-2 border-b border-slate-200/80 dark:border-slate-800">
                        <div class="flex items-center gap-2">
                            <i data-lucide="plus-circle" class="w-4 h-4 text-[#284c9a] dark:text-blue-400"></i>
                            <h4 class="text-xs font-bold text-slate-800 dark:text-slate-100">Tambah Item Service</h4>
                        </div>
                        <div class="text-right">
                            <span class="text-[10px] text-slate-400 block font-semibold">Total Biaya Service</span>
                            <span id="grandtotal_text" class="text-base font-black font-mono-num text-[#284c9a] dark:text-blue-400 leading-tight">0</span>
                        </div>
                    </div>

                    <!-- Baris Form Tambah Item (No-Label) -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-12 gap-2 items-start">
                        
                        <!-- Pilih Item + Tambah Item Baru (Col-span 5) -->
                        <div class="lg:col-span-5 flex items-start gap-1.5">
                            <div class="flex-1 min-w-0">
                                <div class="relative select2-container-v2">
                                    <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-20">
                                        <i data-lucide="settings" class="w-3.5 h-3.5"></i>
                                    </div>
                                    <select name="kode_item" id="kode_item" class="select2KodeItem w-full">
                                        <option value="">Pilih Item Service</option>
                                    </select>
                                </div>
                            </div>
                            <button type="button" id="tambahitemservice" title="Tambah Master Item Baru"
                                class="w-[36px] h-[36px] rounded-lg bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-[#284c9a] dark:text-blue-400 border border-slate-200 dark:border-slate-700 flex items-center justify-center transition shadow-2xs shrink-0 cursor-pointer">
                                <i data-lucide="plus" class="w-4 h-4"></i>
                            </button>
                        </div>

                        <!-- Qty (Col-span 2) -->
                        <div class="lg:col-span-2">
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                                    <i data-lucide="layers" class="w-3.5 h-3.5"></i>
                                </div>
                                <input type="text" name="jumlah" id="jumlah" placeholder="Qty"
                                    class="number-separator text-right w-full h-[36px] pl-8 pr-3 text-xs font-semibold font-mono-num rounded-lg border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 placeholder:text-slate-400 focus:outline-hidden focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition">
                            </div>
                        </div>

                        <!-- Harga (Col-span 3) -->
                        <div class="lg:col-span-3">
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                                    <i data-lucide="tag" class="w-3.5 h-3.5"></i>
                                </div>
                                <input type="text" name="harga" id="harga" placeholder="Harga"
                                    class="number-separator text-right w-full h-[36px] pl-8 pr-3 text-xs font-semibold font-mono-num rounded-lg border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 placeholder:text-slate-400 focus:outline-hidden focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition">
                            </div>
                        </div>

                        <!-- Tombol Tambah Item (Col-span 2) -->
                        <div class="lg:col-span-2">
                            <button type="button" id="tambahitem"
                                class="w-full h-[36px] px-3 rounded-lg bg-[#284c9a] hover:bg-[#1e3a78] active:bg-[#152a57] text-white text-xs font-bold transition flex items-center justify-center gap-1.5 shadow-xs cursor-pointer">
                                <i data-lucide="plus" class="w-3.5 h-3.5"></i>
                                <span>Tambah</span>
                            </button>
                        </div>

                    </div>

                </div>

                <!-- TABEL DATA RINCIAN ITEM SERVICE -->
                <div id="error_rincian_item" class="hidden p-2.5 rounded-lg bg-rose-50 dark:bg-rose-950/40 border border-rose-200 dark:border-rose-800 text-rose-700 dark:text-rose-300 text-xs font-semibold flex items-center gap-2">
                    <i data-lucide="alert-circle" class="w-4 h-4 text-rose-600 shrink-0"></i>
                    <span>Daftar item service masih kosong! Tambahkan minimal 1 item service terlebih dahulu.</span>
                </div>

                <div id="cardTabelItem" class="rounded-xl border border-slate-200/80 dark:border-slate-800 bg-white dark:bg-slate-900 overflow-hidden shadow-2xs transition">
                    <div class="overflow-x-auto min-h-[160px]">
                        <table class="w-full text-xs text-left border-separate border-spacing-0 whitespace-nowrap">
                            <thead>
                                <tr class="bg-[#284c9a] text-white">
                                    <th colspan="5" class="px-3.5 py-2 bg-[#284c9a] text-white border-0">
                                        <div class="flex items-center gap-1.5">
                                            <i data-lucide="list-checks" class="w-3.5 h-3.5 text-blue-200"></i>
                                            <span class="text-xs font-bold text-white uppercase tracking-wider">Daftar Rincian Item Service</span>
                                        </div>
                                    </th>
                                </tr>
                                <tr class="bg-[#1e3a78] text-white text-[11px]">
                                    <th class="py-2 px-3 font-bold uppercase tracking-wider text-white/90 border-b border-blue-900 min-w-[200px]">ITEM SERVICE</th>
                                    <th class="py-2 px-3 text-center font-bold uppercase tracking-wider text-white/90 border-b border-blue-900 w-20">QTY</th>
                                    <th class="py-2 px-3 text-right font-bold uppercase tracking-wider text-white/90 border-b border-blue-900 w-32">HARGA</th>
                                    <th class="py-2 px-3 text-right font-bold uppercase tracking-wider text-white/90 border-b border-blue-900 w-36">TOTAL</th>
                                    <th class="py-2 px-3 text-center font-bold uppercase tracking-wider text-white/90 border-b border-blue-900 w-12">#</th>
                                </tr>
                            </thead>
                            <tbody id="loaditem" class="divide-y divide-slate-100 dark:divide-slate-800/80">
                                <!-- Dynamic rows populated via JS -->
                            </tbody>
                            <tfoot>
                                <tr class="bg-slate-50 dark:bg-slate-800/60 font-bold border-t border-slate-200 dark:border-slate-800">
                                    <td colspan="3" class="py-2.5 px-3 text-right uppercase text-slate-700 dark:text-slate-300 text-[11px]">
                                        Total Biaya Service:
                                    </td>
                                    <td id="grandtotal" class="py-2.5 px-3 text-right font-mono-num font-black text-slate-900 dark:text-slate-100 text-xs">
                                        0
                                    </td>
                                    <td></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>

                <!-- SUBMIT / SIMPAN SECTION -->
                <div class="rounded-xl border border-slate-200/80 dark:border-slate-800 bg-white dark:bg-slate-900 p-3 sm:p-3.5 shadow-2xs space-y-3">
                    <label class="flex items-center gap-2 cursor-pointer select-none">
                        <input type="checkbox" name="aggrement" value="aggrement" class="agreement w-4 h-4 rounded-sm border-slate-300 text-[#284c9a] focus:ring-brand-500">
                        <span class="text-xs font-semibold text-slate-700 dark:text-slate-300">Yakin data transaksi service kendaraan sudah benar dan siap disimpan?</span>
                    </label>

                    <div id="saveButton" class="hidden pt-2 border-t border-slate-100 dark:border-slate-800 flex items-center justify-end gap-2">
                        <button type="submit" id="btnSimpan"
                            class="px-6 h-[36px] rounded-lg bg-[#284c9a] hover:bg-[#1e3a78] active:bg-[#152a57] text-white text-xs font-bold transition flex items-center justify-center gap-2 shadow-xs cursor-pointer">
                            <i data-lucide="send" class="w-3.5 h-3.5"></i>
                            <span>Simpan Service Kendaraan</span>
                        </button>
                    </div>
                </div>

            </div>

        </div>
    </form>

</div>

<!-- MODAL TAMBAH BENGKEL (STATIC BACKDROP) -->
<div class="modal fade" id="modalbengkel" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false" aria-hidden="true">
    <div class="modal-dialog modal-md modal-dialog-centered">
        <div class="modal-content rounded-xl border border-slate-200/80 dark:border-slate-800 bg-white dark:bg-slate-900 shadow-2xl overflow-hidden transition-all duration-150">
            <div class="flex items-center justify-between px-4 py-3 bg-white dark:bg-slate-900 border-b border-slate-200/80 dark:border-slate-800">
                <div class="flex items-center gap-2">
                    <i data-lucide="store" class="w-4 h-4 text-[#284c9a] dark:text-blue-400"></i>
                    <h3 class="text-sm font-bold text-slate-800 dark:text-slate-100 modal-title">Input Bengkel</h3>
                </div>
                <button type="button" data-bs-dismiss="modal"
                    class="w-7 h-7 rounded-lg text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-800 flex items-center justify-center transition cursor-pointer">
                    <i data-lucide="x" class="w-4 h-4"></i>
                </button>
            </div>
            <div class="p-4 sm:p-5" id="loadmodalbengkel"></div>
        </div>
    </div>
</div>

<!-- MODAL TAMBAH ITEM SERVICE (STATIC BACKDROP) -->
<div class="modal fade" id="modalItem" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false" aria-hidden="true">
    <div class="modal-dialog modal-md modal-dialog-centered">
        <div class="modal-content rounded-xl border border-slate-200/80 dark:border-slate-800 bg-white dark:bg-slate-900 shadow-2xl overflow-hidden transition-all duration-150">
            <div class="flex items-center justify-between px-4 py-3 bg-white dark:bg-slate-900 border-b border-slate-200/80 dark:border-slate-800">
                <div class="flex items-center gap-2">
                    <i data-lucide="wrench" class="w-4 h-4 text-[#284c9a] dark:text-blue-400"></i>
                    <h3 class="text-sm font-bold text-slate-800 dark:text-slate-100 modal-title">Input Item Service</h3>
                </div>
                <button type="button" data-bs-dismiss="modal"
                    class="w-7 h-7 rounded-lg text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-800 flex items-center justify-center transition cursor-pointer">
                    <i data-lucide="x" class="w-4 h-4"></i>
                </button>
            </div>
            <div class="p-4 sm:p-5" id="loadmodalItem"></div>
        </div>
    </div>
</div>

@endsection

@push('myscript')
<script>
    $(document).ready(function() {
        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }

        const form = $('#formServicekendaraan');

        if (typeof flatpickr !== 'undefined') {
            form.find(".flatpickr-date").flatpickr({
                dateFormat: "Y-m-d",
                defaultDate: new Date()
            });
        }

        if (typeof easyNumberSeparator !== 'undefined') {
            easyNumberSeparator({
                selector: '.number-separator',
                separator: '.',
                decimalSeparator: ',',
            });
        }

        $('.select2Kendaraan').select2({ placeholder: 'Pilih Kendaraan *', allowClear: true, width: '100%' });
        $('.select2Kodebengkel').select2({ placeholder: 'Pilih Bengkel *', allowClear: true, width: '100%' });
        $('.select2KodeItem').select2({ placeholder: 'Pilih Item Service', allowClear: true, width: '100%' });

        function getBengkel() {
            $("#kode_bengkel").load('/bengkel/getbengkel', function() {
                $("#kode_bengkel").trigger('change');
            });
        }

        function getItem() {
            $("#kode_item").load('/itemservicekendaraan/getitem', function() {
                $("#kode_item").trigger('change');
            });
        }

        getBengkel();
        getItem();

        function convertNumber(number) {
            if (!number) return 0;
            let formatted = number.toString().replace(/\./g, '');
            formatted = formatted.replace(/,/g, '.');
            return parseFloat(formatted) || 0;
        }

        function numberFormat(number, decimals, dec_point, thousands_sep) {
            number = (number + '').replace(/[^0-9+\-Ee.]/g, '');
            var n = !isFinite(+number) ? 0 : +number,
                prec = !isFinite(+decimals) ? 0 : Math.abs(decimals),
                sep = typeof thousands_sep === 'undefined' ? ',' : thousands_sep,
                dec = typeof dec_point === 'undefined' ? '.' : dec_point,
                s = '',
                toFixedFix = function(n, prec) {
                    var k = Math.pow(10, prec);
                    return '' + Math.round(n * k) / k;
                };
            s = (prec ? toFixedFix(n, prec) : '' + Math.round(n)).split('.');
            if (s[0].length > 3) {
                s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
            }
            if ((s[1] || '').length < prec) {
                s[1] = s[1] || '';
                s[1] += new Array(prec - s[1].length + 1).join('0');
            }
            return s.join(dec);
        }

        function showInlineError($el, msg) {
            clearInlineError($el);
            $el.addClass("is-invalid border-rose-500 ring-1 ring-rose-500");
            const $rel = $el.closest(".relative");
            if ($rel.length) {
                $rel.find(".select2-selection").addClass("is-invalid !border-rose-500 !ring-1 !ring-rose-500");
                $rel.find(".select2-container").addClass("is-invalid");
                $rel.find("i, svg").addClass("text-rose-500");
                $rel.siblings(".field-error-msg").remove();
                $rel.parent().find("> .field-error-msg").remove();
                $rel.after(`
                    <p class="field-error-msg text-[11px] font-semibold text-rose-600 dark:text-rose-400 mt-1 pl-1 flex items-center gap-1">
                        <i data-lucide="alert-circle" class="w-3.5 h-3.5 shrink-0"></i>
                        <span>${msg}</span>
                    </p>
                `);
            }
            if (typeof lucide !== 'undefined') lucide.createIcons();
            $el.focus();
        }

        function clearInlineError($el) {
            $el.removeClass("is-invalid border-rose-500 ring-1 ring-rose-500");
            const $rel = $el.closest(".relative");
            if ($rel.length) {
                $rel.find(".select2-selection").removeClass("is-invalid !border-rose-500 !ring-1 !ring-rose-500");
                $rel.find(".select2-container").removeClass("is-invalid");
                $rel.find("i, svg").removeClass("text-rose-500");
                $rel.siblings(".field-error-msg").remove();
                $rel.parent().find("> .field-error-msg").remove();
            }
        }

        // Static modal bounce
        $('#modalbengkel, #modalItem').on('click', function(e) {
            if ($(e.target).is(this)) {
                const $dialog = $(this).find('.modal-content');
                $dialog.addClass('scale-[1.015]');
                setTimeout(() => $dialog.removeClass('scale-[1.015]'), 150);
            }
        });

        $("#tambahbengkel").click(function() {
            $("#modalbengkel").modal("show");
            $("#loadmodalbengkel").load('/bengkel/create', function() {
                if (typeof lucide !== 'undefined') lucide.createIcons();
            });
        });

        $(document).on('submit', '#formBengkel', function(e) {
            e.preventDefault();
            const $f = $(this);
            const nama_bengkel = $f.find("#nama_bengkel").val().trim();
            if (!nama_bengkel) {
                showInlineError($f.find("#nama_bengkel"), "Nama bengkel wajib diisi!");
                return;
            }
            const $btn = $f.find("#btnSimpanBengkel");
            $btn.prop('disabled', true).addClass('opacity-75 cursor-not-allowed');
            $.ajax({
                type: "POST",
                url: "/bengkel/store",
                data: { _token: "{{ csrf_token() }}", nama_bengkel: nama_bengkel },
                cache: false,
                success: function(response) {
                    $btn.prop('disabled', false).removeClass('opacity-75 cursor-not-allowed');
                    if (response.success) {
                        if (typeof window.showToast === 'function') window.showToast('success', response.message);
                        getBengkel();
                        $("#modalbengkel").modal("hide");
                    } else {
                        showInlineError($f.find("#nama_bengkel"), response.message);
                    }
                },
                error: function(response) {
                    $btn.prop('disabled', false).removeClass('opacity-75 cursor-not-allowed');
                    const msg = response.responseJSON?.message || 'Terjadi kesalahan saat menyimpan bengkel.';
                    showInlineError($f.find("#nama_bengkel"), msg);
                }
            });
        });

        $("#tambahitemservice").click(function() {
            $("#modalItem").modal("show");
            $("#loadmodalItem").load('/itemservicekendaraan/create', function() {
                if (typeof lucide !== 'undefined') lucide.createIcons();
            });
        });

        $(document).on('submit', '#formItemservicekendaraan', function(e) {
            e.preventDefault();
            const $f = $(this);
            const nama_item = $f.find("#nama_item").val().trim();
            const jenis_item = $f.find("#jenis_item").val();
            let hasErr = false;

            if (!nama_item) {
                showInlineError($f.find("#nama_item"), "Nama item wajib diisi!");
                hasErr = true;
            }
            if (!jenis_item) {
                showInlineError($f.find("#jenis_item"), "Jenis item wajib dipilih!");
                hasErr = true;
            }
            if (hasErr) return;

            const $btn = $f.find("#btnSimpanItem");
            $btn.prop('disabled', true).addClass('opacity-75 cursor-not-allowed');
            $.ajax({
                type: "POST",
                url: "/itemservicekendaraan/store",
                data: { _token: "{{ csrf_token() }}", nama_item: nama_item, jenis_item: jenis_item },
                cache: false,
                success: function(response) {
                    $btn.prop('disabled', false).removeClass('opacity-75 cursor-not-allowed');
                    if (response.success) {
                        if (typeof window.showToast === 'function') window.showToast('success', response.message);
                        getItem();
                        $("#modalItem").modal("hide");
                    } else {
                        showInlineError($f.find("#nama_item"), response.message);
                    }
                },
                error: function(response) {
                    $btn.prop('disabled', false).removeClass('opacity-75 cursor-not-allowed');
                    const msg = response.responseJSON?.message || 'Terjadi kesalahan saat menyimpan item.';
                    showInlineError($f.find("#nama_item"), msg);
                }
            });
        });

        function calculateTotal() {
            let grandTotal = 0;
            $('.totalharga').each(function() {
                grandTotal += parseFloat(convertNumber($(this).text())) || 0;
            });
            $('#grandtotal').text(numberFormat(grandTotal, '0', ',', '.'));
            $('#grandtotal_text').text(numberFormat(grandTotal, '0', ',', '.'));
        }

        function resetForm() {
            form.find('.select2KodeItem').val('').trigger("change");
            form.find("#jumlah").val("");
            form.find("#harga").val("");
        }

        $("#tambahitem").click(function() {
            const kode_item = form.find("#kode_item").val();
            const nama_item = form.find("#kode_item option:selected").text();
            const jumlah = form.find("#jumlah").val();
            const harga = form.find("#harga").val();

            if (!kode_item) {
                showInlineError(form.find("#kode_item"), "Item service wajib dipilih!");
                return;
            }
            if (!jumlah || convertNumber(jumlah) === 0) {
                showInlineError(form.find("#jumlah"), "Qty wajib diisi!");
                return;
            }
            if (!harga || convertNumber(harga) === 0) {
                showInlineError(form.find("#harga"), "Harga wajib diisi!");
                return;
            }
            if ($("#loaditem").find(`#${kode_item}`).length > 0) {
                showInlineError(form.find("#kode_item"), "Item ini sudah ada di daftar!");
                return;
            }

            const total = convertNumber(harga) * convertNumber(jumlah);
            const totalFormatted = numberFormat(total, '0', ',', '.');

            let newItem = `<tr id="${kode_item}" class="hover:bg-slate-50/80 dark:hover:bg-slate-800/50 transition-colors">
                <td class="py-1.5 px-3 font-semibold text-slate-900 dark:text-slate-100">
                    <input type="hidden" name="kode_item_service[]" value="${kode_item}"/>
                    <input type="hidden" name="harga_item_service[]" value="${harga}"/>
                    <input type="hidden" name="jumlah_item_service[]" value="${jumlah}"/>
                    <span class="font-mono-num text-xs font-bold text-[#284c9a] dark:text-blue-400 mr-1">${kode_item}</span>
                    ${nama_item}
                </td>
                <td class="py-1.5 px-3 text-center font-mono-num font-bold text-slate-800 dark:text-slate-200">${jumlah}</td>
                <td class="py-1.5 px-3 text-right font-mono-num text-slate-700 dark:text-slate-300">${harga}</td>
                <td class="py-1.5 px-3 text-right font-mono-num font-bold text-slate-900 dark:text-slate-100 totalharga">${totalFormatted}</td>
                <td class="py-1.5 px-3 text-center">
                    <button type="button" kode_item="${kode_item}" class="delete w-6 h-6 rounded-md bg-rose-50 text-rose-600 hover:bg-rose-100 dark:bg-rose-950/50 dark:text-rose-400 inline-flex items-center justify-center transition shadow-2xs cursor-pointer" title="Hapus Item">
                        <i data-lucide="trash-2" class="w-3 h-3"></i>
                    </button>
                </td>
            </tr>`;

            form.find("#loaditem").append(newItem);
            $("#cardTabelItem").removeClass("border-rose-500 ring-1 ring-rose-500");
            $("#error_rincian_item").addClass("hidden");
            if (typeof lucide !== 'undefined') lucide.createIcons();
            calculateTotal();
            resetForm();
        });

        $(document).on('click', '.delete', function() {
            $(this).closest('tr').remove();
            calculateTotal();
        });

        form.find('.agreement').change(function() {
            if (this.checked) {
                form.find("#saveButton").removeClass('hidden');
            } else {
                form.find("#saveButton").addClass('hidden');
            }
        });

        function buttonDisabled() {
            const $btn = $("#btnSimpan");
            $btn.prop('disabled', true).addClass('opacity-75 cursor-not-allowed pointer-events-none');
            $btn.html(`
                <svg class="animate-spin h-3.5 w-3.5 text-white shrink-0 inline-block" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                </svg>
                <span>Menyimpan Service...</span>
            `);
        }

        form.submit(function(e) {
            const no_invoice = form.find("#no_invoice").val().trim();
            const tanggal = form.find("#tanggal").val().trim();
            const kode_kendaraan = form.find("#kode_kendaraan").val();
            const kode_bengkel = form.find("#kode_bengkel").val();

            let hasError = false;

            if (!no_invoice) {
                showInlineError(form.find("#no_invoice"), "No. Invoice wajib diisi!");
                hasError = true;
            }
            if (!tanggal) {
                showInlineError(form.find("#tanggal"), "Tanggal service wajib diisi!");
                hasError = true;
            }
            if (!kode_kendaraan) {
                showInlineError(form.find("#kode_kendaraan"), "Kendaraan wajib dipilih!");
                hasError = true;
            }
            if (!kode_bengkel) {
                showInlineError(form.find("#kode_bengkel"), "Bengkel wajib dipilih!");
                hasError = true;
            }
            if ($('#loaditem tr').length === 0) {
                $("#cardTabelItem").addClass("border-rose-500 ring-1 ring-rose-500");
                $("#error_rincian_item").removeClass("hidden");
                if (typeof lucide !== 'undefined') lucide.createIcons();
                hasError = true;
            }

            if (hasError) {
                e.preventDefault();
                return false;
            }

            buttonDisabled();
        });

        $(document).on("input change", "input, select, textarea", function() {
            clearInlineError($(this));
        });

        $(document).on("select2:select select2:clear", function(e) {
            clearInlineError($(e.target));
        });
    });
</script>
@endpush
