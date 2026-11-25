<form action="{{ route('permissions.store') }}" id="formcreatePermission" method="POST" class="space-y-2.5">
    @csrf

    <div>
        <label for="perm_name_create" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
            Nama Permission <span class="text-rose-500">*</span>
        </label>
        <div class="relative">
            <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                <i data-lucide="key-round" class="w-3.5 h-3.5"></i>
            </div>
            <input type="text" name="name" id="perm_name_create" placeholder="Contoh: penjualan.index"
                class="w-full h-[36px] pl-8 pr-3 text-xs font-semibold font-mono-num rounded-lg border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 placeholder:text-slate-400 focus:outline-hidden focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition">
        </div>
    </div>

    <div>
        <label for="id_permission_group_create" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
            Group Permission <span class="text-rose-500">*</span>
        </label>
        <div class="relative select2-container-v2">
            <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-20">
                <i data-lucide="folder" class="w-3.5 h-3.5"></i>
            </div>
            <select name="id_permission_group" id="id_permission_group_create" class="select2GroupCreate w-full">
                <option value="">Pilih Group Permission</option>
                @foreach ($permission_groups as $g)
                    <option value="{{ $g->id }}">{{ $g->name }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <div class="pt-3 border-t border-slate-200/80 dark:border-slate-800 flex items-center justify-end gap-2">
        <button type="button" data-bs-dismiss="modal"
            class="h-[36px] px-4 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 hover:bg-slate-50 dark:hover:bg-slate-700/60 text-slate-700 dark:text-slate-200 text-xs font-bold transition shadow-2xs cursor-pointer">
            Batal
        </button>
        <button type="submit" id="btnSimpanPerm"
            class="h-[36px] px-5 rounded-lg bg-[#284c9a] hover:bg-[#1e3a78] active:bg-[#152a57] text-white text-xs font-bold transition flex items-center justify-center gap-1.5 shadow-xs cursor-pointer">
            <i data-lucide="send" class="w-3.5 h-3.5"></i>
            <span>Simpan Permission</span>
        </button>
    </div>

</form>

<script>
    $(function() {
        if (typeof lucide !== 'undefined') lucide.createIcons();

        const form = $("#formcreatePermission");
        form.find('.select2GroupCreate').select2({ placeholder: 'Pilih Group Permission', allowClear: true, width: '100%' });

        function showInlineError($el, msg) {
            clearInlineError($el);
            $el.addClass("is-invalid border-rose-500 ring-1 ring-rose-500");
            const $rel = $el.closest(".relative");
            if ($rel.length) {
                $rel.find(".select2-selection").addClass("is-invalid !border-rose-500 !ring-1 !ring-rose-500");
                $rel.find(".select2-container").addClass("is-invalid");
                $rel.find("i, svg").addClass("text-rose-500");
                $rel.siblings(".field-error-msg").remove();
                $rel.after(`<p class="field-error-msg text-[11px] font-semibold text-rose-600 dark:text-rose-400 mt-1 pl-1 flex items-center gap-1"><i data-lucide="alert-circle" class="w-3.5 h-3.5 shrink-0"></i><span>${msg}</span></p>`);
            }
            if (typeof lucide !== 'undefined') lucide.createIcons();
            $el.focus();
        }

        function clearInlineError($el) {
            $el.removeClass("is-invalid border-rose-500 ring-1 ring-rose-500");
            const $rel = $el.closest(".relative");
            if ($rel.length) {
                $rel.find(".select2-selection").removeClass("is-invalid !border-rose-500 !ring-1 !ring-rose-500");
                $rel.find(".select2-container").removeClass("is-invalid");
                $rel.find("i, svg").removeClass("text-rose-500");
                $rel.siblings(".field-error-msg").remove();
            }
        }

        form.submit(function(e) {
            let hasError = false;
            if (!form.find("#perm_name_create").val().trim()) { showInlineError(form.find("#perm_name_create"), "Nama permission wajib diisi!"); hasError = true; }
            if (!form.find("#id_permission_group_create").val()) { showInlineError(form.find("#id_permission_group_create"), "Group permission wajib dipilih!"); hasError = true; }

            if (hasError) { e.preventDefault(); return false; }

            const $btn = form.find("#btnSimpanPerm");
            $btn.prop('disabled', true).addClass('opacity-75 cursor-not-allowed pointer-events-none');
            $btn.html(`<svg class="animate-spin h-3.5 w-3.5 text-white shrink-0 inline-block" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path></svg> <span>Menyimpan...</span>`);
        });

        form.on("input change", "input, select", function() { clearInlineError($(this)); });
        form.find("select").on("select2:select select2:clear", function(e) { clearInlineError($(e.target)); });
    });
</script>
