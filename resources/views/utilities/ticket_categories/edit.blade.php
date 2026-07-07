<form action="{{ route('ticketcategory.update', $category->id) }}" method="POST" id="formCategoryEdit" enctype="multipart/form-data">
    @csrf
    @method('PUT')
    <div class="row">
        <div class="col-md-6 mb-3">
            <label class="form-label fw-bold">Kode Kategori</label>
            <input type="text" class="form-control bg-light" value="{{ $category->kode_kategori }}" readonly>
        </div>
        <div class="col-md-6 mb-3">
            <x-input-with-icon label="Nama Kategori Tiket" name="nama_kategori" value="{{ $category->nama_kategori }}" icon="ti ti-file-text" required />
        </div>
    </div>

    <div class="mb-3">
        <x-textarea label="Keterangan / Deskripsi Kategori" name="keterangan" value="{{ $category->keterangan }}" />
    </div>

    <div class="card bg-light border p-3 mb-3">
        <h6 class="fw-bold mb-2"><i class="ti ti-git-branch me-1 text-primary"></i>Pengaturan Level Approval yang Diperlukan:</h6>
        <div class="row">
            <div class="col-md-3 col-6 mb-2">
                <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" id="perlu_manager_dept_edit" name="perlu_manager_dept" value="1" {{ $category->perlu_manager_dept ? 'checked' : '' }}>
                    <label class="form-check-label fw-bold" for="perlu_manager_dept_edit">Manager Dept (PST)</label>
                </div>
            </div>
            <div class="col-md-3 col-6 mb-2">
                <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" id="perlu_smm_edit" name="perlu_smm" value="1" {{ $category->perlu_smm ? 'checked' : '' }}>
                    <label class="form-check-label fw-bold" for="perlu_smm_edit">SMM (Cabang)</label>
                </div>
            </div>
            <div class="col-md-3 col-6 mb-2">
                <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" id="perlu_rsm_edit" name="perlu_rsm" value="1" {{ $category->perlu_rsm ? 'checked' : '' }}>
                    <label class="form-check-label fw-bold" for="perlu_rsm_edit">RSM (Regional)</label>
                </div>
            </div>
            <div class="col-md-3 col-6 mb-2">
                <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" id="perlu_gm_edit" name="perlu_gm" value="1" {{ $category->perlu_gm ? 'checked' : '' }}>
                    <label class="form-check-label fw-bold" for="perlu_gm_edit">GM (Regional/Pusat)</label>
                </div>
            </div>
        </div>
    </div>

    <div class="card bg-light border p-3 mb-3">
        <h6 class="fw-bold mb-2"><i class="ti ti-paperclip me-1 text-danger"></i>Pengaturan Lampiran & Format Template:</h6>
        <div class="form-check form-switch mb-3">
            <input class="form-check-input" type="checkbox" id="wajib_lampiran_edit" name="wajib_lampiran" value="1" {{ $category->wajib_lampiran ? 'checked' : '' }}>
            <label class="form-check-label fw-bold text-danger" for="wajib_lampiran_edit">Wajib Upload File Lampiran saat Pengajuan Tiket</label>
        </div>
        <div class="form-group">
            <label class="form-label fw-bold">Upload File Format Template Baru (.xlsx / .pdf / .docx)</label>
            <input type="file" name="template_file" class="form-control" accept=".xlsx,.xls,.pdf,.doc,.docx">
            @if ($category->template_file)
                <small class="text-success d-block mt-1"><i class="ti ti-file-check me-1"></i>Template saat ini: <a href="{{ asset($category->template_file) }}" target="_blank">Unduh File Template</a></small>
            @endif
        </div>
    </div>

    <div class="form-check form-switch mb-3">
        <input class="form-check-input" type="checkbox" id="is_active_edit" name="is_active" value="1" {{ $category->is_active ? 'checked' : '' }}>
        <label class="form-check-label fw-bold" for="is_active_edit">Status Aktif</label>
    </div>

    <div class="form-group mb-2">
        <button class="btn btn-primary w-100" id="btnUpdateCategory"><i class="ti ti-device-floppy me-1"></i>Update Master Kategori</button>
    </div>
</form>
