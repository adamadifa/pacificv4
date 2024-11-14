<div class="row">
    <div class="col">
        <table class="table">
            <tr>
                <th>Nama</th>
                <td>{{ $user->name }}</td>
            </tr>
            <tr>
                <th>Cabang</th>
                <td>{{ $user->kode_cabang }}</td>
            </tr>
        </table>
    </div>
</div>
<div class="row mt-2">
    <div class="col">
        <table class="table table-bordered">
            <thead class="table-dark">
                <tr>
                    <th>No.</th>
                    <th>Waktu</th>
                    <th>Keterangan</th>
                    <th>Foto</th>
                    <th>Jarak</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $lokasi = explode(',', $user->lokasi_cabang);
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
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ !empty($d->created_at) ? date('H:i', strtotime($d->created_at)) : '' }}</td>
                        <td>{{ $d->keterangan }}</td>
                        <td>
                            @if (!empty($d->foto))
                                @if (Storage::disk('public')->exists('/uploads/aktifitas_smm/' . $d->foto))
                                    <div class="avatar avatar-xs me-2">
                                        <a href="{{ getfotoAktifitias($d->foto) }}" target="_blank">
                                            <img src="{{ getfotoAktifitias($d->foto) }}" alt="" class="rounded-circle">
                                        </a>
                                    </div>
                                @else
                                    <div class="avatar avatar-xs me-2">
                                        <img src="{{ asset('assets/img/avatars/No_Image_Available.jpg') }}" alt="" class="rounded-circle">
                                    </div>
                                @endif
                            @else
                                <div class="avatar avatar-xs me-2">
                                    <img src="{{ asset('assets/img/avatars/No_Image_Available.jpg') }}" alt="" class="rounded-circle">
                                </div>
                            @endif

                        </td>
                        <td>{{ formatAngka($totaljarak) }} Km</td>
                    </tr>
                    @php
                        $lat_start = $d->latitude;
                        $long_start = $d->longitude;
                    @endphp
                @endforeach
            </tbody>
        </table>

    </div>
</div>
