<style>
    /* Premium Compact Unified Design */
    .modal-body-custom {
        padding: 0 !important;
    }

    .nav-tabs-premium {
        background-color: #f8f9fa;
        border-bottom: none;
        padding: 0;
        display: flex;
        gap: 0;
    }

    .nav-tabs-premium .nav-item:first-child .nav-link {
        border-top-left-radius: 0; /* Keep it clean with modal edges early on */
    }

    .nav-tabs-premium .nav-link {
        border: none;
        color: #718096;
        font-weight: 600;
        padding: 12px 25px;
        border-radius: 0;
        transition: all 0.2s ease;
        font-size: 0.85rem;
        margin: 0;
    }

    .nav-tabs-premium .nav-link:hover {
        background-color: #edf2f7;
        color: #2d3748;
    }

    .nav-tabs-premium .nav-link.active {
        background-color: #002e65;
        color: #ffffff !important;
    }

    .unified-container {
        border: none;
        overflow: hidden;
        margin: 0;
        border-radius: 0 0 15px 15px; /* Bottom rounded */
    }

    .unified-header {
        background-color: #002e65;
        color: white;
        padding: 12px 20px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        /* Remove border-radius here to let the table header handle the look if needed,
           but usually, we want the whole top of the content area to feel unified */
    }

    /* Compact Table Design */
    .table-interesting {
        margin-bottom: 0;
        width: 100%;
        border-collapse: collapse;
    }

    .table-interesting thead th {
        background-color: #002e65;
        color: #ffffff !important; /* Pure white for visibility */
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 0.8px;
        padding: 12px 15px;
        border: none;
        font-weight: 700;
        white-space: nowrap;
    }

    .table-interesting tbody tr {
        border-bottom: 1px solid #f1f5f9;
        transition: background-color 0.15s;
    }

    .table-interesting tbody tr:last-child {
        border-bottom: none;
    }

    .table-interesting tbody tr:hover {
        background-color: #f8fafc;
    }

    .table-interesting td {
        padding: 10px 15px;
        vertical-align: middle;
        white-space: nowrap;
    }

    /* Action Buttons - Compact */
    .btn-action-modern {
        width: 32px;
        height: 32px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 8px;
        transition: all 0.2s;
        border: none;
        font-size: 0.9rem;
    }

    .btn-action-modern:hover {
        transform: scale(1.1);
        box-shadow: 0 2px 5px rgba(0,0,0,0.1);
    }

    .btn-masuk { background-color: #10b981; color: white; }
    .btn-pulang { background-color: #f43f5e; color: white; }

    /* Compact Badges */
    .badge-modern {
        padding: 5px 12px;
        border-radius: 6px;
        font-weight: 600;
        font-size: 0.72rem;
    }

    .empty-state-modern {
        padding: 50px 20px;
        text-align: center;
    }

    .text-val {
        font-size: 0.92rem;
        font-weight: 600;
    }

    .text-sub {
        font-size: 0.75rem;
        color: #718096;
    }
</style>

<div class="modal-body-custom">
    <!-- Tab Navigation -->
    <ul class="nav nav-tabs nav-tabs-premium" id="machineTabs" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="mesin1-tab" data-bs-toggle="tab" data-bs-target="#mesin1" type="button" role="tab">
                <i class="ti ti-cloud me-1"></i> Mesin 1
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="mesin2-tab" data-bs-toggle="tab" data-bs-target="#mesin2" type="button" role="tab">
                <i class="ti ti-cloud me-1"></i> Mesin 2
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="adms-tab" data-bs-toggle="tab" data-bs-target="#adms" type="button" role="tab">
                <i class="ti ti-device-laptop me-1"></i> Log ADMS
            </button>
        </li>
    </ul>

    <!-- Tab Content -->
    <div class="tab-content" id="machineTabsContent">
        <!-- Mesin 1 -->
        <div class="tab-pane fade show active" id="mesin1" role="tabpanel">
            <div class="unified-container shadow-sm">
                <div class="unified-header">
                    <div class="d-flex align-items-center gap-3">
                        <span class="badge bg-white text-primary fw-bold" style="font-size: 0.65rem">CLOUD ID</span>
                        <span class="text-white fw-medium small">C2609075E3170B2C</span>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-interesting">
                        <thead>
                            <tr>
                                <th>PIN</th>
                                <th>Status</th>
                                <th>Waktu Scan</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($filtered_array as $d)
                                <tr>
                                    <td><span class="text-val">{{ $d->pin }}</span></td>
                                    <td>
                                        <span class="badge {{ $d->status_scan % 2 == 0 ? 'bg-label-success' : 'bg-label-danger' }} badge-modern">
                                            {{ $d->status_scan % 2 == 0 ? 'IN' : 'OUT' }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="text-val">{{ date('H:i:s', strtotime($d->scan_date)) }}</span>
                                        <span class="text-sub ms-2">{{ date('d/m/y', strtotime($d->scan_date)) }}</span>
                                    </td>
                                    <td>
                                        <div class="d-flex justify-content-center gap-2">
                                            <form method="POST" action="{{ route('presensi.updatefrommachine', [Crypt::encrypt($d->pin), 0]) }}">
                                                @csrf
                                                <input type="hidden" name="scan_date" value="{{ date('Y-m-d H:i:s', strtotime($d->scan_date)) }}">
                                                <button class="btn-action-modern btn-masuk" title="Set Masuk"><i class="ti ti-login"></i></button>
                                            </form>
                                            <form method="POST" action="{{ route('presensi.updatefrommachine', [Crypt::encrypt($d->pin), 1]) }}">
                                                @csrf
                                                <input type="hidden" name="scan_date" value="{{ date('Y-m-d H:i:s', strtotime($d->scan_date)) }}">
                                                <button class="btn-action-modern btn-pulang" title="Set Pulang"><i class="ti ti-logout"></i></button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="empty-state-modern"><span class="text-muted small">Data tidak ditemukan</span></td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Mesin 2 -->
        <div class="tab-pane fade" id="mesin2" role="tabpanel">
            <div class="unified-container shadow-sm">
                <div class="unified-header">
                    <div class="d-flex align-items-center gap-3">
                        <span class="badge bg-white text-primary fw-bold" style="font-size: 0.65rem">CLOUD ID</span>
                        <span class="text-white fw-medium small">C268909557211236</span>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-interesting">
                        <thead>
                            <tr>
                                <th>PIN</th>
                                <th>Status</th>
                                <th>Waktu Scan</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($filtered_array_2 as $d)
                                <tr>
                                    <td><span class="text-val">{{ $d->pin }}</span></td>
                                    <td>
                                        <span class="badge {{ $d->status_scan % 2 == 0 ? 'bg-label-success' : 'bg-label-danger' }} badge-modern">
                                            {{ $d->status_scan % 2 == 0 ? 'IN' : 'OUT' }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="text-val">{{ date('H:i:s', strtotime($d->scan_date)) }}</span>
                                        <span class="text-sub ms-2">{{ date('d/m/y', strtotime($d->scan_date)) }}</span>
                                    </td>
                                    <td>
                                        <div class="d-flex justify-content-center gap-2">
                                            <form method="POST" action="{{ route('presensi.updatefrommachine', [Crypt::encrypt($d->pin), 0]) }}">
                                                @csrf
                                                <input type="hidden" name="scan_date" value="{{ date('Y-m-d H:i:s', strtotime($d->scan_date)) }}">
                                                <button class="btn-action-modern btn-masuk" title="Set Masuk"><i class="ti ti-login"></i></button>
                                            </form>
                                            <form method="POST" action="{{ route('presensi.updatefrommachine', [Crypt::encrypt($d->pin), 1]) }}">
                                                @csrf
                                                <input type="hidden" name="scan_date" value="{{ date('Y-m-d H:i:s', strtotime($d->scan_date)) }}">
                                                <button class="btn-action-modern btn-pulang" title="Set Pulang"><i class="ti ti-logout"></i></button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="empty-state-modern"><span class="text-muted small">Data tidak ditemukan</span></td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Log ADMS -->
        <div class="tab-pane fade" id="adms" role="tabpanel">
            <div class="unified-container shadow-sm">
                <div class="unified-header">
                    <div class="d-flex align-items-center gap-2">
                        <span class="badge bg-white text-primary fw-bold" style="font-size: 0.65rem">ADMS LIVE LOG</span>
                        <span class="text-white fw-medium small">Data real-time dari mesin terdaftar</span>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-interesting">
                        <thead>
                            <tr>
                                <th>PIN</th>
                                <th>Mesin</th>
                                <th>Scan</th>
                                <th>Keterangan</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($log as $l)
                                <tr>
                                    <td><span class="text-val">{{ $l->pin }}</span></td>
                                    <td>
                                        <span class="text-val text-primary" style="font-size: 0.85rem">{{ $l->nama_mesin ?: 'Mesin ADMS' }}</span>
                                        <small class="text-muted ms-1" style="font-size: 10px">{{ $l->id_mesin }}</small>
                                    </td>
                                    <td>
                                        <span class="text-val">{{ date('H:i:s', strtotime($l->jam_absen)) }}</span>
                                        <span class="badge-modern {{ $l->status_scan % 2 == 0 ? 'text-success bg-label-success' : 'text-danger bg-label-danger' }} ms-1">
                                            {{ $l->status_scan % 2 == 0 ? 'IN' : 'OUT' }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge {{ $l->status == 1 ? 'bg-success' : 'bg-danger' }} badge-modern">
                                            {{ $l->keterangan }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="d-flex justify-content-center gap-1">
                                            <form method="POST" action="{{ route('presensi.updatefrommachine', [Crypt::encrypt($l->pin), 0]) }}">
                                                @csrf
                                                <input type="hidden" name="scan_date" value="{{ date('Y-m-d H:i:s', strtotime($l->jam_absen)) }}">
                                                <button class="btn-action-modern btn-masuk"><i class="ti ti-login"></i></button>
                                            </form>
                                            <form method="POST" action="{{ route('presensi.updatefrommachine', [Crypt::encrypt($l->pin), 1]) }}">
                                                @csrf
                                                <input type="hidden" name="scan_date" value="{{ date('Y-m-d H:i:s', strtotime($l->jam_absen)) }}">
                                                <button class="btn-action-modern btn-pulang"><i class="ti ti-logout"></i></button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="empty-state-modern"><span class="text-muted small">Data tidak ditemukan</span></td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
