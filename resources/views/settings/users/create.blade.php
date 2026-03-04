<form action="{{ route('users.store') }}" id="formcreateUser" method="POST">
    @csrf
    <div class="row g-3">
        {{-- Basic Information Section --}}
        <div class="col-12">
            <h6 class="fw-bold mb-2 border-bottom pb-1"><i class="ti ti-user me-2"></i>Informasi Akun</h6>
            <div class="row g-2">
                <div class="col-md-6">
                    <x-input-with-icon icon="ti ti-user" label="Nama Lengkap" name="name" />
                </div>
                <div class="col-md-6">
                    <x-input-with-icon icon="ti ti-user-circle" label="Username" name="username" />
                </div>
                <div class="col-md-6">
                    <x-input-with-icon icon="ti ti-mail" label="Email" name="email" />
                </div>
                <div class="col-md-6">
                    <x-input-with-icon icon="ti ti-key" label="Password" name="password" type="password" />
                </div>
            </div>
        </div>

        {{-- Organization Section --}}
        <div class="col-12 mt-4">
            <h6 class="fw-bold mb-2 border-bottom pb-1"><i class="ti ti-building me-2"></i>Organisasi & Role</h6>
            <div class="row g-2">
                <div class="col-md-6">
                    <x-select label="Role Utama" name="role" :data="$roles" key="name" textShow="name" hideLabel="true" />
                </div>
                <div class="col-md-6">
                    <x-select label="Departemen Utama" name="kode_dept" :data="$departemen" key="kode_dept" textShow="nama_dept" hideLabel="true" />
                </div>
                <div class="col-md-6">
                    <x-select label="Cabang" name="kode_cabang" :data="$cabang" key="kode_cabang" textShow="nama_cabang" hideLabel="true" />
                </div>
                <div class="col-md-6">
                    <x-select label="Regional" name="kode_regional" :data="$regional" key="kode_regional" textShow="nama_regional" hideLabel="true" />
                </div>
            </div>
        </div>

        {{-- Access Section --}}
        <div class="col-12 mt-4">
            <h6 class="fw-bold mb-2 border-bottom pb-1"><i class="ti ti-lock-access me-2"></i>Akses Tambahan</h6>
            <div class="row">
                <div class="col-12">
                    <x-select name="dept_access[]" :data="$departemen" key="kode_dept" textShow="nama_dept" multiple="true" select2="select2DeptAccess" />
                </div>
                <div class="col-12">
                    <x-select name="jabatan_access[]" :data="$jabatan" key="kode_jabatan" textShow="nama_jabatan" multiple="true" select2="select2JabatanAccess" />
                </div>
                <div class="col-12">
                    <div class="form-group mb-3">
                        <select name="karyawan_access[]" id="karyawan_access" class="form-select select2KaryawanAccess" multiple="multiple">
                            <option value="all">Semua Karyawan</option>
                            @foreach ($karyawan as $k)
                                <option value="{{ $k->nik }}">{{ $k->nik }} | {{ $k->nama_karyawan }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-12">
                    <x-select name="group_access[]" :data="$group" key="kode_group" textShow="nama_group" multiple="true" select2="select2GroupAccess" />
                </div>
            </div>
        </div>
    </div>

    <div class="mt-4 pt-2">
        <button class="btn btn-primary w-100 py-2 shadow-sm" type="submit">
            <i class="ti ti-send me-2 fs-5"></i>
            Simpan Data User
        </button>
    </div>
</form>

<style>
    .card-access-item {
        padding: 8px 12px;
        background: #fff;
        border-radius: 8px;
        border: 1px solid #eef0f2;
        margin-bottom: 4px;
        transition: all 0.2s;
    }
    .card-access-item:hover {
        border-color: #7367f0;
        background: #f8f7ff;
    }
    .form-check-input:checked + .form-check-label {
        color: #7367f0;
    }
</style>

<script src="{{ asset('/assets/vendor/libs/@form-validation/umd/bundle/popular.min.js') }}"></script>
<script src="{{ asset('/assets/vendor/libs/@form-validation/umd/plugin-bootstrap5/index.min.js') }}"></script>
<script src="{{ asset('/assets/vendor/libs/@form-validation/umd/plugin-auto-focus/index.min.js') }}"></script>
<script src="{{ asset('assets/js/pages/users/create.js') }}"></script>
<script>
    $(document).ready(function() {
        const select2DeptAccess = $(".select2DeptAccess");
        const select2JabatanAccess = $(".select2JabatanAccess");

        if (select2DeptAccess.length > 0) {
            select2DeptAccess.each(function() {
                var $this = $(this);
                $this.wrap('<div class="position-relative"></div>').select2({
                    placeholder: 'Pilih Departemen',
                    allowClear: true,
                    dropdownParent: $this.parent()
                });
            });
        }

        if (select2JabatanAccess.length > 0) {
            select2JabatanAccess.each(function() {
                var $this = $(this);
                $this.wrap('<div class="position-relative"></div>').select2({
                    placeholder: 'Pilih Jabatan',
                    allowClear: true,
                    dropdownParent: $this.parent()
                });
            });
        }

        const select2KaryawanAccess = $(".select2KaryawanAccess");
        if (select2KaryawanAccess.length > 0) {
            select2KaryawanAccess.each(function() {
                var $this = $(this);
                $this.wrap('<div class="position-relative"></div>').select2({
                    placeholder: 'Pilih Karyawan',
                    allowClear: true,
                    dropdownParent: $this.parent()
                });
            });
        }

        const select2GroupAccess = $(".select2GroupAccess");
        if (select2GroupAccess.length > 0) {
            select2GroupAccess.each(function() {
                var $this = $(this);
                $this.wrap('<div class="position-relative"></div>').select2({
                    placeholder: 'Pilih Grup',
                    allowClear: true,
                    dropdownParent: $this.parent()
                });
            });
        }
    });
</script>
