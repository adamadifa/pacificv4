<form action="{{ route('jurnalumum.store') }}" method="POST" id="formJurnalumum" autocomplete="off" class="space-y-3">
    @csrf

    <!-- CARD INPUT ITEM -->
    <div class="rounded-xl border border-slate-200/80 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-800/30 p-3 sm:p-3.5">
        <div class="flex items-center gap-1.5 mb-2.5">
            <i data-lucide="plus-circle" class="w-3.5 h-3.5 text-[#284c9a] dark:text-blue-400"></i>
            <h5 class="text-xs font-bold text-slate-700 dark:text-slate-200">Tambah Item Jurnal</h5>
        </div>

        <div class="space-y-2.5">
            <!-- Baris 1 (12 Kolom Penuh) -->
            <div class="grid grid-cols-1 sm:grid-cols-12 gap-2.5 items-start">
                <!-- Tanggal -->
                <div class="sm:col-span-3">
                    <label for="tanggal_ju" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                        Tanggal <span class="text-rose-500">*</span>
                    </label>
                    <div class="relative">
                        <div class="prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                            <i data-lucide="calendar" class="w-4 h-4"></i>
                        </div>
                        <input type="text" name="tanggal" id="tanggal_ju" placeholder="Pilih Tanggal" readonly
                            class="flatpickr-date-ju w-full h-[36px] pl-9 pr-3 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-950 text-xs font-medium text-slate-700 dark:text-slate-200 focus:outline-none focus:border-[#284c9a] focus:ring-2 focus:ring-[#284c9a]/15 transition cursor-pointer">
                    </div>
                </div>

                <!-- Akun -->
                <div class="sm:col-span-6">
                    <label for="kode_akun_ju" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                        Akun <span class="text-rose-500">*</span>
                    </label>
                    <div class="relative select2-container-v2">
                        <div class="prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-20">
                            <i data-lucide="layers" class="w-4 h-4"></i>
                        </div>
                        <select name="kode_akun" id="kode_akun_ju" class="select2KodeakunJu w-full">
                            <option value="">Pilih Akun</option>
                            @foreach ($coa as $d)
                                <option value="{{ $d->kode_akun }}">{{ $d->kode_akun }} - {{ $d->nama_akun }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <!-- Jumlah -->
                <div class="sm:col-span-3">
                    <label for="jumlah_ju" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                        Jumlah <span class="text-rose-500">*</span>
                    </label>
                    <div class="relative">
                        <div class="prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                            <i data-lucide="banknote" class="w-4 h-4"></i>
                        </div>
                        <input type="text" name="jumlah" id="jumlah_ju" placeholder="0"
                            class="number-separator-ju w-full h-[36px] pl-9 pr-3 text-right font-mono-num rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-950 text-xs font-medium text-slate-700 dark:text-slate-200 focus:outline-none focus:border-[#284c9a] focus:ring-2 focus:ring-[#284c9a]/15 transition">
                    </div>
                </div>
            </div>

            <!-- Baris 2 (12 Kolom Penuh Sejajar dengan Tombol Tambah) -->
            <div class="grid grid-cols-1 sm:grid-cols-12 gap-2.5 items-start">
                <!-- Keterangan -->
                <div class="sm:col-span-5" id="keterangan_wrap_ju">
                    <label for="keterangan_ju" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                        Keterangan <span class="text-rose-500">*</span>
                    </label>
                    <div class="relative">
                        <div class="prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                            <i data-lucide="file-text" class="w-4 h-4"></i>
                        </div>
                        <input type="text" name="keterangan" id="keterangan_ju" placeholder="Keterangan jurnal"
                            class="w-full h-[36px] pl-9 pr-3 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-950 text-xs font-medium text-slate-700 dark:text-slate-200 focus:outline-none focus:border-[#284c9a] focus:ring-2 focus:ring-[#284c9a]/15 transition">
                    </div>
                </div>

                <!-- Debet / Kredit -->
                <div class="sm:col-span-3" id="dk_wrap_ju">
                    <label for="debet_kredit_ju" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                        Debet / Kredit <span class="text-rose-500">*</span>
                    </label>
                    <div class="relative">
                        <div class="prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                            <i data-lucide="arrow-left-right" class="w-4 h-4"></i>
                        </div>
                        <select name="debet_kredit" id="debet_kredit_ju"
                            class="w-full h-[36px] pl-9 pr-7 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-950 text-xs font-medium text-slate-700 dark:text-slate-200 focus:outline-none focus:border-[#284c9a] focus:ring-2 focus:ring-[#284c9a]/15 transition">
                            <option value="">Debet / Kredit</option>
                            <option value="D">Debet</option>
                            <option value="K">Kredit</option>
                        </select>
                    </div>
                </div>

                <!-- Peruntukan -->
                <div class="sm:col-span-2" id="peruntukan_wrap_ju">
                    <label for="kode_peruntukan_ju" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                        Peruntukan <span class="text-rose-500">*</span>
                    </label>
                    <div class="relative">
                        <div class="prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                            <i data-lucide="tag" class="w-4 h-4"></i>
                        </div>
                        <select name="kode_peruntukan" id="kode_peruntukan_ju"
                            class="w-full h-[36px] pl-9 pr-7 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-950 text-xs font-medium text-slate-700 dark:text-slate-200 focus:outline-none focus:border-[#284c9a] focus:ring-2 focus:ring-[#284c9a]/15 transition">
                            <option value="">Pilih</option>
                            <option value="MP">MP</option>
                            <option value="PC">PACIFIC</option>
                        </select>
                    </div>
                </div>

                <!-- Cabang (hanya tampil jika PC) -->
                <div class="sm:col-span-3" id="cabang_wrap_ju" style="display: none;">
                    <label for="kode_cabang_ju" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                        Cabang <span class="text-rose-500">*</span>
                    </label>
                    <div class="relative select2-container-v2">
                        <div class="prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-20">
                            <i data-lucide="building" class="w-4 h-4"></i>
                        </div>
                        <select name="kode_cabang" id="kode_cabang_ju" class="select2KodecabangJu w-full">
                            <option value="">Pilih Cabang</option>
                            @foreach ($cabang as $d)
                                <option value="{{ $d->kode_cabang }}">{{ textUpperCase($d->nama_cabang) }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <!-- Tombol Tambah (Zero Layout Shift) -->
                <div class="sm:col-span-2" id="btnTambahWrap">
                    <label class="hidden sm:block text-xs font-bold text-transparent mb-1 select-none pointer-events-none">&nbsp;</label>
                    <button type="button" id="btnTambahItem"
                        class="w-full h-[36px] px-3 rounded-lg bg-[#284c9a] hover:bg-[#1e3a78] text-white text-xs font-bold shadow-xs transition flex items-center justify-center gap-1.5 cursor-pointer">
                        <i data-lucide="plus" class="w-3.5 h-3.5"></i>
                        <span>Tambah</span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- TABEL DAFTAR ITEM JURNAL -->
    <div class="rounded-xl border border-slate-200/80 dark:border-slate-800 bg-white dark:bg-slate-900 overflow-hidden">
        <div class="px-3.5 py-2 bg-[#284c9a] flex items-center justify-between text-white">
            <div class="flex items-center gap-2">
                <i data-lucide="list" class="w-4 h-4 text-blue-200"></i>
                <h5 class="text-xs font-bold text-white uppercase tracking-wider">Daftar Item Jurnal</h5>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-xs text-left whitespace-nowrap">
                <thead class="bg-[#1e3a78] text-white/90 text-[11px] font-bold uppercase tracking-wider">
                    <tr>
                        <th class="py-2 px-3">TANGGAL</th>
                        <th class="py-2 px-3">AKUN</th>
                        <th class="py-2 px-3">KETERANGAN</th>
                        <th class="py-2 px-3 text-right">DEBET</th>
                        <th class="py-2 px-3 text-right">KREDIT</th>
                        <th class="py-2 px-3">PERUNTUKAN</th>
                        <th class="py-2 px-3 text-center w-20">#</th>
                    </tr>
                </thead>
                <tbody id="loadjurnalumum" class="divide-y divide-slate-100 dark:divide-slate-800"></tbody>
                <tfoot class="bg-slate-50 dark:bg-slate-800/70 border-t border-slate-200 dark:border-slate-700 font-bold text-xs">
                    <tr>
                        <td colspan="3" class="py-2 px-3 text-right uppercase tracking-wider text-slate-600 dark:text-slate-300">TOTAL</td>
                        <td class="py-2 px-3 text-right font-mono-num text-[#284c9a] dark:text-blue-400" id="total_debet">0</td>
                        <td class="py-2 px-3 text-right font-mono-num text-[#284c9a] dark:text-blue-400" id="total_kredit">0</td>
                        <td colspan="2"></td>
                    </tr>
                </tfoot>
            </table>
        </div>

        <div id="error-jurnalumum" class="hidden px-3.5 py-2 bg-rose-50/80 dark:bg-rose-950/30 border-t border-rose-200/80 dark:border-rose-900/60 text-[11px] font-semibold text-rose-600 dark:text-rose-400 items-center gap-1.5">
            <svg class="w-3.5 h-3.5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
            <span></span>
        </div>
    </div>

    <!-- AGREEMENT & FOOTER SUBMIT -->
    <div class="space-y-2.5">
        <label class="inline-flex items-center gap-2 cursor-pointer select-none">
            <input type="checkbox" name="aggrement" value="aggrement" id="agreementJu"
                class="agreement w-4 h-4 rounded border-slate-300 text-[#284c9a] focus:ring-[#284c9a]/25">
            <span class="text-xs font-semibold text-slate-700 dark:text-slate-200">Yakin Akan Disimpan?</span>
        </label>

        <div id="saveButtonWrap" style="display: none;" class="border-t border-slate-100 dark:border-slate-800 pt-2.5 flex items-center justify-end gap-2">
            <button type="button" onclick="window.closeModalJurnalUmum && window.closeModalJurnalUmum()"
                class="h-[34px] px-3.5 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 hover:bg-slate-50 dark:hover:bg-slate-800 text-slate-600 dark:text-slate-300 text-xs font-bold transition cursor-pointer">
                Batal
            </button>
            <button type="submit" id="btnSimpanJurnalUmum"
                class="h-[34px] px-4 rounded-lg bg-[#284c9a] hover:bg-[#1e3a78] text-white text-xs font-bold shadow-xs transition flex items-center justify-center gap-1.5 cursor-pointer">
                <i data-lucide="save" class="w-3.5 h-3.5"></i>
                <span>Simpan Jurnal</span>
            </button>
        </div>
    </div>
</form>

<script>
    $(function() {
        if (typeof lucide !== 'undefined') lucide.createIcons();

        let total_debet_set = 0;
        let total_kredit_set = 0;
        let baris = 0;

        const $form = $('#formJurnalumum');
        const $tanggal = $form.find('#tanggal_ju');
        const $kodeAkun = $form.find('#kode_akun_ju');
        const $jumlah = $form.find('#jumlah_ju');
        const $keterangan = $form.find('#keterangan_ju');
        const $debetKredit = $form.find('#debet_kredit_ju');
        const $peruntukan = $form.find('#kode_peruntukan_ju');
        const $cabang = $form.find('#kode_cabang_ju');
        const $cabangWrap = $form.find('#cabang_wrap_ju');
        const $tbody = $form.find('#loadjurnalumum');
        const $tableError = $form.find('#error-jurnalumum');

        $tanggal.flatpickr({
            dateFormat: 'Y-m-d',
            allowInput: false
        });

        easyNumberSeparator({
            selector: '.number-separator-ju',
            separator: '.',
            decimalSeparator: ',',
        });

        $kodeAkun.select2({
            placeholder: 'Pilih Akun',
            allowClear: true,
            dropdownCssClass: 'select2-dropdown-v2'
        });

        $cabang.select2({
            placeholder: 'Pilih Cabang',
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

        function loadkodecabang() {
            if ($peruntukan.val() === 'PC') {
                $('#keterangan_wrap_ju').removeClass('sm:col-span-5').addClass('sm:col-span-3');
                $('#dk_wrap_ju').removeClass('sm:col-span-3').addClass('sm:col-span-2');
                $cabangWrap.show();
            } else {
                $('#keterangan_wrap_ju').removeClass('sm:col-span-3').addClass('sm:col-span-5');
                $('#dk_wrap_ju').removeClass('sm:col-span-2').addClass('sm:col-span-3');
                $cabangWrap.hide();
                clearFieldError($cabang);
            }
        }

        $peruntukan.on('change', loadkodecabang);

        $tanggal.add($jumlah).add($keterangan).add($debetKredit).add($peruntukan).on('input change', function() {
            if ($(this).val()) clearFieldError($(this));
        });

        $kodeAkun.add($cabang).on('select2:select change', function() {
            if ($(this).val()) clearFieldError($(this));
        });

        $form.find('.agreement').on('change', function() {
            if (this.checked) {
                $form.find('#saveButtonWrap').show();
            } else {
                $form.find('#saveButtonWrap').hide();
            }
        });

        function numberFormat(number, decimals, dec_point, thousands_sep) {
            number = (number + '').replace(/[^0-9+\-Ee.]/g, '');
            var n = !isFinite(+number) ? 0 : +number,
                prec = !isFinite(+decimals) ? 0 : Math.abs(decimals),
                sep = typeof thousands_sep === 'undefined' ? '.' : thousands_sep,
                dec = typeof dec_point === 'undefined' ? ',' : dec_point,
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

        function calculateTotal() {
            let total_debet = 0;
            let total_kredit = 0;
            $tbody.find('tr').each(function() {
                const debet = $(this).find('.jmldebet').text().replace(/\./g, '') || 0;
                const kredit = $(this).find('.jmlkredit').text().replace(/\./g, '') || 0;
                total_debet += parseInt(debet, 10) || 0;
                total_kredit += parseInt(kredit, 10) || 0;
            });
            total_debet_set = total_debet;
            total_kredit_set = total_kredit;
            $form.find('#total_debet').text(numberFormat(total_debet_set, 0, ',', '.'));
            $form.find('#total_kredit').text(numberFormat(total_kredit_set, 0, ',', '.'));

            if ($tbody.children().length > 0 && total_debet_set === total_kredit_set) {
                $tableError.removeClass('flex').addClass('hidden').find('span').text('');
            }
        }

        function resetForm() {
            $kodeAkun.val('').trigger('change');
            $debetKredit.val('');
            $jumlah.val('');
            $cabang.val('').trigger('change');

            if ($('#btnTambahItem').data('edit-id')) {
                $('#btnTambahItem').removeData('edit-id').html('<i data-lucide="plus" class="w-3.5 h-3.5"></i><span>Tambah</span>');
                if (typeof lucide !== 'undefined') lucide.createIcons();
            }
        }

        $('#btnTambahItem').on('click', function(e) {
            e.preventDefault();
            const tanggal = $tanggal.val();
            const kode_akun = $kodeAkun.val();
            const jumlah = $jumlah.val();
            const keterangan = $keterangan.val();
            const debet_kredit = $debetKredit.val();
            const kode_peruntukan = $peruntukan.val();
            const kode_cabang = kode_peruntukan === 'MP' ? '' : $cabang.val();
            const kredit = debet_kredit === 'K' ? jumlah : '';
            const debet = debet_kredit === 'D' ? jumlah : '';
            const selectedAkun = $kodeAkun.select2('data');
            const nama_akun = selectedAkun && selectedAkun[0] ? selectedAkun[0].text : '';

            let firstInvalid = null;
            if (!tanggal) { setFieldError($tanggal, 'Tanggal wajib diisi.'); firstInvalid = firstInvalid || $tanggal; }
            if (!kode_akun) { setFieldError($kodeAkun, 'Akun wajib dipilih.'); firstInvalid = firstInvalid || $kodeAkun; }
            if (!jumlah || jumlah == '0') { setFieldError($jumlah, 'Jumlah wajib diisi.'); firstInvalid = firstInvalid || $jumlah; }
            if (!keterangan) { setFieldError($keterangan, 'Keterangan wajib diisi.'); firstInvalid = firstInvalid || $keterangan; }
            if (!debet_kredit) { setFieldError($debetKredit, 'Debet/Kredit wajib dipilih.'); firstInvalid = firstInvalid || $debetKredit; }
            if (!kode_peruntukan) { setFieldError($peruntukan, 'Peruntukan wajib dipilih.'); firstInvalid = firstInvalid || $peruntukan; }
            if (kode_peruntukan === 'PC' && !kode_cabang) { setFieldError($cabang, 'Cabang wajib dipilih.'); firstInvalid = firstInvalid || $cabang; }

            if (firstInvalid) {
                firstInvalid.focus();
                return false;
            }

            const editId = $(this).data('edit-id');
            if (editId) {
                const $row = $tbody.find(`#${editId}`);
                $row.find('input[name="tanggal_item[]"]').val(tanggal);
                $row.find('input[name="kode_akun_item[]"]').val(kode_akun);
                $row.find('input[name="debet_kredit_item[]"]').val(debet_kredit);
                $row.find('input[name="jumlah_item[]"]').val(jumlah);
                $row.find('input[name="keterangan_item[]"]').val(keterangan);
                $row.find('input[name="kode_peruntukan_item[]"]').val(kode_peruntukan);
                $row.find('input[name="kode_cabang_item[]"]').val(kode_cabang);

                $row.find('td:eq(0)').text(tanggal);
                $row.find('td:eq(1)').text(nama_akun);
                $row.find('td:eq(2)').text(keterangan);
                $row.find('td:eq(3)').text(debet);
                $row.find('td:eq(4)').text(kredit);
                $row.find('td:eq(5)').text(kode_peruntukan + (kode_cabang ? ' (' + kode_cabang + ')' : ''));

                $(this).removeData('edit-id').html('<i data-lucide="plus" class="w-3.5 h-3.5"></i><span>Tambah</span>');
            } else {
                baris += 1;
                const newRow = `
                    <tr id="ju-row-${baris}" class="hover:bg-slate-50/80 dark:hover:bg-slate-800/40 transition-colors">
                        <input type="hidden" name="tanggal_item[]" value="${tanggal}"/>
                        <input type="hidden" name="kode_akun_item[]" value="${kode_akun}"/>
                        <input type="hidden" name="debet_kredit_item[]" value="${debet_kredit}"/>
                        <input type="hidden" name="jumlah_item[]" value="${jumlah}"/>
                        <input type="hidden" name="keterangan_item[]" value="${keterangan}"/>
                        <input type="hidden" name="kode_peruntukan_item[]" value="${kode_peruntukan}"/>
                        <input type="hidden" name="kode_cabang_item[]" value="${kode_cabang}"/>
                        <td class="py-1.5 px-3 font-mono-num text-slate-700 dark:text-slate-200">${tanggal}</td>
                        <td class="py-1.5 px-3 text-slate-700 dark:text-slate-200">${nama_akun}</td>
                        <td class="py-1.5 px-3 text-slate-700 dark:text-slate-300">${keterangan}</td>
                        <td class="py-1.5 px-3 text-right font-mono-num font-semibold text-slate-800 dark:text-slate-100 jmldebet">${debet}</td>
                        <td class="py-1.5 px-3 text-right font-mono-num font-semibold text-slate-800 dark:text-slate-100 jmlkredit">${kredit}</td>
                        <td class="py-1.5 px-3 font-medium text-slate-600 dark:text-slate-300">${kode_peruntukan} ${kode_cabang ? '(' + kode_cabang + ')' : ''}</td>
                        <td class="py-1.5 px-3">
                            <div class="flex items-center justify-center gap-1">
                                <button type="button" data-id="ju-row-${baris}" class="edit w-6 h-6 rounded-md bg-sky-50 hover:bg-sky-100 text-sky-600 flex items-center justify-center transition cursor-pointer" title="Edit">
                                    <i data-lucide="pencil" class="w-3 h-3"></i>
                                </button>
                                <button type="button" data-id="ju-row-${baris}" class="delete w-6 h-6 rounded-md bg-rose-50 hover:bg-rose-100 text-rose-600 flex items-center justify-center transition cursor-pointer" title="Hapus">
                                    <i data-lucide="trash-2" class="w-3 h-3"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                `;
                $tbody.append(newRow);
            }

            if (typeof lucide !== 'undefined') lucide.createIcons();
            resetForm();
            calculateTotal();
        });

        $form.on('click', '.delete', function(e) {
            e.preventDefault();
            const id = $(this).data('id');
            Swal.fire({
                title: 'Apakah Anda Yakin Ingin Menghapus Data Ini?',
                text: 'Jika dihapus maka data akan hilang permanen.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#284c9a',
                cancelButtonColor: '#e11d48',
                confirmButtonText: 'Ya, Hapus!'
            }).then((result) => {
                if (result.isConfirmed) {
                    const editId = $('#btnTambahItem').data('edit-id');
                    if (editId === id) {
                        resetForm();
                    }
                    $tbody.find(`#${id}`).remove();
                    calculateTotal();
                }
            });
        });

        $form.on('click', '.edit', function(e) {
            e.preventDefault();
            const id = $(this).data('id');
            const $row = $tbody.find(`#${id}`);

            const tanggal = $row.find('input[name="tanggal_item[]"]').val();
            const kode_akun = $row.find('input[name="kode_akun_item[]"]').val();
            const jumlah = $row.find('input[name="jumlah_item[]"]').val();
            const keterangan = $row.find('input[name="keterangan_item[]"]').val();
            const debet_kredit = $row.find('input[name="debet_kredit_item[]"]').val();
            const kode_peruntukan = $row.find('input[name="kode_peruntukan_item[]"]').val();
            const kode_cabang = $row.find('input[name="kode_cabang_item[]"]').val();

            $tanggal.val(tanggal);
            $kodeAkun.val(kode_akun).trigger('change');
            $jumlah.val(jumlah);
            $keterangan.val(keterangan);
            $debetKredit.val(debet_kredit);
            $peruntukan.val(kode_peruntukan).trigger('change');

            setTimeout(function() {
                if (kode_peruntukan === 'PC' && kode_cabang) {
                    $cabang.val(kode_cabang).trigger('change');
                }
            }, 100);

            $('#btnTambahItem').data('edit-id', id).html('<i data-lucide="check" class="w-3.5 h-3.5"></i><span>Update</span>');
            if (typeof lucide !== 'undefined') lucide.createIcons();
        });

        $form.on('submit', function(e) {
            if ($tbody.children().length === 0) {
                e.preventDefault();
                $tableError.removeClass('hidden').addClass('flex').find('span').text('Jurnal umum tidak boleh kosong. Tambahkan minimal 1 item.');
                return false;
            }
            if (total_debet_set !== total_kredit_set) {
                e.preventDefault();
                $tableError.removeClass('hidden').addClass('flex').find('span').text('Total Debet tidak sama dengan Total Kredit.');
                return false;
            }

            const $btn = $('#btnSimpanJurnalUmum');
            $btn.prop('disabled', true)
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
