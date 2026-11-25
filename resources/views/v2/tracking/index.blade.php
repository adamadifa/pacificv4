@extends('layouts.app')
@section('titlepage', 'Monitoring Salesman Realtime')

@section('style')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
<style>
    #map {
        height: 600px;
        width: 100%;
        border-radius: 10px;
        z-index: 1;
    }
    .salesman-item.active-select {
        background-color: rgba(40, 76, 154, 0.08);
        border-left-color: #284c9a !important;
    }
</style>
@endsection

@section('navigasi')
    <div class="flex items-center gap-1.5 text-xs">
        <a href="{{ route('dashboard') }}" class="text-slate-400 hover:text-[#284c9a] transition-colors">Home</a>
        <i data-lucide="chevron-right" class="w-3 h-3 text-slate-400"></i>
        <span class="text-slate-400">Monitoring</span>
        <i data-lucide="chevron-right" class="w-3 h-3 text-slate-400"></i>
        <span class="text-[#284c9a] dark:text-blue-400 font-bold">Realtime Tracking</span>
    </div>
@endsection

@section('content')
<div class="space-y-4">
    <!-- COMPACT NO-LABEL FILTER BAR -->
    <div class="rounded-xl bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 p-3 shadow-2xs">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-12 gap-2.5 items-center">
            <div class="lg:col-span-4 relative select2-icon-wrapper">
                <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none z-20 text-slate-400">
                    <i data-lucide="map-pin" class="w-3.5 h-3.5"></i>
                </div>
                <select id="filter-cabang" class="select2CabangTracking w-full" data-placeholder="Semua Cabang">
                    <option value="">Semua Cabang</option>
                    @foreach ($cabang as $c)
                        <option value="{{ $c->kode_cabang }}">{{ textUpperCase($c->nama_cabang) }}</option>
                    @endforeach
                </select>
            </div>

            <div class="lg:col-span-4 relative">
                <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                    <i data-lucide="calendar" class="w-3.5 h-3.5"></i>
                </div>
                <input type="text" id="filter-tanggal" value="{{ date('Y-m-d') }}" readonly class="flatpickr-date w-full h-[36px] pl-8 pr-3 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-lg text-xs font-semibold text-slate-800 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-[#284c9a]/20 focus:border-[#284c9a] cursor-pointer" placeholder="Tanggal Rute">
            </div>

            <div class="lg:col-span-4 flex items-center gap-2">
                <button type="button" id="btn-refresh" class="flex-1 h-[36px] px-4 rounded-lg bg-[#284c9a] hover:bg-[#1e3a78] text-white text-xs font-bold flex items-center justify-center gap-1.5 transition cursor-pointer">
                    <i data-lucide="refresh-cw" class="w-3.5 h-3.5"></i>
                    <span>Refresh Data</span>
                </button>
                <button type="button" id="btn-clear-trail" class="hidden h-[36px] px-3.5 rounded-lg border border-rose-300 dark:border-rose-800 bg-rose-50 dark:bg-rose-950/40 hover:bg-rose-600 hover:text-white text-rose-600 dark:text-rose-400 text-xs font-bold flex items-center justify-center gap-1.5 transition cursor-pointer">
                    <i data-lucide="trash-2" class="w-3.5 h-3.5"></i>
                    <span>Bersihkan Rute</span>
                </button>
            </div>
        </div>
    </div>

    <!-- MAP & SIDEBAR LIST -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-4 items-start">
        <!-- Map (8 cols) -->
        <div class="lg:col-span-8 rounded-xl bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 shadow-2xs overflow-hidden">
            <div class="px-3.5 py-2 bg-[#284c9a] text-white flex items-center justify-between gap-2 flex-wrap">
                <div class="flex items-center gap-2">
                    <i data-lucide="map" class="w-3.5 h-3.5 text-blue-200"></i>
                    <h2 class="text-xs font-bold text-white uppercase tracking-wider">Peta Lokasi Salesman Realtime</h2>
                </div>
                <div class="flex items-center gap-3 text-[11px] font-semibold">
                    <span class="inline-flex items-center gap-1"><span class="w-2.5 h-2.5 rounded-full bg-emerald-400"></span> Aktif (&lt;15m)</span>
                    <span class="inline-flex items-center gap-1"><span class="w-2.5 h-2.5 rounded-full bg-amber-400"></span> Idle (&lt;30m)</span>
                    <span class="inline-flex items-center gap-1"><span class="w-2.5 h-2.5 rounded-full bg-rose-400"></span> Offline</span>
                </div>
            </div>
            <div class="p-2">
                <div id="map"></div>
            </div>
        </div>

        <!-- Salesman List (4 cols) -->
        <div class="lg:col-span-4 rounded-xl bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 shadow-2xs overflow-hidden">
            <div class="px-3.5 py-2 bg-[#284c9a] text-white flex items-center justify-between gap-2">
                <div class="flex items-center gap-2">
                    <i data-lucide="users" class="w-3.5 h-3.5 text-blue-200"></i>
                    <h2 class="text-xs font-bold text-white uppercase tracking-wider">Daftar Salesman</h2>
                </div>
                <span id="salesman-count" class="px-2 py-0.5 rounded-md bg-white/15 text-white text-[11px] font-bold font-mono-num">0</span>
            </div>

            <div class="max-h-[616px] overflow-y-auto divide-y divide-slate-100 dark:divide-slate-800/80 scrollbar-thin" id="salesman-list">
                <div class="py-12 text-center text-slate-400 text-xs">
                    <div class="inline-block w-5 h-5 border-2 border-[#284c9a] border-t-transparent rounded-full animate-spin mb-2"></div>
                    <div>Memuat data lokasi...</div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('myscript')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
<script>
    $(function() {
        $('.select2CabangTracking').select2({
            placeholder: 'Semua Cabang',
            allowClear: true
        });

        $('.flatpickr-date').flatpickr({
            dateFormat: 'Y-m-d'
        });
    });

    document.addEventListener("DOMContentLoaded", function() {
        const map = L.map('map').setView([-7.389, 109.355], 11);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '© OpenStreetMap contributors'
        }).addTo(map);

        let markers = {};
        let trailLayer = null;
        let trailMarkers = [];
        let autoRefreshInterval = null;

        function notify(msg, type = 'info') {
            if (typeof toastr !== 'undefined') {
                toastr[type](msg);
            } else {
                alert(msg);
            }
        }

        function getMarkerIcon(color) {
            let colorHex = '#10b981';
            if (color === 'yellow') colorHex = '#f59e0b';
            if (color === 'red') colorHex = '#ef4444';

            return L.divIcon({
                className: 'custom-div-icon',
                html: `<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="${colorHex}" width="36px" height="36px">
                        <path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"/>
                       </svg>`,
                iconSize: [36, 36],
                iconAnchor: [18, 36],
                popupAnchor: [0, -32]
            });
        }

        function loadLatestPositions() {
            const kodeCabang = $('#filter-cabang').val() || '';
            fetch(`{{ route('tracking.latest') }}?kode_cabang=${encodeURIComponent(kodeCabang)}`)
                .then(response => response.json())
                .then(res => {
                    if (res.success) {
                        updateMapAndList(res.data);
                    }
                })
                .catch(err => console.error("Error fetching locations:", err));
        }

        function updateMapAndList(data) {
            Object.values(markers).forEach(m => map.removeLayer(m));
            markers = {};

            const listContainer = document.getElementById('salesman-list');
            const countBadge = document.getElementById('salesman-count');
            listContainer.innerHTML = '';
            countBadge.innerText = data.length;

            if (data.length === 0) {
                listContainer.innerHTML = '<div class="py-12 text-center text-slate-400 text-xs">Tidak ada data tracking ditemukan.</div>';
                return;
            }

            const bounds = [];

            data.forEach(item => {
                const lat = parseFloat(item.latitude);
                const lng = parseFloat(item.longitude);
                if (isNaN(lat) || isNaN(lng)) return;
                bounds.push([lat, lng]);

                const marker = L.marker([lat, lng], { icon: getMarkerIcon(item.status) });

                let popupContent = `
                    <div class="p-1 min-w-[220px] font-sans">
                        <h6 class="font-bold text-slate-900 text-xs mb-0.5">${item.nama_salesman}</h6>
                        <div class="text-[11px] text-slate-500">Kode: <strong class="font-mono">${item.kode_salesman}</strong></div>
                        <div class="text-[11px] text-slate-500 mb-2">Cabang: <strong>${item.nama_cabang ?? '-'}</strong></div>
                        <div class="text-[11px] text-slate-600 space-y-0.5 mb-2.5 border-t border-b border-slate-100 py-1.5">
                            <div>Update: <strong>${item.formatted_tracked_at}</strong></div>
                            <div>Selisih: <strong>${item.diff_minutes} menit lalu</strong></div>
                            <div>Akurasi: <strong>${item.accuracy ? Math.round(item.accuracy) + ' m' : '-'}</strong></div>
                        </div>
                        <div class="space-y-1">
                            <button type="button" class="btn-view-route w-full py-1.5 px-2.5 rounded bg-[#284c9a] hover:bg-[#1e3a78] text-white text-[11px] font-bold cursor-pointer" data-kode="${item.kode_salesman}" data-nama="${item.nama_salesman}">
                                Lihat Rute Hari Ini
                            </button>
                            <button type="button" class="btn-request-location w-full py-1.5 px-2.5 rounded bg-emerald-600 hover:bg-emerald-700 text-white text-[11px] font-bold cursor-pointer" data-kode="${item.kode_salesman}" data-nama="${item.nama_salesman}">
                                Minta Lokasi Sekarang
                            </button>
                            <div class="flex gap-1">
                                <button type="button" class="btn-play-alarm flex-1 py-1.5 px-2 rounded bg-rose-600 hover:bg-rose-700 text-white text-[11px] font-bold cursor-pointer" data-kode="${item.kode_salesman}" data-nama="${item.nama_salesman}">
                                    Bunyikan
                                </button>
                                <button type="button" class="btn-stop-alarm flex-1 py-1.5 px-2 rounded bg-slate-600 hover:bg-slate-700 text-white text-[11px] font-bold cursor-pointer" data-kode="${item.kode_salesman}" data-nama="${item.nama_salesman}">
                                    Matikan
                                </button>
                            </div>
                        </div>
                    </div>
                `;

                marker.bindPopup(popupContent);
                marker.addTo(map);
                markers[item.kode_salesman] = marker;

                const statusBadge = item.status === 'green'
                    ? 'bg-emerald-50 dark:bg-emerald-950/50 text-emerald-700 dark:text-emerald-400 border-emerald-200 dark:border-emerald-900'
                    : (item.status === 'yellow'
                        ? 'bg-amber-50 dark:bg-amber-950/50 text-amber-700 dark:text-amber-400 border-amber-200 dark:border-amber-900'
                        : 'bg-rose-50 dark:bg-rose-950/50 text-rose-700 dark:text-rose-400 border-rose-200 dark:border-rose-900');
                const statusText = item.status === 'green' ? 'Aktif' : (item.status === 'yellow' ? 'Idle' : 'Offline');

                const itemHtml = `
                    <div class="salesman-item px-3.5 py-2.5 border-l-4 border-l-transparent hover:bg-slate-50 dark:hover:bg-slate-800/40 cursor-pointer flex items-center justify-between gap-2 transition-colors" data-kode="${item.kode_salesman}" data-lat="${lat}" data-lng="${lng}">
                        <div class="min-w-0">
                            <div class="text-xs font-bold text-slate-800 dark:text-slate-100 truncate">${item.nama_salesman}</div>
                            <div class="text-[11px] text-slate-500 dark:text-slate-400 flex items-center gap-1 mt-0.5">
                                <i data-lucide="building" class="w-3 h-3 shrink-0"></i>
                                <span class="truncate">${item.nama_cabang ?? '-'}</span>
                            </div>
                            <div class="text-[11px] text-slate-400 flex items-center gap-1 mt-0.5 font-mono-num">
                                <i data-lucide="clock" class="w-3 h-3 shrink-0"></i>
                                <span>${item.formatted_tracked_at}</span>
                            </div>
                        </div>
                        <div class="text-right shrink-0">
                            <span class="inline-flex items-center px-2 py-0.5 rounded-md border text-[10px] font-bold ${statusBadge}">${statusText}</span>
                            <div class="text-[10px] text-slate-400 mt-1 font-mono-num">${item.diff_minutes}m lalu</div>
                        </div>
                    </div>
                `;

                listContainer.insertAdjacentHTML('beforeend', itemHtml);
            });

            if (window.lucide) lucide.createIcons();

            if (bounds.length > 0 && !trailLayer) {
                map.fitBounds(bounds, { padding: [50, 50] });
            }

            document.querySelectorAll('.salesman-item').forEach(el => {
                el.addEventListener('click', function() {
                    const lat = parseFloat(this.getAttribute('data-lat'));
                    const lng = parseFloat(this.getAttribute('data-lng'));
                    const kode = this.getAttribute('data-kode');

                    document.querySelectorAll('.salesman-item').forEach(x => x.classList.remove('active-select'));
                    this.classList.add('active-select');

                    map.setView([lat, lng], 16);
                    if (markers[kode]) {
                        markers[kode].openPopup();
                    }
                });
            });
        }

        function loadTrail(kodeSalesman, namaSalesman) {
            const tanggal = document.getElementById('filter-tanggal').value;
            fetch(`/tracking/${kodeSalesman}/trail?tanggal=${encodeURIComponent(tanggal)}`)
                .then(response => response.json())
                .then(res => {
                    if (res.success) {
                        displayTrail(res.data, namaSalesman);
                    }
                })
                .catch(err => console.error("Error fetching trail:", err));
        }

        function displayTrail(data, namaSalesman) {
            clearTrail();

            if (data.length === 0) {
                notify(`Tidak ada rute pergerakan ditemukan untuk ${namaSalesman} pada tanggal tersebut.`, 'warning');
                return;
            }

            const latlngs = [];
            data.forEach((point, index) => {
                const lat = parseFloat(point.latitude);
                const lng = parseFloat(point.longitude);
                latlngs.push([lat, lng]);

                let distanceText = '-';
                let durationText = '-';

                if (index > 0) {
                    const prevPoint = data[index - 1];
                    const prevLat = parseFloat(prevPoint.latitude);
                    const prevLng = parseFloat(prevPoint.longitude);

                    const distanceMeters = L.latLng(lat, lng).distanceTo(L.latLng(prevLat, prevLng));
                    distanceText = distanceMeters >= 1000 ? (distanceMeters / 1000).toFixed(2) + ' km' : Math.round(distanceMeters) + ' meter';

                    const diffSecs = Math.floor((new Date(point.tracked_at) - new Date(prevPoint.tracked_at)) / 1000);
                    const diffMins = Math.floor(diffSecs / 60);
                    const remainingSecs = diffSecs % 60;
                    durationText = diffMins > 0 ? `${diffMins} m ${remainingSecs} s` : `${remainingSecs} detik`;
                }

                const numberIcon = L.divIcon({
                    className: 'custom-trail-icon',
                    html: `<div style="background-color:#284c9a;color:#fff;border:2px solid #fff;border-radius:50%;width:20px;height:20px;display:flex;align-items:center;justify-content:center;font-size:9px;font-weight:bold;box-shadow:0 2px 5px rgba(0,0,0,0.3);">${index + 1}</div>`,
                    iconSize: [20, 20],
                    iconAnchor: [10, 10]
                });

                const dotMarker = L.marker([lat, lng], { icon: numberIcon }).bindTooltip(`
                    <b>Titik #${index + 1}</b><br>
                    Waktu: ${point.tracked_at.substring(11, 19)}<br>
                    Jarak dari Titik #${index}: ${distanceText}<br>
                    Waktu Tempuh: ${durationText}<br>
                    Akurasi: ${point.accuracy ? Math.round(point.accuracy) + ' m' : '-'}
                `);

                dotMarker.addTo(map);
                trailMarkers.push(dotMarker);
            });

            const bounds = L.latLngBounds(latlngs);
            const coordsString = data.map(p => `${p.longitude},${p.latitude}`).join(';');
            const osrmUrl = `https://router.project-osrm.org/route/v1/driving/${coordsString}?overview=full&geometries=geojson`;

            fetch(osrmUrl)
                .then(response => response.json())
                .then(res => {
                    if (res.code === 'Ok' && res.routes && res.routes[0]) {
                        const snappedLatLngs = res.routes[0].geometry.coordinates.map(c => [c[1], c[0]]);
                        trailLayer = L.polyline(snappedLatLngs, { color: '#284c9a', weight: 5, opacity: 0.85 }).addTo(map);
                    } else {
                        trailLayer = L.polyline(latlngs, { color: '#284c9a', weight: 4, opacity: 0.8, dashArray: '5, 10' }).addTo(map);
                    }
                    map.fitBounds(bounds, { padding: [50, 50] });
                })
                .catch(() => {
                    trailLayer = L.polyline(latlngs, { color: '#284c9a', weight: 4, opacity: 0.8, dashArray: '5, 10' }).addTo(map);
                    map.fitBounds(bounds, { padding: [50, 50] });
                });

            const clearBtn = document.getElementById('btn-clear-trail');
            clearBtn.classList.remove('hidden');
        }

        function clearTrail() {
            if (trailLayer) {
                map.removeLayer(trailLayer);
                trailLayer = null;
            }
            trailMarkers.forEach(m => map.removeLayer(m));
            trailMarkers = [];
            document.getElementById('btn-clear-trail').classList.add('hidden');
        }

        document.addEventListener('click', function(e) {
            const viewBtn = e.target.closest('.btn-view-route');
            if (viewBtn) {
                loadTrail(viewBtn.getAttribute('data-kode'), viewBtn.getAttribute('data-nama'));
                map.closePopup();
                return;
            }

            const reqBtn = e.target.closest('.btn-request-location');
            if (reqBtn) {
                const kode = reqBtn.getAttribute('data-kode');
                const nama = reqBtn.getAttribute('data-nama');
                reqBtn.disabled = true;
                const orig = reqBtn.innerHTML;
                reqBtn.innerHTML = 'Mengirim...';

                fetch(`/tracking/${kode}/request`, {
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Content-Type': 'application/json' }
                })
                .then(r => r.json())
                .then(res => {
                    if (res.success) {
                        notify(`Perintah pelacakan terkirim ke ${nama}. Silakan tunggu beberapa saat.`, 'success');
                        setTimeout(loadLatestPositions, 3000);
                    } else {
                        notify(`Gagal meminta lokasi: ${res.message}`, 'error');
                    }
                })
                .catch(() => notify("Terjadi kesalahan koneksi.", 'error'))
                .finally(() => { reqBtn.disabled = false; reqBtn.innerHTML = orig; });
                return;
            }

            const playBtn = e.target.closest('.btn-play-alarm');
            if (playBtn) {
                const kode = playBtn.getAttribute('data-kode');
                const nama = playBtn.getAttribute('data-nama');
                playBtn.disabled = true;
                const orig = playBtn.innerHTML;
                playBtn.innerHTML = '...';

                fetch(`/tracking/${kode}/play-alarm`, {
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Content-Type': 'application/json' }
                })
                .then(r => r.json())
                .then(res => {
                    if (res.success) notify(`Alarm berhasil dibunyikan di perangkat ${nama}.`, 'success');
                    else notify(`Gagal membunyikan alarm: ${res.message}`, 'error');
                })
                .catch(() => notify("Terjadi kesalahan koneksi.", 'error'))
                .finally(() => { playBtn.disabled = false; playBtn.innerHTML = orig; });
                return;
            }

            const stopBtn = e.target.closest('.btn-stop-alarm');
            if (stopBtn) {
                const kode = stopBtn.getAttribute('data-kode');
                const nama = stopBtn.getAttribute('data-nama');
                stopBtn.disabled = true;
                const orig = stopBtn.innerHTML;
                stopBtn.innerHTML = '...';

                fetch(`/tracking/${kode}/stop-alarm`, {
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Content-Type': 'application/json' }
                })
                .then(r => r.json())
                .then(res => {
                    if (res.success) notify(`Alarm berhasil dimatikan di perangkat ${nama}.`, 'success');
                    else notify(`Gagal mematikan alarm: ${res.message}`, 'error');
                })
                .catch(() => notify("Terjadi kesalahan koneksi.", 'error'))
                .finally(() => { stopBtn.disabled = false; stopBtn.innerHTML = orig; });
            }
        });

        $('#filter-cabang').on('change', loadLatestPositions);
        document.getElementById('btn-refresh').addEventListener('click', loadLatestPositions);
        document.getElementById('btn-clear-trail').addEventListener('click', clearTrail);

        loadLatestPositions();
        autoRefreshInterval = setInterval(loadLatestPositions, 30000);
    });
</script>
@endpush
