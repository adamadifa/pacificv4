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
                    <x-select label="Role Utama" name="role" :data="$roles" key="name" textShow="name" selected="{{ $user->roles->first()->name ?? '' }}" select2="select2Role" upperCase="true" />
                </div>
                <div class="col-md-6">
                    <x-select label="Departemen Utama" name="kode_dept" :data="$departemen" key="kode_dept" textShow="nama_dept" selected="{{ $user->kode_dept }}" />
                </div>
                <div class="col-md-4">
                    <x-select label="Cabang" name="kode_cabang" :data="$cabang" key="kode_cabang" textShow="nama_cabang" selected="{{ $user->kode_cabang }}" />
                </div>
                <div class="col-md-4">
                    <x-select label="Regional" name="kode_regional" :data="$regional" key="kode_regional" textShow="nama_regional" selected="{{ $user->kode_regional }}" />
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

        {{-- Dept Access Section --}}
        <div class="col-12 mt-4">
            <h6 class="fw-bold mb-2 border-bottom pb-1"><i class="ti ti-shield-lock me-2"></i>Akses Departemen Tambah</h6>
            <div class="bg-light p-3 rounded border">
                @foreach ($deptchunks as $deptchunk)
                    <div class="row g-2">
                        @foreach ($deptchunk as $dept)
                            <div class="col-md-6">
                                <div class="form-check form-switch card-access-item">
                                    <input class="form-check-input" {{ in_array($dept->kode_dept, $dept_access) ? 'checked' : '' }} 
                                           name="dept_access[]" value="{{ $dept->kode_dept }}" type="checkbox" id="edit_dept_{{ $dept->kode_dept }}">
                                    <label class="form-check-label fw-semibold" for="edit_dept_{{ $dept->kode_dept }}">
                                        {{ $dept->nama_dept }}
                                    </label>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endforeach
            </div>
        </div>

        {{-- Jabatan Access Section --}}
        <div class="col-12 mt-4">
            <h6 class="fw-bold mb-2 border-bottom pb-1"><i class="ti ti-user-shield me-2"></i>Akses Jabatan Tambah</h6>
            <div class="bg-light p-3 rounded border">
                @foreach ($jabatanchunks as $jabatanchunk)
                    <div class="row g-2">
                        @foreach ($jabatanchunk as $j)
                            <div class="col-md-6">
                                <div class="form-check form-switch card-access-item">
                                    <input class="form-check-input" {{ in_array($j->kode_jabatan, $jabatan_access) ? 'checked' : '' }} 
                                           name="jabatan_access[]" value="{{ $j->kode_jabatan }}" type="checkbox" id="edit_jabatan_{{ $j->kode_jabatan }}">
                                    <label class="form-check-label fw-semibold" for="edit_jabatan_{{ $j->kode_jabatan }}">
                                        {{ $j->nama_jabatan }}
                                    </label>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endforeach
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
    });
</script>
