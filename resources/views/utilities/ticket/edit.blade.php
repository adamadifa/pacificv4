<form action="{{ route('ticket.update', Crypt::encrypt($ticket->kode_pengajuan)) }}" method="POST" id="formTicketEdit" enctype="multipart/form-data">
    @csrf
    @method('PUT')
    <div class="row">
        <div class="col-md-6 mb-3">
            <x-input-with-icon label="Tanggal Pengajuan" name="tanggal" value="{{ $ticket->tanggal }}" icon="ti ti-calendar" datepicker="flatpickr-date" readonly />
        </div>
        <div class="col-md-6 mb-3">
            <div class="form-group">
                <label class="form-label fw-bold">Tingkat Prioritas</label>
                <select name="priority" id="priority_edit" class="form-select">
                    <option value="Sedang" {{ $ticket->priority == 'Sedang' ? 'selected' : '' }}>Sedang (Normal)</option>
                    <option value="Rendah" {{ $ticket->priority == 'Rendah' ? 'selected' : '' }}>Rendah (Low)</option>
                    <option value="Tinggi" {{ $ticket->priority == 'Tinggi' ? 'selected' : '' }}>Tinggi (High)</option>
                    <option value="Urgent" {{ $ticket->priority == 'Urgent' ? 'selected' : '' }}>Urgent (Immediate)</option>
                </select>
            </div>
        </div>
    </div>

    <div class="form-group mb-3">
        <label class="form-label fw-bold">Jenis Tiket Ajuan <span class="text-danger">*</span></label>
        <select name="id_kategori" id="id_kategori_edit" class="form-select" required>
            <option value="">-- Pilih Jenis Tiket Ajuan --</option>
            @foreach ($categories as $cat)
                <option value="{{ $cat->id }}" {{ $ticket->id_kategori == $cat->id ? 'selected' : '' }}>{{ $cat->nama_kategori }}</option>
            @endforeach
        </select>
    </div>

    <div class="form-group mb-3">
        <x-input-with-icon label="Judul / Ringkasan Tiket" name="judul" value="{{ $ticket->judul }}" icon="ti ti-heading" required />
    </div>

    <div class="form-group mb-3">
        <x-input-with-icon label="No. Faktur / No. Bukti Transaksi (Opsional / Jika Ada)" name="no_bukti" value="{{ $ticket->no_bukti }}" icon="ti ti-file-description" />
    </div>

    <div class="form-group mb-3">
        <x-textarea label="Detail Keterangan & Alasan Pengajuan" name="keterangan" value="{{ $ticket->keterangan }}" required />
    </div>

    <div class="row">
        <div class="col-md-6 mb-3">
            <div class="form-group">
                <label class="form-label fw-bold">File Lampiran Dokumen Baru (Opsional)</label>
                <input type="file" name="lampiran" class="form-control" accept=".xlsx,.xls,.doc,.docx,.pdf,.jpg,.jpeg,.png,.zip,.rar">
                @if ($ticket->lampiran)
                    <small class="text-success d-block mt-1"><i class="ti ti-paperclip me-1"></i>Lampiran saat ini: <a href="{{ asset($ticket->lampiran) }}" target="_blank">Unduh Lampiran</a></small>
                @endif
            </div>
        </div>
        <div class="col-md-6 mb-3">
            <x-input-with-icon label="Link URL / Referensi Tambahan" name="link" value="{{ $ticket->link }}" icon="ti ti-link" />
        </div>
    </div>

    <div class="card mb-3 border-light shadow-none bg-lighter">
        <div class="card-body p-3">
            <h6 class="fw-bold mb-3 text-primary d-flex align-items-center">
                <i class="ti ti-settings-automation me-2 fs-4"></i>Kustomisasi Alur Persetujuan (Approval Flow)
            </h6>
            @if($ticket->kode_cabang != 'PST')
                <div class="row g-2">
                    <div class="col-md-4">
                        <div class="p-2 border rounded bg-white h-100">
                            <div class="form-check form-switch mb-1">
                                <input class="form-check-input" type="checkbox" name="perlu_smm" id="perlu_smm_edit" value="1" 
                                    {{ $ticket->id_smm ? 'checked' : '' }} 
                                    {{ !empty($ticket->smm_approved_at) ? 'disabled' : '' }}>
                                <label class="form-check-label fw-bold text-dark" for="perlu_smm_edit">Persetujuan SMM</label>
                            </div>
                            <select name="id_smm" id="id_smm_edit" class="form-select form-select-sm" 
                                {{ ($ticket->id_smm && empty($ticket->smm_approved_at)) ? '' : 'disabled' }}>
                                <option value="">-- Pilih SMM --</option>
                                @foreach($smmList as $u)
                                    <option value="{{ $u->id }}" {{ $ticket->id_smm == $u->id ? 'selected' : '' }}>{{ $u->name }}</option>
                                @endforeach
                            </select>
                            @if(!empty($ticket->smm_approved_at))
                                <small class="text-success d-block mt-1"><i class="ti ti-circle-check"></i> Sudah Disetujui</small>
                                <input type="hidden" name="perlu_smm" value="1">
                                <input type="hidden" name="id_smm" value="{{ $ticket->id_smm }}">
                            @endif
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="p-2 border rounded bg-white h-100">
                            <div class="form-check form-switch mb-1">
                                <input class="form-check-input" type="checkbox" name="perlu_rsm" id="perlu_rsm_edit" value="1" 
                                    {{ $ticket->id_rsm ? 'checked' : '' }} 
                                    {{ !empty($ticket->rsm_approved_at) ? 'disabled' : '' }}>
                                <label class="form-check-label fw-bold text-dark" for="perlu_rsm_edit">Persetujuan RSM</label>
                            </div>
                            <select name="id_rsm" id="id_rsm_edit" class="form-select form-select-sm" 
                                {{ ($ticket->id_rsm && empty($ticket->rsm_approved_at)) ? '' : 'disabled' }}>
                                <option value="">-- Pilih RSM --</option>
                                @foreach($rsmList as $u)
                                    <option value="{{ $u->id }}" {{ $ticket->id_rsm == $u->id ? 'selected' : '' }}>{{ $u->name }}</option>
                                @endforeach
                            </select>
                            @if(!empty($ticket->rsm_approved_at))
                                <small class="text-success d-block mt-1"><i class="ti ti-circle-check"></i> Sudah Disetujui</small>
                                <input type="hidden" name="perlu_rsm" value="1">
                                <input type="hidden" name="id_rsm" value="{{ $ticket->id_rsm }}">
                            @endif
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="p-2 border rounded bg-white h-100">
                            <div class="form-check form-switch mb-1">
                                <input class="form-check-input" type="checkbox" name="perlu_gm" id="perlu_gm_edit" value="1" 
                                    {{ $ticket->id_gm ? 'checked' : '' }} 
                                    {{ !empty($ticket->gm_approved_at) ? 'disabled' : '' }}>
                                <label class="form-check-label fw-bold text-dark" for="perlu_gm_edit">Persetujuan GM</label>
                            </div>
                            <select name="id_gm" id="id_gm_edit" class="form-select form-select-sm" 
                                {{ ($ticket->id_gm && empty($ticket->gm_approved_at)) ? '' : 'disabled' }}>
                                <option value="">-- Pilih GM --</option>
                                @foreach($gmList as $u)
                                    <option value="{{ $u->id }}" {{ $ticket->id_gm == $u->id ? 'selected' : '' }}>{{ $u->name }} ({{ $u->roles->pluck('name')->first() == 'gm marketing' ? 'GM Mkt' : ($u->roles->pluck('name')->first() == 'gm operasional' ? 'GM Ops' : 'GM Adm') }})</option>
                                @endforeach
                            </select>
                            @if(!empty($ticket->gm_approved_at))
                                <small class="text-success d-block mt-1"><i class="ti ti-circle-check"></i> Sudah Disetujui</small>
                                <input type="hidden" name="perlu_gm" value="1">
                                <input type="hidden" name="id_gm" value="{{ $ticket->id_gm }}">
                            @endif
                        </div>
                    </div>
                </div>
            @else
                <div class="row g-2">
                    <div class="col-md-12">
                        <div class="p-2 border rounded bg-white h-100">
                            <div class="form-check form-switch mb-1">
                                <input class="form-check-input" type="checkbox" name="perlu_manager_dept" id="perlu_manager_dept_edit" value="1" 
                                    {{ $ticket->id_manager_dept ? 'checked' : '' }}
                                    {{ !empty($ticket->manager_approved_at) ? 'disabled' : '' }}>
                                <label class="form-check-label fw-bold text-dark" for="perlu_manager_dept_edit">Persetujuan Manager Departemen</label>
                            </div>
                            <select name="id_manager_dept" id="id_manager_dept_edit" class="form-select form-select-sm"
                                {{ ($ticket->id_manager_dept && empty($ticket->manager_approved_at)) ? '' : 'disabled' }}>
                                <option value="">-- Pilih Manager --</option>
                                @foreach ($managerList as $u)
                                    <option value="{{ $u->id }}"
                                        {{ $ticket->id_manager_dept == $u->id ? 'selected' : '' }}>{{ $u->name }}
                                    </option>
                                @endforeach
                            </select>
                            @if(!empty($ticket->manager_approved_at))
                                <small class="text-success d-block mt-1"><i class="ti ti-circle-check"></i> Sudah Disetujui</small>
                                <input type="hidden" name="perlu_manager_dept" value="1">
                                <input type="hidden" name="id_manager_dept" value="{{ $ticket->id_manager_dept }}">
                            @endif
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <div class="form-group mb-2">
        <button class="btn btn-primary w-100 py-2 fs-6" id="btnUpdate"><i class="ti ti-device-floppy me-1"></i>Update Tiket Ajuan</button>
    </div>
</form>

<script>
    $(function() {
        $(".flatpickr-date").flatpickr();

        $("#perlu_smm_edit").change(function() {
            $("#id_smm_edit").prop('disabled', !$(this).is(':checked'));
        });
        $("#perlu_rsm_edit").change(function() {
            $("#id_rsm_edit").prop('disabled', !$(this).is(':checked'));
        });
        $("#perlu_gm_edit").change(function() {
            $("#id_gm_edit").prop('disabled', !$(this).is(':checked'));
        });
        $("#perlu_manager_dept_edit").change(function() {
            $("#id_manager_dept_edit").prop('disabled', !$(this).is(':checked'));
        });
    });
</script>
