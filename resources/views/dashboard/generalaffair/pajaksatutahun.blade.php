<h4>Pajak 1 Tahun</h4>
<div class="nav-align-top nav-tabs-shadow mb-4">
    <ul class="nav nav-tabs" role="tablist">
        <li class="nav-item" role="presentation">
            <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#pjslewatjatuhtempo"
                aria-controls="pjslewatjatuhtempo" aria-selected="true">
                Jatuh Tempo
                <span class="badge rounded-pill badge-center h-px-20 w-px-20 bg-label-danger ms-1">
                    {{ count($pajaksatutahun_lewat) }}
                </span>
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button type="button" class="nav-link active" role="tab" data-bs-toggle="tab" data-bs-target="#pjsbulanini"
                aria-controls="pjsbulanini" aria-selected="false" tabindex="-1">
                Bulan Ini
                <span class="badge rounded-pill badge-center h-px-20 w-px-20 bg-label-danger ms-1">
                    {{ count($pajaksatutahun_bulanini) }}
                </span>
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#pjsbulandepan" aria-controls="pjsbulandepan"
                aria-selected="false" tabindex="-1">
                Bulan Depan
                <span class="badge rounded-pill badge-center h-px-20 w-px-20 bg-label-warning ms-1">
                    {{ count($pajaksatutahun_bulandepan) }}
                </span>
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#pjsduabulan" aria-controls="pjsduabulan"
                aria-selected="false" tabindex="-1">
                2 Bulan
                <span class="badge rounded-pill badge-center h-px-20 w-px-20 bg-label-success ms-1">
                    {{ count($pajaksatutahun_duabulan) }}
                </span>
            </button>
        </li>
    </ul>
    <div class="tab-content">
        <div class="tab-pane fade " id="pjslewatjatuhtempo" role="tabpanel">
            <table class="table table-bordered table-striped">
                <thead class="table-dark">
                    <tr>
                        <th>No.</th>
                        <th>No. Polisi</th>
                        <th>Kendaraan</th>
                        <th>Jatuh Tempo</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($pajaksatutahun_lewat as $d)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $d->no_polisi }}</td>
                            <td>{{ $d->merk }} {{ $d->tipe }} {{ $d->tipe_kendaraan }}</td>
                            <td>{{ formatIndo($d->jatuhtempo_pajak_satutahun) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="tab-pane fade show active" id="pjsbulanini" role="tabpanel">
            <table class="table table-bordered">
                <thead class="table-dark">
                    <tr>
                        <th>No.</th>
                        <th>No. Polisi</th>
                        <th>Kendaraan</th>
                        <th>Jatuh Tempo</th>
                        <th class="text-center">Sisa Waktu</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($pajaksatutahun_bulanini as $d)
                        @php
                            $sisahari = hitungSisahari($d->jatuhtempo_pajak_satutahun);
                            $color = $sisahari < 0 ? 'bg-danger text-white' : '';
                        @endphp
                        <tr class="{{ $color }}">
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $d->no_polisi }}</td>
                            <td>{{ $d->merk }} {{ $d->tipe }} {{ $d->tipe_kendaraan }}</td>
                            <td>{{ formatIndo($d->jatuhtempo_pajak_satutahun) }}</td>
                            <td class="text-center">{{ $sisahari }} Hari</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="tab-pane fade" id="pjsbulandepan" role="tabpanel">
            <table class="table table-bordered">
                <thead class="table-dark">
                    <tr>
                        <th>No.</th>
                        <th>No. Polisi</th>
                        <th>Kendaraan</th>
                        <th>Jatuh Tempo</th>
                        <th class="text-center">Sisa Waktu</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($pajaksatutahun_bulandepan as $d)
                        @php
                            $sisahari = hitungSisahari($d->jatuhtempo_pajak_satutahun);
                            $color = $sisahari < 0 ? 'bg-danger text-white' : '';
                        @endphp
                        <tr class="{{ $color }}">
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $d->no_polisi }}</td>
                            <td>{{ $d->merk }} {{ $d->tipe }} {{ $d->tipe_kendaraan }}</td>
                            <td>{{ formatIndo($d->jatuhtempo_pajak_satutahun) }}</td>
                            <td class="text-center">{{ $sisahari }} Hari</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="tab-pane fade" id="pjsduabulan" role="tabpanel">
            <table class="table table-bordered">
                <thead class="table-dark">
                    <tr>
                        <th>No.</th>
                        <th>No. Polisi</th>
                        <th>Kendaraan</th>
                        <th>Jatuh Tempo</th>
                        <th class="text-center">Sisa Waktu</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($pajaksatutahun_duabulan as $d)
                        @php
                            $sisahari = hitungSisahari($d->jatuhtempo_pajak_satutahun);
                            $color = $sisahari < 0 ? 'bg-danger text-white' : '';
                        @endphp
                        <tr class="{{ $color }}">
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $d->no_polisi }}</td>
                            <td>{{ $d->merk }} {{ $d->tipe }} {{ $d->tipe_kendaraan }}</td>
                            <td>{{ formatIndo($d->jatuhtempo_pajak_satutahun) }}</td>
                            <td class="text-center">{{ $sisahari }} Hari</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
