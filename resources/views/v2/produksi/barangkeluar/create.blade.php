@extends('layouts.app')
@section('titlepage', 'Input Barang Keluar Produksi')

@section('navigasi')
    <div class="flex items-center gap-1.5 text-xs">
        <span class="text-slate-400">Home</span>
        <i data-lucide="chevron-right" class="w-3 h-3 text-slate-400"></i>
        <span class="text-slate-400">Produksi</span>
        <i data-lucide="chevron-right" class="w-3 h-3 text-slate-400"></i>
        <a href="{{ route('barangkeluarproduksi.index') }}" class="text-slate-400 hover:text-brand-600 transition">Barang Keluar</a>
        <i data-lucide="chevron-right" class="w-3 h-3 text-slate-400"></i>
        <span class="text-brand-600 dark:text-brand-400 font-bold">Input Barang Keluar</span>
    </div>
@endsection

@section('content')
<div class="space-y-3.5 w-full">

    <!-- PAGE TITLE BAR -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2.5">
        <div>
            <h3 class="text-base font-black text-slate-900 dark:text-white leading-tight">Input Barang Keluar Produksi</h3>
            <p class="text-[11px] text-slate-400 mt-0.5">Pencatatan pemakaian dan pengeluaran barang/bahan baku dari unit produksi.</p>
        </div>

        <a href="{{ route('barangkeluarproduksi.index') }}"
            class="inline-flex items-center justify-center gap-1.5 h-[34px] px-3.5 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 hover:bg-slate-50 dark:hover:bg-slate-700/60 text-slate-700 dark:text-slate-200 text-xs font-bold transition cursor-pointer shrink-0">
            <i data-lucide="arrow-left" class="w-3.5 h-3.5"></i>
            <span>Kembali</span>
        </a>
    </div>

    <form action="{{ route('barangkeluarproduksi.store') }}" id="formcreateBarangkeluarproduksi" method="POST">
        @csrf
        <input type="hidden" id="cektutuplaporan">
        <input type="hidden" id="cekdetailtemp">

        <div class="space-y-3.5">
            <!-- HEADER INFO CARD (Full Width Horizontal) -->
            <div class="rounded-xl bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 p-4 shadow-2xs space-y-3">
                <div class="flex items-center gap-2 pb-2.5 border-b border-slate-100 dark:border-slate-800 text-xs font-bold text-slate-800 dark:text-slate-100">
                    <i data-lucide="file-text" class="w-4 h-4 text-brand-600 dark:text-brand-400"></i>
                    <span>Informasi Bukti Pengeluaran</span>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
                    <!-- No. Bukti -->
                    <div class="field-group">
                        <label for="no_bukti" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                            No. Bukti
                        </label>
                        <div class="relative">
                            <div class="input-prefix-icon absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400 z-10">
                                <i data-lucide="barcode" class="w-4 h-4"></i>
                            </div>
                            <input type="text" name="no_bukti" id="no_bukti"
                                class="w-full h-[36px] pl-9 pr-3 text-xs font-bold font-mono-num rounded-lg border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 text-slate-500"
                                placeholder="[Auto Generated]" readonly>
                        </div>
                    </div>

                    <!-- Tanggal -->
                    <div class="field-group">
                        <label for="tanggal" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                            Tanggal <span class="text-rose-500">*</span>
                        </label>
                        <div class="relative">
                            <div class="input-prefix-icon absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400 z-10">
                                <i data-lucide="calendar" class="w-4 h-4"></i>
                            </div>
                            <input type="text" name="tanggal" id="tanggal"
                                class="flatpickr-date w-full h-[36px] pl-9 pr-3 text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition cursor-pointer"
                                placeholder="Pilih Tanggal" readonly>
                        </div>
                    </div>

                    <!-- Jenis Pengeluaran -->
                    <div class="field-group">
                        <label for="kode_jenis_pengeluaran" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                            Jenis Pengeluaran <span class="text-rose-500">*</span>
                        </label>
                        <div class="relative">
                            <div class="input-prefix-icon absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400 z-10">
                                <i data-lucide="tag" class="w-4 h-4"></i>
                            </div>
                            <select name="kode_jenis_pengeluaran" id="kode_jenis_pengeluaran"
                                class="w-full h-[36px] pl-9 pr-7 text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition appearance-none cursor-pointer">
                                <option value="">Pilih Jenis Pengeluaran</option>
                                <option value="RO">Retur Out (RO)</option>
                                <option value="PK">Pemakaian (PK)</option>
                                <option value="LN">Lainnya (LN)</option>
                            </select>
                            <div class="absolute inset-y-0 right-0 pr-2.5 flex items-center pointer-events-none text-slate-400">
                                <i data-lucide="chevron-down" class="w-4 h-4"></i>
                            </div>
                        </div>
                    </div>

                    <!-- Supplier (Conditional on RO) -->
                    <div id="supplier" class="field-group">
                        <label for="kode_supplier" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                            Supplier <span class="text-rose-500">*</span>
                        </label>
                        <div class="relative">
                            <select name="kode_supplier" id="kode_supplier" class="select2Kodesupplier w-full">
                                <option value="">Pilih Supplier</option>
                                @foreach ($supplier as $d)
                                    <option value="{{ $d->kode_supplier }}">{{ $d->nama_supplier }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <!-- DETAIL ITEMS INPUT & TABLE (Full Width) -->
            <div class="rounded-xl bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 p-4 shadow-2xs space-y-3">
                    
                    <!-- INPUT ROW -->
                    <div class="p-3 rounded-lg border border-slate-200/80 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-900/40 space-y-2.5">
                        <div class="flex items-center gap-1.5 text-xs font-bold text-slate-700 dark:text-slate-200">
                            <i data-lucide="package-plus" class="w-3.5 h-3.5 text-brand-600 dark:text-brand-400"></i>
                            <span>Tambahkan Barang</span>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-12 gap-2">
                            <!-- Barang -->
                            <div class="sm:col-span-4 field-group">
                                <label for="kode_barang_produksi" class="block text-[11px] font-bold text-slate-600 dark:text-slate-300 mb-1">
                                    Barang Produksi <span class="text-rose-500">*</span>
                                </label>
                                <div class="relative">
                                    <select name="kode_barang_produksi" id="kode_barang_produksi" class="select2Kodebarangproduksi w-full">
                                        <option value="">Pilih Barang</option>
                                        @foreach ($barangproduksi as $d)
                                            <option value="{{ $d->kode_barang_produksi }}">{{ $d->kode_barang_produksi }} - {{ $d->nama_barang }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <!-- Keterangan -->
                            <div class="sm:col-span-3 field-group">
                                <label for="keterangan" class="block text-[11px] font-bold text-slate-600 dark:text-slate-300 mb-1">
                                    Keterangan
                                </label>
                                <div class="relative">
                                    <div class="input-prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                                        <i data-lucide="file-text" class="w-3.5 h-3.5"></i>
                                    </div>
                                    <input type="text" name="keterangan" id="keterangan"
                                        class="w-full h-[36px] pl-8 pr-2.5 text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition"
                                        placeholder="Keterangan item (opsional)">
                                </div>
                            </div>

                            <!-- Jumlah -->
                            <div class="sm:col-span-2 field-group">
                                <label for="jumlah" class="block text-[11px] font-bold text-slate-600 dark:text-slate-300 mb-1">
                                    Jumlah <span class="text-rose-500">*</span>
                                </label>
                                <div class="relative">
                                    <div class="input-prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                                        <i data-lucide="layers" class="w-3.5 h-3.5"></i>
                                    </div>
                                    <input type="text" name="jumlah" id="jumlah"
                                        class="number-separator w-full h-[36px] pl-8 pr-2 text-xs font-bold font-mono-num text-right rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition"
                                        placeholder="0">
                                </div>
                            </div>

                            <!-- Berat -->
                            <div class="sm:col-span-2 field-group">
                                <label for="jumlah_berat" class="block text-[11px] font-bold text-slate-600 dark:text-slate-300 mb-1">
                                    Berat (Kg)
                                </label>
                                <div class="relative">
                                    <div class="input-prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                                        <i data-lucide="scale" class="w-3.5 h-3.5"></i>
                                    </div>
                                    <input type="text" name="jumlah_berat" id="jumlah_berat"
                                        class="number-separator w-full h-[36px] pl-8 pr-2 text-xs font-bold font-mono-num text-right rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition"
                                        placeholder="0">
                                </div>
                            </div>

                            <!-- Tombol Tambah -->
                            <div class="sm:col-span-1 self-start">
                                <label class="block text-[11px] font-bold invisible mb-1 select-none pointer-events-none">Aksi</label>
                                <button type="button" id="tambahbarang"
                                    class="w-full h-[36px] rounded-lg bg-brand-600 hover:bg-brand-700 active:bg-brand-800 text-white flex items-center justify-center transition shadow-xs cursor-pointer"
                                    title="Tambah ke Rincian">
                                    <i data-lucide="plus" class="w-4 h-4"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- TABLE OF ITEMS -->
                    <div class="overflow-hidden rounded-xl border border-slate-200/80 dark:border-slate-800 shadow-2xs">
                        <div class="overflow-x-auto scrollbar-thin">
                            <table class="w-full text-xs text-left border-collapse font-sans whitespace-nowrap">
                                <thead>
                                    <tr class="bg-[#284c9a] text-white">
                                        <th colspan="8" class="px-4 py-3 bg-[#284c9a] text-white border-0">
                                            <div class="flex items-center gap-2">
                                                <i data-lucide="list" class="w-4 h-4 text-blue-200"></i>
                                                <span class="text-xs font-bold uppercase tracking-wider text-white">Rincian Barang Keluar</span>
                                            </div>
                                        </th>
                                    </tr>
                                    <tr class="bg-[#1e3a78] text-white text-[11px] border-none">
                                        <th class="py-2.5 px-3 text-center w-12 font-bold uppercase tracking-wider text-white/90 border-none">No</th>
                                        <th class="py-2.5 px-4 font-bold uppercase tracking-wider text-white/90 border-none w-36">Kode Barang</th>
                                        <th class="py-2.5 px-4 font-bold uppercase tracking-wider text-white/90 border-none">Nama Barang</th>
                                        <th class="py-2.5 px-4 text-center font-bold uppercase tracking-wider text-white/90 border-none w-20">Satuan</th>
                                        <th class="py-2.5 px-4 font-bold uppercase tracking-wider text-white/90 border-none">Keterangan</th>
                                        <th class="py-2.5 px-4 text-right font-bold uppercase tracking-wider text-white/90 border-none w-28">Jumlah</th>
                                        <th class="py-2.5 px-4 text-right font-bold uppercase tracking-wider text-white/90 border-none w-28">Berat (Kg)</th>
                                        <th class="py-2.5 px-3 text-center font-bold uppercase tracking-wider text-white/90 border-none w-20">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody id="loaddetailtemp" class="divide-y divide-slate-100 dark:divide-slate-800/60 bg-white dark:bg-slate-900">
                                    <tr>
                                        <td colspan="8" class="py-6 text-center text-slate-400">
                                            <span class="text-xs font-semibold">Pilih barang dan tambahkan ke rincian di atas</span>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- AGREEMENT & SUBMIT BUTTON -->
                    <div class="pt-2 border-t border-slate-100 dark:border-slate-800 space-y-2.5">
                        <label class="flex items-center gap-2 cursor-pointer select-none">
                            <input type="checkbox" name="aggrement" id="defaultCheck3" class="agreement w-4 h-4 rounded text-brand-600 border-slate-300 dark:border-slate-700 focus:ring-brand-500/20">
                            <span class="text-xs font-bold text-slate-700 dark:text-slate-300">Yakin data barang keluar sudah sesuai dan akan disimpan?</span>
                        </label>

                        <div id="saveButton" class="hidden flex items-center justify-end gap-2 pt-1">
                            <a href="{{ route('barangkeluarproduksi.index') }}"
                                class="h-[34px] px-4 inline-flex items-center justify-center rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 hover:bg-slate-50 dark:hover:bg-slate-700/60 text-slate-700 dark:text-slate-200 text-xs font-bold transition cursor-pointer">
                                Batal
                            </a>
                            <button type="submit" id="btnSimpan"
                                class="inline-flex items-center justify-center gap-1.5 h-[34px] px-5 rounded-lg bg-brand-600 hover:bg-brand-700 active:bg-brand-800 text-white text-xs font-bold transition shadow-xs cursor-pointer">
                                <i data-lucide="send" class="w-3.5 h-3.5"></i>
                                <span>Simpan Barang Keluar</span>
                            </button>
                        </div>
                    </div>

                </div>
            </div>
    </form>
</div>

@endsection

@push('myscript')
<script>
    $(function() {
        const $form = $("#formcreateBarangkeluarproduksi");
        const $btnSimpan = $form.find("#btnSimpan");

        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }

        // Standard Error State Helpers
        function setFieldError($input, message) {
            const $container = $input.closest('.field-group');
            $container.addClass('has-error');
            $input.addClass('border-rose-500 ring-1 ring-rose-500 focus:border-rose-500 focus:ring-rose-500/20 is-invalid')
                  .removeClass('border-slate-200 dark:border-slate-700');
            $container.find('.input-prefix-icon').addClass('text-rose-500').removeClass('text-slate-400');

            if ($input.hasClass('select2-hidden-accessible') || $input.next().hasClass('select2')) {
                $input.addClass('is-invalid');
                const $s2 = $input.next('.select2');
                $s2.addClass('is-invalid');
                $s2.find('.select2-selection').addClass('is-invalid border-rose-500 !border-rose-500');
                $container.find('.select2-selection').addClass('is-invalid border-rose-500 !border-rose-500');
            }

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

            if ($input.hasClass('select2-hidden-accessible') || $input.next().hasClass('select2')) {
                $input.removeClass('is-invalid');
                const $s2 = $input.next('.select2');
                $s2.removeClass('is-invalid');
                $s2.find('.select2-selection').removeClass('is-invalid border-rose-500 !border-rose-500');
                $container.find('.select2-selection').removeClass('is-invalid border-rose-500 !border-rose-500');
            }

            $container.find('.field-error-msg').remove();
        }

        // Live Cleanup on change / input
        $form.find('input, select').on('input change select2:select', function() {
            clearFieldError($(this));
        });

        $form.find("#tanggal").flatpickr({
            dateFormat: "Y-m-d",
            onChange: function() {
                clearFieldError($form.find("#tanggal"));
            }
        });

        if (typeof easyNumberSeparator !== 'undefined') {
            easyNumberSeparator({
                selector: '.number-separator',
                separator: '.',
                decimalSeparator: ','
            });
        }

        $form.find('.select2Kodebarangproduksi').select2({
            placeholder: 'Pilih Barang',
            width: '100%'
        }).on('change select2:select', function() {
            clearFieldError($(this));
        });

        $form.find('.select2Kodesupplier').select2({
            placeholder: 'Pilih Supplier',
            width: '100%'
        }).on('change select2:select', function() {
            clearFieldError($(this));
        });

        function showSupplier() {
            var kode_jenis_pengeluaran = $form.find("#kode_jenis_pengeluaran").val();
            if (kode_jenis_pengeluaran === "RO") {
                $form.find("#supplier").removeClass('hidden');
            } else {
                $form.find("#supplier").addClass('hidden');
                clearFieldError($form.find("#kode_supplier"));
            }
        }

        showSupplier();
        $form.find("#kode_jenis_pengeluaran").on("change", showSupplier);

        function cekdetailtemp() {
            $.ajax({
                type: 'POST',
                url: '/barangkeluarproduksi/cekdetailtemp',
                data: {
                    _token: "{{ csrf_token() }}"
                },
                cache: false,
                success: function(respond) {
                    $form.find("#cekdetailtemp").val(respond);
                }
            });
        }

        function cektutuplaporan(tanggal, jenis_laporan) {
            $.ajax({
                type: "POST",
                url: "/tutuplaporan/cektutuplaporan",
                data: {
                    _token: "{{ csrf_token() }}",
                    tanggal: tanggal,
                    jenis_laporan: jenis_laporan
                },
                cache: false,
                success: function(respond) {
                    $form.find("#cektutuplaporan").val(respond);
                }
            });
        }

        function loaddetailtemp() {
            $form.find("#loaddetailtemp").load("/barangkeluarproduksi/getdetailtemp", function() {
                if (typeof lucide !== 'undefined') lucide.createIcons();
            });
            cekdetailtemp();
        }

        loaddetailtemp();

        $form.find("#tanggal").on("change", function() {
            cektutuplaporan($(this).val(), "produksi");
        });

        // Tambah Barang ke Rincian
        $form.find("#tambahbarang").on("click", function(e) {
            e.preventDefault();
            const $kdBarang = $form.find("#kode_barang_produksi");
            const $ket = $form.find("#keterangan");
            const $jml = $form.find("#jumlah");
            const $berat = $form.find("#jumlah_berat");

            let hasAddError = false;
            let $firstAddError = null;

            if (!$kdBarang.val()) {
                setFieldError($kdBarang, "Pilih barang terlebih dahulu");
                hasAddError = true;
                $firstAddError = $kdBarang;
            }
            if (!$jml.val() || $jml.val() === "0") {
                setFieldError($jml, "Jumlah wajib diisi angka > 0");
                if (!hasAddError) {
                    hasAddError = true;
                    $firstAddError = $jml;
                }
            }

            if (hasAddError) {
                if ($firstAddError.hasClass('select2-hidden-accessible')) {
                    $firstAddError.select2('open');
                } else {
                    $firstAddError.focus();
                }
                return;
            }

            const $btnTambah = $(this);
            $btnTambah.prop('disabled', true);
            $.ajax({
                type: "POST",
                url: "{{ route('barangkeluarproduksi.storedetailtemp') }}",
                data: {
                    _token: "{{ csrf_token() }}",
                    kode_barang_produksi: $kdBarang.val(),
                    keterangan: $ket.val(),
                    jumlah: $jml.val(),
                    jumlah_berat: $berat.val()
                },
                cache: false,
                success: function(respond) {
                    $jml.val('');
                    $berat.val('');
                    $ket.val('');
                    $kdBarang.val('').trigger('change');
                    loaddetailtemp();
                    $btnTambah.prop('disabled', false);
                },
                error: function(xhr) {
                    $btnTambah.prop('disabled', false);
                    Swal.fire("Error", xhr.responseJSON?.message || "Gagal menambahkan barang", "error");
                }
            });
        });

        $form.find('.agreement').on("change", function() {
            if (this.checked) {
                $form.find("#saveButton").removeClass("hidden");
            } else {
                $form.find("#saveButton").addClass("hidden");
            }
        });

        // Form Submit Validation
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

            const $tgl = $form.find("#tanggal");
            const $jenis = $form.find("#kode_jenis_pengeluaran");
            const $supp = $form.find("#kode_supplier");
            const cektutuplaporan = $form.find("#cektutuplaporan").val();
            const cekdetailtemp = $form.find("#cekdetailtemp").val();

            validateField($tgl, !$tgl.val(), "Tanggal bukti keluar wajib dipilih");
            validateField($jenis, !$jenis.val(), "Jenis pengeluaran wajib dipilih");

            if ($jenis.val() === "RO") {
                validateField($supp, !$supp.val(), "Pilih supplier untuk jenis Retur Out");
            }

            if (hasError) {
                e.preventDefault();
                if ($firstErrorField) {
                    if ($firstErrorField.hasClass('select2-hidden-accessible')) {
                        $firstErrorField.select2('open');
                    } else {
                        $firstErrorField.focus();
                    }
                }
                return false;
            }

            if (cektutuplaporan === '1') {
                e.preventDefault();
                Swal.fire("Oops!", "Laporan untuk periode tanggal ini sudah ditutup!", "warning");
                return false;
            }

            if (cekdetailtemp === '0' || cekdetailtemp === '') {
                e.preventDefault();
                Swal.fire("Oops!", "Data barang masih kosong! Tambahkan minimal satu barang.", "warning");
                return false;
            }

            // Prevent Double Submit
            $btnSimpan.prop('disabled', true).addClass('opacity-75 cursor-not-allowed pointer-events-none');
            $btnSimpan.html(`
                <svg class="animate-spin h-3.5 w-3.5 text-white shrink-0 inline-block" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <span>Menyimpan Barang Keluar...</span>
            `);
            return true;
        });

        $('body').on('click', '.delete', function(e) {
            e.preventDefault();
            var id = $(this).attr('id');
            Swal.fire({
                title: 'Hapus Barang Ini?',
                text: "Item yang dihapus tidak dapat dipulihkan.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#284c9a',
                cancelButtonColor: '#ef4444',
                confirmButtonText: 'Ya, Hapus!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        type: 'POST',
                        url: '/barangkeluarproduksi/deletetemp',
                        data: {
                            _token: "{{ csrf_token() }}",
                            id: id,
                        },
                        cache: false,
                        success: function(respond) {
                            loaddetailtemp();
                        },
                        error: function(xhr) {
                            Swal.fire("Error", xhr.responseJSON?.message || "Gagal menghapus item", "error");
                        }
                    });
                }
            });
        });
    });
</script>
@endpush
