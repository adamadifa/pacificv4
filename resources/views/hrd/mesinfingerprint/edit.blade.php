<form action="{{ route('mesinfingerprint.update', Crypt::encrypt($mesin->id)) }}" method="POST" id="formMesinEdit">
    @csrf
    @method('PUT')
    <div class="row">
        <div class="col-12">
            <x-input-with-icon icon="ti ti-device-laptop" label="Nama Mesin" name="nama_mesin" value="{{ $mesin->nama_mesin }}" />
            <x-input-with-icon icon="ti ti-barcode" label="Serial Number (SN)" name="sn" value="{{ $mesin->sn }}" />
            <div class="form-group mb-3">
                <label class="form-label" for="status">Status</label>
                <select name="status" id="status" class="form-select">
                    <option value="Aktif" {{ $mesin->status == 'Aktif' ? 'selected' : '' }}>Aktif</option>
                    <option value="Nonaktif" {{ $mesin->status == 'Nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                </select>
            </div>
            <x-input-with-icon icon="ti ti-map-pin" label="Titik Koordinat" name="titik_koordinat" value="{{ $mesin->titik_koordinat }}" />
        </div>
    </div>
    <div class="row mt-2">
        <div class="col-12 text-end">
            <button class="btn btn-primary w-100" type="submit"><i class="ti ti-rotate me-1"></i>Update</button>
        </div>
    </div>
</form>

<script>
    $(function() {
        $("#formMesinEdit").submit(function() {
            const nama_mesin = $("#nama_mesin").val();
            const sn = $("#sn").val();

            if (nama_mesin == "") {
                Swal.fire({
                    title: 'Oops!',
                    text: 'Nama Mesin Harus Diisi',
                    icon: 'warning',
                    showConfirmButton: true
                });
                return false;
            } else if (sn == "") {
                Swal.fire({
                    title: 'Oops!',
                    text: 'Serial Number Harus Diisi',
                    icon: 'warning',
                    showConfirmButton: true
                });
                return false;
            }
        });
    });
</script>
