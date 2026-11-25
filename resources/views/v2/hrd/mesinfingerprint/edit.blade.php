<form action="{{ route('mesinfingerprint.update', Crypt::encrypt($mesin->id)) }}" method="POST" id="formMesinEdit" class="space-y-2.5">
    @csrf
    @method('PUT')

    <!-- Nama Mesin -->
    <div>
        <label for="nama_mesin_edit" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
            Nama Mesin <span class="text-rose-500">*</span>
        </label>
        <div class="relative">
            <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                <i data-lucide="laptop" class="w-3.5 h-3.5"></i>
            </div>
            <input type="text" name="nama_mesin" id="nama_mesin_edit" value="{{ $mesin->nama_mesin }}" placeholder="Masukkan Nama Mesin"
                class="w-full h-[36px] pl-8 pr-3 text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 placeholder:text-slate-400 focus:outline-hidden focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition">
        </div>
    </div>

    <!-- Serial Number -->
    <div>
        <label for="sn_edit" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
            Serial Number (SN) <span class="text-rose-500">*</span>
        </label>
        <div class="relative">
            <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                <i data-lucide="barcode" class="w-3.5 h-3.5"></i>
            </div>
            <input type="text" name="sn" id="sn_edit" value="{{ $mesin->sn }}" placeholder="Masukkan Serial Number"
                class="w-full h-[36px] pl-8 pr-3 text-xs font-semibold font-mono-num rounded-lg border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 placeholder:text-slate-400 focus:outline-hidden focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition">
        </div>
    </div>

    <!-- Status -->
    <div>
        <label for="status_edit" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
            Status
        </label>
        <div class="relative">
            <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                <i data-lucide="power" class="w-3.5 h-3.5"></i>
            </div>
            <select name="status" id="status_edit"
                class="w-full h-[36px] pl-8 pr-8 text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition appearance-none cursor-pointer">
                <option value="Aktif" {{ $mesin->status == 'Aktif' ? 'selected' : '' }}>Aktif</option>
                <option value="Nonaktif" {{ $mesin->status == 'Nonaktif' ? 'selected' : '' }}>Nonaktif</option>
            </select>
            <div class="absolute inset-y-0 right-0 pr-2.5 flex items-center pointer-events-none text-slate-400">
                <i data-lucide="chevron-down" class="w-3 h-3"></i>
            </div>
        </div>
    </div>

    <!-- Titik Koordinat -->
    <div>
        <label for="titik_koordinat_edit" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
            Titik Koordinat (Contoh: -6.123, 106.123)
        </label>
        <div class="relative">
            <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                <i data-lucide="map-pin" class="w-3.5 h-3.5"></i>
            </div>
            <input type="text" name="titik_koordinat" id="titik_koordinat_edit" value="{{ $mesin->titik_koordinat }}" placeholder="Opsional"
                class="w-full h-[36px] pl-8 pr-3 text-xs font-semibold font-mono-num rounded-lg border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 placeholder:text-slate-400 focus:outline-hidden focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition">
        </div>
    </div>

    <!-- Modal Footer Actions -->
    <div class="pt-3 border-t border-slate-200/80 dark:border-slate-800 flex items-center justify-end gap-2">
        <button type="button" data-bs-dismiss="modal"
            class="h-[36px] px-4 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 hover:bg-slate-50 dark:hover:bg-slate-700/60 text-slate-700 dark:text-slate-200 text-xs font-bold transition shadow-2xs cursor-pointer">
            Batal
        </button>
        <button type="submit" id="btnUpdateMesin"
            class="h-[36px] px-5 rounded-lg bg-[#284c9a] hover:bg-[#1e3a78] active:bg-[#152a57] text-white text-xs font-bold transition flex items-center justify-center gap-1.5 shadow-xs cursor-pointer">
            <i data-lucide="save" class="w-3.5 h-3.5"></i>
            <span>Perbarui Mesin</span>
        </button>
    </div>

</form>

<script>
    $(function() {
        if (typeof lucide !== 'undefined') lucide.createIcons();

        const form = $("#formMesinEdit");

        function showInlineError($el, msg) {
            clearInlineError($el);
            $el.addClass("is-invalid border-rose-500 ring-1 ring-rose-500");
            const $rel = $el.closest(".relative");
            if ($rel.length) {
                $rel.find("i, svg").addClass("text-rose-500");
                $rel.siblings(".field-error-msg").remove();
                $rel.parent().find("> .field-error-msg").remove();
                $rel.after(`
                    <p class="field-error-msg text-[11px] font-semibold text-rose-600 dark:text-rose-400 mt-1 pl-1 flex items-center gap-1">
                        <i data-lucide="alert-circle" class="w-3.5 h-3.5 shrink-0"></i>
                        <span>${msg}</span>
                    </p>
                `);
            }
            if (typeof lucide !== 'undefined') lucide.createIcons();
            $el.focus();
        }

        function clearInlineError($el) {
            $el.removeClass("is-invalid border-rose-500 ring-1 ring-rose-500");
            const $rel = $el.closest(".relative");
            if ($rel.length) {
                $rel.find("i, svg").removeClass("text-rose-500");
                $rel.siblings(".field-error-msg").remove();
                $rel.parent().find("> .field-error-msg").remove();
            }
        }

        form.submit(function(e) {
            const nama_mesin = form.find("#nama_mesin_edit").val().trim();
            const sn = form.find("#sn_edit").val().trim();
            let hasError = false;

            if (!nama_mesin) {
                showInlineError(form.find("#nama_mesin_edit"), "Nama mesin wajib diisi!");
                hasError = true;
            }
            if (!sn) {
                showInlineError(form.find("#sn_edit"), "Serial Number (SN) wajib diisi!");
                hasError = true;
            }

            if (hasError) {
                e.preventDefault();
                return false;
            }

            const $btn = form.find("#btnUpdateMesin");
            $btn.prop('disabled', true).addClass('opacity-75 cursor-not-allowed pointer-events-none');
            $btn.html(`
                <svg class="animate-spin h-3.5 w-3.5 text-white shrink-0 inline-block" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                </svg>
                <span>Memperbarui...</span>
            `);
        });

        form.on("input change", "input, select", function() {
            clearInlineError($(this));
        });
    });
</script>
