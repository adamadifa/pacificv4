<form action="{{ route('users.pjpaccess.update', Crypt::encrypt($user->id)) }}" id="formPjpAccess" method="POST">
    @csrf
    <div class="row g-3">
        <div class="col-12">
            <div class="d-flex align-items-center mb-3">
                <div class="avatar avatar-sm me-2">
                    <span class="avatar-initial rounded-circle bg-label-primary">
                        {{ strtoupper(substr($user->name, 0, 1)) }}
                    </span>
                </div>
                <div>
                    <h6 class="mb-0 fw-bold">{{ $user->name }}</h6>
                    <small class="text-muted">Mengatur hak akses khusus modul PJP</small>
                </div>
            </div>
            <h6 class="fw-bold mb-2 border-bottom pb-1"><i class="ti ti-lock-access me-2"></i>Akses Khusus PJP</h6>
            <div class="row mb-3">
                <div class="col-md-6">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="pjp_kategori_jabatan_access[]" id="pjp_kategori_jabatan_m" value="MJ" {{ in_array('MJ', $pjp_kategori_jabatan_access) ? 'checked' : '' }}>
                        <label class="form-check-label fw-bold" for="pjp_kategori_jabatan_m">
                            Management (M)
                        </label>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="pjp_kategori_jabatan_access[]" id="pjp_kategori_jabatan_nm" value="NM" {{ in_array('NM', $pjp_kategori_jabatan_access) ? 'checked' : '' }}>
                        <label class="form-check-label fw-bold" for="pjp_kategori_jabatan_nm">
                            Non-Management (NM)
                        </label>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <x-select name="pjp_cabang_access[]" label="Akses Cabang PJP" :data="$cabang" key="kode_cabang" textShow="nama_cabang" multiple="true" select2="select2PjpCabangAccess" :selected="$pjp_cabang_access" allOption="true" allOptionLabel="Semua Cabang" />
                </div>
                <div class="col-12">
                    <x-select name="pjp_dept_access[]" label="Akses Departemen PJP" :data="$departemen" key="kode_dept" textShow="nama_dept" multiple="true" select2="select2PjpDeptAccess" :selected="$pjp_dept_access" allOption="true" allOptionLabel="Semua Departemen" />
                </div>
                <div class="col-12">
                    <x-select name="pjp_jabatan_access[]" label="Akses Jabatan PJP" :data="$jabatan" key="kode_jabatan" textShow="nama_jabatan" multiple="true" select2="select2PjpJabatanAccess" :selected="$pjp_jabatan_access" allOption="true" allOptionLabel="Semua Jabatan" />
                </div>
                <div class="col-12">
                    <x-select name="pjp_karyawan_access[]" label="Akses Karyawan PJP" :data="$karyawan" key="nik" textShow="nama_karyawan" multiple="true" select2="select2PjpKaryawanAccess" :selected="$pjp_karyawan_access" allOption="true" allOptionLabel="Semua Karyawan" showKey="true" />
                </div>
                <div class="col-12">
                    <x-select name="pjp_group_access[]" label="Akses Grup PJP" :data="$group" key="kode_group" textShow="nama_group" multiple="true" select2="select2PjpGroupAccess" :selected="$pjp_group_access" />
                </div>
            </div>
        </div>
    </div>

    <div class="mt-4 pt-2">
        <button class="btn btn-primary w-100 py-2 shadow-sm" type="submit">
            <i class="ti ti-device-floppy me-2 fs-5"></i>
            Simpan Akses PJP
        </button>
    </div>
</form>

<script>
    $(document).ready(function() {
        const selects = [
            ".select2PjpCabangAccess",
            ".select2PjpDeptAccess",
            ".select2PjpJabatanAccess",
            ".select2PjpKaryawanAccess",
            ".select2PjpGroupAccess"
        ];

        selects.forEach(selector => {
            const $el = $(selector);
            if ($el.length > 0) {
                $el.each(function() {
                    var $this = $(this);
                    $this.wrap('<div class="position-relative"></div>').select2({
                        placeholder: 'Pilih Opsi',
                        allowClear: true,
                        dropdownParent: $this.parent()
                    });
                });
            }
        });
    });
</script>
