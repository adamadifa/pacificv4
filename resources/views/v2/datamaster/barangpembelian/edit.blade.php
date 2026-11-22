<form action="{{ route('barangpembelian.update', Crypt::encrypt($barangpembelian->kode_barang)) }}" id="formeditBarangpembelian" method="POST" class="space-y-2.5">
    @csrf
    @method('PUT')

    <!-- Kode Barang -->
    <div class="field-group">
        <label for="kode_barang_edit" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
            Kode Barang <span class="text-rose-500">*</span>
        </label>
        <div class="relative">
            <div class="input-prefix-icon absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400 z-10 transition-colors">
                <i data-lucide="barcode" class="w-4 h-4"></i>
            </div>
            <input type="text" name="kode_barang" id="kode_barang_edit" value="{{ $barangpembelian->kode_barang }}" autofocus
                class="w-full h-[36px] pl-9 pr-3 text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-800 dark:text-slate-100 uppercase focus:outline-hidden focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition font-mono-num"
                placeholder="Kode Barang">
        </div>
    </div>

    <!-- Nama Barang -->
    <div class="field-group">
        <label for="nama_barang_edit" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
            Nama Barang <span class="text-rose-500">*</span>
        </label>
        <div class="relative">
            <div class="input-prefix-icon absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400 z-10 transition-colors">
                <i data-lucide="box" class="w-4 h-4"></i>
            </div>
            <input type="text" name="nama_barang" id="nama_barang_edit" value="{{ $barangpembelian->nama_barang }}"
                class="w-full h-[36px] pl-9 pr-3 text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition"
                placeholder="Nama Lengkap Barang">
        </div>
    </div>

    <!-- Satuan & Jenis Barang (2 COLS) -->
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2.5">
        <!-- Satuan -->
        <div class="field-group">
            <label for="satuan_edit" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                Satuan <span class="text-rose-500">*</span>
            </label>
            <div class="relative">
                <div class="input-prefix-icon absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400 z-10 transition-colors">
                    <i data-lucide="scale" class="w-4 h-4"></i>
                </div>
                <input type="text" name="satuan" id="satuan_edit" value="{{ $barangpembelian->satuan }}"
                    class="w-full h-[36px] pl-9 pr-3 text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-800 dark:text-slate-100 uppercase focus:outline-hidden focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition"
                    placeholder="Contoh: PCS, DUS, KG">
            </div>
        </div>

        <!-- Jenis Barang -->
        <div class="field-group">
            <label for="kode_jenis_barang_edit" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                Jenis Barang <span class="text-rose-500">*</span>
            </label>
            <div class="relative">
                <div class="input-prefix-icon absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400 z-10 transition-colors">
                    <i data-lucide="tag" class="w-4 h-4"></i>
                </div>
                <select name="kode_jenis_barang" id="kode_jenis_barang_edit"
                    class="w-full h-[36px] pl-9 pr-8 text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition cursor-pointer appearance-none">
                    <option value="">Pilih Jenis</option>
                    @foreach ($list_jenis_barang as $d)
                        <option value="{{ $d['kode_jenis_barang'] }}" {{ $barangpembelian->kode_jenis_barang == $d['kode_jenis_barang'] ? 'selected' : '' }}>
                            {{ $d['nama_jenis_barang'] }}
                        </option>
                    @endforeach
                </select>
                <div class="absolute inset-y-0 right-0 pr-2.5 flex items-center pointer-events-none text-slate-400">
                    <i data-lucide="chevron-down" class="w-3.5 h-3.5"></i>
                </div>
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
                <i data-lucide="folder" class="w-4 h-4"></i>
            </div>
            <select name="kode_kategori" id="kode_kategori_edit"
                class="w-full h-[36px] pl-9 pr-8 text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition cursor-pointer appearance-none">
                <option value="">Pilih Kategori</option>
                @foreach ($kategori as $d)
                    <option value="{{ $d->kode_kategori }}" {{ $barangpembelian->kode_kategori == $d->kode_kategori ? 'selected' : '' }}>
                        {{ $d->nama_kategori }}
                    </option>
                @endforeach
            </select>
            <div class="absolute inset-y-0 right-0 pr-2.5 flex items-center pointer-events-none text-slate-400">
                <i data-lucide="chevron-down" class="w-3.5 h-3.5"></i>
            </div>
        </div>
    </div>

    <!-- Group & Status (2 COLS) -->
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2.5">
        <!-- Group -->
        <div class="field-group">
            <label for="kode_group_edit" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                Group Pembelian <span class="text-rose-500">*</span>
            </label>
            <div class="relative">
                <div class="input-prefix-icon absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400 z-10 transition-colors">
                    <i data-lucide="layers" class="w-4 h-4"></i>
                </div>
                <select name="kode_group" id="kode_group_edit"
                    class="w-full h-[36px] pl-9 pr-8 text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition cursor-pointer appearance-none">
                    <option value="">Pilih Group</option>
                    @foreach ($list_group as $d)
                        <option value="{{ $d['kode_group'] }}" {{ $barangpembelian->kode_group == $d['kode_group'] ? 'selected' : '' }}>
                            {{ $d['nama_group'] }}
                        </option>
                    @endforeach
                </select>
                <div class="absolute inset-y-0 right-0 pr-2.5 flex items-center pointer-events-none text-slate-400">
                    <i data-lucide="chevron-down" class="w-3.5 h-3.5"></i>
                </div>
            </div>
        </div>

        <!-- Status -->
        <div class="field-group">
            <label for="status_edit" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                Status Barang <span class="text-rose-500">*</span>
            </label>
            <div class="relative">
                <div class="input-prefix-icon absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400 z-10 transition-colors">
                    <i data-lucide="check-circle-2" class="w-4 h-4"></i>
                </div>
                <select name="status" id="status_edit"
                    class="w-full h-[36px] pl-9 pr-8 text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition cursor-pointer appearance-none">
                    <option value="">Pilih Status</option>
                    <option value="1" {{ $barangpembelian->status == '1' ? 'selected' : '' }}>Aktif</option>
                    <option value="0" {{ $barangpembelian->status === '0' ? 'selected' : '' }}>Non Aktif</option>
                </select>
                <div class="absolute inset-y-0 right-0 pr-2.5 flex items-center pointer-events-none text-slate-400">
                    <i data-lucide="chevron-down" class="w-3.5 h-3.5"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Submit Button -->
    <div class="pt-2">
        <button type="submit" id="btnUpdateBarangpembelian" data-loading-text="Memperbarui Barang..."
            class="w-full h-[36px] rounded-lg bg-brand-600 hover:bg-brand-700 active:bg-brand-800 text-white font-bold text-xs flex items-center justify-center gap-1.5 shadow-xs transition active:scale-[0.99] cursor-pointer">
            <i data-lucide="check" class="w-3.5 h-3.5"></i>
            <span>Perbarui Data Barang</span>
        </button>
    </div>
</form>

<script>
    $(function() {
        const $form = $("#formeditBarangpembelian");
        const $kode = $form.find("#kode_barang_edit");
        const $nama = $form.find("#nama_barang_edit");
        const $satuan = $form.find("#satuan_edit");
        const $jenis = $form.find("#kode_jenis_barang_edit");
        const $kategori = $form.find("#kode_kategori_edit");
        const $group = $form.find("#kode_group_edit");
        const $status = $form.find("#status_edit");
        const $btnSubmit = $form.find("#btnUpdateBarangpembelian");

        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }

        // Auto uppercase untuk kode barang & satuan
        $kode.add($satuan).on('input', function() {
            $(this).val($(this).val().toUpperCase());
        });

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

            checkRequired($status, "Status barang wajib dipilih!");
            checkRequired($group, "Group pembelian wajib dipilih!");
            checkRequired($kategori, "Kategori barang wajib dipilih!");
            checkRequired($jenis, "Jenis barang wajib dipilih!");
            checkRequired($satuan, "Satuan barang wajib diisi!");
            checkRequired($nama, "Nama barang wajib diisi!");
            checkRequired($kode, "Kode barang wajib diisi!");

            if (hasError) {
                e.preventDefault();
                if (typeof window.resetButtonSubmitting === 'function') {
                    window.resetButtonSubmitting($btnSubmit);
                }
                firstInvalid.focus();
                return false;
            }

            if (typeof window.setButtonSubmitting === 'function') {
                window.setButtonSubmitting($btnSubmit, 'Memperbarui Barang...');
            } else {
                $btnSubmit.prop('disabled', true).addClass('opacity-75 cursor-not-allowed pointer-events-none');
            }
            return true;
        });
    });
</script>