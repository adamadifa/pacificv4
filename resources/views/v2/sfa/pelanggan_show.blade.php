@extends('layouts.app')
@section('titlepage', 'Detail Pelanggan')

@section('navigasi')
    <div class="flex items-center gap-1.5 text-xs">
        <a href="{{ route('sfa.pelanggan') }}" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 transition">Pelanggan</a>
        <i data-lucide="chevron-right" class="w-3 h-3 text-slate-400"></i>
        <span class="text-brand-600 dark:text-brand-400 font-bold">{{ $pelanggan->kode_pelanggan }}</span>
    </div>
@endsection

@section('content')
<link rel="stylesheet" href="{{ asset('assets/vendor/libs/leaflet/leaflet.css') }}" />
<style>
    #map {
        min-height: 190px;
        height: 100%;
        z-index: 1;
    }
    @media (min-width: 640px) {
        #map {
            min-height: 240px;
        }
    }
</style>

@php
    $isCheckedInHere = !empty(Cookie::get('kodepelanggan')) && Crypt::decrypt(Cookie::get('kodepelanggan')) == $pelanggan->kode_pelanggan;
@endphp

<div class="space-y-3.5 sm:space-y-4">
    <!-- TOP ACTION BAR (MOBILE-FIRST) -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
        <div class="flex items-center justify-between gap-2">
            <a href="{{ route('sfa.pelanggan') }}"
                class="h-[36px] sm:h-[34px] px-3 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 hover:bg-slate-50 dark:hover:bg-slate-800 text-slate-700 dark:text-slate-200 text-xs font-bold inline-flex items-center gap-1.5 transition">
                <i data-lucide="arrow-left" class="w-3.5 h-3.5"></i>
                <span>Kembali</span>
            </a>

            @if ($isCheckedInHere)
                <a href="{{ route('sfa.checkout', Crypt::encrypt($pelanggan->kode_pelanggan)) }}"
                    class="sm:hidden h-[36px] px-3.5 rounded-lg bg-rose-600 hover:bg-rose-700 text-white text-xs font-bold inline-flex items-center gap-1.5 shadow-xs transition">
                    <i data-lucide="log-out" class="w-3.5 h-3.5"></i>
                    <span>Check Out</span>
                </a>
            @endif
        </div>

        <div class="grid grid-cols-3 sm:flex sm:flex-wrap items-center gap-2">
            <a href="{{ route('sfa.capture', Crypt::encrypt($pelanggan->kode_pelanggan)) }}"
                class="h-[36px] sm:h-[34px] px-2.5 sm:px-3 rounded-lg bg-[#284c9a] hover:bg-[#1e3a78] text-white text-[11px] sm:text-xs font-bold inline-flex items-center justify-center gap-1.5 shadow-xs transition">
                <i data-lucide="camera" class="w-3.5 h-3.5 shrink-0"></i>
                <span class="truncate">Capture Foto</span>
            </a>

            <a href="{{ route('sfa.createajuanlimit', Crypt::encrypt($pelanggan->kode_pelanggan)) }}"
                class="h-[36px] sm:h-[34px] px-2.5 sm:px-3 rounded-lg bg-[#284c9a] hover:bg-[#1e3a78] text-white text-[11px] sm:text-xs font-bold inline-flex items-center justify-center gap-1.5 shadow-xs transition">
                <i data-lucide="credit-card" class="w-3.5 h-3.5 shrink-0"></i>
                <span class="truncate">Ajuan Limit</span>
            </a>

            <a href="{{ route('sfa.createajuanfaktur', Crypt::encrypt($pelanggan->kode_pelanggan)) }}"
                class="h-[36px] sm:h-[34px] px-2.5 sm:px-3 rounded-lg bg-[#284c9a] hover:bg-[#1e3a78] text-white text-[11px] sm:text-xs font-bold inline-flex items-center justify-center gap-1.5 shadow-xs transition">
                <i data-lucide="file-plus" class="w-3.5 h-3.5 shrink-0"></i>
                <span class="truncate">Ajuan Faktur</span>
            </a>

            @if ($isCheckedInHere)
                <a href="{{ route('sfa.checkout', Crypt::encrypt($pelanggan->kode_pelanggan)) }}"
                    class="hidden sm:inline-flex h-[34px] px-3.5 rounded-lg bg-rose-600 hover:bg-rose-700 text-white text-xs font-bold items-center gap-1.5 shadow-xs transition">
                    <i data-lucide="log-out" class="w-3.5 h-3.5"></i>
                    <span>Check Out</span>
                </a>
            @endif
        </div>
    </div>

    @if ($pelanggan->status_aktif_pelanggan == 0)
        <div class="rounded-xl border border-rose-200 dark:border-rose-900 bg-rose-50 dark:bg-rose-950/40 p-4 flex items-start gap-3">
            <div class="w-9 h-9 rounded-lg bg-rose-100 dark:bg-rose-900/60 text-rose-600 dark:text-rose-400 flex items-center justify-center shrink-0">
                <i data-lucide="alert-triangle" class="w-5 h-5"></i>
            </div>
            <div>
                <h4 class="text-xs font-bold text-rose-800 dark:text-rose-200 uppercase tracking-wider">Peringatan</h4>
                <p class="text-xs text-rose-700 dark:text-rose-300 mt-0.5">
                    Pelanggan tidak aktif. Silakan hubungi admin untuk mengaktifkan pelanggan tersebut.
                </p>
            </div>
        </div>
    @else
        <!-- PROFILE & MAP CARD -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-3.5 sm:gap-4 items-stretch">
            <!-- LEFT: PROFILE PHOTO & SUMMARY -->
            <div class="lg:col-span-7 rounded-xl overflow-hidden border border-slate-200/80 dark:border-slate-800 bg-white dark:bg-slate-900 shadow-2xs flex flex-col">
                <div class="relative h-44 sm:h-auto sm:flex-1 sm:min-h-[220px] w-full bg-slate-200 dark:bg-slate-800 overflow-hidden">
                    @if (Storage::disk('public')->exists('/pelanggan/' . $pelanggan->foto) && !empty($pelanggan->foto))
                        <img src="{{ getfotoPelanggan($pelanggan->foto) }}" alt="{{ $pelanggan->nama_pelanggan }}" id="foto"
                            class="w-full h-full object-cover">
                    @else
                        <img src="{{ asset('assets/img/elements/1.jpg') }}" alt="{{ $pelanggan->nama_pelanggan }}" id="foto"
                            class="w-full h-full object-cover">
                    @endif
                    <div class="absolute inset-0 bg-gradient-to-t from-slate-950/85 via-slate-950/35 to-transparent"></div>
                    <div class="absolute bottom-3 left-3.5 right-3.5 sm:bottom-3.5 sm:left-4 sm:right-4 text-white">
                        <span class="inline-block px-2 py-0.5 rounded-md bg-white/20 backdrop-blur-xs text-[10.5px] font-bold font-mono-num">
                            {{ $pelanggan->kode_pelanggan }}
                        </span>
                        <h4 class="text-sm sm:text-base font-black leading-tight mt-1">{{ textUpperCase($pelanggan->nama_pelanggan) }}</h4>
                        <p class="text-[11px] sm:text-xs text-white/85 mt-0.5 line-clamp-2">{{ textCamelCase($pelanggan->alamat_pelanggan) }}</p>
                    </div>
                </div>

                <!-- METRIC STRIP -->
                <div class="grid grid-cols-3 divide-x divide-slate-200/80 dark:divide-slate-800 bg-slate-50/70 dark:bg-slate-900/60 border-t border-slate-200/80 dark:border-slate-800">
                    <div class="p-2.5 sm:p-3.5 min-w-0">
                        <span class="block text-[9.5px] sm:text-[10px] font-bold uppercase tracking-wider text-slate-400 truncate">Limit Pelanggan</span>
                        <span class="block text-xs sm:text-sm font-black text-slate-800 dark:text-slate-100 font-mono-num mt-0.5 truncate">{{ formatAngka($pelanggan->limit_pelanggan) }}</span>
                    </div>
                    <div class="p-2.5 sm:p-3.5 min-w-0">
                        <span class="block text-[9.5px] sm:text-[10px] font-bold uppercase tracking-wider text-slate-400 truncate">Faktur Max</span>
                        <span class="block text-xs sm:text-sm font-black text-slate-800 dark:text-slate-100 font-mono-num mt-0.5">{{ $ajuanfaktur != null ? $ajuanfaktur->jumlah_faktur : 0 }}</span>
                    </div>
                    <div class="p-2.5 sm:p-3.5 min-w-0">
                        <span class="block text-[9.5px] sm:text-[10px] font-bold uppercase tracking-wider text-slate-400 truncate">Belum Lunas</span>
                        <span class="block text-xs sm:text-sm font-black text-rose-600 dark:text-rose-400 font-mono-num mt-0.5">{{ $fakturkredit }}</span>
                    </div>
                </div>
            </div>

            <!-- RIGHT: MAP & CHECKIN / TRANSAKSI ACTIONS -->
            <div class="lg:col-span-5 rounded-xl border border-slate-200/80 dark:border-slate-800 bg-white dark:bg-slate-900 shadow-2xs overflow-hidden flex flex-col">
                <div class="px-3.5 py-2.5 border-b border-slate-200/80 dark:border-slate-800 flex items-center justify-between gap-2">
                    <div class="flex items-center gap-2">
                        <div class="w-6 h-6 rounded-md bg-blue-50 dark:bg-blue-950/60 text-[#284c9a] dark:text-blue-400 flex items-center justify-center shrink-0">
                            <i data-lucide="map-pin" class="w-3.5 h-3.5"></i>
                        </div>
                        <span class="text-xs font-bold text-slate-800 dark:text-slate-100">Lokasi & Kunjungan</span>
                    </div>
                    <span class="text-[10.5px] text-slate-400 font-medium">GPS Outlet</span>
                </div>

                <div class="p-3 flex-1 flex flex-col gap-3">
                    <div class="flex-1 min-h-[190px] sm:min-h-[240px] rounded-lg overflow-hidden border border-slate-200/80 dark:border-slate-800">
                        <div id="map" class="w-full h-full"></div>
                    </div>

                    @if ($checkin == 0 || !$isCheckedInHere)
                        <div id="checkinsection">
                            <button type="button" id="checkin"
                                class="w-full h-[42px] sm:h-[38px] rounded-lg bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold shadow-xs transition flex items-center justify-center gap-1.5 cursor-pointer">
                                <i data-lucide="fingerprint" class="w-4 h-4"></i>
                                <span>Check In Pelanggan</span>
                            </button>
                        </div>
                    @else
                        <div class="grid grid-cols-2 gap-2">
                            <a href="/sfa/penjualan/create"
                                class="h-[42px] sm:h-[38px] rounded-lg bg-[#284c9a] hover:bg-[#1e3a78] text-white text-xs font-bold shadow-xs transition flex items-center justify-center gap-1.5">
                                <i data-lucide="shopping-bag" class="w-4 h-4"></i>
                                <span>Penjualan</span>
                            </a>
                            <a href="/sfa/retur/create"
                                class="h-[42px] sm:h-[38px] rounded-lg bg-rose-600 hover:bg-rose-700 text-white text-xs font-bold shadow-xs transition flex items-center justify-center gap-1.5">
                                <i data-lucide="rotate-ccw" class="w-4 h-4"></i>
                                <span>Retur</span>
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    @endif

    <!-- HISTORI PENJUALAN & RETUR TABS -->
    <div class="rounded-xl bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 shadow-2xs overflow-hidden">
        <div class="px-3.5 py-2.5 border-b border-slate-200/80 dark:border-slate-800 flex flex-col sm:flex-row sm:items-center justify-between gap-2.5">
            <div class="flex items-center gap-2">
                <div class="w-6 h-6 rounded-md bg-blue-50 dark:bg-blue-950/60 text-[#284c9a] dark:text-blue-400 flex items-center justify-center shrink-0">
                    <i data-lucide="history" class="w-3.5 h-3.5"></i>
                </div>
                <h4 class="text-xs font-bold text-slate-800 dark:text-slate-100">Riwayat Transaksi Outlet</h4>
            </div>

            <div class="w-full sm:w-auto grid grid-cols-2 sm:inline-flex items-center gap-1 bg-slate-100 dark:bg-slate-800 p-1 rounded-lg">
                <button type="button" data-target="#pane-penjualan"
                    class="sfa-show-tab-btn active h-[32px] sm:h-[28px] px-3 rounded-md text-xs font-bold bg-white dark:bg-slate-900 text-[#284c9a] dark:text-blue-400 shadow-xs transition flex items-center justify-center gap-1.5 cursor-pointer">
                    <i data-lucide="shopping-bag" class="w-3.5 h-3.5"></i>
                    <span>Penjualan</span>
                </button>
                <button type="button" data-target="#pane-retur"
                    class="sfa-show-tab-btn h-[32px] sm:h-[28px] px-3 rounded-md text-xs font-bold text-slate-500 dark:text-slate-400 hover:text-slate-700 dark:hover:text-slate-200 transition flex items-center justify-center gap-1.5 cursor-pointer">
                    <i data-lucide="rotate-ccw" class="w-3.5 h-3.5"></i>
                    <span>Retur</span>
                </button>
            </div>
        </div>

        <div class="p-4">
            <div id="pane-penjualan" class="sfa-show-tab-pane">
                @if ($isCheckedInHere)
                    <livewire:penjualanpelanggan :kode_pelanggan="$pelanggan->kode_pelanggan" />
                @else
                    <div class="py-6 text-center text-xs text-slate-400">
                        Lakukan <strong>Check In</strong> terlebih dahulu untuk melihat riwayat penjualan pelanggan.
                    </div>
                @endif
            </div>

            <div id="pane-retur" class="sfa-show-tab-pane hidden">
                @if ($isCheckedInHere)
                    <livewire:returpelanggan :kode_pelanggan="$pelanggan->kode_pelanggan" />
                @else
                    <div class="py-6 text-center text-xs text-slate-400">
                        Lakukan <strong>Check In</strong> terlebih dahulu untuk melihat riwayat retur pelanggan.
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@push('myscript')
<script src="{{ asset('assets/vendor/libs/leaflet/leaflet.js') }}"></script>
<script>
    $(function() {
        if (typeof lucide !== 'undefined') lucide.createIcons();

        // Tab Switching
        $('.sfa-show-tab-btn').on('click', function() {
            const $btn = $(this);
            const target = $btn.data('target');

            $('.sfa-show-tab-btn')
                .removeClass('active bg-white dark:bg-slate-900 text-[#284c9a] dark:text-blue-400 shadow-xs')
                .addClass('text-slate-500 dark:text-slate-400 hover:text-slate-700 dark:hover:text-slate-200');
            $btn
                .addClass('active bg-white dark:bg-slate-900 text-[#284c9a] dark:text-blue-400 shadow-xs')
                .removeClass('text-slate-500 dark:text-slate-400 hover:text-slate-700 dark:hover:text-slate-200');

            $('.sfa-show-tab-pane').addClass('hidden');
            $(target).removeClass('hidden');
            if (typeof lucide !== 'undefined') lucide.createIcons();
        });

        const status_lokasi = '{{ $pelanggan->status_lokasi }}';
        const lokasi_pelanggan = '{{ $pelanggan->latitude }},{{ $pelanggan->longitude }}';
        let currentLocation = '';

        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(successCallback, errorCallback);
        } else {
            Swal.fire({
                title: 'Oops!',
                text: 'Maaf, browser Anda tidak mendukung geolokasi HTML5.',
                icon: 'error',
                timer: 3000
            });
        }

        function successCallback(position) {
            currentLocation = position.coords.latitude + ',' + position.coords.longitude;
            const currentLatitude = position.coords.latitude;
            const currentLongitude = position.coords.longitude;

            const koordinat_pelanggan = lokasi_pelanggan.split(',');
            let latitudePelanggan = currentLatitude;
            let longitudePelanggan = currentLongitude;
            if (status_lokasi == 1 && koordinat_pelanggan[0] && koordinat_pelanggan[1]) {
                latitudePelanggan = koordinat_pelanggan[0];
                longitudePelanggan = koordinat_pelanggan[1];
            }

            const map = L.map('map').setView([currentLatitude, currentLongitude], 18);
            L.tileLayer('https://{s}.google.com/vt?lyrs=m&x={x}&y={y}&z={z}', {
                maxZoom: 20,
                subdomains: ['mt0', 'mt1', 'mt2', 'mt3']
            }).addTo(map);
            L.marker([currentLatitude, currentLongitude]).addTo(map);
            L.circle([latitudePelanggan, longitudePelanggan], {
                color: 'red',
                fillColor: '#f03',
                fillOpacity: 0.5,
                radius: 30
            }).addTo(map);
            setTimeout(() => map.invalidateSize(), 200);
        }

        function errorCallback(error) {
            let msg = 'Waktu percobaan habis sebelum bisa mendapatkan data lokasi.';
            if (error.code == 1) {
                msg = 'Anda tidak membagikan akses lokasi browser Anda.';
            } else if (error.code == 2) {
                msg = 'Jaringan tidak aktif atau layanan penentuan posisi tidak dapat dijangkau.';
            }
            Swal.fire({
                title: 'Oops!',
                text: msg,
                icon: 'error',
                timer: 3000
            });
        }

        $('#checkin').on('click', function() {
            const kodepel = "{{ $pelanggan->kode_pelanggan }}";
            $('#checkinsection').hide();
            $.ajax({
                type: 'POST',
                url: '/sfa/checkinstore',
                data: {
                    _token: "{{ csrf_token() }}",
                    kode_pelanggan: kodepel,
                    lokasi: currentLocation
                },
                cache: false,
                success: function(respond) {
                    if (respond.success == true) {
                        Swal.fire({
                            title: 'Berhasil',
                            text: respond.message,
                            icon: 'success',
                            showConfirmButton: true,
                            didClose: () => {
                                $('#checkinsection').hide();
                                location.reload();
                            }
                        });
                    } else {
                        Swal.fire({
                            title: 'Gagal',
                            text: respond.message,
                            icon: 'error',
                            showConfirmButton: true,
                            didClose: () => {
                                location.reload();
                            }
                        });
                    }
                }
            });
        });
    });
</script>
@endpush
