@extends('layouts.app')
@section('titlepage', 'Ajuan Limit Pelanggan')

@section('navigasi')
    <div class="flex items-center gap-1.5 text-xs">
        <a href="{{ route('sfa.pelanggan') }}" class="text-slate-400 hover:text-[#284c9a] transition">SFA</a>
        <i data-lucide="chevron-right" class="w-3 h-3 text-slate-400"></i>
        <a href="{{ route('sfa.showpelanggan', Crypt::encrypt($pelanggan->kode_pelanggan)) }}"
            class="text-slate-400 hover:text-[#284c9a] transition truncate max-w-[120px] sm:max-w-none">{{ $pelanggan->nama_pelanggan }}</a>
        <i data-lucide="chevron-right" class="w-3 h-3 text-slate-400"></i>
        <span class="text-brand-600 dark:text-brand-400 font-bold">Ajuan Limit</span>
    </div>
@endsection

@section('content')
<div class="max-w-4xl mx-auto space-y-3">
    <!-- PAGE HEADER -->
    <div class="flex items-center justify-between gap-2">
        <div>
            <h3 class="text-base font-black text-slate-900 dark:text-white leading-tight">Ajuan Limit Pelanggan</h3>
            <p class="text-[11px] text-slate-400 mt-0.5">Lengkapi data pelanggan dan analisa kelayakan kredit outlet.</p>
        </div>
        <a href="{{ route('sfa.showpelanggan', Crypt::encrypt($pelanggan->kode_pelanggan)) }}"
            class="h-[34px] px-3 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-600 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-800 text-xs font-bold inline-flex items-center gap-1.5 shrink-0 transition">
            <i data-lucide="arrow-left" class="w-3.5 h-3.5"></i>
            <span>Kembali</span>
        </a>
    </div>

    <form action="{{ route('sfa.storeajuanlimit', Crypt::encrypt($pelanggan->kode_pelanggan)) }}"
        autocomplete="off" id="formAjuanlimit" method="POST" enctype="multipart/form-data" class="space-y-3">
        @csrf

        <!-- CARD 1: DATA IDENTITAS & ALAMAT PELANGGAN -->
        <div class="bg-white dark:bg-slate-900 rounded-xl border border-slate-200/80 dark:border-slate-800 shadow-2xs p-3 sm:p-3.5">
            <div class="flex items-center gap-2 pb-2 mb-2.5 border-b border-slate-100 dark:border-slate-800">
                <i data-lucide="user-check" class="w-3.5 h-3.5 text-[#284c9a] dark:text-blue-400"></i>
                <h4 class="text-xs font-bold text-slate-700 dark:text-slate-200">Identitas & Kontak Pelanggan</h4>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-2.5">
                <div>
                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">Kode Pelanggan</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                            <i data-lucide="barcode" class="w-3.5 h-3.5"></i>
                        </div>
                        <input type="text" name="kode_pelanggan" id="kode_pelanggan" value="{{ $pelanggan->kode_pelanggan }}" disabled
                            class="w-full h-[36px] pl-8 pr-3 rounded-lg border border-slate-200 dark:border-slate-700 bg-slate-100 dark:bg-slate-800/70 text-xs font-mono-num font-semibold text-slate-500 dark:text-slate-400 cursor-not-allowed">
                    </div>
                </div>

                <div>
                    <label for="nama_pelanggan" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                        Nama Pelanggan <span class="text-rose-500">*</span>
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                            <i data-lucide="user" class="w-3.5 h-3.5"></i>
                        </div>
                        <input type="text" name="nama_pelanggan" id="nama_pelanggan" value="{{ $pelanggan->nama_pelanggan }}"
                            placeholder="Nama Pelanggan"
                            class="w-full h-[36px] pl-8 pr-3 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-xs font-medium text-slate-700 dark:text-slate-200 focus:outline-none focus:border-[#284c9a] focus:ring-2 focus:ring-[#284c9a]/15 transition">
                    </div>
                </div>

                <div>
                    <label for="nik" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">NIK</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                            <i data-lucide="credit-card" class="w-3.5 h-3.5"></i>
                        </div>
                        <input type="text" name="nik" id="nik" value="{{ $pelanggan->nik }}" placeholder="Nomor Induk Kependudukan"
                            class="w-full h-[36px] pl-8 pr-3 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-xs font-mono-num font-medium text-slate-700 dark:text-slate-200 focus:outline-none focus:border-[#284c9a] focus:ring-2 focus:ring-[#284c9a]/15 transition">
                    </div>
                </div>

                <div>
                    <label for="no_kk" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">No. KK</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                            <i data-lucide="file-text" class="w-3.5 h-3.5"></i>
                        </div>
                        <input type="text" name="no_kk" id="no_kk" value="{{ $pelanggan->no_kk }}" placeholder="Nomor Kartu Keluarga"
                            class="w-full h-[36px] pl-8 pr-3 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-xs font-mono-num font-medium text-slate-700 dark:text-slate-200 focus:outline-none focus:border-[#284c9a] focus:ring-2 focus:ring-[#284c9a]/15 transition">
                    </div>
                </div>

                <div>
                    <label for="tanggal_lahir" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">Tanggal Lahir</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                            <i data-lucide="calendar" class="w-3.5 h-3.5"></i>
                        </div>
                        <input type="text" name="tanggal_lahir" id="tanggal_lahir" value="{{ $pelanggan->tanggal_lahir }}"
                            placeholder="Pilih Tanggal" readonly
                            class="flatpickr-date w-full h-[36px] pl-8 pr-3 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-xs font-medium text-slate-700 dark:text-slate-200 focus:outline-none focus:border-[#284c9a] focus:ring-2 focus:ring-[#284c9a]/15 transition cursor-pointer">
                    </div>
                </div>

                <div>
                    <label for="no_hp_pelanggan" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">No. HP</label>
                    <div class="flex items-center gap-2">
                        <div class="relative flex-1">
                            <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                                <i data-lucide="phone" class="w-3.5 h-3.5"></i>
                            </div>
                            <input type="text" name="no_hp_pelanggan" id="no_hp_pelanggan" value="{{ $pelanggan->no_hp_pelanggan }}"
                                placeholder="08xxxxxxxxxx" {{ $pelanggan->no_hp_pelanggan == 'NA' ? 'readonly' : '' }}
                                class="w-full h-[36px] pl-8 pr-3 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-xs font-mono-num font-medium text-slate-700 dark:text-slate-200 focus:outline-none focus:border-[#284c9a] focus:ring-2 focus:ring-[#284c9a]/15 transition">
                        </div>
                        <label class="inline-flex items-center gap-1.5 h-[36px] px-2.5 rounded-lg border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 text-xs font-semibold text-slate-600 dark:text-slate-300 cursor-pointer shrink-0">
                            <input type="checkbox" class="na_nohp rounded border-slate-300 text-[#284c9a] focus:ring-[#284c9a]" value="1" id="na_nohp"
                                {{ $pelanggan->no_hp_pelanggan == 'NA' ? 'checked' : '' }}>
                            <span>NA</span>
                        </label>
                    </div>
                </div>

                <div>
                    <label for="alamat_pelanggan" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                        Alamat Pelanggan <span class="text-rose-500">*</span>
                    </label>
                    <div class="relative">
                        <div class="absolute top-2.5 left-2.5 pointer-events-none text-slate-400 z-10">
                            <i data-lucide="map-pin" class="w-3.5 h-3.5"></i>
                        </div>
                        <textarea name="alamat_pelanggan" id="alamat_pelanggan" rows="2" placeholder="Alamat lengkap pelanggan"
                            class="w-full pl-8 pr-3 py-2 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-xs font-medium text-slate-700 dark:text-slate-200 focus:outline-none focus:border-[#284c9a] focus:ring-2 focus:ring-[#284c9a]/15 transition">{{ $pelanggan->alamat_pelanggan }}</textarea>
                    </div>
                </div>

                <div>
                    <label for="alamat_toko" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">Alamat Toko</label>
                    <div class="relative">
                        <div class="absolute top-2.5 left-2.5 pointer-events-none text-slate-400 z-10">
                            <i data-lucide="store" class="w-3.5 h-3.5"></i>
                        </div>
                        <textarea name="alamat_toko" id="alamat_toko" rows="2" placeholder="Alamat lengkap toko"
                            class="w-full pl-8 pr-3 py-2 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-xs font-medium text-slate-700 dark:text-slate-200 focus:outline-none focus:border-[#284c9a] focus:ring-2 focus:ring-[#284c9a]/15 transition">{{ $pelanggan->alamat_toko }}</textarea>
                    </div>
                </div>

                @hasanyrole($roles_show_cabang)
                    <div>
                        <label for="kode_cabang" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">Cabang</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-20">
                                <i data-lucide="building-2" class="w-3.5 h-3.5"></i>
                            </div>
                            <select name="kode_cabang" id="kode_cabang"
                                class="w-full h-[36px] pl-8 pr-3 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-xs font-medium text-slate-700 dark:text-slate-200 focus:outline-none focus:border-[#284c9a] focus:ring-2 focus:ring-[#284c9a]/15 transition">
                                <option value="">Pilih Cabang</option>
                                @foreach ($cabang as $c)
                                    <option value="{{ $c->kode_cabang }}" {{ $pelanggan->kode_cabang == $c->kode_cabang ? 'selected' : '' }}>
                                        {{ $c->nama_cabang }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                @endhasanyrole

                <div>
                    <label for="kode_wilayah" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                        Wilayah / Rute <span class="text-rose-500">*</span>
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-20">
                            <i data-lucide="map" class="w-3.5 h-3.5"></i>
                        </div>
                        <select name="kode_wilayah" id="kode_wilayah" class="select2Kodewilayah w-full">
                        </select>
                    </div>
                </div>

                <div>
                    <label for="hari" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">Hari Kunjungan</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                            <i data-lucide="calendar-days" class="w-3.5 h-3.5"></i>
                        </div>
                        <select name="hari" id="hari"
                            class="w-full h-[36px] pl-8 pr-3 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-xs font-medium text-slate-700 dark:text-slate-200 focus:outline-none focus:border-[#284c9a] focus:ring-2 focus:ring-[#284c9a]/15 transition">
                            <option value="">Pilih Hari</option>
                            @foreach (['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'] as $h)
                                <option value="{{ $h }}" {{ $pelanggan->hari == $h ? 'selected' : '' }}>{{ $h }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <!-- CARD 2: PENGAJUAN LIMIT & PROFIL OUTLET -->
        <div class="bg-white dark:bg-slate-900 rounded-xl border border-slate-200/80 dark:border-slate-800 shadow-2xs p-3 sm:p-3.5">
            <div class="flex items-center justify-between gap-2 pb-2 mb-2.5 border-b border-slate-100 dark:border-slate-800">
                <div class="flex items-center gap-2">
                    <i data-lucide="wallet" class="w-3.5 h-3.5 text-[#284c9a] dark:text-blue-400"></i>
                    <h4 class="text-xs font-bold text-slate-700 dark:text-slate-200">Pengajuan Limit & Profil Outlet</h4>
                </div>
                <span class="px-2 py-0.5 rounded-md bg-amber-50 dark:bg-amber-950/50 text-amber-700 dark:text-amber-300 text-[10px] font-semibold">
                    Penentu Skor Kredit
                </span>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-2.5">
                <div>
                    <label for="jumlah" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                        Jumlah Ajuan Limit <span class="text-rose-500">*</span>
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                            <i data-lucide="banknote" class="w-3.5 h-3.5"></i>
                        </div>
                        <input type="text" name="jumlah" id="jumlah" placeholder="0"
                            class="money w-full h-[36px] pl-8 pr-3 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-xs font-mono-num font-bold text-right text-slate-700 dark:text-slate-200 focus:outline-none focus:border-[#284c9a] focus:ring-2 focus:ring-[#284c9a]/15 transition">
                    </div>
                </div>

                <div>
                    <label for="omset_toko" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">Omset Toko</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                            <i data-lucide="trending-up" class="w-3.5 h-3.5"></i>
                        </div>
                        <input type="text" name="omset_toko" id="omset_toko" value="{{ formatRupiah($pelanggan->omset_toko) }}" placeholder="0"
                            class="money w-full h-[36px] pl-8 pr-3 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-xs font-mono-num font-bold text-right text-slate-700 dark:text-slate-200 focus:outline-none focus:border-[#284c9a] focus:ring-2 focus:ring-[#284c9a]/15 transition">
                    </div>
                </div>

                <div>
                    <label for="ljt" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                        LJT (Hari) <span class="text-rose-500">*</span>
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                            <i data-lucide="clock" class="w-3.5 h-3.5"></i>
                        </div>
                        <select name="ljt" id="ljt"
                            class="w-full h-[36px] pl-8 pr-3 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-xs font-medium text-slate-700 dark:text-slate-200 focus:outline-none focus:border-[#284c9a] focus:ring-2 focus:ring-[#284c9a]/15 transition">
                            <option value="">Pilih LJT</option>
                            <option value="14" {{ $pelanggan->ljt == 14 ? 'selected' : '' }}>14 Hari</option>
                            <option value="30" {{ $pelanggan->ljt == 30 ? 'selected' : '' }}>30 Hari</option>
                            <option value="45" {{ $pelanggan->ljt == 45 ? 'selected' : '' }}>45 Hari</option>
                        </select>
                    </div>
                </div>

                <div>
                    <label for="cara_pembayaran" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">Cara Pembayaran</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                            <i data-lucide="credit-card" class="w-3.5 h-3.5"></i>
                        </div>
                        <select name="cara_pembayaran" id="cara_pembayaran"
                            class="w-full h-[36px] pl-8 pr-3 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-xs font-medium text-slate-700 dark:text-slate-200 focus:outline-none focus:border-[#284c9a] focus:ring-2 focus:ring-[#284c9a]/15 transition">
                            <option value="">Pilih Cara Pembayaran</option>
                            <option value="BT" {{ $pelanggan->cara_pembayaran == 'BT' ? 'selected' : '' }}>Bank Transfer</option>
                            <option value="AC" {{ $pelanggan->cara_pembayaran == 'AC' ? 'selected' : '' }}>Advance Cash</option>
                            <option value="CQ" {{ $pelanggan->cara_pembayaran == 'CQ' ? 'selected' : '' }}>Cheque</option>
                        </select>
                    </div>
                </div>

                <div>
                    <label for="kepemilikan" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">Kepemilikan</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                            <i data-lucide="home" class="w-3.5 h-3.5"></i>
                        </div>
                        <select name="kepemilikan" id="kepemilikan"
                            class="w-full h-[36px] pl-8 pr-3 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-xs font-medium text-slate-700 dark:text-slate-200 focus:outline-none focus:border-[#284c9a] focus:ring-2 focus:ring-[#284c9a]/15 transition">
                            <option value="">Pilih Kepemilikan</option>
                            <option value="SW" {{ $pelanggan->kepemilikan == 'SW' ? 'selected' : '' }}>Sewa</option>
                            <option value="MS" {{ $pelanggan->kepemilikan == 'MS' ? 'selected' : '' }}>Milik Sendiri</option>
                        </select>
                    </div>
                </div>

                <div>
                    <label for="lama_berjualan" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">Lama Usaha</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                            <i data-lucide="hourglass" class="w-3.5 h-3.5"></i>
                        </div>
                        <select name="lama_berjualan" id="lama_berjualan"
                            class="w-full h-[36px] pl-8 pr-3 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-xs font-medium text-slate-700 dark:text-slate-200 focus:outline-none focus:border-[#284c9a] focus:ring-2 focus:ring-[#284c9a]/15 transition">
                            <option value="">Pilih Lama Usaha</option>
                            <option value="LU01" {{ $pelanggan->lama_berjualan == 'LU01' ? 'selected' : '' }}>&lt; 2 Tahun</option>
                            <option value="LU02" {{ $pelanggan->lama_berjualan == 'LU02' ? 'selected' : '' }}>2 - 5 Tahun</option>
                            <option value="LU03" {{ $pelanggan->lama_berjualan == 'LU03' ? 'selected' : '' }}>&gt; 5 Tahun</option>
                        </select>
                    </div>
                </div>

                <div>
                    <label for="status_outlet" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">Status Outlet</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                            <i data-lucide="badge-check" class="w-3.5 h-3.5"></i>
                        </div>
                        <select name="status_outlet" id="status_outlet"
                            class="w-full h-[36px] pl-8 pr-3 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-xs font-medium text-slate-700 dark:text-slate-200 focus:outline-none focus:border-[#284c9a] focus:ring-2 focus:ring-[#284c9a]/15 transition">
                            <option value="">Pilih Status Outlet</option>
                            <option value="NO" {{ $pelanggan->status_outlet == 'NO' ? 'selected' : '' }}>New Outlet</option>
                            <option value="EO" {{ $pelanggan->status_outlet == 'EO' ? 'selected' : '' }}>Existing Outlet</option>
                        </select>
                    </div>
                </div>

                <div>
                    <label for="type_outlet" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">Type Outlet</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                            <i data-lucide="tag" class="w-3.5 h-3.5"></i>
                        </div>
                        <select name="type_outlet" id="type_outlet"
                            class="w-full h-[36px] pl-8 pr-3 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-xs font-medium text-slate-700 dark:text-slate-200 focus:outline-none focus:border-[#284c9a] focus:ring-2 focus:ring-[#284c9a]/15 transition">
                            <option value="">Pilih Type Outlet</option>
                            <option value="GR" {{ $pelanggan->type_outlet == 'GR' ? 'selected' : '' }}>Grosir</option>
                            <option value="RT" {{ $pelanggan->type_outlet == 'RT' ? 'selected' : '' }}>Retail</option>
                        </select>
                    </div>
                </div>

                <div>
                    <label for="kode_klasifikasi" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">Klasifikasi Outlet</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                            <i data-lucide="layers" class="w-3.5 h-3.5"></i>
                        </div>
                        <select name="kode_klasifikasi" id="kode_klasifikasi"
                            class="w-full h-[36px] pl-8 pr-3 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-xs font-medium text-slate-700 dark:text-slate-200 focus:outline-none focus:border-[#284c9a] focus:ring-2 focus:ring-[#284c9a]/15 transition">
                            <option value="">Pilih Klasifikasi Outlet</option>
                            @foreach ($klasifikasi_outlet as $ko)
                                <option value="{{ $ko->kode_klasifikasi }}" {{ $pelanggan->kode_klasifikasi == $ko->kode_klasifikasi ? 'selected' : '' }}>
                                    {{ strtoupper($ko->klasifikasi) }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div>
                    <label for="lama_langganan" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">Lama Langganan</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                            <i data-lucide="history" class="w-3.5 h-3.5"></i>
                        </div>
                        <select name="lama_langganan" id="lama_langganan"
                            class="w-full h-[36px] pl-8 pr-3 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-xs font-medium text-slate-700 dark:text-slate-200 focus:outline-none focus:border-[#284c9a] focus:ring-2 focus:ring-[#284c9a]/15 transition">
                            <option value="">Pilih Lama Langganan</option>
                            <option value="LL01" {{ $pelanggan->lama_langganan == 'LL01' ? 'selected' : '' }}>&lt; 2 Tahun</option>
                            <option value="LL02" {{ $pelanggan->lama_langganan == 'LL02' ? 'selected' : '' }}>&gt; 2 Tahun</option>
                        </select>
                    </div>
                </div>

                <div>
                    <label for="jaminan" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">Jaminan</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                            <i data-lucide="shield-check" class="w-3.5 h-3.5"></i>
                        </div>
                        <select name="jaminan" id="jaminan"
                            class="w-full h-[36px] pl-8 pr-3 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-xs font-medium text-slate-700 dark:text-slate-200 focus:outline-none focus:border-[#284c9a] focus:ring-2 focus:ring-[#284c9a]/15 transition">
                            <option value="">Pilih Jaminan</option>
                            <option value="1" {{ $pelanggan->jaminan === '1' ? 'selected' : '' }}>Ada</option>
                            <option value="0" {{ $pelanggan->jaminan === '0' ? 'selected' : '' }}>Tidak Ada</option>
                        </select>
                    </div>
                </div>

                <div>
                    <label for="histori_transaksi" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                        Histori Pembayaran 6 Bulan Terakhir <span class="text-rose-500">*</span>
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                            <i data-lucide="receipt" class="w-3.5 h-3.5"></i>
                        </div>
                        <select name="histori_transaksi" id="histori_transaksi"
                            class="w-full h-[36px] pl-8 pr-3 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-xs font-medium text-slate-700 dark:text-slate-200 focus:outline-none focus:border-[#284c9a] focus:ring-2 focus:ring-[#284c9a]/15 transition">
                            <option value="">Pilih Histori Pembayaran</option>
                            <option value="HT01">&lt; 14 Hari</option>
                            <option value="HT02">= 14 Hari</option>
                            <option value="HT03">&gt; 14 Hari</option>
                        </select>
                    </div>
                </div>

                <div>
                    <label for="topup_terakhir" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                        Terakhir Top Up <span class="text-rose-500">*</span>
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                            <i data-lucide="calendar" class="w-3.5 h-3.5"></i>
                        </div>
                        <input type="text" name="topup_terakhir" id="topup_terakhir" placeholder="Pilih Tanggal" readonly
                            class="flatpickr-date w-full h-[36px] pl-8 pr-3 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-xs font-medium text-slate-700 dark:text-slate-200 focus:outline-none focus:border-[#284c9a] focus:ring-2 focus:ring-[#284c9a]/15 transition cursor-pointer">
                    </div>
                    <input type="hidden" id="lama_topup" name="lama_topup">
                    <span id="usia_topup" class="block text-[11px] font-semibold text-[#284c9a] dark:text-blue-400 mt-1 pl-1"></span>
                </div>
            </div>
        </div>

        <!-- CARD 3: FAKTUR BELUM LUNAS & SKOR KELAYAKAN -->
        <div class="bg-white dark:bg-slate-900 rounded-xl border border-slate-200/80 dark:border-slate-800 shadow-2xs overflow-hidden">
            <div class="bg-[#284c9a] px-3.5 py-2 flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <i data-lucide="file-warning" class="w-3.5 h-3.5 text-blue-200"></i>
                    <h4 class="text-xs font-bold text-white">Faktur Belum Lunas</h4>
                </div>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse whitespace-nowrap">
                    <thead class="bg-[#1e3a78]">
                        <tr>
                            <th class="py-2 px-3 text-[11px] font-bold uppercase tracking-wider text-white/90">No. Faktur</th>
                            <th class="py-2 px-3 text-[11px] font-bold uppercase tracking-wider text-white/90 text-right">Sisa Piutang</th>
                        </tr>
                    </thead>
                    <tbody id="loadlistfakturkredit" class="divide-y divide-slate-100 dark:divide-slate-800 text-xs text-slate-700 dark:text-slate-200"></tbody>
                </table>
                <input type="hidden" id="jml_faktur" name="jml_faktur">
            </div>
        </div>

        <!-- STRIP SKOR & REKOMENDASI -->
        <div class="rounded-xl bg-[#284c9a] text-white overflow-hidden shadow-xs">
            <input type="hidden" name="skor" id="skor">
            <div class="grid grid-cols-2 divide-x divide-white/15">
                <div class="p-3 sm:p-3.5">
                    <p class="text-[11px] text-white/75 font-semibold">Total Skor</p>
                    <p id="total_score" class="text-lg font-black font-mono-num mt-0.5">0.00</p>
                </div>
                <div class="p-3 sm:p-3.5">
                    <p class="text-[11px] text-white/75 font-semibold">Rekomendasi</p>
                    <p id="rekomendasi" class="text-sm sm:text-base font-black mt-0.5">-</p>
                </div>
            </div>
        </div>

        <!-- CARD 4: URAIAN ANALISA & SUBMIT -->
        <div class="bg-white dark:bg-slate-900 rounded-xl border border-slate-200/80 dark:border-slate-800 shadow-2xs p-3 sm:p-3.5 space-y-3">
            <div>
                <label for="uraian_analisa" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                    Uraian Analisa <span class="text-rose-500">*</span>
                </label>
                <div class="relative">
                    <div class="absolute top-2.5 left-2.5 pointer-events-none text-slate-400 z-10">
                        <i data-lucide="file-edit" class="w-3.5 h-3.5"></i>
                    </div>
                    <textarea name="uraian_analisa" id="uraian_analisa" rows="3" placeholder="Tuliskan uraian analisa kelayakan kredit..."
                        class="w-full pl-8 pr-3 py-2 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-xs font-medium text-slate-700 dark:text-slate-200 focus:outline-none focus:border-[#284c9a] focus:ring-2 focus:ring-[#284c9a]/15 transition"></textarea>
                </div>
            </div>

            <div class="flex items-center justify-end gap-2 pt-2 border-t border-slate-100 dark:border-slate-800">
                <a href="{{ route('sfa.showpelanggan', Crypt::encrypt($pelanggan->kode_pelanggan)) }}"
                    class="h-[36px] px-4 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-600 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-800 text-xs font-bold inline-flex items-center justify-center transition">
                    Batal
                </a>
                <button type="submit" id="btnSimpan"
                    class="h-[36px] px-5 rounded-lg bg-[#284c9a] hover:bg-[#1e3a78] text-white text-xs font-bold shadow-xs transition inline-flex items-center justify-center gap-1.5">
                    <i data-lucide="send" class="w-3.5 h-3.5"></i>
                    <span>Kirim Ajuan Limit</span>
                </button>
            </div>
        </div>
    </form>
</div>
@endsection

@push('myscript')
<script src="{{ asset('assets/js/pages/ajuanlimit.js') }}"></script>
<script>
    $(function() {
        if (typeof lucide !== 'undefined') lucide.createIcons();

        const kode_pelanggan = "{{ $pelanggan->kode_pelanggan }}";
        const $form = $("#formAjuanlimit");

        if (typeof flatpickr !== 'undefined') {
            flatpickr(".flatpickr-date", {
                dateFormat: "Y-m-d",
                allowInput: false
            });
        }

        if ($('.select2Kodewilayah').length) {
            $('.select2Kodewilayah').select2({
                placeholder: 'Pilih Wilayah',
                width: '100%'
            });
        }

        function getwilayahbyCabang() {
            var kode_cabang = $form.find("#kode_cabang").val();
            $.ajax({
                type: 'POST',
                url: '/wilayah/getwilayahbycabang',
                data: {
                    _token: "{{ csrf_token() }}",
                    kode_cabang: kode_cabang,
                    kode_wilayah: "{{ $pelanggan->kode_wilayah }}",
                },
                cache: false,
                success: function(respond) {
                    $("#kode_wilayah").html(respond);
                }
            });
        }

        getwilayahbyCabang();

        $form.find("#kode_cabang").change(function() {
            getwilayahbyCabang();
        });

        $('.na_nohp').change(function() {
            if (this.checked) {
                $("#no_hp_pelanggan").val("NA").attr("readonly", true);
            } else {
                $("#no_hp_pelanggan").val("").attr("readonly", false);
            }
        });

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

        getlistFakturkredit(kode_pelanggan);

        $form.find("#omset_toko").on('keyup keydown', function() {
            var jumlah = $("#jumlah").val();
            if (jumlah == "" || jumlah == 0) {
                $(this).prop('readonly', true);
                Swal.fire({
                    title: "Oops!",
                    text: "Jumlah Ajuan Kredit Harus Diisi Dulu !",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: () => {
                        $form.find("#jumlah").focus();
                    },
                });
            }
            loadSkor();
        });

        $form.find("#jumlah").on('keyup keydown', function() {
            var jumlah = $("#jumlah").val();
            if (jumlah == "" || jumlah == 0) {
                $("#omset_toko").prop('readonly', true);
            } else {
                $("#omset_toko").prop('readonly', false);
            }
            loadSkor();
        });

        function gettopupTerakhir() {
            const tanggal = $("#topup_terakhir").val();
            $.ajax({
                type: 'POST',
                url: '/ajuanlimit/gettopupterakhir',
                data: {
                    _token: "{{ csrf_token() }}",
                    tanggal: tanggal
                },
                cache: false,
                success: function(response) {
                    $("#usia_topup").text(response.data.usia_topup);
                    $("#lama_topup").val(response.data.lama_topup);
                    loadSkor();
                }
            });
        }

        $("#topup_terakhir").change(function() {
            gettopupTerakhir();
        });

        $("#status_outlet,#type_outlet,#ljt,#cara_pembayaran,#kepemilikan,#lama_topup,#lama_langganan,#lama_berjualan,#jaminan,#histori_transaksi")
            .change(function() {
                loadSkor();
            });

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
            let omset_toko = $("#omset_toko").val() || "0";
            let jumlah = $("#jumlah").val() || "0";
            let ot = omset_toko.replace(/\./g, '');
            let jm = jumlah.replace(/\./g, '');

            let ratioomset = jm > 0 ? Math.round(parseInt(ot) / parseInt(jm)) : 0;
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

            if (omset_toko != "" && omset_toko !== '0') {
                if (ratioomset < 3) {
                    score_omset = 0.35;
                } else if (ratioomset >= 3) {
                    score_omset = 0.50;
                }
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

            let totalscore = parseFloat(score_outlet) + parseFloat(score_top) + parseFloat(score_carabayar) +
                parseFloat(score_kepemilikan) + parseFloat(score_lamausaha) + parseFloat(score_jaminan) +
                parseFloat(score_lamatopup) + parseFloat(score_lamalangganan) + parseFloat(score_ht) +
                parseFloat(score_omset) + parseFloat(score_faktur) + parseFloat(score_typeoutlet);
            let scoreakhir = totalscore.toFixed(2);

            let rekomendasi = "-";
            if (scoreakhir <= 2) rekomendasi = "Tidak Layak";
            else if (scoreakhir > 2 && scoreakhir <= 4) rekomendasi = "Tidak Disarankan";
            else if (scoreakhir > 4 && scoreakhir <= 6.75) rekomendasi = "Beresiko";
            else if (scoreakhir > 6.75 && scoreakhir <= 8.5) rekomendasi = "Layak Dengan Pertimbangan";
            else if (scoreakhir > 8.5 && scoreakhir <= 10) rekomendasi = "Layak";

            $("#total_score").text(scoreakhir);
            $("#skor").val(scoreakhir);
            $("#rekomendasi").text(rekomendasi);
        }

        function setFieldError($el, msg) {
            const $wrap = $el.closest('.relative');
            $el.addClass('border-rose-500 ring-1 ring-rose-500 focus:border-rose-500 focus:ring-rose-500/20');
            if ($el.hasClass('select2-hidden-accessible')) {
                $el.next('.select2-container').find('.select2-selection').addClass('!border-rose-500 !ring-1 !ring-rose-500');
            }
            $wrap.find('> div:first-child').removeClass('text-slate-400').addClass('text-rose-500');
            $wrap.parent().find('.field-error-msg').remove();
            $wrap.after(`<p class="field-error-msg text-[11px] font-semibold text-rose-600 dark:text-rose-400 mt-1 pl-1 flex items-center gap-1"><svg class="w-3 h-3 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg><span>${msg}</span></p>`);
        }

        function clearFieldError($el) {
            const $wrap = $el.closest('.relative');
            $el.removeClass('border-rose-500 ring-1 ring-rose-500 focus:border-rose-500 focus:ring-rose-500/20');
            if ($el.hasClass('select2-hidden-accessible')) {
                $el.next('.select2-container').find('.select2-selection').removeClass('!border-rose-500 !ring-1 !ring-rose-500');
            }
            $wrap.find('> div:first-child').removeClass('text-rose-500').addClass('text-slate-400');
            $wrap.parent().find('.field-error-msg').remove();
        }

        $form.find('input, select, textarea').on('input change select2:select', function() {
            clearFieldError($(this));
        });

        $form.on('submit', function(e) {
            let firstError = null;
            const requiredFields = [
                { id: '#nama_pelanggan', msg: 'Nama pelanggan wajib diisi.' },
                { id: '#alamat_pelanggan', msg: 'Alamat pelanggan wajib diisi.' },
                { id: '#kode_wilayah', msg: 'Wilayah / rute wajib dipilih.' },
                { id: '#jumlah', msg: 'Jumlah ajuan limit wajib diisi.' },
                { id: '#omset_toko', msg: 'Omset toko wajib diisi.' },
                { id: '#ljt', msg: 'LJT wajib dipilih.' },
                { id: '#cara_pembayaran', msg: 'Cara pembayaran wajib dipilih.' },
                { id: '#kepemilikan', msg: 'Kepemilikan wajib dipilih.' },
                { id: '#lama_berjualan', msg: 'Lama usaha wajib dipilih.' },
                { id: '#status_outlet', msg: 'Status outlet wajib dipilih.' },
                { id: '#type_outlet', msg: 'Type outlet wajib dipilih.' },
                { id: '#kode_klasifikasi', msg: 'Klasifikasi outlet wajib dipilih.' },
                { id: '#lama_langganan', msg: 'Lama langganan wajib dipilih.' },
                { id: '#jaminan', msg: 'Jaminan wajib dipilih.' },
                { id: '#histori_transaksi', msg: 'Histori pembayaran wajib dipilih.' },
                { id: '#topup_terakhir', msg: 'Tanggal terakhir top up wajib diisi.' },
                { id: '#uraian_analisa', msg: 'Uraian analisa wajib diisi.' }
            ];

            requiredFields.forEach(function(field) {
                const $el = $form.find(field.id);
                const val = ($el.val() || '').toString().trim();
                const isZeroAmount = field.id === '#jumlah' && (val === '' || val === '0');
                if (!val || isZeroAmount) {
                    setFieldError($el, field.msg);
                    if (!firstError) firstError = $el;
                }
            });

            if (firstError) {
                e.preventDefault();
                firstError.focus();
                return false;
            }

            const $btn = $("#btnSimpan");
            $btn.prop('disabled', true).addClass('opacity-75 cursor-not-allowed pointer-events-none');
            $btn.html(`<svg class="animate-spin h-3.5 w-3.5 text-white shrink-0 inline-block" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path></svg><span>Menyimpan...</span>`);
        });

        loadSkor();
    });
</script>
@endpush
