<form action="{{ route('penjualan.updatefakturbatal', Crypt::encrypt($penjualan->no_faktur)) }}" method="POST" id="formBatalkanfaktur" class="space-y-3.5" autocomplete="off">
    @csrf
    @method('PUT')

    <!-- EXECUTIVE SUMMARY STRIP -->
    <div class="rounded-xl border border-slate-200/80 dark:border-slate-800 p-3 sm:p-3.5 bg-slate-50/50 dark:bg-slate-900/40">
        <div class="flex items-center justify-between pb-2 mb-2.5 border-b border-slate-200/80 dark:border-slate-800">
            <div class="flex items-center gap-2">
                <div class="w-6 h-6 rounded-md bg-rose-50 dark:bg-rose-950/60 text-rose-600 dark:text-rose-400 flex items-center justify-center shrink-0">
                    <i data-lucide="file-x" class="w-3.5 h-3.5"></i>
                </div>
                <div>
                    <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 dark:text-slate-500 block leading-none">Konfirmasi Pembatalan</span>
                    <span class="font-bold font-mono-num text-rose-600 dark:text-rose-400 text-xs sm:text-sm">
                        {{ $penjualan->no_faktur }}
                    </span>
                </div>
            </div>
            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10.5px] font-bold bg-rose-50 dark:bg-rose-950/50 text-rose-700 dark:text-rose-400 border border-rose-200 dark:border-rose-900/50">
                <i data-lucide="alert-circle" class="w-3 h-3"></i>
                <span>Faktur Batal</span>
            </span>
        </div>

        <div class="grid grid-cols-2 sm:grid-cols-4 gap-2.5 text-xs">
            <div>
                <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 dark:text-slate-500 block">Tanggal Faktur</span>
                <span class="font-semibold text-slate-800 dark:text-slate-200">{{ DateToIndo($penjualan->tanggal) }}</span>
            </div>
            <div>
                <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 dark:text-slate-500 block">Kode Pelanggan</span>
                <span class="font-mono-num font-bold text-slate-800 dark:text-slate-200">{{ $penjualan->kode_pelanggan }}</span>
            </div>
            <div>
                <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 dark:text-slate-500 block">Nama Pelanggan</span>
                <span class="font-semibold text-slate-800 dark:text-slate-200 truncate block">{{ $penjualan->nama_pelanggan }}</span>
            </div>
            <div>
                <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 dark:text-slate-500 block">Jenis Transaksi</span>
                <span class="inline-flex items-center px-1.5 py-0.5 rounded font-bold text-[10.5px] {{ $penjualan->jenis_transaksi == 'T' ? 'bg-emerald-50 text-emerald-700 dark:bg-emerald-950/50 dark:text-emerald-400' : 'bg-amber-50 text-amber-700 dark:bg-amber-950/50 dark:text-amber-400' }}">
                    {{ $penjualan->jenis_transaksi == 'T' ? 'TUNAI' : 'KREDIT' }}
                </span>
            </div>
        </div>
    </div>

    <!-- FORM INPUT KETERANGAN -->
    <div class="rounded-xl border border-slate-200/80 dark:border-slate-800 p-3 sm:p-3.5 bg-slate-50/50 dark:bg-slate-900/40">
        <div class="text-xs font-bold text-slate-700 dark:text-slate-200 mb-2.5 flex items-center gap-1.5">
            <i data-lucide="message-square-warning" class="w-3.5 h-3.5 text-[#284c9a] dark:text-blue-400"></i>
            <span>Alasan Pembatalan Faktur</span>
        </div>

        <div class="field-group">
            <label for="keterangan_batal" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                Alasan Faktur Dibatalkan <span class="text-rose-500">*</span>
            </label>
            <div class="relative">
                <div class="input-prefix-icon absolute top-2.5 left-0 pl-2.5 flex items-start pointer-events-none text-slate-400 z-10 transition-colors">
                    <i data-lucide="align-left" class="w-3.5 h-3.5"></i>
                </div>
                <textarea name="keterangan" id="keterangan_batal" rows="3"
                    class="w-full pl-8 pr-3 py-2 text-xs font-medium rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 placeholder:text-slate-400 focus:outline-hidden focus:ring-2 focus:ring-rose-500/20 focus:border-rose-500 transition"
                    placeholder="Masukkan alasan lengkap faktur ini dibatalkan...">{{ $penjualan->keterangan }}</textarea>
            </div>
            <p class="field-error-msg hidden text-[11px] font-semibold text-rose-600 dark:text-rose-400 mt-1 pl-1 flex items-center gap-1">
                <i data-lucide="alert-circle" class="w-3 h-3 text-rose-500 inline shrink-0"></i>
                <span class="error-text">Alasan pembatalan harus diisi</span>
            </p>
        </div>
    </div>

    <!-- ACTION BUTTONS -->
    <div class="flex items-center justify-end gap-2 pt-1 border-t border-slate-100 dark:border-slate-800">
        <button type="button" class="btnCancel h-[34px] px-3.5 rounded-lg border border-slate-200 dark:border-slate-700 hover:bg-slate-100 dark:hover:bg-slate-800 text-slate-600 dark:text-slate-300 text-xs font-semibold transition cursor-pointer">
            Batal
        </button>
        <button type="submit" id="btnSubmitBatal"
            class="h-[34px] px-4 rounded-lg bg-rose-600 hover:bg-rose-700 active:bg-rose-800 text-white text-xs font-bold transition flex items-center gap-1.5 shadow-xs cursor-pointer">
            <i data-lucide="file-x" class="w-3.5 h-3.5"></i>
            <span>Ubah Ke Faktur Batal</span>
        </button>
    </div>
</form>

<script>
    $(function() {
        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }

        const $form = $("#formBatalkanfaktur");
        const $keterangan = $form.find("#keterangan_batal");
        const $btnSubmit = $form.find("#btnSubmitBatal");

        // Tutup modal via tombol Batal
        $form.find(".btnCancel").on('click', function() {
            $("#modalPenjualan").addClass("hidden").removeClass("flex");
        });

        // Live cleanup saat mengetik
        $keterangan.on('input change', function() {
            $(this).removeClass('border-rose-500 ring-1 ring-rose-500');
            $(this).closest('.field-group').find('.input-prefix-icon').removeClass('text-rose-500').addClass('text-slate-400');
            $(this).closest('.field-group').find('.field-error-msg').addClass('hidden');
        });

        $form.on('submit', function(e) {
            let hasError = false;
            const val = $keterangan.val().trim();

            if (!val) {
                hasError = true;
                $keterangan.addClass('border-rose-500 ring-1 ring-rose-500 focus:border-rose-500');
                $keterangan.closest('.field-group').find('.input-prefix-icon').addClass('text-rose-500').removeClass('text-slate-400');
                $keterangan.closest('.field-group').find('.field-error-msg').removeClass('hidden').find('.error-text').text('Alasan faktur dibatalkan wajib diisi!');
                $keterangan.focus();
            }

            if (hasError) {
                e.preventDefault();
                return false;
            }

            // Anti-double submit dengan spinner
            $btnSubmit.prop('disabled', true).addClass('opacity-75 cursor-not-allowed pointer-events-none');
            $btnSubmit.html(`
                <svg class="animate-spin h-3.5 w-3.5 text-white shrink-0 inline-block" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <span>Menyimpan...</span>
            `);
        });
    });
</script>
