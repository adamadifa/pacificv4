<form action="{{ route('barangproduksi.update', Crypt::encrypt($barangproduksi->kode_barang_produksi)) }}" id="formeditBarang" method="POST" class="space-y-2.5">
    @csrf
    @method('PUT')

    <!-- Kode Barang (Readonly) -->
    <div class="field-group">
        <label for="kode_barang_edit" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
            Kode Barang
        </label>
        <div class="relative">
            <div class="input-prefix-icon absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400 z-10 transition-colors">
                <i data-lucide="barcode" class="w-4 h-4"></i>
            </div>
            <input type="text" name="kode_barang_produksi" id="kode_barang_edit" readonly
                value="{{ $barangproduksi->kode_barang_produksi }}"
                class="w-full h-[36px] pl-9 pr-3 text-xs font-semibold font-mono-num rounded-lg border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 text-slate-500 dark:text-slate-400 focus:outline-hidden cursor-not-allowed">
        </div>
    </div>

    <!-- Nama Barang -->
    <div class="field-group">
        <label for="nama_barang_edit" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
            Nama Barang Produksi <span class="text-rose-500">*</span>
        </label>
        <div class="relative">
            <div class="input-prefix-icon absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400 z-10 transition-colors">
                <i data-lucide="box" class="w-4 h-4"></i>
            </div>
            <input type="text" name="nama_barang" id="nama_barang_edit"
                value="{{ $barangproduksi->nama_barang }}"
                class="w-full h-[36px] pl-9 pr-3 text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition"
                placeholder="Misal: TEPUNG TAPIOKA SPESIAL">
        </div>
    </div>

    <!-- Satuan -->
    <div class="field-group">
        <label for="satuan_edit" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
            Satuan <span class="text-rose-500">*</span>
        </label>
        <div class="relative">
            <div class="input-prefix-icon absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400 z-10 transition-colors">
                <i data-lucide="scale" class="w-4 h-4"></i>
            </div>
            <input type="text" name="satuan" id="satuan_edit"
                value="{{ $barangproduksi->satuan }}"
                class="w-full h-[36px] pl-9 pr-3 text-xs font-semibold uppercase rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition"
                placeholder="Misal: KG, DUS, PCS, ROLL">
        </div>
    </div>

    <!-- Asal Barang -->
    <div class="field-group">
        <label for="kode_asal_barang_edit" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
            Asal Barang <span class="text-rose-500">*</span>
        </label>
        <div class="relative">
            <div class="input-prefix-icon absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400 z-10 transition-colors">
                <i data-lucide="warehouse" class="w-4 h-4"></i>
            </div>
            <select name="kode_asal_barang" id="kode_asal_barang_edit"
                class="w-full h-[36px] pl-9 pr-8 text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition appearance-none cursor-pointer">
                <option value="">Pilih Asal Barang</option>
                <option value="GD" {{ $barangproduksi->kode_asal_barang == 'GD' ? 'selected' : '' }}>Gudang</option>
                <option value="SS" {{ $barangproduksi->kode_asal_barang == 'SS' ? 'selected' : '' }}>Seasoning</option>
                <option value="TR" {{ $barangproduksi->kode_asal_barang == 'TR' ? 'selected' : '' }}>Trial</option>
            </select>
            <div class="absolute inset-y-0 right-0 pr-2.5 flex items-center pointer-events-none text-slate-400">
                <i data-lucide="chevron-down" class="w-4 h-4"></i>
            </div>
        </div>
    </div>

    <!-- Kategori Barang -->
    <div class="field-group">
        <label for="kode_kategori_edit" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
            Kategori Barang <span class="text-rose-500">*</span>
        </label>
        <div class="relative">
            <div class="input-prefix-icon absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400 z-10 transition-colors">
                <i data-lucide="tag" class="w-4 h-4"></i>
            </div>
            <select name="kode_kategori" id="kode_kategori_edit"
                class="w-full h-[36px] pl-9 pr-8 text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition appearance-none cursor-pointer">
                <option value="">Pilih Kategori Barang</option>
                @foreach ($list_kategori_barang_produksi as $d)
                    <option value="{{ $d['kode_kategori'] }}" {{ $barangproduksi->kode_kategori == $d['kode_kategori'] ? 'selected' : '' }}>
                        {{ $d['nama_kategori'] }}
                    </option>
                @endforeach
            </select>
            <div class="absolute inset-y-0 right-0 pr-2.5 flex items-center pointer-events-none text-slate-400">
                <i data-lucide="chevron-down" class="w-4 h-4"></i>
            </div>
        </div>
    </div>

    <!-- Status Aktif Barang -->
    <div class="field-group">
        <label for="status_aktif_barang_edit" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
            Status Aktif Barang <span class="text-rose-500">*</span>
        </label>
        <div class="relative">
            <div class="input-prefix-icon absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400 z-10 transition-colors">
                <i data-lucide="check-circle" class="w-4 h-4"></i>
            </div>
            <select name="status_aktif_barang" id="status_aktif_barang_edit"
                class="w-full h-[36px] pl-9 pr-8 text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition appearance-none cursor-pointer">
                <option value="">Pilih Status</option>
                <option value="1" {{ $barangproduksi->status_aktif_barang === '1' ? 'selected' : '' }}>Aktif</option>
                <option value="0" {{ $barangproduksi->status_aktif_barang === '0' ? 'selected' : '' }}>Non Aktif</option>
            </select>
            <div class="absolute inset-y-0 right-0 pr-2.5 flex items-center pointer-events-none text-slate-400">
                <i data-lucide="chevron-down" class="w-4 h-4"></i>
            </div>
        </div>
    </div>

    <!-- Submit Button -->
    <div class="pt-2">
        <button type="submit" id="btnSubmitEditBarang" data-loading-text="Memperbarui Data..."
            class="w-full h-[36px] rounded-lg bg-brand-600 hover:bg-brand-700 active:bg-brand-800 text-white font-bold text-xs flex items-center justify-center gap-1.5 shadow-xs transition active:scale-[0.99] cursor-pointer">
            <i data-lucide="refresh-cw" class="w-3.5 h-3.5"></i>
            <span>Simpan Perubahan</span>
        </button>
    </div>
</form>

<script>
    $(function() {
        const $form = $("#formeditBarang");
        const $nama = $form.find("#nama_barang_edit");
        const $satuan = $form.find("#satuan_edit");
        const $asal = $form.find("#kode_asal_barang_edit");
        const $kategori = $form.find("#kode_kategori_edit");
        const $status = $form.find("#status_aktif_barang_edit");
        const $btnSubmit = $form.find("#btnSubmitEditBarang");

        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }

        // Helper invalid
        function setFieldError($input, message) {
            const $container = $input.closest('.field-group');
            $input.addClass('border-rose-500 ring-1 ring-rose-500 focus:border-rose-500 focus:ring-rose-500/20')
                  .removeClass('border-slate-200 dark:border-slate-700');
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
            $container.find('.input-prefix-icon').removeClass('text-rose-500').addClass('text-slate-400');
            $container.find('.field-error-msg').remove();
        }

        $form.find('input, select').on('input change', function() {
            clearFieldError($(this));
        });

        // Submit form validation
        $form.on('submit', function(e) {
            let hasError = false;

            if (!$nama.val() || !$nama.val().toString().trim()) {
                setFieldError($nama, "Nama barang produksi wajib diisi!");
                hasError = true;
            }

            if (!$satuan.val() || !$satuan.val().toString().trim()) {
                setFieldError($satuan, "Satuan wajib diisi!");
                hasError = true;
            }

            if (!$asal.val()) {
                setFieldError($asal, "Asal barang wajib dipilih!");
                hasError = true;
            }

            if (!$kategori.val()) {
                setFieldError($kategori, "Kategori barang wajib dipilih!");
                hasError = true;
            }

            if (!$status.val()) {
                setFieldError($status, "Status aktif barang wajib dipilih!");
                hasError = true;
            }

            if (hasError) {
                e.preventDefault();
                if (typeof window.resetButtonSubmitting === 'function') {
                    window.resetButtonSubmitting($btnSubmit);
                }
                $nama.focus();
                return false;
            }

            if (typeof window.setButtonSubmitting === 'function') {
                window.setButtonSubmitting($btnSubmit, 'Memperbarui Data...');
            } else {
                $btnSubmit.prop('disabled', true).addClass('opacity-75 cursor-not-allowed pointer-events-none');
            }
            return true;
        });
    });
</script>
