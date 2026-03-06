<form action="{{ route('ajuanfaktur.update', Crypt::encrypt($ajuanfaktur->no_pengajuan)) }}" id="formEditposisi" method="POST">
    @csrf
    @method('PUT')
    <div class="row">
        <div class="col">
            <div class="form-group mb-3">
                <select name="posisi_ajuan" id="posisi_ajuan" class="form-select">
                    <option value="">Belum di Konfigurasi</option>
                    @foreach ($roles_approve as $role)
                        <option value="{{ $role }}" {{ $ajuanfaktur->posisi_ajuan == $role ? 'selected' : '' }}>
                            {{ textUpperCase($role) }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col">
            <button class="btn btn-primary w-100"><i class="ti ti-send me-1"></i>Ubah Posisi</button>
        </div>
    </div>
</form>
