<div class="d-flex align-items-center mb-3">
    <div class="stat-icon-wrapper bg-label-info me-2" style="width: 38px; height: 38px; border-radius: 8px;">
        <i class="ti ti-certificate fs-4"></i>
    </div>
    <h5 class="mb-0 fw-bold">KIR Kendaraan</h5>
</div>

<div class="nav-align-top mb-4">
    <ul class="nav nav-tabs nav-tabs-line" role="tablist">
        <li class="nav-item" role="presentation">
            <button type="button" class="nav-link active" role="tab" data-bs-toggle="tab" data-bs-target="#bulanini" aria-controls="bulanini"
                aria-selected="true">
                Bulan Ini
                <span class="badge rounded-pill bg-label-danger ms-2">{{ count($kir_bulanini) }}</span>
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#lewatjatuhtempo" aria-controls="lewatjatuhtempo"
                aria-selected="false">
                Jatuh Tempo
                <span class="badge rounded-pill bg-danger ms-2">{{ count($kir_lewat) }}</span>
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#bulandepan" aria-controls="bulandepan"
                aria-selected="false">
                Bulan Depan
                <span class="badge rounded-pill bg-label-warning ms-2">{{ count($kir_bulandepan) }}</span>
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#duabulan" aria-controls="duabulan"
                aria-selected="false">
                2 Bulan
                <span class="badge rounded-pill bg-label-success ms-2">{{ count($kir_duabulan) }}</span>
            </button>
        </li>
    </ul>
    <div class="tab-content shadow-none border-0 px-0">
        {{-- Bulan Ini --}}
        <div class="tab-pane fade show active" id="bulanini" role="tabpanel">
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
                        @forelse ($kir_bulanini as $d)
                            @php
                                $sisahari = hitungSisahari($d->jatuhtempo_kir);
                                $badgeColor = $sisahari < 0 ? 'bg-danger' : ($sisahari <= 7 ? 'bg-warning' : 'bg-primary');
                            @endphp
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td><span class="fw-bold">{{ $d->no_polisi }}</span></td>
                                <td><small class="text-muted d-block">{{ $d->merk }}</small>{{ $d->tipe }} {{ $d->tipe_kendaraan }}</td>
                                <td>{{ formatIndo($d->jatuhtempo_kir) }}</td>
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
        <div class="tab-pane fade" id="lewatjatuhtempo" role="tabpanel">
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
                        @forelse ($kir_lewat as $d)
                            <tr class="table-danger">
                                <td>{{ $loop->iteration }}</td>
                                <td><span class="fw-bold text-danger">{{ $d->no_polisi }}</span></td>
                                <td><small class="text-muted d-block">{{ $d->merk }}</small>{{ $d->tipe }} {{ $d->tipe_kendaraan }}</td>
                                <td>{{ formatIndo($d->jatuhtempo_kir) }}</td>
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
        <div class="tab-pane fade" id="bulandepan" role="tabpanel">
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
                        @forelse ($kir_bulandepan as $d)
                            @php
                                $sisahari = hitungSisahari($d->jatuhtempo_kir);
                            @endphp
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td><span class="fw-bold">{{ $d->no_polisi }}</span></td>
                                <td><small class="text-muted d-block">{{ $d->merk }}</small>{{ $d->tipe }} {{ $d->tipe_kendaraan }}</td>
                                <td>{{ formatIndo($d->jatuhtempo_kir) }}</td>
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
        <div class="tab-pane fade" id="duabulan" role="tabpanel">
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
                        @forelse ($kir_duabulan as $d)
                            @php
                                $sisahari = hitungSisahari($d->jatuhtempo_kir);
                            @endphp
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td><span class="fw-bold">{{ $d->no_polisi }}</span></td>
                                <td><small class="text-muted d-block">{{ $d->merk }}</small>{{ $d->tipe }} {{ $d->tipe_kendaraan }}</td>
                                <td>{{ formatIndo($d->jatuhtempo_kir) }}</td>
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
