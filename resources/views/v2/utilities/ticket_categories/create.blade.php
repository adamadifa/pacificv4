<form action="{{ route('ticketcategory.store') }}" method="POST" id="formCategoryCreate" enctype="multipart/form-data" class="space-y-3">
    @csrf
    <div class="grid grid-cols-1 md:grid-cols-2 gap-2.5 items-start">
        <div>
            <label for="kode_kategori_create" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                Kode Kategori <span class="text-rose-500">*</span>
            </label>
            <div class="relative">
                <div class="field-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                    <i data-lucide="tag" class="w-3.5 h-3.5"></i>
                </div>
                <input type="text" name="kode_kategori" id="kode_kategori_create" class="w-full h-[36px] pl-8 pr-3 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-lg text-xs font-semibold text-slate-800 dark:text-slate-100 uppercase focus:outline-none focus:ring-2 focus:ring-[#284c9a]/20 focus:border-[#284c9a]" placeholder="Contoh: PERBAIKAN">
            </div>
        </div>
        <div>
            <label for="nama_kategori_create" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                Nama Kategori Tiket <span class="text-rose-500">*</span>
            </label>
            <div class="relative">
                <div class="field-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                    <i data-lucide="file-text" class="w-3.5 h-3.5"></i>
                </div>
                <input type="text" name="nama_kategori" id="nama_kategori_create" class="w-full h-[36px] pl-8 pr-3 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-lg text-xs font-semibold text-slate-800 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-[#284c9a]/20 focus:border-[#284c9a]" placeholder="Masukkan nama kategori">
            </div>
        </div>
    </div>

    <div>
        <label for="keterangan_create" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
            Keterangan / Deskripsi Kategori
        </label>
        <div class="relative">
            <div class="field-icon absolute top-2.5 left-0 pl-2.5 flex items-start pointer-events-none text-slate-400 z-10">
                <i data-lucide="align-left" class="w-3.5 h-3.5"></i>
            </div>
            <textarea name="keterangan" id="keterangan_create" rows="2" class="w-full pl-8 pr-3 py-2 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-lg text-xs font-medium text-slate-800 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-[#284c9a]/20 focus:border-[#284c9a]" placeholder="Deskripsi singkat kategori tiket (opsional)"></textarea>
        </div>
    </div>

    <div class="rounded-xl bg-slate-50 dark:bg-slate-800/40 border border-slate-200/80 dark:border-slate-800 p-3 sm:p-3.5">
        <div class="flex items-center gap-1.5 mb-2.5">
            <i data-lucide="git-branch" class="w-3.5 h-3.5 text-[#284c9a] dark:text-blue-400"></i>
            <h4 class="text-xs font-bold text-slate-700 dark:text-slate-200">Pengaturan Level Approval</h4>
        </div>
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-2.5">
            <label class="flex items-center gap-2 p-2 rounded-lg bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 cursor-pointer">
                <input type="checkbox" id="perlu_manager_dept_create" name="perlu_manager_dept" value="1" checked class="w-3.5 h-3.5 rounded text-[#284c9a] focus:ring-[#284c9a]">
                <span class="text-xs font-bold text-slate-700 dark:text-slate-200">Manager Dept</span>
            </label>
            <label class="flex items-center gap-2 p-2 rounded-lg bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 cursor-pointer">
                <input type="checkbox" id="perlu_smm_create" name="perlu_smm" value="1" checked class="w-3.5 h-3.5 rounded text-[#284c9a] focus:ring-[#284c9a]">
                <span class="text-xs font-bold text-slate-700 dark:text-slate-200">SMM (Cabang)</span>
            </label>
            <label class="flex items-center gap-2 p-2 rounded-lg bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 cursor-pointer">
                <input type="checkbox" id="perlu_rsm_create" name="perlu_rsm" value="1" checked class="w-3.5 h-3.5 rounded text-[#284c9a] focus:ring-[#284c9a]">
                <span class="text-xs font-bold text-slate-700 dark:text-slate-200">RSM (Regional)</span>
            </label>
            <label class="flex items-center gap-2 p-2 rounded-lg bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 cursor-pointer">
                <input type="checkbox" id="perlu_gm_create" name="perlu_gm" value="1" class="w-3.5 h-3.5 rounded text-[#284c9a] focus:ring-[#284c9a]">
                <span class="text-xs font-bold text-slate-700 dark:text-slate-200">GM (Pusat)</span>
            </label>
        </div>
    </div>

    <div class="rounded-xl bg-slate-50 dark:bg-slate-800/40 border border-slate-200/80 dark:border-slate-800 p-3 sm:p-3.5 space-y-2.5">
        <div class="flex items-center gap-1.5 mb-2.5">
            <i data-lucide="paperclip" class="w-3.5 h-3.5 text-[#284c9a] dark:text-blue-400"></i>
            <h4 class="text-xs font-bold text-slate-700 dark:text-slate-200">Pengaturan Lampiran & Format Template</h4>
        </div>
        <label class="flex items-center gap-2 cursor-pointer">
            <input type="checkbox" id="wajib_lampiran_create" name="wajib_lampiran" value="1" class="w-3.5 h-3.5 rounded text-rose-600 focus:ring-rose-500">
            <span class="text-xs font-bold text-rose-600 dark:text-rose-400">Wajib Upload File Lampiran saat Pengajuan Tiket</span>
        </label>
        <div>
            <label for="template_file_create" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                Upload File Format Template (.xlsx / .pdf / .docx)
            </label>
            <input type="file" name="template_file" id="template_file_create" accept=".xlsx,.xls,.pdf,.doc,.docx" class="w-full text-xs text-slate-600 dark:text-slate-300 file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-bold file:bg-[#284c9a] file:text-white hover:file:bg-[#1e3a78] bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-lg cursor-pointer">
            <p class="text-[11px] text-slate-400 mt-1">User akan dapat mengunduh format ini saat mengajukan tiket jenis ini.</p>
        </div>
    </div>

    <label class="flex items-center gap-2 cursor-pointer">
        <input type="checkbox" id="is_active_create" name="is_active" value="1" checked class="w-3.5 h-3.5 rounded text-[#284c9a] focus:ring-[#284c9a]">
        <span class="text-xs font-bold text-slate-700 dark:text-slate-200">Status Aktif</span>
    </label>

    <div class="flex items-center justify-end gap-2 pt-2 border-t border-slate-100 dark:border-slate-800">
        <button type="button" data-bs-dismiss="modal" class="h-[36px] px-4 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-xs font-bold text-slate-600 dark:text-slate-300 hover:bg-slate-50 transition cursor-pointer">Batal</button>
        <button type="submit" id="btnSimpanCategory" class="h-[36px] px-4 rounded-lg bg-[#284c9a] hover:bg-[#1e3a78] text-white text-xs font-bold flex items-center gap-1.5 transition cursor-pointer">
            <i data-lucide="save" class="w-3.5 h-3.5"></i>
            <span>Simpan Master Kategori</span>
        </button>
    </div>
</form>

<script>
    $(function() {
        const $form = $('#formCategoryCreate');

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

        $form.find('input, textarea').on('input change', function() {
            clearErr($(this));
        });

        $form.on('submit', function(e) {
            const $kode = $form.find('#kode_kategori_create');
            const $nama = $form.find('#nama_kategori_create');
            let firstErr = null;

            if (!$kode.val().trim()) {
                setErr($kode, 'Kode kategori wajib diisi!');
                firstErr = firstErr || $kode;
            }
            if (!$nama.val().trim()) {
                setErr($nama, 'Nama kategori wajib diisi!');
                firstErr = firstErr || $nama;
            }

            if (firstErr) {
                e.preventDefault();
                firstErr.focus();
                return false;
            }

            const $btn = $form.find('#btnSimpanCategory');
            $btn.prop('disabled', true).addClass('opacity-75 cursor-not-allowed pointer-events-none')
                .html('<svg class="animate-spin h-3.5 w-3.5 text-white shrink-0 inline-block" viewBox="0 0 24 24" fill="none"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"/></svg><span>Menyimpan...</span>');
        });
    });
</script>
