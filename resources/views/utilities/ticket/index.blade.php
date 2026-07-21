@extends('layouts.app')
@section('titlepage', 'Tiket Ajuan System')

@section('content')
@section('navigasi')
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h4 class="mb-0 fw-bold text-dark">Tiket Ajuan System</h4>
            <small class="text-muted">Helpdesk & Service Desk IT — Kelola pengajuan perbaikan, permintaan user, dan kendala sistem.</small>
        </div>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0" style="font-size:13px">
                <li class="breadcrumb-item"><a href="#"><i class="ti ti-folder me-1"></i>Utilities</a></li>
                <li class="breadcrumb-item active">Tiket</li>
            </ol>
        </nav>
    </div>
@endsection

<style>
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap');

    .ticket-page { 
        font-family: 'Inter', sans-serif; 
        color: #334155;
    }

    /* ===== HEADER ACTION AREA ===== */
    .tk-header-action-bar {
        background: #ffffff;
        border-radius: 12px;
        border: 1px solid #e2e8f0;
        padding: 16px 20px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.02);
    }

    /* ===== KPI CARDS ===== */
    .tk-stat-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
        gap: 16px;
    }
    .tk-stat-box {
        background: #ffffff;
        border-radius: 12px;
        border: 1px solid #e2e8f0;
        padding: 16px 20px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.02);
        transition: all 0.2s ease;
    }
    .tk-stat-box:hover {
        border-color: #cbd5e1;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.04);
    }
    .tk-stat-icon-wrapper {
        width: 44px;
        height: 44px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
        flex-shrink: 0;
    }
    .tk-stat-num {
        font-size: 24px;
        font-weight: 700;
        line-height: 1.2;
        color: #1e293b;
    }
    .tk-stat-title {
        font-size: 11px;
        font-weight: 600;
        text-transform: uppercase;
        color: #64748b;
        letter-spacing: 0.5px;
    }

    /* ===== FILTER CARD ===== */
    .tk-filter-card {
        background: #ffffff;
        border-radius: 12px;
        border: 1px solid #e2e8f0;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.02);
    }
    .tk-filter-card .form-label {
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        color: #475569;
        letter-spacing: 0.5px;
        margin-bottom: 6px;
    }
    .tk-filter-card .form-control,
    .tk-filter-card .form-select {
        border-radius: 8px;
        font-size: 13px;
        border-color: #cbd5e1;
        padding: 8px 12px;
        color: #1e293b;
    }
    .tk-filter-card .form-control:focus,
    .tk-filter-card .form-select:focus {
        border-color: #4f46e5;
        box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1);
    }

    /* ===== TICKET CONTAINER ===== */
    .tk-container {
        display: flex;
        flex-direction: column;
        gap: 12px;
    }

    /* ===== TICKET CARD (RE-DESIGNED FROM 0) ===== */
    .tk-item {
        background: #ffffff;
        border-radius: 12px;
        border: 1px solid #e2e8f0;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.02);
        transition: all 0.2s ease;
        overflow: hidden;
    }
    .tk-item:hover {
        border-color: #cbd5e1;
        box-shadow: 0 4px 16px rgba(0, 0, 0, 0.05);
    }

    /* Top Row Info Bar */
    .tk-item-top {
        background: #f8fafc;
        padding: 12px 20px;
        border-bottom: 1px solid #e2e8f0;
        display: flex;
        align-items: center;
        gap: 12px;
        flex-wrap: wrap;
    }

    /* Inner Content */
    .tk-item-body {
        padding: 18px 20px;
    }

    /* Typography & Details */
    .tk-item-title {
        font-size: 15px;
        font-weight: 700;
        color: #0f172a;
        margin-bottom: 6px;
    }
    .tk-item-desc {
        font-size: 13px;
        color: #475569;
        line-height: 1.55;
        margin-bottom: 12px;
    }
    .tk-item-nobukti {
        font-size: 11px;
        font-family: monospace;
        color: #475569;
        background: #f1f5f9;
        padding: 3px 8px;
        border-radius: 4px;
        border: 1px solid #e2e8f0;
        display: inline-flex;
        align-items: center;
        margin-bottom: 10px;
    }

    /* Badges & Tags */
    .tk-badge {
        font-size: 11px;
        font-weight: 600;
        padding: 3px 10px;
        border-radius: 6px;
        display: inline-flex;
        align-items: center;
        gap: 5px;
    }
    .tk-badge.kode {
        background: #eef2ff;
        color: #4f46e5;
        border: 1px solid #c7d2fe;
        font-family: monospace;
    }
    .tk-badge.kategori {
        background: #f0fdf4;
        color: #16a34a;
        border: 1px solid #bbf7d0;
    }
    .tk-badge.cabang {
        background: #f1f5f9;
        color: #334155;
        border: 1px solid #e2e8f0;
        font-weight: 700;
    }
    .tk-badge.priority-urgent {
        background: #fef2f2;
        color: #dc2626;
        border: 1px solid #fca5a5;
        font-weight: 700;
    }
    .tk-badge.priority-high {
        background: #fff7ed;
        color: #c2410c;
        border: 1px solid #fed7aa;
    }
    .tk-badge.priority-medium {
        background: #eff6ff;
        color: #1d4ed8;
        border: 1px solid #bfdbfe;
    }
    .tk-badge.priority-low {
        background: #f8fafc;
        color: #64748b;
        border: 1px solid #cbd5e1;
    }

    /* User Profile section */
    .tk-user-box {
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .tk-user-avatar {
        width: 36px;
        height: 36px;
        border-radius: 8px;
        background: #f1f5f9;
        color: #475569;
        font-weight: 700;
        font-size: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        border: 1px solid #e2e8f0;
    }
    .tk-user-name {
        font-size: 13px;
        font-weight: 600;
        color: #1e293b;
        line-height: 1.3;
    }
    .tk-user-dept {
        font-size: 10px;
        font-weight: 600;
        text-transform: uppercase;
        color: #94a3b8;
    }

    /* Attachment Links */
    .tk-link-btn {
        font-size: 11px;
        font-weight: 600;
        padding: 4px 10px;
        border-radius: 6px;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 4px;
        transition: all 0.15s;
    }
    .tk-link-btn.doc {
        background: #fef2f2;
        color: #dc2626;
        border: 1px solid #fecaca;
    }
    .tk-link-btn.doc:hover { background: #dc2626; color: #ffffff; }
    .tk-link-btn.web {
        background: #eff6ff;
        color: #2563eb;
        border: 1px solid #bfdbfe;
    }
    .tk-link-btn.web:hover { background: #2563eb; color: #ffffff; }

    /* Action Buttons Area */
    .tk-action-group {
        display: flex;
        gap: 8px;
        justify-content: flex-end;
        align-items: center;
    }
    .tk-btn-control {
        padding: 8px 14px;
        font-size: 12px;
        font-weight: 600;
        border-radius: 8px;
        border: 1px solid;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 5px;
        transition: all 0.18s;
        text-decoration: none;
    }
    .tk-btn-control.chat {
        border-color: #cbd5e1;
        background: #ffffff;
        color: #334155;
    }
    .tk-btn-control.chat:hover { background: #f8fafc; color: #0f172a; }
    .tk-btn-control.approve {
        border-color: #86efac;
        background: #f0fdf4;
        color: #15803d;
    }
    .tk-btn-control.approve:hover { background: #15803d; color: #ffffff; border-color: #15803d; }
    .tk-btn-control.edit {
        border-color: #fcd34d;
        background: #fffbeb;
        color: #b45309;
    }
    .tk-btn-control.edit:hover { background: #b45309; color: #ffffff; border-color: #b45309; }
    .tk-btn-control.del {
        border-color: #fca5a5;
        background: #fef2f2;
        color: #dc2626;
    }
    .tk-btn-control.del:hover { background: #dc2626; color: #ffffff; border-color: #dc2626; }
    .tk-btn-control.view {
        border-color: #e2e8f0;
        background: #ffffff;
        color: #475569;
    }
    .tk-btn-control.view:hover { background: #f1f5f9; color: #0f172a; }
    
    .tk-badge-msg {
        background: #dc2626;
        color: #ffffff;
        border-radius: 20px;
        padding: 1px 6px;
        font-size: 10px;
        font-weight: 700;
        margin-left: 2px;
    }

    /* Section divider line */
    .tk-v-line {
        border-left: 1px solid #e2e8f0;
        height: 100%;
        min-height: 48px;
    }
    @media (max-width: 991.98px) {
        .tk-v-line { display: none; }
    }

    /* Empty state */
    .tk-empty-container {
        background: #ffffff;
        border-radius: 12px;
        border: 2px dashed #cbd5e1;
        padding: 4rem 2rem;
        text-align: center;
    }

    /* General layout adjust */
    .pulse-dot-red {
        width: 6px;
        height: 6px;
        border-radius: 50%;
        background: #dc2626;
        display: inline-block;
        animation: pulseEffect 1.2s infinite;
    }
    @keyframes pulseEffect {
        0%, 100% { opacity: 1; transform: scale(1); }
        50% { opacity: 0.4; transform: scale(1.5); }
    }
</style>

<div class="ticket-page container-fluid px-0">
    
    {{-- ===== HEADER AREA ===== --}}
    <div class="tk-header-action-bar mb-4">
        <div class="row align-items-center g-3">
            <div class="col-md-6">
                <h5 class="mb-1 fw-bold text-dark"><i class="ti ti-ticket me-2 text-primary fs-3"></i>Pusat Layanan & Pengajuan Tiket IT</h5>
                <p class="text-muted small mb-0">Kelola dan pantau seluruh permohonan bantuan teknis serta sistem IT Anda secara efisien.</p>
            </div>
            <div class="col-md-6 text-md-end">
                <div class="d-inline-flex gap-2">
                    @hasrole('super admin')
                        <a href="{{ route('ticket.cetaklaporan', request()->all()) }}" target="_blank" class="btn btn-outline-secondary fw-semibold">
                            <i class="ti ti-printer me-1"></i> Cetak SLA
                        </a>
                        <a href="{{ route('ticketconfig.index') }}" class="btn btn-outline-primary fw-semibold">
                            <i class="ti ti-settings me-1"></i> Config Approval
                        </a>
                    @endhasrole
                    <button class="btn btn-primary fw-semibold" id="btnCreate">
                        <i class="ti ti-plus me-1"></i> Buat Tiket Ajuan
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- ===== STATS ROW ===== --}}
    <div class="tk-stat-grid mb-4">
        <div class="tk-stat-box">
            <div>
                <div class="tk-stat-title">Total Pengajuan</div>
                <div class="tk-stat-num mt-1">{{ number_format($stats['total'] ?? 0) }}</div>
            </div>
            <div class="tk-stat-icon-wrapper bg-light text-secondary">
                <i class="ti ti-tickets"></i>
            </div>
        </div>
        <div class="tk-stat-box">
            <div>
                <div class="tk-stat-title">Menunggu Persetujuan</div>
                <div class="tk-stat-num mt-1 text-warning">{{ number_format($stats['pending'] ?? 0) }}</div>
            </div>
            <div class="tk-stat-icon-wrapper bg-light-warning text-warning">
                <i class="ti ti-hourglass-low"></i>
            </div>
        </div>
        <div class="tk-stat-box">
            <div>
                <div class="tk-stat-title">Sedang Diproses IT</div>
                <div class="tk-stat-num mt-1 text-info">{{ number_format($stats['proses_it'] ?? 0) }}</div>
            </div>
            <div class="tk-stat-icon-wrapper bg-light-info text-info">
                <i class="ti ti-cpu"></i>
            </div>
        </div>
        <div class="tk-stat-box">
            <div>
                <div class="tk-stat-title">Selesai / Ditutup</div>
                <div class="tk-stat-num mt-1 text-success">{{ number_format($stats['selesai'] ?? 0) }}</div>
            </div>
            <div class="tk-stat-icon-wrapper bg-light-success text-success">
                <i class="ti ti-circle-check"></i>
            </div>
        </div>
    </div>

    {{-- ===== FILTER PANEL (NO CARD, NO LABELS) ===== --}}
    <div class="mb-4">
        <form action="{{ route('ticket.index') }}" method="GET">
            <div class="row g-2 align-items-center">
                @hasanyrole($roles_show_cabang)
                    <div class="col-lg-3 col-md-6">
                        <select name="kode_cabang_search" class="form-select select2" data-placeholder="Semua Cabang">
                            <option value="">Semua Cabang</option>
                            @foreach ($cabang as $c)
                                <option value="{{ $c->kode_cabang }}" {{ Request('kode_cabang_search') == $c->kode_cabang ? 'selected' : '' }}>
                                    {{ strtoupper($c->nama_cabang) }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                @endrole
                <div class="col-lg-3 col-md-6">
                    <select name="id_kategori_search" class="form-select select2" data-placeholder="Semua Kategori">
                        <option value="">Semua Kategori</option>
                        @foreach ($categories as $cat)
                            <option value="{{ $cat->id }}" {{ Request('id_kategori_search') == $cat->id ? 'selected' : '' }}>
                                {{ $cat->nama_kategori }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-lg-2 col-md-6">
                    <select name="status_search" class="form-select">
                        <option value="">Semua Status</option>
                        <option value="pending" {{ Request('status_search') == 'pending' ? 'selected' : '' }}>Belum Selesai</option>
                        <option value="selesai" {{ Request('status_search') == 'selesai' ? 'selected' : '' }}>Selesai</option>
                        <option value="ditolak" {{ Request('status_search') == 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                    </select>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="input-group">
                        <input type="text" name="keyword" class="form-control"
                            placeholder="Cari No. Tiket / Judul / Keterangan..."
                            value="{{ Request('keyword') }}">
                        <button class="btn btn-primary" type="submit">
                            <i class="ti ti-search"></i> Cari
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>

    {{-- ===== SECTION HEADER ===== --}}
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h6 class="text-dark fw-bold mb-0"><i class="ti ti-list-details me-1 text-primary"></i>Daftar Pengajuan Tiket</h6>
        <span class="badge bg-light text-secondary border fw-semibold">{{ $ticket->total() }} Data Ditemukan</span>
    </div>

    {{-- ===== TICKET ITEM CARDS LIST ===== --}}
    <div class="tk-container">
        @forelse ($ticket as $d)
            @php
                $currentUserId = auth()->user()->id;
                $userRoles = auth()->user()->roles->pluck('name')->toArray();
                $isApprover = false;
                if ($d->posisi_approval == 'ADMIN' && auth()->user()->hasRole(['super admin', 'admin maintenance'])) {
                    $isApprover = true;
                } elseif (auth()->user()->hasRole('super admin')) {
                    $isApprover = true;
                } elseif (in_array($d->posisi_approval, $userRoles)) {
                    $isApprover = true;
                } elseif ($d->posisi_approval == 'MANAGER_DEPT' && $d->id_manager_dept == $currentUserId) {
                    $isApprover = true;
                } elseif ($d->posisi_approval == 'SMM' && $d->id_smm == $currentUserId) {
                    $isApprover = true;
                } elseif ($d->posisi_approval == 'RSM' && $d->id_rsm == $currentUserId) {
                    $isApprover = true;
                } elseif ($d->posisi_approval == 'GM' && $d->id_gm == $currentUserId) {
                    $isApprover = true;
                }
                $priorityClass = match($d->priority) {
                    'Urgent' => 'priority-urgent',
                    'Tinggi' => 'priority-high',
                    'Sedang' => 'priority-medium',
                    default  => 'priority-low',
                };
            @endphp

            <div class="tk-item">
                <!-- TOP META STRIP -->
                <div class="tk-item-top">
                    <span class="tk-badge kode">{{ $d->kode_pengajuan }}</span>
                    <span class="text-muted small"><i class="ti ti-calendar me-1"></i>{{ date('d/m/Y', strtotime($d->tanggal)) }}</span>
                    <span class="tk-badge kategori">{{ $d->category->nama_kategori ?? 'Umum' }}</span>
                    <span class="tk-badge {{ $priorityClass }}">
                        @if($d->priority == 'Urgent') <span class="pulse-dot-red me-1"></span> @endif
                        {{ strtoupper($d->priority ?? 'Rendah') }}
                    </span>
                    <span class="ms-auto tk-badge cabang">{{ $d->kode_cabang }}</span>
                </div>

                <!-- CARD MAIN BODY -->
                <div class="tk-item-body">
                    <div class="row align-items-center g-3">
                        
                        <!-- Left Content Info -->
                        <div class="col-lg-5">
                            <h6 class="tk-item-title">{{ $d->judul ?? 'Pengajuan Tiket' }}</h6>
                            @if ($d->no_bukti)
                                <div class="tk-item-nobukti"><i class="ti ti-file-description me-1"></i>No. Bukti: {{ $d->no_bukti }}</div>
                                <br>
                            @endif
                            <p class="tk-item-desc text-secondary mb-2">{{ Str::limit($d->keterangan, 160) }}</p>
                            
                            @if ($d->lampiran || $d->link)
                                <div class="d-inline-flex gap-2 flex-wrap">
                                    @if ($d->lampiran)
                                        <a href="{{ asset($d->lampiran) }}" target="_blank" class="tk-link-btn doc">
                                            <i class="ti ti-paperclip"></i> Dokumen Lampiran
                                        </a>
                                    @endif
                                    @if ($d->link)
                                        <a href="{{ $d->link }}" target="_blank" class="tk-link-btn web">
                                            <i class="ti ti-link"></i> Link Referensi
                                        </a>
                                    @endif
                                </div>
                            @endif
                        </div>

                        <!-- Mid 1: Vertical Divider -->
                        <div class="col-auto tk-v-line"></div>

                        <!-- Mid 2: User Profile -->
                        <div class="col-lg-3">
                            <div class="tk-user-box mb-3">
                                <div class="tk-user-avatar">{{ strtoupper(substr($d->user->name ?? 'U', 0, 1)) }}</div>
                                <div>
                                    <div class="tk-user-name">{{ formatName2($d->user->name ?? '-') }}</div>
                                    <div class="tk-user-dept">{{ $d->user->kode_dept ?? '-' }}</div>
                                </div>
                            </div>
                            <div class="d-flex flex-column gap-1 align-items-start">
                                <div class="mb-1">{!! $d->badge_status !!}</div>
                                <div class="mb-1">{!! $d->badge_posisi !!}</div>
                                <div class="tk-sla"><i class="ti ti-clock-hour-4 me-1"></i>{{ $d->lama_penyelesaian }}</div>
                            </div>
                        </div>

                        <!-- Mid 3: Vertical Divider -->
                        <div class="col-auto tk-v-line"></div>

                        <!-- Right: Actions -->
                        <div class="col-lg-3 text-lg-end">
                            <div class="tk-action-group">
                                <button class="tk-btn-control chat btnMessage w-100"
                                    kode_pengajuan="{{ $d->kode_pengajuan }}"
                                    title="Diskusi / Chat Tiket">
                                    <i class="ti ti-message-dots"></i> Diskusi
                                    @if (($d->messages_count ?? 0) > 0)
                                        <span class="tk-badge-msg">{{ $d->messages_count }}</span>
                                    @endif
                                </button>

                                @if ($d->status == '0')
                                    @if ($isApprover)
                                        <button class="tk-btn-control approve btnApprove w-100 mt-1"
                                            kode_pengajuan="{{ $d->kode_pengajuan }}"
                                            title="Proses Approval Tiket">
                                            <i class="ti ti-check"></i> Approve
                                        </button>
                                    @endif
                                    @if ($d->id_user == $currentUserId || auth()->user()->hasRole('super admin'))
                                        <button class="tk-btn-control edit btnEdit w-100 mt-1"
                                            kode_pengajuan="{{ $d->kode_pengajuan }}"
                                            title="Edit Tiket">
                                            <i class="ti ti-edit"></i> Edit
                                        </button>
                                        <form method="POST"
                                            action="{{ route('ticket.delete', Crypt::encrypt($d->kode_pengajuan)) }}"
                                            class="deleteform w-100 mt-1">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="tk-btn-control del delete-confirm w-100">
                                                <i class="ti ti-trash"></i> Hapus
                                            </button>
                                        </form>
                                    @endif
                                @else
                                    <button class="tk-btn-control view btnApprove w-100"
                                        kode_pengajuan="{{ $d->kode_pengajuan }}"
                                        title="Lihat Detail Tiket">
                                        <i class="ti ti-eye"></i> Detail
                                    </button>
                                @endif
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        @empty
            <div class="tk-empty-container">
                <i class="ti ti-ticket-off text-muted display-4 d-block mb-3"></i>
                <h5 class="fw-bold text-secondary">Tidak Ada Tiket Ditemukan</h5>
                <p class="text-muted small mb-0">Silakan sesuaikan filter pencarian atau buat tiket baru jika ada kendala.</p>
            </div>
        @endforelse
    </div>

    {{-- ===== PAGINATION ===== --}}
    <div class="d-flex justify-content-between align-items-center mt-4">
        <div class="small text-muted">Menampilkan data tiket ajuan terbaru</div>
        <div>{{ $ticket->links('pagination::bootstrap-5') }}</div>
    </div>

</div>

{{-- Ticket Modals --}}
<x-modal-form id="modalTicket" size="modal-lg" show="loadmodalform" title="Tiket Ajuan" />
<x-modal-form id="modalTicketMessage" size="modal-lg" show="loadmodalmessage" title="Diskusi Tiket Ajuan" />

@endsection

@push('myscript')
<script>
    $(function() {
        function openTicketModal(url, title) {
            $("#modalTicket").modal("show");
            $("#modalTicket").find(".modal-title").text(title);
            $("#modalTicket").find(".loadmodalform").load(url);
        }

        $("#btnCreate").click(function(e) {
            e.preventDefault();
            openTicketModal("{{ route('ticket.create') }}", "Buat Tiket Ajuan Baru");
        });

        $(document).on("click", ".btnEdit", function(e) {
            e.preventDefault();
            let kp = $(this).attr("kode_pengajuan");
            openTicketModal("/ticket/" + kp + "/edit", "Edit Tiket Ajuan");
        });

        $(document).on("click", ".btnApprove", function(e) {
            e.preventDefault();
            let kp = $(this).attr("kode_pengajuan");
            openTicketModal("/ticket/" + kp + "/approve", "Detail & Persetujuan Tiket");
        });

        $(document).on("click", ".btnMessage", function(e) {
            e.preventDefault();
            let kp = $(this).attr("kode_pengajuan");
            $("#modalTicketMessage").modal("show");
            $("#modalTicketMessage").find(".modal-title").text("Diskusi Tiket: " + kp);
            $("#modalTicketMessage").find(".loadmodalform, .loadmodalmessage, #loadmodalmessage").load("/ticket/" + kp + "/message");
        });
    });
</script>
@endpush
