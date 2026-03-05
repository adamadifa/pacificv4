@extends('layouts.app')
@section('titlepage', 'Konfigurasi Approval Ajuan Limit')

@section('content')
@section('navigasi')
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h4 class="mb-0">Konfigurasi Approval Ajuan Limit</h4>
            <small class="text-muted">Kelola alur persetujuan berdasarkan rentang nominal.</small>
        </div>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0" style="font-size: 13px">
                <li class="breadcrumb-item">
                    <a href="#"><i class="ti ti-folder me-1"></i>Marketing</a>
                </li>
                <li class="breadcrumb-item">
                    <a href="{{ route('ajuanlimit.index') }}">Ajuan Limit Kredit</a>
                </li>
                <li class="breadcrumb-item active">Konfigurasi</li>
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
        font-size: 14px;
        color: #002e65;
        font-weight: 700;
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

<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="mb-0 fw-bold"><i class="ti ti-settings me-2"></i>Daftar Konfigurasi Approval</h5>
            <div class="d-flex gap-2">
                @can('ajuanlimit.config')
                    <a href="{{ route('ajuanlimitconfig.create') }}" class="btn btn-primary shadow-sm">
                        <i class="ti ti-plus me-1"></i> Tambah Konfigurasi
                    </a>
                @endcan
            </div>
        </div>
    </div>
</div>

<div class="row">
    @forelse ($config as $d)
        <div class="col-12">
            <div class="config-card border">
                <div class="card-body-compact">
                    <div class="row align-items-center">
                        <!-- Nominal Range -->
                        <div class="col-md-3 border-end">
                            <div class="d-flex align-items-center">
                                <div class="bg-label-info p-2 rounded-3 me-3">
                                    <i class="ti ti-currency-dollar fs-4"></i>
                                </div>
                                <div class="config-info-item">
                                    <span class="info-label-sm">Range Nominal</span>
                                    <span class="info-value-sm">
                                        {{ number_format($d->min_limit, 0, ',', '.') }} - {{ number_format($d->max_limit, 0, ',', '.') }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        <!-- Approval Pipeline -->
                        <div class="col-md-7 border-end">
                            <div class="d-flex align-items-center h-100">
                                <div class="approval-pipeline-compact w-100">
                                    <span class="info-label-sm me-2 d-none d-xl-inline">Flow:</span>
                                    @php
                                        $roles = is_string($d->roles) ? json_decode($d->roles) : $d->roles;
                                    @endphp
                                    @foreach ($roles as $role)
                                        <span class="role-badge-sm">{{ textUpperCase($role) }}</span>
                                        @if (!$loop->last)
                                            <i class="ti ti-chevron-right pipeline-arrow-sm"></i>
                                        @endif
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        <!-- Actions -->
                        <div class="col-md-2">
                            <div class="action-buttons-compact justify-content-end">
                                @can('ajuanlimit.config')
                                    <a href="{{ route('ajuanlimitconfig.edit', $d->id) }}" class="btn-action-sm btn-edit-sm" title="Edit">
                                        <i class="ti ti-edit"></i>
                                    </a>
                                    <form method="POST" action="{{ route('ajuanlimitconfig.destroy', $d->id) }}" class="d-inline">
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
        </div>
    @empty
        <div class="col-12">
            <div class="empty-state-compact">
                <i class="ti ti-settings-automation fs-1 text-muted mb-3 d-block"></i>
                <h5 class="fw-bold">Belum Ada Konfigurasi</h5>
                <p class="text-muted mb-4 small">Tentukan alur persetujuan berdasarkan rentang nominal.</p>
                @can('ajuanlimit.config')
                    <a href="{{ route('ajuanlimitconfig.create') }}" class="btn btn-primary">
                        Buat Konfigurasi Pertama
                    </a>
                @endcan
            </div>
        </div>
    @endforelse
</div>
@endsection
