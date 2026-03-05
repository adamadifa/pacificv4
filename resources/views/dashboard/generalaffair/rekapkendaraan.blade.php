<ul class="list-group list-group-flush">
    @php
        $total_semua = 0;
    @endphp
    @foreach ($rekapkendaraan as $d)
        @php
            $total_semua += $d->total;
        @endphp
        <li class="list-group-item d-flex justify-content-between align-items-center px-4 py-3 border-bottom-soft">
            <div class="d-flex align-items-center">
                <div class="branch-avatar me-3">
                    <i class="ti ti-map-pin text-primary"></i>
                </div>
                <div>
                    <h6 class="mb-0 fw-bold text-uppercase" style="font-size: 0.85rem; letter-spacing: 0.5px;">{{ $d->nama_cabang }}</h6>
                    <small class="text-muted" style="font-size: 0.75rem;">Area Distributor</small>
                </div>
            </div>
            <div class="text-end">
                <span class="badge bg-label-primary rounded-pill px-3">{{ $d->total }} Unit</span>
            </div>
        </li>
    @endforeach
</ul>
<div class="card-footer bg-light border-0 px-4 py-3">
    <div class="d-flex justify-content-between align-items-center">
        <div class="d-flex align-items-center">
            <div class="avatar avatar-sm me-3">
                <span class="avatar-initial rounded bg-primary shadow-sm"><i class="ti ti-sum"></i></span>
            </div>
            <h6 class="mb-0 fw-bold text-dark">TOTAL KENDARAAN</h6>
        </div>
        <div class="text-end">
            <h5 class="mb-0 fw-bold text-primary">{{ number_format($total_semua) }} <small class="text-muted fw-normal" style="font-size: 0.75rem;">Unit</small></h5>
        </div>
    </div>
</div>

<style>
    .branch-avatar {
        width: 32px;
        height: 32px;
        background: rgba(115, 103, 240, 0.08);
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .border-bottom-soft {
        border-bottom: 1px solid rgba(0, 0, 0, 0.05) !important;
    }
    .list-group-item:last-child {
        border-bottom: 0 !important;
    }
    .list-group-item {
        transition: background-color 0.2s ease;
    }
    .list-group-item:hover {
        background-color: rgba(0, 0, 0, 0.01);
    }
</style>
