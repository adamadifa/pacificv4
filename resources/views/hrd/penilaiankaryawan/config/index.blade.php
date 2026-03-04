@extends('layouts.app')
@section('titlepage', 'Config Approval Penilaian')

@section('content')
@section('content')
@section('navigasi')
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h4 class="mb-0">Config Approval Penilaian</h4>
            <small class="text-muted">Kelola hierarki dan alur persetujuan penilaian sesuai unit organisasi.</small>
        </div>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0" style="font-size: 13px">
                <li class="breadcrumb-item">
                    <a href="#"><i class="ti ti-folder me-1"></i>HRD</a>
                </li>
                <li class="breadcrumb-item">
                    <a href="{{ route('penilaiankaryawan.index') }}">Penilaian Karyawan</a>
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

    .card-body-compact {
        padding: 1rem 1.5rem;
    }

    .config-info-item {
        display: flex;
        flex-direction: column;
    }

    .info-label-sm {
        font-size: 10px;
        font-weight: 700;
        color: #9e9e9e;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 2px;
    }

    .info-value-sm {
        font-size: 13px;
        color: #424242;
        font-weight: 600;
    }

    .approval-pipeline-compact {
        display: flex;
        align-items: center;
        flex-wrap: wrap;
        gap: 6px;
        background: #f8f9fa;
        padding: 8px 12px;
        border-radius: 8px;
        border: 1px solid #edf2f7;
    }

    .role-badge-sm {
        padding: 4px 10px;
        background: #fff;
        color: #3f6791;
        border-radius: 6px;
        font-size: 11px;
        font-weight: 700;
        border: 1px solid rgba(63, 103, 145, 0.15);
        box-shadow: 0 1px 3px rgba(0,0,0,0.02);
    }

    .pipeline-arrow-sm {
        color: #cbd5e0;
        font-size: 12px;
    }

    .action-buttons-compact {
        display: flex;
        gap: 8px;
    }

    .btn-action-sm {
        width: 32px;
        height: 32px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 8px;
        transition: all 0.2s;
        border: none;
        font-size: 14px;
    }

    .btn-edit-sm { background: #e8fadf; color: #28c76f; }
    .btn-edit-sm:hover { background: #28c76f; color: #fff; }

    .btn-delete-sm { background: #feeaea; color: #ea5455; }
    .btn-delete-sm:hover { background: #ea5455; color: #fff; }

    .empty-state-compact {
        padding: 3rem;
        text-align: center;
        background: #fff;
        border-radius: 16px;
        border: 2px dashed #e2e8f0;
    }
</style>

<div class="row mb-3">
    <div class="col-12 text-end">
        @can('penilaiankaryawan.config.create')
            <a href="{{ route('penilaiankaryawanconfig.create') }}" class="btn btn-primary d-inline-flex align-items-center">
                <i class="ti ti-plus me-1 fs-5"></i>
                <span>Tambah Konfigurasi</span>
            </a>
        @endcan
    </div>
</div>

<div class="row">
    @forelse ($config as $d)
        <div class="col-12">
            <div class="config-card">
                <div class="card-body-compact">
                    <div class="row align-items-center">
                        <!-- Unit Organization -->
                        <div class="col-md-3 border-end">
                            <div class="d-flex align-items-center">
                                <div class="bg-label-primary p-2 rounded-3 me-3">
                                    <i class="ti ti-building fs-4"></i>
                                </div>
                                <div class="config-info-item">
                                    <span class="info-label-sm">{{ $d->nama_cabang ?? 'SEMUA CABANG' }}</span>
                                    <h6 class="mb-0 fw-bold">{{ $d->nama_dept ?? 'SEMUA DEPARTEMEN' }}</h6>
                                </div>
                            </div>
                        </div>

                        <!-- Kategori & Jabatan -->
                        <div class="col-md-3 border-end">
                            <div class="row">
                                <div class="col-6">
                                    <div class="config-info-item">
                                        <span class="info-label-sm">Kategori</span>
                                        <span class="info-value-sm">{{ $d->kategori_jabatan ?? 'ALL' }}</span>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="config-info-item">
                                        <span class="info-label-sm">Jabatan</span>
                                        <span class="info-value-sm text-truncate" title="{{ $d->nama_jabatan ?? 'ALL' }}">
                                            {{ $d->nama_jabatan ?? 'ALL' }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Approval Pipeline -->
                        <div class="col-md-5">
                            <div class="d-flex align-items-center h-100">
                                <div class="approval-pipeline-compact w-100">
                                    <span class="info-label-sm me-2 d-none d-xl-inline">Flow:</span>
                                    @foreach ($d->roles as $role)
                                        <span class="role-badge-sm">{{ textUpperCase($role) }}</span>
                                        @if (!$loop->last)
                                            <i class="ti ti-chevron-right pipeline-arrow-sm"></i>
                                        @endif
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        <!-- Actions -->
                        <div class="col-md-1">
                                        <div class="action-buttons-compact justify-content-end">
                                            @can('penilaiankaryawan.config.edit')
                                                <a href="{{ route('penilaiankaryawanconfig.edit', $d->id) }}" class="btn-action-sm btn-edit-sm" title="Edit">
                                                    <i class="ti ti-edit"></i>
                                                </a>
                                            @endcan
                                            @can('penilaiankaryawan.config.delete')
                                                <form method="POST" name="deleteform" class="deleteform" action="{{ route('penilaiankaryawanconfig.destroy', $d->id) }}">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn-action-sm btn-delete-sm delete-confirm" title="Hapus">
                                                        <i class="ti ti-trash"></i>
                                                    </button>
                                                </form>
                                            @endcan
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
                <p class="text-muted mb-4 small">Silahkan rancang alur persetujuan untuk penilaian karyawan.</p>
                @can('penilaiankaryawan.config.create')
                    <a href="{{ route('penilaiankaryawanconfig.create') }}" class="btn btn-primary">
                        Mulai Sekarang
                    </a>
                @endcan
            </div>
        </div>
    @endforelse
</div>
@endsection
