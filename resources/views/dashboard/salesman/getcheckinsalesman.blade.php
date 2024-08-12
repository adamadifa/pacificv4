<ul class="timeline mb-0">
    @php
        $lokasi_cabang = explode(',', $cabang->lokasi_cabang);
        $lat_start = $lokasi_cabang[0];
        $long_start = $lokasi_cabang[1];

    @endphp
    @foreach ($kunjungan as $d)
        @php
            $durasi = hitungdurasi(date('H:i', strtotime($d->checkin_time)), date('H:i', strtotime($d->checkout_time)));
            $jarak = hitungJarak($lat_start, $long_start, $d->latitude, $d->longitude);
        @endphp
        <li class="timeline-item ps-6 border-left-dashed">
            <span class="timeline-indicator-advanced timeline-indicator-primary border-0 shadow-none">
                <i class="ti ti-circle-check"></i>
            </span>
            <div class="timeline-event ps-1">
                <div class="timeline-header">
                    <small class="text-primary text-uppercase">{{ $d->kode_pelanggan }}- {{ textUpperCase($d->nama_pelanggan) }}</small>
                </div>
                <h6 class="my-50">
                    <span class="text-success">
                        <i class="ti ti-login me-2"></i>
                        {{ date('H:i', strtotime($d->checkin_time)) }}
                    </span>
                    -
                    <span class="text-danger">
                        <i class="ti ti-logout me-2"></i>
                        {{ date('H:i', strtotime($d->checkout_time)) }}
                    </span>
                </h6>
                <span class="text-info">Durasi : {{ $durasi['jam'] }} : {{ $durasi['menit'] }}</span>
                <p class="text-body mb-0">{{ textCamelCase($d->alamat_pelanggan) }}</p>
                <span class="text-info">Jarak : {{ formatAngkaDesimal($jarak / 1000) }} km</span>
            </div>
        </li>
        @php
            $lat_start = $d->latitude;
            $long_start = $d->longitude;
        @endphp
    @endforeach
</ul>
