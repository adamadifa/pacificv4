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
                    <x-select label="Role Utama" name="role" :data="$roles" key="name" textShow="name" />
                </div>
                <div class="col-md-6">
                    <x-select label="Departemen Utama" name="kode_dept" :data="$departemen" key="kode_dept" textShow="nama_dept" />
                </div>
                <div class="col-md-6">
                    <x-select label="Cabang" name="kode_cabang" :data="$cabang" key="kode_cabang" textShow="nama_cabang" />
                </div>
                <div class="col-md-6">
                    <x-select label="Regional" name="kode_regional" :data="$regional" key="kode_regional" textShow="nama_regional" />
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
                                    <input class="form-check-input" name="dept_access[]" value="{{ $dept->kode_dept }}" type="checkbox" id="dept_{{ $dept->kode_dept }}">
                                    <label class="form-check-label fw-semibold" for="dept_{{ $dept->kode_dept }}">
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
                                    <input class="form-check-input" name="jabatan_access[]" value="{{ $j->kode_jabatan }}" type="checkbox" id="jabatan_{{ $j->kode_jabatan }}">
                                    <label class="form-check-label fw-semibold" for="jabatan_{{ $j->kode_jabatan }}">
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
