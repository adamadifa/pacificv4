<form action="{{ route('ticketupdate.store') }}" method="POST" id="formTicketUpdateCreate" class="space-y-3">
    @csrf
    <div class="grid grid-cols-1 md:grid-cols-2 gap-2.5 items-start">
        <div>
            <label for="tanggal_tu_create" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                Tanggal <span class="text-rose-500">*</span>
            </label>
            <div class="relative">
                <div class="field-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                    <i data-lucide="calendar" class="w-3.5 h-3.5"></i>
                </div>
                <input type="text" name="tanggal" id="tanggal_tu_create" value="{{ date('Y-m-d') }}" readonly class="flatpickr-date w-full h-[36px] pl-8 pr-3 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-lg text-xs font-semibold text-slate-800 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-[#284c9a]/20 focus:border-[#284c9a]" placeholder="Pilih Tanggal">
            </div>
        </div>

        <div>
            <label for="kategori_tu_create" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                Kategori <span class="text-rose-500">*</span>
            </label>
            <div class="relative">
                <div class="field-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                    <i data-lucide="tags" class="w-3.5 h-3.5"></i>
                </div>
                <select name="kategori" id="kategori_tu_create" class="w-full h-[36px] pl-8 pr-3 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-lg text-xs font-semibold text-slate-800 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-[#284c9a]/20 focus:border-[#284c9a]">
                    <option value="">Pilih Kategori</option>
                    <option value="1">Penjualan</option>
                    <option value="2">Pembayaran</option>
                    <option value="3">Retur</option>
                    <option value="4">DPB</option>
                    <option value="5">Mutasi Persediaan</option>
                </select>
            </div>
        </div>
    </div>

    <div>
        <label for="no_bukti_tu_create" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
            No. Bukti <span class="text-rose-500">*</span>
        </label>
        <div class="relative">
            <div class="field-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                <i data-lucide="barcode" class="w-3.5 h-3.5"></i>
            </div>
            <input type="text" name="no_bukti" id="no_bukti_tu_create" class="w-full h-[36px] pl-8 pr-3 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-lg text-xs font-semibold text-slate-800 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-[#284c9a]/20 focus:border-[#284c9a]" placeholder="Masukkan nomor bukti / faktur">
        </div>
    </div>

    <div>
        <label for="keterangan_tu_create" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
            Keterangan <span class="text-rose-500">*</span>
        </label>
        <div class="relative">
            <div class="field-icon absolute top-2.5 left-0 pl-2.5 flex items-start pointer-events-none text-slate-400 z-10">
                <i data-lucide="align-left" class="w-3.5 h-3.5"></i>
            </div>
            <textarea name="keterangan" id="keterangan_tu_create" rows="3" class="w-full pl-8 pr-3 py-2 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-lg text-xs font-medium text-slate-800 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-[#284c9a]/20 focus:border-[#284c9a]" placeholder="Jelaskan alasan & detail perubahan data..."></textarea>
        </div>
    </div>

    <div>
        <label for="link_tu_create" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
            Link Lampiran Dokumen (Opsional)
        </label>
        <div class="relative">
            <div class="field-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                <i data-lucide="link" class="w-3.5 h-3.5"></i>
            </div>
            <input type="text" name="link" id="link_tu_create" class="w-full h-[36px] pl-8 pr-3 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-lg text-xs font-semibold text-slate-800 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-[#284c9a]/20 focus:border-[#284c9a]" placeholder="https://...">
        </div>
    </div>

    <div class="flex items-center justify-end gap-2 pt-2 border-t border-slate-100 dark:border-slate-800">
        <button type="button" data-bs-dismiss="modal" class="h-[36px] px-4 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-xs font-bold text-slate-600 dark:text-slate-300 hover:bg-slate-50 transition cursor-pointer">Batal</button>
        <button type="submit" id="btnSimpanTuCreate" class="h-[36px] px-4 rounded-lg bg-[#284c9a] hover:bg-[#1e3a78] text-white text-xs font-bold flex items-center gap-1.5 transition cursor-pointer">
            <i data-lucide="ticket" class="w-3.5 h-3.5"></i>
            <span>Buat Ticket</span>
        </button>
    </div>
</form>

<script>
    $(function() {
        const $form = $('#formTicketUpdateCreate');
        $form.find('.flatpickr-date').flatpickr({ dateFormat: 'Y-m-d' });

        function clearErr($el) {
            $el.removeClass('border-rose-500 ring-1 ring-rose-500 focus:border-rose-500 focus:ring-rose-500/20');
            $el.closest('.relative').find('.field-icon').removeClass('text-rose-500').addClass('text-slate-400');
            $el.closest('.relative').parent().find('.field-error-msg').remove();
        }

        function setErr($el, msg) {
            clearErr($el);
            $el.addClass('border-rose-500 ring-1 ring-rose-500 focus:border-rose-500 focus:ring-rose-500/20');
            $el.closest('.relative').find('.field-icon').removeClass('text-slate-400').addClass('text-rose-500');
            $el.closest('.relative').after(`<p class="field-error-msg text-[11px] font-semibold text-rose-600 dark:text-rose-400 mt-1 pl-1 flex items-center gap-1"><svg class="w-3 h-3 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg><span>${msg}</span></p>`);
        }

        $form.find('input, select, textarea').on('input change', function() {
            clearErr($(this));
        });

        $form.on('submit', function(e) {
            const $tgl = $form.find('#tanggal_tu_create');
            const $kat = $form.find('#kategori_tu_create');
            const $bukti = $form.find('#no_bukti_tu_create');
            const $ket = $form.find('#keterangan_tu_create');
            let firstErr = null;

            if (!$tgl.val()) { setErr($tgl, 'Tanggal harus diisi!'); firstErr = firstErr || $tgl; }
            if (!$kat.val()) { setErr($kat, 'Kategori harus diisi!'); firstErr = firstErr || $kat; }
            if (!$bukti.val().trim()) { setErr($bukti, 'No. Bukti harus diisi!'); firstErr = firstErr || $bukti; }
            if (!$ket.val().trim()) { setErr($ket, 'Keterangan harus diisi!'); firstErr = firstErr || $ket; }

            if (firstErr) {
                e.preventDefault();
                firstErr.focus();
                return false;
            }

            const $btn = $form.find('#btnSimpanTuCreate');
            $btn.prop('disabled', true).addClass('opacity-75 cursor-not-allowed pointer-events-none')
                .html('<svg class="animate-spin h-3.5 w-3.5 text-white shrink-0 inline-block" viewBox="0 0 24 24" fill="none"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"/></svg><span>Menyimpan...</span>');
        });
    });
</script>
