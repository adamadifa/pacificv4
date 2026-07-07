@extends('layouts.app')
@section('titlepage', 'Tiket Ajuan System')

@section('content')
@section('navigasi')
    <span>Tiket Ajuan System</span>
@endsection

<style>
    .ticket-header-banner {
        background: linear-gradient(135deg, #312e81 0%, #1e1b4b 40%, #0f172a 100%);
        border-radius: 20px;
        padding: 32px 36px;
        box-shadow: 0 20px 40px -15px rgba(15, 23, 42, 0.35);
        position: relative;
        overflow: hidden;
        border: 1px solid rgba(255, 255, 255, 0.08);
    }

    .ticket-header-banner::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -10%;
        width: 350px;
        height: 350px;
        border-radius: 50%;
        background: radial-gradient(circle, rgba(99, 102, 241, 0.15) 0%, rgba(99, 102, 241, 0) 70%);
        pointer-events: none;
    }

    .stat-card-modern {
        border: 1px solid #e2e8f0;
        border-radius: 16px;
        background: #ffffff;
        padding: 20px !important;
        position: relative;
        overflow: hidden;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.02), 0 2px 4px -1px rgba(0, 0, 0, 0.01);
    }

    .stat-card-modern:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 20px -8px rgba(0, 0, 0, 0.08);
        border-color: #cbd5e1;
    }

    .stat-card-modern::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        width: 100%;
        height: 4px;
        opacity: 0.8;
    }

    .stat-card-modern.total-card::after {
        background: #4f46e5;
    }

    .stat-card-modern.pending-card::after {
        background: #f59e0b;
    }

    .stat-card-modern.proses-card::after {
        background: #06b6d4;
    }

    .stat-card-modern.selesai-card::after {
        background: #10b981;
    }

    .stat-icon-box {
        width: 52px;
        height: 52px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.6rem;
        transition: all 0.3s ease;
    }

    .stat-card-modern:hover .stat-icon-box {
        transform: scale(1.1) rotate(5deg);
    }

    .table-ticket-modern {
        border-collapse: separate;
        border-spacing: 0 12px;
        width: 100%;
    }

    .table-ticket-modern thead tr th {
        border: none;
        padding: 8px 20px;
    }

    .table-ticket-modern tbody tr {
        background: #ffffff;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.01), 0 2px 4px -1px rgba(0, 0, 0, 0.01);
        transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
        border-radius: 16px;
    }

    .table-ticket-modern tbody tr:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 24px -8px rgba(0, 0, 0, 0.08);
        background: #fafafa;
    }

    .table-ticket-modern td {
        padding: 20px 20px;
        vertical-align: middle;
        border-top: 1px solid #f1f5f9;
        border-bottom: 1px solid #f1f5f9;
    }

    .table-ticket-modern td:first-child {
        border-left: 1px solid #f1f5f9;
        border-top-left-radius: 16px;
        border-bottom-left-radius: 16px;
    }

    .table-ticket-modern td:last-child {
        border-right: 1px solid #f1f5f9;
        border-top-right-radius: 16px;
        border-bottom-right-radius: 16px;
    }

    .avatar-user-badge {
        width: 40px;
        height: 40px;
        border-radius: 12px;
        background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%);
        color: #ffffff;
        font-weight: 700;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.95rem;
        box-shadow: 0 4px 10px rgba(79, 70, 229, 0.25);
    }

    /* Glow priority styles */
    .badge-priority-modern {
        font-weight: 600;
        padding: 5px 12px;
        border-radius: 30px;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        font-size: 0.75rem;
    }

    .priority-urgent {
        background: #fef2f2;
        color: #ef4444;
        border: 1px solid #fca5a5;
        box-shadow: 0 0 10px rgba(239, 68, 68, 0.15);
    }

    .priority-urgent .pulse-dot {
        width: 8px;
        height: 8px;
        background-color: #ef4444;
        border-radius: 50%;
        display: inline-block;
        animation: priorityPulse 1.5s infinite;
    }

    @keyframes priorityPulse {
        0% {
            box-shadow: 0 0 0 0 rgba(239, 68, 68, 0.7);
        }

        70% {
            box-shadow: 0 0 0 6px rgba(239, 68, 68, 0);
        }

        100% {
            box-shadow: 0 0 0 0 rgba(239, 68, 68, 0);
        }
    }

    .priority-tinggi {
        background: #fffbeb;
        color: #d97706;
        border: 1px solid #fcd34d;
    }

    .priority-sedang {
        background: #eff6ff;
        color: #2563eb;
        border: 1px solid #bfdbfe;
    }

    .priority-rendah {
        background: #f8fafc;
        color: #64748b;
        border: 1px solid #cbd5e1;
    }

    .btn-action-circle {
        width: 38px;
        height: 38px;
        border-radius: 12px;
        padding: 0;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        border: 1px solid #cbd5e1;
        background: #ffffff;
        color: #475569;
    }

    .btn-action-circle:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.08);
    }

    .btn-action-circle.btn-message-glow {
        background: #eff6ff;
        color: #2563eb;
        border-color: #bfdbfe;
    }

    .btn-action-circle.btn-message-glow:hover {
        background: #2563eb;
        color: #ffffff;
        border-color: #1d4ed8;
        box-shadow: 0 4px 12px rgba(37, 99, 235, 0.35);
    }

    .btn-action-circle.btn-approve-glow {
        background: #ecfdf5;
        color: #059669;
        border-color: #a7f3d0;
    }

    .btn-action-circle.btn-approve-glow:hover {
        background: #10b981;
        color: #ffffff;
        border-color: #047857;
        box-shadow: 0 4px 12px rgba(16, 185, 129, 0.35);
    }

    .btn-action-circle.btn-edit-glow {
        background: #fffbeb;
        color: #d97706;
        border-color: #fde68a;
    }

    .btn-action-circle.btn-edit-glow:hover {
        background: #f59e0b;
        color: #ffffff;
        border-color: #b45309;
        box-shadow: 0 4px 12px rgba(245, 158, 11, 0.35);
    }

    .btn-action-circle.btn-delete-glow {
        background: #fef2f2;
        color: #dc2626;
        border-color: #fecaca;
    }

    .btn-action-circle.btn-delete-glow:hover {
        background: #ef4444;
        color: #ffffff;
        border-color: #b91c1c;
        box-shadow: 0 4px 12px rgba(239, 68, 68, 0.35);
    }

    .btn-action-circle.btn-view-glow {
        background: #faf5ff;
        color: #7c3aed;
        border-color: #e9d5ff;
    }

    .btn-action-circle.btn-view-glow:hover {
        background: #7c3aed;
        color: #ffffff;
        border-color: #6d28d9;
        box-shadow: 0 4px 12px rgba(124, 58, 237, 0.35);
    }
</style>

<div class="row">
    <div class="col-12">
        <div class="nav-align-top nav-tabs-shadow mb-4">
            @include('layouts.navigation_ticket')
            <div class="tab-content border-0 p-0 bg-transparent">
                <div class="tab-pane fade active show" id="navs-justified-home" role="tabpanel">

                    {{-- Banner Header --}}
                    <div class="ticket-header-banner mb-4">
                        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
                            <div>
                                <span class="badge bg-label-primary text-primary px-3 py-1 rounded-pill fw-bold mb-2">
                                    <i class="ti ti-ticket me-1"></i>Helpdesk & Service Desk IT
                                </span>
                                <h3 class="fw-bold text-white mb-1">Pusat Tiket Ajuan System</h3>
                                <p class="text-white-50 mb-0">Kelola pengajuan perbaikan, permintaan user, penambahan
                                    menu, dan perpindahan data.</p>
                            </div>
                            <div class="d-flex gap-2">
                                @hasrole('super admin')
                                    <a href="{{ route('ticket.cetaklaporan', request()->all()) }}" target="_blank"
                                        class="btn btn-success fw-bold px-3 py-3 rounded-3"
                                        title="Cetak Laporan Evaluation & SLA Tiket">
                                        <i class="ti ti-printer me-2 fs-4"></i>Cetak Laporan
                                    </a>
                                @endhasrole
                                <button class="btn btn-primary btn-lg shadow-lg fw-bold px-4 py-3 rounded-3"
                                    id="btnCreate">
                                    <i class="ti ti-plus me-2 fs-4"></i>Buat Tiket Ajuan Baru
                                </button>
                            </div>
                        </div>
                    </div>

                    {{-- KPI Cards --}}
                    <div class="row g-3 mb-4">
                        <div class="col-lg-3 col-md-6 col-sm-6">
                            <div class="card stat-card-modern total-card p-3">
                                <div class="d-flex align-items-center justify-content-between">
                                    <div>
                                        <span class="text-muted small fw-bold text-uppercase">Total Tiket</span>
                                        <h2 class="fw-bold text-dark mb-0 mt-1">
                                            {{ number_format($stats['total'] ?? 0) }}</h2>
                                    </div>
                                    <div class="stat-icon-box bg-label-primary text-primary">
                                        <i class="ti ti-tickets"></i>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-3 col-md-6 col-sm-6">
                            <div class="card stat-card-modern pending-card p-3">
                                <div class="d-flex align-items-center justify-content-between">
                                    <div>
                                        <span class="text-muted small fw-bold text-uppercase">Menunggu Approval</span>
                                        <h2 class="fw-bold text-warning mb-0 mt-1">
                                            {{ number_format($stats['pending'] ?? 0) }}</h2>
                                    </div>
                                    <div class="stat-icon-box bg-label-warning text-warning">
                                        <i class="ti ti-hourglass-low"></i>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-3 col-md-6 col-sm-6">
                            <div class="card stat-card-modern proses-card p-3">
                                <div class="d-flex align-items-center justify-content-between">
                                    <div>
                                        <span class="text-muted small fw-bold text-uppercase">Diproses IT</span>
                                        <h2 class="fw-bold text-info mb-0 mt-1">
                                            {{ number_format($stats['proses_it'] ?? 0) }}</h2>
                                    </div>
                                    <div class="stat-icon-box bg-label-info text-info">
                                        <i class="ti ti-cpu"></i>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-3 col-md-6 col-sm-6">
                            <div class="card stat-card-modern selesai-card p-3">
                                <div class="d-flex align-items-center justify-content-between">
                                    <div>
                                        <span class="text-muted small fw-bold text-uppercase">Tiket Selesai</span>
                                        <h2 class="fw-bold text-success mb-0 mt-1">
                                            {{ number_format($stats['selesai'] ?? 0) }}</h2>
                                    </div>
                                    <div class="stat-icon-box bg-label-success text-success">
                                        <i class="ti ti-circle-check"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Filter Panel --}}
                    <div class="card border-0 shadow-sm rounded-4 mb-4">
                        <div class="card-body p-3">
                            <form action="{{ route('ticket.index') }}" method="GET">
                                <div class="row g-2 align-items-end">
                                    @hasanyrole($roles_show_cabang)
                                        <div class="col-lg-3 col-md-6">
                                            <label class="form-label small fw-bold text-uppercase text-muted"><i
                                                    class="ti ti-building me-1"></i>Cabang</label>
                                            <x-select label="Semua Cabang" name="kode_cabang_search" :data="$cabang"
                                                key="kode_cabang" textShow="nama_cabang" upperCase="true"
                                                selected="{{ Request('kode_cabang_search') }}"
                                                select2="select2Kodecabangsearch" />
                                        </div>
                                    @endrole

                                    <div class="col-lg-3 col-md-6">
                                        <label class="form-label small fw-bold text-uppercase text-muted"><i
                                                class="ti ti-category me-1"></i>Kategori Tiket</label>
                                        <select name="id_kategori_search" class="form-select select2">
                                            <option value="">Semua Kategori Tiket</option>
                                            @foreach ($categories as $cat)
                                                <option value="{{ $cat->id }}"
                                                    {{ Request('id_kategori_search') == $cat->id ? 'selected' : '' }}>
                                                    {{ $cat->nama_kategori }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-lg-3 col-md-6">
                                        <label class="form-label small fw-bold text-uppercase text-muted"><i
                                                class="ti ti-filter me-1"></i>Status Tiket</label>
                                        <select name="status_search" class="form-select">
                                            <option value="">Semua Status</option>
                                            <option value="pending"
                                                {{ Request('status_search') == 'pending' ? 'selected' : '' }}>Belum
                                                Selesai / Menunggu</option>
                                            <option value="selesai"
                                                {{ Request('status_search') == 'selesai' ? 'selected' : '' }}>Selesai
                                                (Done)</option>
                                            <option value="ditolak"
                                                {{ Request('status_search') == 'ditolak' ? 'selected' : '' }}>Ditolak
                                                (Declined)</option>
                                        </select>
                                    </div>

                                    <div class="col-lg-3 col-md-6">
                                        <label class="form-label small fw-bold text-uppercase text-muted"><i
                                                class="ti ti-search me-1"></i>Cari Tiket</label>
                                        <div class="input-group">
                                            <input type="text" name="keyword" class="form-control"
                                                placeholder="No. Tiket / Judul / Keterangan..."
                                                value="{{ Request('keyword') }}">
                                            <button class="btn btn-primary" type="submit"><i
                                                    class="ti ti-search"></i></button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                    {{-- Data Table --}}
                    <div class="table-responsive">
                        <table class="table table-ticket-modern align-middle mb-0">
                            <thead>
                                <tr class="text-uppercase text-muted small fw-bold">
                                    <th style="width: 11%">No. Tiket</th>
                                    <th style="width: 16%">Kategori & Prioritas</th>
                                    <th>Judul & Detail Pengajuan</th>
                                    <th style="width: 14%">Pengaju (User)</th>
                                    <th style="width: 6%" class="text-center">Cabang</th>
                                    <th style="width: 18%" class="text-center">Status & Durasi SLA</th>
                                    <th style="width: 13%" class="text-center">Aksi & Diskusi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($ticket as $d)
                                    <tr>
                                        <td>
                                            <span
                                                class="badge bg-primary-subtle text-primary border border-primary font-monospace fs-6 px-2 py-1">
                                                {{ $d->kode_pengajuan }}
                                            </span>
                                            <div class="small text-muted mt-1">
                                                <i
                                                    class="ti ti-calendar me-1"></i>{{ date('d/m/Y', strtotime($d->tanggal)) }}
                                            </div>
                                        </td>
                                        <td>
                                            <span
                                                class="badge bg-label-info text-info fw-bold mb-1 d-inline-block text-truncate"
                                                style="max-width: 160px;">
                                                <i
                                                    class="ti ti-tag me-1"></i>{{ $d->category->nama_kategori ?? 'Umum' }}
                                            </span>
                                            <div>
                                                @if ($d->priority == 'Urgent')
                                                    <span class="badge-priority-modern priority-urgent">
                                                        <span class="pulse-dot"></span>URGENT
                                                    </span>
                                                @elseif($d->priority == 'Tinggi')
                                                    <span class="badge-priority-modern priority-tinggi">
                                                        <i class="ti ti-alert-triangle fs-7"></i>HIGH
                                                    </span>
                                                @elseif($d->priority == 'Sedang')
                                                    <span class="badge-priority-modern priority-sedang">
                                                        <i class="ti ti-info-circle fs-7"></i>MEDIUM
                                                    </span>
                                                @else
                                                    <span class="badge-priority-modern priority-rendah">
                                                        <i class="ti ti-circle fs-7"></i>LOW
                                                    </span>
                                                @endif
                                            </div>
                                        </td>
                                        <td>
                                            <h6 class="fw-bold text-dark mb-1">{{ $d->judul ?? 'Pengajuan Tiket' }}
                                            </h6>
                                            @if ($d->no_bukti)
                                                <span
                                                    class="badge bg-light text-dark border font-monospace mb-1 d-inline-block"><i
                                                        class="ti ti-file-description me-1"></i>No. Bukti:
                                                    {{ $d->no_bukti }}</span>
                                            @endif
                                            <p class="text-muted small mb-1">{{ Str::limit($d->keterangan, 95) }}</p>

                                            @if ($d->lampiran || $d->link)
                                                <div class="d-flex gap-2 align-items-center mt-1">
                                                    @if ($d->lampiran)
                                                        <a href="{{ asset($d->lampiran) }}" target="_blank"
                                                            class="badge bg-danger-subtle text-danger border border-danger text-decoration-none">
                                                            <i class="ti ti-paperclip me-1"></i>Lampiran Dokumen
                                                        </a>
                                                    @endif
                                                    @if ($d->link)
                                                        <a href="{{ $d->link }}" target="_blank"
                                                            class="badge bg-info-subtle text-info border border-info text-decoration-none">
                                                            <i class="ti ti-link me-1"></i>Link Referensi
                                                        </a>
                                                    @endif
                                                </div>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center gap-2">
                                                <div class="avatar-user-badge">
                                                    {{ strtoupper(substr($d->user->name ?? 'U', 0, 1)) }}
                                                </div>
                                                <div>
                                                    <strong
                                                        class="text-dark small d-block">{{ formatName2($d->user->name ?? '-') }}</strong>
                                                    <span
                                                        class="badge bg-light text-dark border px-2 py-0 fs-7">{{ $d->user->kode_dept ?? 'Dept' }}</span>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge bg-dark px-2 py-1 fs-7">{{ $d->kode_cabang }}</span>
                                        </td>
                                        <td class="text-center">
                                            <div class="mb-1">{!! $d->badge_status !!}</div>
                                            <div>{!! $d->badge_posisi !!}</div>
                                            <div class="mt-1">
                                                <span class="badge bg-light text-primary border px-2 py-1 fs-7"
                                                    title="Durasi Waktu Penyelesaian Tiket">
                                                    <i class="ti ti-clock-hour-4 me-1"></i>{{ $d->lama_penyelesaian }}
                                                </span>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <div class="d-flex justify-content-center align-items-center gap-1">

                                                {{-- Chat Thread Button with Notification Badge --}}
                                                <button
                                                    class="btn-action-circle btn-message-glow position-relative btnMessage"
                                                    kode_pengajuan="{{ $d->kode_pengajuan }}"
                                                    title="Diskusi / Chat Tiket">
                                                    <i class="ti ti-message-dots fs-5"></i>
                                                    @if (($d->messages_count ?? 0) > 0)
                                                        <span
                                                            class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger border border-light px-1"
                                                            style="font-size: 0.65rem;">
                                                            {{ $d->messages_count }}
                                                            <span class="visually-hidden">pesan masuk</span>
                                                        </span>
                                                    @endif
                                                </button>

                                                {{-- Approval Button --}}
                                                @if ($d->status == '0')
                                                    @php
                                                        $currentUserId = auth()->user()->id;
                                                        $isApprover = false;
                                                        if (
                                                            $d->posisi_approval == 'MANAGER_DEPT' &&
                                                            $d->id_manager_dept == $currentUserId
                                                        ) {
                                                            $isApprover = true;
                                                        } elseif (
                                                            $d->posisi_approval == 'SMM' &&
                                                            $d->id_smm == $currentUserId
                                                        ) {
                                                            $isApprover = true;
                                                        } elseif (
                                                            $d->posisi_approval == 'RSM' &&
                                                            $d->id_rsm == $currentUserId
                                                        ) {
                                                            $isApprover = true;
                                                        } elseif (
                                                            $d->posisi_approval == 'GM' &&
                                                            $d->id_gm == $currentUserId
                                                        ) {
                                                            $isApprover = true;
                                                        } elseif (
                                                            $d->posisi_approval == 'ADMIN' &&
                                                            auth()
                                                                ->user()
                                                                ->hasRole(['super admin', 'admin maintenance'])
                                                        ) {
                                                            $isApprover = true;
                                                        } elseif (auth()->user()->hasRole('super admin')) {
                                                            $isApprover = true;
                                                        }
                                                    @endphp

                                                    @if ($isApprover)
                                                        <button class="btn-action-circle btn-approve-glow btnApprove"
                                                            kode_pengajuan="{{ $d->kode_pengajuan }}"
                                                            title="Proses Approval Tiket">
                                                            <i class="ti ti-check fs-5"></i>
                                                        </button>
                                                    @endif

                                                    {{-- Edit Button --}}
                                                    @if ($d->id_user == auth()->user()->id || auth()->user()->hasRole('super admin'))
                                                        <button class="btn-action-circle btn-edit-glow btnEdit"
                                                            kode_pengajuan="{{ $d->kode_pengajuan }}"
                                                            title="Edit Tiket">
                                                            <i class="ti ti-edit fs-5"></i>
                                                        </button>
                                                    @endif

                                                    {{-- Delete Button --}}
                                                    @if ($d->id_user == auth()->user()->id || auth()->user()->hasRole('super admin'))
                                                        <form method="POST"
                                                            action="{{ route('ticket.delete', Crypt::encrypt($d->kode_pengajuan)) }}"
                                                            class="deleteform d-inline">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit"
                                                                class="btn-action-circle btn-delete-glow delete-confirm"
                                                                title="Hapus Tiket">
                                                                <i class="ti ti-trash fs-5"></i>
                                                            </button>
                                                        </form>
                                                    @endif
                                                @else
                                                    {{-- View Detail Button --}}
                                                    <button class="btn-action-circle btn-view-glow btnApprove"
                                                        kode_pengajuan="{{ $d->kode_pengajuan }}"
                                                        title="Lihat Detail Tiket">
                                                        <i class="ti ti-eye fs-5"></i>
                                                    </button>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center py-5">
                                            <div class="py-4">
                                                <i
                                                    class="ti ti-ticket-off text-muted opacity-50 display-4 d-block mb-3"></i>
                                                <h5 class="fw-bold text-secondary">Belum Ada Tiket Ajuan</h5>
                                                <p class="text-muted small">Tidak ada data tiket yang cocok dengan
                                                    kriteria pencarian Anda.</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{-- Pagination --}}
                    <div class="d-flex justify-content-between align-items-center mt-4">
                        <div class="small text-muted">
                            Menampilkan data tiket ajuan terbaru
                        </div>
                        <div>
                            {{ $ticket->links('pagination::bootstrap-5') }}
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

{{-- Ticket Modals with show props --}}
<x-modal-form id="modalTicket" size="modal-lg" show="loadmodalform" title="Tiket Ajuan" />
<x-modal-form id="modalTicketMessage" size="modal-lg" show="loadmodalmessage" title="Diskusi Tiket Ajuan" />

@endsection

@push('myscript')
<script>
    $(function() {
        $("#btnCreate").click(function(e) {
            e.preventDefault();
            $("#modalTicket").modal("show");
            $("#modalTicket").find(".modal-title").text("Buat Tiket Ajuan Baru");
            $("#modalTicket").find(".loadmodalform").load("{{ route('ticket.create') }}");
        });

        $(".btnEdit").click(function(e) {
            e.preventDefault();
            let kode_pengajuan = $(this).attr("kode_pengajuan");
            $("#modalTicket").modal("show");
            $("#modalTicket").find(".modal-title").text("Edit Tiket Ajuan");
            $("#modalTicket").find(".loadmodalform").load("/ticket/" + kode_pengajuan + "/edit");
        });

        $(".btnApprove").click(function(e) {
            e.preventDefault();
            let kode_pengajuan = $(this).attr("kode_pengajuan");
            $("#modalTicket").modal("show");
            $("#modalTicket").find(".modal-title").text("Detail & Persetujuan Tiket Ajuan");
            $("#modalTicket").find(".loadmodalform").load("/ticket/" + kode_pengajuan + "/approve");
        });

        $(".btnMessage").click(function(e) {
            e.preventDefault();
            let kode_pengajuan = $(this).attr("kode_pengajuan");
            $("#modalTicketMessage").modal("show");
            $("#modalTicketMessage").find(".modal-title").text("Diskusi Tiket Ajuan: " +
                kode_pengajuan);
            $("#modalTicketMessage").find(".loadmodalform, .loadmodalmessage, #loadmodalmessage").load(
                "/ticket/" + kode_pengajuan + "/message");
        });
    });
</script>
@endpush
