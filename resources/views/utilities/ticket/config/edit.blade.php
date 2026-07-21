@extends('layouts.app')
@section('titlepage', 'Edit Konfigurasi Approval Tiket')

@section('content')
@section('navigasi')
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h4 class="mb-0">Edit Konfigurasi Approval Tiket</h4>
            <small class="text-muted">Perbarui alur persetujuan tiket untuk departemen dan cabang ini.</small>
        </div>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0" style="font-size: 13px">
                <li class="breadcrumb-item"><a href="#"><i class="ti ti-folder me-1"></i>Utilities</a></li>
                <li class="breadcrumb-item"><a href="{{ route('ticket.index') }}">Tiket</a></li>
                <li class="breadcrumb-item"><a href="{{ route('ticketconfig.index') }}">Config Approval</a></li>
                <li class="breadcrumb-item active"><i class="ti ti-edit me-1"></i>Edit Config</li>
            </ol>
        </nav>
    </div>
@endsection

<style>
    .config-card { border: none; border-radius: 16px; box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05); transition: all 0.3s ease; background: #fff; }
    .step-number { width: 32px; height: 32px; background: linear-gradient(135deg, #4b6cb7 0%, #182848 100%); color: white; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 12px; margin-right: 15px; flex-shrink: 0; }
    .role-item { background: #fff; border: 1px solid #eef0f2; border-radius: 14px !important; padding: 0 !important; margin-bottom: 8px; transition: all 0.2s; }
    .role-item:hover { border-color: #4b6cb7; transform: translateX(4px); }
    .drag-handle { color: #adb5bd; cursor: move; font-size: 1.2rem; padding: 1rem; }
    .ui-state-highlight { height: 80px; background: rgba(75, 108, 183, 0.03); border: 2px dashed #4b6cb7; border-radius: 14px; margin-bottom: 8px; list-style: none; }
    .empty-state { border: 2px dashed #dee2e6; border-radius: 16px; padding: 4rem 2rem; text-align: center; }
    .btn-add-role { border-radius: 12px; padding: 0.6rem 1.5rem; font-weight: 600; }
    .flow-connector { width: 2px; height: 10px; background: #dee2e6; margin-left: 54px; margin-top: -8px; margin-bottom: -4px; }
    .list-group-item:last-child .flow-connector { display: none; }
    .floating-save { padding: 1.5rem 2rem; border-top: 1px solid rgba(0,0,0,0.05); background: #fafafa; border-radius: 0 0 16px 16px; }
    .gradient-btn { background: linear-gradient(135deg, #4b6cb7 0%, #182848 100%); border: none; box-shadow: 0 4px 15px rgba(75, 108, 183, 0.3); padding: 10px 24px; border-radius: 12px; font-weight: 600; transition: all 0.3s; color: white; }
    .gradient-btn:hover { transform: translateY(-2px); box-shadow: 0 8px 25px rgba(75, 108, 183, 0.4); color: white; }
    .admin-terminal { background: #fff3cd; border: 1px solid #ffc107; border-radius: 10px; padding: 10px 16px; font-size: 12px; font-weight: 600; color: #856404; display: flex; align-items: center; gap: 8px; }
</style>

<form action="{{ route('ticketconfig.update', $config->id) }}" method="POST" id="formConfig">
    @csrf
    @method('PUT')
    <div class="row g-4">
        <div class="col-lg-4">
            <div class="card config-card h-100">
                <div class="card-header border-bottom bg-transparent py-3">
                    <h5 class="mb-0 fw-bold"><i class="ti ti-target me-2 text-primary"></i>Cakupan Konfigurasi</h5>
                </div>
                <div class="card-body pt-4">
                    <div class="form-group mb-4">
                        <label class="form-label fw-semibold">Departemen <span class="text-muted fw-normal">(opsional)</span></label>
                        <select name="kode_dept" class="form-select select2">
                            <option value="">ALL DEPARTEMEN</option>
                            @foreach ($departemen as $d)
                                <option value="{{ $d->kode_dept }}" {{ $config->kode_dept == $d->kode_dept ? 'selected' : '' }}>
                                    {{ $d->nama_dept }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group mb-4">
                        <label class="form-label fw-semibold">Cabang <span class="text-muted fw-normal">(opsional)</span></label>
                        <select name="kode_cabang" class="form-select select2">
                            <option value="">ALL CABANG</option>
                            @foreach ($cabang as $d)
                                <option value="{{ $d->kode_cabang }}" {{ $config->kode_cabang == $d->kode_cabang ? 'selected' : '' }}>
                                    {{ $d->nama_cabang }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-8">
            <div class="card config-card h-100">
                <div class="card-header border-bottom bg-transparent py-3 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-bold"><i class="ti ti-git-merge me-2 text-primary"></i>Alur Persetujuan</h5>
                    <span class="badge bg-label-primary" id="step-count">0 Steps</span>
                </div>
                <div class="card-body pt-4">
                    <div class="d-flex gap-3 mb-4 p-3 bg-light rounded-3">
                        <div class="flex-grow-1">
                            <select id="role-selector" class="form-select select2">
                                <option value="">Pilih Role...</option>
                                @foreach ($roles as $d)
                                    <option value="{{ $d->name }}">{{ textUpperCase($d->name) }}</option>
                                @endforeach
                            </select>
                        </div>
                        <button type="button" class="btn btn-primary btn-add-role" id="btn-add-role">
                            <i class="ti ti-plus me-1"></i> Tambah Ke Alur
                        </button>
                    </div>

                    <div id="empty-state" class="empty-state" style="display:none;">
                        <i class="ti ti-layout-navbar-expand fs-1 mb-3 d-block"></i>
                        <h5>Belum Ada Alur Persetujuan</h5>
                        <p class="mb-0">Pilih role di atas untuk mulai merancang hierarki persetujuan tiket.</p>
                    </div>

                    <ul id="sortable-roles" class="list-group list-group-flush sortable-roles">
                        {{-- Pre-populated from existing config --}}
                        @foreach ($config->roles as $role)
                            <li class="list-group-item role-item border-0 p-0 mb-1" data-role="{{ $role }}">
                                <div class="d-flex align-items-center p-3 bg-white border rounded-3 position-relative">
                                    <i class="ti ti-menu-2 drag-handle me-3"></i>
                                    <div class="step-number"><span class="step-num-text">{{ $loop->iteration }}</span></div>
                                    <div class="flex-grow-1">
                                        <h6 class="mb-0 fw-bold">{{ textUpperCase($role) }}</h6>
                                        <input type="hidden" name="roles[]" value="{{ $role }}">
                                    </div>
                                    <button type="button" class="btn btn-sm btn-icon btn-label-danger btn-remove-role shadow-none border-0">
                                        <i class="ti ti-trash"></i>
                                    </button>
                                </div>
                                <div class="flow-connector"></div>
                            </li>
                        @endforeach
                    </ul>

                    <div class="admin-terminal mt-3" id="admin-terminal-indicator">
                        <i class="ti ti-terminal-2"></i>
                        <span>Setelah semua role di atas menyetujui, tiket akan masuk ke <strong>Admin IT</strong> untuk diproses.</span>
                    </div>
                </div>

                <div class="floating-save d-flex justify-content-end gap-2">
                    <a href="{{ route('ticketconfig.index') }}" class="btn btn-label-secondary px-4 border-0">Batal</a>
                    <button type="submit" class="btn gradient-btn shadow-sm">
                        <i class="ti ti-device-floppy me-2"></i>Update Konfigurasi
                    </button>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection

@push('myscript')
    <script>
        $(function() {
            $(".select2").select2();

            $("#sortable-roles").sortable({
                placeholder: "ui-state-highlight",
                handle: ".drag-handle",
                update: function() { updateStepNumbers(); }
            });

            function updateStepNumbers() {
                let count = 0;
                $(".role-item").each(function(index) {
                    $(this).find(".step-num-text").text(index + 1);
                    count++;
                });
                $("#step-count").text(count + " Steps");
                if (count > 0) {
                    $("#empty-state").hide();
                    $("#sortable-roles").show();
                    $("#admin-terminal-indicator").show();
                } else {
                    $("#empty-state").show();
                    $("#sortable-roles").hide();
                    $("#admin-terminal-indicator").hide();
                }
            }

            function addRole(role) {
                if (!role) return;
                let exists = false;
                $(".role-item").each(function() { if ($(this).data('role') === role) exists = true; });
                if (exists) { toastr.warning("Role sudah ada dalam alur ini."); return; }
                let roleUpper = role.toUpperCase();
                let item = `
                    <li class="list-group-item role-item border-0 p-0 mb-1" data-role="${role}">
                        <div class="d-flex align-items-center p-3 bg-white border rounded-3">
                            <i class="ti ti-menu-2 drag-handle me-3"></i>
                            <div class="step-number"><span class="step-num-text">0</span></div>
                            <div class="flex-grow-1">
                                <h6 class="mb-0 fw-bold">${roleUpper}</h6>
                                <input type="hidden" name="roles[]" value="${role}">
                            </div>
                            <button type="button" class="btn btn-sm btn-icon btn-label-danger btn-remove-role shadow-none border-0">
                                <i class="ti ti-trash"></i>
                            </button>
                        </div>
                        <div class="flow-connector"></div>
                    </li>`;
                $("#sortable-roles").append(item);
                $("#role-selector").val('').trigger('change');
                updateStepNumbers();
                $(".role-item").last().hide().fadeIn(300);
            }

            $("#btn-add-role").click(function() { addRole($("#role-selector").val()); });
            $(document).on('click', '.btn-remove-role', function() {
                $(this).closest('li').fadeOut(200, function() { $(this).remove(); updateStepNumbers(); });
            });

            updateStepNumbers();
        });
    </script>
@endpush
