<form action="{{ route('kendaraan.update', Crypt::encrypt($kendaraan->kode_kendaraan)) }}" id="formeditKendaraan" method="POST" class="space-y-2.5">
    @csrf
    @method('PUT')

    <!-- ROW 1: KODE & NO POLISI -->
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2.5">
        <div class="field-group">
            <label for="kode_kendaraan_edit" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                Kode Kendaraan
            </label>
            <div class="relative">
                <div class="input-prefix-icon absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400 z-10 transition-colors">
                    <i data-lucide="barcode" class="w-4 h-4"></i>
                </div>
                <input type="text" name="kode_kendaraan" id="kode_kendaraan_edit" value="{{ $kendaraan->kode_kendaraan }}" disabled
                    class="w-full h-[36px] pl-9 pr-3 text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800/50 text-slate-500 font-mono-num cursor-not-allowed">
            </div>
        </div>

        <div class="field-group">
            <label for="no_polisi_edit" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                No. Polisi <span class="text-rose-500">*</span>
            </label>
            <div class="relative">
                <div class="input-prefix-icon absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400 z-10 transition-colors">
                    <i data-lucide="hash" class="w-4 h-4"></i>
                </div>
                <input type="text" name="no_polisi" id="no_polisi_edit" value="{{ $kendaraan->no_polisi }}" autofocus
                    class="w-full h-[36px] pl-9 pr-3 text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-800 dark:text-slate-100 uppercase focus:outline-hidden focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition"
                    placeholder="Contoh: D 1234 ABC">
            </div>
        </div>
    </div>

    <!-- ROW 2: NO STNK & NO UJI -->
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2.5">
        <div class="field-group">
            <label for="no_stnk_edit" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                No. STNK
            </label>
            <div class="relative">
                <div class="input-prefix-icon absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400 z-10 transition-colors">
                    <i data-lucide="file-text" class="w-4 h-4"></i>
                </div>
                <input type="text" name="no_stnk" id="no_stnk_edit" value="{{ $kendaraan->no_stnk }}"
                    class="w-full h-[36px] pl-9 pr-3 text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition"
                    placeholder="Nomor STNK">
            </div>
        </div>

        <div class="field-group">
            <label for="no_uji_edit" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                No. Uji (KIR)
            </label>
            <div class="relative">
                <div class="input-prefix-icon absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400 z-10 transition-colors">
                    <i data-lucide="file-check" class="w-4 h-4"></i>
                </div>
                <input type="text" name="no_uji" id="no_uji_edit" value="{{ $kendaraan->no_uji }}"
                    class="w-full h-[36px] pl-9 pr-3 text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition"
                    placeholder="Nomor Uji Berkala">
            </div>
        </div>
    </div>

    <!-- ROW 3: SIPA & MERK -->
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2.5">
        <div class="field-group">
            <label for="sipa_edit" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                SIPA
            </label>
            <div class="relative">
                <div class="input-prefix-icon absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400 z-10 transition-colors">
                    <i data-lucide="shield-check" class="w-4 h-4"></i>
                </div>
                <input type="text" name="sipa" id="sipa_edit" value="{{ $kendaraan->sipa }}"
                    class="w-full h-[36px] pl-9 pr-3 text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition"
                    placeholder="Nomor Izin Trayek / SIPA">
            </div>
        </div>

        <div class="field-group">
            <label for="merk_edit" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                Merk Kendaraan
            </label>
            <div class="relative">
                <div class="input-prefix-icon absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400 z-10 transition-colors">
                    <i data-lucide="tag" class="w-4 h-4"></i>
                </div>
                <input type="text" name="merk" id="merk_edit" value="{{ $kendaraan->merek }}"
                    class="w-full h-[36px] pl-9 pr-3 text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition"
                    placeholder="Contoh: Mitsubishi, Isuzu, Toyota">
            </div>
        </div>
    </div>

    <!-- ROW 4: TIPE KENDARAAN & SUB TIPE -->
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2.5">
        <div class="field-group">
            <label for="tipe_kendaraan_edit" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                Tipe Kendaraan
            </label>
            <div class="relative">
                <div class="input-prefix-icon absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400 z-10 transition-colors">
                    <i data-lucide="truck" class="w-4 h-4"></i>
                </div>
                <input type="text" name="tipe_kendaraan" id="tipe_kendaraan_edit" value="{{ $kendaraan->tipe_kendaraan }}"
                    class="w-full h-[36px] pl-9 pr-3 text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition"
                    placeholder="Contoh: Truk Box, Blind Van, Pick Up">
            </div>
        </div>

        <div class="field-group">
            <label for="tipe_edit" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                Sub Tipe / Varian
            </label>
            <div class="relative">
                <div class="input-prefix-icon absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400 z-10 transition-colors">
                    <i data-lucide="layers" class="w-4 h-4"></i>
                </div>
                <input type="text" name="tipe" id="tipe_edit" value="{{ $kendaraan->tipe }}"
                    class="w-full h-[36px] pl-9 pr-3 text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition"
                    placeholder="Contoh: FE 71, Elf NLR, Gran Max">
            </div>
        </div>
    </div>

    <!-- ROW 5: NO RANGKA & NO MESIN -->
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2.5">
        <div class="field-group">
            <label for="no_rangka_edit" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                No. Rangka
            </label>
            <div class="relative">
                <div class="input-prefix-icon absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400 z-10 transition-colors">
                    <i data-lucide="binary" class="w-4 h-4"></i>
                </div>
                <input type="text" name="no_rangka" id="no_rangka_edit" value="{{ $kendaraan->no_rangka }}"
                    class="w-full h-[36px] pl-9 pr-3 text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-800 dark:text-slate-100 uppercase focus:outline-hidden focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition"
                    placeholder="Nomor Rangka Kendaraan">
            </div>
        </div>

        <div class="field-group">
            <label for="no_mesin_edit" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                No. Mesin
            </label>
            <div class="relative">
                <div class="input-prefix-icon absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400 z-10 transition-colors">
                    <i data-lucide="cpu" class="w-4 h-4"></i>
                </div>
                <input type="text" name="no_mesin" id="no_mesin_edit" value="{{ $kendaraan->no_mesin }}"
                    class="w-full h-[36px] pl-9 pr-3 text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-800 dark:text-slate-100 uppercase focus:outline-hidden focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition"
                    placeholder="Nomor Mesin Kendaraan">
            </div>
        </div>
    </div>

    <!-- ROW 6: TAHUN & ATAS NAMA -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-2.5">
        <div class="field-group">
            <label for="tahun_pembuatan_edit" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                Tahun Pembuatan
            </label>
            <div class="relative">
                <div class="input-prefix-icon absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400 z-10 transition-colors">
                    <i data-lucide="calendar" class="w-4 h-4"></i>
                </div>
                <input type="number" name="tahun_pembuatan" id="tahun_pembuatan_edit" value="{{ $kendaraan->tahun_pembuatan }}" min="1990" max="{{ date('Y') + 1 }}"
                    class="w-full h-[36px] pl-9 pr-3 text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition font-mono-num"
                    placeholder="Contoh: 2021">
            </div>
        </div>

        <div class="field-group sm:col-span-2">
            <label for="atas_nama_edit" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                Atas Nama (Kepemilikan)
            </label>
            <div class="relative">
                <div class="input-prefix-icon absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400 z-10 transition-colors">
                    <i data-lucide="user-check" class="w-4 h-4"></i>
                </div>
                <input type="text" name="atas_nama" id="atas_nama_edit" value="{{ $kendaraan->atas_nama }}"
                    class="w-full h-[36px] pl-9 pr-3 text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition"
                    placeholder="Nama Pemilik STNK">
            </div>
        </div>
    </div>

    <!-- ROW 7: ALAMAT -->
    <div class="field-group">
        <label for="alamat_edit" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
            Alamat STNK
        </label>
        <div class="relative">
            <div class="input-prefix-icon absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400 z-10 transition-colors">
                <i data-lucide="map-pin" class="w-4 h-4"></i>
            </div>
            <input type="text" name="alamat" id="alamat_edit" value="{{ $kendaraan->alamat }}"
                class="w-full h-[36px] pl-9 pr-3 text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition"
                placeholder="Alamat Sesuai STNK">
        </div>
    </div>

    <!-- ROW 8: PAJAK 1 TAHUN & PAJAK 5 TAHUN (FLATPICKR) -->
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2.5">
        <div class="field-group">
            <label for="jatuhtempo_pajak_satutahun_edit" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                Jatuh Tempo Pajak 1 Th <span class="text-rose-500">*</span>
            </label>
            <div class="relative">
                <div class="input-prefix-icon absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400 z-10 transition-colors">
                    <i data-lucide="calendar-check" class="w-4 h-4"></i>
                </div>
                <input type="text" name="jatuhtempo_pajak_satutahun" id="jatuhtempo_pajak_satutahun_edit" readonly
                    value="{{ $kendaraan->jatuhtempo_pajak_satutahun }}"
                    class="flatpickr-date w-full h-[36px] pl-9 pr-3 text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition cursor-pointer font-mono-num"
                    placeholder="Pilih Tanggal">
            </div>
        </div>

        <div class="field-group">
            <label for="jatuhtempo_pajak_limatahun_edit" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                Jatuh Tempo Pajak 5 Th <span class="text-rose-500">*</span>
            </label>
            <div class="relative">
                <div class="input-prefix-icon absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400 z-10 transition-colors">
                    <i data-lucide="calendar-range" class="w-4 h-4"></i>
                </div>
                <input type="text" name="jatuhtempo_pajak_limatahun" id="jatuhtempo_pajak_limatahun_edit" readonly
                    value="{{ $kendaraan->jatuhtempo_pajak_limatahun }}"
                    class="flatpickr-date w-full h-[36px] pl-9 pr-3 text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition cursor-pointer font-mono-num"
                    placeholder="Pilih Tanggal">
            </div>
        </div>
    </div>

    <!-- ROW 9: JATUH TEMPO KIR & KAPASITAS -->
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2.5">
        <div class="field-group">
            <label for="jatuhtempo_kir_edit" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                Jatuh Tempo KIR
            </label>
            <div class="relative">
                <div class="input-prefix-icon absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400 z-10 transition-colors">
                    <i data-lucide="calendar-clock" class="w-4 h-4"></i>
                </div>
                <input type="text" name="jatuhtempo_kir" id="jatuhtempo_kir_edit" readonly
                    value="{{ $kendaraan->jatuhtempo_kir }}"
                    class="flatpickr-date w-full h-[36px] pl-9 pr-3 text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition cursor-pointer font-mono-num"
                    placeholder="Pilih Tanggal">
            </div>
        </div>

        <div class="field-group">
            <label for="kapasitas_edit" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                Kapasitas Muatan <span class="text-rose-500">*</span>
            </label>
            <div class="relative">
                <div class="input-prefix-icon absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400 z-10 transition-colors">
                    <i data-lucide="scale" class="w-4 h-4"></i>
                </div>
                <input type="number" name="kapasitas" id="kapasitas_edit" value="{{ $kendaraan->kapasitas }}" min="0"
                    class="w-full h-[36px] pl-9 pr-3 text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition font-mono-num"
                    placeholder="Kapasitas (misal: 2500)">
            </div>
        </div>
    </div>

    <!-- ROW 10: CABANG & STATUS AKTIF -->
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2.5">
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
                        <option value="{{ $c->kode_cabang }}" {{ $kendaraan->kode_cabang == $c->kode_cabang ? 'selected' : '' }}>
                            {{ $c->nama_cabang }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="field-group">
            <label for="status_aktif_kendaraan_edit" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                Status Kendaraan <span class="text-rose-500">*</span>
            </label>
            <div class="relative">
                <div class="input-prefix-icon absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400 z-10 transition-colors">
                    <i data-lucide="check-circle-2" class="w-4 h-4"></i>
                </div>
                <select name="status_aktif_kendaraan" id="status_aktif_kendaraan_edit"
                    class="w-full h-[36px] pl-9 pr-8 text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition cursor-pointer appearance-none">
                    <option value="">Pilih Status</option>
                    <option value="1" {{ $kendaraan->status_aktif_kendaraan === '1' ? 'selected' : '' }}>Aktif</option>
                    <option value="0" {{ $kendaraan->status_aktif_kendaraan === '0' ? 'selected' : '' }}>Non Aktif</option>
                </select>
                <div class="absolute inset-y-0 right-0 pr-2.5 flex items-center pointer-events-none text-slate-400">
                    <i data-lucide="chevron-down" class="w-3.5 h-3.5"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Submit Button -->
    <div class="pt-2">
        <button type="submit" id="btnUpdateKendaraan" data-loading-text="Memperbarui Kendaraan..."
            class="w-full h-[36px] rounded-lg bg-brand-600 hover:bg-brand-700 active:bg-brand-800 text-white font-bold text-xs flex items-center justify-center gap-1.5 shadow-xs transition active:scale-[0.99] cursor-pointer">
            <i data-lucide="check" class="w-3.5 h-3.5"></i>
            <span>Perbarui Data Kendaraan</span>
        </button>
    </div>
</form>

<script>
    $(function() {
        const $form = $("#formeditKendaraan");
        const $noPolisi = $form.find("#no_polisi_edit");
        const $pajak1 = $form.find("#jatuhtempo_pajak_satutahun_edit");
        const $pajak5 = $form.find("#jatuhtempo_pajak_limatahun_edit");
        const $kapasitas = $form.find("#kapasitas_edit");
        const $cabang = $form.find("#kode_cabang_edit");
        const $status = $form.find("#status_aktif_kendaraan_edit");
        const $btnSubmit = $form.find("#btnUpdateKendaraan");

        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }

        // Init flatpickr pada modal jika belum di-init
        if (typeof flatpickr !== 'undefined') {
            $form.find(".flatpickr-date").each(function() {
                if (!this._flatpickr) {
                    flatpickr(this, {
                        dateFormat: "Y-m-d",
                        allowInput: false
                    });
                }
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

        // Auto uppercase untuk no polisi
        $noPolisi.on('input', function() {
            $(this).val($(this).val().toUpperCase());
        });

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

            checkRequired($status, "Status aktif kendaraan wajib dipilih!");
            checkRequired($cabang, "Cabang penugasan wajib dipilih!");
            checkRequired($kapasitas, "Kapasitas muatan wajib diisi!");
            checkRequired($pajak5, "Jatuh tempo pajak 5 tahun wajib diisi!");
            checkRequired($pajak1, "Jatuh tempo pajak 1 tahun wajib diisi!");
            checkRequired($noPolisi, "No. Polisi kendaraan wajib diisi!");

            if (hasError) {
                e.preventDefault();
                if (typeof window.resetButtonSubmitting === 'function') {
                    window.resetButtonSubmitting($btnSubmit);
                }
                firstInvalid.focus();
                return false;
            }

            if (typeof window.setButtonSubmitting === 'function') {
                window.setButtonSubmitting($btnSubmit, 'Memperbarui Kendaraan...');
            } else {
                $btnSubmit.prop('disabled', true).addClass('opacity-75 cursor-not-allowed pointer-events-none');
            }
            return true;
        });
    });
</script>