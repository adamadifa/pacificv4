<form action="{{ route('harga.update', Crypt::encrypt($harga->kode_harga)) }}" id="formeditHarga" method="POST" class="space-y-2.5">
    @csrf
    @method('PUT')

    @php
        $isSuperAdmin = auth()->user()->hasRole('super admin');
    @endphp

    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2.5">
        <!-- Input Kode Harga (Readonly) -->
        <div class="field-group">
            <label for="kode_harga_edit" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                Kode Tarif Harga
            </label>
            <div class="relative">
                <div class="input-prefix-icon absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400 z-10 transition-colors">
                    <i data-lucide="hash" class="w-4 h-4"></i>
                </div>
                <input type="text" name="kode_harga" id="kode_harga_edit" value="{{ $harga->kode_harga }}" readonly
                    class="w-full h-[36px] pl-9 pr-3 text-xs font-bold uppercase rounded-lg border border-slate-200 dark:border-slate-700 bg-slate-100 dark:bg-slate-800/60 text-slate-500 dark:text-slate-400 cursor-not-allowed"
                    placeholder="Kode Tarif Harga">
            </div>
            <p class="text-[10px] text-slate-400 mt-0.5 pl-1">Kode harga bersifat permanen.</p>
        </div>

        <!-- Select Produk -->
        <div class="field-group">
            <label for="kode_produk_edit" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                Produk <span class="text-rose-500">*</span>
            </label>
            <div class="relative">
                <div class="input-prefix-icon absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400 z-10 transition-colors">
                    <i data-lucide="box" class="w-4 h-4"></i>
                </div>
                <select name="kode_produk" id="kode_produk_edit" {{ !$isSuperAdmin ? 'disabled' : '' }}
                    class="w-full h-[36px] pl-9 pr-8 text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-700 {{ !$isSuperAdmin ? 'bg-slate-100 dark:bg-slate-800/60 text-slate-500 cursor-not-allowed' : 'bg-white dark:bg-slate-800 text-slate-800 dark:text-slate-100 cursor-pointer' }} focus:outline-hidden focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition appearance-none">
                    <option value="">Pilih Produk</option>
                    @foreach ($produk as $p)
                        <option value="{{ $p->kode_produk }}" {{ $harga->kode_produk == $p->kode_produk ? 'selected' : '' }}>
                            {{ $p->kode_produk }} - {{ $p->nama_produk }}
                        </option>
                    @endforeach
                </select>
                <div class="absolute inset-y-0 right-0 pr-2.5 flex items-center pointer-events-none text-slate-400">
                    <i data-lucide="chevron-down" class="w-3.5 h-3.5"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- TARIF HARGA JUAL (3 COLS) -->
    <div class="p-3 rounded-lg bg-slate-50 dark:bg-slate-800/40 border border-slate-200/80 dark:border-slate-700/60 space-y-2">
        <span class="text-[11px] font-bold text-slate-700 dark:text-slate-300 flex items-center gap-1.5">
            <i data-lucide="tag" class="w-3.5 h-3.5 text-brand-600"></i>
            <span>Tarif Harga Jual Pokok</span>
        </span>
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-2.5">
            <!-- Harga Dus -->
            <div class="field-group">
                <label for="harga_dus_edit" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                    Harga Dus <span class="text-rose-500">*</span>
                </label>
                <div class="relative">
                    <div class="input-prefix-icon absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400 z-10">
                        <span class="text-[10px] font-bold">Rp</span>
                    </div>
                    <input type="text" name="harga_dus" id="harga_dus_edit" value="{{ formatRupiah($harga->harga_dus) }}"
                        class="money w-full h-[36px] pl-9 pr-3 text-right text-xs font-bold font-mono-num rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition"
                        placeholder="Harga / Dus">
                </div>
                <p class="text-[10px] text-slate-400 mt-0.5 pl-1">Harga 1 Dus.</p>
            </div>

            <!-- Harga Pack -->
            <div class="field-group">
                <label for="harga_pack_edit" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                    Harga Pack <span class="text-rose-500">*</span>
                </label>
                <div class="relative">
                    <div class="input-prefix-icon absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400 z-10">
                        <span class="text-[10px] font-bold">Rp</span>
                    </div>
                    <input type="text" name="harga_pack" id="harga_pack_edit" value="{{ formatRupiah($harga->harga_pack) }}"
                        class="money w-full h-[36px] pl-9 pr-3 text-right text-xs font-bold font-mono-num rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition"
                        placeholder="Harga / Pack">
                </div>
                <p class="text-[10px] text-slate-400 mt-0.5 pl-1">Harga 1 Pack.</p>
            </div>

            <!-- Harga Pcs -->
            <div class="field-group">
                <label for="harga_pcs_edit" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                    Harga Pcs <span class="text-rose-500">*</span>
                </label>
                <div class="relative">
                    <div class="input-prefix-icon absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400 z-10">
                        <span class="text-[10px] font-bold">Rp</span>
                    </div>
                    <input type="text" name="harga_pcs" id="harga_pcs_edit" value="{{ formatRupiah($harga->harga_pcs) }}"
                        class="money w-full h-[36px] pl-9 pr-3 text-right text-xs font-bold font-mono-num rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition"
                        placeholder="Harga / Pcs">
                </div>
                <p class="text-[10px] text-slate-400 mt-0.5 pl-1">Harga 1 Pcs.</p>
            </div>
        </div>
    </div>

    <!-- TARIF HARGA RETUR (3 COLS) -->
    <div class="p-3 rounded-lg bg-slate-50 dark:bg-slate-800/40 border border-slate-200/80 dark:border-slate-700/60 space-y-2">
        <span class="text-[11px] font-bold text-slate-700 dark:text-slate-300 flex items-center gap-1.5">
            <i data-lucide="rotate-ccw" class="w-3.5 h-3.5 text-rose-600"></i>
            <span>Tarif Harga Retur Kemasan</span>
        </span>
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-2.5">
            <!-- Retur Dus -->
            <div class="field-group">
                <label for="harga_retur_dus_edit" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                    Retur Dus <span class="text-rose-500">*</span>
                </label>
                <div class="relative">
                    <div class="input-prefix-icon absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400 z-10">
                        <span class="text-[10px] font-bold">Rp</span>
                    </div>
                    <input type="text" name="harga_retur_dus" id="harga_retur_dus_edit" value="{{ formatRupiah($harga->harga_retur_dus) }}"
                        class="money w-full h-[36px] pl-9 pr-3 text-right text-xs font-bold font-mono-num rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition"
                        placeholder="Retur / Dus">
                </div>
                <p class="text-[10px] text-slate-400 mt-0.5 pl-1">Retur 1 Dus.</p>
            </div>

            <!-- Retur Pack -->
            <div class="field-group">
                <label for="harga_retur_pack_edit" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                    Retur Pack <span class="text-rose-500">*</span>
                </label>
                <div class="relative">
                    <div class="input-prefix-icon absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400 z-10">
                        <span class="text-[10px] font-bold">Rp</span>
                    </div>
                    <input type="text" name="harga_retur_pack" id="harga_retur_pack_edit" value="{{ formatRupiah($harga->harga_retur_pack) }}"
                        class="money w-full h-[36px] pl-9 pr-3 text-right text-xs font-bold font-mono-num rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition"
                        placeholder="Retur / Pack">
                </div>
                <p class="text-[10px] text-slate-400 mt-0.5 pl-1">Retur 1 Pack.</p>
            </div>

            <!-- Retur Pcs -->
            <div class="field-group">
                <label for="harga_retur_pcs_edit" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                    Retur Pcs <span class="text-rose-500">*</span>
                </label>
                <div class="relative">
                    <div class="input-prefix-icon absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400 z-10">
                        <span class="text-[10px] font-bold">Rp</span>
                    </div>
                    <input type="text" name="harga_retur_pcs" id="harga_retur_pcs_edit" value="{{ formatRupiah($harga->harga_retur_pcs) }}"
                        class="money w-full h-[36px] pl-9 pr-3 text-right text-xs font-bold font-mono-num rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition"
                        placeholder="Retur / Pcs">
                </div>
                <p class="text-[10px] text-slate-400 mt-0.5 pl-1">Retur 1 Pcs.</p>
            </div>
        </div>
    </div>

    <!-- STATUS AKTIF, PPN, PROMO (3 COLS) -->
    @if ($isSuperAdmin)
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-2.5">
            <!-- Status Aktif -->
            <div class="field-group">
                <label for="status_aktif_harga_edit" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                    Status Aktif <span class="text-rose-500">*</span>
                </label>
                <div class="relative">
                    <div class="input-prefix-icon absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400 z-10 transition-colors">
                        <i data-lucide="toggle-left" class="w-4 h-4"></i>
                    </div>
                    <select name="status_aktif_harga" id="status_aktif_harga_edit"
                        class="w-full h-[36px] pl-9 pr-8 text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition cursor-pointer appearance-none">
                        <option value="">Status Keaktifan</option>
                        <option value="1" {{ (string)$harga->status_aktif_harga === '1' ? 'selected' : '' }}>Aktif</option>
                        <option value="0" {{ (string)$harga->status_aktif_harga === '0' ? 'selected' : '' }}>Non Aktif</option>
                    </select>
                    <div class="absolute inset-y-0 right-0 pr-2.5 flex items-center pointer-events-none text-slate-400">
                        <i data-lucide="chevron-down" class="w-3.5 h-3.5"></i>
                    </div>
                </div>
            </div>

            <!-- Status PPN -->
            <div class="field-group">
                <label for="status_ppn_edit" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                    Status PPN <span class="text-rose-500">*</span>
                </label>
                <div class="relative">
                    <div class="input-prefix-icon absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400 z-10 transition-colors">
                        <i data-lucide="percent" class="w-4 h-4"></i>
                    </div>
                    <select name="status_ppn" id="status_ppn_edit"
                        class="w-full h-[36px] pl-9 pr-8 text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition cursor-pointer appearance-none">
                        <option value="">Status PPN</option>
                        <option value="IN" {{ $harga->status_ppn === 'IN' ? 'selected' : '' }}>INCLUDE</option>
                        <option value="EX" {{ $harga->status_ppn === 'EX' ? 'selected' : '' }}>EXCLUDE</option>
                    </select>
                    <div class="absolute inset-y-0 right-0 pr-2.5 flex items-center pointer-events-none text-slate-400">
                        <i data-lucide="chevron-down" class="w-3.5 h-3.5"></i>
                    </div>
                </div>
            </div>

            <!-- Status Promo -->
            <div class="field-group">
                <label for="status_promo_edit" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                    Status Promo
                </label>
                <div class="relative">
                    <div class="input-prefix-icon absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400 z-10 transition-colors">
                        <i data-lucide="sparkles" class="w-4 h-4"></i>
                    </div>
                    <select name="status_promo" id="status_promo_edit"
                        class="w-full h-[36px] pl-9 pr-8 text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition cursor-pointer appearance-none">
                        <option value="">Status Promo</option>
                        <option value="1" {{ (string)$harga->status_promo === '1' ? 'selected' : '' }}>Aktif</option>
                        <option value="0" {{ (string)$harga->status_promo === '0' ? 'selected' : '' }}>Non Aktif</option>
                    </select>
                    <div class="absolute inset-y-0 right-0 pr-2.5 flex items-center pointer-events-none text-slate-400">
                        <i data-lucide="chevron-down" class="w-3.5 h-3.5"></i>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- KATEGORI SALESMAN & CABANG (2 COLS) -->
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2.5">
        <!-- Kategori Salesman -->
        <div class="field-group">
            <label for="kode_kategori_salesman_edit" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                Kategori Salesman <span class="text-rose-500">*</span>
            </label>
            <div class="relative">
                <div class="input-prefix-icon absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400 z-10 transition-colors">
                    <i data-lucide="user-check" class="w-4 h-4"></i>
                </div>
                <select name="kode_kategori_salesman" id="kode_kategori_salesman_edit" {{ !$isSuperAdmin ? 'disabled' : '' }}
                    class="w-full h-[36px] pl-9 pr-8 text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-700 {{ !$isSuperAdmin ? 'bg-slate-100 dark:bg-slate-800/60 text-slate-500 cursor-not-allowed' : 'bg-white dark:bg-slate-800 text-slate-800 dark:text-slate-100 cursor-pointer' }} focus:outline-hidden focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition appearance-none">
                    <option value="">Pilih Kategori Salesman</option>
                    @foreach ($kategori_salesman as $ks)
                        <option value="{{ $ks->kode_kategori_salesman }}" {{ $harga->kode_kategori_salesman == $ks->kode_kategori_salesman ? 'selected' : '' }}>
                            {{ $ks->nama_kategori_salesman }}
                        </option>
                    @endforeach
                </select>
                <div class="absolute inset-y-0 right-0 pr-2.5 flex items-center pointer-events-none text-slate-400">
                    <i data-lucide="chevron-down" class="w-3.5 h-3.5"></i>
                </div>
            </div>
        </div>

        <!-- Cabang -->
        @if ($isSuperAdmin)
            <div class="field-group">
                <label for="kode_cabang_edit" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                    Cabang <span class="text-rose-500">*</span>
                </label>
                <div class="relative">
                    <div class="input-prefix-icon absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400 z-10 transition-colors">
                        <i data-lucide="building-2" class="w-4 h-4"></i>
                    </div>
                    <select name="kode_cabang" id="kode_cabang_edit"
                        class="select2Cabang w-full h-[36px] text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-800 dark:text-slate-100">
                        <option value="">Pilih Cabang</option>
                        @foreach ($cabang as $c)
                            <option value="{{ $c->kode_cabang }}" {{ $harga->kode_cabang == $c->kode_cabang ? 'selected' : '' }}>
                                {{ $c->nama_cabang }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
        @else
            <input type="hidden" name="kode_cabang" value="{{ $harga->kode_cabang }}">
        @endif
    </div>

    <!-- Submit Button -->
    <div class="pt-1.5">
        <button type="submit" id="btnUpdateHarga" data-loading-text="Memperbarui Data Harga..."
            class="w-full h-[36px] rounded-lg bg-brand-600 hover:bg-brand-700 active:bg-brand-800 text-white font-bold text-xs flex items-center justify-center gap-1.5 shadow-xs transition active:scale-[0.99] cursor-pointer">
            <i data-lucide="check" class="w-3.5 h-3.5"></i>
            <span>Perbarui Data Harga</span>
        </button>
    </div>
</form>

<script>
    $(function() {
        const $form = $("#formeditHarga");
        const $hargaDus = $form.find("#harga_dus_edit");
        const $hargaPack = $form.find("#harga_pack_edit");
        const $hargaPcs = $form.find("#harga_pcs_edit");
        const $returDus = $form.find("#harga_retur_dus_edit");
        const $returPack = $form.find("#harga_retur_pack_edit");
        const $returPcs = $form.find("#harga_retur_pcs_edit");
        const $btnSubmit = $form.find("#btnUpdateHarga");

        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }

        // Initialize money mask
        if ($.fn.maskMoney) {
            $form.find(".money").maskMoney({
                thousands: '.',
                decimal: ',',
                precision: 0
            });
        }

        // Init Select2 Cabang
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

        // Helper untuk menandai input invalid
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

        // Helper untuk membersihkan tanda error
        function clearFieldError($input) {
            const $container = $input.closest('.field-group');
            $input.removeClass('border-rose-500 ring-1 ring-rose-500 focus:border-rose-500 focus:ring-rose-500/20')
                  .addClass('border-slate-200 dark:border-slate-700');
            $container.find('.select2-selection--single').removeClass('!border-rose-500 !ring-1 !ring-rose-500');
            $container.find('.input-prefix-icon').removeClass('text-rose-500').addClass('text-slate-400');
            $container.find('.field-error-msg').remove();
        }

        // Event listener saat user memperbaiki isi input
        $form.find('input, select').on('input change select2:select select2:clear', function() {
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

            checkRequired($returPcs, "Harga retur pcs wajib diisi!");
            checkRequired($returPack, "Harga retur pack wajib diisi!");
            checkRequired($returDus, "Harga retur dus wajib diisi!");
            checkRequired($hargaPcs, "Harga pcs wajib diisi!");
            checkRequired($hargaPack, "Harga pack wajib diisi!");
            checkRequired($hargaDus, "Harga dus wajib diisi!");

            if (hasError) {
                e.preventDefault();
                if (typeof window.resetButtonSubmitting === 'function') {
                    window.resetButtonSubmitting($btnSubmit);
                }
                firstInvalid.focus();
                return false;
            }

            if (typeof window.setButtonSubmitting === 'function') {
                window.setButtonSubmitting($btnSubmit, 'Memperbarui Data Harga...');
            } else {
                $btnSubmit.prop('disabled', true).addClass('opacity-75 cursor-not-allowed pointer-events-none');
            }
            return true;
        });
    });
</script>
