<form action="{{ route('pelanggan.store') }}" aria-autocomplete="false" id="formcreatePelanggan" method="POST" enctype="multipart/form-data" class="space-y-3">
    @csrf

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        
        <!-- KOLOM KIRI: DATA UTAMA & IDENTITAS -->
        <div class="space-y-3">
            <!-- 1. IDENTITAS & DOMISILI -->
            <div class="p-3 rounded-lg border border-slate-200/90 dark:border-slate-800 bg-white dark:bg-slate-900">
                <div class="flex items-center gap-1.5 pb-2 mb-2.5 border-b border-slate-100 dark:border-slate-800">
                    <i data-lucide="user" class="w-3.5 h-3.5 text-brand-600 dark:text-brand-400"></i>
                    <span class="text-xs font-bold text-slate-800 dark:text-slate-100">Identitas & Domisili</span>
                </div>

                <div class="space-y-2.5">
                    <!-- Kode Pelanggan (Auto) -->
                    <div class="field-group">
                        <label for="kode_pelanggan_create" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                            Kode Pelanggan
                        </label>
                        <div class="relative">
                            <div class="input-prefix-icon absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400 z-10 transition-colors">
                                <i data-lucide="barcode" class="w-4 h-4"></i>
                            </div>
                            <input type="text" name="kode_pelanggan" id="kode_pelanggan_create" readonly value="AUTO (Sesuai Cabang)"
                                class="w-full h-[36px] pl-9 pr-3 text-xs font-semibold font-mono-num rounded-lg border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800/60 text-slate-500 dark:text-slate-400 focus:outline-hidden cursor-not-allowed">
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-2">
                        <!-- NIK -->
                        <div class="field-group">
                            <label for="nik_create" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">NIK</label>
                            <div class="relative">
                                <div class="input-prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10 transition-colors">
                                    <i data-lucide="credit-card" class="w-3.5 h-3.5"></i>
                                </div>
                                <input type="text" name="nik" id="nik_create"
                                    class="w-full h-[36px] pl-8 pr-2.5 text-xs font-semibold font-mono-num rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition"
                                    placeholder="16 digit NIK">
                            </div>
                        </div>

                        <!-- No. KK -->
                        <div class="field-group">
                            <label for="no_kk_create" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">No. KK</label>
                            <div class="relative">
                                <div class="input-prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10 transition-colors">
                                    <i data-lucide="file-text" class="w-3.5 h-3.5"></i>
                                </div>
                                <input type="text" name="no_kk" id="no_kk_create"
                                    class="w-full h-[36px] pl-8 pr-2.5 text-xs font-semibold font-mono-num rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition"
                                    placeholder="16 digit KK">
                            </div>
                        </div>
                    </div>

                    <!-- Nama Pelanggan -->
                    <div class="field-group">
                        <label for="nama_pelanggan_create" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                            Nama Toko / Pelanggan <span class="text-rose-500">*</span>
                        </label>
                        <div class="relative">
                            <div class="input-prefix-icon absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400 z-10 transition-colors">
                                <i data-lucide="store" class="w-4 h-4"></i>
                            </div>
                            <input type="text" name="nama_pelanggan" id="nama_pelanggan_create"
                                class="w-full h-[36px] pl-9 pr-3 text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition"
                                placeholder="Contoh: Toko Berkah Jaya">
                        </div>
                    </div>

                    <!-- Tanggal Lahir -->
                    <div class="field-group">
                        <label for="tanggal_lahir_create" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                            Tanggal Lahir Owner
                        </label>
                        <div class="relative">
                            <div class="input-prefix-icon absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400 z-10 transition-colors">
                                <i data-lucide="calendar" class="w-4 h-4"></i>
                            </div>
                            <input type="text" name="tanggal_lahir" id="tanggal_lahir_create" readonly
                                class="flatpickr-date w-full h-[36px] pl-9 pr-3 text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition cursor-pointer"
                                placeholder="Pilih Tanggal Lahir">
                        </div>
                    </div>

                    <!-- Alamat Pelanggan -->
                    <div class="field-group">
                        <label for="alamat_pelanggan_create" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                            Alamat Domisili <span class="text-rose-500">*</span>
                        </label>
                        <textarea name="alamat_pelanggan" id="alamat_pelanggan_create" rows="2"
                            class="w-full p-2.5 text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition"
                            placeholder="Alamat domisili pemilik..."></textarea>
                    </div>

                    <!-- Alamat Toko -->
                    <div class="field-group">
                        <label for="alamat_toko_create" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                            Alamat Toko / Outlet <span class="text-rose-500">*</span>
                        </label>
                        <textarea name="alamat_toko" id="alamat_toko_create" rows="2"
                            class="w-full p-2.5 text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition"
                            placeholder="Alamat fisik toko tempat pengiriman..."></textarea>
                    </div>

                    <!-- No. HP & NA Checkbox -->
                    <div class="field-group">
                        <div class="flex items-center justify-between mb-1">
                            <label for="no_hp_pelanggan_create" class="text-xs font-bold text-slate-700 dark:text-slate-200">
                                No. HP / WhatsApp <span class="text-rose-500">*</span>
                            </label>
                            <label class="inline-flex items-center gap-1.5 text-xs font-semibold text-slate-500 cursor-pointer">
                                <input type="checkbox" id="na_nohp_create" class="w-3.5 h-3.5 rounded text-brand-600 focus:ring-brand-500 border-slate-300">
                                <span>Tidak Ada (NA)</span>
                            </label>
                        </div>
                        <div class="relative">
                            <div class="input-prefix-icon absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400 z-10 transition-colors">
                                <i data-lucide="phone" class="w-4 h-4"></i>
                            </div>
                            <input type="text" name="no_hp_pelanggan" id="no_hp_pelanggan_create"
                                class="w-full h-[36px] pl-9 pr-3 text-xs font-semibold font-mono-num rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition"
                                placeholder="08xxxxxxxxxx">
                        </div>
                    </div>
                </div>
            </div>

            <!-- 2. WILAYAH & SALESMAN -->
            <div class="p-3 rounded-lg border border-slate-200/90 dark:border-slate-800 bg-white dark:bg-slate-900 space-y-2.5">
                <div class="flex items-center gap-1.5 pb-2 mb-2.5 border-b border-slate-100 dark:border-slate-800">
                    <i data-lucide="map" class="w-3.5 h-3.5 text-brand-600 dark:text-brand-400"></i>
                    <span class="text-xs font-bold text-slate-800 dark:text-slate-100">Wilayah & Salesman</span>
                </div>

                <!-- Cabang (Jika Berhak) -->
                @hasanyrole(config('global.roles_show_cabang'))
                    <div class="field-group">
                        <label for="kode_cabang_create" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                            Cabang <span class="text-rose-500">*</span>
                        </label>
                        <div class="relative">
                            <div class="input-prefix-icon absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400 z-10 transition-colors">
                                <i data-lucide="building-2" class="w-4 h-4"></i>
                            </div>
                            <select name="kode_cabang" id="kode_cabang_create" class="select2CabangPelanggan w-full h-[36px] text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-800 dark:text-slate-100">
                                <option value="">Pilih Cabang</option>
                                @foreach ($cabang as $c)
                                    <option value="{{ $c->kode_cabang }}">{{ $c->nama_cabang }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                @endhasanyrole

                <!-- Salesman (Dynamic AJAX) -->
                <div class="field-group">
                    <label for="kode_salesman_create" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                        Salesman Penanggung Jawab <span class="text-rose-500">*</span>
                    </label>
                    <div class="relative">
                        <div class="input-prefix-icon absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400 z-10 transition-colors">
                            <i data-lucide="user-check" class="w-4 h-4"></i>
                        </div>
                        <select name="kode_salesman" id="kode_salesman_create"
                            class="w-full h-[36px] pl-9 pr-8 text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition appearance-none cursor-pointer">
                            <option value="">Pilih Salesman</option>
                        </select>
                        <div class="absolute inset-y-0 right-0 pr-2.5 flex items-center pointer-events-none text-slate-400">
                            <i data-lucide="chevron-down" class="w-4 h-4"></i>
                        </div>
                    </div>
                </div>

                <!-- Wilayah (Dynamic AJAX) -->
                <div class="field-group">
                    <label for="kode_wilayah_create" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                        Wilayah / Rute <span class="text-rose-500">*</span>
                    </label>
                    <div class="relative">
                        <div class="input-prefix-icon absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400 z-10 transition-colors">
                            <i data-lucide="compass" class="w-4 h-4"></i>
                        </div>
                        <select name="kode_wilayah" id="kode_wilayah_create"
                            class="w-full h-[36px] pl-9 pr-8 text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition appearance-none cursor-pointer">
                            <option value="">Pilih Wilayah</option>
                        </select>
                        <div class="absolute inset-y-0 right-0 pr-2.5 flex items-center pointer-events-none text-slate-400">
                            <i data-lucide="chevron-down" class="w-4 h-4"></i>
                        </div>
                    </div>
                </div>

                <!-- Hari Kunjungan (Maksimal 2 Hari) -->
                <div class="field-group">
                    <div class="flex items-center justify-between mb-1">
                        <label class="text-xs font-bold text-slate-700 dark:text-slate-200">
                            Hari Kunjungan Rutin <span class="text-rose-500">*</span>
                        </label>
                        <span class="text-[10px] text-slate-400 font-semibold">(Maks. 2 Hari)</span>
                    </div>
                    <div class="flex items-center gap-1.5 flex-wrap text-xs font-semibold text-slate-600 dark:text-slate-300">
                        @foreach (['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'] as $h)
                            <label class="inline-flex items-center gap-1 px-2 py-1 rounded-md bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 cursor-pointer hover:border-brand-500 transition">
                                <input type="checkbox" name="hari[]" value="{{ $h }}" class="hari-checkbox w-3.5 h-3.5 rounded text-brand-600 focus:ring-brand-500 border-slate-300">
                                <span>{{ $h }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <!-- KOLOM KANAN: KEUANGAN, PROFIL OUTLET & UPLOAD -->
        <div class="space-y-3">
            <!-- 3. PARAMETER KREDIT & STATUS -->
            <div class="p-3 rounded-lg border border-slate-200/90 dark:border-slate-800 bg-white dark:bg-slate-900 space-y-2.5">
                <div class="flex items-center gap-1.5 pb-2 mb-2.5 border-b border-slate-100 dark:border-slate-800">
                    <i data-lucide="banknote" class="w-3.5 h-3.5 text-brand-600 dark:text-brand-400"></i>
                    <span class="text-xs font-bold text-slate-800 dark:text-slate-100">Limit Piutang & Ketentuan</span>
                </div>

                <div class="grid grid-cols-2 gap-2">
                    <!-- Limit Pelanggan -->
                    <div class="field-group">
                        <label for="limit_pelanggan_create" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                            Plafon Limit (Rp)
                        </label>
                        <div class="relative">
                            <div class="input-prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10 transition-colors">
                                <i data-lucide="receipt" class="w-3.5 h-3.5"></i>
                            </div>
                            <input type="text" name="limit_pelanggan" id="limit_pelanggan_create"
                                class="money w-full h-[36px] pl-8 pr-2.5 text-xs font-semibold font-mono-num text-right rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition"
                                placeholder="0">
                        </div>
                    </div>

                    <!-- LJT (Lama Jatuh Tempo) -->
                    <div class="field-group">
                        <label for="ljt_create" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">LJT (Hari)</label>
                        <div class="relative">
                            <div class="input-prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10 transition-colors">
                                <i data-lucide="clock" class="w-3.5 h-3.5"></i>
                            </div>
                            <select name="ljt" id="ljt_create"
                                class="w-full h-[36px] pl-8 pr-6 text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition appearance-none cursor-pointer">
                                <option value="">Pilih LJT</option>
                                <option value="14">14 Hari</option>
                                <option value="30">30 Hari</option>
                                <option value="45">45 Hari</option>
                            </select>
                            <div class="absolute inset-y-0 right-0 pr-2 flex items-center pointer-events-none text-slate-400">
                                <i data-lucide="chevron-down" class="w-3.5 h-3.5"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Status Aktif Pelanggan -->
                <div class="field-group">
                    <label for="status_aktif_pelanggan_create" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                        Status Pelanggan <span class="text-rose-500">*</span>
                    </label>
                    <div class="relative">
                        <div class="input-prefix-icon absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400 z-10 transition-colors">
                            <i data-lucide="check-circle-2" class="w-4 h-4"></i>
                        </div>
                        <select name="status_aktif_pelanggan" id="status_aktif_pelanggan_create"
                            class="w-full h-[36px] pl-9 pr-8 text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition appearance-none cursor-pointer">
                            <option value="">Pilih Status</option>
                            <option value="1" selected>Aktif</option>
                            <option value="0">Non Aktif</option>
                        </select>
                        <div class="absolute inset-y-0 right-0 pr-2.5 flex items-center pointer-events-none text-slate-400">
                            <i data-lucide="chevron-down" class="w-4 h-4"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 4. PROFIL BISNIS & OUTLET -->
            <div class="p-3 rounded-lg border border-slate-200/90 dark:border-slate-800 bg-white dark:bg-slate-900 space-y-2.5">
                <div class="flex items-center gap-1.5 pb-2 mb-2.5 border-b border-slate-100 dark:border-slate-800">
                    <i data-lucide="building" class="w-3.5 h-3.5 text-brand-600 dark:text-brand-400"></i>
                    <span class="text-xs font-bold text-slate-800 dark:text-slate-100">Profil Usaha & Outlet</span>
                </div>

                <div class="grid grid-cols-2 gap-2">
                    <!-- Kepemilikan -->
                    <div class="field-group">
                        <label for="kepemilikan_create" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">Kepemilikan</label>
                        <div class="relative">
                            <div class="input-prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10 transition-colors">
                                <i data-lucide="key-round" class="w-3.5 h-3.5"></i>
                            </div>
                            <select name="kepemilikan" id="kepemilikan_create" class="w-full h-[36px] pl-8 pr-6 text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-800 dark:text-slate-100 focus:outline-hidden appearance-none cursor-pointer">
                                <option value="">Pilih Kepemilikan</option>
                                <option value="SW">Sewa</option>
                                <option value="MS">Milik Sendiri</option>
                            </select>
                            <div class="absolute inset-y-0 right-0 pr-2 flex items-center pointer-events-none text-slate-400">
                                <i data-lucide="chevron-down" class="w-3.5 h-3.5"></i>
                            </div>
                        </div>
                    </div>

                    <!-- Lama Berjualan -->
                    <div class="field-group">
                        <label for="lama_berjualan_create" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">Lama Usaha</label>
                        <div class="relative">
                            <div class="input-prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10 transition-colors">
                                <i data-lucide="calendar-range" class="w-3.5 h-3.5"></i>
                            </div>
                            <select name="lama_berjualan" id="lama_berjualan_create" class="w-full h-[36px] pl-8 pr-6 text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-800 dark:text-slate-100 focus:outline-hidden appearance-none cursor-pointer">
                                <option value="">Lama Berjualan</option>
                                <option value="LU01">< 2 Tahun</option>
                                <option value="LU02">2 - 5 Tahun</option>
                                <option value="LU03">> 5 Tahun</option>
                            </select>
                            <div class="absolute inset-y-0 right-0 pr-2 flex items-center pointer-events-none text-slate-400">
                                <i data-lucide="chevron-down" class="w-3.5 h-3.5"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-2">
                    <!-- Status Outlet -->
                    <div class="field-group">
                        <label for="status_outlet_create" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">Status Outlet</label>
                        <div class="relative">
                            <div class="input-prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10 transition-colors">
                                <i data-lucide="badge-check" class="w-3.5 h-3.5"></i>
                            </div>
                            <select name="status_outlet" id="status_outlet_create" class="w-full h-[36px] pl-8 pr-6 text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-800 dark:text-slate-100 focus:outline-hidden appearance-none cursor-pointer">
                                <option value="">Pilih Status</option>
                                <option value="NO">New Outlet</option>
                                <option value="EO">Existing Outlet</option>
                            </select>
                            <div class="absolute inset-y-0 right-0 pr-2 flex items-center pointer-events-none text-slate-400">
                                <i data-lucide="chevron-down" class="w-3.5 h-3.5"></i>
                            </div>
                        </div>
                    </div>

                    <!-- Type Outlet -->
                    <div class="field-group">
                        <label for="type_outlet_create" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">Tipe Outlet</label>
                        <div class="relative">
                            <div class="input-prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10 transition-colors">
                                <i data-lucide="shopping-cart" class="w-3.5 h-3.5"></i>
                            </div>
                            <select name="type_outlet" id="type_outlet_create" class="w-full h-[36px] pl-8 pr-6 text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-800 dark:text-slate-100 focus:outline-hidden appearance-none cursor-pointer">
                                <option value="">Pilih Tipe</option>
                                <option value="GR">Grosir</option>
                                <option value="RT">Retail</option>
                            </select>
                            <div class="absolute inset-y-0 right-0 pr-2 flex items-center pointer-events-none text-slate-400">
                                <i data-lucide="chevron-down" class="w-3.5 h-3.5"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-2">
                    <!-- Klasifikasi Outlet -->
                    <div class="field-group">
                        <label for="kode_klasifikasi_create" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">Klasifikasi</label>
                        <div class="relative">
                            <div class="input-prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10 transition-colors">
                                <i data-lucide="tag" class="w-3.5 h-3.5"></i>
                            </div>
                            <select name="kode_klasifikasi" id="kode_klasifikasi_create" class="w-full h-[36px] pl-8 pr-6 text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-800 dark:text-slate-100 focus:outline-hidden appearance-none cursor-pointer">
                                <option value="">Pilih Klasifikasi</option>
                                @foreach ($klasifikasi_outlet as $k)
                                    <option value="{{ $k->kode_klasifikasi }}">{{ $k->klasifikasi }}</option>
                                @endforeach
                            </select>
                            <div class="absolute inset-y-0 right-0 pr-2 flex items-center pointer-events-none text-slate-400">
                                <i data-lucide="chevron-down" class="w-3.5 h-3.5"></i>
                            </div>
                        </div>
                    </div>

                    <!-- Cara Pembayaran -->
                    <div class="field-group">
                        <label for="cara_pembayaran_create" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">Pembayaran</label>
                        <div class="relative">
                            <div class="input-prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10 transition-colors">
                                <i data-lucide="wallet" class="w-3.5 h-3.5"></i>
                            </div>
                            <select name="cara_pembayaran" id="cara_pembayaran_create" class="w-full h-[36px] pl-8 pr-6 text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-800 dark:text-slate-100 focus:outline-hidden appearance-none cursor-pointer">
                                <option value="">Cara Bayar</option>
                                <option value="BT">Bank Transfer</option>
                                <option value="AC">Advance Cash</option>
                                <option value="CQ">Cheque</option>
                            </select>
                            <div class="absolute inset-y-0 right-0 pr-2 flex items-center pointer-events-none text-slate-400">
                                <i data-lucide="chevron-down" class="w-3.5 h-3.5"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-2">
                    <!-- Lama Langganan -->
                    <div class="field-group">
                        <label for="lama_langganan_create" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">Lama Langganan</label>
                        <div class="relative">
                            <div class="input-prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10 transition-colors">
                                <i data-lucide="history" class="w-3.5 h-3.5"></i>
                            </div>
                            <select name="lama_langganan" id="lama_langganan_create" class="w-full h-[36px] pl-8 pr-6 text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-800 dark:text-slate-100 focus:outline-hidden appearance-none cursor-pointer">
                                <option value="">Pilih Lama Langganan</option>
                                <option value="LL01">< 2 Tahun</option>
                                <option value="LL02">> 2 Tahun</option>
                            </select>
                            <div class="absolute inset-y-0 right-0 pr-2 flex items-center pointer-events-none text-slate-400">
                                <i data-lucide="chevron-down" class="w-3.5 h-3.5"></i>
                            </div>
                        </div>
                    </div>

                    <!-- Jaminan -->
                    <div class="field-group">
                        <label for="jaminan_create" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">Jaminan</label>
                        <div class="relative">
                            <div class="input-prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10 transition-colors">
                                <i data-lucide="shield-check" class="w-3.5 h-3.5"></i>
                            </div>
                            <select name="jaminan" id="jaminan_create" class="w-full h-[36px] pl-8 pr-6 text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-800 dark:text-slate-100 focus:outline-hidden appearance-none cursor-pointer">
                                <option value="">Pilih Jaminan</option>
                                <option value="1">Ada</option>
                                <option value="0">Tidak Ada</option>
                            </select>
                            <div class="absolute inset-y-0 right-0 pr-2 flex items-center pointer-events-none text-slate-400">
                                <i data-lucide="chevron-down" class="w-3.5 h-3.5"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-2">
                    <!-- Titik Koordinat GPS -->
                    <div class="field-group">
                        <label for="lokasi_create" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">Koordinat GPS</label>
                        <div class="relative">
                            <div class="input-prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10 transition-colors">
                                <i data-lucide="map-pin" class="w-3.5 h-3.5"></i>
                            </div>
                            <input type="text" name="lokasi" id="lokasi_create"
                                class="w-full h-[36px] pl-8 pr-2.5 text-xs font-semibold font-mono-num rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition"
                                placeholder="-6.917464, 107.619123">
                        </div>
                    </div>

                    <!-- Omset Toko -->
                    <div class="field-group">
                        <label for="omset_toko_create" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">Omset / Bulan (Rp)</label>
                        <div class="relative">
                            <div class="input-prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10 transition-colors">
                                <i data-lucide="trending-up" class="w-3.5 h-3.5"></i>
                            </div>
                            <input type="text" name="omset_toko" id="omset_toko_create"
                                class="money w-full h-[36px] pl-8 pr-2.5 text-xs font-semibold font-mono-num text-right rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition"
                                placeholder="0">
                        </div>
                    </div>
                </div>


                <!-- Foto Upload: Toko & Owner -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-2.5">
                    <!-- Foto Toko / Outlet -->
                    <div class="field-group">
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">Foto Toko / Outlet</label>
                        <div class="relative border-2 border-dashed border-slate-200 dark:border-slate-700 hover:border-brand-500/60 dark:hover:border-brand-500/60 rounded-xl p-2.5 bg-slate-50/50 dark:bg-slate-800/30 transition-all group">
                            <input type="file" name="foto" id="foto_create" accept="image/*" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-20">
                            
                            <div class="flex items-center gap-2.5">
                                <div id="preview_container_create" class="w-12 h-12 rounded-lg bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 flex items-center justify-center shrink-0 overflow-hidden shadow-2xs group-hover:scale-105 transition-transform">
                                    <i data-lucide="image-plus" id="preview_icon_create" class="w-5 h-5 text-slate-400 group-hover:text-brand-600 transition-colors"></i>
                                    <img id="preview_img_create" src="" alt="Preview Toko" class="w-full h-full object-cover hidden">
                                </div>

                                <div class="min-w-0 flex-1">
                                    <span class="inline-flex items-center justify-center gap-1 px-2 py-0.5 rounded-md bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-700 dark:text-slate-200 text-[11px] font-bold shadow-2xs group-hover:border-brand-500 group-hover:text-brand-600 transition">
                                        <i data-lucide="upload-cloud" class="w-3 h-3 text-brand-600"></i>
                                        <span id="upload_label_create">Pilih Foto</span>
                                    </span>
                                    <p class="text-[9px] text-slate-400 mt-1 truncate">Maks. 2MB</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Foto Owner / Pemilik -->
                    <div class="field-group">
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">Foto Owner / Pemilik</label>
                        <div class="relative border-2 border-dashed border-slate-200 dark:border-slate-700 hover:border-brand-500/60 dark:hover:border-brand-500/60 rounded-xl p-2.5 bg-slate-50/50 dark:bg-slate-800/30 transition-all group">
                            <input type="file" name="foto_owner" id="foto_owner_create" accept="image/*" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-20">
                            
                            <div class="flex items-center gap-2.5">
                                <div id="preview_container_owner_create" class="w-12 h-12 rounded-lg bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 flex items-center justify-center shrink-0 overflow-hidden shadow-2xs group-hover:scale-105 transition-transform">
                                    <i data-lucide="user-plus" id="preview_icon_owner_create" class="w-5 h-5 text-slate-400 group-hover:text-brand-600 transition-colors"></i>
                                    <img id="preview_img_owner_create" src="" alt="Preview Owner" class="w-full h-full object-cover hidden">
                                </div>

                                <div class="min-w-0 flex-1">
                                    <span class="inline-flex items-center justify-center gap-1 px-2 py-0.5 rounded-md bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-700 dark:text-slate-200 text-[11px] font-bold shadow-2xs group-hover:border-brand-500 group-hover:text-brand-600 transition">
                                        <i data-lucide="upload-cloud" class="w-3 h-3 text-brand-600"></i>
                                        <span id="upload_label_owner_create">Pilih Foto</span>
                                    </span>
                                    <p class="text-[9px] text-slate-400 mt-1 truncate">Maks. 2MB</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <!-- SUBMIT BUTTON -->
    <div class="pt-2">
        <button type="submit" id="btnSubmitCreatePelanggan" data-loading-text="Menyimpan Data Pelanggan..."
            class="w-full h-[38px] rounded-lg bg-brand-600 hover:bg-brand-700 active:bg-brand-800 text-white font-bold text-xs flex items-center justify-center gap-1.5 shadow-xs transition active:scale-[0.99] cursor-pointer">
            <i data-lucide="send" class="w-4 h-4"></i>
            <span>Simpan Data Pelanggan</span>
        </button>
    </div>
</form>

<script>
    $(function() {
        const $form = $("#formcreatePelanggan");
        const $nama = $form.find("#nama_pelanggan_create");
        const $alamat = $form.find("#alamat_pelanggan_create");
        const $toko = $form.find("#alamat_toko_create");
        const $hp = $form.find("#no_hp_pelanggan_create");
        const $naHp = $form.find("#na_nohp_create");
        const $cabang = $form.find("#kode_cabang_create");
        const $salesman = $form.find("#kode_salesman_create");
        const $wilayah = $form.find("#kode_wilayah_create");
        const $status = $form.find("#status_aktif_pelanggan_create");
        const $btnSubmit = $form.find("#btnSubmitCreatePelanggan");

        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }

        if ($.fn.maskMoney) {
            $form.find(".money").maskMoney({
                thousands: '.',
                decimal: ',',
                precision: 0,
                allowZero: true
            });
        }

        $form.find(".flatpickr-date").flatpickr({
            dateFormat: "Y-m-d"
        });

        // Live preview for photo upload
        $("#foto_create").on("change", function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(evt) {
                    $("#preview_img_create").attr("src", evt.target.result).removeClass("hidden");
                    $("#preview_icon_create").addClass("hidden");
                    $("#file_name_create").text(file.name).removeClass("hidden");
                    $("#upload_label_create").text("Ganti Foto");
                };
                reader.readAsDataURL(file);
            }
        });

        // Live preview for owner photo upload
        $("#foto_owner_create").on("change", function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(evt) {
                    $("#preview_img_owner_create").attr("src", evt.target.result).removeClass("hidden");
                    $("#preview_icon_owner_create").addClass("hidden");
                    $("#upload_label_owner_create").text("Ganti Foto");
                };
                reader.readAsDataURL(file);
            }
        });

        // NA Checkbox handler
        $naHp.on('change', function() {
            if (this.checked) {
                $hp.val("NA").attr("readonly", true);
                clearFieldError($hp);
            } else {
                $hp.val("").attr("readonly", false).focus();
            }
        });

        // Limit Hari to Max 2
        $form.find('.hari-checkbox').on('change', function() {
            const checkedCount = $form.find('.hari-checkbox:checked').length;
            if (checkedCount > 2) {
                $(this).prop('checked', false);
                alert('Maksimal hanya boleh memilih 2 hari kunjungan!');
            }
        });

        // Dynamic Salesman & Wilayah by Cabang
        function loadSalesmanAndWilayah(cbg) {
            if (!cbg) return;
            // Load Salesman
            $.ajax({
                type: 'POST',
                url: '/salesman/getsalesmanbycabang',
                data: { _token: "{{ csrf_token() }}", kode_cabang: cbg },
                success: function(resp) {
                    $salesman.html(resp);
                }
            });

            // Load Wilayah
            $.ajax({
                type: 'POST',
                url: '/wilayah/getwilayahbycabang',
                data: { _token: "{{ csrf_token() }}", kode_cabang: cbg },
                success: function(resp) {
                    $wilayah.html(resp);
                }
            });
        }

        if ($cabang.length) {
            $cabang.on('change', function() {
                loadSalesmanAndWilayah($(this).val());
            });
            if ($cabang.val()) loadSalesmanAndWilayah($cabang.val());
        } else {
            // Non-all-cabang role
            loadSalesmanAndWilayah("{{ auth()->user()->kode_cabang }}");
        }

        // Helper invalid & clean
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

        function clearFieldError($input) {
            const $container = $input.closest('.field-group');
            $input.removeClass('border-rose-500 ring-1 ring-rose-500 focus:border-rose-500 focus:ring-rose-500/20')
                  .addClass('border-slate-200 dark:border-slate-700');
            $container.find('.input-prefix-icon').removeClass('text-rose-500').addClass('text-slate-400');
            $container.find('.field-error-msg').remove();
        }

        $form.find('input, select, textarea').on('input change', function() {
            clearFieldError($(this));
        });

        // Submit form validation
        $form.on('submit', function(e) {
            let hasError = false;
            let $firstError = null;

            if (!$nama.val() || !$nama.val().toString().trim()) {
                setFieldError($nama, "Nama toko / pelanggan wajib diisi!");
                hasError = true;
                if (!$firstError) $firstError = $nama;
            }

            if (!$alamat.val() || !$alamat.val().toString().trim()) {
                setFieldError($alamat, "Alamat domisili wajib diisi!");
                hasError = true;
                if (!$firstError) $firstError = $alamat;
            }

            if (!$toko.val() || !$toko.val().toString().trim()) {
                setFieldError($toko, "Alamat toko wajib diisi!");
                hasError = true;
                if (!$firstError) $firstError = $toko;
            }

            const hpVal = $hp.val() ? $hp.val().toString().trim() : '';
            if (!hpVal) {
                setFieldError($hp, "No. Handphone wajib diisi atau centang NA!");
                hasError = true;
                if (!$firstError) $firstError = $hp;
            } else if (hpVal !== 'NA' && hpVal.length < 10) {
                setFieldError($hp, "No. Handphone minimal 10 digit!");
                hasError = true;
                if (!$firstError) $firstError = $hp;
            }

            if ($cabang.length && !$cabang.val()) {
                setFieldError($cabang, "Cabang wajib dipilih!");
                hasError = true;
                if (!$firstError) $firstError = $cabang;
            }

            if (!$salesman.val()) {
                setFieldError($salesman, "Salesman wajib dipilih!");
                hasError = true;
                if (!$firstError) $firstError = $salesman;
            }

            if (!$wilayah.val()) {
                setFieldError($wilayah, "Wilayah wajib dipilih!");
                hasError = true;
                if (!$firstError) $firstError = $wilayah;
            }

            const hariChecked = $form.find('.hari-checkbox:checked').length;
            if (hariChecked === 0) {
                alert('Pilih minimal satu hari kunjungan rutin!');
                hasError = true;
            }

            if ($status.val() === '') {
                setFieldError($status, "Status aktif pelanggan wajib dipilih!");
                hasError = true;
                if (!$firstError) $firstError = $status;
            }

            if (hasError) {
                e.preventDefault();
                if (typeof window.resetButtonSubmitting === 'function') {
                    window.resetButtonSubmitting($btnSubmit);
                }
                if ($firstError) $firstError.focus();
                return false;
            }

            if (typeof window.setButtonSubmitting === 'function') {
                window.setButtonSubmitting($btnSubmit, 'Menyimpan Data Pelanggan...');
            } else {
                $btnSubmit.prop('disabled', true).addClass('opacity-75 cursor-not-allowed pointer-events-none');
            }
            return true;
        });
    });
</script>
