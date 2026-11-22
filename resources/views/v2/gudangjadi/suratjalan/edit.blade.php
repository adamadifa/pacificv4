<form action="{{ route('suratjalan.update', Crypt::encrypt($surat_jalan->no_mutasi)) }}" method="POST" id="formeditSuratjalan" autocomplete="off" class="space-y-3">
    @csrf
    @method('PUT')
    <input type="hidden" id="cektutuplaporan">

    <!-- RINGKASAN DATA SURAT JALAN & PERMINTAAN -->
    <div class="rounded-xl border border-slate-200/80 dark:border-slate-800 bg-slate-50/70 dark:bg-slate-800/40 p-3">
        <div class="flex items-center gap-2 mb-2 text-xs font-bold uppercase tracking-wider text-[#284c9a] dark:text-blue-400">
            <i data-lucide="file-check" class="w-4 h-4"></i>
            <span>Referensi Surat Jalan</span>
        </div>
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-2.5 text-xs">
            <div>
                <span class="block text-[11px] text-slate-400 font-semibold">No. Surat Jalan</span>
                <span class="font-bold font-mono-num text-[#284c9a] dark:text-blue-400">{{ $surat_jalan->no_mutasi }}</span>
            </div>
            <div>
                <span class="block text-[11px] text-slate-400 font-semibold">No. Permintaan</span>
                <span class="font-bold font-mono-num text-slate-800 dark:text-slate-100">{{ $surat_jalan->no_permintaan ?? '-' }}</span>
            </div>
            <div>
                <span class="block text-[11px] text-slate-400 font-semibold">Cabang Tujuan</span>
                <span class="font-bold text-slate-800 dark:text-slate-100">{{ textUpperCase($surat_jalan->nama_cabang) }}</span>
            </div>
            @if (!empty($surat_jalan->kode_salesman))
                <div>
                    <span class="block text-[11px] text-slate-400 font-semibold">Salesman</span>
                    <span class="font-semibold text-slate-700 dark:text-slate-300">{{ $surat_jalan->nama_salesman }}</span>
                </div>
            @endif
        </div>
    </div>

    <!-- DATA UTAMA SURAT JALAN -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-2.5 items-start">
        <!-- No Mutasi (Readonly) -->
        <div class="field-group">
            <label class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                No. Surat Jalan
            </label>
            <div class="relative">
                <div class="input-prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                    <i data-lucide="barcode" class="w-3.5 h-3.5"></i>
                </div>
                <input type="text" name="no_mutasi" id="no_mutasi" readonly value="{{ $surat_jalan->no_mutasi }}"
                    class="w-full h-[36px] pl-8 pr-3 text-xs font-bold font-mono-num rounded-lg border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 text-[#284c9a] dark:text-blue-400 cursor-not-allowed">
            </div>
        </div>

        <!-- No Dokumen -->
        <div class="field-group">
            <label for="no_dok" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                No. Dokumen <span class="text-rose-500">*</span>
            </label>
            <div class="relative">
                <div class="input-prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10 transition-colors">
                    <i data-lucide="file-text" class="w-3.5 h-3.5"></i>
                </div>
                <input type="text" name="no_dok" id="no_dok" value="{{ $surat_jalan->no_dok }}" placeholder="Contoh: 123/SJ/2026"
                    class="w-full h-[36px] pl-8 pr-3 text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 placeholder-slate-400 focus:outline-hidden focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition">
            </div>
        </div>

        <!-- Tanggal Surat Jalan -->
        <div class="field-group">
            <label for="tanggal" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                Tanggal <span class="text-rose-500">*</span>
            </label>
            <div class="relative">
                <div class="input-prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10 transition-colors">
                    <i data-lucide="calendar" class="w-3.5 h-3.5"></i>
                </div>
                <input type="text" name="tanggal" id="tanggal" value="{{ $surat_jalan->tanggal }}" placeholder="Pilih Tanggal" readonly
                    class="flatpickr-date w-full h-[36px] pl-8 pr-3 text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition cursor-pointer">
            </div>
        </div>
    </div>

    <!-- ANGKUTAN & TUJUAN -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-2.5 items-start">
        <!-- Tujuan Angkutan -->
        <div class="field-group">
            <label for="kode_tujuan" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                Tujuan <span class="text-rose-500">*</span>
            </label>
            <div class="relative">
                <div class="input-prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-20">
                    <i data-lucide="map-pin" class="w-3.5 h-3.5"></i>
                </div>
                <select name="kode_tujuan" id="kode_tujuan" class="Select2Kodetujuan w-full">
                    <option value="">Pilih Tujuan</option>
                    @foreach ($tujuan_angkutan as $d)
                        <option value="{{ $d->kode_tujuan }}" data-tarif="{{ formatAngka($d->tarif) }}"
                            {{ $surat_jalan->kode_tujuan == $d->kode_tujuan ? 'selected' : '' }}>
                            {{ textUpperCase($d->tujuan) }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>

        <!-- Armada Angkutan -->
        <div class="field-group">
            <label for="kode_angkutan" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                Armada Angkutan
            </label>
            <div class="relative">
                <div class="input-prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-20">
                    <i data-lucide="truck" class="w-3.5 h-3.5"></i>
                </div>
                <select name="kode_angkutan" id="kode_angkutan" class="select2Kodeangkutan w-full">
                    <option value="">Pilih Angkutan</option>
                    @foreach ($angkutan as $d)
                        <option value="{{ $d->kode_angkutan }}" {{ $surat_jalan->kode_angkutan == $d->kode_angkutan ? 'selected' : '' }}>
                            {{ textUpperCase($d->nama_angkutan) }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>

        <!-- No Polisi -->
        <div class="field-group">
            <label for="no_polisi" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                No. Polisi
            </label>
            <div class="relative">
                <div class="input-prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10 transition-colors">
                    <i data-lucide="car" class="w-3.5 h-3.5"></i>
                </div>
                <input type="text" name="no_polisi" id="no_polisi" value="{{ $surat_jalan->no_polisi }}" placeholder="Contoh: D 1234 ABC"
                    class="w-full h-[36px] pl-8 pr-3 text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 placeholder-slate-400 focus:outline-hidden focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition">
            </div>
        </div>
    </div>

    <!-- BIAYA ANGKUTAN (TARIF, TEPUNG, BS) -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-2.5 items-start">
        <div class="field-group">
            <label for="tarif" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                Tarif Angkutan
            </label>
            <div class="relative">
                <div class="input-prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10 transition-colors">
                    <i data-lucide="banknote" class="w-3.5 h-3.5"></i>
                </div>
                <input type="text" name="tarif" id="tarif" value="{{ formatAngka($surat_jalan->tarif) }}" placeholder="0"
                    class="money w-full h-[36px] pl-8 pr-3 text-right text-xs font-bold font-mono-num rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition">
            </div>
        </div>

        <div class="field-group">
            <label for="tepung" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                Tepung
            </label>
            <div class="relative">
                <div class="input-prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10 transition-colors">
                    <i data-lucide="coins" class="w-3.5 h-3.5"></i>
                </div>
                <input type="text" name="tepung" id="tepung" value="{{ formatAngka($surat_jalan->tepung) }}" placeholder="0"
                    class="money w-full h-[36px] pl-8 pr-3 text-right text-xs font-bold font-mono-num rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition">
            </div>
        </div>

        <div class="field-group">
            <label for="bs" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                Biaya Lain (BS)
            </label>
            <div class="relative">
                <div class="input-prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10 transition-colors">
                    <i data-lucide="wallet" class="w-3.5 h-3.5"></i>
                </div>
                <input type="text" name="bs" id="bs" value="{{ formatAngka($surat_jalan->bs) }}" placeholder="0"
                    class="money w-full h-[36px] pl-8 pr-3 text-right text-xs font-bold font-mono-num rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition">
            </div>
        </div>
    </div>

    <!-- KETERANGAN SURAT JALAN -->
    <div class="field-group">
        <label for="keterangan" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
            Keterangan Tambahan
        </label>
        <div class="relative">
            <div class="input-prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                <i data-lucide="file-text" class="w-3.5 h-3.5"></i>
            </div>
            <input type="text" name="keterangan" id="keterangan" value="{{ $surat_jalan->keterangan }}" placeholder="Keterangan pengiriman surat jalan (opsional)"
                class="w-full h-[36px] pl-8 pr-3 text-xs font-medium rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 placeholder-slate-400 focus:outline-hidden focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition">
        </div>
    </div>

    <!-- SEPARATOR BAR -->
    <div class="pt-2 border-t border-slate-100 dark:border-slate-800">
        <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Detail Produk Kiriman</span>
    </div>

    <!-- INPUT PRODUK & JUMLAH (ANTI-LAYOUT SHIFT: ITEMS-START + SPACER LABEL) -->
    <div class="rounded-lg border border-slate-200/80 dark:border-slate-800 bg-slate-50/60 dark:bg-slate-800/30 p-2.5 sm:p-3">
        <div class="grid grid-cols-1 sm:grid-cols-12 gap-2.5 items-start">
            <!-- Pilih Produk -->
            <div class="sm:col-span-7 field-group">
                <label for="kode_produk" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                    Tambah Produk
                </label>
                <div class="relative">
                    <div class="input-prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-20">
                        <i data-lucide="box" class="w-3.5 h-3.5"></i>
                    </div>
                    <select name="kode_produk" id="kode_produk" class="select2Kodeproduk w-full">
                        <option value="">Pilih Produk</option>
                        @foreach ($produk as $d)
                            <option value="{{ $d->kode_produk }}">{{ $d->kode_produk }} - {{ $d->nama_produk }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <!-- Jumlah -->
            <div class="sm:col-span-3 field-group">
                <label for="jumlah" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                    Jumlah
                </label>
                <div class="relative">
                    <div class="input-prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10 transition-colors">
                        <i data-lucide="layers" class="w-3.5 h-3.5"></i>
                    </div>
                    <input type="text" name="jumlah" id="jumlah" placeholder="0"
                        class="money w-full h-[36px] pl-8 pr-3 text-right text-xs font-bold font-mono-num rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition">
                </div>
            </div>

            <!-- Tombol Tambah Produk (Zero Layout Shift Spacer) -->
            <div class="sm:col-span-2 flex flex-col">
                <label class="hidden sm:block text-xs font-bold text-transparent mb-1 select-none pointer-events-none">&nbsp;</label>
                <button type="button" id="tambahproduk"
                    class="h-[36px] w-full inline-flex items-center justify-center gap-1 px-3 rounded-lg bg-brand-600 hover:bg-brand-700 active:bg-brand-800 text-white text-xs font-bold transition shadow-xs cursor-pointer">
                    <i data-lucide="plus" class="w-3.5 h-3.5"></i>
                    <span>Tambah</span>
                </button>
            </div>
        </div>
    </div>

    <!-- TABEL DETAIL PRODUK V2 (~36px KOMPAK) -->
    <div class="rounded-xl border border-slate-200/80 dark:border-slate-800 overflow-hidden shadow-2xs">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse whitespace-nowrap" id="tabledetailProduk">
                <thead>
                    <tr class="bg-[#284c9a] text-white">
                        <th colspan="4" class="py-2 px-3.5">
                            <div class="flex items-center gap-1.5 text-xs font-bold uppercase tracking-wider">
                                <i data-lucide="package" class="w-4 h-4 text-white/80"></i>
                                <span>Daftar Produk Kiriman</span>
                            </div>
                        </th>
                    </tr>
                    <tr class="bg-[#1e3a78] text-white text-[11px] font-bold uppercase tracking-wider">
                        <th class="py-1.5 px-3 w-36">Kode Produk</th>
                        <th class="py-1.5 px-3">Nama Produk</th>
                        <th class="py-1.5 px-3 text-right w-36">Jumlah</th>
                        <th class="py-1.5 px-3 text-center w-14">Aksi</th>
                    </tr>
                </thead>
                <tbody id="loaddetail" class="divide-y divide-slate-100 dark:divide-slate-800 text-xs text-slate-700 dark:text-slate-300">
                    @foreach ($detail as $d)
                        <tr id="index_{{ $d->kode_produk }}" class="hover:bg-slate-50/80 dark:hover:bg-slate-800/50 transition-colors">
                            <td class="py-1.5 px-3 font-mono-num font-bold text-[#284c9a] dark:text-blue-400">
                                {{ $d->kode_produk }}
                                <input type="hidden" name="kode_produk[]" value="{{ $d->kode_produk }}">
                            </td>
                            <td class="py-1.5 px-3 font-medium text-slate-800 dark:text-slate-100">
                                {{ $d->nama_produk }}
                            </td>
                            <td class="py-1.5 px-3 text-right">
                                <input type="text" name="jml[]" value="{{ formatAngka($d->jumlah) }}"
                                    class="money jml w-28 h-[28px] px-2 text-right text-xs font-mono-num font-bold rounded border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-1 focus:ring-brand-500">
                            </td>
                            <td class="py-1.5 px-3 text-center">
                                <button type="button" kode_produk="{{ $d->kode_produk }}"
                                    class="delete w-6 h-6 rounded-md inline-flex items-center justify-center text-rose-600 hover:text-rose-700 hover:bg-rose-50 dark:hover:bg-rose-950/30 transition cursor-pointer">
                                    <i data-lucide="trash-2" class="w-3.5 h-3.5"></i>
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- PERSETUJUAN & SUBMIT BUTTON -->
    <div class="pt-2 border-t border-slate-100 dark:border-slate-800 space-y-2.5">
        <label class="flex items-center gap-2 cursor-pointer select-none text-xs font-semibold text-slate-700 dark:text-slate-300">
            <input type="checkbox" name="aggrement" id="defaultCheck3" value="aggrement"
                class="agreement w-4 h-4 rounded border-slate-300 dark:border-slate-700 text-brand-600 focus:ring-brand-500 transition">
            <span>Yakin perubahan data surat jalan sudah benar dan siap disimpan?</span>
        </label>

        <div id="saveButton" class="hidden">
            <button type="submit" id="btnSimpan"
                class="w-full h-[36px] inline-flex items-center justify-center gap-1.5 rounded-lg bg-[#284c9a] hover:bg-[#1e3a78] active:bg-[#152a56] text-white text-xs font-bold transition shadow-xs cursor-pointer">
                <i data-lucide="send" class="w-3.5 h-3.5"></i>
                <span>Simpan Perubahan</span>
            </button>
        </div>
    </div>
</form>

<script>
$(function() {
    const $form = $("#formeditSuratjalan");

    // Initialize Flatpickr
    $form.find(".flatpickr-date").flatpickr({
        dateFormat: "Y-m-d",
        enable: [{
            from: "{{ $start_periode }}",
            to: "{{ $end_periode }}"
        }]
    });

    // Initialize Money
    $form.find(".money").maskMoney({ thousands: '.', decimal: ',', precision: 2 });

    // Select2 Styling V2 Standard
    function initSelect2($select, placeholder) {
        if ($select.length) {
            $select.select2({
                placeholder: placeholder,
                allowClear: true,
                width: '100%'
            });
        }
    }

    initSelect2($form.find('.Select2Kodetujuan'), 'Pilih Tujuan');
    initSelect2($form.find('.select2Kodeangkutan'), 'Pilih Angkutan');
    initSelect2($form.find('.select2Kodeproduk'), 'Pilih Produk');

    // Auto update tarif saat kode tujuan dipilih
    $form.find("#kode_tujuan").on('change select2:select', function() {
        const tarif = $(this).find(':selected').data('tarif');
        if (tarif) {
            $form.find("#tarif").val(tarif);
            clearFieldError($form.find("#tarif"));
        }
    });

    // Cek Tutup Laporan
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
                $("#cektutuplaporan").val(respond);
            }
        });
    }

    $form.find("#tanggal").on('change', function() {
        cektutuplaporan($(this).val(), "gudangjadi");
    });

    // Validasi Interaktif Helpers V2
    function setFieldError($field, message) {
        const $group = $field.closest('.field-group');
        $group.addClass('has-error');
        $field.addClass('is-invalid !border-rose-500 !ring-1 !ring-rose-500');
        $group.find('.select2-selection').addClass('is-invalid !border-rose-500 !ring-1 !ring-rose-500');
        $group.find('.select2-container').addClass('is-invalid');
        $group.find('.input-prefix-icon').addClass('!text-rose-500');
        $group.find('.field-error-msg').remove();
        $group.append(`
            <p class="field-error-msg text-[11px] font-semibold text-rose-600 dark:text-rose-400 mt-1 pl-1 flex items-center gap-1">
                <svg class="w-3.5 h-3.5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <circle cx="12" cy="12" r="10"></circle>
                    <line x1="12" y1="8" x2="12" y2="12"></line>
                    <line x1="12" y1="16" x2="12.01" y2="16"></line>
                </svg>
                <span>${message}</span>
            </p>
        `);
    }

    function clearFieldError($field) {
        const $group = $field.closest('.field-group');
        $group.removeClass('has-error');
        $field.removeClass('is-invalid !border-rose-500 !ring-1 !ring-rose-500');
        $group.find('.select2-selection').removeClass('is-invalid !border-rose-500 !ring-1 !ring-rose-500');
        $group.find('.select2-container').removeClass('is-invalid');
        $group.find('.input-prefix-icon').removeClass('!text-rose-500');
        $group.find('.field-error-msg').remove();
    }

    // Live Cleanup
    $form.find("input, select").on('input change select2:select select2:clear', function() {
        clearFieldError($(this));
    });

    // Tambah Produk ke Tabel
    function addProduk() {
        const $dataProduk = $form.find("#kode_produk :selected");
        const kode_produk = $dataProduk.val();
        const nama_produk = $dataProduk.text();
        const jumlah = $form.find("#jumlah").val();

        const tr = `
            <tr id="index_${kode_produk}" class="hover:bg-slate-50/80 dark:hover:bg-slate-800/50 transition-colors">
                <td class="py-1.5 px-3 font-mono-num font-bold text-[#284c9a] dark:text-blue-400">
                    <input type="hidden" name="kode_produk[]" value="${kode_produk}"/>
                    ${kode_produk}
                </td>
                <td class="py-1.5 px-3 font-medium text-slate-800 dark:text-slate-100">${nama_produk}</td>
                <td class="py-1.5 px-3 text-right">
                    <input type="text" name="jml[]" value="${jumlah}"
                        class="money jml w-28 h-[28px] px-2 text-right text-xs font-mono-num font-bold rounded border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-1 focus:ring-brand-500" />
                </td>
                <td class="py-1.5 px-3 text-center">
                    <button type="button" kode_produk="${kode_produk}"
                        class="delete w-6 h-6 rounded-md inline-flex items-center justify-center text-rose-600 hover:text-rose-700 hover:bg-rose-50 dark:hover:bg-rose-950/30 transition cursor-pointer">
                        <i data-lucide="trash-2" class="w-3.5 h-3.5"></i>
                    </button>
                </td>
            </tr>
        `;

        $('#loaddetail').prepend(tr);
        $(".money").maskMoney({ thousands: '.', decimal: ',', precision: 2 });
        $form.find('.select2Kodeproduk').val('').trigger("change");
        $form.find("#jumlah").val("");
        if (window.lucide) lucide.createIcons();
    }

    $form.find("#tambahproduk").on('click', function(e) {
        e.preventDefault();
        const kode_produk = $form.find("#kode_produk").val();
        const jumlah = $form.find("#jumlah").val();

        let hasError = false;
        if (!kode_produk) {
            setFieldError($form.find("#kode_produk"), "Silakan pilih produk terlebih dahulu");
            hasError = true;
        }
        if (!jumlah || jumlah === "0" || jumlah === "0,00") {
            setFieldError($form.find("#jumlah"), "Jumlah harus lebih dari 0");
            if (!hasError) $form.find("#jumlah").focus();
            hasError = true;
        }

        if (hasError) return;

        if ($form.find('#tabledetailProduk').find('#index_' + kode_produk).length > 0) {
            setFieldError($form.find("#kode_produk"), "Produk ini sudah ada di daftar");
            return;
        }

        addProduk();
    });

    // Hapus Produk dari Tabel
    $form.on('click', '.delete', function(e) {
        e.preventDefault();
        const kode_produk = $(this).attr("kode_produk");
        $(`#index_${kode_produk}`).remove();
    });

    // Agreement Toggle
    $form.find('.agreement').on('change', function() {
        if (this.checked) {
            $form.find("#saveButton").removeClass('hidden');
        } else {
            $form.find("#saveButton").addClass('hidden');
        }
    });

    // Form Submit & Client-side Validation
    $form.on('submit', function(e) {
        let hasError = false;
        let $firstError = null;

        const tanggal = $form.find("#tanggal").val();
        const no_dok = $form.find("#no_dok").val();
        const kode_tujuan = $form.find("#kode_tujuan").val();
        const kode_angkutan = $form.find("#kode_angkutan").val();
        const no_polisi = $form.find("#no_polisi").val();
        const tarif = $form.find("#tarif").val();
        const cektutup = $form.find("#cektutuplaporan").val();

        if ($form.find('#loaddetail tr').length === 0) {
            setFieldError($form.find("#kode_produk"), "Daftar produk kiriman tidak boleh kosong");
            hasError = true;
            $firstError = $form.find("#kode_produk");
        }

        if (!no_dok) {
            setFieldError($form.find("#no_dok"), "No. Dokumen wajib diisi");
            hasError = true;
            if (!$firstError) $firstError = $form.find("#no_dok");
        }

        if (!tanggal) {
            setFieldError($form.find("#tanggal"), "Tanggal wajib dipilih");
            hasError = true;
            if (!$firstError) $firstError = $form.find("#tanggal");
        }

        if (!kode_tujuan) {
            setFieldError($form.find("#kode_tujuan"), "Tujuan wajib dipilih");
            hasError = true;
            if (!$firstError) $firstError = $form.find("#kode_tujuan");
        }

        if (kode_angkutan && !no_polisi) {
            setFieldError($form.find("#no_polisi"), "No. Polisi wajib diisi jika armada dipilih");
            hasError = true;
            if (!$firstError) $firstError = $form.find("#no_polisi");
        }

        if (kode_angkutan && (!tarif || tarif === "0")) {
            setFieldError($form.find("#tarif"), "Tarif wajib diisi jika armada dipilih");
            hasError = true;
            if (!$firstError) $firstError = $form.find("#tarif");
        }

        if (cektutup === '1') {
            setFieldError($form.find("#tanggal"), "Laporan untuk periode tanggal ini sudah ditutup!");
            hasError = true;
            if (!$firstError) $firstError = $form.find("#tanggal");
        }

        if (hasError) {
            e.preventDefault();
            if ($firstError) $firstError.focus();
            return false;
        }

        // Anti double-submit & loading spinner
        const $btn = $form.find("#btnSimpan");
        $btn.prop('disabled', true).addClass('opacity-75 cursor-not-allowed pointer-events-none');
        $btn.html(`
            <svg class="animate-spin h-3.5 w-3.5 text-white shrink-0 inline-block" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            <span>Menyimpan...</span>
        `);
    });

    if (window.lucide) lucide.createIcons();
});
</script>
