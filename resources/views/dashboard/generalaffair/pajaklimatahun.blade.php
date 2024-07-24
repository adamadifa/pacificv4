<h4>Pajak 5 Tahun</h4>
<div class="nav-align-top nav-tabs-shadow mb-4">
    <ul class="nav nav-tabs" role="tablist">
        <li class="nav-item" role="presentation">
            <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#pjllewatjatuhtempo"
                aria-controls="pjllewatjatuhtempo" aria-selected="true">
                Jatuh Tempo
                <span class="badge rounded-pill badge-center h-px-20 w-px-20 bg-label-danger ms-1">
                    {{ count($pajaklimatahun_lewat) }}
                </span>
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button type="button" class="nav-link active" role="tab" data-bs-toggle="tab" data-bs-target="#pjlbulanini"
                aria-controls="pjlbulanini" aria-selected="false" tabindex="-1">
                Bulan Ini
                <span class="badge rounded-pill badge-center h-px-20 w-px-20 bg-label-danger ms-1">
                    {{ count($pajaklimatahun_bulanini) }}
                </span>
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#pjlbulandepan" aria-controls="pjlbulandepan"
                aria-selected="false" tabindex="-1">
                Bulan Depan
                <span class="badge rounded-pill badge-center h-px-20 w-px-20 bg-label-warning ms-1">
                    {{ count($pajaklimatahun_bulandepan) }}
                </span>
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#pjlduabulan" aria-controls="pjlduabulan"
                aria-selected="false" tabindex="-1">
                2 Bulan
                <span class="badge rounded-pill badge-center h-px-20 w-px-20 bg-label-success ms-1">
                    {{ count($pajaklimatahun_duabulan) }}
                </span>
            </button>
        </li>
    </ul>
    <div class="tab-content">
        <div class="tab-pane fade " id="pjllewatjatuhtempo" role="tabpanel">
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
                    @foreach ($pajaklimatahun_lewat as $d)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $d->no_polisi }}</td>
                            <td>{{ $d->merk }} {{ $d->tipe }} {{ $d->tipe_kendaraan }}</td>
                            <td>{{ formatIndo($d->jatuhtempo_pajak_limatahun) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="tab-pane fade show active" id="pjlbulanini" role="tabpanel">
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
                    @foreach ($pajaklimatahun_bulanini as $d)
                        @php
                            $sisahari = hitungSisahari($d->jatuhtempo_pajak_limatahun);
                            $color = $sisahari < 0 ? 'bg-danger text-white' : '';
                        @endphp
                        <tr class="{{ $color }}">
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $d->no_polisi }}</td>
                            <td>{{ $d->merk }} {{ $d->tipe }} {{ $d->tipe_kendaraan }}</td>
                            <td>{{ formatIndo($d->jatuhtempo_pajak_limatahun) }}</td>
                            <td class="text-center">{{ $sisahari }} Hari</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="tab-pane fade" id="pjlbulandepan" role="tabpanel">
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
                    @foreach ($pajaklimatahun_bulandepan as $d)
                        @php
                            $sisahari = hitungSisahari($d->jatuhtempo_pajak_limatahun);
                            $color = $sisahari < 0 ? 'bg-danger text-white' : '';
                        @endphp
                        <tr class="{{ $color }}">
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $d->no_polisi }}</td>
                            <td>{{ $d->merk }} {{ $d->tipe }} {{ $d->tipe_kendaraan }}</td>
                            <td>{{ formatIndo($d->jatuhtempo_pajak_limatahun) }}</td>
                            <td class="text-center">{{ $sisahari }} Hari</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="tab-pane fade" id="pjlduabulan" role="tabpanel">
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
                    @foreach ($pajaklimatahun_duabulan as $d)
                        @php
                            $sisahari = hitungSisahari($d->jatuhtempo_pajak_limatahun);
                            $color = $sisahari < 0 ? 'bg-danger text-white' : '';
                        @endphp
                        <tr class="{{ $color }}">
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $d->no_polisi }}</td>
                            <td>{{ $d->merk }} {{ $d->tipe }} {{ $d->tipe_kendaraan }}</td>
                            <td>{{ formatIndo($d->jatuhtempo_pajak_limatahun) }}</td>
                            <td class="text-center">{{ $sisahari }} Hari</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
