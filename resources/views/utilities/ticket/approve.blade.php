<style>
    .stepper-timeline {
        display: flex;
        justify-content: space-between;
        align-items: stretch;
        gap: 16px;
        position: relative;
        flex-wrap: wrap;
    }
    
    .stepper-step {
        flex: 1;
        min-width: 120px;
        background: #ffffff;
        border: 1.5px solid #e2e8f0;
        border-radius: 14px;
        padding: 16px 12px;
        position: relative;
        transition: all 0.3s ease;
        display: flex;
        flex-direction: column;
        align-items: center;
        text-align: center;
        gap: 6px;
    }
    
    .stepper-step.active {
        border-color: #f59e0b;
        background: #fffbeb;
        box-shadow: 0 4px 12px rgba(245, 158, 11, 0.08);
    }
    
    .stepper-step.approved {
        border-color: #10b981;
        background: #ecfdf5;
        box-shadow: 0 4px 12px rgba(16, 185, 129, 0.08);
    }

    .stepper-step.it-processing {
        border-color: #3b82f6;
        background: #eff6ff;
        box-shadow: 0 4px 12px rgba(59, 130, 246, 0.08);
    }
    
    .step-icon-box {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        background: #cbd5e1;
        color: #ffffff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 0.9rem;
    }
    
    .stepper-step.approved .step-icon-box {
        background: #10b981;
    }
    
    .stepper-step.active .step-icon-box {
        background: #f59e0b;
    }

    .stepper-step.it-processing .step-icon-box {
        background: #3b82f6;
    }

    .ticket-info-card-premium {
        border-radius: 16px;
        border: 1px solid #e2e8f0;
        background: #f8fafc;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.02);
    }
</style>

<form action="{{ route('ticket.storeapprove', Crypt::encrypt($ticket->kode_pengajuan)) }}" method="POST" id="formApproveTicket">
    @csrf

    {{-- Ticket Information Card --}}
    <div class="card ticket-info-card-premium mb-3">
        <div class="card-body p-4">
            <div class="d-flex justify-content-between align-items-start mb-3">
                <div>
                    <span class="badge bg-primary-subtle text-primary border border-primary font-monospace fs-6 mb-2 px-2 py-1">{{ $ticket->kode_pengajuan }}</span>
                    <h4 class="fw-bold text-dark mb-0">{{ $ticket->judul ?? 'Pengajuan Tiket' }}</h4>
                </div>
                <div class="d-flex flex-column align-items-end gap-1">
                    {!! $ticket->badge_priority !!}
                    {!! $ticket->badge_status !!}
                </div>
            </div>

            <div class="row text-muted small my-3 g-2">
                <div class="col-md-4">
                    <span class="d-block text-uppercase text-muted fw-bold" style="font-size: 0.7rem;">Pembuat</span>
                    <span class="text-dark fw-semibold">{{ $ticket->user->name ?? '-' }}</span> <span class="badge bg-secondary-subtle text-secondary small">{{ $ticket->kode_cabang ?? '-' }} / {{ $ticket->kode_dept ?? '-' }}</span>
                </div>
                <div class="col-md-4">
                    <span class="d-block text-uppercase text-muted fw-bold" style="font-size: 0.7rem;">Tanggal</span>
                    <span class="text-dark fw-semibold"><i class="ti ti-calendar me-1"></i>{{ date('d-m-Y', strtotime($ticket->tanggal)) }}</span>
                </div>
                <div class="col-md-4">
                    <span class="d-block text-uppercase text-muted fw-bold" style="font-size: 0.7rem;">Kategori</span>
                    <span class="text-dark fw-semibold"><i class="ti ti-tag me-1"></i>{{ $ticket->category->nama_kategori ?? 'Umum' }}</span>
                </div>
            </div>

            <hr class="my-3">

            <div class="mb-3">
                <label class="fw-bold text-dark small mb-1">Keterangan / Detail Pengajuan:</label>
                <div class="p-3 bg-white rounded border text-secondary" style="line-height: 1.6; font-size: 0.9rem; white-space: pre-line;">{{ $ticket->keterangan }}</div>
            </div>

            @if ($ticket->lampiran || $ticket->link)
                <div class="d-flex gap-2 mt-2">
                    @if ($ticket->lampiran)
                        <a href="{{ asset($ticket->lampiran) }}" target="_blank" class="btn btn-sm btn-outline-danger px-3 py-2 rounded-pill">
                            <i class="ti ti-paperclip me-1"></i>Unduh File Lampiran
                        </a>
                    @endif
                    @if ($ticket->link)
                        <a href="{{ $ticket->link }}" target="_blank" class="btn btn-sm btn-outline-info px-3 py-2 rounded-pill">
                            <i class="ti ti-link me-1"></i>Buka Link Referensi
                        </a>
                    @endif
                </div>
            @endif
        </div>
    </div>

    {{-- Approval Stepper Timeline --}}
    <div class="card border-0 shadow-sm rounded-4 mb-3">
        <div class="card-header bg-light border-bottom py-3 px-4">
            <h6 class="mb-0 fw-bold text-dark"><i class="ti ti-git-merge me-2 text-primary fs-5"></i>Alur Status Persetujuan (Approval Flow)</h6>
        </div>
        <div class="card-body p-4">
            <div class="stepper-timeline">
                @if ($ticket->kode_cabang == 'PST')
                    {{-- PST Flow --}}
                    <div class="stepper-step {{ $ticket->manager_approved_at ? 'approved' : ($ticket->posisi_approval == 'MANAGER_DEPT' ? 'active' : '') }}">
                        <div class="step-icon-box">
                            @if ($ticket->manager_approved_at)
                                <i class="ti ti-check fs-6"></i>
                            @else
                                1
                            @endif
                        </div>
                        <div class="fw-bold small text-dark">Manager Dept</div>
                        <small class="text-muted d-block text-truncate" style="max-width: 140px;" title="{{ $ticket->managerDept->name ?? 'Dept Manager' }}">{{ $ticket->managerDept->name ?? 'Dept Manager' }}</small>
                        @if ($ticket->manager_approved_at)
                            <span class="badge bg-success-subtle text-success mt-1 small" style="font-size: 0.65rem;">Disetujui<br>{{ date('d/m H:i', strtotime($ticket->manager_approved_at)) }}</span>
                        @elseif($ticket->posisi_approval == 'MANAGER_DEPT' && $ticket->status == '0')
                            <span class="badge bg-warning-subtle text-warning mt-1 small" style="font-size: 0.65rem;">Menunggu</span>
                        @else
                            <span class="badge bg-secondary-subtle text-secondary mt-1 small" style="font-size: 0.65rem;">-</span>
                        @endif
                    </div>
                @else
                    {{-- Non-PST Flow --}}
                    <div class="stepper-step {{ $ticket->smm_approved_at ? 'approved' : ($ticket->posisi_approval == 'SMM' ? 'active' : '') }}">
                        <div class="step-icon-box">
                            @if ($ticket->smm_approved_at)
                                <i class="ti ti-check fs-6"></i>
                            @else
                                1
                            @endif
                        </div>
                        <div class="fw-bold small text-dark">SMM</div>
                        <small class="text-muted d-block text-truncate" style="max-width: 120px;" title="{{ $ticket->smmUser->name ?? 'SMM Cabang' }}">{{ $ticket->smmUser->name ?? 'SMM Cabang' }}</small>
                        @if ($ticket->smm_approved_at)
                            <span class="badge bg-success-subtle text-success mt-1 small" style="font-size: 0.65rem;">Disetujui</span>
                        @elseif($ticket->posisi_approval == 'SMM' && $ticket->status == '0')
                            <span class="badge bg-warning-subtle text-warning mt-1 small" style="font-size: 0.65rem;">Menunggu</span>
                        @else
                            <span class="badge bg-secondary-subtle text-secondary mt-1 small" style="font-size: 0.65rem;">-</span>
                        @endif
                    </div>

                    @if ($ticket->category && $ticket->category->perlu_rsm)
                        <div class="stepper-step {{ $ticket->rsm_approved_at ? 'approved' : ($ticket->posisi_approval == 'RSM' ? 'active' : '') }}">
                            <div class="step-icon-box">
                                @if ($ticket->rsm_approved_at)
                                    <i class="ti ti-check fs-6"></i>
                                @else
                                    2
                                @endif
                            </div>
                            <div class="fw-bold small text-dark">RSM</div>
                            <small class="text-muted d-block text-truncate" style="max-width: 120px;" title="{{ $ticket->rsmUser->name ?? 'RSM Regional' }}">{{ $ticket->rsmUser->name ?? 'RSM Regional' }}</small>
                            @if ($ticket->rsm_approved_at)
                                <span class="badge bg-success-subtle text-success mt-1 small" style="font-size: 0.65rem;">Disetujui</span>
                            @elseif($ticket->posisi_approval == 'RSM' && $ticket->status == '0')
                                <span class="badge bg-warning-subtle text-warning mt-1 small" style="font-size: 0.65rem;">Menunggu</span>
                            @else
                                <span class="badge bg-secondary-subtle text-secondary mt-1 small" style="font-size: 0.65rem;">-</span>
                            @endif
                        </div>
                    @endif

                    @if ($ticket->category && $ticket->category->perlu_gm)
                        <div class="stepper-step {{ $ticket->gm_approved_at ? 'approved' : ($ticket->posisi_approval == 'GM' ? 'active' : '') }}">
                            <div class="step-icon-box">
                                @if ($ticket->gm_approved_at)
                                    <i class="ti ti-check fs-6"></i>
                                @else
                                    3
                                @endif
                            </div>
                            <div class="fw-bold small text-dark">GM</div>
                            <small class="text-muted d-block text-truncate" style="max-width: 120px;" title="{{ $ticket->gmUser->name ?? 'GM' }}">{{ $ticket->gmUser->name ?? 'GM' }}</small>
                            @if ($ticket->gm_approved_at)
                                <span class="badge bg-success-subtle text-success mt-1 small" style="font-size: 0.65rem;">Disetujui</span>
                            @elseif($ticket->posisi_approval == 'GM' && $ticket->status == '0')
                                <span class="badge bg-warning-subtle text-warning mt-1 small" style="font-size: 0.65rem;">Menunggu</span>
                            @else
                                <span class="badge bg-secondary-subtle text-secondary mt-1 small" style="font-size: 0.65rem;">-</span>
                            @endif
                        </div>
                    @endif
                @endif

                {{-- IT Exec Final --}}
                <div class="stepper-step {{ $ticket->status == '1' ? 'approved' : ($ticket->posisi_approval == 'ADMIN' && $ticket->status == '0' ? 'it-processing' : '') }}">
                    <div class="step-icon-box">
                        @if ($ticket->status == '1')
                            <i class="ti ti-check fs-6"></i>
                        @else
                            <i class="ti ti-settings fs-6"></i>
                        @endif
                    </div>
                    <div class="fw-bold small text-dark">IT Admin</div>
                    <small class="text-muted d-block text-truncate" style="max-width: 120px;" title="{{ $ticket->adminUser->name ?? 'Eksekusi IT' }}">{{ $ticket->adminUser->name ?? 'Eksekusi IT' }}</small>
                    @if ($ticket->status == '1')
                        <span class="badge bg-success-subtle text-success mt-1 small" style="font-size: 0.65rem;">Selesai</span>
                    @elseif($ticket->posisi_approval == 'ADMIN' && $ticket->status == '0')
                        <span class="badge bg-primary-subtle text-primary mt-1 small" style="font-size: 0.65rem;">Proses IT</span>
                    @else
                        <span class="badge bg-secondary-subtle text-secondary mt-1 small" style="font-size: 0.65rem;">-</span>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- Decline Notes Input Area --}}
    <div id="declineNotesArea" class="form-group mb-3 d-none">
        <label class="form-label fw-bold text-danger">Alasan Penolakan Tiket <span class="text-danger">*</span></label>
        <textarea name="catatan_decline" id="catatan_decline" class="form-control border-danger" rows="3" placeholder="Masukkan alasan penolakan agar pembuat tiket dapat mengetahui alasannya..."></textarea>
    </div>

    {{-- Action Buttons --}}
    @if ($ticket->status == '0')
        <div class="d-flex gap-2">
            <button type="submit" name="approve" id="btnSubmitApprove" class="btn btn-success flex-grow-1 py-2 fw-bold">
                <i class="ti ti-check me-1"></i>Setujui Pengajuan Tiket
            </button>
            <button type="button" id="btnToggleDecline" class="btn btn-outline-danger py-2 fw-bold">
                <i class="ti ti-x me-1"></i>Tolak (Decline)
            </button>
            <button type="submit" name="decline" id="btnSubmitDecline" class="btn btn-danger py-2 fw-bold d-none">
                <i class="ti ti-x me-1"></i>Konfirmasi Penolakan
            </button>
        </div>
    @else
        <div class="alert alert-secondary text-center mb-0">
            <i class="ti ti-info-circle me-1"></i>Tiket ini sudah memiliki status akhir ({{ $ticket->status == '1' ? 'Selesai' : 'Ditolak' }}).
        </div>
    @endif
</form>

<script>
    $(function() {
        $("#btnToggleDecline").click(function() {
            $("#declineNotesArea").removeClass("d-none");
            $("#btnSubmitApprove").addClass("d-none");
            $(this).addClass("d-none");
            $("#btnSubmitDecline").removeClass("d-none");
            $("#catatan_decline").focus();
        });

        $("#formApproveTicket").submit(function(e) {
            if ($("#btnSubmitDecline").is(":visible") && $("#catatan_decline").val().trim() == "") {
                Swal.fire({
                    title: "Oops!",
                    text: "Alasan penolakan wajib diisi!",
                    icon: "warning"
                });
                return false;
            }
        });
    });
</script>
