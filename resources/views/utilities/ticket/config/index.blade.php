@extends('layouts.app')
@section('titlepage', 'Config Approval Tiket')

@section('content')
@section('navigasi')
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h4 class="mb-0">Config Approval Tiket</h4>
            <small class="text-muted">Kelola hierarki dan alur persetujuan tiket sesuai departemen dan cabang.</small>
        </div>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0" style="font-size: 13px">
                <li class="breadcrumb-item">
                    <a href="#"><i class="ti ti-folder me-1"></i>Utilities</a>
                </li>
                <li class="breadcrumb-item">
                    <a href="{{ route('ticket.index') }}">Tiket</a>
                </li>
                <li class="breadcrumb-item active"><i class="ti ti-settings me-1"></i>Config Approval</li>
            </ol>
        </nav>
    </div>
@endsection

<style>
    .config-card {
        border: none;
        border-radius: 12px;
        transition: all 0.2s ease-in-out;
        background: #fff;
        box-shadow: 0 2px 15px rgba(0, 0, 0, 0.05);
        margin-bottom: 1rem;
    }
    .config-card:hover {
        transform: translateX(4px);
        box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
    }
    .card-body-compact { padding: 1rem 1.5rem; }
    .config-info-item { display: flex; flex-direction: column; }
    .info-label-sm {
        font-size: 10px; font-weight: 700; color: #9e9e9e;
        text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 2px;
    }
    .info-value-sm { font-size: 13px; color: #424242; font-weight: 600; }
    .approval-pipeline-compact {
        display: flex; align-items: center; flex-wrap: wrap; gap: 6px;
        background: #f8f9fa; padding: 8px 12px;
        border-radius: 8px; border: 1px solid #edf2f7;
    }
    .role-badge-sm {
        padding: 4px 10px; background: #fff; color: #3f6791;
        border-radius: 6px; font-size: 11px; font-weight: 700;
        border: 1px solid rgba(63, 103, 145, 0.15);
        box-shadow: 0 1px 3px rgba(0,0,0,0.02);
    }
    .pipeline-arrow-sm { color: #cbd5e0; font-size: 12px; }
    .action-buttons-compact { display: flex; gap: 8px; }
    .btn-action-sm {
        width: 32px; height: 32px; display: flex; align-items: center;
        justify-content: center; border-radius: 8px;
        transition: all 0.2s; border: none; font-size: 14px;
    }
    .btn-edit-sm { background: #e8fadf; color: #28c76f; }
    .btn-edit-sm:hover { background: #28c76f; color: #fff; }
    .btn-delete-sm { background: #feeaea; color: #ea5455; }
    .btn-delete-sm:hover { background: #ea5455; color: #fff; }
    .empty-state-compact {
        padding: 3rem; text-align: center; background: #fff;
        border-radius: 16px; border: 2px dashed #e2e8f0;
    }
    .scope-badge-all {
        background: #e8f4fd; color: #0984e3; padding: 2px 8px;
        border-radius: 4px; font-size: 10px; font-weight: 700;
    }
</style>

<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="mb-0 fw-bold"><i class="ti ti-settings me-2"></i>Konfigurasi Approval Tiket</h5>
            <div class="d-flex gap-2">
                <a href="{{ route('ticket.index') }}" class="btn btn-label-secondary">
                    <i class="ti ti-arrow-left me-1"></i> Kembali ke Tiket
                </a>
                <a href="{{ route('ticketconfig.create') }}" class="btn btn-primary">
                    <i class="ti ti-plus me-1"></i> Tambah Konfigurasi
                </a>
            </div>
        </div>
    </div>
</div>

<div class="row">
    @forelse ($config as $d)
        <div class="col-12">
            <div class="config-card">
                <div class="card-body-compact">
                    <div class="row align-items-center">
                        <!-- Unit Organization -->
                        <div class="col-md-4 border-end">
                            <div class="d-flex align-items-center">
                                <div class="bg-label-primary p-2 rounded-3 me-3">
                                    <i class="ti ti-ticket fs-4"></i>
                                </div>
                                <div class="config-info-item">
                                    <span class="info-label-sm">
                                        @if ($d->nama_cabang)
                                            {{ $d->nama_cabang }}
                                        @else
                                            <span class="scope-badge-all">SEMUA CABANG</span>
                                        @endif
                                    </span>
                                    <h6 class="mb-0 fw-bold">
                                        {{ $d->nama_dept ?? '' }}
                                        @if (!$d->nama_dept)
                                            <span class="scope-badge-all">SEMUA DEPARTEMEN</span>
                                        @endif
                                    </h6>
                                </div>
                            </div>
                        </div>

                        <!-- Approval Pipeline -->
                        <div class="col-md-6 border-end">
                            <div class="d-flex align-items-center h-100">
                                <div class="approval-pipeline-compact w-100">
                                    <span class="info-label-sm me-2">Flow:</span>
                                    @foreach ($d->roles as $role)
                                        <span class="role-badge-sm">{{ textUpperCase($role) }}</span>
                                        @if (!$loop->last)
                                            <i class="ti ti-chevron-right pipeline-arrow-sm"></i>
                                        @endif
                                    @endforeach
                                    <i class="ti ti-chevron-right pipeline-arrow-sm"></i>
                                    <span class="role-badge-sm" style="background:#fff3cd;color:#856404;border-color:#ffc107;">ADMIN IT</span>
                                </div>
                            </div>
                        </div>

                        <!-- Actions -->
                        <div class="col-md-2">
                            <div class="action-buttons-compact justify-content-end">
                                <a href="{{ route('ticketconfig.edit', $d->id) }}" class="btn-action-sm btn-edit-sm" title="Edit">
                                    <i class="ti ti-edit"></i>
                                </a>
                                <form method="POST" name="deleteform" class="deleteform" action="{{ route('ticketconfig.destroy', $d->id) }}">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-action-sm btn-delete-sm delete-confirm" title="Hapus">
                                        <i class="ti ti-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @empty
        <div class="col-12">
            <div class="empty-state-compact">
                <i class="ti ti-settings-automation fs-1 text-muted mb-3 d-block"></i>
                <h5 class="fw-bold">Belum Ada Konfigurasi</h5>
                <p class="text-muted mb-4 small">
                    Tanpa konfigurasi, semua tiket akan langsung masuk ke Admin IT tanpa perlu persetujuan bertahap.
                </p>
                <a href="{{ route('ticketconfig.create') }}" class="btn btn-primary">
                    <i class="ti ti-plus me-1"></i> Tambah Konfigurasi Pertama
                </a>
            </div>
        </div>
    @endforelse
</div>
@endsection
