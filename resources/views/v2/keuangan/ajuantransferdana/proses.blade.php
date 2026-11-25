<form action="{{ route('ajuantransfer.prosesstore', Crypt::encrypt($ajuantransfer->no_pengajuan)) }}" id="formProsesajuantransferdana" method="POST" class="space-y-3.5" autocomplete="off">
    @csrf

    <!-- Summary Box Detail Ajuan -->
    <div class="rounded-xl border border-slate-200/80 dark:border-slate-800 bg-slate-50/60 dark:bg-slate-900/40 p-3 sm:p-3.5 shadow-2xs space-y-2.5">
        <div class="flex items-center justify-between pb-2 border-b border-slate-200/80 dark:border-slate-800">
            <span class="text-xs font-bold text-slate-800 dark:text-slate-100 font-mono-num">{{ $ajuantransfer->no_pengajuan }}</span>
            <span class="text-xs font-bold font-mono-num text-[#284c9a] dark:text-blue-400">{{ DateToIndo($ajuantransfer->tanggal) }}</span>
        </div>
        <div class="grid grid-cols-2 sm:grid-cols-3 gap-2.5 text-xs">
            <div>
                <span class="text-[10px] text-slate-400 font-medium block">Penerima</span>
                <span class="font-bold text-slate-800 dark:text-slate-200 block truncate">{{ textUpperCase($ajuantransfer->nama) }}</span>
            </div>
            <div>
                <span class="text-[10px] text-slate-400 font-medium block">Bank / Rekening</span>
                <span class="font-bold text-slate-800 dark:text-slate-200 block">{{ textUpperCase($ajuantransfer->nama_bank) }}</span>
                <span class="text-[10px] text-slate-400 font-mono-num block">{{ $ajuantransfer->no_rekening ?: '-' }}</span>
            </div>
            <div>
                <span class="text-[10px] text-slate-400 font-medium block">Jumlah Transfer</span>
                <span class="text-sm font-black font-mono-num text-[#284c9a] dark:text-blue-400 block">Rp {{ formatAngka($ajuantransfer->jumlah) }}</span>
            </div>
            <div class="sm:col-span-3 pt-1 border-t border-slate-200/60 dark:border-slate-800">
                <span class="text-[10px] text-slate-400 font-medium block">Cabang & Keterangan</span>
                <div class="flex items-center gap-1.5 mt-0.5">
                    <span class="px-1.5 py-0.5 rounded text-[10px] font-bold bg-blue-100 text-blue-800 dark:bg-blue-950/60 dark:text-blue-300">{{ textUpperCase($ajuantransfer->nama_cabang) }}</span>
                    <span class="text-slate-700 dark:text-slate-300 font-medium">{{ $ajuantransfer->keterangan }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Link Bukti Input -->
    <div class="space-y-1">
        <label for="link_bukti_proses" class="block text-xs font-bold text-slate-700 dark:text-slate-200">
            Link Bukti Transfer <span class="text-rose-500">*</span>
        </label>
        <div class="relative">
            <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                <i data-lucide="link" class="w-4 h-4"></i>
            </div>
            <input type="text" id="link_bukti_proses" name="bukti" placeholder="https://..."
                class="w-full h-[36px] pl-9 pr-3 rounded-lg border border-slate-200/80 dark:border-slate-700 bg-white dark:bg-slate-800 text-xs font-medium text-slate-800 dark:text-slate-100 placeholder:text-slate-400 focus:outline-hidden focus:border-[#284c9a] focus:ring-1 focus:ring-[#284c9a] transition">
        </div>
        <p id="error-link-bukti-proses" class="hidden text-[11px] font-semibold text-rose-600 dark:text-rose-400 mt-1 pl-1 items-center gap-1">
            <i data-lucide="alert-circle" class="w-3.5 h-3.5 shrink-0"></i>
            <span>Link bukti transfer wajib diisi</span>
        </p>
    </div>

    <!-- Tombol Aksi (Right-Aligned) -->
    <div class="flex items-center justify-end gap-2 pt-2 border-t border-slate-100 dark:border-slate-800">
        <button type="button" id="btnCancelProses"
            class="h-[34px] px-3.5 rounded-lg border border-slate-200 dark:border-slate-700 hover:bg-slate-100 dark:hover:bg-slate-800 text-slate-600 dark:text-slate-300 text-xs font-bold transition cursor-pointer">
            Batal
        </button>
        <button type="submit" id="btnSubmitProses"
            class="h-[34px] px-4 rounded-lg bg-[#284c9a] hover:bg-[#1e3a78] text-white text-xs font-bold transition shadow-xs cursor-pointer inline-flex items-center gap-1.5">
            <i data-lucide="send" class="w-3.5 h-3.5"></i>
            <span>Proses Transfer</span>
        </button>
    </div>
</form>

<script>
    $(function() {
        const $form = $("#formProsesajuantransferdana");

        if (typeof lucide !== 'undefined') lucide.createIcons();

        function clearError($input, $error) {
            $input.removeClass('border-rose-500 ring-1 ring-rose-500 focus:border-rose-500 focus:ring-rose-500/20');
            $input.closest('.relative').find('i').removeClass('text-rose-500');
            $error.addClass('hidden').removeClass('flex');
        }

        function showError($input, $error) {
            $input.addClass('border-rose-500 ring-1 ring-rose-500 focus:border-rose-500 focus:ring-rose-500/20');
            $input.closest('.relative').find('i').addClass('text-rose-500');
            $error.removeClass('hidden').addClass('flex');
            if (typeof lucide !== 'undefined') lucide.createIcons();
        }

        $form.find("#link_bukti_proses").on('input change', function() {
            clearError($(this), $form.find("#error-link-bukti-proses"));
        });

        $form.find("#btnCancelProses").on('click', function() {
            if (typeof modalAjuantransfer !== 'undefined') {
                modalAjuantransfer.close();
            } else {
                $("#modalAjuantransfer").addClass('hidden');
                $('body').removeClass('overflow-hidden');
            }
        });

        $form.on('submit', function(e) {
            e.preventDefault();
            const bukti = $form.find("#link_bukti_proses").val().trim();

            if (!bukti) {
                showError($form.find("#link_bukti_proses"), $form.find("#error-link-bukti-proses"));
                $form.find("#link_bukti_proses").focus();
                return false;
            }

            const $btn = $form.find("#btnSubmitProses");
            $btn.prop('disabled', true).addClass('opacity-75 cursor-not-allowed pointer-events-none');
            $btn.html(`
                <svg class="animate-spin h-3.5 w-3.5 text-white shrink-0 inline-block" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <span>Memproses...</span>
            `);

            this.submit();
        });
    });
</script>
