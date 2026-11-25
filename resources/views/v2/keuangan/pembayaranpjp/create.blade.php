<form action="{{ route('pembayaranpjp.store') }}" method="POST" id="formBayarpjp" class="space-y-3.5" autocomplete="off">
    @csrf
    <input type="hidden" name="no_pinjaman" value="{{ $no_pinjaman }}">

    <!-- Tanggal -->
    <div class="space-y-1">
        <label for="tanggal_bayar_pjp" class="block text-xs font-bold text-slate-700 dark:text-slate-200">
            Tanggal Pembayaran <span class="text-rose-500">*</span>
        </label>
        <div class="relative">
            <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                <i data-lucide="calendar" class="w-4 h-4"></i>
            </div>
            <input type="text" id="tanggal_bayar_pjp" name="tanggal" value="{{ date('Y-m-d') }}" readonly placeholder="Pilih Tanggal"
                class="flatpickr-date w-full h-[36px] pl-9 pr-3 rounded-lg border border-slate-200/80 dark:border-slate-700 bg-white dark:bg-slate-800 text-xs font-semibold text-slate-800 dark:text-slate-100 placeholder:text-slate-400 focus:outline-hidden focus:border-[#284c9a] focus:ring-1 focus:ring-[#284c9a] transition">
        </div>
        <p id="error-tanggal-bayar-pjp" class="hidden text-[11px] font-semibold text-rose-600 dark:text-rose-400 mt-1 pl-1 items-center gap-1">
            <i data-lucide="alert-circle" class="w-3.5 h-3.5 shrink-0"></i>
            <span>Tanggal pembayaran wajib diisi</span>
        </p>
    </div>

    <!-- Jumlah -->
    <div class="space-y-1">
        <label for="jumlah_bayar_pjp" class="block text-xs font-bold text-slate-700 dark:text-slate-200">
            Jumlah Pembayaran <span class="text-rose-500">*</span>
        </label>
        <div class="relative">
            <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                <i data-lucide="dollar-sign" class="w-4 h-4"></i>
            </div>
            <input type="text" id="jumlah_bayar_pjp" name="jumlah" placeholder="0"
                class="money w-full h-[36px] pl-9 pr-3 text-right rounded-lg border border-slate-200/80 dark:border-slate-700 bg-white dark:bg-slate-800 text-xs font-bold text-slate-800 dark:text-slate-100 placeholder:text-slate-400 focus:outline-hidden focus:border-[#284c9a] focus:ring-1 focus:ring-[#284c9a] transition">
        </div>
        <p id="error-jumlah-bayar-pjp" class="hidden text-[11px] font-semibold text-rose-600 dark:text-rose-400 mt-1 pl-1 items-center gap-1">
            <i data-lucide="alert-circle" class="w-3.5 h-3.5 shrink-0"></i>
            <span>Jumlah pembayaran wajib diisi</span>
        </p>
    </div>

    <!-- Tombol Aksi Kanan -->
    <div class="flex items-center justify-end gap-2 pt-2 border-t border-slate-100 dark:border-slate-800">
        <button type="button" id="btnCancelBayarPjpModal"
            class="h-[36px] px-4 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 hover:bg-slate-100 dark:hover:bg-slate-700 text-slate-600 dark:text-slate-300 text-xs font-bold transition cursor-pointer">
            Batal
        </button>
        <button type="submit" id="btnSubmitBayarPjp"
            class="h-[36px] px-4 rounded-lg bg-[#284c9a] hover:bg-[#1e3a78] text-white text-xs font-bold transition flex items-center gap-1.5 shadow-xs cursor-pointer">
            <i data-lucide="send" class="w-3.5 h-3.5"></i>
            <span>Simpan Pembayaran</span>
        </button>
    </div>
</form>

<script>
    $(function() {
        if (typeof lucide !== 'undefined') lucide.createIcons();

        const $form = $("#formBayarpjp");

        $form.find(".flatpickr-date").flatpickr({
            dateFormat: "Y-m-d",
            allowInput: false
        });

        $form.find(".money").maskMoney({
            thousands: '.',
            decimal: ',',
            precision: 0
        });

        $form.find("#tanggal_bayar_pjp").on('input change', function() {
            $(this).removeClass('border-rose-500 ring-1 ring-rose-500');
            $form.find("#error-tanggal-bayar-pjp").addClass('hidden').removeClass('flex');
        });

        $form.find("#jumlah_bayar_pjp").on('input change', function() {
            $(this).removeClass('border-rose-500 ring-1 ring-rose-500');
            $form.find("#error-jumlah-bayar-pjp").addClass('hidden').removeClass('flex');
        });

        $form.find("#btnCancelBayarPjpModal").on('click', function() {
            if (typeof modalBayarPjpHelper !== 'undefined') {
                modalBayarPjpHelper.close();
            }
        });

        $form.on('submit', function(e) {
            e.preventDefault();
            let hasError = false;

            const tanggal = $form.find("#tanggal_bayar_pjp").val().trim();
            if (!tanggal) {
                $form.find("#error-tanggal-bayar-pjp").removeClass('hidden').addClass('flex');
                $form.find("#tanggal_bayar_pjp").addClass('border-rose-500 ring-1 ring-rose-500');
                hasError = true;
            }

            const jmlStr = $form.find("#jumlah_bayar_pjp").val();
            const jml = jmlStr ? parseInt(jmlStr.replace(/\./g, '')) || 0 : 0;
            if (jml <= 0) {
                $form.find("#error-jumlah-bayar-pjp").removeClass('hidden').addClass('flex');
                $form.find("#jumlah_bayar_pjp").addClass('border-rose-500 ring-1 ring-rose-500');
                hasError = true;
            }

            if (hasError) return false;

            const $btn = $form.find("#btnSubmitBayarPjp");
            $btn.prop('disabled', true).addClass('opacity-75 cursor-not-allowed pointer-events-none');
            $btn.html(`
                <svg class="animate-spin h-3.5 w-3.5 text-white shrink-0 inline-block" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <span>Menyimpan...</span>
            `);

            this.submit();
        });
    });
</script>
