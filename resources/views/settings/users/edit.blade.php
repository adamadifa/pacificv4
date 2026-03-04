<form action="{{ route('users.update', Crypt::encrypt($user->id)) }}" id="formeditUser" method="POST">
    @csrf
    @method('PUT')
    <div class="row g-3">
        {{-- Basic Information Section --}}
        <div class="col-12">
            <h6 class="fw-bold mb-2 border-bottom pb-1"><i class="ti ti-user me-2"></i>Informasi Akun</h6>
            <div class="row g-2">
                <div class="col-md-6">
                    <x-input-with-icon icon="ti ti-user" label="Nama Lengkap" name="name" value="{{ $user->name }}" />
                </div>
                <div class="col-md-6">
                    <x-input-with-icon icon="ti ti-user-circle" label="Username" name="username" value="{{ $user->username }}" />
                </div>
                <div class="col-md-6">
                    <x-input-with-icon icon="ti ti-mail" label="Email" name="email" value="{{ $user->email }}" />
                </div>
                <div class="col-md-6">
                    <x-input-with-icon icon="ti ti-key" label="Password (Kosongkan jika tidak diubah)" name="password" type="password" />
                </div>
            </div>
        </div>

        {{-- Organization & Status Section --}}
        <div class="col-12 mt-4">
            <h6 class="fw-bold mb-2 border-bottom pb-1"><i class="ti ti-building me-2"></i>Organisasi, Role & Status</h6>
            <div class="row g-2">
                <div class="col-md-6">
                    <x-select label="Role Utama" name="role" :data="$roles" key="name" textShow="name" selected="{{ $user->roles->first()->name ?? '' }}" select2="select2Role" upperCase="true" hideLabel="true" />
                </div>
                <div class="col-md-6">
                    <x-select label="Departemen Utama" name="kode_dept" :data="$departemen" key="kode_dept" textShow="nama_dept" selected="{{ $user->kode_dept }}" hideLabel="true" />
                </div>
                <div class="col-md-4">
                    <x-select label="Cabang" name="kode_cabang" :data="$cabang" key="kode_cabang" textShow="nama_cabang" selected="{{ $user->kode_cabang }}" hideLabel="true" />
                </div>
                <div class="col-md-4">
                    <x-select label="Regional" name="kode_regional" :data="$regional" key="kode_regional" textShow="nama_regional" selected="{{ $user->kode_regional }}" hideLabel="true" />
                </div>
                <div class="col-md-4">
                    <div class="form-group mb-3">
                        <select name="status" id="status" class="form-select">
                            <option value="1" {{ $user->status == '1' ? 'selected' : '' }}>Aktif</option>
                            <option value="0" {{ $user->status == '0' ? 'selected' : '' }}>Non Aktif</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>

        {{-- Access Section --}}
        <div class="col-12 mt-4">
            <h6 class="fw-bold mb-2 border-bottom pb-1"><i class="ti ti-lock-access me-2"></i>Akses Tambahan</h6>
            <div class="row">
                <div class="col-12">
                    <x-select name="dept_access[]" :data="$departemen" key="kode_dept" textShow="nama_dept" multiple="true" select2="select2DeptAccess" :selected="$dept_access" />
                </div>
                <div class="col-12">
                    <x-select name="jabatan_access[]" :data="$jabatan" key="kode_jabatan" textShow="nama_jabatan" multiple="true" select2="select2JabatanAccess" :selected="$jabatan_access" />
                </div>
                <div class="col-12">
                    <div class="form-group mb-3">
                        <select name="karyawan_access[]" id="karyawan_access" class="form-select select2KaryawanAccess" multiple="multiple">
                            <option value="all" {{ in_array('all', $karyawan_access) ? 'selected' : '' }}>Semua Karyawan</option>
                            @foreach ($karyawan as $k)
                                <option value="{{ $k->nik }}" {{ in_array($k->nik, $karyawan_access) ? 'selected' : '' }}>{{ $k->nik }} | {{ $k->nama_karyawan }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-12">
                    <x-select name="group_access[]" :data="$group" key="kode_group" textShow="nama_group" multiple="true" select2="select2GroupAccess" :selected="$group_access" />
                </div>
            </div>
        </div>
    </div>

    <div class="mt-4 pt-2">
        <button class="btn btn-primary w-100 py-2 shadow-sm" type="submit">
            <i class="ti ti-refresh me-2 fs-5"></i>
            Update Data User
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
<script src="{{ asset('assets/js/pages/users/edit.js') }}"></script>
<script>
    $(document).ready(function() {
        const select2Role = $(".select2Role");
        const select2DeptAccess = $(".select2DeptAccess");
        const select2JabatanAccess = $(".select2JabatanAccess");
        const select2GroupAccess = $(".select2GroupAccess");

        if (select2Role.length > 0) {
            select2Role.each(function() {
                var $this = $(this);
                $this.wrap('<div class="position-relative"></div>').select2({
                    placeholder: 'Pilih Role',
                    allowClear: true,
                    dropdownParent: $this.parent()
                });
            });
        }

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
