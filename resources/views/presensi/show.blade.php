<style>
    .attendance-detail-card {
        border: none;
        box-shadow: none;
    }

    .attendance-photo-container {
        width: 100%;
        height: 280px;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        border: 3px solid #fff;
    }

    .attendance-photo-container img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .info-label {
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: #64748b;
        font-weight: 700;
        margin-bottom: 2px;
    }

    .info-value {
        font-size: 0.95rem;
        font-weight: 600;
        color: #1e293b;
    }

    .map-container {
        height: 250px;
        width: 100%;
        border-radius: 12px;
        overflow: hidden;
        border: 1px solid #e2e8f0;
        box-shadow: inset 0 2px 4px rgba(0, 0, 0, 0.05);
    }

    .badge-status {
        padding: 6px 12px;
        border-radius: 8px;
        font-weight: 700;
        font-size: 0.75rem;
        text-transform: uppercase;
    }

    .info-item {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 10px;
        border-radius: 10px;
        background: #f8fafc;
        transition: all 0.2s;
    }

    .info-item:hover {
        background: #f1f5f9;
    }

    .info-icon {
        width: 36px;
        height: 36px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 8px;
        background: #fff;
        color: #3b82f6;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
    }
</style>

<div class="attendance-detail-card">
    <div class="row g-4">
        {{-- Left Side: Photo & Status --}}
        <div class="col-md-5">
            <div class="attendance-photo-container mb-3">
                @php
                    $foto = $status == 'in' ? $presensi->foto_in : $presensi->foto_out;
                    $type_label = $status == 'in' ? 'Masuk' : 'Pulang';
                    $type_color = $status == 'in' ? 'success' : 'danger';
                    $type_icon = $status == 'in' ? 'ti-login' : 'ti-logout';
                @endphp

                @if (!empty($foto))
                    <img src="{{ asset('storage/uploads/absensi/' . $foto) }}" alt="Attendance Photo">
                @else
                    <div class="w-100 h-100 d-flex flex-column align-items-center justify-content-center bg-light text-muted">
                        <i class="ti ti-camera-off fs-1 mb-2"></i>
                        <span class="small fw-bold">Tidak Ada Foto</span>
                    </div>
                @endif
            </div>

            <div class="d-grid">
                <div class="badge-status bg-label-{{ $type_color }} text-center d-flex align-items-center justify-content-center gap-2">
                    <i class="ti {{ $type_icon }} fs-5"></i>
                    PRESENSI {{ $type_label }}
                </div>
            </div>
        </div>

        {{-- Right Side: Information --}}
        <div class="col-md-7">
            <div class="row g-3">
                <div class="col-12">
                    <div class="info-item">
                        <div class="info-icon">
                            <i class="ti ti-user fs-5"></i>
                        </div>
                        <div>
                            <div class="info-label">Karyawan</div>
                            <div class="info-value">{{ $presensi->nama_karyawan }}</div>
                            <div class="text-muted" style="font-size: 0.7rem;">NIK: {{ $presensi->nik }}</div>
                        </div>
                    </div>
                </div>

                <div class="col-sm-6">
                    <div class="info-item">
                        <div class="info-icon text-primary">
                            <i class="ti ti-calendar fs-5"></i>
                        </div>
                        <div>
                            <div class="info-label">Tanggal</div>
                            <div class="info-value">{{ DateToIndo($presensi->tanggal) }}</div>
                        </div>
                    </div>
                </div>

                <div class="col-sm-6">
                    <div class="info-item">
                        <div class="info-icon text-info">
                            <i class="ti ti-clock fs-5"></i>
                        </div>
                        <div>
                            <div class="info-label">Waktu {{ $type_label }}</div>
                            <div class="info-value">{{ date('H:i', strtotime($status == 'in' ? $presensi->jam_in : $presensi->jam_out)) }}</div>
                        </div>
                    </div>
                </div>

                <div class="col-12">
                    <div class="info-item">
                        <div class="info-icon text-warning">
                            <i class="ti ti-map-pin fs-5"></i>
                        </div>
                        <div class="w-100">
                            <div class="info-label">Lokasi Presensi</div>
                            <div class="info-value text-truncate" style="max-width: 200px;">
                                {{ $status == 'in' ? $presensi->lokasi_in : $presensi->lokasi_out }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Bottom: Map --}}
        @php
            $lokasi = $status == 'in' ? $presensi->lokasi_in : $presensi->lokasi_out;
            $map_id = $status == 'in' ? 'map' : 'map_out';
        @endphp

        @if (!empty($lokasi))
            <div class="col-12">
                <div class="d-flex align-items-center gap-2 mb-2 px-1">
                    <i class="ti ti-map-2 text-primary fs-5"></i>
                    <h6 class="mb-0 fw-bold">Visualisasi Lokasi</h6>
                </div>
                <div class="map-container">
                    <div id="{{ $map_id }}" style="width: 100%; height: 100%;"></div>
                </div>
            </div>
        @endif
    </div>
</div>

<script>
    setTimeout(function() {
        var lokasi = "{{ $lokasi }}";
        var lok = lokasi.split(",");
        var latitude = lok[0];
        var longitude = lok[1];

        var latitude_kantor = "{{ $latitude }}";
        var longitude_kantor = "{{ $longitude }}";
        var rd = "{{ $presensi->radius_cabang }}";

        var mapId = "{{ $map_id }}";
        var map = L.map(mapId, {
            center: [latitude, longitude],
            zoom: 15,
            zoomControl: true
        });

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '&copy; OpenStreetMap'
        }).addTo(map);

        var marker = L.marker([latitude, longitude]).addTo(map);

        var circle = L.circle([latitude_kantor, longitude_kantor], {
            color: '#3b82f6',
            fillColor: '#3b82f6',
            fillOpacity: 0.15,
            radius: rd
        }).addTo(map);

        // Auto invalidate size after modal shown
        map.invalidateSize();
    }, 300);
</script>
