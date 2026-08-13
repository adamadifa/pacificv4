<style>
    .evaluation-card {
        transition: all 0.3s ease;
        border: 1px solid rgba(0, 0, 0, 0.08);
        border-radius: 12px;
        overflow: hidden;
    }

    .evaluation-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1) !important;
        border-color: #ff9f43;
    }
</style>

<div class="d-flex align-items-center mb-3">
    <div class="avatar me-2">
        <span class="avatar-initial rounded bg-label-warning">
            <i class="ti ti-clipboard-check fs-4"></i>
        </span>
    </div>
    <h4 class="mb-0">Karyawan Belum Penilaian</h4>
</div>

<div class="row g-3">
    @if (count($kontrak_belum_penilaian) > 0)
        @foreach ($kontrak_belum_penilaian as $d)
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
                <div class="card evaluation-card bg-white shadow-sm h-100 {{ $borderClass }}" style="border-left: 5px solid !important;">
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
                                <div class="pe-lg-3 d-flex flex-column align-items-lg-end align-items-start justify-content-between h-100">
                                    <div class="mb-2 text-lg-end text-start">
                                        <span class="contract-info-label">Habis Kontrak</span>
                                        <div class="contract-info-value mb-1">{{ formatIndo($d->sampai) }}</div>
                                        <span class="badge {{ $badgeClass }} contract-status-badge">
                                            <i class="ti ti-clock-hour-4 me-1"></i>
                                            {{ $sisahari < 0 ? abs($sisahari) . ' Hari Lewat' : $sisahari . ' Hari Lagi' }}
                                        </span>
                                    </div>
                                    @can('penilaiankaryawan.create')
                                        <a href="{{ route('penilaiankaryawan.index') }}" class="btn btn-warning btn-sm waves-effect waves-light mt-1">
                                            <i class="ti ti-clipboard-check me-1 fs-6"></i> Input Penilaian
                                        </a>
                                    @endcan
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
                    <i class="ti ti-circle-check fs-1 mb-2 text-success"></i>
                    <p class="mb-0 fw-medium">Semua karyawan yang akan habis kontrak dalam 2 bulan sudah dinilai.</p>
                </div>
            </div>
        </div>
    @endif
</div>
