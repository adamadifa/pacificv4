@php
    $foto = $status == 'in' ? $presensi->foto_in : $presensi->foto_out;
    $type_label = $status == 'in' ? 'Masuk' : 'Pulang';
    $is_in = $status == 'in';
    $lokasi = $is_in ? $presensi->lokasi_in : $presensi->lokasi_out;
    $map_id = $is_in ? 'map' : 'map_out';
@endphp

<style>
    .presensi-show-grid {
        display: grid;
        grid-template-columns: 240px minmax(0, 1fr);
        gap: 16px;
        align-items: start;
    }
    .presensi-info-row {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 10px;
    }
    .presensi-photo-box {
        width: 100%;
        height: 230px;
        border-radius: 12px;
        overflow: hidden;
        border: 1px solid #e2e8f0;
        background: #f1f5f9;
    }
    .presensi-map-box {
        width: 100%;
        height: 260px;
        border-radius: 12px;
        overflow: hidden;
        border: 1px solid #e2e8f0;
        position: relative;
        z-index: 0;
    }
    @media (max-width: 767px) {
        .presensi-show-grid,
        .presensi-info-row {
            grid-template-columns: 1fr;
        }
    }
</style>

<div class="space-y-4">
    <div class="presensi-show-grid">
        <!-- Left: Foto & Status -->
        <div class="space-y-2.5">
            <div class="presensi-photo-box">
                @if (!empty($foto))
                    <img src="{{ asset('storage/uploads/absensi/' . $foto) }}" alt="Attendance Photo" style="width:100%; height:100%; object-fit:cover;">
                @else
                    <div class="w-full h-full flex flex-col items-center justify-center text-slate-400 gap-1.5" style="height:100%;">
                        <i data-lucide="camera-off" class="w-8 h-8"></i>
                        <span class="text-xs font-bold">Tidak Ada Foto</span>
                    </div>
                @endif
            </div>

            <div class="w-full py-2 px-3 rounded-lg text-xs font-bold uppercase tracking-wider text-center flex items-center justify-center gap-1.5 {{ $is_in ? 'bg-emerald-50 text-emerald-700 dark:bg-emerald-950/60 dark:text-emerald-300' : 'bg-rose-50 text-rose-700 dark:bg-rose-950/60 dark:text-rose-300' }}">
                <i data-lucide="{{ $is_in ? 'log-in' : 'log-out' }}" class="w-4 h-4"></i>
                <span>PRESENSI {{ $type_label }}</span>
            </div>
        </div>

        <!-- Right: Info -->
        <div class="space-y-2.5 min-w-0">
            <div class="p-3 rounded-xl bg-slate-50 dark:bg-slate-800/50 border border-slate-200/70 dark:border-slate-800 flex items-center gap-3">
                <div class="w-9 h-9 rounded-lg bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-700 flex items-center justify-center text-[#284c9a] dark:text-blue-400 shrink-0">
                    <i data-lucide="user" class="w-4 h-4"></i>
                </div>
                <div class="min-w-0 flex-1">
                    <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Karyawan</p>
                    <p class="text-xs font-bold text-slate-800 dark:text-slate-100 truncate">{{ $presensi->nama_karyawan }}</p>
                    <p class="text-[11px] font-mono-num text-slate-400">NIK: {{ $presensi->nik }}</p>
                </div>
            </div>

            <div class="presensi-info-row">
                <div class="p-3 rounded-xl bg-slate-50 dark:bg-slate-800/50 border border-slate-200/70 dark:border-slate-800 flex items-center gap-3 min-w-0">
                    <div class="w-9 h-9 rounded-lg bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-700 flex items-center justify-center text-[#284c9a] dark:text-blue-400 shrink-0">
                        <i data-lucide="calendar" class="w-4 h-4"></i>
                    </div>
                    <div class="min-w-0">
                        <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Tanggal</p>
                        <p class="text-xs font-bold text-slate-800 dark:text-slate-100">{{ DateToIndo($presensi->tanggal) }}</p>
                    </div>
                </div>

                <div class="p-3 rounded-xl bg-slate-50 dark:bg-slate-800/50 border border-slate-200/70 dark:border-slate-800 flex items-center gap-3 min-w-0">
                    <div class="w-9 h-9 rounded-lg bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-700 flex items-center justify-center text-sky-600 dark:text-sky-400 shrink-0">
                        <i data-lucide="clock" class="w-4 h-4"></i>
                    </div>
                    <div class="min-w-0">
                        <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Waktu {{ $type_label }}</p>
                        <p class="text-xs font-black font-mono-num text-slate-800 dark:text-slate-100">{{ date('H:i', strtotime($is_in ? $presensi->jam_in : $presensi->jam_out)) }}</p>
                    </div>
                </div>
            </div>

            <div class="p-3 rounded-xl bg-slate-50 dark:bg-slate-800/50 border border-slate-200/70 dark:border-slate-800 flex items-center gap-3 min-w-0">
                <div class="w-9 h-9 rounded-lg bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-700 flex items-center justify-center text-amber-600 dark:text-amber-400 shrink-0">
                    <i data-lucide="map-pin" class="w-4 h-4"></i>
                </div>
                <div class="min-w-0 flex-1">
                    <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Lokasi Presensi</p>
                    <p class="text-xs font-mono-num font-semibold text-slate-800 dark:text-slate-100 truncate">{{ $lokasi ?: '-' }}</p>
                </div>
            </div>
        </div>
    </div>

    @if (!empty($lokasi))
        <div>
            <div class="flex items-center gap-1.5 mb-2">
                <i data-lucide="map" class="w-4 h-4 text-[#284c9a] dark:text-blue-400"></i>
                <h6 class="text-xs font-bold text-slate-800 dark:text-slate-100">Visualisasi Lokasi</h6>
            </div>
            <div class="presensi-map-box">
                <div id="{{ $map_id }}" style="width:100%; height:100%;"></div>
            </div>
        </div>
    @endif

    <div class="pt-3 border-t border-slate-100 dark:border-slate-800 flex items-center justify-end">
        <button type="button" onclick="window.closeModalPresensi && window.closeModalPresensi()"
            class="h-[34px] px-4 rounded-lg bg-[#284c9a] hover:bg-[#1e3a78] text-white text-xs font-bold transition cursor-pointer">
            Tutup
        </button>
    </div>
</div>

<script>
    (function() {
        function initPresensiMap() {
            if (typeof lucide !== 'undefined') lucide.createIcons();
            var lokasi = "{{ $lokasi }}";
            if (!lokasi || typeof L === 'undefined') return;
            var lok = lokasi.split(',');
            var latitude = parseFloat(lok[0]);
            var longitude = parseFloat(lok[1]);
            if (isNaN(latitude) || isNaN(longitude)) return;

            var latitude_kantor = parseFloat("{{ $latitude }}");
            var longitude_kantor = parseFloat("{{ $longitude }}");
            var rd = parseFloat("{{ $presensi->radius_cabang }}") || 100;

            var mapId = "{{ $map_id }}";
            var container = L.DomUtil.get(mapId);
            if (container != null) {
                container._leaflet_id = null;
            }

            var map = L.map(mapId, {
                center: [latitude, longitude],
                zoom: 16,
                zoomControl: true
            });

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
                attribution: '&copy; OpenStreetMap'
            }).addTo(map);

            L.marker([latitude, longitude]).addTo(map);

            if (!isNaN(latitude_kantor) && !isNaN(longitude_kantor)) {
                L.circle([latitude_kantor, longitude_kantor], {
                    color: '#284c9a',
                    fillColor: '#284c9a',
                    fillOpacity: 0.15,
                    radius: rd
                }).addTo(map);
            }

            setTimeout(function() {
                map.invalidateSize();
            }, 250);
        }

        if (typeof L === 'undefined') {
            if (!document.getElementById('leaflet-css-v2')) {
                var link = document.createElement('link');
                link.id = 'leaflet-css-v2';
                link.rel = 'stylesheet';
                link.href = "{{ asset('assets/vendor/libs/leaflet/leaflet.css') }}?v=1.0.1";
                document.head.appendChild(link);
            }
            var script = document.createElement('script');
            script.src = "{{ asset('assets/vendor/libs/leaflet/leaflet.js') }}?v=1.0.1";
            script.onload = function() {
                setTimeout(initPresensiMap, 150);
            };
            document.head.appendChild(script);
        } else {
            setTimeout(initPresensiMap, 250);
        }
    })();
</script>
