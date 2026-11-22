<form action="{{ route('salesman.update', Crypt::encrypt($salesman->kode_salesman)) }}" id="formeditSalesman" method="POST" enctype="multipart/form-data" class="space-y-2.5">
    @csrf
    @method('PUT')

    <div class="grid grid-cols-1 md:grid-cols-2 gap-2.5">
        <!-- Kode Salesman (Readonly) -->
        <div class="field-group">
            <label for="kode_salesman_edit" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                Kode Salesman
            </label>
            <div class="relative">
                <div class="input-prefix-icon absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400 z-10 transition-colors">
                    <i data-lucide="barcode" class="w-4 h-4"></i>
                </div>
                <input type="text" name="kode_salesman" id="kode_salesman_edit" readonly
                    value="{{ $salesman->kode_salesman }}"
                    class="w-full h-[36px] pl-9 pr-3 text-xs font-semibold uppercase font-mono-num rounded-lg border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 text-slate-500 dark:text-slate-400 focus:outline-hidden cursor-not-allowed">
            </div>
        </div>

        <!-- Nama Salesman -->
        <div class="field-group">
            <label for="nama_salesman_edit" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                Nama Salesman <span class="text-rose-500">*</span>
            </label>
            <div class="relative">
                <div class="input-prefix-icon absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400 z-10 transition-colors">
                    <i data-lucide="user" class="w-4 h-4"></i>
                </div>
                <input type="text" name="nama_salesman" id="nama_salesman_edit"
                    value="{{ $salesman->nama_salesman }}"
                    class="w-full h-[36px] pl-9 pr-3 text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition"
                    placeholder="Nama lengkap salesman">
            </div>
        </div>
    </div>

    <!-- Alamat Salesman -->
    <div class="field-group">
        <label for="alamat_salesman_edit" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
            Alamat Domisili <span class="text-rose-500">*</span>
        </label>
        <div class="relative">
            <div class="input-prefix-icon absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400 z-10 transition-colors">
                <i data-lucide="map-pin" class="w-4 h-4"></i>
            </div>
            <input type="text" name="alamat_salesman" id="alamat_salesman_edit"
                value="{{ $salesman->alamat_salesman }}"
                class="w-full h-[36px] pl-9 pr-3 text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition"
                placeholder="Alamat lengkap salesman">
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-2.5">
        <!-- No. HP -->
        <div class="field-group">
            <label for="no_hp_salesman_edit" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                No. Handphone / WA <span class="text-rose-500">*</span>
            </label>
            <div class="relative">
                <div class="input-prefix-icon absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400 z-10 transition-colors">
                    <i data-lucide="phone" class="w-4 h-4"></i>
                </div>
                <input type="text" name="no_hp_salesman" id="no_hp_salesman_edit"
                    value="{{ $salesman->no_hp_salesman }}"
                    class="w-full h-[36px] pl-9 pr-3 text-xs font-semibold font-mono-num rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition"
                    placeholder="Contoh: 08123456789">
            </div>
        </div>

        <!-- Kategori Salesman -->
        <div class="field-group">
            <label for="kode_kategori_salesman_edit" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                Kategori Salesman <span class="text-rose-500">*</span>
            </label>
            <div class="relative">
                <div class="input-prefix-icon absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400 z-10 transition-colors">
                    <i data-lucide="tag" class="w-4 h-4"></i>
                </div>
                <select name="kode_kategori_salesman" id="kode_kategori_salesman_edit"
                    class="w-full h-[36px] pl-9 pr-8 text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition appearance-none cursor-pointer">
                    <option value="">Pilih Kategori</option>
                    @foreach ($kategori_salesman as $k)
                        <option value="{{ $k->kode_kategori_salesman }}" {{ $salesman->kode_kategori_salesman == $k->kode_kategori_salesman ? 'selected' : '' }}>
                            {{ $k->nama_kategori_salesman }}
                        </option>
                    @endforeach
                </select>
                <div class="absolute inset-y-0 right-0 pr-2.5 flex items-center pointer-events-none text-slate-400">
                    <i data-lucide="chevron-down" class="w-4 h-4"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-2.5">
        <!-- Status Komisi -->
        <div class="field-group">
            <label for="status_komisi_salesman_edit" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                Status Komisi <span class="text-rose-500">*</span>
            </label>
            <div class="relative">
                <div class="input-prefix-icon absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400 z-10 transition-colors">
                    <i data-lucide="coins" class="w-4 h-4"></i>
                </div>
                <select name="status_komisi_salesman" id="status_komisi_salesman_edit"
                    class="w-full h-[36px] pl-9 pr-8 text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition appearance-none cursor-pointer">
                    <option value="">Pilih Status Komisi</option>
                    <option value="1" {{ $salesman->status_komisi_salesman === '1' ? 'selected' : '' }}>Ya (Mendapat Komisi)</option>
                    <option value="0" {{ $salesman->status_komisi_salesman === '0' ? 'selected' : '' }}>Tidak (Tanpa Komisi)</option>
                </select>
                <div class="absolute inset-y-0 right-0 pr-2.5 flex items-center pointer-events-none text-slate-400">
                    <i data-lucide="chevron-down" class="w-4 h-4"></i>
                </div>
            </div>
        </div>

        <!-- Status Aktif -->
        <div class="field-group">
            <label for="status_aktif_salesman_edit" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                Status Keaktifan <span class="text-rose-500">*</span>
            </label>
            <div class="relative">
                <div class="input-prefix-icon absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400 z-10 transition-colors">
                    <i data-lucide="check-circle-2" class="w-4 h-4"></i>
                </div>
                <select name="status_aktif_salesman" id="status_aktif_salesman_edit"
                    class="w-full h-[36px] pl-9 pr-8 text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition appearance-none cursor-pointer">
                    <option value="">Pilih Status</option>
                    <option value="1" {{ $salesman->status_aktif_salesman === '1' ? 'selected' : '' }}>Aktif</option>
                    <option value="0" {{ $salesman->status_aktif_salesman === '0' ? 'selected' : '' }}>Non Aktif</option>
                </select>
                <div class="absolute inset-y-0 right-0 pr-2.5 flex items-center pointer-events-none text-slate-400">
                    <i data-lucide="chevron-down" class="w-4 h-4"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Cabang (Jika Berhak Pilih) -->
    @hasanyrole(config('global.roles_show_cabang'))
        <div class="field-group">
            <label for="kode_cabang_edit" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                Cabang Penempatan <span class="text-rose-500">*</span>
            </label>
            <div class="relative">
                <div class="input-prefix-icon absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400 z-10 transition-colors">
                    <i data-lucide="building-2" class="w-4 h-4"></i>
                </div>
                <select name="kode_cabang" id="kode_cabang_edit" class="select2Cabang w-full h-[36px] text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-800 dark:text-slate-100">
                    <option value="">Pilih Cabang</option>
                    @foreach ($cabang as $c)
                        <option value="{{ $c->kode_cabang }}" {{ $salesman->kode_cabang == $c->kode_cabang ? 'selected' : '' }}>
                            {{ $c->nama_cabang }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>
    @endhasanyrole

    <!-- Upload Marker Foto GPS & Current Preview -->
    <div class="field-group">
        <label for="marker_edit" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
            Ganti Marker / Foto Salesman (Opsional)
        </label>
        
        <div class="flex items-center gap-3">
            @if (!empty($salesman->marker))
                <div class="shrink-0">
                    <img src="{{ getdocMarker($salesman->marker) }}" alt="Marker" class="w-9 h-9 rounded-lg object-cover border border-slate-200 dark:border-slate-700 shadow-2xs">
                </div>
            @endif
            <div class="relative flex-1">
                <div class="input-prefix-icon absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400 z-10 transition-colors">
                    <i data-lucide="image" class="w-4 h-4"></i>
                </div>
                <input type="file" name="marker" id="marker_edit" accept="image/*"
                    class="w-full text-xs text-slate-600 dark:text-slate-300 file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-brand-50 file:text-brand-700 hover:file:bg-brand-100 dark:file:bg-brand-950 dark:file:text-brand-300 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 h-[36px] pl-9 pr-3 flex items-center transition cursor-pointer">
            </div>
        </div>
        <p class="text-[10px] text-slate-400 mt-0.5 pl-1">Biarkan kosong jika tidak ingin mengubah marker.</p>
    </div>

    <!-- Submit Button -->
    <div class="pt-2">
        <button type="submit" id="btnSubmitEditSalesman" data-loading-text="Memperbarui Salesman..."
            class="w-full h-[36px] rounded-lg bg-brand-600 hover:bg-brand-700 active:bg-brand-800 text-white font-bold text-xs flex items-center justify-center gap-1.5 shadow-xs transition active:scale-[0.99] cursor-pointer">
            <i data-lucide="refresh-cw" class="w-3.5 h-3.5"></i>
            <span>Simpan Perubahan</span>
        </button>
    </div>
</form>

<script>
    $(function() {
        const $form = $("#formeditSalesman");
        const $nama = $form.find("#nama_salesman_edit");
        const $alamat = $form.find("#alamat_salesman_edit");
        const $hp = $form.find("#no_hp_salesman_edit");
        const $kategori = $form.find("#kode_kategori_salesman_edit");
        const $komisi = $form.find("#status_komisi_salesman_edit");
        const $status = $form.find("#status_aktif_salesman_edit");
        const $cabang = $form.find("#kode_cabang_edit");
        const $btnSubmit = $form.find("#btnSubmitEditSalesman");

        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }

        // Init Select2 Cabang jika ada
        if ($.fn.select2) {
            $form.find(".select2Cabang").each(function() {
                var $this = $(this);
                $this.select2({
                    placeholder: "Pilih Cabang",
                    allowClear: true,
                    width: '100%'
                });
            });
        }

        // Helper invalid
        function setFieldError($input, message) {
            const $container = $input.closest('.field-group');
            $input.addClass('border-rose-500 ring-1 ring-rose-500 focus:border-rose-500 focus:ring-rose-500/20')
                  .removeClass('border-slate-200 dark:border-slate-700');
            $container.find('.select2-selection--single').addClass('!border-rose-500 !ring-1 !ring-rose-500');
            $container.find('.input-prefix-icon').addClass('text-rose-500').removeClass('text-slate-400');
            $container.find('.field-error-msg').remove();
            $container.append(`
                <p class="field-error-msg text-[11px] font-semibold text-rose-600 dark:text-rose-400 mt-1 pl-1 flex items-center gap-1">
                    <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <circle cx="12" cy="12" r="10"></circle>
                        <line x1="12" y1="8" x2="12" y2="12"></line>
                        <line x1="12" y1="16" x2="12.01" y2="16"></line>
                    </svg>
                    <span>${message}</span>
                </p>
            `);
        }

        // Helper clean
        function clearFieldError($input) {
            const $container = $input.closest('.field-group');
            $input.removeClass('border-rose-500 ring-1 ring-rose-500 focus:border-rose-500 focus:ring-rose-500/20')
                  .addClass('border-slate-200 dark:border-slate-700');
            $container.find('.select2-selection--single').removeClass('!border-rose-500 !ring-1 !ring-rose-500');
            $container.find('.input-prefix-icon').removeClass('text-rose-500').addClass('text-slate-400');
            $container.find('.field-error-msg').remove();
        }

        $form.find('input, select').on('input change select2:select select2:clear', function() {
            clearFieldError($(this));
        });

        // Submit form validation
        $form.on('submit', function(e) {
            let hasError = false;
            let $firstError = null;

            if (!$nama.val() || !$nama.val().toString().trim()) {
                setFieldError($nama, "Nama salesman wajib diisi!");
                hasError = true;
                if (!$firstError) $firstError = $nama;
            }

            if (!$alamat.val() || !$alamat.val().toString().trim()) {
                setFieldError($alamat, "Alamat domisili wajib diisi!");
                hasError = true;
                if (!$firstError) $firstError = $alamat;
            }

            if (!$hp.val() || !$hp.val().toString().trim()) {
                setFieldError($hp, "No. Handphone wajib diisi!");
                hasError = true;
                if (!$firstError) $firstError = $hp;
            } else if (!/^\d+$/.test($hp.val().toString().trim())) {
                setFieldError($hp, "No. Handphone harus berupa angka!");
                hasError = true;
                if (!$firstError) $firstError = $hp;
            }

            if (!$kategori.val()) {
                setFieldError($kategori, "Kategori salesman wajib dipilih!");
                hasError = true;
                if (!$firstError) $firstError = $kategori;
            }

            if ($komisi.val() === '') {
                setFieldError($komisi, "Status komisi wajib dipilih!");
                hasError = true;
                if (!$firstError) $firstError = $komisi;
            }

            if ($status.val() === '') {
                setFieldError($status, "Status keaktifan wajib dipilih!");
                hasError = true;
                if (!$firstError) $firstError = $status;
            }

            if ($cabang.length && !$cabang.val()) {
                setFieldError($cabang, "Cabang penempatan wajib dipilih!");
                hasError = true;
                if (!$firstError) $firstError = $cabang;
            }

            if (hasError) {
                e.preventDefault();
                if (typeof window.resetButtonSubmitting === 'function') {
                    window.resetButtonSubmitting($btnSubmit);
                }
                if ($firstError) {
                    if ($firstError.hasClass('select2-hidden-accessible')) {
                        $firstError.select2('open');
                    } else {
                        $firstError.focus();
                    }
                }
                return false;
            }

            if (typeof window.setButtonSubmitting === 'function') {
                window.setButtonSubmitting($btnSubmit, 'Memperbarui Salesman...');
            } else {
                $btnSubmit.prop('disabled', true).addClass('opacity-75 cursor-not-allowed pointer-events-none');
            }
            return true;
        });
    });
</script>
