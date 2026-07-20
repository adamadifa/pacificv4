@extends('layouts.app')
@section('titlepage', 'Monitoring Salesman Realtime')

@section('style')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
<style>
    #map {
        height: 600px;
        width: 100%;
        border-radius: 12px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        border: 1px solid #e2e8f0;
    }
    .salesman-item {
        cursor: pointer;
        transition: all 0.2s ease;
        border-left: 4px solid transparent;
    }
    .salesman-item:hover {
        background-color: #f8fafc;
        border-left-color: #3b82f6;
    }
    .salesman-item.active-select {
        background-color: #eff6ff;
        border-left-color: #2563eb;
    }
    .legend-status {
        width: 12px;
        height: 12px;
        border-radius: 50%;
        display: inline-block;
    }
    .bg-status-green { background-color: #10b981; }
    .bg-status-yellow { background-color: #f59e0b; }
    .bg-status-red { background-color: #ef4444; }
    .salesman-list-container {
        max-height: 600px;
        overflow-y: auto;
    }
</style>
@endsection

@section('content')
@section('navigasi')
    <span>Monitoring Salesman</span>
@endsection

<div class="row">
    <!-- Filter Panel -->
    <div class="col-12 mb-4">
        <div class="card border shadow-sm">
            <div class="card-body py-3">
                <div class="row g-3 align-items-center">
                    <div class="col-lg-4 col-md-4 col-sm-12">
                        <label class="form-label fw-semibold">Pilih Cabang</label>
                        <select id="filter-cabang" class="form-select">
                            <option value="">Semua Cabang</option>
                            @foreach ($cabang as $c)
                                <option value="{{ $c->kode_cabang }}">{{ textUpperCase($c->nama_cabang) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-lg-4 col-md-4 col-sm-12">
                        <label class="form-label fw-semibold">Tanggal Rute (Untuk Detail Pergerakan)</label>
                        <input type="date" id="filter-tanggal" class="form-control" value="{{ date('Y-m-d') }}">
                    </div>
                    <div class="col-lg-4 col-md-4 col-sm-12 d-flex gap-2 align-self-end">
                        <button id="btn-refresh" class="btn btn-primary flex-grow-1">
                            <i class="ti ti-refresh me-1"></i> Refresh Data
                        </button>
                        <button id="btn-clear-trail" class="btn btn-outline-danger" style="display: none;">
                            <i class="ti ti-trash me-1"></i> Bersihkan Rute
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Map & Sidebar -->
    <div class="col-lg-8 col-md-7 col-sm-12 mb-4">
        <div class="card border shadow-sm h-100">
            <div class="card-body p-2">
                <div id="map"></div>
            </div>
        </div>
    </div>

    <div class="col-lg-4 col-md-5 col-sm-12 mb-4">
        <div class="card border shadow-sm h-100">
            <div class="card-header border-bottom py-3 d-flex justify-content-between align-items-center bg-light">
                <h5 class="card-title mb-0 fw-bold">Daftar Salesman</h5>
                <span class="badge bg-primary rounded-pill" id="salesman-count">0</span>
            </div>
            <div class="card-body p-0 salesman-list-container">
                <div class="list-group list-group-flush" id="salesman-list">
                    <div class="text-center py-5 text-muted">
                        <div class="spinner-border spinner-border-sm text-primary mb-2" role="status"></div>
                        <div>Memuat data lokasi...</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('myscript')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        // Initialize Map
        const map = L.map('map').setView([-7.389, 109.355], 11);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '© OpenStreetMap contributors'
        }).addTo(map);

        let markers = {};
        let trailLayer = null;
        let trailMarkers = [];
        let autoRefreshInterval = null;

        // Leaflet custom marker color generator using SVGs
        function getMarkerIcon(color) {
            let colorHex = '#10b981'; // green
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

        // Fetch latest positions
        function loadLatestPositions() {
            const kodeCabang = document.getElementById('filter-cabang').value;
            fetch(`{{ route('tracking.latest') }}?kode_cabang=${kodeCabang}`)
                .then(response => response.json())
                .then(res => {
                    if (res.success) {
                        updateMapAndList(res.data);
                    }
                })
                .catch(err => console.error("Error fetching locations:", err));
        }

        // Update map markers & sidebar list
        function updateMapAndList(data) {
            // Clear existing markers from map
            Object.values(markers).forEach(m => map.removeLayer(m));
            markers = {};

            const listContainer = document.getElementById('salesman-list');
            const countBadge = document.getElementById('salesman-count');
            listContainer.innerHTML = '';
            countBadge.innerText = data.length;

            if (data.length === 0) {
                listContainer.innerHTML = '<div class="text-center py-5 text-muted">Tidak ada data tracking ditemukan.</div>';
                return;
            }

            const bounds = [];

            data.forEach(item => {
                const lat = parseFloat(item.latitude);
                const lng = parseFloat(item.longitude);
                
                if (isNaN(lat) || isNaN(lng)) return;
                bounds.push([lat, lng]);

                // Create Map Marker
                const marker = L.marker([lat, lng], { icon: getMarkerIcon(item.status) });
                
                let popupContent = `
                    <div class="p-1">
                        <h6 class="fw-bold mb-1">${item.nama_salesman}</h6>
                        <small class="text-muted d-block mb-1">Kode: ${item.kode_salesman}</small>
                        <small class="text-muted d-block mb-2">Cabang: ${item.nama_cabang ?? '-'}</small>
                        <table class="table table-sm table-borderless mb-2" style="font-size: 0.8rem;">
                            <tr><td>Update</td><td>: ${item.formatted_tracked_at}</td></tr>
                            <tr><td>Selisih</td><td>: ${item.diff_minutes} menit lalu</td></tr>
                            <tr><td>Akurasi</td><td>: ${item.accuracy ? Math.round(item.accuracy) + ' m' : '-'}</td></tr>
                        </table>
                        <button class="btn btn-sm btn-primary w-100 btn-view-route mb-1" data-kode="${item.kode_salesman}" data-nama="${item.nama_salesman}">
                            <i class="ti ti-map-pin me-1"></i>Lihat Rute Hari Ini
                        </button>
                        <button class="btn btn-sm btn-success w-100 btn-request-location" data-kode="${item.kode_salesman}" data-nama="${item.nama_salesman}">
                            <i class="ti ti-location me-1"></i>Minta Lokasi Sekarang
                        </button>
                    </div>
                `;
                
                marker.bindPopup(popupContent);
                marker.addTo(map);
                markers[item.kode_salesman] = marker;

                // Create Sidebar List Item
                const badgeColor = item.status === 'green' ? 'success' : (item.status === 'yellow' ? 'warning' : 'danger');
                const statusText = item.status === 'green' ? 'Aktif' : (item.status === 'yellow' ? 'Idle' : 'Offline');
                
                const itemHtml = `
                    <div class="list-group-item salesman-item p-3 border-bottom d-flex align-items-center justify-content-between" data-kode="${item.kode_salesman}" data-lat="${lat}" data-lng="${lng}">
                        <div>
                            <div class="fw-bold text-dark mb-1" style="font-size: 0.9rem;">${item.nama_salesman}</div>
                            <div class="text-muted" style="font-size: 0.75rem;">
                                <i class="ti ti-building me-1"></i>${item.nama_cabang ?? '-'}
                            </div>
                            <div class="text-muted" style="font-size: 0.75rem;">
                                <i class="ti ti-clock me-1"></i>${item.formatted_tracked_at}
                            </div>
                        </div>
                        <div class="text-end">
                            <span class="badge bg-light-${badgeColor} text-${badgeColor} rounded-pill px-2 mb-1" style="font-size: 0.7rem;">${statusText}</span>
                            <div class="text-muted" style="font-size: 0.7rem;">${item.diff_minutes}m lalu</div>
                        </div>
                    </div>
                `;
                
                listContainer.insertAdjacentHTML('beforeend', itemHtml);
            });

            // Adjust map bounds to fit all markers if we don't have a trail drawn
            if (bounds.length > 0 && !trailLayer) {
                map.fitBounds(bounds, { padding: [50, 50] });
            }

            // Bind sidebar click actions
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

        // Fetch route trail for a specific salesman
        function loadTrail(kodeSalesman, namaSalesman) {
            const tanggal = document.getElementById('filter-tanggal').value;
            fetch(`/tracking/${kodeSalesman}/trail?tanggal=${tanggal}`)
                .then(response => response.json())
                .then(res => {
                    if (res.success) {
                        displayTrail(res.data, namaSalesman);
                    }
                })
                .catch(err => console.error("Error fetching trail:", err));
        }

        // Display trail polyline and path markers on the map (Snapped to roads using OSRM)
        function displayTrail(data, namaSalesman) {
            clearTrail();

            if (data.length === 0) {
                alert(`Tidak ada rute pergerakan ditemukan untuk ${namaSalesman} pada tanggal tersebut.`);
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

                    // Calculate distance in meters using Leaflet distanceTo
                    const p1 = L.latLng(lat, lng);
                    const p2 = L.latLng(prevLat, prevLng);
                    const distanceMeters = p1.distanceTo(p2);
                    
                    if (distanceMeters >= 1000) {
                        distanceText = (distanceMeters / 1000).toFixed(2) + ' km';
                    } else {
                        distanceText = Math.round(distanceMeters) + ' meter';
                    }

                    // Calculate time difference
                    const t1 = new Date(point.tracked_at);
                    const t2 = new Date(prevPoint.tracked_at);
                    const diffMs = t1 - t2;
                    const diffSecs = Math.floor(diffMs / 1000);
                    const diffMins = Math.floor(diffSecs / 60);
                    const remainingSecs = diffSecs % 60;
                    
                    if (diffMins > 0) {
                        durationText = `${diffMins} m ${remainingSecs} s`;
                    } else {
                        durationText = `${remainingSecs} detik`;
                    }
                }

                // Create markers for trail points showing the order number
                const numberIcon = L.divIcon({
                    className: 'custom-trail-icon',
                    html: `<div style="
                        background-color: #2563eb;
                        color: #ffffff;
                        border: 2px solid #ffffff;
                        border-radius: 50%;
                        width: 20px;
                        height: 20px;
                        display: flex;
                        align-items: center;
                        justify-content: center;
                        font-size: 9px;
                        font-weight: bold;
                        box-shadow: 0 2px 5px rgba(0,0,0,0.3);
                    ">${index + 1}</div>`,
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

            // Fetch routing geometry from Open Source Routing Machine (OSRM) to snap to road
            // Format: longitude,latitude;longitude,latitude...
            const coordsString = data.map(p => `${p.longitude},${p.latitude}`).join(';');
            const osrmUrl = `https://router.project-osrm.org/route/v1/driving/${coordsString}?overview=full&geometries=geojson`;

            fetch(osrmUrl)
                .then(response => response.json())
                .then(res => {
                    if (res.code === 'Ok' && res.routes && res.routes[0]) {
                        const routeCoords = res.routes[0].geometry.coordinates;
                        // Convert [lng, lat] from GeoJSON to [lat, lng] for Leaflet
                        const snappedLatLngs = routeCoords.map(c => [c[1], c[0]]);
                        
                        trailLayer = L.polyline(snappedLatLngs, {
                            color: '#3b82f6',
                            weight: 5,
                            opacity: 0.85
                        }).addTo(map);
                    } else {
                        // Fallback to straight lines if OSRM fails
                        trailLayer = L.polyline(latlngs, {
                            color: '#3b82f6',
                            weight: 4,
                            opacity: 0.8,
                            dashArray: '5, 10'
                        }).addTo(map);
                    }
                    map.fitBounds(bounds, { padding: [50, 50] });
                })
                .catch(err => {
                    console.error("OSRM Routing Error:", err);
                    // Fallback to straight lines
                    trailLayer = L.polyline(latlngs, {
                        color: '#3b82f6',
                        weight: 4,
                        opacity: 0.8,
                        dashArray: '5, 10'
                    }).addTo(map);
                    map.fitBounds(bounds, { padding: [50, 50] });
                });

            document.getElementById('btn-clear-trail').style.display = 'inline-block';
        }

        // Clear trail layers from map
        function clearTrail() {
            if (trailLayer) {
                map.removeLayer(trailLayer);
                trailLayer = null;
            }
            trailMarkers.forEach(m => map.removeLayer(m));
            trailMarkers = [];
            document.getElementById('btn-clear-trail').style.display = 'none';
        }

        // Listen for View Route button inside Marker Popup
        document.addEventListener('click', function(e) {
            const btn = e.target.closest('.btn-view-route');
            if (btn) {
                const kode = btn.getAttribute('data-kode');
                const nama = btn.getAttribute('data-nama');
                loadTrail(kode, nama);
                map.closePopup();
            }
        });

        // Listen for Request Location button inside Marker Popup
        document.addEventListener('click', function(e) {
            const btn = e.target.closest('.btn-request-location');
            if (btn) {
                const kode = btn.getAttribute('data-kode');
                const nama = btn.getAttribute('data-nama');
                
                // Show loading/sending state
                btn.disabled = true;
                const originalText = btn.innerHTML;
                btn.innerHTML = `<span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span> Mengirim...`;

                fetch(`/tracking/${kode}/request`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(res => {
                    if (res.success) {
                        alert(`Perintah pelacakan terkirim ke ${nama}. Silakan tunggu beberapa saat.`);
                        // Auto-refresh peta setelah 3 detik untuk mengambil lokasi terbaru
                        setTimeout(loadLatestPositions, 3000);
                    } else {
                        alert(`Gagal meminta lokasi: ${res.message}`);
                    }
                })
                .catch(err => {
                    console.error("Error requesting location:", err);
                    alert("Terjadi kesalahan koneksi.");
                })
                .finally(() => {
                    btn.disabled = false;
                    btn.innerHTML = originalText;
                });
            }
        });

        // Event listeners for filters and refresh
        document.getElementById('filter-cabang').addEventListener('change', loadLatestPositions);
        document.getElementById('btn-refresh').addEventListener('click', loadLatestPositions);
        document.getElementById('btn-clear-trail').addEventListener('click', clearTrail);

        // Load initially
        loadLatestPositions();

        // Polling auto-refresh every 30 seconds
        autoRefreshInterval = setInterval(loadLatestPositions, 30000);
    });
</script>
@endpush
