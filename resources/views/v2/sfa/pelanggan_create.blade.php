@extends('layouts.app')
@section('titlepage', 'Register New Outlet')

@section('navigasi')
    <div class="flex items-center gap-1.5 text-xs">
        <a href="{{ route('sfa.pelanggan') }}" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 transition">Pelanggan</a>
        <i data-lucide="chevron-right" class="w-3 h-3 text-slate-400"></i>
        <span class="text-brand-600 dark:text-brand-400 font-bold">Register New Outlet</span>
    </div>
@endsection

@section('content')
<div class="space-y-4">
    <!-- PAGE HEADER -->
    <div class="flex flex-wrap items-center justify-between gap-2.5">
        <div>
            <h3 class="text-base font-black text-slate-900 dark:text-white leading-tight">Register New Outlet</h3>
            <p class="text-[11px] text-slate-400 mt-0.5">Formulir pendaftaran outlet pelanggan baru oleh salesman.</p>
        </div>
        <a href="{{ route('sfa.pelanggan') }}"
            class="h-[34px] px-3 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 hover:bg-slate-50 dark:hover:bg-slate-800 text-slate-700 dark:text-slate-200 text-xs font-bold inline-flex items-center gap-1.5 transition">
            <i data-lucide="arrow-left" class="w-3.5 h-3.5"></i>
            <span>Kembali</span>
        </a>
    </div>

    <form action="{{ route('sfa.storepelanggan') }}" aria-autocomplete="false" id="formcreatePelanggan" method="POST" enctype="multipart/form-data" class="space-y-4">
        @csrf
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-3.5 sm:gap-4 items-start">

            <!-- LEFT CARD: IDENTITAS OUTLET -->
            <div class="lg:col-span-7 rounded-xl bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 shadow-2xs overflow-hidden">
                <div class="px-3.5 py-2.5 border-b border-slate-200/80 dark:border-slate-800 flex items-center justify-between gap-2">
                    <div class="flex items-center gap-2">
                        <div class="w-6 h-6 rounded-md bg-blue-50 dark:bg-blue-950/60 text-[#284c9a] dark:text-blue-400 flex items-center justify-center shrink-0">
                            <i data-lucide="user-plus" class="w-3.5 h-3.5"></i>
                        </div>
                        <span class="text-xs font-bold text-slate-800 dark:text-slate-100">Identitas & Alamat Pelanggan</span>
                    </div>
                    <span class="text-[10.5px] text-slate-400 font-medium">Data Utama</span>
                </div>

                <div class="p-3.5 sm:p-4 space-y-3">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2.5 items-start">
                        <div>
                            <label for="kode_pelanggan" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">Kode Pelanggan</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                                    <i data-lucide="barcode" class="w-4 h-4"></i>
                                </div>
                                <input type="text" name="kode_pelanggan" id="kode_pelanggan" disabled placeholder="Auto Generate"
                                    class="w-full h-[36px] pl-9 pr-3 rounded-lg border border-slate-200 dark:border-slate-700 bg-slate-100 dark:bg-slate-800 text-xs font-medium text-slate-500 cursor-not-allowed">
                            </div>
                        </div>

                        <div>
                            <label for="nik" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">NIK</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                                    <i data-lucide="credit-card" class="w-4 h-4"></i>
                                </div>
                                <input type="text" name="nik" id="nik" placeholder="Nomor Induk Kependudukan"
                                    class="w-full h-[36px] pl-9 pr-3 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-950 text-xs font-medium text-slate-700 dark:text-slate-200 focus:outline-none focus:border-[#284c9a] focus:ring-2 focus:ring-[#284c9a]/15 transition">
                            </div>
                        </div>

                        <div>
                            <label for="no_kk" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">No. KK</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                                    <i data-lucide="file-text" class="w-4 h-4"></i>
                                </div>
                                <input type="text" name="no_kk" id="no_kk" placeholder="Nomor Kartu Keluarga"
                                    class="w-full h-[36px] pl-9 pr-3 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-950 text-xs font-medium text-slate-700 dark:text-slate-200 focus:outline-none focus:border-[#284c9a] focus:ring-2 focus:ring-[#284c9a]/15 transition">
                            </div>
                        </div>

                        <div>
                            <label for="tanggal_lahir" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">Tanggal Lahir</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                                    <i data-lucide="calendar" class="w-4 h-4"></i>
                                </div>
                                <input type="text" name="tanggal_lahir" id="tanggal_lahir" readonly placeholder="Pilih Tanggal"
                                    class="flatpickr-date w-full h-[36px] pl-9 pr-3 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-950 text-xs font-medium text-slate-700 dark:text-slate-200 focus:outline-none focus:border-[#284c9a] focus:ring-2 focus:ring-[#284c9a]/15 transition cursor-pointer">
                            </div>
                        </div>
                    </div>

                    <div class="field-group">
                        <label for="nama_pelanggan" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                            Nama Pelanggan <span class="text-rose-500">*</span>
                        </label>
                        <div class="relative">
                            <div class="input-prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                                <i data-lucide="user" class="w-4 h-4"></i>
                            </div>
                            <input type="text" name="nama_pelanggan" id="nama_pelanggan" placeholder="Nama lengkap pelanggan / outlet"
                                class="w-full h-[36px] pl-9 pr-3 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-950 text-xs font-medium text-slate-700 dark:text-slate-200 focus:outline-none focus:border-[#284c9a] focus:ring-2 focus:ring-[#284c9a]/15 transition">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2.5 items-start">
                        <div class="field-group">
                            <label for="alamat_pelanggan" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                                Alamat Pelanggan <span class="text-rose-500">*</span>
                            </label>
                            <textarea name="alamat_pelanggan" id="alamat_pelanggan" rows="2" placeholder="Alamat domisili pelanggan"
                                class="w-full p-2.5 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-950 text-xs font-medium text-slate-700 dark:text-slate-200 focus:outline-none focus:border-[#284c9a] focus:ring-2 focus:ring-[#284c9a]/15 transition"></textarea>
                        </div>

                        <div class="field-group">
                            <label for="alamat_toko" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                                Alamat Toko <span class="text-rose-500">*</span>
                            </label>
                            <textarea name="alamat_toko" id="alamat_toko" rows="2" placeholder="Alamat lokasi toko"
                                class="w-full p-2.5 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-950 text-xs font-medium text-slate-700 dark:text-slate-200 focus:outline-none focus:border-[#284c9a] focus:ring-2 focus:ring-[#284c9a]/15 transition"></textarea>
                        </div>
                    </div>

                    <div class="grid grid-cols-12 gap-2.5 items-start">
                        <div class="col-span-9 sm:col-span-10 field-group">
                            <label for="no_hp_pelanggan" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                                No. HP <span class="text-rose-500">*</span>
                            </label>
                            <div class="relative">
                                <div class="input-prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                                    <i data-lucide="phone" class="w-4 h-4"></i>
                                </div>
                                <input type="text" name="no_hp_pelanggan" id="no_hp_pelanggan" placeholder="08xxxxxxxxxx"
                                    class="w-full h-[36px] pl-9 pr-3 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-950 text-xs font-medium text-slate-700 dark:text-slate-200 focus:outline-none focus:border-[#284c9a] focus:ring-2 focus:ring-[#284c9a]/15 transition">
                            </div>
                        </div>

                        <div class="col-span-3 sm:col-span-2">
                            <label class="block text-xs font-bold text-transparent mb-1 select-none pointer-events-none">&nbsp;</label>
                            <label for="na_nohp" class="h-[36px] px-2.5 rounded-lg border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800/60 flex items-center justify-center gap-1.5 cursor-pointer select-none">
                                <input class="na_nohp rounded border-slate-300 text-[#284c9a] focus:ring-[#284c9a]" type="checkbox" value="1" id="na_nohp">
                                <span class="text-xs font-bold text-slate-600 dark:text-slate-300">NA</span>
                            </label>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2.5 items-start">
                        <div class="field-group">
                            <label for="kode_wilayah" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                                Wilayah <span class="text-rose-500">*</span>
                            </label>
                            <div class="relative">
                                <div class="input-prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-20">
                                    <i data-lucide="map" class="w-4 h-4"></i>
                                </div>
                                <select name="kode_wilayah" id="kode_wilayah"
                                    class="select2Kodewilayah w-full h-[36px] pl-9 pr-8 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-950 text-xs font-medium text-slate-700 dark:text-slate-200">
                                </select>
                            </div>
                        </div>

                        <div class="field-group">
                            <label for="ljt" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                                LJT <span class="text-rose-500">*</span>
                            </label>
                            <div class="relative">
                                <div class="input-prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                                    <i data-lucide="clock" class="w-4 h-4"></i>
                                </div>
                                <select name="ljt" id="ljt"
                                    class="w-full h-[36px] pl-9 pr-8 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-950 text-xs font-medium text-slate-700 dark:text-slate-200 focus:outline-none focus:border-[#284c9a] focus:ring-2 focus:ring-[#284c9a]/15 transition">
                                    <option value="">Pilih LJT</option>
                                    <option value="14">14</option>
                                    <option value="30">30</option>
                                    <option value="45">45</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1.5">Hari Kunjungan <span class="text-[11px] font-normal text-slate-400">(Maks. 2 Hari)</span></label>
                        <div class="grid grid-cols-3 sm:flex sm:flex-wrap gap-1.5">
                            @foreach (['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'] as $hari)
                                <label for="hari{{ strtolower($hari) }}"
                                    class="inline-flex items-center justify-center sm:justify-start gap-1.5 px-2.5 py-1.5 rounded-lg border border-slate-200 dark:border-slate-700 bg-slate-50/70 dark:bg-slate-800/60 text-xs font-medium text-slate-700 dark:text-slate-200 cursor-pointer select-none hover:border-[#284c9a]/50 transition">
                                    <input class="rounded border-slate-300 text-[#284c9a] focus:ring-[#284c9a]" name="hari[]" value="{{ $hari }}" type="checkbox" id="hari{{ strtolower($hari) }}">
                                    <span>{{ $hari }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            <!-- RIGHT CARD: DATA TAMBAHAN & SUBMIT -->
            <div class="lg:col-span-5 rounded-xl bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 shadow-2xs overflow-hidden">
                <div class="px-3.5 py-2.5 border-b border-slate-200/80 dark:border-slate-800 flex items-center justify-between gap-2">
                    <div class="flex items-center gap-2">
                        <div class="w-6 h-6 rounded-md bg-blue-50 dark:bg-blue-950/60 text-[#284c9a] dark:text-blue-400 flex items-center justify-center shrink-0">
                            <i data-lucide="store" class="w-3.5 h-3.5"></i>
                        </div>
                        <span class="text-xs font-bold text-slate-800 dark:text-slate-100">Profil Usaha & Lokasi</span>
                    </div>
                    <span class="text-[10.5px] text-slate-400 font-medium">Opsional</span>
                </div>

                <div class="p-3.5 sm:p-4 space-y-3">
                    <div class="rounded-lg border border-amber-200 dark:border-amber-800/60 bg-amber-50/80 dark:bg-amber-950/40 px-3 py-2 flex items-center gap-2 text-xs text-amber-800 dark:text-amber-200">
                        <i data-lucide="info" class="w-4 h-4 text-amber-600 shrink-0"></i>
                        <span>Bisa diisi saat melakukan ajuan limit kredit.</span>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2.5 items-start">
                        <div>
                            <label for="kepemilikan" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">Kepemilikan</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                                    <i data-lucide="home" class="w-4 h-4"></i>
                                </div>
                                <select name="kepemilikan" id="kepemilikan"
                                    class="w-full h-[36px] pl-9 pr-8 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-950 text-xs font-medium text-slate-700 dark:text-slate-200 focus:outline-none focus:border-[#284c9a] focus:ring-2 focus:ring-[#284c9a]/15 transition">
                                    <option value="">Pilih Kepemilikan</option>
                                    <option value="SW">Sewa</option>
                                    <option value="MS">Milik Sendiri</option>
                                </select>
                            </div>
                        </div>

                        <div>
                            <label for="lama_berjualan" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">Lama Usaha</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                                    <i data-lucide="calendar-clock" class="w-4 h-4"></i>
                                </div>
                                <select name="lama_berjualan" id="lama_berjualan"
                                    class="w-full h-[36px] pl-9 pr-8 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-950 text-xs font-medium text-slate-700 dark:text-slate-200 focus:outline-none focus:border-[#284c9a] focus:ring-2 focus:ring-[#284c9a]/15 transition">
                                    <option value="">Pilih Lama Usaha</option>
                                    <option value="LU01">&lt; 2 Tahun</option>
                                    <option value="LU02">2 - 5 Tahun</option>
                                    <option value="LU03">&gt; 5 Tahun</option>
                                </select>
                            </div>
                        </div>

                        <div>
                            <label for="status_outlet" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">Status Outlet</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                                    <i data-lucide="badge-check" class="w-4 h-4"></i>
                                </div>
                                <select name="status_outlet" id="status_outlet"
                                    class="w-full h-[36px] pl-9 pr-8 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-950 text-xs font-medium text-slate-700 dark:text-slate-200 focus:outline-none focus:border-[#284c9a] focus:ring-2 focus:ring-[#284c9a]/15 transition">
                                    <option value="">Pilih Status Outlet</option>
                                    <option value="NO">New Outlet</option>
                                    <option value="EO">Existing Outlet</option>
                                </select>
                            </div>
                        </div>

                        <div>
                            <label for="kode_klasifikasi" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">Klasifikasi Outlet</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                                    <i data-lucide="layers" class="w-4 h-4"></i>
                                </div>
                                <select name="kode_klasifikasi" id="kode_klasifikasi"
                                    class="w-full h-[36px] pl-9 pr-8 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-950 text-xs font-medium text-slate-700 dark:text-slate-200 focus:outline-none focus:border-[#284c9a] focus:ring-2 focus:ring-[#284c9a]/15 transition">
                                    <option value="">Pilih Klasifikasi</option>
                                    @foreach ($klasifikasi_outlet as $d)
                                        <option value="{{ $d->kode_klasifikasi }}">{{ textUpperCase($d->klasifikasi) }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div>
                            <label for="type_outlet" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">Type Outlet</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                                    <i data-lucide="tag" class="w-4 h-4"></i>
                                </div>
                                <select name="type_outlet" id="type_outlet"
                                    class="w-full h-[36px] pl-9 pr-8 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-950 text-xs font-medium text-slate-700 dark:text-slate-200 focus:outline-none focus:border-[#284c9a] focus:ring-2 focus:ring-[#284c9a]/15 transition">
                                    <option value="">Pilih Type Outlet</option>
                                    <option value="GR">Grosir</option>
                                    <option value="RT">Retail</option>
                                </select>
                            </div>
                        </div>

                        <div>
                            <label for="cara_pembayaran" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">Cara Pembayaran</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                                    <i data-lucide="wallet" class="w-4 h-4"></i>
                                </div>
                                <select name="cara_pembayaran" id="cara_pembayaran"
                                    class="w-full h-[36px] pl-9 pr-8 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-950 text-xs font-medium text-slate-700 dark:text-slate-200 focus:outline-none focus:border-[#284c9a] focus:ring-2 focus:ring-[#284c9a]/15 transition">
                                    <option value="">Pilih Cara Pembayaran</option>
                                    <option value="BT">Bank Transfer</option>
                                    <option value="AC">Advance Cash</option>
                                    <option value="CQ">Cheque</option>
                                </select>
                            </div>
                        </div>

                        <div>
                            <label for="lama_langganan" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">Lama Langganan</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                                    <i data-lucide="history" class="w-4 h-4"></i>
                                </div>
                                <select name="lama_langganan" id="lama_langganan"
                                    class="w-full h-[36px] pl-9 pr-8 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-950 text-xs font-medium text-slate-700 dark:text-slate-200 focus:outline-none focus:border-[#284c9a] focus:ring-2 focus:ring-[#284c9a]/15 transition">
                                    <option value="">Pilih Lama Langganan</option>
                                    <option value="LL01">&lt; 2 Tahun</option>
                                    <option value="LL02">&gt; 2 Tahun</option>
                                </select>
                            </div>
                        </div>

                        <div>
                            <label for="jaminan" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">Jaminan</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                                    <i data-lucide="shield-check" class="w-4 h-4"></i>
                                </div>
                                <select name="jaminan" id="jaminan"
                                    class="w-full h-[36px] pl-9 pr-8 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-950 text-xs font-medium text-slate-700 dark:text-slate-200 focus:outline-none focus:border-[#284c9a] focus:ring-2 focus:ring-[#284c9a]/15 transition">
                                    <option value="">Pilih Jaminan</option>
                                    <option value="1">Ada</option>
                                    <option value="0">Tidak Ada</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div>
                        <label for="lokasi" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">Titik Koordinat</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                                <i data-lucide="map-pin" class="w-4 h-4"></i>
                            </div>
                            <input type="text" name="lokasi" id="lokasi" placeholder="-7.123456, 108.123456"
                                class="w-full h-[36px] pl-9 pr-3 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-950 text-xs font-medium text-slate-700 dark:text-slate-200 focus:outline-none focus:border-[#284c9a] focus:ring-2 focus:ring-[#284c9a]/15 transition">
                        </div>
                    </div>

                    <div>
                        <label for="omset_toko" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">Omset Toko</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                                <i data-lucide="banknote" class="w-4 h-4"></i>
                            </div>
                            <input type="text" name="omset_toko" id="omset_toko" placeholder="0"
                                class="money text-right w-full h-[36px] pl-9 pr-3 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-950 text-xs font-medium font-mono-num text-slate-700 dark:text-slate-200 focus:outline-none focus:border-[#284c9a] focus:ring-2 focus:ring-[#284c9a]/15 transition">
                        </div>
                    </div>

                    <div class="pt-3 border-t border-slate-100 dark:border-slate-800 flex flex-col-reverse sm:flex-row sm:items-center sm:justify-end gap-2">
                        <a href="{{ route('sfa.pelanggan') }}"
                            class="w-full sm:w-auto h-[36px] px-4 rounded-lg border border-slate-200 dark:border-slate-700 text-xs font-bold text-slate-600 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-800 inline-flex items-center justify-center transition">
                            Batal
                        </a>
                        <button type="submit" id="btnSimpan"
                            class="w-full sm:w-auto h-[36px] px-4 rounded-lg bg-[#284c9a] hover:bg-[#1e3a78] text-white text-xs font-bold shadow-xs transition inline-flex items-center justify-center gap-1.5 cursor-pointer">
                            <i data-lucide="save" class="w-3.5 h-3.5"></i>
                            <span>Simpan Outlet</span>
                        </button>
                    </div>
                </div>
            </div>

        </div>
    </form>
</div>
@endsection

@push('myscript')
<script>
    $(function() {
        if (typeof lucide !== 'undefined') lucide.createIcons();

        $('.flatpickr-date').flatpickr({
            dateFormat: 'Y-m-d',
            allowInput: false
        });

        $('.select2Kodewilayah').select2({
            placeholder: 'Pilih Wilayah',
            allowClear: true,
            dropdownCssClass: 'select2-dropdown-v2'
        });

        $('input[type="checkbox"][name="hari[]"]').on('change', function() {
            const checkedCount = $('input[type="checkbox"][name="hari[]"]:checked').length;
            if (checkedCount > 2) {
                $(this).prop('checked', false);
                Swal.fire({
                    icon: 'warning',
                    title: 'Oops...',
                    text: 'Hanya boleh memilih 2 hari'
                });
            }
        });

        function getwilayahbyCabang() {
            const kode_cabang = $('#formcreatePelanggan').find('#kode_cabang').val();
            $.ajax({
                type: 'POST',
                url: '/wilayah/getwilayahbycabang',
                data: {
                    _token: "{{ csrf_token() }}",
                    kode_cabang: kode_cabang
                },
                cache: false,
                success: function(respond) {
                    $('#formcreatePelanggan').find('#kode_wilayah').html(respond).trigger('change.select2');
                }
            });
        }

        getwilayahbyCabang();

        const $form = $('#formcreatePelanggan');

        function showFieldError($field, message) {
            $field.addClass('border-rose-500 ring-1 ring-rose-500');
            if ($field.hasClass('select2-hidden-accessible')) {
                $field.next('.select2-container').find('.select2-selection').addClass('!border-rose-500 !ring-1 !ring-rose-500');
            }
            const $group = $field.closest('.field-group');
            $group.find('.input-prefix-icon').addClass('text-rose-500');
            $group.find('.field-error-msg').remove();
            $group.append(`
                <p class="field-error-msg text-[11px] font-semibold text-rose-600 dark:text-rose-400 mt-1 pl-1 flex items-center gap-1">
                    <svg class="w-3.5 h-3.5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                    <span>${message}</span>
                </p>
            `);
        }

        function clearFieldError($field) {
            $field.removeClass('border-rose-500 ring-1 ring-rose-500');
            if ($field.hasClass('select2-hidden-accessible')) {
                $field.next('.select2-container').find('.select2-selection').removeClass('!border-rose-500 !ring-1 !ring-rose-500');
            }
            const $group = $field.closest('.field-group');
            $group.find('.input-prefix-icon').removeClass('text-rose-500');
            $group.find('.field-error-msg').remove();
        }

        $form.on('input change', 'input, textarea, select', function() {
            clearFieldError($(this));
        });
        $form.find('select').on('select2:select select2:clear', function() {
            clearFieldError($(this));
        });

        $('.na_nohp').on('change', function() {
            const $hp = $form.find('#no_hp_pelanggan');
            if (this.checked) {
                $hp.val('NA').attr('readonly', true);
                clearFieldError($hp);
            } else {
                $hp.val('').attr('readonly', false);
            }
        });

        $form.on('submit', function(e) {
            const $nama = $form.find('#nama_pelanggan');
            const $alamat = $form.find('#alamat_pelanggan');
            const $alamatToko = $form.find('#alamat_toko');
            const $noHp = $form.find('#no_hp_pelanggan');
            const $wilayah = $form.find('#kode_wilayah');
            const $ljt = $form.find('#ljt');

            let firstInvalid = null;

            if (!$nama.val().trim()) {
                showFieldError($nama, 'Nama Pelanggan wajib diisi.');
                firstInvalid = firstInvalid || $nama;
            }
            if (!$alamat.val().trim()) {
                showFieldError($alamat, 'Alamat Pelanggan wajib diisi.');
                firstInvalid = firstInvalid || $alamat;
            }
            if (!$alamatToko.val().trim()) {
                showFieldError($alamatToko, 'Alamat Toko wajib diisi.');
                firstInvalid = firstInvalid || $alamatToko;
            }

            const hpVal = $noHp.val().trim();
            if (!hpVal) {
                showFieldError($noHp, 'No. HP wajib diisi.');
                firstInvalid = firstInvalid || $noHp;
            } else if (hpVal !== 'NA' && hpVal.length < 10) {
                showFieldError($noHp, 'No. HP minimal 10 karakter.');
                firstInvalid = firstInvalid || $noHp;
            }

            if (!$wilayah.val()) {
                showFieldError($wilayah, 'Wilayah / Route wajib dipilih.');
                firstInvalid = firstInvalid || $wilayah;
            }
            if (!$ljt.val()) {
                showFieldError($ljt, 'LJT wajib dipilih.');
                firstInvalid = firstInvalid || $ljt;
            }

            if (firstInvalid) {
                e.preventDefault();
                firstInvalid.focus();
                return false;
            }

            const $btn = $form.find('#btnSimpan');
            $btn.prop('disabled', true).addClass('opacity-75 cursor-not-allowed pointer-events-none').html(`
                <svg class="animate-spin h-3.5 w-3.5 text-white shrink-0 inline-block" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <span>Menyimpan...</span>
            `);
        });
    });
</script>
@endpush
