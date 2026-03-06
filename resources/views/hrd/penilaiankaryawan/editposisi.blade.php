<form action="{{ route('penilaiankaryawan.updateposisi', Crypt::encrypt($penilaian->kode_penilaian)) }}" method="POST" id="formEditPosisi">
    @csrf
    @method('PUT')
    <div class="row">
        <div class="col-12">
            <x-input-with-icon label="Kode Penilaian" name="kode_penilaian" value="{{ $penilaian->kode_penilaian }}" icon="ti ti-barcode" readonly="true" />
            <x-input-with-icon label="Nama Karyawan" name="nama_karyawan" value="{{ $penilaian->nama_karyawan }}" icon="ti ti-user" readonly="true" />
            <div class="form-group mb-3">
                <label class="form-label">Status</label>
                <select name="status" id="status_edit" class="form-select">
                    <option value="0" {{ $penilaian->status == '0' ? 'selected' : '' }}>Pending</option>
                    <option value="1" {{ $penilaian->status == '1' ? 'selected' : '' }}>Disetujui</option>
                    <option value="2" {{ $penilaian->status == '2' ? 'selected' : '' }}>Ditolak</option>
                </select>
            </div>
            <div class="form-group mb-3">
                <label class="form-label">Posisi Ajuan</label>
                <select name="posisi_ajuan" id="posisi_ajuan_edit" class="form-select">
                    <option value="">Pilih Posisi</option>
                    @foreach ($roles as $role)
                        <option value="{{ $role->id }}" {{ $penilaian->posisi_ajuan == $role->id ? 'selected' : '' }}>{{ textUpperCase($role->name) }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>
    <div class="row mt-3">
        <div class="col-12">
            <button type="submit" class="btn btn-primary w-100"><i class="ti ti-send me-1"></i> Update Posisi</button>
        </div>
    </div>
</form>
