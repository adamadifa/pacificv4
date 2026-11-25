<form action="{{ route('harilibur.store') }}" method="POST" id="formHariLiburCreate" autocomplete="off" class="space-y-2.5">
    @csrf

    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2.5 items-start">
        <div>
            <label for="tanggal_create" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                Tanggal <span class="text-rose-500">*</span>
            </label>
            <div class="relative">
                <div class="prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                    <i data-lucide="calendar" class="w-4 h-4"></i>
                </div>
                <input type="text" name="tanggal" id="tanggal_create" placeholder="Pilih Tanggal" readonly
                    class="flatpickr-date w-full h-[36px] pl-9 pr-3 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-950 text-xs font-medium text-slate-700 dark:text-slate-200 focus:outline-none focus:border-[#284c9a] focus:ring-2 focus:ring-[#284c9a]/15 transition cursor-pointer">
            </div>
        </div>

        <div>
            <label for="kategori_create" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                Kategori Libur <span class="text-rose-500">*</span>
            </label>
            <div class="relative">
                <div class="prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                    <i data-lucide="tag" class="w-4 h-4"></i>
                </div>
                <select name="kategori" id="kategori_create"
                    class="w-full h-[36px] pl-9 pr-8 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-950 text-xs font-medium text-slate-700 dark:text-slate-200 focus:outline-none focus:border-[#284c9a] focus:ring-2 focus:ring-[#284c9a]/15 transition">
                    <option value="">Pilih Kategori Libur</option>
                    @foreach ($kategorilibur as $k)
                        <option value="{{ $k->kode_kategori }}">{{ strtoupper($k->nama_kategori) }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>

    @if (in_array($level_user, ['super admin', 'asst. manager hrd', 'spv presensi']))
        <div>
            <label for="kode_cabang_create" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                Cabang <span class="text-rose-500">*</span>
            </label>
            <div class="relative">
                <div class="prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-20">
                    <i data-lucide="building-2" class="w-4 h-4"></i>
                </div>
                <select name="kode_cabang" id="kode_cabang_create"
                    class="select2KodecabangCreate w-full h-[36px] pl-9 pr-8 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-950 text-xs font-medium text-slate-700 dark:text-slate-200">
                    <option value="">Pilih Cabang</option>
                    @foreach ($cabang as $c)
                        <option value="{{ $c->kode_cabang }}">{{ strtoupper($c->nama_cabang) }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div id="departemen_create_wrap">
            <label for="kode_dept_create" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                Departemen <span class="text-rose-500">*</span>
            </label>
            <div class="relative">
                <div class="prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-20">
                    <i data-lucide="briefcase" class="w-4 h-4"></i>
                </div>
                <select name="kode_dept" id="kode_dept_create"
                    class="select2KodeDeptCreate w-full h-[36px] pl-9 pr-8 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-950 text-xs font-medium text-slate-700 dark:text-slate-200">
                    <option value="">Pilih Departemen</option>
                    @foreach ($departemen as $dept)
                        <option value="{{ $dept->kode_dept }}">{{ strtoupper($dept->nama_dept) }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    @endif

    <div>
        <label for="tanggal_diganti_create" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
            Tanggal Yang Diganti
        </label>
        <div class="relative">
            <div class="prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                <i data-lucide="calendar-sync" class="w-4 h-4"></i>
            </div>
            <input type="text" name="tanggal_diganti" id="tanggal_diganti_create" placeholder="Pilih Tanggal" readonly
                class="flatpickr-date w-full h-[36px] pl-9 pr-3 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-950 text-xs font-medium text-slate-700 dark:text-slate-200 focus:outline-none focus:border-[#284c9a] focus:ring-2 focus:ring-[#284c9a]/15 transition cursor-pointer">
        </div>
        <p class="mt-1 text-[11px] text-sky-600 dark:text-sky-400 flex items-center gap-1">
            <i data-lucide="info" class="w-3.5 h-3.5 shrink-0"></i>
            <span>Kosongkan jika tidak ada tanggal libur yang diganti.</span>
        </p>
    </div>

    <div class="rounded-lg border border-slate-200/80 dark:border-slate-800 bg-slate-50/70 dark:bg-slate-800/40 p-2.5">
        <label for="limajam_create" class="flex items-start gap-2 cursor-pointer">
            <input type="checkbox" id="limajam_create" value="1" name="limajam"
                class="mt-0.5 rounded border-slate-300 text-[#284c9a] focus:ring-[#284c9a]">
            <div>
                <span class="block text-xs font-bold text-slate-700 dark:text-slate-200">5 Jam Kerja Untuk Tanggal Sebelumnya</span>
                <span class="block text-[11px] text-slate-400">Centang jika tanggal sebelum libur diberlakukan 5 jam kerja.</span>
            </div>
        </label>
    </div>

    <div>
        <label for="keterangan_create" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
            Keterangan <span class="text-rose-500">*</span>
        </label>
        <div class="relative">
            <div class="prefix-icon absolute top-2.5 left-0 pl-2.5 flex items-start pointer-events-none text-slate-400 z-10">
                <i data-lucide="file-text" class="w-4 h-4"></i>
            </div>
            <textarea name="keterangan" id="keterangan_create" rows="2" placeholder="Masukkan keterangan hari libur..."
                class="w-full pl-9 pr-3 py-2 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-950 text-xs font-medium text-slate-700 dark:text-slate-200 focus:outline-none focus:border-[#284c9a] focus:ring-2 focus:ring-[#284c9a]/15 transition"></textarea>
        </div>
    </div>

    <div class="flex items-center justify-end gap-2 pt-2 border-t border-slate-100 dark:border-slate-800">
        <button type="button" onclick="window.closeModalHariLibur && window.closeModalHariLibur()"
            class="h-[36px] px-4 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-xs font-bold text-slate-600 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-700 transition cursor-pointer">
            Batal
        </button>
        <button type="submit" id="btnSimpanHariLiburCreate"
            class="h-[36px] px-4 rounded-lg bg-[#284c9a] hover:bg-[#1e3a78] text-white text-xs font-bold shadow-xs transition flex items-center justify-center gap-1.5 cursor-pointer">
            <i data-lucide="save" class="w-3.5 h-3.5"></i>
            <span>Simpan</span>
        </button>
    </div>
</form>

<script>
    $(function() {
        const $form = $('#formHariLiburCreate');

        $form.find('.flatpickr-date').flatpickr({
            dateFormat: 'Y-m-d',
            allowInput: false
        });

        if ($.fn.select2) {
            $form.find('.select2KodecabangCreate').select2({
                placeholder: 'Pilih Cabang',
                width: '100%'
            });
            $form.find('.select2KodeDeptCreate').select2({
                placeholder: 'Pilih Departemen',
                width: '100%'
            });
        }

        function showDepartemen() {
            const $cabang = $form.find('#kode_cabang_create');
            if (!$cabang.length) return;
            if ($cabang.val() === 'PST') {
                $form.find('#departemen_create_wrap').show();
            } else {
                $form.find('#departemen_create_wrap').hide();
            }
        }

        showDepartemen();
        $form.find('#kode_cabang_create').on('change', showDepartemen);

        function clearError($el) {
            $el.removeClass('border-rose-500 ring-1 ring-rose-500 focus:border-rose-500 focus:ring-rose-500/20');
            const $rel = $el.closest('.relative');
            $rel.find('.select2-selection').removeClass('!border-rose-500 !ring-1 !ring-rose-500');
            $rel.find('.prefix-icon').removeClass('text-rose-500').addClass('text-slate-400');
            $rel.siblings('.field-error-msg').remove();
        }

        function showError($el, msg) {
            clearError($el);
            $el.addClass('border-rose-500 ring-1 ring-rose-500 focus:border-rose-500 focus:ring-rose-500/20');
            const $rel = $el.closest('.relative');
            $rel.find('.select2-selection').addClass('!border-rose-500 !ring-1 !ring-rose-500');
            $rel.find('.prefix-icon').removeClass('text-slate-400').addClass('text-rose-500');
            $rel.after(`
                <p class="field-error-msg text-[11px] font-semibold text-rose-600 dark:text-rose-400 mt-1 pl-1 flex items-center gap-1">
                    <svg class="w-3.5 h-3.5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>
                    <span>${msg}</span>
                </p>
            `);
        }

        $form.find('input, select, textarea').on('input change select2:select', function() {
            clearError($(this));
        });

        $form.on('submit', function(e) {
            const $tanggal = $form.find('#tanggal_create');
            const $cabang = $form.find('#kode_cabang_create');
            const $dept = $form.find('#kode_dept_create');
            const $kategori = $form.find('#kategori_create');
            const $keterangan = $form.find('#keterangan_create');
            let firstError = null;

            if (!$tanggal.val()) {
                showError($tanggal, 'Tanggal wajib diisi.');
                if (!firstError) firstError = $tanggal;
            }
            if ($cabang.length && !$cabang.val()) {
                showError($cabang, 'Cabang wajib dipilih.');
                if (!firstError) firstError = $cabang;
            }
            if ($cabang.length && $cabang.val() === 'PST' && !$dept.val()) {
                showError($dept, 'Departemen wajib dipilih.');
                if (!firstError) firstError = $dept;
            }
            if (!$kategori.val()) {
                showError($kategori, 'Kategori wajib dipilih.');
                if (!firstError) firstError = $kategori;
            }
            if (!$.trim($keterangan.val())) {
                showError($keterangan, 'Keterangan wajib diisi.');
                if (!firstError) firstError = $keterangan;
            }

            if (firstError) {
                e.preventDefault();
                firstError.focus();
                return false;
            }

            const $btn = $form.find('#btnSimpanHariLiburCreate');
            $btn.prop('disabled', true).addClass('opacity-75 cursor-not-allowed pointer-events-none');
            $btn.html(`
                <svg class="animate-spin h-3.5 w-3.5 text-white shrink-0 inline-block" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <span>Menyimpan...</span>
            `);
        });
    });
</script>
