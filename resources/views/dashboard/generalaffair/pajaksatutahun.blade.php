<div class="d-flex align-items-center mb-3">
    <div class="stat-icon-wrapper bg-label-warning me-2" style="width: 38px; height: 38px; border-radius: 8px;">
        <i class="ti ti-calendar-event fs-4"></i>
    </div>
    <h5 class="mb-0 fw-bold">Pajak 1 Tahunan</h5>
</div>

<div class="nav-align-top mb-4">
    <ul class="nav nav-tabs nav-tabs-line" role="tablist">
        <li class="nav-item" role="presentation">
            <button type="button" class="nav-link active" role="tab" data-bs-toggle="tab" data-bs-target="#pjsbulanini" aria-controls="pjsbulanini"
                aria-selected="true">
                Bulan Ini
                <span class="badge rounded-pill bg-label-danger ms-2">{{ count($pajaksatutahun_bulanini) }}</span>
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#pjslewatjatuhtempo"
                aria-controls="pjslewatjatuhtempo" aria-selected="false">
                Jatuh Tempo
                <span class="badge rounded-pill bg-danger ms-2">{{ count($pajaksatutahun_lewat) }}</span>
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#pjsbulandepan" aria-controls="pjsbulandepan"
                aria-selected="false">
                Bulan Depan
                <span class="badge rounded-pill bg-label-warning ms-2">{{ count($pajaksatutahun_bulandepan) }}</span>
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#pjsduabulan" aria-controls="pjsduabulan"
                aria-selected="false">
                2 Bulan
                <span class="badge rounded-pill bg-label-success ms-2">{{ count($pajaksatutahun_duabulan) }}</span>
            </button>
        </li>
    </ul>
    <div class="tab-content shadow-none border-0 px-0">
        {{-- Bulan Ini --}}
        <div class="tab-pane fade show active" id="pjsbulanini" role="tabpanel">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-light">
                        <tr>
                            <th style="width: 50px;">No.</th>
                            <th>No. Polisi</th>
                            <th>Kendaraan</th>
                            <th>Jatuh Tempo</th>
                            <th class="text-center">Sisa Waktu</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($pajaksatutahun_bulanini as $d)
                            @php
                                $sisahari = hitungSisahari($d->jatuhtempo_pajak_satutahun);
                                $badgeColor = $sisahari < 0 ? 'bg-danger' : ($sisahari <= 7 ? 'bg-warning' : 'bg-primary');
                            @endphp
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td><span class="fw-bold">{{ $d->no_polisi }}</span></td>
                                <td><small class="text-muted d-block">{{ $d->merk }}</small>{{ $d->tipe }} {{ $d->tipe_kendaraan }}</td>
                                <td>{{ formatIndo($d->jatuhtempo_pajak_satutahun) }}</td>
                                <td class="text-center">
                                    <span class="badge {{ $badgeColor }}">{{ $sisahari }} Hari</span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-4 text-muted">Tidak ada data untuk bulan ini</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Jatuh Tempo --}}
        <div class="tab-pane fade" id="pjslewatjatuhtempo" role="tabpanel">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-light">
                        <tr>
                            <th style="width: 50px;">No.</th>
                            <th>No. Polisi</th>
                            <th>Kendaraan</th>
                            <th>Jatuh Tempo</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($pajaksatutahun_lewat as $d)
                            <tr class="table-danger">
                                <td>{{ $loop->iteration }}</td>
                                <td><span class="fw-bold text-danger">{{ $d->no_polisi }}</span></td>
                                <td><small class="text-muted d-block">{{ $d->merk }}</small>{{ $d->tipe }} {{ $d->tipe_kendaraan }}</td>
                                <td>{{ formatIndo($d->jatuhtempo_pajak_satutahun) }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center py-4 text-muted">Tidak ada data jatuh tempo</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Bulan Depan --}}
        <div class="tab-pane fade" id="pjsbulandepan" role="tabpanel">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-light">
                        <tr>
                            <th style="width: 50px;">No.</th>
                            <th>No. Polisi</th>
                            <th>Kendaraan</th>
                            <th>Jatuh Tempo</th>
                            <th class="text-center">Sisa Waktu</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($pajaksatutahun_bulandepan as $d)
                            @php
                                $sisahari = hitungSisahari($d->jatuhtempo_pajak_satutahun);
                            @endphp
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td><span class="fw-bold">{{ $d->no_polisi }}</span></td>
                                <td><small class="text-muted d-block">{{ $d->merk }}</small>{{ $d->tipe }} {{ $d->tipe_kendaraan }}</td>
                                <td>{{ formatIndo($d->jatuhtempo_pajak_satutahun) }}</td>
                                <td class="text-center">
                                    <span class="badge bg-label-warning">{{ $sisahari }} Hari</span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-4 text-muted">Tidak ada data untuk bulan depan</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- 2 Bulan --}}
        <div class="tab-pane fade" id="pjsduabulan" role="tabpanel">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-light">
                        <tr>
                            <th style="width: 50px;">No.</th>
                            <th>No. Polisi</th>
                            <th>Kendaraan</th>
                            <th>Jatuh Tempo</th>
                            <th class="text-center">Sisa Waktu</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($pajaksatutahun_duabulan as $d)
                            @php
                                $sisahari = hitungSisahari($d->jatuhtempo_pajak_satutahun);
                            @endphp
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td><span class="fw-bold">{{ $d->no_polisi }}</span></td>
                                <td><small class="text-muted d-block">{{ $d->merk }}</small>{{ $d->tipe }} {{ $d->tipe_kendaraan }}</td>
                                <td>{{ formatIndo($d->jatuhtempo_pajak_satutahun) }}</td>
                                <td class="text-center">
                                    <span class="badge bg-label-success">{{ $sisahari }} Hari</span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-4 text-muted">Tidak ada data untuk 2 bulan kedepan</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
