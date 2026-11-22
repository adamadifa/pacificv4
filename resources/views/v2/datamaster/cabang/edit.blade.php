<form action="{{ route('cabang.update', Crypt::encrypt($cabang->kode_cabang)) }}" id="formEditCabang" method="POST" class="space-y-3">
    @csrf
    @method('PUT')

    <!-- GROUP 1: IDENTITAS CABANG & PERUSAHAAN -->
    <div class="rounded-lg border border-slate-200/90 dark:border-slate-800 bg-white dark:bg-slate-900 p-3 space-y-2.5">
        <div class="flex items-center gap-1.5 pb-2 border-b border-slate-100 dark:border-slate-800/80">
            <i data-lucide="building-2" class="w-3.5 h-3.5 text-brand-600 dark:text-brand-400"></i>
            <h4 class="text-xs font-bold text-slate-800 dark:text-slate-100">Identitas Cabang & Perusahaan</h4>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-12 gap-2.5">
            <!-- Kode Cabang (Read-Only) -->
            <div class="sm:col-span-4 field-group">
                <label class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                    Kode Cabang <span class="text-xs font-normal text-slate-400">(Terkunci)</span>
                </label>
                <div class="relative">
                    <div class="input-prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                        <i data-lucide="lock" class="w-3.5 h-3.5"></i>
                    </div>
                    <input type="text" value="{{ $cabang->kode_cabang }}" readonly disabled
                        class="w-full h-[36px] pl-8 pr-2.5 text-xs font-black uppercase font-mono-num rounded-lg border border-slate-200 dark:border-slate-800 bg-slate-100 dark:bg-slate-800/70 text-slate-500 cursor-not-allowed">
                </div>
            </div>

            <!-- Nama Cabang -->
            <div class="sm:col-span-8 field-group">
                <label for="nama_cabang_edit" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                    Nama Cabang <span class="text-rose-500">*</span>
                </label>
                <div class="relative">
                    <div class="input-prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                        <i data-lucide="building" class="w-3.5 h-3.5"></i>
                    </div>
                    <input type="text" name="nama_cabang" id="nama_cabang_edit" value="{{ $cabang->nama_cabang }}"
                        class="w-full h-[36px] pl-8 pr-2.5 text-xs font-bold uppercase rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition placeholder:text-slate-400"
                        placeholder="Contoh: TASIKMALAYA">
                </div>
            </div>

            <!-- Regional -->
            <div class="sm:col-span-12 field-group">
                <label for="kode_regional_edit" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                    Wilayah Regional <span class="text-rose-500">*</span>
                </label>
                <div class="relative">
                    <div class="input-prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-20">
                        <i data-lucide="map-pin" class="w-3.5 h-3.5"></i>
                    </div>
                    <select name="kode_regional" id="kode_regional_edit" class="select2-regional-edit w-full">
                        <option value="">Pilih Regional</option>
                        @foreach ($regional as $r)
                            <option value="{{ $r->kode_regional }}" {{ $cabang->kode_regional == $r->kode_regional ? 'selected' : '' }}>
                                {{ $r->kode_regional }} - {{ $r->nama_regional }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <!-- Kode PT -->
            <div class="sm:col-span-4 field-group">
                <label for="kode_pt_edit" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                    Kode PT <span class="text-rose-500">*</span>
                </label>
                <div class="relative">
                    <div class="input-prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                        <i data-lucide="shield" class="w-3.5 h-3.5"></i>
                    </div>
                    <input type="text" name="kode_pt" id="kode_pt_edit" maxlength="3" value="{{ $cabang->kode_pt }}"
                        class="w-full h-[36px] pl-8 pr-2.5 text-xs font-black uppercase font-mono-num rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition placeholder:text-slate-400"
                        placeholder="Contoh: PAC">
                </div>
            </div>

            <!-- Nama PT -->
            <div class="sm:col-span-8 field-group">
                <label for="nama_pt_edit" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                    Nama Entitas PT <span class="text-rose-500">*</span>
                </label>
                <div class="relative">
                    <div class="input-prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                        <i data-lucide="landmark" class="w-3.5 h-3.5"></i>
                    </div>
                    <input type="text" name="nama_pt" id="nama_pt_edit" value="{{ $cabang->nama_pt }}"
                        class="w-full h-[36px] pl-8 pr-2.5 text-xs font-bold uppercase rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition placeholder:text-slate-400"
                        placeholder="Contoh: PT PACIFIC TASIKMALAYA">
                </div>
            </div>
        </div>
    </div>

    <!-- GROUP 2: LOKASI & KONTAK OPERASIONAL -->
    <div class="rounded-lg border border-slate-200/90 dark:border-slate-800 bg-white dark:bg-slate-900 p-3 space-y-2.5">
        <div class="flex items-center gap-1.5 pb-2 border-b border-slate-100 dark:border-slate-800/80">
            <i data-lucide="map-pinned" class="w-3.5 h-3.5 text-emerald-600 dark:text-emerald-400"></i>
            <h4 class="text-xs font-bold text-slate-800 dark:text-slate-100">Lokasi & Kontak Operasional</h4>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-12 gap-2.5">
            <!-- Alamat Cabang -->
            <div class="sm:col-span-12 field-group">
                <label for="alamat_cabang_edit" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                    Alamat Lengkap Kantor <span class="text-rose-500">*</span>
                </label>
                <div class="relative">
                    <div class="input-prefix-icon absolute top-2.5 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                        <i data-lucide="map-pin" class="w-3.5 h-3.5"></i>
                    </div>
                    <textarea name="alamat_cabang" id="alamat_cabang_edit" rows="2"
                        class="w-full pl-8 pr-2.5 py-1.5 text-xs font-medium rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition placeholder:text-slate-400"
                        placeholder="Masukkan alamat lengkap kantor cabang...">{{ $cabang->alamat_cabang }}</textarea>
                </div>
            </div>

            <!-- Telepon Cabang -->
            <div class="sm:col-span-6 field-group">
                <label for="telepon_cabang_edit" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                    No. Telepon <span class="text-rose-500">*</span>
                </label>
                <div class="relative">
                    <div class="input-prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                        <i data-lucide="phone" class="w-3.5 h-3.5"></i>
                    </div>
                    <input type="text" name="telepon_cabang" id="telepon_cabang_edit" maxlength="13" value="{{ $cabang->telepon_cabang }}"
                        class="w-full h-[36px] pl-8 pr-2.5 text-xs font-semibold font-mono-num rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition placeholder:text-slate-400"
                        placeholder="Contoh: 08123456789">
                </div>
            </div>

            <!-- Radius Absensi (Meter) -->
            <div class="sm:col-span-6 field-group">
                <label for="radius_cabang_edit" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                    Radius Absensi (Meter) <span class="text-rose-500">*</span>
                </label>
                <div class="relative">
                    <div class="input-prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                        <i data-lucide="radar" class="w-3.5 h-3.5"></i>
                    </div>
                    <input type="number" name="radius_cabang" id="radius_cabang_edit" value="{{ $cabang->radius_cabang ?? 50 }}" min="5" max="5000"
                        class="w-full h-[36px] pl-8 pr-2.5 text-xs font-bold font-mono-num rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition"
                        placeholder="Contoh: 50">
                </div>
            </div>

            <!-- Titik Koordinat Lokasi GPS -->
            <div class="sm:col-span-6 field-group">
                <label for="lokasi_cabang_edit" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                    Titik Koordinat GPS <span class="text-rose-500">*</span>
                </label>
                <div class="relative">
                    <div class="input-prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                        <i data-lucide="navigation" class="w-3.5 h-3.5"></i>
                    </div>
                    <input type="text" name="lokasi_cabang" id="lokasi_cabang_edit" value="{{ $cabang->lokasi_cabang }}"
                        class="w-full h-[36px] pl-8 pr-2.5 text-xs font-semibold font-mono-num rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition placeholder:text-slate-400"
                        placeholder="Contoh: -7.3274, 108.2207">
                </div>
            </div>

            <!-- Urutan -->
            <div class="sm:col-span-3 field-group">
                <label for="urutan_edit" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                    Urutan Tampil <span class="text-rose-500">*</span>
                </label>
                <div class="relative">
                    <div class="input-prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                        <i data-lucide="list-ordered" class="w-3.5 h-3.5"></i>
                    </div>
                    <input type="number" name="urutan" id="urutan_edit" value="{{ $cabang->urutan ?? 1 }}" min="1"
                        class="w-full h-[36px] pl-8 pr-2.5 text-xs font-bold font-mono-num rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition">
                </div>
            </div>

            <!-- Color Marker -->
            <div class="sm:col-span-3 field-group">
                <label for="color_marker_edit" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                    Warna Marker GPS
                </label>
                <div class="flex items-center gap-2">
                    <input type="color" name="color_marker" id="color_marker_edit" value="{{ $cabang->color_marker ?? '#284c9a' }}"
                        class="w-10 h-[36px] p-0.5 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 cursor-pointer">
                    <input type="text" id="color_text_edit" value="{{ $cabang->color_marker ?? '#284c9a' }}" readonly
                        class="w-full h-[36px] px-2 text-xs font-mono-num font-bold uppercase rounded-lg border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 text-slate-700 dark:text-slate-200">
                </div>
            </div>
        </div>
    </div>

    <!-- SUBMIT & CANCEL BUTTONS -->
    <div class="flex items-center justify-end gap-2 pt-2">
        <button type="button" class="btnCancelModal inline-flex items-center justify-center h-[36px] px-4 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 hover:bg-slate-50 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-200 text-xs font-bold transition shadow-2xs cursor-pointer">
            Batal
        </button>
        <button type="submit" id="btnSubmitEditCabang"
            class="inline-flex items-center justify-center gap-1.5 h-[36px] px-5 rounded-lg bg-brand-600 hover:bg-brand-700 active:bg-brand-800 text-white text-xs font-bold transition shadow-xs cursor-pointer">
            <i data-lucide="refresh-cw" class="w-3.5 h-3.5"></i>
            <span>Simpan Perubahan</span>
        </button>
    </div>
</form>

<script>
    $(document).ready(function() {
        const $form = $("#formEditCabang");
        const $btnSubmit = $form.find("#btnSubmitEditCabang");

        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }

        // Initialize Select2 Regional
        if ($.fn.select2) {
            $form.find(".select2-regional-edit").select2({
                placeholder: "Pilih Wilayah Regional",
                allowClear: true,
                width: '100%'
            });

            $form.find(".select2-regional-edit").on("change select2:select", function() {
                clearFieldError($(this));
            });
        }

        // Color Marker Live Sync
        $form.find("#color_marker_edit").on("input change", function() {
            $form.find("#color_text_edit").val($(this).val().toUpperCase());
        });

        // Field Error Helpers
        function setFieldError($input, message) {
            const $container = $input.closest('.field-group');
            $input.addClass('border-rose-500 ring-1 ring-rose-500 focus:border-rose-500 focus:ring-rose-500/20')
                  .removeClass('border-slate-200 dark:border-slate-700');
            $container.find('.input-prefix-icon').addClass('text-rose-500').removeClass('text-slate-400');

            if ($input.hasClass('select2-hidden-accessible')) {
                $container.find('.select2-selection').addClass('!border-rose-500 !ring-1 !ring-rose-500');
            }

            $container.find('.field-error-msg').remove();
            $container.append(`
                <p class="field-error-msg text-[11px] font-semibold text-rose-600 dark:text-rose-400 mt-1 pl-1 flex items-center gap-1">
                    <svg class="w-3 h-3 text-rose-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                        <circle cx="12" cy="12" r="10"></circle>
                        <line x1="12" y1="8" x2="12" y2="12"></line>
                        <line x1="12" y1="16" x2="12.01" y2="16"></line>
                    </svg>
                    <span>${message}</span>
                </p>
            `);
        }

        function clearFieldError($input) {
            const $container = $input.closest('.field-group');
            $input.removeClass('border-rose-500 ring-1 ring-rose-500 focus:border-rose-500 focus:ring-rose-500/20')
                  .addClass('border-slate-200 dark:border-slate-700');
            $container.find('.input-prefix-icon').removeClass('text-rose-500').addClass('text-slate-400');

            if ($input.hasClass('select2-hidden-accessible')) {
                $container.find('.select2-selection').removeClass('!border-rose-500 !ring-1 !ring-rose-500');
            }

            $container.find('.field-error-msg').remove();
        }

        // Live Cleanup on Type
        $form.find('input, textarea, select').on('input change', function() {
            clearFieldError($(this));
        });

        // Form Submit Validation
        $form.on("submit", function(e) {
            let hasError = false;
            let $firstErrorField = null;

            function validateField($field, condition, message) {
                if (condition) {
                    setFieldError($field, message);
                    if (!hasError) {
                        hasError = true;
                        $firstErrorField = $field;
                    }
                } else {
                    clearFieldError($field);
                }
            }

            const $nama = $form.find("#nama_cabang_edit");
            const $regional = $form.find("#kode_regional_edit");
            const $kodePt = $form.find("#kode_pt_edit");
            const $namaPt = $form.find("#nama_pt_edit");
            const $alamat = $form.find("#alamat_cabang_edit");
            const $telepon = $form.find("#telepon_cabang_edit");
            const $lokasi = $form.find("#lokasi_cabang_edit");
            const $radius = $form.find("#radius_cabang_edit");
            const $urutan = $form.find("#urutan_edit");

            validateField($nama, !$nama.val().trim(), "Nama cabang wajib diisi");
            validateField($regional, !$regional.val(), "Pilih wilayah regional");
            validateField($kodePt, !$kodePt.val().trim() || $kodePt.val().trim().length > 3, "Kode PT wajib diisi (maks 3 huruf)");
            validateField($namaPt, !$namaPt.val().trim(), "Nama entitas PT wajib diisi");
            validateField($alamat, !$alamat.val().trim(), "Alamat kantor cabang wajib diisi");
            validateField($telepon, !$telepon.val().trim() || isNaN($telepon.val()), "No. telepon wajib diisi angka");
            validateField($lokasi, !$lokasi.val().trim(), "Titik koordinat GPS wajib diisi");
            validateField($radius, !$radius.val() || $radius.val() <= 0, "Radius absensi wajib diisi angka positif");
            validateField($urutan, !$urutan.val() || $urutan.val() <= 0, "Urutan tampil wajib diisi");

            if (hasError) {
                e.preventDefault();
                if ($firstErrorField) {
                    if ($firstErrorField.hasClass('select2-hidden-accessible')) {
                        $firstErrorField.select2('open');
                    } else {
                        $firstErrorField.focus();
                    }
                }
                return false;
            }

            // Prevent Double Submit
            $btnSubmit.prop('disabled', true).addClass('opacity-75 cursor-not-allowed pointer-events-none');
            $btnSubmit.html(`
                <svg class="animate-spin h-3.5 w-3.5 text-white shrink-0 inline-block" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <span>Menyimpan Perubahan...</span>
            `);
            return true;
        });
    });
</script>
