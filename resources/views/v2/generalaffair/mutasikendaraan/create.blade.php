<form action="{{ route('mutasikendaraan.store') }}" method="POST" id="formMutasikendaraan" class="space-y-2.5">
    @csrf

    <!-- Kendaraan -->
    <div>
        <label for="kode_kendaraan_create" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
            Kendaraan <span class="text-rose-500">*</span>
        </label>
        <div class="relative select2-container-v2">
            <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-20">
                <i data-lucide="truck" class="w-3.5 h-3.5"></i>
            </div>
            <select name="kode_kendaraan" id="kode_kendaraan_create" class="select2KendaraanCreate w-full">
                <option value="">Pilih Kendaraan</option>
                @foreach ($kendaraan as $d)
                    <option value="{{ $d->kode_kendaraan }}">{{ $d->no_polisi }} — {{ $d->merk }} {{ $d->tipe }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <!-- Cabang Tujuan -->
    <div>
        <label for="kode_cabang_create" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
            Cabang Tujuan <span class="text-rose-500">*</span>
        </label>
        <div class="relative select2-container-v2">
            <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-20">
                <i data-lucide="building" class="w-3.5 h-3.5"></i>
            </div>
            <select name="kode_cabang" id="kode_cabang_create" class="select2KodecabangCreate w-full">
                <option value="">Pilih Cabang Tujuan</option>
                @foreach ($cabang as $d)
                    <option value="{{ $d->kode_cabang }}">{{ textUpperCase($d->nama_cabang) }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <!-- Tanggal Mutasi -->
    <div>
        <label for="tanggal_mutasi_create" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
            Tanggal Mutasi <span class="text-rose-500">*</span>
        </label>
        <div class="relative">
            <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                <i data-lucide="calendar" class="w-3.5 h-3.5"></i>
            </div>
            <input type="text" name="tanggal" id="tanggal_mutasi_create" placeholder="Pilih Tanggal" readonly
                class="flatpickr-date w-full h-[36px] pl-8 pr-3 text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 placeholder:text-slate-400 focus:outline-hidden focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition cursor-pointer">
        </div>
    </div>

    <!-- Keterangan -->
    <div>
        <label for="keterangan_mutasi_create" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
            Keterangan <span class="text-rose-500">*</span>
        </label>
        <div class="relative">
            <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                <i data-lucide="file-text" class="w-3.5 h-3.5"></i>
            </div>
            <input type="text" name="keterangan" id="keterangan_mutasi_create" placeholder="Masukkan alasan atau keterangan mutasi"
                class="w-full h-[36px] pl-8 pr-3 text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 placeholder:text-slate-400 focus:outline-hidden focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition">
        </div>
    </div>

    <!-- Modal Footer Actions (Right-Aligned) -->
    <div class="pt-3 border-t border-slate-200/80 dark:border-slate-800 flex items-center justify-end gap-2">
        <button type="button" data-bs-dismiss="modal"
            class="h-[36px] px-4 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 hover:bg-slate-50 dark:hover:bg-slate-700/60 text-slate-700 dark:text-slate-200 text-xs font-bold transition shadow-2xs cursor-pointer">
            Batal
        </button>
        <button type="submit" id="btnSimpanMutasi"
            class="h-[36px] px-5 rounded-lg bg-[#284c9a] hover:bg-[#1e3a78] active:bg-[#152a57] text-white text-xs font-bold transition flex items-center justify-center gap-1.5 shadow-xs cursor-pointer">
            <i data-lucide="send" class="w-3.5 h-3.5"></i>
            <span>Simpan Mutasi</span>
        </button>
    </div>

</form>

<script>
    $(function() {
        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }

        const form = $('#formMutasikendaraan');

        if (typeof flatpickr !== 'undefined') {
            form.find(".flatpickr-date").flatpickr({
                dateFormat: "Y-m-d",
                defaultDate: new Date()
            });
        }

        form.find('.select2KendaraanCreate').select2({ placeholder: 'Pilih Kendaraan', allowClear: true, width: '100%' });
        form.find('.select2KodecabangCreate').select2({ placeholder: 'Pilih Cabang Tujuan', allowClear: true, width: '100%' });

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
            const $btn = form.find("#btnSimpanMutasi");
            $btn.prop('disabled', true).addClass('opacity-75 cursor-not-allowed pointer-events-none');
            $btn.html(`
                <svg class="animate-spin h-3.5 w-3.5 text-white shrink-0 inline-block" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                </svg>
                <span>Menyimpan...</span>
            `);
        }

        form.submit(function(e) {
            const kode_kendaraan = form.find("#kode_kendaraan_create").val();
            const kode_cabang = form.find("#kode_cabang_create").val();
            const tanggal = form.find("#tanggal_mutasi_create").val().trim();
            const keterangan = form.find("#keterangan_mutasi_create").val().trim();

            let hasError = false;

            if (!kode_kendaraan) {
                showInlineError(form.find("#kode_kendaraan_create"), "Kendaraan wajib dipilih!");
                hasError = true;
            }
            if (!kode_cabang) {
                showInlineError(form.find("#kode_cabang_create"), "Cabang tujuan wajib dipilih!");
                hasError = true;
            }
            if (!tanggal) {
                showInlineError(form.find("#tanggal_mutasi_create"), "Tanggal mutasi wajib diisi!");
                hasError = true;
            }
            if (!keterangan) {
                showInlineError(form.find("#keterangan_mutasi_create"), "Keterangan mutasi wajib diisi!");
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
