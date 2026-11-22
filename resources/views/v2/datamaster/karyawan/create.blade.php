<form action="{{ route('karyawan.store') }}" id="formCreateKaryawan" method="POST" enctype="multipart/form-data" class="space-y-3">
    @csrf

    <!-- GROUP 1: DATA PRIBADI & KEPENDUDUKAN -->
    <div class="rounded-lg border border-slate-200/90 dark:border-slate-800 bg-white dark:bg-slate-900 p-3 space-y-2.5">
        <div class="flex items-center gap-1.5 pb-2 border-b border-slate-100 dark:border-slate-800/80">
            <i data-lucide="user-check" class="w-3.5 h-3.5 text-brand-600 dark:text-brand-400"></i>
            <h4 class="text-xs font-bold text-slate-800 dark:text-slate-100">Data Pribadi & Kependudukan</h4>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-12 gap-2.5">
            <!-- NIK -->
            <div class="sm:col-span-4 field-group">
                <label for="nik_create" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                    NIK Karyawan <span class="text-rose-500">*</span>
                </label>
                <div class="relative">
                    <div class="input-prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                        <i data-lucide="barcode" class="w-3.5 h-3.5"></i>
                    </div>
                    <input type="text" name="nik" id="nik_create"
                        class="w-full h-[36px] pl-8 pr-2.5 text-xs font-black font-mono-num rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition placeholder:text-slate-400"
                        placeholder="Contoh: 22.01.123">
                </div>
            </div>

            <!-- No. KTP -->
            <div class="sm:col-span-4 field-group">
                <label for="no_ktp_create" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                    No. KTP (NIK Kependudukan) <span class="text-rose-500">*</span>
                </label>
                <div class="relative">
                    <div class="input-prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                        <i data-lucide="credit-card" class="w-3.5 h-3.5"></i>
                    </div>
                    <input type="text" name="no_ktp" id="no_ktp_create" maxlength="16"
                        class="w-full h-[36px] pl-8 pr-2.5 text-xs font-semibold font-mono-num rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition placeholder:text-slate-400"
                        placeholder="16 Digit No. KTP">
                </div>
            </div>

            <!-- Nama Karyawan -->
            <div class="sm:col-span-4 field-group">
                <label for="nama_karyawan_create" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                    Nama Lengkap <span class="text-rose-500">*</span>
                </label>
                <div class="relative">
                    <div class="input-prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                        <i data-lucide="user" class="w-3.5 h-3.5"></i>
                    </div>
                    <input type="text" name="nama_karyawan" id="nama_karyawan_create"
                        class="w-full h-[36px] pl-8 pr-2.5 text-xs font-bold rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition placeholder:text-slate-400"
                        placeholder="Nama lengkap karyawan">
                </div>
            </div>

            <!-- Tempat Lahir -->
            <div class="sm:col-span-4 field-group">
                <label for="tempat_lahir_create" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                    Tempat Lahir <span class="text-rose-500">*</span>
                </label>
                <div class="relative">
                    <div class="input-prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                        <i data-lucide="map-pin" class="w-3.5 h-3.5"></i>
                    </div>
                    <input type="text" name="tempat_lahir" id="tempat_lahir_create"
                        class="w-full h-[36px] pl-8 pr-2.5 text-xs font-medium rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition placeholder:text-slate-400"
                        placeholder="Kota tempat lahir">
                </div>
            </div>

            <!-- Tanggal Lahir -->
            <div class="sm:col-span-4 field-group">
                <label for="tanggal_lahir_create" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                    Tanggal Lahir <span class="text-rose-500">*</span>
                </label>
                <div class="relative">
                    <div class="input-prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                        <i data-lucide="calendar" class="w-3.5 h-3.5"></i>
                    </div>
                    <input type="text" name="tanggal_lahir" id="tanggal_lahir_create" readonly
                        class="flatpickr-date w-full h-[36px] pl-8 pr-2.5 text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition cursor-pointer"
                        placeholder="Pilih Tanggal Lahir">
                </div>
            </div>

            <!-- Jenis Kelamin -->
            <div class="sm:col-span-4 field-group">
                <label for="jenis_kelamin_create" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                    Jenis Kelamin <span class="text-rose-500">*</span>
                </label>
                <div class="relative">
                    <div class="input-prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                        <i data-lucide="users" class="w-3.5 h-3.5"></i>
                    </div>
                    <select name="jenis_kelamin" id="jenis_kelamin_create"
                        class="w-full h-[36px] pl-8 pr-7 text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition appearance-none cursor-pointer">
                        <option value="">Pilih Jenis Kelamin</option>
                        <option value="L">Laki - Laki</option>
                        <option value="P">Perempuan</option>
                    </select>
                    <div class="absolute inset-y-0 right-0 pr-2 flex items-center pointer-events-none text-slate-400">
                        <i data-lucide="chevron-down" class="w-3.5 h-3.5"></i>
                    </div>
                </div>
            </div>

            <!-- No. HP -->
            <div class="sm:col-span-4 field-group">
                <label for="no_hp_create" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                    No. Handphone / WA <span class="text-rose-500">*</span>
                </label>
                <div class="relative">
                    <div class="input-prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                        <i data-lucide="phone" class="w-3.5 h-3.5"></i>
                    </div>
                    <input type="text" name="no_hp" id="no_hp_create" maxlength="13"
                        class="w-full h-[36px] pl-8 pr-2.5 text-xs font-semibold font-mono-num rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition placeholder:text-slate-400"
                        placeholder="Contoh: 08123456789">
                </div>
            </div>

            <!-- Status Perkawinan -->
            <div class="sm:col-span-4 field-group">
                <label for="kode_status_kawin_create" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                    Status Perkawinan <span class="text-rose-500">*</span>
                </label>
                <div class="relative">
                    <div class="input-prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                        <i data-lucide="heart" class="w-3.5 h-3.5"></i>
                    </div>
                    <select name="kode_status_kawin" id="kode_status_kawin_create"
                        class="w-full h-[36px] pl-8 pr-7 text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition appearance-none cursor-pointer">
                        <option value="">Pilih Status Kawin</option>
                        @foreach ($status_perkawinan as $s)
                            <option value="{{ $s->kode_status_kawin }}">{{ $s->kode_status_kawin }} - {{ $s->keterangan_status_kawin }}</option>
                        @endforeach
                    </select>
                    <div class="absolute inset-y-0 right-0 pr-2 flex items-center pointer-events-none text-slate-400">
                        <i data-lucide="chevron-down" class="w-3.5 h-3.5"></i>
                    </div>
                </div>
            </div>

            <!-- Pendidikan Terakhir -->
            <div class="sm:col-span-4 field-group">
                <label for="pendidikan_terakhir_create" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                    Pendidikan Terakhir <span class="text-rose-500">*</span>
                </label>
                <div class="relative">
                    <div class="input-prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                        <i data-lucide="graduation-cap" class="w-3.5 h-3.5"></i>
                    </div>
                    <select name="pendidikan_terakhir" id="pendidikan_terakhir_create"
                        class="w-full h-[36px] pl-8 pr-7 text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition appearance-none cursor-pointer">
                        <option value="">Pilih Pendidikan</option>
                        @foreach (['SD','SMP','SMA','SMK','D1','D2','D3','D4','S1','S2','S3'] as $p)
                            <option value="{{ $p }}">{{ $p }}</option>
                        @endforeach
                    </select>
                    <div class="absolute inset-y-0 right-0 pr-2 flex items-center pointer-events-none text-slate-400">
                        <i data-lucide="chevron-down" class="w-3.5 h-3.5"></i>
                    </div>
                </div>
            </div>

            <!-- Alamat Domisili -->
            <div class="sm:col-span-12 field-group">
                <label for="alamat_create" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                    Alamat Lengkap Domisili <span class="text-rose-500">*</span>
                </label>
                <div class="relative">
                    <div class="input-prefix-icon absolute top-2.5 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                        <i data-lucide="home" class="w-3.5 h-3.5"></i>
                    </div>
                    <textarea name="alamat" id="alamat_create" rows="2"
                        class="w-full pl-8 pr-2.5 py-1.5 text-xs font-medium rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition placeholder:text-slate-400"
                        placeholder="Alamat domisili lengkap karyawan..."></textarea>
                </div>
            </div>
        </div>
    </div>

    <!-- GROUP 2: PENEMPATAN & STRUKTUR KEPEGAWAIAN -->
    <div class="rounded-lg border border-slate-200/90 dark:border-slate-800 bg-white dark:bg-slate-900 p-3 space-y-2.5">
        <div class="flex items-center gap-1.5 pb-2 border-b border-slate-100 dark:border-slate-800/80">
            <i data-lucide="briefcase" class="w-3.5 h-3.5 text-blue-600 dark:text-blue-400"></i>
            <h4 class="text-xs font-bold text-slate-800 dark:text-slate-100">Penempatan & Struktur Kepegawaian</h4>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-12 gap-2.5">
            <!-- Perusahaan -->
            <div class="sm:col-span-6 field-group">
                <label for="kode_perusahaan_create" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                    Entitas Perusahaan <span class="text-rose-500">*</span>
                </label>
                <div class="relative">
                    <div class="input-prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                        <i data-lucide="landmark" class="w-3.5 h-3.5"></i>
                    </div>
                    <select name="kode_perusahaan" id="kode_perusahaan_create"
                        class="w-full h-[36px] pl-8 pr-7 text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition appearance-none cursor-pointer">
                        <option value="">Pilih Perusahaan</option>
                        <option value="MP">Makmur Permata (MP)</option>
                        <option value="PC">Pacific (PC)</option>
                    </select>
                    <div class="absolute inset-y-0 right-0 pr-2 flex items-center pointer-events-none text-slate-400">
                        <i data-lucide="chevron-down" class="w-3.5 h-3.5"></i>
                    </div>
                </div>
            </div>

            <!-- Kantor Cabang -->
            <div class="sm:col-span-6 field-group">
                <label for="kode_cabang_create" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                    Kantor Cabang <span class="text-rose-500">*</span>
                </label>
                <div class="relative">
                    <div class="input-prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-20">
                        <i data-lucide="building-2" class="w-3.5 h-3.5"></i>
                    </div>
                    <select name="kode_cabang" id="kode_cabang_create" class="select2-karyawan w-full">
                        <option value="">Pilih Cabang</option>
                        @foreach ($cabang as $c)
                            <option value="{{ $c->kode_cabang }}">{{ $c->kode_cabang }} - {{ $c->nama_cabang }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <!-- Departemen -->
            <div class="sm:col-span-6 field-group">
                <label for="kode_dept_create" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                    Departemen <span class="text-rose-500">*</span>
                </label>
                <div class="relative">
                    <div class="input-prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-20">
                        <i data-lucide="layers" class="w-3.5 h-3.5"></i>
                    </div>
                    <select name="kode_dept" id="kode_dept_create" class="select2-karyawan w-full">
                        <option value="">Pilih Departemen</option>
                        @foreach ($departemen as $d)
                            <option value="{{ $d->kode_dept }}">{{ $d->nama_dept }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <!-- Group -->
            <div class="sm:col-span-6 field-group">
                <label for="kode_group_create" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                    Group <span class="text-rose-500">*</span>
                </label>
                <div class="relative">
                    <div class="input-prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-20">
                        <i data-lucide="users" class="w-3.5 h-3.5"></i>
                    </div>
                    <select name="kode_group" id="kode_group_create" class="select2-karyawan w-full">
                        <option value="">Pilih Group</option>
                        @foreach ($group as $g)
                            <option value="{{ $g->kode_group }}">{{ $g->nama_group }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <!-- Jabatan -->
            <div class="sm:col-span-6 field-group">
                <label for="kode_jabatan_create" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                    Jabatan <span class="text-rose-500">*</span>
                </label>
                <div class="relative">
                    <div class="input-prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-20">
                        <i data-lucide="award" class="w-3.5 h-3.5"></i>
                    </div>
                    <select name="kode_jabatan" id="kode_jabatan_create" class="select2-karyawan w-full">
                        <option value="">Pilih Jabatan</option>
                        @foreach ($jabatan as $j)
                            <option value="{{ $j->kode_jabatan }}">{{ $j->nama_jabatan }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <!-- Klasifikasi -->
            <div class="sm:col-span-6 field-group">
                <label for="kode_klasifikasi_create" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                    Klasifikasi <span class="text-rose-500">*</span>
                </label>
                <div class="relative">
                    <div class="input-prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-20">
                        <i data-lucide="tag" class="w-3.5 h-3.5"></i>
                    </div>
                    <select name="kode_klasifikasi" id="kode_klasifikasi_create" class="select2-karyawan w-full">
                        <option value="">Pilih Klasifikasi</option>
                        @foreach ($klasifikasi as $k)
                            <option value="{{ $k->kode_klasifikasi }}">{{ $k->klasifikasi }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <!-- Tanggal Masuk -->
            <div class="sm:col-span-6 field-group">
                <label for="tanggal_masuk_create" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                    Tanggal Masuk <span class="text-rose-500">*</span>
                </label>
                <div class="relative">
                    <div class="input-prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                        <i data-lucide="calendar-check" class="w-3.5 h-3.5"></i>
                    </div>
                    <input type="text" name="tanggal_masuk" id="tanggal_masuk_create" readonly
                        class="flatpickr-date w-full h-[36px] pl-8 pr-2.5 text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition cursor-pointer"
                        placeholder="Pilih Tanggal Masuk">
                </div>
            </div>

            <!-- Status Karyawan -->
            <div class="sm:col-span-6 field-group">
                <label for="status_karyawan_create" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                    Status Ikatan Kerja <span class="text-rose-500">*</span>
                </label>
                <div class="relative">
                    <div class="input-prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                        <i data-lucide="badge-check" class="w-3.5 h-3.5"></i>
                    </div>
                    <select name="status_karyawan" id="status_karyawan_create"
                        class="w-full h-[36px] pl-8 pr-7 text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition appearance-none cursor-pointer">
                        <option value="">Pilih Status Karyawan</option>
                        <option value="K">Kontrak (PKWT)</option>
                        <option value="T">Tetap (PKWTT)</option>
                    </select>
                    <div class="absolute inset-y-0 right-0 pr-2 flex items-center pointer-events-none text-slate-400">
                        <i data-lucide="chevron-down" class="w-3.5 h-3.5"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- GROUP 3: FOTO PROFIL KARYAWAN -->
    <div class="rounded-lg border border-slate-200/90 dark:border-slate-800 bg-white dark:bg-slate-900 p-3 space-y-2.5">
        <div class="flex items-center gap-1.5 pb-2 border-b border-slate-100 dark:border-slate-800/80">
            <i data-lucide="camera" class="w-3.5 h-3.5 text-purple-600 dark:text-purple-400"></i>
            <h4 class="text-xs font-bold text-slate-800 dark:text-slate-100">Foto Profil Karyawan</h4>
        </div>

        <div class="flex flex-col sm:flex-row items-center gap-4">
            <!-- Preview Box -->
            <div class="w-20 h-24 rounded-lg border border-dashed border-slate-300 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 flex items-center justify-center overflow-hidden shrink-0 shadow-2xs">
                <img id="preview_img_karyawan_create" src="#" alt="Preview" class="w-full h-full object-cover hidden">
                <div id="preview_icon_karyawan_create" class="flex flex-col items-center justify-center text-slate-400">
                    <i data-lucide="user" class="w-8 h-8 text-slate-400"></i>
                    <span class="text-[9px] font-medium text-slate-400 mt-1">Pas Foto</span>
                </div>
            </div>

            <!-- Upload Controls -->
            <div class="flex-1 w-full text-center sm:text-left">
                <input type="file" name="foto" id="foto_create" accept="image/jpeg,image/png,image/jpg" class="hidden">
                <label for="foto_create"
                    class="inline-flex items-center justify-center gap-1.5 h-[34px] px-3.5 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 hover:bg-slate-50 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-200 text-xs font-bold transition shadow-2xs cursor-pointer">
                    <i data-lucide="upload-cloud" class="w-3.5 h-3.5 text-brand-600"></i>
                    <span id="upload_label_karyawan_create">Pilih Pas Foto</span>
                </label>
                <span id="file_name_karyawan_create" class="block text-[11px] text-slate-500 font-mono-num mt-1 truncate hidden"></span>
                <p class="text-[10px] text-slate-400 mt-1">Format: JPG, JPEG, PNG (Maksimal 1MB).</p>
            </div>
        </div>
    </div>

    <!-- SUBMIT & CANCEL BUTTONS -->
    <div class="flex items-center justify-end gap-2 pt-2">
        <button type="button" class="btnCancelModal inline-flex items-center justify-center h-[36px] px-4 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 hover:bg-slate-50 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-200 text-xs font-bold transition shadow-2xs cursor-pointer">
            Batal
        </button>
        <button type="submit" id="btnSubmitCreateKaryawan"
            class="inline-flex items-center justify-center gap-1.5 h-[36px] px-5 rounded-lg bg-brand-600 hover:bg-brand-700 active:bg-brand-800 text-white text-xs font-bold transition shadow-xs cursor-pointer">
            <i data-lucide="send" class="w-3.5 h-3.5"></i>
            <span>Simpan Karyawan</span>
        </button>
    </div>
</form>

<script>
    $(document).ready(function() {
        const $form = $("#formCreateKaryawan");
        const $btnSubmit = $form.find("#btnSubmitCreateKaryawan");

        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }

        // Flatpickr for date fields
        $form.find(".flatpickr-date").flatpickr({
            dateFormat: "Y-m-d"
        });

        // Select2 for Relational Dropdowns
        if ($.fn.select2) {
            $form.find(".select2-karyawan").select2({
                placeholder: "Pilih Opsi",
                allowClear: true,
                width: '100%'
            });

            $form.find(".select2-karyawan").on("change select2:select", function() {
                clearFieldError($(this));
            });
        }

        // Photo Upload Live Preview
        $form.find("#foto_create").on("change", function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(evt) {
                    $form.find("#preview_img_karyawan_create").attr("src", evt.target.result).removeClass("hidden");
                    $form.find("#preview_icon_karyawan_create").addClass("hidden");
                    $form.find("#file_name_karyawan_create").text(file.name).removeClass("hidden");
                    $form.find("#upload_label_karyawan_create").text("Ganti Foto");
                };
                reader.readAsDataURL(file);
            }
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
                        <line x1="12" y1="8" x2="12.01" y2="16"></line>
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

            const $nik = $form.find("#nik_create");
            const $noKtp = $form.find("#no_ktp_create");
            const $nama = $form.find("#nama_karyawan_create");
            const $tempatLahir = $form.find("#tempat_lahir_create");
            const $tglLahir = $form.find("#tanggal_lahir_create");
            const $jk = $form.find("#jenis_kelamin_create");
            const $hp = $form.find("#no_hp_create");
            const $statusKawin = $form.find("#kode_status_kawin_create");
            const $pendidikan = $form.find("#pendidikan_terakhir_create");
            const $alamat = $form.find("#alamat_create");

            const $perusahaan = $form.find("#kode_perusahaan_create");
            const $cabang = $form.find("#kode_cabang_create");
            const $dept = $form.find("#kode_dept_create");
            const $group = $form.find("#kode_group_create");
            const $jabatan = $form.find("#kode_jabatan_create");
            const $klasifikasi = $form.find("#kode_klasifikasi_create");
            const $tglMasuk = $form.find("#tanggal_masuk_create");
            const $statusKaryawan = $form.find("#status_karyawan_create");

            validateField($nik, !$nik.val().trim(), "NIK karyawan wajib diisi");
            validateField($noKtp, !$noKtp.val().trim() || isNaN($noKtp.val()), "No. KTP wajib 16 digit angka");
            validateField($nama, !$nama.val().trim(), "Nama lengkap karyawan wajib diisi");
            validateField($tempatLahir, !$tempatLahir.val().trim(), "Tempat lahir wajib diisi");
            validateField($tglLahir, !$tglLahir.val().trim(), "Pilih tanggal lahir");
            validateField($jk, !$jk.val(), "Pilih jenis kelamin");
            validateField($hp, !$hp.val().trim() || isNaN($hp.val()), "No. Handphone wajib diisi angka");
            validateField($statusKawin, !$statusKawin.val(), "Pilih status perkawinan");
            validateField($pendidikan, !$pendidikan.val(), "Pilih pendidikan terakhir");
            validateField($alamat, !$alamat.val().trim(), "Alamat lengkap wajib diisi");

            validateField($perusahaan, !$perusahaan.val(), "Pilih entitas perusahaan");
            validateField($cabang, !$cabang.val(), "Pilih kantor cabang");
            validateField($dept, !$dept.val(), "Pilih departemen");
            validateField($group, !$group.val(), "Pilih group kerja");
            validateField($jabatan, !$jabatan.val(), "Pilih jabatan karyawan");
            validateField($klasifikasi, !$klasifikasi.val(), "Pilih klasifikasi");
            validateField($tglMasuk, !$tglMasuk.val().trim(), "Pilih tanggal masuk kerja");
            validateField($statusKaryawan, !$statusKaryawan.val(), "Pilih status ikatan kerja");

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
                <span>Menyimpan Karyawan...</span>
            `);
            return true;
        });
    });
</script>
