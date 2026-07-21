<style>
    .stepper-timeline {
        display: flex;
        justify-content: space-between;
        align-items: stretch;
        gap: 12px;
        position: relative;
        flex-wrap: wrap;
    }
    
    .stepper-step {
        flex: 1;
        min-width: 130px;
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 10px;
        padding: 12px 10px;
        position: relative;
        transition: all 0.2s ease;
        display: flex;
        flex-direction: column;
        align-items: center;
        text-align: center;
        gap: 4px;
    }
    
    .stepper-step.active {
        border-color: #fbbf24;
        background: #fffbeb;
        box-shadow: 0 4px 10px rgba(251, 191, 36, 0.05);
    }
    
    .stepper-step.approved {
        border-color: #10b981;
        background: #ecfdf5;
        box-shadow: 0 4px 10px rgba(16, 185, 129, 0.05);
    }

    .stepper-step.it-processing {
        border-color: #3b82f6;
        background: #eff6ff;
        box-shadow: 0 4px 10px rgba(59, 130, 246, 0.05);
    }
    
    .step-icon-box {
        width: 28px;
        height: 28px;
        border-radius: 50%;
        background: #cbd5e1;
        color: #ffffff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 0.8rem;
    }
    
    .stepper-step.approved .step-icon-box {
        background: #10b981;
    }
    
    .stepper-step.active .step-icon-box {
        background: #fbbf24;
    }

    .stepper-step.it-processing .step-icon-box {
        background: #3b82f6;
    }

    .ticket-info-card-premium {
        border-radius: 12px;
        border: 1px solid #e2e8f0;
        background: #f8fafc;
    }
</style>

<form action="{{ route('ticket.storeapprove', Crypt::encrypt($ticket->kode_pengajuan)) }}" method="POST" id="formApproveTicket">
    @csrf

    {{-- Ticket Information --}}
    <div class="card ticket-info-card-premium border-0 shadow-none mb-3">
        <div class="card-body p-3">
            <div class="d-flex justify-content-between align-items-start mb-3">
                <div>
                    <span class="badge bg-primary-subtle text-primary border border-primary font-monospace fs-6 mb-2 px-2 py-1">{{ $ticket->kode_pengajuan }}</span>
                    <h5 class="fw-bold text-dark mb-0">{{ $ticket->judul ?? 'Pengajuan Tiket' }}</h5>
                </div>
                <div class="d-flex flex-column align-items-end gap-1">
                    {!! $ticket->badge_priority !!}
                    {!! $ticket->badge_status !!}
                    {!! $ticket->badge_posisi !!}
                </div>
            </div>

            <div class="row text-muted small my-3 g-2">
                <div class="col-md-4">
                    <span class="d-block text-uppercase text-muted fw-bold mb-1" style="font-size: 0.65rem;">Pembuat</span>
                    <span class="text-dark fw-semibold">{{ $ticket->user->name ?? '-' }}</span> 
                    <span class="badge bg-secondary-subtle text-secondary small ms-1">{{ $ticket->kode_cabang ?? '-' }} / {{ $ticket->kode_dept ?? '-' }}</span>
                </div>
                <div class="col-md-4">
                    <span class="d-block text-uppercase text-muted fw-bold mb-1" style="font-size: 0.65rem;">Tanggal</span>
                    <span class="text-dark fw-semibold"><i class="ti ti-calendar me-1"></i>{{ date('d-m-Y', strtotime($ticket->tanggal)) }}</span>
                </div>
                <div class="col-md-4">
                    <span class="d-block text-uppercase text-muted fw-bold mb-1" style="font-size: 0.65rem;">Kategori</span>
                    <span class="text-dark fw-semibold"><i class="ti ti-tag me-1"></i>{{ $ticket->category->nama_kategori ?? 'Umum' }}</span>
                </div>
            </div>

            <hr class="my-3">

            <div class="mb-3">
                <label class="fw-bold text-dark small mb-1">Keterangan / Detail Pengajuan:</label>
                <div class="p-3 bg-white rounded border text-secondary" style="line-height: 1.6; font-size: 0.85rem; white-space: pre-line;">{{ $ticket->keterangan }}</div>
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

    {{-- Approval Stepper Timeline (DYNAMIC CONFIG) --}}
    <div class="card border-0 shadow-none mb-3">
        <div class="card-header bg-light border rounded-top py-2 px-3">
            <h6 class="mb-0 fw-bold text-dark small"><i class="ti ti-git-merge me-2 text-primary"></i>Alur Status Persetujuan (Approval Flow)</h6>
        </div>
        <div class="card-body border border-top-0 rounded-bottom p-3">
            <div class="stepper-timeline">
                @php
                    $currentPosisi = $ticket->posisi_approval;
                    $status = $ticket->status;
                    $foundCurrent = false;
                @endphp

                @foreach ($configRoles as $role)
                    @php
                        $stepClass = '';
                        $isCurrent = ($currentPosisi == $role && $status == '0');
                        
                        if ($isCurrent) {
                            $stepClass = 'active';
                            $foundCurrent = true;
                        } elseif ($status == '1' || $status == '2') {
                            $stepClass = 'approved';
                        } elseif (!$foundCurrent) {
                            $stepClass = 'approved';
                        }
                    @endphp
                    <div class="stepper-step {{ $stepClass }}">
                        <div class="step-icon-box">
                            @if ($stepClass == 'approved')
                                <i class="ti ti-check small"></i>
                            @else
                                {{ $loop->iteration }}
                            @endif
                        </div>
                        <div class="fw-bold small text-dark">{{ strtoupper($role) }}</div>
                        @if ($isCurrent)
                            <span class="badge bg-warning-subtle text-warning mt-1 small" style="font-size: 0.6rem;">Menunggu</span>
                        @elseif ($stepClass == 'approved')
                            <span class="badge bg-success-subtle text-success mt-1 small" style="font-size: 0.6rem;">Selesai</span>
                        @else
                            <span class="badge bg-secondary-subtle text-secondary mt-1 small" style="font-size: 0.6rem;">-</span>
                        @endif
                    </div>
                @endforeach

                {{-- IT Exec Final --}}
                @php
                    $adminClass = '';
                    if ($status == '1') {
                        $adminClass = 'approved';
                    } elseif ($currentPosisi == 'ADMIN' && $status == '0') {
                        $adminClass = 'it-processing';
                    }
                @endphp
                <div class="stepper-step {{ $adminClass }}">
                    <div class="step-icon-box">
                        @if ($status == '1')
                            <i class="ti ti-check small"></i>
                        @else
                            <i class="ti ti-settings small"></i>
                        @endif
                    </div>
                    <div class="fw-bold small text-dark">IT Admin</div>
                    @if ($status == '1')
                        <span class="badge bg-success-subtle text-success mt-1 small" style="font-size: 0.6rem;">Selesai</span>
                    @elseif ($currentPosisi == 'ADMIN' && $status == '0')
                        <span class="badge bg-primary-subtle text-primary mt-1 small" style="font-size: 0.6rem;">Proses IT</span>
                    @else
                        <span class="badge bg-secondary-subtle text-secondary mt-1 small" style="font-size: 0.6rem;">-</span>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- Decline Notes Input Area --}}
    <div id="declineNotesArea" class="form-group mb-3 d-none">
        <label class="form-label fw-bold text-danger">Alasan Penolakan Tiket <span class="text-danger">*</span></label>
        <textarea name="catatan_decline" id="catatan_decline" class="form-control border-danger" rows="3" placeholder="Masukkan alasan penolakan..."></textarea>
    </div>

    @hasrole('super admin')
        <div class="card border border-warning bg-light-warning mb-3">
            <div class="card-body p-3">
                <label class="form-label fw-bold text-warning-dark mb-1">
                    <i class="ti ti-shield-check me-1"></i> Super Admin Panel Override
                </label>
                <p class="text-muted small mb-2">Sebagai super admin, Anda dapat langsung mengalihkan posisi persetujuan tiket ini ke role lain.</p>
                <select name="manual_posisi_approval" class="form-select select2-no-search">
                    <option value="">-- Tetap Ikuti Alur Approval Normal --</option>
                    @foreach ($roles as $role)
                        <option value="{{ $role }}" {{ $ticket->posisi_approval == $role ? 'selected' : '' }}>
                            Pindahkan ke: {{ strtoupper($role) }}
                        </option>
                    @endforeach
                    <option value="ADMIN" {{ $ticket->posisi_approval == 'ADMIN' ? 'selected' : '' }}>
                        Pindahkan ke: ADMIN IT (Proses IT)
                    </option>
                    <option value="SELESAI" {{ $ticket->posisi_approval == 'SELESAI' ? 'selected' : '' }}>
                        Pindahkan ke: SELESAI (Selesaikan Tiket)
                    </option>
                </select>
            </div>
        </div>
    @endhasrole

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
