<form action="{{ route('ticket.store') }}" method="POST" id="formTicket" enctype="multipart/form-data">
    @csrf
    <div class="row">
        <div class="col-md-6 mb-3">
            <x-input-with-icon label="Tanggal Pengajuan" name="tanggal" value="{{ date('Y-m-d') }}"
                icon="ti ti-calendar" datepicker="flatpickr-date" readonly />
        </div>
        <div class="col-md-6 mb-3">
            <div class="form-group">
                <label class="form-label fw-bold">Tingkat Prioritas</label>
                <select name="priority" id="priority" class="form-select">
                    <option value="Sedang" selected>Sedang (Normal)</option>
                    <option value="Rendah">Rendah (Low)</option>
                    <option value="Tinggi">Tinggi (High)</option>
                    <option value="Urgent">Urgent (Immediate)</option>
                </select>
            </div>
        </div>
    </div>

    <div class="form-group mb-3">
        <label class="form-label fw-bold">Jenis Tiket Ajuan <span class="text-danger">*</span></label>
        <select name="id_kategori" id="id_kategori" class="form-select" required>
            <option value="">-- Pilih Jenis Tiket Ajuan --</option>
            @foreach ($categories as $cat)
                <option value="{{ $cat->id }}">{{ $cat->nama_kategori }}</option>
            @endforeach
        </select>
    </div>

    {{-- Category Dynamic Notification & Template Card --}}
    <div id="categoryNoticeCard" class="card bg-lighter border border-info mb-3 d-none">
        <div class="card-body p-3">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <span class="fw-bold text-info"><i class="ti ti-info-circle me-1"></i>Informasi & Persyaratan Kategori
                    Tiket</span>
                <a href="#" id="btnDownloadTemplate" class="btn btn-sm btn-info d-none" target="_blank">
                    <i class="ti ti-download me-1"></i>Download Format Template
                </a>
            </div>
            <p id="categoryKeteranganText" class="small text-muted mb-0"></p>
            <div id="lampiranNotice" class="mt-2 text-danger small font-weight-bold d-none">
                <i class="ti ti-alert-circle me-1"></i>Kategori ini **WAJIB** melampirkan file dokumen format hasil isi
                template!
            </div>
        </div>
    </div>

    <div class="form-group mb-3">
        <x-input-with-icon label="Judul / Ringkasan Tiket" name="judul" icon="ti ti-heading"
            placeholder="Contoh: Permintaan Tambah Menu Laporan Penjualan Baru" required />
    </div>

    <div class="form-group mb-3">
        <x-input-with-icon label="No. Faktur / No. Bukti Transaksi (Opsional / Jika Ada)" name="no_bukti"
            icon="ti ti-file-description" placeholder="Contoh: FK2026070001 / PJ-260701" />
    </div>

    <div class="form-group mb-3">
        <x-textarea label="Detail Keterangan & Alasan Pengajuan" name="keterangan"
            placeholder="Jelaskan secara rinci detail kebutuhan atau perbaikan yang diajukan..." required />
    </div>

    <div class="row">
        <div class="col-md-6 mb-3">
            <div class="form-group">
                <label class="form-label fw-bold" id="labelLampiran">File Lampiran Dokumen</label>
                <input type="file" name="lampiran" id="lampiran" class="form-control"
                    accept=".xlsx,.xls,.doc,.docx,.pdf,.jpg,.jpeg,.png,.zip,.rar">
                <small class="text-muted">Format yang didukung: Excel, Word, PDF, Gambar, Zip (Maks 10MB)</small>
            </div>
        </div>
        <div class="col-md-6 mb-3">
            <x-input-with-icon label="Link URL / Referensi Tambahan (Opsional)" name="link" icon="ti ti-link"
                placeholder="https://..." />
        </div>
    </div>

    <div class="card mb-3 border-light shadow-none bg-lighter">
        <div class="card-body p-3">
            <h6 class="fw-bold mb-3 text-primary d-flex align-items-center">
                <i class="ti ti-settings-automation me-2 fs-4"></i>Kustomisasi Alur Persetujuan (Approval Flow)
            </h6>
            @if (auth()->user()->kode_cabang != 'PST')
                <div class="row g-2">
                    <div class="col-md-4">
                        <div class="p-2 border rounded bg-white h-100">
                            <!-- <div class="form-check form-switch mb-1">
                                            <input class="form-check-input" type="checkbox" name="perlu_smm" id="perlu_smm" value="1">
                                            <label class="form-check-label fw-bold text-dark" for="perlu_smm">Persetujuan
                                                SMM</label>
                                        </div> -->
                            <select name="id_smm" id="id_smm" class="form-select form-select-sm">
                                <!-- <option value="">-- Pilih SMM --</option> -->
                                @foreach ($smmList as $u)
                                    <option value="{{ $u->id }}" {{ $u->id == auth()->user()->id ? '' : 'selected' }}>
                                        {{ $u->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <!-- <div class="col-md-4">
                                            <div class="p-2 border rounded bg-white h-100">
                                                <div class="form-check form-switch mb-1">
                                                    <input class="form-check-input" type="checkbox" name="perlu_rsm" id="perlu_rsm"
                                                        value="1">
                                                    <label class="form-check-label fw-bold text-dark" for="perlu_rsm">Persetujuan
                                                        RSM</label>
                                                </div>
                                                <select name="id_rsm" id="id_rsm" class="form-select form-select-sm" disabled>
                                                    <option value="">-- Pilih RSM --</option>
                                                    @foreach ($rsmList as $u)
                                                        <option value="{{ $u->id }}" selected>{{ $u->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div> -->
                    <div class="col-md-4">
                        <div class="p-2 border rounded bg-white h-100">
                            <!-- <div class="form-check form-switch mb-1">
                                            <input class="form-check-input" type="checkbox" name="perlu_gm" id="perlu_gm" value="1">
                                            <label class="form-check-label fw-bold text-dark" for="perlu_gm">Persetujuan
                                                GM</label>
                                        </div> -->
                            <select name="id_gm" id="id_gm" class="form-select form-select-sm">
                                <!-- <option value="">-- Pilih GM --</option> -->
                                @foreach ($gmList as $u)
                                    <option value="{{ $u->id }}" {{ $u->id == 20 ? 'selected' : '' }}>
                                        {{ $u->name }}
                                        ({{ $u->roles->pluck('name')->first() == 'gm marketing' ? 'GM Mkt' : ($u->roles->pluck('name')->first() == 'gm operasional' ? 'GM Ops' : 'GM Adm') }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            @else
                <div class="row g-2">
                    <div class="col-md-12">
                        <div class="p-2 border rounded bg-white h-100">
                            <!-- <div class="form-check form-switch mb-1">
                                    <input class="form-check-input" type="checkbox" name="perlu_manager_dept"
                                        id="perlu_manager_dept" value="1" checked>
                                    <label class="form-check-label fw-bold text-dark" for="perlu_manager_dept">Persetujuan
                                        Manager Departemen</label>
                                </div> -->
                            <select name="id_manager_dept" id="id_manager_dept" class="form-select form-select-sm">
                                <!-- <option value="">-- Pilih Manager --</option> -->
                                @foreach ($managerList as $u)
                                    <option value="{{ $u->id }}" {{ $u->id == auth()->user()->id ? '' : 'selected' }}>
                                        {{ $u->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <div class="alert alert-warning d-flex align-items-center p-2 mb-3">
        <i class="ti ti-shield-check fs-4 me-2"></i>
        <small>Tiket akan diproses sesuai alur persetujuan bertingkat cabang/departemen Anda sebelum dieksekusi oleh tim
            IT Admin.</small>
    </div>

    <div class="form-group mb-2">
        <button class="btn btn-primary w-100 py-2 fs-6" id="btnSimpan"><i class="ti ti-ticket me-1"></i>Kirim Tiket
            Ajuan Baru</button>
    </div>
</form>

<script>
    $(function () {
        $(".flatpickr-date").flatpickr();

        $("#id_kategori").change(function () {
            let categoryId = $(this).val();
            if (categoryId != "") {
                $.ajax({
                    url: "/ticket/category-detail/" + categoryId,
                    type: "GET",
                    dataType: "json",
                    success: function (response) {
                        if (response.status == "success") {
                            let cat = response.data;
                            $("#categoryNoticeCard").removeClass("d-none");
                            $("#categoryKeteranganText").text(cat.keterangan ||
                                "Tidak ada keterangan tambahan.");

                            if (cat.template_file) {
                                $("#btnDownloadTemplate").attr("href",
                                    "/ticket/download-template/" + cat.id).removeClass(
                                        "d-none");
                            } else {
                                $("#btnDownloadTemplate").addClass("d-none");
                            }

                            if (cat.wajib_lampiran) {
                                $("#lampiranNotice").removeClass("d-none");
                                $("#labelLampiran").html(
                                    'File Lampiran Dokumen <span class="text-danger">* (Wajib Upload)</span>'
                                );
                                $("#lampiran").prop('required', true);
                            } else {
                                $("#lampiranNotice").addClass("d-none");
                                $("#labelLampiran").html(
                                    'File Lampiran Dokumen (Opsional)');
                                $("#lampiran").prop('required', false);
                            }

                            // Auto check/uncheck based on category default rules
                            $("#perlu_smm").prop('checked', cat.perlu_smm == 1).trigger(
                                'change');
                            $("#perlu_rsm").prop('checked', cat.perlu_rsm == 1).trigger(
                                'change');
                            $("#perlu_gm").prop('checked', cat.perlu_gm == 1).trigger(
                                'change');
                        }
                    }
                });
            } else {
                $("#categoryNoticeCard").addClass("d-none");
                $("#labelLampiran").html('File Lampiran Dokumen');
                $("#lampiran").prop('required', false);
            }
        });

        $("#perlu_smm").change(function () {
            $("#id_smm").prop('disabled', !$(this).is(':checked'));
        });
        $("#perlu_rsm").change(function () {
            $("#id_rsm").prop('disabled', !$(this).is(':checked'));
        });
        $("#perlu_gm").change(function () {
            $("#id_gm").prop('disabled', !$(this).is(':checked'));
        });
        $("#perlu_manager_dept").change(function () {
            $("#id_manager_dept").prop('disabled', !$(this).is(':checked'));
        });

        $("#formTicket").submit(function (e) {
            let id_kategori = $(this).find("#id_kategori").val();
            let judul = $(this).find("#judul").val();
            let keterangan = $(this).find("#keterangan").val();

            if (id_kategori == "" || judul == "" || keterangan == "") {
                Swal.fire({
                    title: "Oops!",
                    text: "Mohon lengkapi semua field yang wajib diisi!",
                    icon: "warning",
                    showConfirmButton: true,
                });
                return false;
            } else {
                $("#btnSimpan").prop('disabled', true);
                $("#btnSimpan").html(
                    '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Mengirim Tiket...'
                );
            }
        });
    });
</script>