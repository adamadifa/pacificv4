<form action="{{ route('kesepakatanbersama.storepotongan', Crypt::encrypt($no_kb)) }}" method="POST" id="formPotongan" autocomplete="off" class="space-y-3">
    @csrf

    <!-- Input Tambah Potongan -->
    <div class="grid grid-cols-1 sm:grid-cols-12 gap-2.5 items-start">
        <div class="sm:col-span-6">
            <label for="keterangan_potongan" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                Keterangan <span class="text-rose-500">*</span>
            </label>
            <div class="relative">
                <div class="prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                    <i data-lucide="file-text" class="w-4 h-4"></i>
                </div>
                <input type="text" name="keterangan" id="keterangan_potongan" placeholder="Keterangan potongan"
                    class="w-full h-[36px] pl-9 pr-3 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-950 text-xs font-medium text-slate-700 dark:text-slate-200 focus:outline-none focus:border-[#284c9a] focus:ring-2 focus:ring-[#284c9a]/15 transition">
            </div>
        </div>

        <div class="sm:col-span-3">
            <label for="jumlah_potongan" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                Jumlah <span class="text-rose-500">*</span>
            </label>
            <div class="relative">
                <div class="prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                    <i data-lucide="banknote" class="w-4 h-4"></i>
                </div>
                <input type="text" name="jumlah" id="jumlah_potongan" placeholder="0"
                    class="money-potongan w-full h-[36px] pl-9 pr-3 text-right font-mono-num rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-950 text-xs font-medium text-slate-700 dark:text-slate-200 focus:outline-none focus:border-[#284c9a] focus:ring-2 focus:ring-[#284c9a]/15 transition">
            </div>
        </div>

        <div class="sm:col-span-3">
            <label class="hidden sm:block text-xs font-bold text-transparent mb-1 select-none pointer-events-none">&nbsp;</label>
            <button type="button" id="btnTambahpotongan"
                class="w-full h-[36px] px-3 rounded-lg bg-[#284c9a] hover:bg-[#1e3a78] text-white text-xs font-bold shadow-xs transition flex items-center justify-center gap-1 cursor-pointer">
                <i data-lucide="plus" class="w-3.5 h-3.5"></i>
                <span>Tambah</span>
            </button>
        </div>
    </div>

    <!-- Tabel Daftar Potongan -->
    <div class="rounded-xl border border-slate-200/80 dark:border-slate-800 bg-white dark:bg-slate-900 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-xs text-left whitespace-nowrap">
                <thead class="bg-[#1e3a78] text-white/90 text-[11px] font-bold uppercase tracking-wider">
                    <tr>
                        <th class="py-2 px-3">KETERANGAN</th>
                        <th class="py-2 px-3 text-right w-36">JUMLAH</th>
                        <th class="py-2 px-3 text-center w-16">#</th>
                    </tr>
                </thead>
                <tbody id="loadpotongan" class="divide-y divide-slate-100 dark:divide-slate-800">
                    @php $no = 1; @endphp
                    @foreach ($potongan as $d)
                        <tr id="pot-row-{{ $no }}" class="hover:bg-slate-50/80 dark:hover:bg-slate-800/40 transition-colors">
                            <input type="hidden" name="keterangan_item[]" value="{{ $d->keterangan }}">
                            <input type="hidden" name="jumlah_item[]" value="{{ formatAngka($d->jumlah) }}">
                            <td class="py-1.5 px-3 text-slate-700 dark:text-slate-200">{{ $d->keterangan }}</td>
                            <td class="py-1.5 px-3 text-right font-mono-num font-semibold text-slate-800 dark:text-slate-100">{{ formatAngka($d->jumlah) }}</td>
                            <td class="py-1.5 px-3 text-center">
                                <button type="button" class="delete-potongan w-6 h-6 rounded-md bg-rose-50 hover:bg-rose-100 text-rose-600 dark:bg-rose-950/50 dark:text-rose-400 inline-flex items-center justify-center transition cursor-pointer" title="Hapus">
                                    <i data-lucide="trash-2" class="w-3 h-3"></i>
                                </button>
                            </td>
                        </tr>
                        @php $no++; @endphp
                    @endforeach
                </tbody>
            </table>
        </div>

        <div id="error-loadpotongan" class="hidden px-3.5 py-2 bg-rose-50/80 dark:bg-rose-950/30 border-t border-rose-200/80 dark:border-rose-900/60 text-[11px] font-semibold text-rose-600 dark:text-rose-400 items-center gap-1.5">
            <svg class="w-3.5 h-3.5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
            <span>Data potongan masih kosong. Tambahkan minimal 1 potongan.</span>
        </div>
    </div>

    <!-- Agreement & Footer Submit -->
    <div class="space-y-2.5">
        <label class="inline-flex items-center gap-2 cursor-pointer select-none">
            <input class="agreement w-4 h-4 rounded border-slate-300 text-[#284c9a] focus:ring-[#284c9a]/25" name="aggrement" value="aggrement" type="checkbox" id="agreementPotongan">
            <span class="text-xs font-semibold text-slate-700 dark:text-slate-200">Yakin Akan Disimpan?</span>
        </label>

        <div id="saveButtonPotongan" style="display: none;" class="border-t border-slate-100 dark:border-slate-800 pt-2.5 flex items-center justify-end gap-2">
            <button type="button" onclick="window.closeModalKb && window.closeModalKb()"
                class="h-[34px] px-3.5 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 hover:bg-slate-50 dark:hover:bg-slate-800 text-slate-600 dark:text-slate-300 text-xs font-bold transition cursor-pointer">
                Batal
            </button>
            <button type="submit" id="btnSimpanPotongan"
                class="h-[34px] px-4 rounded-lg bg-[#284c9a] hover:bg-[#1e3a78] text-white text-xs font-bold shadow-xs transition flex items-center justify-center gap-1.5 cursor-pointer">
                <i data-lucide="save" class="w-3.5 h-3.5"></i>
                <span>Simpan</span>
            </button>
        </div>
    </div>
</form>

<script>
    $(function() {
        if (typeof lucide !== 'undefined') lucide.createIcons();

        const $form = $('#formPotongan');
        const $keterangan = $form.find('#keterangan_potongan');
        const $jumlah = $form.find('#jumlah_potongan');
        const $tbody = $form.find('#loadpotongan');
        const $tableError = $form.find('#error-loadpotongan');

        $jumlah.maskMoney();
        let baris = {{ $no }};

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

        $keterangan.add($jumlah).on('input change', function() {
            if ($(this).val()) clearFieldError($(this));
        });

        $('#btnTambahpotongan').on('click', function(e) {
            e.preventDefault();
            const ket = $keterangan.val().trim();
            const jml = $jumlah.val().trim();

            let firstInvalid = null;
            if (!ket) { setFieldError($keterangan, 'Keterangan wajib diisi.'); firstInvalid = firstInvalid || $keterangan; }
            if (!jml) { setFieldError($jumlah, 'Jumlah wajib diisi.'); firstInvalid = firstInvalid || $jumlah; }

            if (firstInvalid) {
                firstInvalid.focus();
                return false;
            }

            const newItem = `
                <tr id="pot-row-${baris}" class="hover:bg-slate-50/80 dark:hover:bg-slate-800/40 transition-colors">
                    <input type="hidden" name="keterangan_item[]" value="${ket}"/>
                    <input type="hidden" name="jumlah_item[]" value="${jml}"/>
                    <td class="py-1.5 px-3 text-slate-700 dark:text-slate-200">${ket}</td>
                    <td class="py-1.5 px-3 text-right font-mono-num font-semibold text-slate-800 dark:text-slate-100">${jml}</td>
                    <td class="py-1.5 px-3 text-center">
                        <button type="button" class="delete-potongan w-6 h-6 rounded-md bg-rose-50 hover:bg-rose-100 text-rose-600 dark:bg-rose-950/50 dark:text-rose-400 inline-flex items-center justify-center transition cursor-pointer" title="Hapus">
                            <i data-lucide="trash-2" class="w-3 h-3"></i>
                        </button>
                    </td>
                </tr>
            `;

            $tbody.append(newItem);
            if (typeof lucide !== 'undefined') lucide.createIcons();
            $tableError.removeClass('flex').addClass('hidden');
            $keterangan.val('');
            $jumlah.val('');
            baris += 1;
        });

        $form.on('click', '.delete-potongan', function(e) {
            e.preventDefault();
            const $row = $(this).closest('tr');
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
                    $row.remove();
                }
            });
        });

        $form.find('.agreement').on('change', function() {
            if (this.checked) {
                $('#saveButtonPotongan').show();
            } else {
                $('#saveButtonPotongan').hide();
            }
        });

        $form.on('submit', function(e) {
            if ($tbody.children().length === 0) {
                e.preventDefault();
                $tableError.removeClass('hidden').addClass('flex');
                $keterangan.focus();
                return false;
            }

            const $btn = $('#btnSimpanPotongan');
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
