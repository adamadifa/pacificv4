@php
    $lat_start = '';
    $long_start = '';
@endphp
@foreach ($aktifitas as $d)
    @if ($loop->first)
        @php
            $jarak = hitungjarak($lokasi[0], $lokasi[1], $d->latitude, $d->longitude);
            $totaljarak = $jarak / 1000;
            // $totalwaktu = 0;
        @endphp
    @else
        @php
            $jarak = hitungjarak($lat_start, $long_start, $d->latitude, $d->longitude);
            $totaljarak = $jarak / 1000;
            // $totalwaktu = hitungjamdesimal($start_time, $d->tanggal);
        @endphp
    @endif
    <div class="card mb-1 border border-primary p-0 shadow">
        <div class="card-body p-3">
            <div class="d-flex">
                <div class="img-thumbnail">
                    @if (!empty($d->foto))
                        @php
                            $path = Storage::url('uploads/aktifitas_smm/' . $d->foto);
                        @endphp
                        <img src="{{ url($path) }}" class="avatar avatar-md rounded-circle" alt="">
                    @else
                        <img src="{{ asset('assets/img/marker/default.png') }}" class="avatar avatar-40 rounded-circle" alt="">
                    @endif
                    {{-- <img src="{{ asset('assets/img/avatars/1.png') }}" alt="" class="w-px-50 h-auto rounded-circle"> --}}
                </div>
                <div class="detail ms-2">
                    <span class="badge bg-primary m-0"><i class="ti ti-calendar me-2"></i>{{ DateToIndo($d->tanggal) }}
                        {{ date('H:i', strtotime($d->created_at)) }}</span>
                    <span class="badge bg-success mt-1">
                        <i class="ti ti-map-pin me-1"></i> {{ $d->latitude }},{{ $d->longitude }}
                    </span>
                    <span class="badge bg-info mt-1">
                        <i class="ti ti-timeline me-1"></i> {{ formatAngka($totaljarak) }} Km
                    </span>
                    <p class="mt-2">{{ $d->keterangan }}</p>
                </div>
            </div>
        </div>
    </div>
    @php
        $lat_start = $d->latitude;
        $long_start = $d->longitude;
    @endphp
@endforeach
