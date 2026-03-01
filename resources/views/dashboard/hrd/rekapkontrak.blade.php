<style>
    .contract-card {
        transition: all 0.3s ease;
        border: 1px solid rgba(0, 0, 0, 0.08);
        border-radius: 12px;
        overflow: hidden;
    }

    .contract-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1) !important;
        border-color: #002e65;
    }

    .contract-info-label {
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: #888;
        margin-bottom: 2px;
        display: block;
    }

    .contract-info-value {
        font-weight: 600;
        color: #333;
        font-size: 0.9rem;
    }

    .contract-status-badge {
        padding: 5px 12px;
        border-radius: 6px;
        font-weight: 600;
        font-size: 0.75rem;
    }

    .nav-tabs-custom .nav-link {
        border: none;
        border-bottom: 3px solid transparent;
        color: #666;
        font-weight: 500;
        padding: 1rem 1.5rem;
    }

    .nav-tabs-custom .nav-link.active {
        background: transparent;
        border-bottom-color: #002e65;
        color: #002e65;
    }
</style>

<div class="d-flex align-items-center mb-3">
    <div class="avatar me-2">
        <span class="avatar-initial rounded bg-label-primary">
            <i class="ti ti-file-certificate fs-4"></i>
        </span>
    </div>
    <h4 class="mb-0">Karyawan Habis Kontrak</h4>
</div>

<div class="nav-align-top mb-4">
    <ul class="nav nav-tabs nav-tabs-custom" role="tablist">
        <li class="nav-item" role="presentation">
            <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#lewatjatuhtempo">
                Jatuh Tempo
                <span class="badge rounded-pill bg-danger ms-2">{{ count($kontrak_lewat) }}</span>
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button type="button" class="nav-link active" role="tab" data-bs-toggle="tab" data-bs-target="#bulanini">
                Bulan Ini
                <span class="badge rounded-pill bg-primary ms-2">{{ count($kontrak_bulanini) }}</span>
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#bulandepan">
                Bulan Depan
                <span class="badge rounded-pill bg-warning ms-2">{{ count($kontrak_bulandepan) }}</span>
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#duabulan">
                2 Bulan Ke Depan
                <span class="badge rounded-pill bg-success ms-2">{{ count($kontrak_duabulan) }}</span>
            </button>
        </li>
    </ul>

    <div class="tab-content bg-transparent p-0 shadow-none border-0 mt-3">
        @php
            $tabs = [
                'lewatjatuhtempo' => $kontrak_lewat,
                'bulanini' => $kontrak_bulanini,
                'bulandepan' => $kontrak_bulandepan,
                'duabulan' => $kontrak_duabulan,
            ];
        @endphp

        @foreach ($tabs as $id => $data)
            <div class="tab-pane fade {{ $id == 'bulanini' ? 'show active' : '' }}" id="{{ $id }}" role="tabpanel">
                <div class="row g-3">
                    @if (count($data) > 0)
                        @foreach ($data as $d)
                            @php
                                $sisahari = hitungSisahari($d->sampai);
                                if ($sisahari <= 7) {
                                    $badgeClass = 'bg-label-danger';
                                    $borderClass = 'border-left-danger';
                                } elseif ($sisahari <= 30) {
                                    $badgeClass = 'bg-label-warning';
                                    $borderClass = 'border-left-warning';
                                } else {
                                    $badgeClass = 'bg-label-success';
                                    $borderClass = 'border-left-success';
                                }
                            @endphp
                            <div class="col-12">
                                <div class="card contract-card bg-white shadow-sm h-100 {{ $borderClass }}" style="border-left: 5px solid !important;">
                                    <div class="card-body p-3">
                                        <div class="row align-items-center">
                                            <div class="col-lg-4 col-md-12 mb-lg-0 mb-3 border-end">
                                                <div class="d-flex align-items-center">
                                                    <div class="avatar avatar-md me-3">
                                                        <span class="avatar-initial rounded-circle bg-label-secondary">
                                                            <i class="ti ti-user fs-3"></i>
                                                        </span>
                                                    </div>
                                                    <div>
                                                        <h6 class="mb-0 fw-bold">{{ formatName($d->nama_karyawan) }}</h6>
                                                        <span class="text-muted small">{{ $d->nik }}</span>
                                                        <div class="mt-1">
                                                            <span class="badge bg-label-info p-1 px-2" style="font-size: 0.65rem">
                                                                <i class="ti ti-hash me-1" style="font-size: 0.7rem"></i>{{ $d->no_kontrak }}
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-3 col-md-6 mb-md-0 mb-3 border-end">
                                                <div class="ps-lg-3">
                                                    <span class="contract-info-label">Jabatan & Dept</span>
                                                    <div class="contract-info-value">{{ singkatString($d->nama_jabatan) }}</div>
                                                    <div class="text-muted small">{{ $d->kode_dept }}</div>
                                                </div>
                                            </div>
                                            <div class="col-lg-2 col-md-6 mb-md-0 mb-3 border-end">
                                                <div class="ps-lg-3">
                                                    <span class="contract-info-label">Cabang</span>
                                                    <div class="contract-info-value">{{ textupperCase($d->nama_cabang) }}</div>
                                                </div>
                                            </div>
                                            <div class="col-lg-3 col-md-12 text-lg-end">
                                                <div class="pe-lg-3">
                                                    <span class="contract-info-label">Berakhir Pada</span>
                                                    <div class="contract-info-value mb-1">{{ formatIndo($d->sampai) }}</div>
                                                    <span class="badge {{ $badgeClass }} contract-status-badge">
                                                        <i class="ti ti-clock-hour-4 me-1"></i>
                                                        {{ $sisahari < 0 ? abs($sisahari) . ' Hari Lewat' : $sisahari . ' Hari Lagi' }}
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="col-12">
                            <div class="card bg-label-secondary border-0 shadow-none">
                                <div class="card-body text-center py-4">
                                    <i class="ti ti-info-circle fs-1 mb-2"></i>
                                    <p class="mb-0 fw-medium">Tidak ada data karyawan habis kontrak untuk periode ini.</p>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        @endforeach
    </div>
</div>
