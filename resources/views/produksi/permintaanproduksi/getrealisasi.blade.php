<div class="row g-3 mb-4">
    <div class="col-sm-6 col-md-3">
        <div class="card shadow-none border bg-light bg-opacity-50">
            <div class="card-body p-3 text-center">
                <small class="text-muted d-block mb-1">No. Permintaan</small>
                <h6 class="mb-0 fw-bold">{{ $pp->no_permintaan }}</h6>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-md-3">
        <div class="card shadow-none border bg-light bg-opacity-50">
            <div class="card-body p-3 text-center">
                <small class="text-muted d-block mb-1">Periode</small>
                <h6 class="mb-0 fw-bold">{{ $namabulan[$pp->bulan] }} {{ $pp->tahun }}</h6>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-md-3">
        <div class="card shadow-none border bg-light bg-opacity-50">
            <div class="card-body p-3 text-center">
                <small class="text-muted d-block mb-1">Unit</small>
                <h6 class="mb-0 fw-bold">DUS</h6>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-md-3">
        <div class="card shadow-none border bg-light bg-opacity-50">
            <div class="card-body p-3 text-center">
                <small class="text-muted d-block mb-1">Status</small>
                <span class="badge bg-label-success">Selesai</span>
            </div>
        </div>
    </div>
</div>

<div class="table-responsive">
    <table class="table table-hover border-top">
        <thead>
            <tr>
                <th class="ps-0">Produk</th>
                <th class="text-end">Permintaan</th>
                <th class="text-end">Realisasi</th>
                <th class="text-center" style="width: 100px;">Ratio</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($detail as $d)
                @php
                    $permintaan = $d->oman_marketing - $d->stok_gudang + $d->buffer_stok;
                    $realisasi = $d->jml_realisasi;
                    if ($permintaan != 0) {
                        $ratio = ($realisasi / $permintaan) * 100;
                    } else {
                        $ratio = 0;
                    }

                    if ($ratio < 50) {
                        $color = 'danger';
                    } elseif ($ratio < 70) {
                        $color = 'warning';
                    } elseif ($ratio < 90) {
                        $color = 'info';
                    } else {
                        $color = 'success';
                    }
                @endphp
                <tr>
                    <td class="ps-0 py-2">
                        <div class="d-flex align-items-center">
                            <div class="avatar avatar-xs me-2">
                                <span class="avatar-initial rounded bg-label-secondary"><i class="ti ti-package fs-6"></i></span>
                            </div>
                            <span class="fw-medium">{{ $d->kode_produk }}</span>
                        </div>
                    </td>
                    <td class="text-end py-2 fw-bold text-dark">{{ formatAngka($permintaan) }}</td>
                    <td class="text-end py-2 text-primary fw-bold">{{ formatAngka($realisasi) }}</td>
                    <td class="text-center py-2">
                        <div class="d-flex align-items-center justify-content-center">
                            <div class="progress w-100 me-2" style="height: 6px;">
                                <div class="progress-bar bg-{{ $color }}" role="progressbar" style="width: {{ ROUND($ratio) }}%" aria-valuenow="{{ ROUND($ratio) }}" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                            <small class="fw-bold">{{ ROUND($ratio) }}%</small>
                        </div>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

<style>
    .progress {
        background-color: #f1f0f2;
    }
</style>
