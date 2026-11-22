<form action="{{ route('produk.update', Crypt::encrypt($produk->kode_produk)) }}" id="formeditProduk" method="POST" class="space-y-2.5">
    @csrf
    @method('PUT')

    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2.5">
        <!-- Input Kode Produk (Readonly) -->
        <div class="field-group">
            <label for="kode_produk_edit" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                Kode Produk
            </label>
            <div class="relative">
                <div class="input-prefix-icon absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400 z-10 transition-colors">
                    <i data-lucide="hash" class="w-4 h-4"></i>
                </div>
                <input type="text" name="kode_produk" id="kode_produk_edit" value="{{ $produk->kode_produk }}" readonly
                    class="w-full h-[36px] pl-9 pr-3 text-xs font-bold uppercase rounded-lg border border-slate-200 dark:border-slate-700 bg-slate-100 dark:bg-slate-800/60 text-slate-500 dark:text-slate-400 cursor-not-allowed"
                    placeholder="Kode Produk">
            </div>
            <p class="text-[10px] text-slate-400 mt-0.5 pl-1">Kode produk bersifat permanen.</p>
        </div>

        <!-- Input Nama Produk -->
        <div class="field-group">
            <label for="nama_produk_edit" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                Nama Produk <span class="text-rose-500">*</span>
            </label>
            <div class="relative">
                <div class="input-prefix-icon absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400 z-10 transition-colors">
                    <i data-lucide="box" class="w-4 h-4"></i>
                </div>
                <input type="text" name="nama_produk" id="nama_produk_edit" value="{{ $produk->nama_produk }}" maxlength="100" autofocus
                    class="w-full h-[36px] pl-9 pr-3 text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition"
                    placeholder="Nama Lengkap Produk">
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2.5">
        <!-- Select Jenis Produk -->
        <div class="field-group">
            <label for="kode_jenis_produk_edit" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                Jenis Produk <span class="text-rose-500">*</span>
            </label>
            <div class="relative">
                <div class="input-prefix-icon absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400 z-10 transition-colors">
                    <i data-lucide="grid" class="w-4 h-4"></i>
                </div>
                <select name="kode_jenis_produk" id="kode_jenis_produk_edit"
                    class="w-full h-[36px] pl-9 pr-8 text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition cursor-pointer appearance-none">
                    <option value="">Pilih Jenis Produk</option>
                    @foreach ($jenisproduk as $j)
                        <option value="{{ $j->kode_jenis_produk }}" {{ $produk->kode_jenis_produk == $j->kode_jenis_produk ? 'selected' : '' }}>
                            {{ $j->nama_jenis_produk }}
                        </option>
                    @endforeach
                </select>
                <div class="absolute inset-y-0 right-0 pr-2.5 flex items-center pointer-events-none text-slate-400">
                    <i data-lucide="chevron-down" class="w-3.5 h-3.5"></i>
                </div>
            </div>
        </div>

        <!-- Select Kategori Produk -->
        <div class="field-group">
            <label for="kode_kategori_produk_edit" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                Kategori Produk <span class="text-rose-500">*</span>
            </label>
            <div class="relative">
                <div class="input-prefix-icon absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400 z-10 transition-colors">
                    <i data-lucide="layers" class="w-4 h-4"></i>
                </div>
                <select name="kode_kategori_produk" id="kode_kategori_produk_edit"
                    class="w-full h-[36px] pl-9 pr-8 text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition cursor-pointer appearance-none">
                    <option value="">Pilih Kategori Produk</option>
                    @foreach ($kategoriproduk as $k)
                        <option value="{{ $k->kode_kategori_produk }}" {{ $produk->kode_kategori_produk == $k->kode_kategori_produk ? 'selected' : '' }}>
                            {{ $k->nama_kategori_produk }}
                        </option>
                    @endforeach
                </select>
                <div class="absolute inset-y-0 right-0 pr-2.5 flex items-center pointer-events-none text-slate-400">
                    <i data-lucide="chevron-down" class="w-3.5 h-3.5"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Satuan & Status -->
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2.5">
        <!-- Satuan -->
        <div class="field-group">
            <label for="satuan_edit" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                Satuan <span class="text-rose-500">*</span>
            </label>
            <div class="relative">
                <div class="input-prefix-icon absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400 z-10 transition-colors">
                    <i data-lucide="tag" class="w-4 h-4"></i>
                </div>
                <input type="text" name="satuan" id="satuan_edit" value="{{ $produk->satuan }}" maxlength="20"
                    class="w-full h-[36px] pl-9 pr-3 text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition"
                    placeholder="Satuan">
            </div>
        </div>

        <!-- Status Aktif -->
        <div class="field-group">
            <label for="status_aktif_produk_edit" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                Status Produk <span class="text-rose-500">*</span>
            </label>
            <div class="relative">
                <div class="input-prefix-icon absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400 z-10 transition-colors">
                    <i data-lucide="toggle-left" class="w-4 h-4"></i>
                </div>
                <select name="status_aktif_produk" id="status_aktif_produk_edit"
                    class="w-full h-[36px] pl-9 pr-8 text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition cursor-pointer appearance-none">
                    <option value="">Status Keaktifan</option>
                    <option value="1" {{ (string)$produk->status_aktif_produk === '1' ? 'selected' : '' }}>Aktif</option>
                    <option value="0" {{ (string)$produk->status_aktif_produk === '0' ? 'selected' : '' }}>Non Aktif</option>
                </select>
                <div class="absolute inset-y-0 right-0 pr-2.5 flex items-center pointer-events-none text-slate-400">
                    <i data-lucide="chevron-down" class="w-3.5 h-3.5"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Konversi Kemasan (3 Cols) -->
    <div class="p-3 rounded-lg bg-slate-50 dark:bg-slate-800/40 border border-slate-200/80 dark:border-slate-700/60 space-y-2">
        <span class="text-[11px] font-bold text-slate-700 dark:text-slate-300 flex items-center gap-1.5">
            <i data-lucide="package" class="w-3.5 h-3.5 text-brand-600"></i>
            <span>Konversi Isi Satuan Kemasan</span>
        </span>
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-2.5">
            <div class="field-group">
                <label for="isi_pcs_dus_edit" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                    Isi Pcs / Dus <span class="text-rose-500">*</span>
                </label>
                <div class="relative">
                    <div class="input-prefix-icon absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400 z-10">
                        <i data-lucide="hash" class="w-4 h-4"></i>
                    </div>
                    <input type="number" name="isi_pcs_dus" id="isi_pcs_dus_edit" value="{{ $produk->isi_pcs_dus }}" min="1"
                        class="w-full h-[36px] pl-9 pr-3 text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition"
                        placeholder="Isi Pcs / Dus">
                </div>
                <p class="text-[10px] text-slate-400 mt-0.5 pl-1">Pcs dlm 1 Dus.</p>
            </div>

            <div class="field-group">
                <label for="isi_pack_dus_edit" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                    Isi Pack / Dus <span class="text-rose-500">*</span>
                </label>
                <div class="relative">
                    <div class="input-prefix-icon absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400 z-10">
                        <i data-lucide="hash" class="w-4 h-4"></i>
                    </div>
                    <input type="number" name="isi_pack_dus" id="isi_pack_dus_edit" value="{{ $produk->isi_pack_dus }}" min="1"
                        class="w-full h-[36px] pl-9 pr-3 text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition"
                        placeholder="Isi Pack / Dus">
                </div>
                <p class="text-[10px] text-slate-400 mt-0.5 pl-1">Pack dlm 1 Dus.</p>
            </div>

            <div class="field-group">
                <label for="isi_pcs_pack_edit" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                    Isi Pcs / Pack <span class="text-rose-500">*</span>
                </label>
                <div class="relative">
                    <div class="input-prefix-icon absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400 z-10">
                        <i data-lucide="hash" class="w-4 h-4"></i>
                    </div>
                    <input type="number" name="isi_pcs_pack" id="isi_pcs_pack_edit" value="{{ $produk->isi_pcs_pack }}" min="1"
                        class="w-full h-[36px] pl-9 pr-3 text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition"
                        placeholder="Isi Pcs / Pack">
                </div>
                <p class="text-[10px] text-slate-400 mt-0.5 pl-1">Pcs dlm 1 Pack.</p>
            </div>
        </div>
    </div>

    <!-- Kategori Diskon -->
    <div class="field-group">
        <label for="kode_kategori_diskon_edit" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
            Kategori Diskon
        </label>
        <div class="relative">
            <div class="input-prefix-icon absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400 z-10 transition-colors">
                <i data-lucide="percent" class="w-4 h-4"></i>
            </div>
            <select name="kode_kategori_diskon" id="kode_kategori_diskon_edit"
                class="w-full h-[36px] pl-9 pr-8 text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition cursor-pointer appearance-none">
                <option value="">Pilih Kategori Diskon</option>
                @foreach ($kategoridiskon as $kd)
                    <option value="{{ $kd->kode_kategori_diskon }}" {{ $produk->kode_kategori_diskon == $kd->kode_kategori_diskon ? 'selected' : '' }}>
                        {{ $kd->kode_kategori_diskon }} - {{ $kd->nama_kategori }}
                    </option>
                @endforeach
            </select>
            <div class="absolute inset-y-0 right-0 pr-2.5 flex items-center pointer-events-none text-slate-400">
                <i data-lucide="chevron-down" class="w-3.5 h-3.5"></i>
            </div>
        </div>
    </div>

    <!-- SKU & Urutan -->
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2.5">
        <!-- Kode SKU -->
        <div class="field-group">
            <label for="kode_sku_edit" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                Kode SKU <span class="text-rose-500">*</span>
            </label>
            <div class="relative">
                <div class="input-prefix-icon absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400 z-10 transition-colors">
                    <i data-lucide="qr-code" class="w-4 h-4"></i>
                </div>
                <input type="text" name="kode_sku" id="kode_sku_edit" value="{{ $produk->kode_sku }}" maxlength="30"
                    class="w-full h-[36px] pl-9 pr-3 text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition"
                    placeholder="Kode SKU Produk">
            </div>
        </div>

        <!-- Urutan -->
        <div class="field-group">
            <label for="urutan_edit" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                Urutan Tampil
            </label>
            <div class="relative">
                <div class="input-prefix-icon absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400 z-10 transition-colors">
                    <i data-lucide="list-ordered" class="w-4 h-4"></i>
                </div>
                <input type="number" name="urutan" id="urutan_edit" value="{{ $produk->urutan }}" min="0"
                    class="w-full h-[36px] pl-9 pr-3 text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition"
                    placeholder="Urutan Tampil">
            </div>
        </div>
    </div>

    <!-- Submit Button -->
    <div class="pt-1.5">
        <button type="submit" id="btnUpdateProduk" data-loading-text="Memperbarui Produk..."
            class="w-full h-[36px] rounded-lg bg-brand-600 hover:bg-brand-700 active:bg-brand-800 text-white font-bold text-xs flex items-center justify-center gap-1.5 shadow-xs transition active:scale-[0.99] cursor-pointer">
            <i data-lucide="check" class="w-3.5 h-3.5"></i>
            <span>Perbarui Data Produk</span>
        </button>
    </div>
</form>

<script>
    $(function() {
        const $form = $("#formeditProduk");
        const $nama = $form.find("#nama_produk_edit");
        const $jenis = $form.find("#kode_jenis_produk_edit");
        const $kategori = $form.find("#kode_kategori_produk_edit");
        const $satuan = $form.find("#satuan_edit");
        const $pcsDus = $form.find("#isi_pcs_dus_edit");
        const $packDus = $form.find("#isi_pack_dus_edit");
        const $pcsPack = $form.find("#isi_pcs_pack_edit");
        const $status = $form.find("#status_aktif_produk_edit");
        const $sku = $form.find("#kode_sku_edit");
        const $kategoriDiskon = $form.find("#kode_kategori_diskon_edit");
        const $btnSubmit = $form.find("#btnUpdateProduk");

        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }

        // Helper untuk menandai input invalid
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

        // Helper untuk membersihkan tanda error
        function clearFieldError($input) {
            const $container = $input.closest('.field-group');
            $input.removeClass('border-rose-500 ring-1 ring-rose-500 focus:border-rose-500 focus:ring-rose-500/20')
                  .addClass('border-slate-200 dark:border-slate-700');
            $container.find('.input-prefix-icon').removeClass('text-rose-500').addClass('text-slate-400');
            $container.find('.field-error-msg').remove();
        }

        // Event listener saat user memperbaiki isi input
        $form.find('input, select').on('input change', function() {
            clearFieldError($(this));
        });

        // Submit form validation
        $form.on('submit', function(e) {
            let hasError = false;
            let firstInvalid = null;

            function checkRequired($input, msg) {
                if (!$input.val() || !$input.val().toString().trim()) {
                    setFieldError($input, msg);
                    hasError = true;
                    if (!firstInvalid) firstInvalid = $input;
                }
            }

            checkRequired($kategoriDiskon, "Kategori diskon wajib dipilih!");
            checkRequired($sku, "Kode SKU wajib diisi!");
            checkRequired($status, "Status produk wajib dipilih!");
            checkRequired($pcsPack, "Isi Pcs/Pack wajib diisi!");
            checkRequired($packDus, "Isi Pack/Dus wajib diisi!");
            checkRequired($pcsDus, "Isi Pcs/Dus wajib diisi!");
            checkRequired($satuan, "Satuan produk wajib diisi!");
            checkRequired($kategori, "Kategori produk wajib dipilih!");
            checkRequired($jenis, "Jenis produk wajib dipilih!");
            checkRequired($nama, "Nama produk wajib diisi!");

            if (hasError) {
                e.preventDefault();
                if (typeof window.resetButtonSubmitting === 'function') {
                    window.resetButtonSubmitting($btnSubmit);
                }
                firstInvalid.focus();
                return false;
            }

            if (typeof window.setButtonSubmitting === 'function') {
                window.setButtonSubmitting($btnSubmit, 'Memperbarui Produk...');
            } else {
                $btnSubmit.prop('disabled', true).addClass('opacity-75 cursor-not-allowed pointer-events-none');
            }
            return true;
        });
    });
</script>
