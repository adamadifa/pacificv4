<form action="{{ route('ajuanlimit.store') }}" id="formAjuanlimit" method="POST" enctype="multipart/form-data" class="space-y-3" autocomplete="off">
    @csrf
    <input type="hidden" name="cek_foto_toko" id="cek_foto_toko">
    <input type="hidden" name="cek_foto_owner" id="cek_foto_owner">
    <input type="hidden" id="jml_faktur" name="jml_faktur" value="0">
    <input type="hidden" id="lama_topup" name="lama_topup" value="0">
    <input type="hidden" name="skor" id="skor" value="0">

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-3.5 items-start">
        
        <!-- KOLOM KIRI: DATA PENGAJUAN & DATA PELANGGAN (Col 6) -->
        <div class="lg:col-span-6 space-y-3">
            
            <!-- SECTION DATA DASAR PENGAJUAN -->
            <div class="rounded-xl border border-slate-200/80 dark:border-slate-800 p-3 bg-slate-50/40 dark:bg-slate-900/40 space-y-2.5">
                <div class="flex items-center gap-1.5 pb-2 border-b border-slate-100 dark:border-slate-800 text-xs font-bold text-slate-700 dark:text-slate-200">
                    <i data-lucide="file-text" class="w-3.5 h-3.5 text-[#284c9a] dark:text-blue-400"></i>
                    <span>Informasi Pengajuan</span>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-2.5 items-start">
                    <!-- No. Pengajuan (Auto) -->
                    <div class="field-group">
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">No. Pengajuan</label>
                        <div class="relative">
                            <div class="input-prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                                <i data-lucide="barcode" class="w-3.5 h-3.5"></i>
                            </div>
                            <input type="text" name="no_pengajuan" id="no_pengajuan" value="Auto Generated" disabled
                                class="w-full h-[34px] pl-8 pr-2.5 text-xs font-mono-num font-bold rounded-lg border border-slate-200 dark:border-slate-700 bg-slate-100 dark:bg-slate-800 text-slate-500 cursor-not-allowed">
                        </div>
                    </div>

                    <!-- Tanggal Pengajuan -->
                    <div class="field-group">
                        <label for="tanggal" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                            Tanggal <span class="text-rose-500">*</span>
                        </label>
                        <div class="relative">
                            <div class="input-prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10 transition-colors">
                                <i data-lucide="calendar" class="w-3.5 h-3.5"></i>
                            </div>
                            <input type="text" name="tanggal" id="tanggal" value="{{ date('Y-m-d') }}" placeholder="Pilih Tanggal" readonly
                                class="flatpickr-date w-full h-[34px] pl-8 pr-2.5 text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-2 focus:ring-[#284c9a]/20 focus:border-[#284c9a] transition cursor-pointer">
                        </div>
                    </div>
                </div>

                <!-- Kode Pelanggan & Lookup Button (Anti Layout-Shift) -->
                <div class="field-group">
                    <label for="kode_pelanggan" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                        Pilih Pelanggan <span class="text-rose-500">*</span>
                    </label>
                    <div class="grid grid-cols-12 gap-1.5 items-start">
                        <div class="col-span-9 sm:col-span-10 relative">
                            <div class="input-prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10 transition-colors">
                                <i data-lucide="user" class="w-3.5 h-3.5"></i>
                            </div>
                            <input type="text" name="kode_pelanggan" id="kode_pelanggan" readonly placeholder="Klik Cari Pelanggan..."
                                class="w-full h-[34px] pl-8 pr-2.5 text-xs font-mono-num font-bold rounded-lg border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 text-slate-800 dark:text-slate-100 cursor-pointer">
                        </div>
                        <div class="col-span-3 sm:col-span-2">
                            <button type="button" id="kode_pelanggan_search"
                                class="w-full h-[34px] px-3 rounded-lg bg-[#284c9a] hover:bg-[#1e3a78] active:bg-[#152a56] text-white text-xs font-bold transition flex items-center justify-center gap-1.5 shadow-2xs cursor-pointer shrink-0">
                                <i data-lucide="search" class="w-3.5 h-3.5"></i>
                                <span>Cari</span>
                            </button>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-2.5 items-start">
                    <!-- NIK -->
                    <div class="field-group">
                        <label for="nik" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                            NIK <span class="text-rose-500">*</span>
                        </label>
                        <div class="relative">
                            <div class="input-prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10 transition-colors">
                                <i data-lucide="credit-card" class="w-3.5 h-3.5"></i>
                            </div>
                            <input type="text" name="nik" id="nik" placeholder="NIK Pelanggan"
                                class="w-full h-[34px] pl-8 pr-2.5 text-xs font-mono-num rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-2 focus:ring-[#284c9a]/20 focus:border-[#284c9a] transition">
                        </div>
                    </div>

                    <!-- Nama Pelanggan (Otomatis dari Pelanggan) -->
                    <div class="field-group">
                        <label for="nama_pelanggan" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                            Nama Pelanggan <span class="text-[10px] font-normal text-slate-400">(Otomatis)</span>
                        </label>
                        <div class="relative">
                            <div class="input-prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10 transition-colors">
                                <i data-lucide="user" class="w-3.5 h-3.5"></i>
                            </div>
                            <input type="text" name="nama_pelanggan" id="nama_pelanggan" placeholder="Otomatis dari data pelanggan" readonly
                                class="w-full h-[34px] pl-8 pr-2.5 text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-700 bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 cursor-not-allowed">
                        </div>
                    </div>
                </div>

                <!-- Alamat Pelanggan -->
                <div class="field-group">
                    <label for="alamat_pelanggan" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                        Alamat <span class="text-rose-500">*</span>
                    </label>
                    <textarea name="alamat_pelanggan" id="alamat_pelanggan" rows="2" placeholder="Alamat lengkap outlet / pelanggan"
                        class="w-full p-2 text-xs font-medium rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-2 focus:ring-[#284c9a]/20 focus:border-[#284c9a] transition"></textarea>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-2.5 items-start">
                    <!-- No HP -->
                    <div class="field-group">
                        <label for="no_hp_pelanggan" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                            No. HP <span class="text-rose-500">*</span>
                        </label>
                        <div class="relative">
                            <div class="input-prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10 transition-colors">
                                <i data-lucide="phone" class="w-3.5 h-3.5"></i>
                            </div>
                            <input type="text" name="no_hp_pelanggan" id="no_hp_pelanggan" placeholder="No. HP"
                                class="w-full h-[34px] pl-8 pr-2.5 text-xs font-mono-num rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-2 focus:ring-[#284c9a]/20 focus:border-[#284c9a] transition">
                        </div>
                    </div>

                    <!-- Cabang -->
                    <div class="field-group">
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">Cabang</label>
                        <div class="relative">
                            <div class="input-prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                                <i data-lucide="building-2" class="w-3.5 h-3.5"></i>
                            </div>
                            <input type="text" name="nama_cabang" id="nama_cabang" placeholder="Cabang" disabled
                                class="w-full h-[34px] pl-8 pr-2.5 text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-700 bg-slate-100 dark:bg-slate-800 text-slate-500 cursor-not-allowed">
                        </div>
                    </div>

                    <!-- Salesman -->
                    <div class="field-group">
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">Salesman</label>
                        <div class="relative">
                            <div class="input-prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                                <i data-lucide="user-check" class="w-3.5 h-3.5"></i>
                            </div>
                            <input type="text" name="nama_salesman" id="nama_salesman" placeholder="Salesman" disabled
                                class="w-full h-[34px] pl-8 pr-2.5 text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-700 bg-slate-100 dark:bg-slate-800 text-slate-500 cursor-not-allowed">
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-2.5 items-start">
                    <!-- Hari Kunjungan -->
                    <div class="field-group">
                        <label for="hari" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                            Hari Kunjungan <span class="text-rose-500">*</span>
                        </label>
                        <div class="relative">
                            <div class="input-prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10 transition-colors">
                                <i data-lucide="calendar" class="w-3.5 h-3.5"></i>
                            </div>
                            <select name="hari" id="hari"
                                class="w-full h-[34px] pl-8 pr-7 text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-2 focus:ring-[#284c9a]/20 focus:border-[#284c9a] transition appearance-none cursor-pointer">
                                <option value="">Pilih Hari</option>
                                <option value="Senin">Senin</option>
                                <option value="Selasa">Selasa</option>
                                <option value="Rabu">Rabu</option>
                                <option value="Kamis">Kamis</option>
                                <option value="Jumat">Jumat</option>
                                <option value="Sabtu">Sabtu</option>
                                <option value="Minggu">Minggu</option>
                            </select>
                            <div class="absolute inset-y-0 right-0 pr-2 flex items-center pointer-events-none text-slate-400">
                                <i data-lucide="chevron-down" class="w-3.5 h-3.5"></i>
                            </div>
                        </div>
                    </div>

                    <!-- Titik Lokasi / Koordinat -->
                    <div class="field-group">
                        <label for="lokasi" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                            Titik Koordinat <span class="text-rose-500">*</span>
                        </label>
                        <div class="relative">
                            <div class="input-prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10 transition-colors">
                                <i data-lucide="map-pin" class="w-3.5 h-3.5"></i>
                            </div>
                            <input type="text" name="lokasi" id="lokasi" placeholder="Latitude, Longitude"
                                class="w-full h-[34px] pl-8 pr-2.5 text-xs font-mono-num rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-2 focus:ring-[#284c9a]/20 focus:border-[#284c9a] transition">
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-2.5 items-start">
                    <!-- Jumlah Ajuan Limit -->
                    <div class="field-group">
                        <label for="jumlah" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                            Jumlah Ajuan Limit <span class="text-rose-500">*</span>
                        </label>
                        <div class="relative">
                            <div class="input-prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10 transition-colors">
                                <i data-lucide="coins" class="w-3.5 h-3.5"></i>
                            </div>
                            <input type="text" name="jumlah" id="jumlah" placeholder="0"
                                class="money w-full h-[34px] pl-8 pr-2.5 text-xs text-right font-mono-num font-bold rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-2 focus:ring-[#284c9a]/20 focus:border-[#284c9a] transition">
                        </div>
                    </div>

                    <!-- LJT (Lama Jatuh Tempo) -->
                    <div class="field-group">
                        <label for="ljt" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                            LJT (Hari) <span class="text-rose-500">*</span>
                        </label>
                        <div class="relative">
                            <div class="input-prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10 transition-colors">
                                <i data-lucide="clock" class="w-3.5 h-3.5"></i>
                            </div>
                            <select name="ljt" id="ljt"
                                class="w-full h-[34px] pl-8 pr-7 text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-2 focus:ring-[#284c9a]/20 focus:border-[#284c9a] transition appearance-none cursor-pointer">
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

                <!-- PREVIEW FOTO TOKO & OWNER -->
                <div class="pt-1 field-group" id="dok_container">
                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                        Dokumentasi Toko & Owner <span class="text-[10px] text-slate-400 font-normal">(Wajib jika limit &gt; Rp 15.000.000)</span>
                    </label>
                    <div class="grid grid-cols-2 gap-2.5">
                        <div class="rounded-lg border border-slate-200 dark:border-slate-700 p-2 bg-white dark:bg-slate-900 text-center">
                            <span class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Foto Toko</span>
                            <div class="w-full h-24 rounded bg-slate-100 dark:bg-slate-800 flex items-center justify-center overflow-hidden">
                                <img src="{{ asset('assets/img/avatars/No_Image_Available.jpg') }}" alt="Foto Toko" id="foto" class="w-full h-full object-cover">
                            </div>
                        </div>
                        <div class="rounded-lg border border-slate-200 dark:border-slate-700 p-2 bg-white dark:bg-slate-900 text-center">
                            <span class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Foto Owner</span>
                            <div class="w-full h-24 rounded bg-slate-100 dark:bg-slate-800 flex items-center justify-center overflow-hidden">
                                <img src="{{ asset('assets/img/avatars/No_Image_Available.jpg') }}" alt="Foto Owner" id="foto_owner" class="w-full h-full object-cover">
                            </div>
                        </div>
                    </div>
                </div>

            </div>

        </div>

        <!-- KOLOM KANAN: PARAMETER USAHA & SCORING OTOMATIS (Col 6) -->
        <div class="lg:col-span-6 space-y-3">
            
            <div class="rounded-xl border border-slate-200/80 dark:border-slate-800 p-3 sm:p-3.5 bg-slate-50/40 dark:bg-slate-900/40 space-y-2.5">
                <div class="flex items-center gap-1.5 pb-2 mb-2.5 border-b border-slate-100 dark:border-slate-800 text-xs font-bold text-slate-700 dark:text-slate-200">
                    <i data-lucide="sliders" class="w-3.5 h-3.5 text-[#284c9a] dark:text-blue-400"></i>
                    <span>Analisis Kelayakan & Profil Usaha</span>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-2.5 items-start">
                    <!-- Kepemilikan (NOT NULL in DB) -->
                    <div class="field-group">
                        <label for="kepemilikan" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                            Kepemilikan <span class="text-rose-500">*</span>
                        </label>
                        <div class="relative">
                            <div class="input-prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10 transition-colors">
                                <i data-lucide="home" class="w-3.5 h-3.5"></i>
                            </div>
                            <select name="kepemilikan" id="kepemilikan"
                                class="w-full h-[34px] pl-8 pr-7 text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-2 focus:ring-[#284c9a]/20 focus:border-[#284c9a] transition appearance-none cursor-pointer">
                                <option value="">Pilih Kepemilikan</option>
                                <option value="SW">Sewa</option>
                                <option value="MS">Milik Sendiri</option>
                            </select>
                            <div class="absolute inset-y-0 right-0 pr-2 flex items-center pointer-events-none text-slate-400">
                                <i data-lucide="chevron-down" class="w-3.5 h-3.5"></i>
                            </div>
                        </div>
                    </div>

                    <!-- Lama Berjualan (NOT NULL in DB) -->
                    <div class="field-group">
                        <label for="lama_berjualan" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                            Lama Usaha <span class="text-rose-500">*</span>
                        </label>
                        <div class="relative">
                            <div class="input-prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10 transition-colors">
                                <i data-lucide="calendar-clock" class="w-3.5 h-3.5"></i>
                            </div>
                            <select name="lama_berjualan" id="lama_berjualan"
                                class="w-full h-[34px] pl-8 pr-7 text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-2 focus:ring-[#284c9a]/20 focus:border-[#284c9a] transition appearance-none cursor-pointer">
                                <option value="">Pilih Lama Usaha</option>
                                <option value="LU01">&lt; 2 Tahun</option>
                                <option value="LU02">2 - 5 Tahun</option>
                                <option value="LU03">&gt; 5 Tahun</option>
                            </select>
                            <div class="absolute inset-y-0 right-0 pr-2 flex items-center pointer-events-none text-slate-400">
                                <i data-lucide="chevron-down" class="w-3.5 h-3.5"></i>
                            </div>
                        </div>
                    </div>

                    <!-- Status Outlet (NOT NULL in DB) -->
                    <div class="field-group">
                        <label for="status_outlet" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                            Status Outlet <span class="text-rose-500">*</span>
                        </label>
                        <div class="relative">
                            <div class="input-prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10 transition-colors">
                                <i data-lucide="store" class="w-3.5 h-3.5"></i>
                            </div>
                            <select name="status_outlet" id="status_outlet"
                                class="w-full h-[34px] pl-8 pr-7 text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-2 focus:ring-[#284c9a]/20 focus:border-[#284c9a] transition appearance-none cursor-pointer">
                                <option value="">Pilih Status</option>
                                <option value="NO">New Outlet</option>
                                <option value="EO">Existing Outlet</option>
                            </select>
                            <div class="absolute inset-y-0 right-0 pr-2 flex items-center pointer-events-none text-slate-400">
                                <i data-lucide="chevron-down" class="w-3.5 h-3.5"></i>
                            </div>
                        </div>
                    </div>

                    <!-- Type Outlet (NOT NULL in DB) -->
                    <div class="field-group">
                        <label for="type_outlet" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                            Type Outlet <span class="text-rose-500">*</span>
                        </label>
                        <div class="relative">
                            <div class="input-prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10 transition-colors">
                                <i data-lucide="tag" class="w-3.5 h-3.5"></i>
                            </div>
                            <select name="type_outlet" id="type_outlet"
                                class="w-full h-[34px] pl-8 pr-7 text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-2 focus:ring-[#284c9a]/20 focus:border-[#284c9a] transition appearance-none cursor-pointer">
                                <option value="">Pilih Tipe</option>
                                <option value="GR">Grosir</option>
                                <option value="RT">Retail</option>
                            </select>
                            <div class="absolute inset-y-0 right-0 pr-2 flex items-center pointer-events-none text-slate-400">
                                <i data-lucide="chevron-down" class="w-3.5 h-3.5"></i>
                            </div>
                        </div>
                    </div>

                    <!-- Cara Pembayaran (NOT NULL in DB) -->
                    <div class="field-group">
                        <label for="cara_pembayaran" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                            Cara Pembayaran <span class="text-rose-500">*</span>
                        </label>
                        <div class="relative">
                            <div class="input-prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10 transition-colors">
                                <i data-lucide="wallet" class="w-3.5 h-3.5"></i>
                            </div>
                            <select name="cara_pembayaran" id="cara_pembayaran"
                                class="w-full h-[34px] pl-8 pr-7 text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-2 focus:ring-[#284c9a]/20 focus:border-[#284c9a] transition appearance-none cursor-pointer">
                                <option value="">Pilih Pembayaran</option>
                                <option value="BT">Bank Transfer</option>
                                <option value="AC">Advance Cash</option>
                                <option value="CQ">Cheque</option>
                            </select>
                            <div class="absolute inset-y-0 right-0 pr-2 flex items-center pointer-events-none text-slate-400">
                                <i data-lucide="chevron-down" class="w-3.5 h-3.5"></i>
                            </div>
                        </div>
                    </div>

                    <!-- Lama Langganan (NOT NULL in DB) -->
                    <div class="field-group">
                        <label for="lama_langganan" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                            Lama Langganan <span class="text-rose-500">*</span>
                        </label>
                        <div class="relative">
                            <div class="input-prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10 transition-colors">
                                <i data-lucide="user-check" class="w-3.5 h-3.5"></i>
                            </div>
                            <select name="lama_langganan" id="lama_langganan"
                                class="w-full h-[34px] pl-8 pr-7 text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-2 focus:ring-[#284c9a]/20 focus:border-[#284c9a] transition appearance-none cursor-pointer">
                                <option value="">Pilih Durasi</option>
                                <option value="LL01">&lt; 2 Tahun</option>
                                <option value="LL02">&gt; 2 Tahun</option>
                            </select>
                            <div class="absolute inset-y-0 right-0 pr-2 flex items-center pointer-events-none text-slate-400">
                                <i data-lucide="chevron-down" class="w-3.5 h-3.5"></i>
                            </div>
                        </div>
                    </div>

                    <!-- Jaminan (NOT NULL in DB) -->
                    <div class="field-group">
                        <label for="jaminan" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                            Jaminan <span class="text-rose-500">*</span>
                        </label>
                        <div class="relative">
                            <div class="input-prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10 transition-colors">
                                <i data-lucide="shield" class="w-3.5 h-3.5"></i>
                            </div>
                            <select name="jaminan" id="jaminan"
                                class="w-full h-[34px] pl-8 pr-7 text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-2 focus:ring-[#284c9a]/20 focus:border-[#284c9a] transition appearance-none cursor-pointer">
                                <option value="">Pilih Jaminan</option>
                                <option value="1">Ada</option>
                                <option value="2">Tidak Ada</option>
                            </select>
                            <div class="absolute inset-y-0 right-0 pr-2 flex items-center pointer-events-none text-slate-400">
                                <i data-lucide="chevron-down" class="w-3.5 h-3.5"></i>
                            </div>
                        </div>
                    </div>

                    <!-- Histori Transaksi (NOT NULL in DB) -->
                    <div class="field-group">
                        <label for="histori_transaksi" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                            Histori Pembayaran 6 Bln <span class="text-rose-500">*</span>
                        </label>
                        <div class="relative">
                            <div class="input-prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10 transition-colors">
                                <i data-lucide="trending-up" class="w-3.5 h-3.5"></i>
                            </div>
                            <select name="histori_transaksi" id="histori_transaksi"
                                class="w-full h-[34px] pl-8 pr-7 text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-2 focus:ring-[#284c9a]/20 focus:border-[#284c9a] transition appearance-none cursor-pointer">
                                <option value="">Pilih Histori</option>
                                <option value="HT01">&lt; 14 Hari</option>
                                <option value="HT02">= 14 Hari</option>
                                <option value="HT03">&gt; 14 Hari</option>
                            </select>
                            <div class="absolute inset-y-0 right-0 pr-2 flex items-center pointer-events-none text-slate-400">
                                <i data-lucide="chevron-down" class="w-3.5 h-3.5"></i>
                            </div>
                        </div>
                    </div>

                    <!-- Terakhir Top Up (NOT NULL in DB) -->
                    <div class="field-group">
                        <label for="topup_terakhir" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                            Terakhir Top Up <span class="text-rose-500">*</span>
                            <span id="usia_topup" class="text-brand-600 dark:text-brand-400 font-mono-num font-semibold text-[10.5px] ml-1"></span>
                        </label>
                        <div class="relative">
                            <div class="input-prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10 transition-colors">
                                <i data-lucide="calendar" class="w-3.5 h-3.5"></i>
                            </div>
                            <input type="text" name="topup_terakhir" id="topup_terakhir" placeholder="Pilih Tanggal Top Up" readonly
                                class="flatpickr-date w-full h-[34px] pl-8 pr-2.5 text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-2 focus:ring-[#284c9a]/20 focus:border-[#284c9a] transition cursor-pointer">
                        </div>
                    </div>

                    <!-- Omset Toko (NOT NULL in DB) -->
                    <div class="field-group">
                        <label for="omset_toko" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                            Omset Toko / Bulan <span class="text-rose-500">*</span>
                        </label>
                        <div class="relative">
                            <div class="input-prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10 transition-colors">
                                <i data-lucide="coins" class="w-3.5 h-3.5"></i>
                            </div>
                            <input type="text" name="omset_toko" id="omset_toko" placeholder="0" readonly
                                class="money w-full h-[34px] pl-8 pr-2.5 text-xs text-right font-mono-num font-bold rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-2 focus:ring-[#284c9a]/20 focus:border-[#284c9a] transition">
                        </div>
                    </div>
                </div>

                <!-- DAFTAR FAKTUR BELUM LUNAS -->
                <div class="pt-1">
                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">Daftar Faktur Belum Lunas</label>
                    <div class="rounded-lg border border-slate-200 dark:border-slate-700 overflow-hidden shadow-2xs">
                        <div class="overflow-x-auto max-h-[140px]">
                            <table class="w-full text-xs text-left border-collapse whitespace-nowrap">
                                <thead class="bg-[#1e3a78] text-white text-[10.5px]">
                                    <tr>
                                        <th class="py-1 px-2.5 font-bold uppercase tracking-wider text-white/90">No. Faktur</th>
                                        <th class="py-1 px-2.5 text-right font-bold uppercase tracking-wider text-white/90">Sisa Piutang</th>
                                    </tr>
                                </thead>
                                <tbody id="loadlistfakturkredit" class="divide-y divide-slate-100 dark:divide-slate-800 bg-white dark:bg-slate-900">
                                    <tr>
                                        <td colspan="2" class="py-3 text-center text-slate-400 text-[11px]">
                                            Pilih pelanggan terlebih dahulu untuk memuat tagihan berjalan.
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- SCORE & REKOMENDASI BOX (PACIFIC NAVY STRIP) -->
                <div class="rounded-xl overflow-hidden bg-[#284c9a] text-white p-3 shadow-xs flex items-center justify-between">
                    <div>
                        <span class="text-[10px] font-bold uppercase tracking-wider text-white/70 block">Hasil Analisa Skor</span>
                        <div class="flex items-center gap-2 mt-0.5">
                            <span id="total_score" class="text-2xl font-black font-mono-num leading-none">0.00</span>
                            <span id="rekomendasi" class="px-2 py-0.5 rounded text-[11px] font-bold bg-white/20 text-white leading-normal">
                                Menunggu Data
                            </span>
                        </div>
                    </div>
                    <div class="w-10 h-10 rounded-lg bg-white/10 flex items-center justify-center text-white/80 shrink-0">
                        <i data-lucide="award" class="w-5 h-5"></i>
                    </div>
                </div>

                <!-- Uraian Analisa -->
                <div class="field-group">
                    <label for="uraian_analisa" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                        Uraian Analisa <span class="text-rose-500">*</span>
                    </label>
                    <textarea name="uraian_analisa" id="uraian_analisa" rows="2" placeholder="Catatan atau pertimbangan pengajuan limit..."
                        class="w-full p-2 text-xs font-medium rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-2 focus:ring-[#284c9a]/20 focus:border-[#284c9a] transition"></textarea>
                </div>

                <!-- Referensi Approval -->
                <div class="field-group">
                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">Rekomendasi Referensi</label>
                    <div class="flex flex-wrap items-center gap-3 text-xs">
                        <label class="inline-flex items-center gap-1.5 cursor-pointer">
                            <input type="checkbox" name="referensi[]" value="smm" class="rounded border-slate-300 text-[#284c9a] focus:ring-[#284c9a]">
                            <span class="font-medium text-slate-700 dark:text-slate-300">SMM</span>
                        </label>
                        <label class="inline-flex items-center gap-1.5 cursor-pointer">
                            <input type="checkbox" name="referensi[]" value="rsm" class="rounded border-slate-300 text-[#284c9a] focus:ring-[#284c9a]">
                            <span class="font-medium text-slate-700 dark:text-slate-300">RSM</span>
                        </label>
                        <label class="inline-flex items-center gap-1.5 cursor-pointer">
                            <input type="checkbox" name="referensi[]" value="gm" class="rounded border-slate-300 text-[#284c9a] focus:ring-[#284c9a]">
                            <span class="font-medium text-slate-700 dark:text-slate-300">GM</span>
                        </label>
                        <label class="inline-flex items-center gap-1.5 cursor-pointer">
                            <input type="checkbox" name="referensi[]" value="direktur" class="rounded border-slate-300 text-[#284c9a] focus:ring-[#284c9a]">
                            <span class="font-medium text-slate-700 dark:text-slate-300">Direktur</span>
                        </label>
                        <label class="inline-flex items-center gap-1.5 cursor-pointer">
                            <input type="checkbox" name="referensi[]" value="external" id="external" class="rounded border-slate-300 text-[#284c9a] focus:ring-[#284c9a]">
                            <span class="font-medium text-slate-700 dark:text-slate-300">External</span>
                        </label>
                    </div>
                </div>

                <!-- Keterangan Referensi External -->
                <div class="field-group hidden" id="ket_ref">
                    <label for="ket_referensi" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                        Keterangan Referensi External <span class="text-rose-500">*</span>
                    </label>
                    <div class="relative">
                        <div class="input-prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10 transition-colors">
                            <i data-lucide="user-check" class="w-3.5 h-3.5"></i>
                        </div>
                        <input type="text" name="ket_referensi" id="ket_referensi" placeholder="Sebutkan referensi pihak external..."
                            class="w-full h-[34px] pl-8 pr-2.5 text-xs font-medium rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-2 focus:ring-[#284c9a]/20 focus:border-[#284c9a] transition">
                    </div>
                </div>

            </div>

        </div>

    </div>

    <!-- FOOTER ACTIONS -->
    <div class="pt-2 border-t border-slate-200/80 dark:border-slate-800 flex items-center justify-end gap-2">
        <button type="button" class="btn-close-modal h-[34px] px-4 rounded-lg bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-600 dark:text-slate-300 text-xs font-bold transition cursor-pointer">
            Batal
        </button>
        <button type="submit" id="submitAjuanlimit"
            class="inline-flex items-center justify-center gap-1.5 h-[34px] px-4 rounded-lg bg-[#284c9a] hover:bg-[#1e3a78] active:bg-[#152a56] text-white text-xs font-bold transition shadow-xs cursor-pointer">
            <i data-lucide="send" class="w-3.5 h-3.5"></i>
            <span id="btnSimpanText">Simpan Pengajuan Limit</span>
        </button>
    </div>
</form>

<script>
    $(function() {
        const $form = $("#formAjuanlimit");

        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }

        // Flatpickr & Money Mask (Gunakan maskMoney sesuai sistem V2)
        if (typeof flatpickr !== 'undefined') {
            $form.find(".flatpickr-date").flatpickr({
                dateFormat: "Y-m-d",
                onChange: function(selectedDates, dateStr, instance) {
                    clearError($(instance.element));
                }
            });
        }

        if ($.fn.maskMoney) {
            $form.find(".money").maskMoney({
                thousands: '.',
                decimal: ',',
                precision: 0
            });
        } else if ($.fn.mask) {
            $form.find(".money").mask("#.##0", {
                reverse: true
            });
        }

        function toggleKeteranganReferensi() {
            if ($("#external").is(':checked')) {
                $("#ket_ref").removeClass('hidden');
            } else {
                $("#ket_ref").addClass('hidden');
            }
        }
        toggleKeteranganReferensi();
        $("#external").on('change', toggleKeteranganReferensi);

        function convertToRupiah(number) {
            if (number) {
                var rupiah = "";
                var numberrev = number.toString().split("").reverse().join("");
                for (var i = 0; i < numberrev.length; i++) {
                    if (i % 3 == 0) rupiah += numberrev.substr(i, 3) + ".";
                }
                return rupiah.split("", rupiah.length - 1).reverse().join("");
            } else {
                return number || '0';
            }
        }

        function checkFileExistence(fileFoto) {
            var filePath = '/pelanggan/' + fileFoto;
            var foto = "{{ url(Storage::url('pelanggan')) }}/" + fileFoto;
            var fotoDefault = "{{ asset('assets/img/avatars/No_Image_Available.jpg') }}";
            $.ajax({
                url: '/pelanggan/cekfotopelanggan?file=' + encodeURIComponent(filePath),
                type: 'GET',
                success: function(response) {
                    if (response && response.exists) {
                        $("#foto").attr("src", foto);
                    } else {
                        $("#foto").attr("src", fotoDefault);
                    }
                },
                error: function() {
                    $("#foto").attr("src", fotoDefault);
                }
            });
        }

        function checkFileExistenceOwner(fileFoto) {
            var filePath = '/pelanggan/owner/' + fileFoto;
            var foto = "{{ url(Storage::url('pelanggan/owner')) }}/" + fileFoto;
            var fotoDefault = "{{ asset('assets/img/avatars/No_Image_Available.jpg') }}";
            $.ajax({
                url: '/pelanggan/cekfotopelanggan?file=' + encodeURIComponent(filePath),
                type: 'GET',
                success: function(response) {
                    if (response && response.exists) {
                        $("#foto_owner").attr("src", foto);
                    } else {
                        $("#foto_owner").attr("src", fotoDefault);
                    }
                },
                error: function() {
                    $("#foto_owner").attr("src", fotoDefault);
                }
            });
        }

        function getlistFakturkredit(kode_pelanggan) {
            $.ajax({
                type: 'GET',
                url: `/pelanggan/${kode_pelanggan}/getlistfakturkredit`,
                cache: false,
                success: function(respond) {
                    $("#loadlistfakturkredit").html(respond);
                    const jmlfaktur = $('#loadlistfakturkredit').find('tr').length;
                    $("#jml_faktur").val(jmlfaktur);
                    loadSkor();
                }
            });
        }

        function getPelanggan(kode_pelanggan) {
            $.ajax({
                url: `/pelanggan/${kode_pelanggan}/getPelanggan`,
                type: "GET",
                cache: false,
                success: function(response) {
                    if (response && response.data) {
                        const status_aktif = response.data.status_aktif_pelanggan;
                        if (status_aktif === '0') {
                            Swal.fire({
                                title: "Oops!",
                                text: "Pelanggan tidak aktif, silakan hubungi admin!",
                                icon: "warning"
                            });
                        } else {
                            $form.find("#kode_pelanggan").val(response.data.kode_pelanggan);
                            $form.find("#nik").val(response.data.nik);
                            $form.find("#nama_pelanggan").val(response.data.nama_pelanggan);
                            $form.find("#alamat_pelanggan").val(response.data.alamat_pelanggan);
                            $form.find("#no_hp_pelanggan").val(response.data.no_hp_pelanggan);
                            $form.find("#nama_cabang").val(response.data.nama_cabang);
                            $form.find("#nama_salesman").val(response.data.nama_salesman);
                            $form.find("#hari").val(response.data.hari);
                            $form.find("#lokasi").val((response.data.latitude || '') + "," + (response.data.longitude || ''));
                            $form.find("#ljt").val(response.data.ljt);
                            $form.find("#kepemilikan").val(response.data.kepemilikan);
                            $form.find("#lama_berjualan").val(response.data.lama_berjualan);
                            $form.find("#type_outlet").val(response.data.type_outlet);
                            $form.find("#status_outlet").val(response.data.status_outlet);
                            $form.find("#cara_pembayaran").val(response.data.cara_pembayaran);
                            $form.find("#lama_langganan").val(response.data.lama_langganan);
                            $form.find("#jaminan").val(response.data.jaminan);
                            $form.find("#omset_toko").val(convertToRupiah(response.data.omset_toko));
                            
                            $form.find("#cek_foto_toko").val(response.data.foto);
                            $form.find("#cek_foto_owner").val(response.data.foto_owner);
                            checkFileExistence(response.data.foto);
                            checkFileExistenceOwner(response.data.foto_owner);

                            $('#modalPelanggan').addClass('hidden');
                            getlistFakturkredit(response.data.kode_pelanggan);
                            loadSkor();

                            // Bersihkan error state pada semua field yang sudah terisi otomatis
                            $form.find('input, select, textarea').each(function() {
                                if ($(this).val()) {
                                    clearError($(this));
                                }
                            });
                        }
                    }
                }
            });
        }

        // Table Pelanggan Row Click
        $(document).off('click', '.pilihpelanggan').on('click', '.pilihpelanggan', function(e) {
            e.preventDefault();
            const kode = $(this).attr('kode_pelanggan');
            getPelanggan(kode);
        });

        // Omset Toko & Jumlah validation
        $form.find("#omset_toko").on('keyup keydown input change', function() {
            var jumlah = $("#jumlah").val();
            if (!jumlah || jumlah == '0') {
                $(this).prop('readonly', true);
            }
            loadSkor();
        });

        $form.find("#jumlah").on('keyup keydown input change', function() {
            var jumlah = $(this).val();
            if (!jumlah || jumlah == '0') {
                $("#omset_toko").prop('readonly', true);
            } else {
                $("#omset_toko").prop('readonly', false);
            }
            loadSkor();
        });

        function gettopupTerakhir() {
            const tanggal = $("#topup_terakhir").val();
            if (!tanggal) return;
            $.ajax({
                type: 'POST',
                url: '/ajuanlimit/gettopupterakhir',
                data: {
                    _token: "{{ csrf_token() }}",
                    tanggal: tanggal
                },
                cache: false,
                success: function(response) {
                    if (response && response.data) {
                        $("#usia_topup").text(response.data.usia_topup);
                        $("#lama_topup").val(response.data.lama_topup);
                        loadSkor();
                    }
                }
            });
        }

        $("#topup_terakhir").on('change', gettopupTerakhir);

        $("#status_outlet, #type_outlet, #ljt, #cara_pembayaran, #kepemilikan, #lama_topup, #lama_langganan, #lama_berjualan, #jaminan, #histori_transaksi").on('change', function() {
            loadSkor();
        });

        // SCORING ENGINE
        function loadSkor() {
            let status_outlet = $("#status_outlet").val();
            let type_outlet = $("#type_outlet").val();
            let ljt = $("#ljt").val();
            let cara_pembayaran = $("#cara_pembayaran").val();
            let kepemilikan = $("#kepemilikan").val();
            let lama_topup = $("#lama_topup").val();
            let lama_langganan = $("#lama_langganan").val();
            let lama_berjualan = $("#lama_berjualan").val();
            let jaminan = $("#jaminan").val();
            let histori_transaksi = $("#histori_transaksi").val();
            let omset_toko = $("#omset_toko").val();
            let jumlah = $("#jumlah").val();

            let ot = omset_toko ? parseInt(omset_toko.replace(/\./g, '')) : 0;
            let jm = jumlah ? parseInt(jumlah.replace(/\./g, '')) : 0;
            let ratioomset = jm > 0 ? Math.round(ot / jm) : 0;
            let jmlfaktur = $('#loadlistfakturkredit').find('tr').length;

            let score_omset = 0;
            let score_faktur = 0;
            let score_outlet = 0;
            let score_jaminan = 0;
            let score_typeoutlet = 0;
            let score_top = 0;
            let score_carabayar = 0;
            let score_kepemilikan = 0;
            let score_ht = 0;
            let score_lamausaha = 0;
            let score_lamalangganan = 0;
            let score_lamatopup = 0;

            if (ot > 0) {
                score_omset = ratioomset < 3 ? 0.35 : 0.50;
            }

            if (jmlfaktur == 1) score_faktur = 0.50;
            else if (jmlfaktur == 2) score_faktur = 0.40;
            else if (jmlfaktur == 3) score_faktur = 0.30;
            else if (jmlfaktur > 3) score_faktur = 0.20;

            if (status_outlet == "NO") score_outlet = 1.05;
            else if (status_outlet == "EO") score_outlet = 1.50;

            if (jaminan == 1) score_jaminan = 1.00;
            else if (jaminan == 2) score_jaminan = 0.70;

            if (type_outlet == "GR") score_typeoutlet = 0.50;
            else if (type_outlet == "RT") score_typeoutlet = 0.35;

            if (ljt == 14) score_top = 1.00;
            else if (ljt == 30) score_top = 0.70;
            else if (ljt == 45) score_top = 0.40;

            if (cara_pembayaran == 'BT') score_carabayar = 0.50;
            else if (cara_pembayaran == 'AC') score_carabayar = 0.35;
            else if (cara_pembayaran == 'CQ') score_carabayar = 0.20;

            if (kepemilikan == "MS") score_kepemilikan = 1.00;
            else if (kepemilikan == "SW") score_kepemilikan = 0.70;

            if (histori_transaksi == "HT01") score_ht = 1.00;
            else if (histori_transaksi == "HT02") score_ht = 0.70;
            else if (histori_transaksi == "HT03") score_ht = 0.40;

            if (lama_berjualan == "LU01") score_lamausaha = 0.40;
            else if (lama_berjualan == "LU02") score_lamausaha = 0.70;
            else if (lama_berjualan == "LU03") score_lamausaha = 1.00;

            if (lama_langganan == "LL01") score_lamalangganan = 0.70;
            else if (lama_langganan == "LL02") score_lamalangganan = 1.00;

            if (lama_topup == 0 || lama_topup <= 31) score_lamatopup = 0.50;
            else if (lama_topup > 31) score_lamatopup = 0.35;

            let totalscore = score_outlet + score_top + score_carabayar + score_kepemilikan + score_lamausaha +
                score_jaminan + score_lamatopup + score_lamalangganan + score_ht + score_omset + score_faktur + score_typeoutlet;

            let scoreakhir = totalscore.toFixed(2);
            let rekomendasi = "Menunggu Data";
            if (scoreakhir <= 2) rekomendasi = "Tidak Layak";
            else if (scoreakhir > 2 && scoreakhir <= 4) rekomendasi = "Tidak Disarankan";
            else if (scoreakhir > 4 && scoreakhir <= 6.75) rekomendasi = "Beresiko";
            else if (scoreakhir > 6.75 && scoreakhir <= 8.5) rekomendasi = "Layak Dengan Pertimbangan";
            else if (scoreakhir > 8.5 && scoreakhir <= 10) rekomendasi = "Layak";

            $("#total_score").text(scoreakhir);
            $("#skor").val(scoreakhir);
            $("#rekomendasi").text(rekomendasi);
        }

        // Helper Error State Interaktif (Standar V2 GEMINI.md - Anti Swal Alert)
        function showError($field, message) {
            $field.addClass('!border-rose-500 !ring-1 !ring-rose-500 focus:!border-rose-500 focus:!ring-rose-500/20');
            const $group = $field.closest('.field-group');
            $group.find('.input-prefix-icon').addClass('text-rose-500').removeClass('text-slate-400');
            $group.find('.field-error-msg').remove();
            $group.append(`
                <p class="field-error-msg text-[11px] font-semibold text-rose-600 dark:text-rose-400 mt-1 pl-1 flex items-center gap-1">
                    <svg class="w-3.5 h-3.5 shrink-0" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                    </svg>
                    <span>${message}</span>
                </p>
            `);
        }

        function clearError($field) {
            $field.removeClass('!border-rose-500 !ring-1 !ring-rose-500 focus:!border-rose-500 focus:!ring-rose-500/20');
            const $group = $field.closest('.field-group');
            $group.find('.input-prefix-icon').removeClass('text-rose-500').addClass('text-slate-400');
            $group.find('.field-error-msg').remove();
        }

        function showPhotoError(message) {
            const $docGroup = $form.find('#dok_container');
            $docGroup.addClass('p-2 rounded-xl border !border-rose-500 !ring-1 !ring-rose-500 bg-rose-50/20');
            $docGroup.find('.field-error-msg').remove();
            $docGroup.append(`
                <p class="field-error-msg text-[11px] font-semibold text-rose-600 dark:text-rose-400 mt-2 pl-1 flex items-center gap-1">
                    <svg class="w-3.5 h-3.5 shrink-0" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                    </svg>
                    <span>${message}</span>
                </p>
            `);
        }

        function clearPhotoError() {
            const $docGroup = $form.find('#dok_container');
            $docGroup.removeClass('p-2 rounded-xl border !border-rose-500 !ring-1 !ring-rose-500 bg-rose-50/20');
            $docGroup.find('.field-error-msg').remove();
        }

        // Live Cleanup saat mengetik / mengubah data (Standar V2 GEMINI.md)
        $form.on('input change select2:select', 'input, select, textarea', function() {
            clearError($(this));
        });

        // Form Submit dengan Validasi Standar V2 Lengkap & Sesuai Schema Database NOT NULL
        $form.on('submit', function(e) {
            let hasError = false;
            let $firstErrorField = null;

            function checkField($field, message) {
                if (!$field.length || $field.val() === null || $field.val() === undefined || $.trim($field.val()) === '') {
                    showError($field, message);
                    hasError = true;
                    if (!$firstErrorField) {
                        $firstErrorField = $field;
                    }
                }
            }

            // 1. Tanggal Pengajuan (NOT NULL)
            checkField($form.find("#tanggal"), "Tanggal pengajuan wajib diisi");

            // 2. Kode Pelanggan (NOT NULL)
            checkField($form.find("#kode_pelanggan"), "Pilih pelanggan terlebih dahulu");

            // 3. NIK (NOT NULL)
            checkField($form.find("#nik"), "NIK / No. KTP pelanggan wajib diisi");

            // 4. Alamat Pelanggan (NOT NULL)
            checkField($form.find("#alamat_pelanggan"), "Alamat pelanggan wajib diisi");

            // 6. No. HP Pelanggan (NOT NULL)
            checkField($form.find("#no_hp_pelanggan"), "No. HP pelanggan wajib diisi");

            // 7. Hari Kunjungan (NOT NULL)
            checkField($form.find("#hari"), "Hari kunjungan wajib dipilih");

            // 8. Titik Koordinat Lokasi (NOT NULL)
            checkField($form.find("#lokasi"), "Titik koordinat lokasi wajib diisi");

            // 9. Jumlah Ajuan Limit (NOT NULL)
            const $jumlah = $form.find("#jumlah");
            let jmlVal = ($jumlah.val() || '0').replace(/\./g, '');
            let jmlNominal = parseInt(jmlVal) || 0;
            if (jmlNominal <= 0) {
                showError($jumlah, "Jumlah limit pengajuan harus lebih dari 0");
                hasError = true;
                if (!$firstErrorField) $firstErrorField = $jumlah;
            }

            // 10. LJT (NOT NULL)
            checkField($form.find("#ljt"), "Tenor LJT wajib dipilih");

            // 11. Validasi Foto Dokumentasi Toko & Owner (Jika ajuan > 15 juta)
            let cekFotoToko = $form.find("#cek_foto_toko").val();
            let cekFotoOwner = $form.find("#cek_foto_owner").val();
            if (jmlNominal > 15000000 && (!cekFotoToko || !cekFotoOwner)) {
                showPhotoError("Ajuan di atas Rp 15.000.000 wajib memiliki dokumentasi Foto Toko dan Foto Owner pada data master pelanggan");
                showError($jumlah, "Ajuan > 15 juta wajib memiliki foto toko & owner");
                hasError = true;
                if (!$firstErrorField) $firstErrorField = $form.find('#dok_container');
            } else {
                clearPhotoError();
            }

            // 12. Kepemilikan (NOT NULL in DB)
            checkField($form.find("#kepemilikan"), "Kepemilikan wajib dipilih");

            // 13. Lama Usaha (NOT NULL in DB)
            checkField($form.find("#lama_berjualan"), "Lama usaha wajib dipilih");

            // 14. Status Outlet (NOT NULL in DB)
            checkField($form.find("#status_outlet"), "Status outlet wajib dipilih");

            // 15. Type Outlet (NOT NULL in DB)
            checkField($form.find("#type_outlet"), "Tipe outlet wajib dipilih");

            // 16. Cara Pembayaran (NOT NULL in DB)
            checkField($form.find("#cara_pembayaran"), "Cara pembayaran wajib dipilih");

            // 17. Lama Langganan (NOT NULL in DB)
            checkField($form.find("#lama_langganan"), "Lama langganan wajib dipilih");

            // 18. Jaminan (NOT NULL in DB)
            checkField($form.find("#jaminan"), "Jaminan wajib dipilih");

            // 19. Histori Pembayaran (NOT NULL in DB)
            checkField($form.find("#histori_transaksi"), "Histori pembayaran 6 bulan wajib dipilih");

            // 20. Terakhir Top Up (NOT NULL in DB)
            checkField($form.find("#topup_terakhir"), "Tanggal terakhir top up wajib dipilih");

            // 21. Omset Toko (NOT NULL in DB)
            const $omsetToko = $form.find("#omset_toko");
            let omsetVal = ($omsetToko.val() || '0').replace(/\./g, '');
            if (parseInt(omsetVal) <= 0) {
                showError($omsetToko, "Omset toko wajib diisi (lebih dari 0)");
                hasError = true;
                if (!$firstErrorField) $firstErrorField = $omsetToko;
            }

            // 22. Uraian Analisa (Wajib diisi)
            checkField($form.find("#uraian_analisa"), "Uraian analisa wajib diisi");

            // 23. Referensi External Keterangan
            if ($form.find("#external").is(':checked') && !$form.find("#ket_referensi").val()) {
                showError($form.find("#ket_referensi"), "Keterangan referensi pihak external wajib diisi");
                hasError = true;
                if (!$firstErrorField) $firstErrorField = $form.find("#ket_referensi");
            }

            if (hasError) {
                e.preventDefault();
                if ($firstErrorField) {
                    $firstErrorField.focus();
                    const container = $form.closest('.overflow-y-auto')[0];
                    if (container && $firstErrorField[0]) {
                        $firstErrorField[0].scrollIntoView({ behavior: 'smooth', block: 'center' });
                    }
                }
                return false;
            }

            // Double submit prevention (Standar V2 GEMINI.md)
            const $btn = $form.find('#submitAjuanlimit');
            $btn.prop('disabled', true).addClass('opacity-75 cursor-not-allowed pointer-events-none');
            $form.find('#btnSimpanText').html(`
                <svg class="animate-spin -ml-1 mr-2 h-3.5 w-3.5 text-white inline-block" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                Menyimpan...
            `);
        });

    });
</script>
