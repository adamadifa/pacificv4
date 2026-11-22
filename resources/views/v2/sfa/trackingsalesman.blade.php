@extends('layouts.app')
@section('titlepage', 'Tracking Salesman')

@section('navigasi')
    <div class="flex items-center gap-1.5 text-xs">
        <span class="text-slate-400">Home</span>
        <i data-lucide="chevron-right" class="w-3 h-3 text-slate-400"></i>
        <span class="text-slate-400">SFA</span>
        <i data-lucide="chevron-right" class="w-3 h-3 text-slate-400"></i>
        <span class="text-brand-600 dark:text-brand-400 font-bold">Tracking Salesman</span>
    </div>
@endsection

@section('style')
<link rel="stylesheet" href="{{ asset('assets/vendor/libs/leaflet/leaflet.css') }}" />
<style>
    #map {
        height: 640px;
        width: 100%;
        z-index: 10;
    }
    .leaflet-popup-content-wrapper {
        border-radius: 12px !important;
        padding: 0 !important;
        overflow: hidden !important;
        box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.25) !important;
        border: 1px solid rgba(226, 232, 240, 0.9) !important;
    }
    .leaflet-popup-content {
        margin: 0 !important;
        line-height: inherit !important;
    }
    .leaflet-container {
        font-family: inherit !important;
    }
    .timeline-item.active-stop {
        border-color: #284c9a !important;
        background-color: rgba(40, 76, 154, 0.06) !important;
    }
</style>
@endsection

@section('content')
<div class="space-y-4">

    <!-- FILTER BAR STANDAR V2 (NO-LABEL, UNIFORM h-[34px], PREFIX LUCIDE ICON) -->
    <div class="rounded-xl bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 p-3.5 sm:p-4 shadow-2xs">
        <form id="formFilterTracking" class="flex flex-wrap items-center gap-2.5">
            
            <!-- Tanggal Kunjungan (No-Label with Lucide Prefix & Flatpickr) -->
            <div class="relative w-full sm:w-44">
                <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                    <i data-lucide="calendar" class="w-3.5 h-3.5"></i>
                </div>
                <input type="text" name="tanggal" id="tanggal" value="{{ date('Y-m-d') }}" placeholder="Pilih Tanggal" readonly
                    class="flatpickr-date w-full h-[34px] pl-8 pr-2.5 text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-800 dark:text-slate-200 focus:outline-hidden focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition-all cursor-pointer">
            </div>

            <!-- Pilih Cabang (Role Check) -->
            @hasanyrole($roles_show_cabang)
                <div class="relative w-full sm:w-52">
                    <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                        <i data-lucide="building-2" class="w-3.5 h-3.5"></i>
                    </div>
                    <select name="kode_cabang" id="kode_cabang"
                        class="w-full h-[34px] pl-8 pr-7 text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-800 dark:text-slate-200 focus:outline-hidden focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition-all appearance-none cursor-pointer">
                        <option value="">Semua Cabang</option>
                        @foreach ($cabang as $d)
                            <option value="{{ $d->kode_cabang }}">{{ textUpperCase($d->nama_cabang) }}</option>
                        @endforeach
                    </select>
                    <div class="absolute inset-y-0 right-0 pr-2 flex items-center pointer-events-none text-slate-400">
                        <i data-lucide="chevron-down" class="w-3 h-3"></i>
                    </div>
                </div>
            @endhasanyrole

            <!-- Pilih Salesman (Populated via AJAX) -->
            <div class="relative w-full sm:w-60">
                <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                    <i data-lucide="user-check" class="w-3.5 h-3.5"></i>
                </div>
                <select name="kode_salesman" id="kode_salesman"
                    class="w-full h-[34px] pl-8 pr-7 text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-800 dark:text-slate-200 focus:outline-hidden focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition-all appearance-none cursor-pointer">
                    <option value="">Semua Salesman</option>
                </select>
                <div class="absolute inset-y-0 right-0 pr-2 flex items-center pointer-events-none text-slate-400">
                    <i data-lucide="chevron-down" class="w-3 h-3"></i>
                </div>
            </div>

            <!-- Submit Button (Uniform h-[34px], rounded-lg) -->
            <button type="button" id="btnTrackingSalesman"
                class="h-[34px] px-4 rounded-lg bg-brand-600 hover:bg-brand-700 text-white text-xs font-bold inline-flex items-center gap-1.5 shadow-2xs hover:shadow-xs transition-all active:scale-95 shrink-0">
                <i data-lucide="search" class="w-3.5 h-3.5"></i>
                <span>Lacak Rute</span>
            </button>

            <!-- Reset Button -->
            <button type="button" id="btnResetFilter"
                class="h-[34px] px-3 rounded-lg border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-750 text-xs font-bold inline-flex items-center gap-1.5 transition-all shrink-0"
                title="Muat ulang posisi hari ini">
                <i data-lucide="rotate-ccw" class="w-3.5 h-3.5"></i>
                <span>Reset</span>
            </button>

        </form>
    </div>

    <!-- SPLIT VIEW: LEAFLET MAP & INTERACTIVE TIMELINE LIST -->
    <div class="grid grid-cols-12 gap-4">
        
        <!-- MAP CONTAINER (LEFT: 8 or 9 cols) -->
        <div class="col-span-12 lg:col-span-8 xl:col-span-9 flex flex-col">
            <div class="rounded-xl bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 p-3 sm:p-4 shadow-2xs flex-1 flex flex-col">
                
                <!-- Map Header Toolbar -->
                <div class="flex items-center justify-between gap-3 pb-3 mb-3 border-b border-slate-100 dark:border-slate-800 flex-wrap">
                    <div class="flex items-center gap-2.5">
                        <div class="w-8 h-8 rounded-lg bg-brand-50 dark:bg-brand-950/60 text-brand-600 dark:text-brand-400 flex items-center justify-center border border-brand-200/60 dark:border-brand-800/60 shrink-0">
                            <i data-lucide="map" class="w-4 h-4"></i>
                        </div>
                        <div>
                            <h3 class="text-xs sm:text-sm font-bold text-slate-900 dark:text-white leading-tight">
                                Peta Jalur & Lokasi Check-In
                            </h3>
                            <p class="text-[11px] text-slate-400 leading-none mt-0.5" id="mapStatusText">
                                Menampilkan pergerakan kunjungan salesman
                            </p>
                        </div>
                    </div>

                    <div class="flex items-center gap-2">
                        <!-- Fit Bounds Button -->
                        <button type="button" id="btnFitBounds"
                            class="h-[30px] px-2.5 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-700 dark:text-slate-200 text-xs font-bold inline-flex items-center gap-1 hover:bg-slate-50 transition-colors shadow-2xs"
                            title="Pusatkan peta ke seluruh titik">
                            <i data-lucide="maximize" class="w-3.5 h-3.5 text-slate-400"></i>
                            <span>Pusatkan Semua</span>
                        </button>
                    </div>
                </div>

                <!-- Leaflet Map Container -->
                <div class="relative w-full flex-1 rounded-lg overflow-hidden border border-slate-200/80 dark:border-slate-800 shadow-inner">
                    <div id="map"></div>
                    
                    <!-- Loading Overlay on Map -->
                    <div id="mapLoadingOverlay" class="absolute inset-0 bg-white/70 dark:bg-slate-900/70 backdrop-blur-2xs z-30 flex flex-col items-center justify-center transition-opacity duration-200 hidden">
                        <div class="w-9 h-9 border-3 border-brand-600 border-t-transparent rounded-full animate-spin mb-2"></div>
                        <span class="text-xs font-bold text-slate-800 dark:text-slate-100">Memetakan data rute kunjungan...</span>
                    </div>
                </div>

            </div>
        </div>

        <!-- TIMELINE SIDEBAR (RIGHT: 4 or 3 cols) -->
        <div class="col-span-12 lg:col-span-4 xl:col-span-3 flex flex-col">
            <div class="rounded-xl bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 p-3 sm:p-4 shadow-2xs flex-1 flex flex-col max-h-[720px]">
                
                <!-- Sidebar Header -->
                <div class="flex items-center justify-between gap-2 pb-3 mb-3 border-b border-slate-100 dark:border-slate-800">
                    <div class="flex items-center gap-2">
                        <div class="w-7 h-7 rounded-lg bg-emerald-50 dark:bg-emerald-950/60 text-emerald-600 dark:text-emerald-400 flex items-center justify-center border border-emerald-100 dark:border-emerald-800 shrink-0">
                            <i data-lucide="list-ordered" class="w-3.5 h-3.5"></i>
                        </div>
                        <h4 class="text-xs font-bold text-slate-900 dark:text-white leading-tight">
                            Urutan Kunjungan
                        </h4>
                    </div>
                    <span id="badgeStopCount" class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-black bg-brand-50 dark:bg-brand-950/60 text-brand-700 dark:text-brand-300 border border-brand-200/60 font-mono-num">
                        0 Stop
                    </span>
                </div>

                <!-- Timeline List Container -->
                <div id="timelineContainer" class="flex-1 overflow-y-auto space-y-2.5 pr-1 scrollbar-thin">
                    <div class="py-12 text-center text-slate-400">
                        <i data-lucide="navigation" class="w-7 h-7 mx-auto text-slate-300 dark:text-slate-600 mb-1.5 animate-pulse"></i>
                        <span class="text-xs font-medium">Memuat data rute kunjungan...</span>
                    </div>
                </div>

            </div>
        </div>

    </div>

</div>
@endsection

@push('myscript')
<script src="{{ asset('assets/vendor/libs/leaflet/leaflet.js') }}"></script>
<script>
    $(function() {
        // Inisialisasi koordinat awal cabang
        const initLat = parseFloat("{{ $lokasi_cabang[0] ?? '-7.389' }}");
        const initLng = parseFloat("{{ $lokasi_cabang[1] ?? '109.355' }}");

        // Inisialisasi Peta Leaflet
        const map = L.map('map', {
            zoomControl: true,
            scrollWheelZoom: true
        }).setView([initLat, initLng], 13);

        // Tile layer OpenStreetMap
        L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>'
        }).addTo(map);

        // Marker Kantor / Cabang
        const branchMarker = L.marker([initLat, initLng], {
            title: 'Kantor Cabang'
        }).addTo(map);
        branchMarker.bindPopup(`
            <div class="p-3 text-xs">
                <div class="font-bold text-slate-800 text-sm">Kantor / Gudang Cabang</div>
                <div class="text-[11px] text-slate-500 mt-1">Titik Koordinat Pangkal: ${initLat}, ${initLng}</div>
            </div>
        `);

        // Layer Group untuk marker checkin & polyline rute
        let checkinLayerGroup = L.layerGroup().addTo(map);
        let routePolyline = null;
        let markersStore = [];

        // Haversine formula distance calculation in Km
        function getDistanceKm(lat1, lon1, lat2, lon2) {
            const R = 6371;
            const dLat = (lat2 - lat1) * Math.PI / 180;
            const dLon = (lon2 - lon1) * Math.PI / 180;
            const a = Math.sin(dLat / 2) * Math.sin(dLat / 2) +
                      Math.cos(lat1 * Math.PI / 180) * Math.cos(lat2 * Math.PI / 180) *
                      Math.sin(dLon / 2) * Math.sin(dLon / 2);
            const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
            return R * c;
        }

        // AJAX memuat daftar salesman berdasarkan cabang
        function loadSalesman() {
            const kode_cabang = $("#kode_cabang").val();
            $.ajax({
                type: 'POST',
                url: '/salesman/getsalesmanbycabang',
                data: {
                    _token: "{{ csrf_token() }}",
                    kode_cabang: kode_cabang
                },
                cache: false,
                success: function(respond) {
                    $("#kode_salesman").html(respond);
                    if (typeof lucide !== 'undefined') lucide.createIcons();
                }
            });
        }

        loadSalesman();
        $("#kode_cabang").change(function() {
            loadSalesman();
        });

        // Fungsi utama memuat titik tracking
        function loadTrackingPoints(tanggal, kode_cabang, kode_salesman) {
            $("#mapLoadingOverlay").removeClass('hidden');

            $.getJSON('/sfa/getlocationcheckin', {
                tanggal: tanggal,
                kode_cabang: kode_cabang,
                kode_salesman: kode_salesman
            }, function(data) {
                // Bersihkan marker & garis rute sebelumnya
                checkinLayerGroup.clearLayers();
                if (routePolyline) {
                    map.removeLayer(routePolyline);
                    routePolyline = null;
                }
                markersStore = [];

                const pointsCount = data.length;
                $("#badgeStopCount").text(pointsCount + ' Stop');

                if (!pointsCount) {
                    $("#mapLoadingOverlay").addClass('hidden');
                    $("#mapStatusText").text('Tidak ditemukan titik check-in pada filter ini');
                    $("#timelineContainer").html(`
                        <div class="py-12 text-center text-slate-400">
                            <i data-lucide="map-pin-off" class="w-8 h-8 mx-auto text-slate-300 dark:text-slate-600 mb-2"></i>
                            <div class="text-xs font-semibold">Tidak ada riwayat check-in</div>
                            <div class="text-[11px] text-slate-400 mt-0.5">Pilih tanggal atau salesman lain untuk melacak</div>
                        </div>
                    `);
                    if (typeof lucide !== 'undefined') lucide.createIcons();
                    return;
                }

                // Kalkulasi statistik data
                const uniqueOutlets = new Set();
                let firstTime = null;
                let lastTime = null;
                let totalDistanceKm = 0;
                let latLngList = [];
                let timelineHtml = '';

                let prevLat = initLat;
                let prevLng = initLng;

                $.each(data, function(index, item) {
                    const lat = parseFloat(item.latitude);
                    const lng = parseFloat(item.longitude);
                    latLngList.push([lat, lng]);

                    uniqueOutlets.add(item.kode_pelanggan);

                    // Track timestamps
                    const checkinTime = item.checkin_time || (item.created_at ? item.created_at.substring(11, 16) : '-');
                    if (index === 0) firstTime = checkinTime;
                    lastTime = checkinTime;

                    // Calculate distance from previous point
                    const stepDist = getDistanceKm(prevLat, prevLng, lat, lng);
                    totalDistanceKm += stepDist;
                    prevLat = lat;
                    prevLng = lng;

                    // Buat custom numbered marker dengan Navy Pacific Style
                    const checkinIcon = L.divIcon({
                        className: 'custom-checkin-marker',
                        html: `
                            <div class="relative group cursor-pointer transition-transform duration-150 hover:scale-115">
                                <div class="w-8 h-8 rounded-full bg-[#284c9a] border-2 border-white shadow-md flex items-center justify-center text-white font-black font-mono-num text-xs">
                                    ${index + 1}
                                </div>
                                <div class="w-2.5 h-2.5 bg-[#284c9a] rotate-45 mx-auto -mt-1.5 border-r border-b border-white"></div>
                            </div>
                        `,
                        iconSize: [32, 38],
                        iconAnchor: [16, 36],
                        popupAnchor: [0, -36]
                    });

                    // Foto pelanggan
                    const fotoUrl = item.foto 
                        ? ("{{ Storage::url('pelanggan/') }}" + item.foto) 
                        : "{{ asset('assets/img/avatars/No_Image_Available.jpg') }}";

                    // Popup konten HTML modern
                    const popupHtml = `
                        <div class="w-64 p-3 font-sans text-xs bg-white">
                            <div class="flex items-center gap-2 mb-2 pb-2 border-b border-slate-100">
                                <div class="w-6 h-6 rounded-full bg-[#284c9a] text-white flex items-center justify-center font-black font-mono-num text-[11px] shrink-0">
                                    ${index + 1}
                                </div>
                                <div class="min-w-0">
                                    <div class="font-black text-slate-800 truncate leading-tight">${item.nama_pelanggan || '-'}</div>
                                    <div class="text-[10px] text-slate-400 font-mono-num">${item.kode_pelanggan || '-'}</div>
                                </div>
                            </div>
                            <div class="relative w-full h-28 rounded-lg overflow-hidden bg-slate-100 mb-2 border border-slate-200">
                                <img src="${fotoUrl}" alt="Outlet" class="w-full h-full object-cover" onerror="this.src='{{ asset('assets/img/avatars/No_Image_Available.jpg') }}'">
                            </div>
                            <div class="space-y-1 text-[11px] text-slate-600">
                                <div class="flex items-center gap-1 text-emerald-600 font-bold">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    <span>Pukul ${checkinTime} WIB</span>
                                </div>
                                <div class="text-slate-500 line-clamp-2 leading-tight">
                                    ${item.alamat_pelanggan || 'Alamat tidak tertera'}
                                </div>
                                <div class="text-[10px] text-slate-400 font-mono-num pt-1 border-t border-slate-100">
                                    GPS: ${lat.toFixed(5)}, ${lng.toFixed(5)}
                                </div>
                            </div>
                        </div>
                    `;

                    const marker = L.marker([lat, lng], { icon: checkinIcon }).bindPopup(popupHtml);
                    checkinLayerGroup.addLayer(marker);
                    markersStore.push(marker);

                    // Buat timeline item card di sidebar
                    timelineHtml += `
                        <div class="timeline-item p-3 rounded-lg border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-850 hover:border-brand-500 hover:shadow-xs transition-all cursor-pointer group"
                             data-index="${index}">
                            <div class="flex items-start gap-2.5">
                                <div class="w-6 h-6 rounded-full bg-[#284c9a] text-white flex items-center justify-center font-black font-mono-num text-[11px] shrink-0 mt-0.5 group-hover:scale-105 transition-transform">
                                    ${index + 1}
                                </div>
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center justify-between gap-1">
                                        <div class="text-xs font-bold text-slate-800 dark:text-slate-100 truncate group-hover:text-brand-600">
                                            ${item.nama_pelanggan || '-'}
                                        </div>
                                        <span class="inline-flex items-center gap-0.5 px-1.5 py-0.5 rounded text-[10px] font-bold bg-emerald-50 dark:bg-emerald-950/60 text-emerald-700 dark:text-emerald-400 font-mono-num shrink-0">
                                            ${checkinTime}
                                        </span>
                                    </div>
                                    <div class="text-[10px] text-slate-400 font-mono-num mt-0.5">${item.kode_pelanggan || '-'}</div>
                                    <div class="text-[11px] text-slate-500 dark:text-slate-400 truncate mt-1 leading-snug">
                                        ${item.alamat_pelanggan || 'Alamat tidak tertera'}
                                    </div>
                                    <div class="flex items-center justify-between mt-2 pt-1.5 border-t border-slate-100 dark:border-slate-800 text-[10px] text-slate-400">
                                        <span>+${stepDist.toFixed(1)} Km</span>
                                        <span class="text-brand-600 font-bold group-hover:underline flex items-center gap-0.5">
                                            Buka Peta <i data-lucide="chevron-right" class="w-3 h-3"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    `;
                });

                // Tambahkan Garis Polylines menghubungkan titik-titik kunjungan
                if (latLngList.length > 1) {
                    routePolyline = L.polyline(latLngList, {
                        color: '#284c9a',
                        weight: 4,
                        opacity: 0.75,
                        dashArray: '6, 8',
                        lineJoin: 'round'
                    }).addTo(map);
                }

                $("#mapStatusText").text(`${pointsCount} titik kunjungan terpetakan &bull; Total estimasi jelajah ${totalDistanceKm.toFixed(1)} Km`);

                // Pasang HTML timeline
                $("#timelineContainer").html(timelineHtml);

                // Auto zoom fit bounds
                if (latLngList.length > 0) {
                    const bounds = L.latLngBounds(latLngList);
                    bounds.extend([initLat, initLng]);
                    map.fitBounds(bounds, { padding: [40, 40] });
                }

                $("#mapLoadingOverlay").addClass('hidden');
                if (typeof lucide !== 'undefined') lucide.createIcons();
            }).fail(function() {
                $("#mapLoadingOverlay").addClass('hidden');
                $("#mapStatusText").text('Gagal mengambil data dari server');
            });
        }

        // Klik tombol Lacak
        $("#btnTrackingSalesman").click(function(e) {
            e.preventDefault();
            const tanggal = $("#tanggal").val();
            const kode_cabang = $("#kode_cabang").val() || '';
            const kode_salesman = $("#kode_salesman").val() || '';
            loadTrackingPoints(tanggal, kode_cabang, kode_salesman);
        });

        // Klik tombol Reset
        $("#btnResetFilter").click(function(e) {
            e.preventDefault();
            const defaultDate = "{{ date('Y-m-d') }}";
            if ($("#tanggal")[0] && $("#tanggal")[0]._flatpickr) {
                $("#tanggal")[0]._flatpickr.setDate(defaultDate);
            } else {
                $("#tanggal").val(defaultDate);
            }
            $("#kode_cabang").val('');
            $("#kode_salesman").val('');
            loadTrackingPoints(defaultDate, '', '');
        });

        // Klik item di timeline untuk pusatkan peta dan buka popup marker
        $(document).on('click', '.timeline-item', function() {
            $(".timeline-item").removeClass('active-stop');
            $(this).addClass('active-stop');

            const idx = parseInt($(this).attr('data-index'));
            if (!isNaN(idx) && markersStore[idx]) {
                const targetMarker = markersStore[idx];
                const latLng = targetMarker.getLatLng();
                map.flyTo(latLng, 16, {
                    animate: true,
                    duration: 1
                });
                setTimeout(() => {
                    targetMarker.openPopup();
                }, 1000);
            }
        });

        // Tombol Pusatkan Semua
        $("#btnFitBounds").click(function() {
            if (markersStore.length > 0) {
                const group = L.featureGroup(markersStore);
                map.fitBounds(group.getBounds(), { padding: [40, 40] });
            } else {
                map.setView([initLat, initLng], 13);
            }
        });

        // Load awal data hari ini
        loadTrackingPoints("{{ date('Y-m-d') }}", '', '');
    });
</script>
@endpush
