@extends('layouts.app')
@section('titlepage', 'Capture Pelanggan')

@section('navigasi')
    <div class="flex items-center gap-1.5 text-xs">
        <a href="{{ route('sfa.pelanggan') }}" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 transition">Pelanggan</a>
        <i data-lucide="chevron-right" class="w-3 h-3 text-slate-400"></i>
        <a href="{{ route('sfa.showpelanggan', Crypt::encrypt($pelanggan->kode_pelanggan)) }}" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 transition">{{ $pelanggan->kode_pelanggan }}</a>
        <i data-lucide="chevron-right" class="w-3 h-3 text-slate-400"></i>
        <span class="text-brand-600 dark:text-brand-400 font-bold">Capture</span>
    </div>
@endsection

@section('content')
<link rel="stylesheet" href="{{ asset('assets/vendor/libs/leaflet/leaflet.css') }}" />
<style>
    .webcam-capture,
    .webcam-capture video {
        display: block;
        width: 100% !important;
        height: auto !important;
        border-radius: 0.75rem;
        overflow: hidden;
    }

    #map {
        height: 220px;
        z-index: 1;
    }
</style>

<div class="space-y-4 max-w-3xl">
    <div class="flex flex-wrap items-center justify-between gap-2">
        <div>
            <h3 class="text-base font-black text-slate-900 dark:text-white leading-tight">Capture Foto & Lokasi Outlet</h3>
            <p class="text-[11px] text-slate-400 mt-0.5">{{ $pelanggan->kode_pelanggan }} &bull; {{ textUpperCase($pelanggan->nama_pelanggan) }}</p>
        </div>
        <a href="{{ route('sfa.showpelanggan', Crypt::encrypt($pelanggan->kode_pelanggan)) }}"
            class="h-[34px] px-3 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 hover:bg-slate-50 dark:hover:bg-slate-800 text-slate-700 dark:text-slate-200 text-xs font-bold inline-flex items-center gap-1.5 transition">
            <i data-lucide="arrow-left" class="w-3.5 h-3.5"></i>
            <span>Kembali</span>
        </a>
    </div>

    <div class="rounded-xl bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 shadow-2xs overflow-hidden">
        <div class="px-3.5 py-2.5 border-b border-slate-200/80 dark:border-slate-800 flex items-center gap-2">
            <div class="w-6 h-6 rounded-md bg-blue-50 dark:bg-blue-950/60 text-[#284c9a] dark:text-blue-400 flex items-center justify-center shrink-0">
                <i data-lucide="camera" class="w-3.5 h-3.5"></i>
            </div>
            <span class="text-xs font-bold text-slate-800 dark:text-slate-100">Kamera & Peta Lokasi</span>
        </div>

        <div class="p-4 space-y-3">
            <div class="webcam-capture rounded-xl border border-slate-200 dark:border-slate-800 bg-slate-950 overflow-hidden"></div>
            <div id="map" class="w-full rounded-xl border border-slate-200 dark:border-slate-800 overflow-hidden"></div>

            <div class="pt-2 flex items-center justify-end gap-2">
                <button type="button" id="takeabsen"
                    class="w-full sm:w-auto h-[38px] px-5 rounded-lg bg-[#284c9a] hover:bg-[#1e3a78] text-white text-xs font-bold shadow-xs transition inline-flex items-center justify-center gap-1.5 cursor-pointer">
                    <i data-lucide="camera" class="w-4 h-4"></i>
                    <span>Capture & Simpan</span>
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('myscript')
<script src="{{ asset('assets/vendor/libs/leaflet/leaflet.js') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/webcamjs/1.0.25/webcam.min.js"></script>
<script>
    $(function() {
        if (typeof lucide !== 'undefined') lucide.createIcons();

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
            const koordinat_pelanggan = currentLocation.split(',');
            const map = L.map('map').setView([koordinat_pelanggan[0], koordinat_pelanggan[1]], 15);
            L.tileLayer('http://{s}.google.com/vt?lyrs=m&x={x}&y={y}&z={z}', {
                maxZoom: 20,
                subdomains: ['mt0', 'mt1', 'mt2', 'mt3']
            }).addTo(map);
            L.marker([koordinat_pelanggan[0], koordinat_pelanggan[1]]).addTo(map);
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

        Webcam.set({
            width: 590,
            height: 460,
            image_format: 'jpeg',
            jpeg_quality: 80
        });

        const cameras = [];
        if (navigator.mediaDevices && navigator.mediaDevices.enumerateDevices) {
            navigator.mediaDevices.enumerateDevices().then(function(devices) {
                devices.forEach(function(device) {
                    if (device.kind === 'videoinput') {
                        cameras.push(device.deviceId);
                    }
                });
            });
        }

        Webcam.set('constraints', {
            width: 590,
            height: 460,
            image_format: 'jpeg',
            jpeg_quality: 80,
            sourceId: cameras[0],
            facingMode: {
                exact: 'environment'
            }
        });

        Webcam.attach('.webcam-capture');

        $('#takeabsen').on('click', function() {
            let image = '';
            Webcam.snap(function(data_uri) {
                image = data_uri;
            });

            const kode_pelanggan = "{{ Crypt::encrypt($pelanggan->kode_pelanggan) }}";
            const $btn = $(this);
            const origHtml = $btn.html();
            $btn.prop('disabled', true).addClass('opacity-75 pointer-events-none').html('<span>Menyimpan...</span>');

            $.ajax({
                type: 'POST',
                url: '/sfa/storepelanggancapture',
                data: {
                    _token: "{{ csrf_token() }}",
                    kode_pelanggan: "{{ $pelanggan->kode_pelanggan }}",
                    image: image,
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
                                $('#takeabsen').hide();
                                window.location.href = `/sfa/pelanggan/${kode_pelanggan}/show`;
                            }
                        });
                    } else {
                        $btn.prop('disabled', false).removeClass('opacity-75 pointer-events-none').html(origHtml);
                        Swal.fire({
                            title: 'Gagal',
                            text: respond.message,
                            icon: 'error',
                            showConfirmButton: true
                        });
                    }
                },
                error: function() {
                    $btn.prop('disabled', false).removeClass('opacity-75 pointer-events-none').html(origHtml);
                }
            });
        });
    });
</script>
@endpush
