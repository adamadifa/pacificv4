@php $namabulan = config('global.nama_bulan'); @endphp
<form action="{{ route('permintaanproduksi.store') }}" method="POST" id="frmCreatePermintaanproduksi" class="space-y-3.5">
    @csrf

    <div class="field-group">
        <label for="kode_oman" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
            Pilih Order Marketing (OMAN) <span class="text-rose-500">*</span>
        </label>
        <div class="relative">
            <div class="input-prefix-icon absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400 z-10">
                <i data-lucide="file-text" class="w-4 h-4"></i>
            </div>
            <select name="kode_oman" id="kode_oman"
                class="w-full h-[36px] pl-9 pr-8 text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition appearance-none cursor-pointer">
                <option value="">Pilih Dokumen OMAN</option>
                @foreach ($oman as $d)
                    <option value="{{ Crypt::encrypt($d['kode_oman']) }}">
                        {{ $d['kode_oman'] }} &bull; {{ $namabulan[$d->bulan] ?? $d->bulan }} {{ $d->tahun }}
                    </option>
                @endforeach
            </select>
            <div class="absolute inset-y-0 right-0 pr-2.5 flex items-center pointer-events-none text-slate-400">
                <i data-lucide="chevron-down" class="w-4 h-4"></i>
            </div>
        </div>
    </div>

    <!-- TABLE RINCIAN PRODUK DARI OMAN -->
    <div class="rounded-lg border border-slate-200/80 dark:border-slate-800 overflow-hidden shadow-2xs">
        <div class="overflow-x-auto scrollbar-thin max-h-[360px]">
            <table class="w-full text-xs text-left border-collapse whitespace-nowrap" id="mytable">
                <thead class="sticky top-0 z-10">
                    <tr class="bg-[#284c9a] text-white">
                        <th colspan="6" class="px-4 py-3 bg-[#284c9a] text-white border-0">
                            <div class="flex items-center gap-2">
                                <i data-lucide="file-spreadsheet" class="w-4 h-4 text-blue-200"></i>
                                <span class="text-xs font-bold uppercase tracking-wider text-white">Rincian Permintaan Produksi dari OMAN</span>
                            </div>
                        </th>
                    </tr>
                    <tr class="bg-[#1e3a78] text-white text-[11px] border-none">
                        <th class="py-2.5 px-4 font-bold uppercase tracking-wider text-white/90 border-none">Kode Produk</th>
                        <th class="py-2.5 px-4 font-bold uppercase tracking-wider text-white/90 border-none">Nama Produk</th>
                        <th class="py-2.5 px-4 text-right font-bold uppercase tracking-wider text-white/90 border-none">Oman Marketing</th>
                        <th class="py-2.5 px-4 text-right font-bold uppercase tracking-wider text-white/90 border-none">Stok Gudang</th>
                        <th class="py-2.5 px-4 text-right font-bold uppercase tracking-wider text-white/90 border-none w-36">Buffer Stok</th>
                        <th class="py-2.5 px-4 text-right font-bold uppercase tracking-wider text-white/90 border-none w-36">Total Permintaan</th>
                    </tr>
                </thead>
                <tbody id="loadoman" class="divide-y divide-slate-100 dark:divide-slate-800/80 bg-white dark:bg-slate-900">
                    <tr>
                        <td colspan="6" class="py-8 text-center text-slate-400">
                            <div class="flex flex-col items-center justify-center gap-1.5">
                                <i data-lucide="arrow-up-circle" class="w-6 h-6 text-slate-300 dark:text-slate-600"></i>
                                <span class="text-xs font-semibold">Silakan pilih dokumen OMAN di atas untuk memuat rincian produk</span>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- SUBMIT BUTTON (WITH SPINNER & ANTI DOUBLE-SUBMIT) -->
    <div class="pt-2 flex items-center justify-end gap-2 border-t border-slate-100 dark:border-slate-800">
        <button type="button" class="btn-cancel-modal h-[34px] px-4 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 hover:bg-slate-50 dark:hover:bg-slate-700/60 text-slate-700 dark:text-slate-200 text-xs font-bold transition cursor-pointer" data-bs-dismiss="modal">
            Batal
        </button>
        <button type="submit" id="btnSubmitPP"
            class="inline-flex items-center justify-center gap-1.5 h-[34px] px-5 rounded-lg bg-brand-600 hover:bg-brand-700 active:bg-brand-800 text-white text-xs font-bold transition shadow-xs cursor-pointer">
            <i data-lucide="send" class="w-3.5 h-3.5"></i>
            <span>Simpan Permintaan Produksi</span>
        </button>
    </div>
</form>

<script>
    $(function() {
        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }

        const $form = $("#frmCreatePermintaanproduksi");
        const $omanSelect = $form.find("#kode_oman");
        const $btnSubmit = $form.find("#btnSubmitPP");

        function setFieldError($input, message) {
            const $container = $input.closest('.field-group');
            $input.addClass('border-rose-500 ring-1 ring-rose-500 focus:border-rose-500 focus:ring-rose-500/20')
                  .removeClass('border-slate-200 dark:border-slate-700');
            $container.find('.input-prefix-icon').addClass('text-rose-500').removeClass('text-slate-400');

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
            $input.removeClass('border-rose-500 ring-1 ring-rose-500 focus:border-rose-500 focus:ring-rose-500/20')
                  .addClass('border-slate-200 dark:border-slate-700');
            $container.find('.input-prefix-icon').removeClass('text-rose-500').addClass('text-slate-400');
            $container.find('.field-error-msg').remove();
        }

        $omanSelect.on("change input", function() {
            clearFieldError($(this));
            const val = $(this).val();

            if (!val) {
                $("#loadoman").html(`
                    <tr>
                        <td colspan="6" class="py-8 text-center text-slate-400">
                            <div class="flex flex-col items-center justify-center gap-1.5">
                                <i data-lucide="arrow-up-circle" class="w-6 h-6 text-slate-300 dark:text-slate-600"></i>
                                <span class="text-xs font-semibold">Silakan pilih dokumen OMAN di atas untuk memuat rincian produk</span>
                            </div>
                        </td>
                    </tr>
                `);
                if (typeof lucide !== 'undefined') lucide.createIcons();
                return;
            }

            $("#loadoman").html(`
                <tr>
                    <td colspan="6" class="py-8 text-center text-slate-400">
                        <div class="flex items-center justify-center gap-2">
                            <svg class="animate-spin h-5 w-5 text-brand-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            <span class="text-xs font-semibold">Mengambil data OMAN...</span>
                        </div>
                    </td>
                </tr>
            `);

            $("#loadoman").load("/oman/" + val + "/getoman", function() {
                $("#loadoman input.form-table").addClass("h-7 px-2 text-xs rounded border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:border-brand-500 font-mono-num");
                if (typeof lucide !== 'undefined') lucide.createIcons();
            });
        });

        $form.on("submit", function(e) {
            const kode_oman = $omanSelect.val();
            if (!kode_oman) {
                e.preventDefault();
                setFieldError($omanSelect, "Silakan pilih dokumen OMAN terlebih dahulu");
                $omanSelect.focus();
                return false;
            }

            // Double submit prevention
            $btnSubmit.prop('disabled', true).addClass('opacity-75 cursor-not-allowed pointer-events-none');
            $btnSubmit.html(`
                <svg class="animate-spin h-3.5 w-3.5 text-white shrink-0 inline-block" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <span>Menyimpan Permintaan Produksi...</span>
            `);
            return true;
        });
    });
</script>
