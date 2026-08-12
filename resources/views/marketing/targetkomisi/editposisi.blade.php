<form action="{{ route('targetkomisi.updateposisi', Crypt::encrypt($targetkomisi->kode_target)) }}" method="POST" id="formEditPosisi">
    @csrf
    @method('PUT')
    <div class="row">
        <div class="col-12">
            <x-input-with-icon label="Kode Target" name="kode_target" value="{{ $targetkomisi->kode_target }}" icon="ti ti-barcode" readonly="true" />
            <x-input-with-icon label="Cabang" name="nama_cabang" value="{{ $targetkomisi->nama_cabang }}" icon="ti ti-building" readonly="true" />
            
            <div class="form-group mb-3">
                <label class="form-label">Status</label>
                <select name="status" id="status_edit" class="form-select">
                    <option value="0" {{ $targetkomisi->status == '0' ? 'selected' : '' }}>Pending</option>
                    <option value="1" {{ $targetkomisi->status == '1' ? 'selected' : '' }}>Disetujui</option>
                </select>
            </div>
            
            <div class="form-group mb-3">
                <label class="form-label">Posisi Ajuan</label>
                <select name="posisi_ajuan" id="posisi_ajuan_edit" class="form-select">
                    <option value="">Pilih Posisi</option>
                    @foreach ($roles as $role)
                        <option value="{{ $role->id }}" {{ $current_role_id == $role->id ? 'selected' : '' }}>
                            {{ textUpperCase($role->name) }}
                        </option>
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
