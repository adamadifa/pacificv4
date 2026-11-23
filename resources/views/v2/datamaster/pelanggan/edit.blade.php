<form action="{{ route('pelanggan.update', Crypt::encrypt($pelanggan->kode_pelanggan)) }}" aria-autocomplete="false" id="formeditPelanggan" method="POST" enctype="multipart/form-data" class="space-y-3">
    @csrf
    @method('PUT')

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
                    <!-- Kode Pelanggan (Readonly) -->
                    <div class="field-group">
                        <label for="kode_pelanggan_edit" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                            Kode Pelanggan
                        </label>
                        <div class="relative">
                            <div class="input-prefix-icon absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400 z-10 transition-colors">
                                <i data-lucide="barcode" class="w-4 h-4"></i>
                            </div>
                            <input type="text" name="kode_pelanggan" id="kode_pelanggan_edit" readonly value="{{ $pelanggan->kode_pelanggan }}"
                                class="w-full h-[36px] pl-9 pr-3 text-xs font-semibold font-mono-num rounded-lg border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800/60 text-slate-500 dark:text-slate-400 focus:outline-hidden cursor-not-allowed">
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-2">
                        <!-- NIK -->
                        <div class="field-group">
                            <label for="nik_edit" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">NIK</label>
                            <div class="relative">
                                <div class="input-prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10 transition-colors">
                                    <i data-lucide="credit-card" class="w-3.5 h-3.5"></i>
                                </div>
                                <input type="text" name="nik" id="nik_edit" value="{{ $pelanggan->nik }}"
                                    class="w-full h-[36px] pl-8 pr-2.5 text-xs font-semibold font-mono-num rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition"
                                    placeholder="16 digit NIK">
                            </div>
                        </div>

                        <!-- No. KK -->
                        <div class="field-group">
                            <label for="no_kk_edit" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">No. KK</label>
                            <div class="relative">
                                <div class="input-prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10 transition-colors">
                                    <i data-lucide="file-text" class="w-3.5 h-3.5"></i>
                                </div>
                                <input type="text" name="no_kk" id="no_kk_edit" value="{{ $pelanggan->no_kk }}"
                                    class="w-full h-[36px] pl-8 pr-2.5 text-xs font-semibold font-mono-num rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition"
                                    placeholder="16 digit KK">
                            </div>
                        </div>
                    </div>

                    <!-- Nama Pelanggan -->
                    <div class="field-group">
                        <label for="nama_pelanggan_edit" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                            Nama Toko / Pelanggan <span class="text-rose-500">*</span>
                        </label>
                        <div class="relative">
                            <div class="input-prefix-icon absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400 z-10 transition-colors">
                                <i data-lucide="store" class="w-4 h-4"></i>
                            </div>
                            <input type="text" name="nama_pelanggan" id="nama_pelanggan_edit" value="{{ $pelanggan->nama_pelanggan }}"
                                class="w-full h-[36px] pl-9 pr-3 text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition"
                                placeholder="Contoh: Toko Berkah Jaya">
                        </div>
                    </div>

                    <!-- Tanggal Lahir -->
                    <div class="field-group">
                        <label for="tanggal_lahir_edit" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                            Tanggal Lahir Owner
                        </label>
                        <div class="relative">
                            <div class="input-prefix-icon absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400 z-10 transition-colors">
                                <i data-lucide="calendar" class="w-4 h-4"></i>
                            </div>
                            <input type="text" name="tanggal_lahir" id="tanggal_lahir_edit" readonly value="{{ $pelanggan->tanggal_lahir }}"
                                class="flatpickr-date w-full h-[36px] pl-9 pr-3 text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition cursor-pointer"
                                placeholder="Pilih Tanggal Lahir">
                        </div>
                    </div>

                    <!-- Alamat Pelanggan -->
                    <div class="field-group">
                        <label for="alamat_pelanggan_edit" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                            Alamat Domisili <span class="text-rose-500">*</span>
                        </label>
                        <textarea name="alamat_pelanggan" id="alamat_pelanggan_edit" rows="2"
                            class="w-full p-2.5 text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition"
                            placeholder="Alamat domisili pemilik...">{{ $pelanggan->alamat_pelanggan }}</textarea>
                    </div>

                    <!-- Alamat Toko -->
                    <div class="field-group">
                        <label for="alamat_toko_edit" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                            Alamat Toko / Outlet <span class="text-rose-500">*</span>
                        </label>
                        <textarea name="alamat_toko" id="alamat_toko_edit" rows="2"
                            class="w-full p-2.5 text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition"
                            placeholder="Alamat fisik toko tempat pengiriman...">{{ $pelanggan->alamat_toko }}</textarea>
                    </div>

                    <!-- No. HP & NA Checkbox -->
                    <div class="field-group">
                        <div class="flex items-center justify-between mb-1">
                            <label for="no_hp_pelanggan_edit" class="text-xs font-bold text-slate-700 dark:text-slate-200">
                                No. HP / WhatsApp <span class="text-rose-500">*</span>
                            </label>
                            <label class="inline-flex items-center gap-1.5 text-xs font-semibold text-slate-500 cursor-pointer">
                                <input type="checkbox" id="na_nohp_edit" class="w-3.5 h-3.5 rounded text-brand-600 focus:ring-brand-500 border-slate-300" {{ $pelanggan->no_hp_pelanggan == 'NA' ? 'checked' : '' }}>
                                <span>Tidak Ada (NA)</span>
                            </label>
                        </div>
                        <div class="relative">
                            <div class="input-prefix-icon absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400 z-10 transition-colors">
                                <i data-lucide="phone" class="w-4 h-4"></i>
                            </div>
                            <input type="text" name="no_hp_pelanggan" id="no_hp_pelanggan_edit" value="{{ $pelanggan->no_hp_pelanggan }}"
                                {{ $pelanggan->no_hp_pelanggan == 'NA' ? 'readonly' : '' }}
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
                        <label for="kode_cabang_edit" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                            Cabang <span class="text-rose-500">*</span>
                        </label>
                        <div class="relative">
                            <div class="input-prefix-icon absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400 z-10 transition-colors">
                                <i data-lucide="building-2" class="w-4 h-4"></i>
                            </div>
                            <select name="kode_cabang" id="kode_cabang_edit" class="w-full h-[36px] pl-9 pr-8 text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-800 dark:text-slate-100 focus:outline-hidden appearance-none cursor-pointer">
                                <option value="">Pilih Cabang</option>
                                @foreach ($cabang as $c)
                                    <option value="{{ $c->kode_cabang }}" {{ $pelanggan->kode_cabang == $c->kode_cabang ? 'selected' : '' }}>
                                        {{ $c->nama_cabang }}
                                    </option>
                                @endforeach
                            </select>
                            <div class="absolute inset-y-0 right-0 pr-2.5 flex items-center pointer-events-none text-slate-400">
                                <i data-lucide="chevron-down" class="w-4 h-4"></i>
                            </div>
                        </div>
                    </div>
                @endhasanyrole

                <!-- Cabang PKP -->
                <div class="field-group">
                    <label for="kode_cabang_pkp_edit" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                        Cabang PKP
                    </label>
                    <div class="relative">
                        <div class="input-prefix-icon absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400 z-10 transition-colors">
                            <i data-lucide="file-check" class="w-4 h-4"></i>
                        </div>
                        <select name="kode_cabang_pkp" id="kode_cabang_pkp_edit" class="w-full h-[36px] pl-9 pr-8 text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-800 dark:text-slate-100 focus:outline-hidden appearance-none cursor-pointer">
                            <option value="">Pilih Cabang PKP</option>
                            @foreach ($cabang_pkp as $cp)
                                <option value="{{ $cp->kode_cabang }}" {{ $pelanggan->kode_cabang_pkp == $cp->kode_cabang ? 'selected' : '' }}>
                                    {{ $cp->nama_pt }}
                                </option>
                            @endforeach
                        </select>
                        <div class="absolute inset-y-0 right-0 pr-2.5 flex items-center pointer-events-none text-slate-400">
                            <i data-lucide="chevron-down" class="w-4 h-4"></i>
                        </div>
                    </div>
                </div>

                <!-- Salesman (Dynamic AJAX) -->
                <div class="field-group">
                    <label for="kode_salesman_edit" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                        Salesman Penanggung Jawab <span class="text-rose-500">*</span>
                    </label>
                    <div class="relative">
                        <div class="input-prefix-icon absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400 z-10 transition-colors">
                            <i data-lucide="user-check" class="w-4 h-4"></i>
                        </div>
                        <select name="kode_salesman" id="kode_salesman_edit"
                            class="w-full h-[36px] pl-9 pr-8 text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-800 dark:text-slate-100 focus:outline-hidden appearance-none cursor-pointer">
                            <option value="">Pilih Salesman</option>
                        </select>
                        <div class="absolute inset-y-0 right-0 pr-2.5 flex items-center pointer-events-none text-slate-400">
                            <i data-lucide="chevron-down" class="w-4 h-4"></i>
                        </div>
                    </div>
                </div>

                <!-- Wilayah (Dynamic AJAX) -->
                <div class="field-group">
                    <label for="kode_wilayah_edit" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                        Wilayah / Rute <span class="text-rose-500">*</span>
                    </label>
                    <div class="relative">
                        <div class="input-prefix-icon absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400 z-10 transition-colors">
                            <i data-lucide="compass" class="w-4 h-4"></i>
                        </div>
                        <select name="kode_wilayah" id="kode_wilayah_edit"
                            class="w-full h-[36px] pl-9 pr-8 text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-800 dark:text-slate-100 focus:outline-hidden appearance-none cursor-pointer">
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
                                <input type="checkbox" name="hari[]" value="{{ $h }}" class="hari-checkbox-edit w-3.5 h-3.5 rounded text-brand-600 focus:ring-brand-500 border-slate-300"
                                    {{ str_contains(strtolower($pelanggan->hari), strtolower($h)) ? 'checked' : '' }}>
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
                        <label for="limit_pelanggan_edit" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                            Plafon Limit (Rp)
                        </label>
                        <div class="relative">
                            <div class="input-prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10 transition-colors">
                                <i data-lucide="receipt" class="w-3.5 h-3.5"></i>
                            </div>
                            <input type="text" name="limit_pelanggan" id="limit_pelanggan_edit" value="{{ formatRupiah($pelanggan->limit_pelanggan) }}"
                                class="money w-full h-[36px] pl-8 pr-2.5 text-xs font-semibold font-mono-num text-right rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition"
                                placeholder="0">
                        </div>
                    </div>

                    <!-- LJT (Lama Jatuh Tempo) -->
                    <div class="field-group">
                        <label for="ljt_edit" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">LJT (Hari)</label>
                        <div class="relative">
                            <div class="input-prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10 transition-colors">
                                <i data-lucide="clock" class="w-3.5 h-3.5"></i>
                            </div>
                            <select name="ljt" id="ljt_edit" class="w-full h-[36px] pl-8 pr-6 text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-800 dark:text-slate-100 focus:outline-hidden appearance-none cursor-pointer">
                                <option value="">Pilih LJT</option>
                                <option value="14" {{ $pelanggan->ljt == 14 ? 'selected' : '' }}>14 Hari</option>
                                <option value="30" {{ $pelanggan->ljt == 30 ? 'selected' : '' }}>30 Hari</option>
                                <option value="45" {{ $pelanggan->ljt == 45 ? 'selected' : '' }}>45 Hari</option>
                            </select>
                            <div class="absolute inset-y-0 right-0 pr-2 flex items-center pointer-events-none text-slate-400">
                                <i data-lucide="chevron-down" class="w-3.5 h-3.5"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Rekening Bank -->
                <div class="grid grid-cols-3 gap-2">
                    <div>
                        <label class="block text-[11px] font-bold text-slate-700 dark:text-slate-200 mb-1">No. Rekening</label>
                        <div class="relative">
                            <div class="input-prefix-icon absolute inset-y-0 left-0 pl-2 flex items-center pointer-events-none text-slate-400 z-10 transition-colors">
                                <i data-lucide="credit-card" class="w-3 h-3"></i>
                            </div>
                            <input type="text" name="no_rekening" value="{{ $pelanggan->no_rekening }}" class="w-full h-[34px] pl-6 pr-1.5 text-xs rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-800 dark:text-slate-100 focus:outline-hidden" placeholder="No. Rek">
                        </div>
                    </div>
                    <div>
                        <label class="block text-[11px] font-bold text-slate-700 dark:text-slate-200 mb-1">Atas Nama</label>
                        <div class="relative">
                            <div class="input-prefix-icon absolute inset-y-0 left-0 pl-2 flex items-center pointer-events-none text-slate-400 z-10 transition-colors">
                                <i data-lucide="user" class="w-3 h-3"></i>
                            </div>
                            <input type="text" name="pemilik_rekening" value="{{ $pelanggan->pemilik_rekening }}" class="w-full h-[34px] pl-6 pr-1.5 text-xs rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-800 dark:text-slate-100 focus:outline-hidden" placeholder="Pemilik">
                        </div>
                    </div>
                    <div>
                        <label class="block text-[11px] font-bold text-slate-700 dark:text-slate-200 mb-1">Bank</label>
                        <div class="relative">
                            <div class="input-prefix-icon absolute inset-y-0 left-0 pl-2 flex items-center pointer-events-none text-slate-400 z-10 transition-colors">
                                <i data-lucide="landmark" class="w-3 h-3"></i>
                            </div>
                            <input type="text" name="bank" value="{{ $pelanggan->bank }}" class="w-full h-[34px] pl-6 pr-1.5 text-xs rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-800 dark:text-slate-100 focus:outline-hidden" placeholder="BCA/BRI">
                        </div>
                    </div>
                </div>

                <!-- Metode Pembayaran Program -->
                <div class="field-group">
                    <label for="metode_bayar_edit" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">Metode Pembayaran Program</label>
                    <div class="relative">
                        <div class="input-prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10 transition-colors">
                            <i data-lucide="gift" class="w-3.5 h-3.5"></i>
                        </div>
                        <select name="metode_bayar" id="metode_bayar_edit" class="w-full h-[36px] pl-8 pr-6 text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-800 dark:text-slate-100 focus:outline-hidden appearance-none cursor-pointer">
                            <option value="">Pilih Metode Pembayaran Program</option>
                            <option value="TN" {{ $pelanggan->metode_bayar == 'TN' ? 'selected' : '' }}>Tunai</option>
                            <option value="TF" {{ $pelanggan->metode_bayar == 'TF' ? 'selected' : '' }}>Transfer</option>
                            <option value="VC" {{ $pelanggan->metode_bayar == 'VC' ? 'selected' : '' }}>Voucher</option>
                        </select>
                        <div class="absolute inset-y-0 right-0 pr-2 flex items-center pointer-events-none text-slate-400">
                            <i data-lucide="chevron-down" class="w-3.5 h-3.5"></i>
                        </div>
                    </div>
                </div>

                <!-- Status Aktif Pelanggan -->
                <div class="field-group">
                    <label for="status_aktif_pelanggan_edit" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                        Status Pelanggan <span class="text-rose-500">*</span>
                    </label>
                    <div class="relative">
                        <div class="input-prefix-icon absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400 z-10 transition-colors">
                            <i data-lucide="check-circle-2" class="w-4 h-4"></i>
                        </div>
                        <select name="status_aktif_pelanggan" id="status_aktif_pelanggan_edit"
                            class="w-full h-[36px] pl-9 pr-8 text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-800 dark:text-slate-100 focus:outline-hidden appearance-none cursor-pointer">
                            <option value="1" {{ $pelanggan->status_aktif_pelanggan === '1' ? 'selected' : '' }}>Aktif</option>
                            <option value="0" {{ $pelanggan->status_aktif_pelanggan === '0' ? 'selected' : '' }}>Non Aktif</option>
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
                        <label for="kepemilikan_edit" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">Kepemilikan</label>
                        <div class="relative">
                            <div class="input-prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10 transition-colors">
                                <i data-lucide="key-round" class="w-3.5 h-3.5"></i>
                            </div>
                            <select name="kepemilikan" id="kepemilikan_edit" class="w-full h-[36px] pl-8 pr-6 text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-800 dark:text-slate-100 focus:outline-hidden appearance-none cursor-pointer">
                                <option value="">Pilih Kepemilikan</option>
                                <option value="SW" {{ $pelanggan->kepemilikan == 'SW' ? 'selected' : '' }}>Sewa</option>
                                <option value="MS" {{ $pelanggan->kepemilikan == 'MS' ? 'selected' : '' }}>Milik Sendiri</option>
                            </select>
                            <div class="absolute inset-y-0 right-0 pr-2 flex items-center pointer-events-none text-slate-400">
                                <i data-lucide="chevron-down" class="w-3.5 h-3.5"></i>
                            </div>
                        </div>
                    </div>

                    <!-- Lama Berjualan -->
                    <div class="field-group">
                        <label for="lama_berjualan_edit" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">Lama Usaha</label>
                        <div class="relative">
                            <div class="input-prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10 transition-colors">
                                <i data-lucide="calendar-range" class="w-3.5 h-3.5"></i>
                            </div>
                            <select name="lama_berjualan" id="lama_berjualan_edit" class="w-full h-[36px] pl-8 pr-6 text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-800 dark:text-slate-100 focus:outline-hidden appearance-none cursor-pointer">
                                <option value="">Lama Berjualan</option>
                                <option value="LU01" {{ $pelanggan->lama_berjualan == 'LU01' ? 'selected' : '' }}>< 2 Tahun</option>
                                <option value="LU02" {{ $pelanggan->lama_berjualan == 'LU02' ? 'selected' : '' }}>2 - 5 Tahun</option>
                                <option value="LU03" {{ $pelanggan->lama_berjualan == 'LU03' ? 'selected' : '' }}>> 5 Tahun</option>
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
                        <label for="status_outlet_edit" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">Status Outlet</label>
                        <div class="relative">
                            <div class="input-prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10 transition-colors">
                                <i data-lucide="badge-check" class="w-3.5 h-3.5"></i>
                            </div>
                            <select name="status_outlet" id="status_outlet_edit" class="w-full h-[36px] pl-8 pr-6 text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-800 dark:text-slate-100 focus:outline-hidden appearance-none cursor-pointer">
                                <option value="NO" {{ $pelanggan->status_outlet == 'NO' ? 'selected' : '' }}>New Outlet</option>
                                <option value="EO" {{ $pelanggan->status_outlet == 'EO' ? 'selected' : '' }}>Existing Outlet</option>
                            </select>
                            <div class="absolute inset-y-0 right-0 pr-2 flex items-center pointer-events-none text-slate-400">
                                <i data-lucide="chevron-down" class="w-3.5 h-3.5"></i>
                            </div>
                        </div>
                    </div>

                    <!-- Type Outlet -->
                    <div class="field-group">
                        <label for="type_outlet_edit" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">Tipe Outlet</label>
                        <div class="relative">
                            <div class="input-prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10 transition-colors">
                                <i data-lucide="shopping-cart" class="w-3.5 h-3.5"></i>
                            </div>
                            <select name="type_outlet" id="type_outlet_edit" class="w-full h-[36px] pl-8 pr-6 text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-800 dark:text-slate-100 focus:outline-hidden appearance-none cursor-pointer">
                                <option value="GR" {{ $pelanggan->type_outlet == 'GR' ? 'selected' : '' }}>Grosir</option>
                                <option value="RT" {{ $pelanggan->type_outlet == 'RT' ? 'selected' : '' }}>Retail</option>
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
                        <label for="kode_klasifikasi_edit" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">Klasifikasi</label>
                        <div class="relative">
                            <div class="input-prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10 transition-colors">
                                <i data-lucide="tag" class="w-3.5 h-3.5"></i>
                            </div>
                            <select name="kode_klasifikasi" id="kode_klasifikasi_edit" class="w-full h-[36px] pl-8 pr-6 text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-800 dark:text-slate-100 focus:outline-hidden appearance-none cursor-pointer">
                                <option value="">Pilih Klasifikasi</option>
                                @foreach ($klasifikasi_outlet as $k)
                                    <option value="{{ $k->kode_klasifikasi }}" {{ $pelanggan->kode_klasifikasi == $k->kode_klasifikasi ? 'selected' : '' }}>
                                        {{ $k->klasifikasi }}
                                    </option>
                                @endforeach
                            </select>
                            <div class="absolute inset-y-0 right-0 pr-2 flex items-center pointer-events-none text-slate-400">
                                <i data-lucide="chevron-down" class="w-3.5 h-3.5"></i>
                            </div>
                        </div>
                    </div>

                    <!-- Cara Pembayaran -->
                    <div class="field-group">
                        <label for="cara_pembayaran_edit" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">Pembayaran</label>
                        <div class="relative">
                            <div class="input-prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10 transition-colors">
                                <i data-lucide="wallet" class="w-3.5 h-3.5"></i>
                            </div>
                            <select name="cara_pembayaran" id="cara_pembayaran_edit" class="w-full h-[36px] pl-8 pr-6 text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-800 dark:text-slate-100 focus:outline-hidden appearance-none cursor-pointer">
                                <option value="BT" {{ $pelanggan->cara_pembayaran == 'BT' ? 'selected' : '' }}>Bank Transfer</option>
                                <option value="AC" {{ $pelanggan->cara_pembayaran == 'AC' ? 'selected' : '' }}>Advance Cash</option>
                                <option value="CQ" {{ $pelanggan->cara_pembayaran == 'CQ' ? 'selected' : '' }}>Cheque</option>
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
                        <label for="lama_langganan_edit" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">Lama Langganan</label>
                        <div class="relative">
                            <div class="input-prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10 transition-colors">
                                <i data-lucide="history" class="w-3.5 h-3.5"></i>
                            </div>
                            <select name="lama_langganan" id="lama_langganan_edit" class="w-full h-[36px] pl-8 pr-6 text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-800 dark:text-slate-100 focus:outline-hidden appearance-none cursor-pointer">
                                <option value="">Pilih Lama Langganan</option>
                                <option value="LL01" {{ $pelanggan->lama_langganan == 'LL01' ? 'selected' : '' }}>< 2 Tahun</option>
                                <option value="LL02" {{ $pelanggan->lama_langganan == 'LL02' ? 'selected' : '' }}>> 2 Tahun</option>
                            </select>
                            <div class="absolute inset-y-0 right-0 pr-2 flex items-center pointer-events-none text-slate-400">
                                <i data-lucide="chevron-down" class="w-3.5 h-3.5"></i>
                            </div>
                        </div>
                    </div>

                    <!-- Jaminan -->
                    <div class="field-group">
                        <label for="jaminan_edit" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">Jaminan</label>
                        <div class="relative">
                            <div class="input-prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10 transition-colors">
                                <i data-lucide="shield-check" class="w-3.5 h-3.5"></i>
                            </div>
                            <select name="jaminan" id="jaminan_edit" class="w-full h-[36px] pl-8 pr-6 text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-800 dark:text-slate-100 focus:outline-hidden appearance-none cursor-pointer">
                                <option value="">Pilih Jaminan</option>
                                <option value="1" {{ $pelanggan->jaminan === '1' ? 'selected' : '' }}>Ada</option>
                                <option value="0" {{ $pelanggan->jaminan === '0' ? 'selected' : '' }}>Tidak Ada</option>
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
                        <label for="lokasi_edit" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                            Titik Koordinat Toko
                        </label>
                        <div class="relative">
                            <div class="input-prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10 transition-colors">
                                <i data-lucide="map-pin" class="w-3.5 h-3.5"></i>
                            </div>
                            <input type="text" name="lokasi" id="lokasi_edit"
                                value="{{ (!empty($pelanggan->latitude) && !empty($pelanggan->longitude)) ? trim($pelanggan->latitude) . ',' . trim($pelanggan->longitude) : '' }}"
                                class="w-full h-[36px] pl-8 pr-2.5 text-xs font-semibold font-mono-num rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition"
                                placeholder="-7.3665114,108.2148793">
                        </div>
                    </div>

                    <!-- Omset Toko -->
                    <div class="field-group">
                        <label for="omset_toko_edit" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">Omset / Bulan (Rp)</label>
                        <div class="relative">
                            <div class="input-prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10 transition-colors">
                                <i data-lucide="trending-up" class="w-3.5 h-3.5"></i>
                            </div>
                            <input type="text" name="omset_toko" id="omset_toko_edit"
                                value="{{ formatRupiah($pelanggan->omset_toko) }}"
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
                            <input type="file" name="foto" id="foto_edit" accept="image/*" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-20">
                            
                            <div class="flex items-center gap-2.5">
                                <div id="preview_container_edit" class="w-12 h-12 rounded-lg bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 flex items-center justify-center shrink-0 overflow-hidden shadow-2xs group-hover:scale-105 transition-transform">
                                    @if (!empty($pelanggan->foto) && Storage::disk('public')->exists('/pelanggan/' . $pelanggan->foto))
                                        <img id="preview_img_edit" src="{{ getfotoPelanggan($pelanggan->foto) }}" alt="Preview Toko" class="w-full h-full object-cover">
                                        <i data-lucide="image-plus" id="preview_icon_edit" class="w-5 h-5 text-slate-400 group-hover:text-brand-600 transition-colors hidden"></i>
                                    @else
                                        <i data-lucide="image-plus" id="preview_icon_edit" class="w-5 h-5 text-slate-400 group-hover:text-brand-600 transition-colors"></i>
                                        <img id="preview_img_edit" src="" alt="Preview Toko" class="w-full h-full object-cover hidden">
                                    @endif
                                </div>

                                <div class="min-w-0 flex-1">
                                    <span class="inline-flex items-center justify-center gap-1 px-2 py-0.5 rounded-md bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-700 dark:text-slate-200 text-[11px] font-bold shadow-2xs group-hover:border-brand-500 group-hover:text-brand-600 transition">
                                        <i data-lucide="upload-cloud" class="w-3 h-3 text-brand-600"></i>
                                        <span id="upload_label_edit">{{ !empty($pelanggan->foto) ? 'Ganti Foto' : 'Pilih Foto' }}</span>
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
                            <input type="file" name="foto_owner" id="foto_owner_edit" accept="image/*" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-20">
                            
                            <div class="flex items-center gap-2.5">
                                <div id="preview_container_owner_edit" class="w-12 h-12 rounded-lg bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 flex items-center justify-center shrink-0 overflow-hidden shadow-2xs group-hover:scale-105 transition-transform">
                                    @if (!empty($pelanggan->foto_owner) && Storage::disk('public')->exists('/pelanggan/owner/' . $pelanggan->foto_owner))
                                        <img id="preview_img_owner_edit" src="{{ getfotoPelangganowner($pelanggan->foto_owner) }}" alt="Preview Owner" class="w-full h-full object-cover">
                                        <i data-lucide="user-plus" id="preview_icon_owner_edit" class="w-5 h-5 text-slate-400 group-hover:text-brand-600 transition-colors hidden"></i>
                                    @else
                                        <i data-lucide="user-plus" id="preview_icon_owner_edit" class="w-5 h-5 text-slate-400 group-hover:text-brand-600 transition-colors"></i>
                                        <img id="preview_img_owner_edit" src="" alt="Preview Owner" class="w-full h-full object-cover hidden">
                                    @endif
                                </div>

                                <div class="min-w-0 flex-1">
                                    <span class="inline-flex items-center justify-center gap-1 px-2 py-0.5 rounded-md bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-700 dark:text-slate-200 text-[11px] font-bold shadow-2xs group-hover:border-brand-500 group-hover:text-brand-600 transition">
                                        <i data-lucide="upload-cloud" class="w-3 h-3 text-brand-600"></i>
                                        <span id="upload_label_owner_edit">{{ !empty($pelanggan->foto_owner) ? 'Ganti Foto' : 'Pilih Foto' }}</span>
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
        <button type="submit" id="btnSubmitEditPelanggan" data-loading-text="Memperbarui Data Pelanggan..."
            class="w-full h-[38px] rounded-lg bg-brand-600 hover:bg-brand-700 active:bg-brand-800 text-white font-bold text-xs flex items-center justify-center gap-1.5 shadow-xs transition active:scale-[0.99] cursor-pointer">
            <i data-lucide="refresh-cw" class="w-4 h-4"></i>
            <span>Simpan Perubahan</span>
        </button>
    </div>
</form>

<script>
    $(function() {
        const $form = $("#formeditPelanggan");
        const $nama = $form.find("#nama_pelanggan_edit");
        const $alamat = $form.find("#alamat_pelanggan_edit");
        const $toko = $form.find("#alamat_toko_edit");
        const $hp = $form.find("#no_hp_pelanggan_edit");
        const $naHp = $form.find("#na_nohp_edit");
        const $cabang = $form.find("#kode_cabang_edit");
        const $salesman = $form.find("#kode_salesman_edit");
        const $wilayah = $form.find("#kode_wilayah_edit");
        const $status = $form.find("#status_aktif_pelanggan_edit");
        const $btnSubmit = $form.find("#btnSubmitEditPelanggan");

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

        // Live preview for photo toko upload
        $("#foto_edit").on("change", function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(evt) {
                    $("#preview_img_edit").attr("src", evt.target.result).removeClass("hidden");
                    $("#preview_icon_edit").addClass("hidden");
                    $("#file_name_edit").text(file.name).removeClass("hidden");
                    $("#upload_label_edit").text("Ganti");
                };
                reader.readAsDataURL(file);
            }
        });

        // Live preview for photo owner upload
        $("#foto_owner_edit").on("change", function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(evt) {
                    $("#preview_img_owner_edit").attr("src", evt.target.result).removeClass("hidden");
                    $("#preview_icon_owner_edit").addClass("hidden");
                    $("#upload_label_owner_edit").text("Ganti");
                };
                reader.readAsDataURL(file);
            }
        });

        // NA Checkbox handler
        $naHp.on('change', function() {
            if (this.checked) {
                $hp.val("NA").attr("readonly", true);
            } else {
                $hp.val("").attr("readonly", false).focus();
            }
        });

        // Limit Hari to Max 2
        $form.find('.hari-checkbox-edit').on('change', function() {
            const checkedCount = $form.find('.hari-checkbox-edit:checked').length;
            if (checkedCount > 2) {
                $(this).prop('checked', false);
                alert('Maksimal hanya boleh memilih 2 hari kunjungan!');
            }
        });

        // Dynamic Salesman & Wilayah by Cabang
        function loadSalesmanAndWilayahEdit(cbg, selectedSalesman, selectedWilayah) {
            if (!cbg) return;
            $.ajax({
                type: 'POST',
                url: '/salesman/getsalesmanbycabang',
                data: {
                    _token: "{{ csrf_token() }}",
                    kode_cabang: cbg,
                    kode_salesman: selectedSalesman
                },
                success: function(resp) {
                    $salesman.html(resp);
                }
            });

            $.ajax({
                type: 'POST',
                url: '/wilayah/getwilayahbycabang',
                data: {
                    _token: "{{ csrf_token() }}",
                    kode_cabang: cbg,
                    kode_wilayah: selectedWilayah
                },
                success: function(resp) {
                    $wilayah.html(resp);
                }
            });
        }

        const currentCabang = $cabang.length ? $cabang.val() : "{{ $pelanggan->kode_cabang }}";
        loadSalesmanAndWilayahEdit(currentCabang, "{{ $pelanggan->kode_salesman }}", "{{ $pelanggan->kode_wilayah }}");

        if ($cabang.length) {
            $cabang.on('change', function() {
                loadSalesmanAndWilayahEdit($(this).val(), null, null);
            });
        }

        // Submit form validation
        $form.on('submit', function(e) {
            let hasError = false;

            if (!$nama.val() || !$nama.val().toString().trim()) {
                alert("Nama toko / pelanggan wajib diisi!");
                hasError = true;
            }

            if (!$alamat.val() || !$alamat.val().toString().trim()) {
                alert("Alamat domisili wajib diisi!");
                hasError = true;
            }

            if (!$toko.val() || !$toko.val().toString().trim()) {
                alert("Alamat toko wajib diisi!");
                hasError = true;
            }

            if (hasError) {
                e.preventDefault();
                return false;
            }

            if (typeof window.setButtonSubmitting === 'function') {
                window.setButtonSubmitting($btnSubmit, 'Memperbarui Data Pelanggan...');
            } else {
                $btnSubmit.prop('disabled', true).addClass('opacity-75 cursor-not-allowed pointer-events-none');
            }
            return true;
        });
    });
</script>
