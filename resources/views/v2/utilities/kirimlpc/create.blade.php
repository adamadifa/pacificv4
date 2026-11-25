<form action="{{ route('kirimlpc.store') }}" method="POST" id="formKirimlpc" enctype="multipart/form-data" class="space-y-2.5">
    @csrf
    <input type="hidden" id="cektutuplaporan">

    @hasanyrole($roles_show_cabang)
        <div>
            <label for="kode_cabang_lpc" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                Cabang <span class="text-rose-500">*</span>
            </label>
            <div class="relative select2-container-v2">
                <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-20">
                    <i data-lucide="building" class="w-3.5 h-3.5"></i>
                </div>
                <select name="kode_cabang" id="kode_cabang_lpc" class="select2KodecabangLPC w-full">
                    <option value="">Pilih Cabang</option>
                    @foreach ($cabang as $d)
                        <option value="{{ $d->kode_cabang }}">{{ textuppercase($d->nama_cabang) }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    @endhasanyrole

    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2.5 items-start">
        <!-- Bulan -->
        <div>
            <label for="bulan_lpc_create" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                Bulan <span class="text-rose-500">*</span>
            </label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                    <i data-lucide="calendar-days" class="w-3.5 h-3.5"></i>
                </div>
                <select name="bulan" id="bulan_lpc_create"
                    class="w-full h-[36px] pl-8 pr-8 text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition appearance-none cursor-pointer">
                    <option value="">Pilih Bulan</option>
                    @foreach ($list_bulan as $d)
                        <option value="{{ $d['kode_bulan'] }}">{{ $d['nama_bulan'] }}</option>
                    @endforeach
                </select>
                <div class="absolute inset-y-0 right-0 pr-2.5 flex items-center pointer-events-none text-slate-400">
                    <i data-lucide="chevron-down" class="w-3 h-3"></i>
                </div>
            </div>
        </div>

        <!-- Tahun -->
        <div>
            <label for="tahun_lpc_create" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                Tahun <span class="text-rose-500">*</span>
            </label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                    <i data-lucide="calendar" class="w-3.5 h-3.5"></i>
                </div>
                <select name="tahun" id="tahun_lpc_create"
                    class="w-full h-[36px] pl-8 pr-8 text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition appearance-none cursor-pointer">
                    <option value="">Pilih Tahun</option>
                    @for ($t = $start_year; $t <= date('Y'); $t++)
                        <option value="{{ $t }}" {{ $t == date('Y') ? 'selected' : '' }}>{{ $t }}</option>
                    @endfor
                </select>
                <div class="absolute inset-y-0 right-0 pr-2.5 flex items-center pointer-events-none text-slate-400">
                    <i data-lucide="chevron-down" class="w-3 h-3"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2.5 items-start">
        <!-- Tanggal Kirim -->
        <div>
            <label for="tanggal_lpc_create" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                Tanggal Kirim <span class="text-rose-500">*</span>
            </label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                    <i data-lucide="calendar" class="w-3.5 h-3.5"></i>
                </div>
                <input type="text" name="tanggal" id="tanggal_lpc_create" placeholder="Pilih Tanggal" readonly
                    class="flatpickr-date w-full h-[36px] pl-8 pr-3 text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 placeholder:text-slate-400 focus:outline-hidden focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition cursor-pointer">
            </div>
        </div>

        <!-- Jam Kirim -->
        <div>
            <label for="jam_kirim_lpc" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                Jam Kirim (00:00) <span class="text-rose-500">*</span>
            </label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                    <i data-lucide="clock" class="w-3.5 h-3.5"></i>
                </div>
                <input type="text" name="jam_kirim" id="jam_kirim_lpc" placeholder="00:00"
                    class="w-full h-[36px] pl-8 pr-3 text-xs font-semibold font-mono-num rounded-lg border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 placeholder:text-slate-400 focus:outline-hidden focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition">
            </div>
        </div>
    </div>

    <!-- Lampiran Foto -->
    <div>
        <label for="foto_lpc" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
            Lampiran / Bukti Kirim (Foto)
        </label>
        <input type="file" name="foto" id="foto_lpc" accept="image/*"
            class="block w-full text-xs text-slate-600 dark:text-slate-300 file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-bold file:bg-blue-50 file:text-[#284c9a] hover:file:bg-blue-100 dark:file:bg-blue-950/60 dark:file:text-blue-300 border border-slate-200 dark:border-slate-800 rounded-lg cursor-pointer bg-white dark:bg-slate-900">
    </div>

    <!-- Modal Footer Actions (Right-Aligned) -->
    <div class="pt-3 border-t border-slate-200/80 dark:border-slate-800 flex items-center justify-end gap-2">
        <button type="button" data-bs-dismiss="modal"
            class="h-[36px] px-4 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 hover:bg-slate-50 dark:hover:bg-slate-700/60 text-slate-700 dark:text-slate-200 text-xs font-bold transition shadow-2xs cursor-pointer">
            Batal
        </button>
        <button type="submit" id="btnSimpanLPC"
            class="h-[36px] px-5 rounded-lg bg-[#284c9a] hover:bg-[#1e3a78] active:bg-[#152a57] text-white text-xs font-bold transition flex items-center justify-center gap-1.5 shadow-xs cursor-pointer">
            <i data-lucide="send" class="w-3.5 h-3.5"></i>
            <span>Kirim LPC</span>
        </button>
    </div>

</form>

<script>
    $(function() {
        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }

        const form = $("#formKirimlpc");
        if ($.fn.mask) {
            form.find("#jam_kirim_lpc").mask("99:99");
        }

        if (typeof flatpickr !== 'undefined') {
            form.find(".flatpickr-date").flatpickr({
                dateFormat: "Y-m-d",
                defaultDate: new Date()
            });
        }

        form.find('.select2KodecabangLPC').select2({ placeholder: 'Pilih Cabang', allowClear: true, width: '100%' });

        function showInlineError($el, msg) {
            clearInlineError($el);
            $el.addClass("is-invalid border-rose-500 ring-1 ring-rose-500");
            const $rel = $el.closest(".relative");
            if ($rel.length) {
                $rel.find(".select2-selection").addClass("is-invalid !border-rose-500 !ring-1 !ring-rose-500");
                $rel.find(".select2-container").addClass("is-invalid");
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
                $rel.find(".select2-selection").removeClass("is-invalid !border-rose-500 !ring-1 !ring-rose-500");
                $rel.find(".select2-container").removeClass("is-invalid");
                $rel.find("i, svg").removeClass("text-rose-500");
                $rel.siblings(".field-error-msg").remove();
                $rel.parent().find("> .field-error-msg").remove();
            }
        }

        function buttonDisable() {
            const $btn = form.find("#btnSimpanLPC");
            $btn.prop('disabled', true).addClass('opacity-75 cursor-not-allowed pointer-events-none');
            $btn.html(`
                <svg class="animate-spin h-3.5 w-3.5 text-white shrink-0 inline-block" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                </svg>
                <span>Mengirim...</span>
            `);
        }

        form.submit(function(e) {
            const $cabang = form.find("#kode_cabang_lpc");
            const bulan = form.find("#bulan_lpc_create").val();
            const tahun = form.find("#tahun_lpc_create").val();
            const tanggal = form.find("#tanggal_lpc_create").val().trim();
            const jam_kirim = form.find("#jam_kirim_lpc").val().trim();

            let hasError = false;

            if ($cabang.length && !$cabang.val()) {
                showInlineError($cabang, "Cabang wajib dipilih!");
                hasError = true;
            }
            if (!bulan) {
                showInlineError(form.find("#bulan_lpc_create"), "Bulan wajib dipilih!");
                hasError = true;
            }
            if (!tahun) {
                showInlineError(form.find("#tahun_lpc_create"), "Tahun wajib dipilih!");
                hasError = true;
            }
            if (!tanggal) {
                showInlineError(form.find("#tanggal_lpc_create"), "Tanggal kirim wajib diisi!");
                hasError = true;
            }
            if (!jam_kirim) {
                showInlineError(form.find("#jam_kirim_lpc"), "Jam kirim wajib diisi!");
                hasError = true;
            }

            if (hasError) {
                e.preventDefault();
                return false;
            }

            buttonDisable();
        });

        form.on("input change", "input, select", function() {
            clearInlineError($(this));
        });

        form.find("select").on("select2:select select2:clear", function(e) {
            clearInlineError($(e.target));
        });
    });
</script>
