<form action="{{ route('ticketcategory.store') }}" method="POST" id="formCategory" enctype="multipart/form-data">
    @csrf
    <div class="row">
        <div class="col-md-6 mb-3">
            <x-input-with-icon label="Kode Kategori (misal: PERBAIKAN)" name="kode_kategori" icon="ti ti-tag" required />
        </div>
        <div class="col-md-6 mb-3">
            <x-input-with-icon label="Nama Kategori Tiket" name="nama_kategori" icon="ti ti-file-text" required />
        </div>
    </div>

    <div class="mb-3">
        <x-textarea label="Keterangan / Deskripsi Kategori" name="keterangan" />
    </div>

    <div class="card bg-light border p-3 mb-3">
        <h6 class="fw-bold mb-2"><i class="ti ti-git-branch me-1 text-primary"></i>Pengaturan Level Approval yang Diperlukan:</h6>
        <div class="row">
            <div class="col-md-3 col-6 mb-2">
                <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" id="perlu_manager_dept" name="perlu_manager_dept" value="1" checked>
                    <label class="form-check-label fw-bold" for="perlu_manager_dept">Manager Dept (PST)</label>
                </div>
            </div>
            <div class="col-md-3 col-6 mb-2">
                <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" id="perlu_smm" name="perlu_smm" value="1" checked>
                    <label class="form-check-label fw-bold" for="perlu_smm">SMM (Cabang)</label>
                </div>
            </div>
            <div class="col-md-3 col-6 mb-2">
                <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" id="perlu_rsm" name="perlu_rsm" value="1" checked>
                    <label class="form-check-label fw-bold" for="perlu_rsm">RSM (Regional)</label>
                </div>
            </div>
            <div class="col-md-3 col-6 mb-2">
                <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" id="perlu_gm" name="perlu_gm" value="1">
                    <label class="form-check-label fw-bold" for="perlu_gm">GM (Regional/Pusat)</label>
                </div>
            </div>
        </div>
    </div>

    <div class="card bg-light border p-3 mb-3">
        <h6 class="fw-bold mb-2"><i class="ti ti-paperclip me-1 text-danger"></i>Pengaturan Lampiran & Format Template:</h6>
        <div class="form-check form-switch mb-3">
            <input class="form-check-input" type="checkbox" id="wajib_lampiran" name="wajib_lampiran" value="1">
            <label class="form-check-label fw-bold text-danger" for="wajib_lampiran">Wajib Upload File Lampiran saat Pengajuan Tiket</label>
        </div>
        <div class="form-group">
            <label class="form-label fw-bold">Upload File Format Template (.xlsx / .pdf / .docx)</label>
            <input type="file" name="template_file" class="form-control" accept=".xlsx,.xls,.pdf,.doc,.docx">
            <small class="text-muted d-block mt-1">User akan dapat mengunduh format ini saat mengajukan tiket jenis ini.</small>
        </div>
    </div>

    <div class="form-check form-switch mb-3">
        <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1" checked>
        <label class="form-check-label fw-bold" for="is_active">Status Aktif</label>
    </div>

    <div class="form-group mb-2">
        <button class="btn btn-primary w-100" id="btnSimpanCategory"><i class="ti ti-device-floppy me-1"></i>Simpan Master Kategori</button>
    </div>
</form>
