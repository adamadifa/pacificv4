<form action="{{ route('penyupah.updatekaryawan', [Crypt::encrypt($kode_gaji), Crypt::encrypt($detail->nik)]) }}" method="POST" id="formEditKaryawan">
    @csrf
    @method('PUT')
    <div class="form-group mb-3">
        <label class="form-label fw-bold">Karyawan</label>
        <input type="text" class="form-control" value="{{ $detail->nik }} - {{ $detail->nama_karyawan }}" readonly disabled>
    </div>
    <x-input-with-icon label="Pengurang" name="pengurang" icon="ti ti-minus" align="right" money="true" value="{{ formatAngka($detail->pengurang) }}" />
    <x-input-with-icon label="Penambah" name="penambah" icon="ti ti-plus" align="right" money="true" value="{{ formatAngka($detail->penambah) }}" />
    <div class="form-group mb-3">
        <button class="btn btn-primary w-100" id="btnUpdateKaryawan"><i class="ti ti-send me-1"></i>Simpan Perubahan</button>
    </div>
</form>
<script>
    $(document).ready(function() {
        $(".money").maskMoney();

        const form = $("#formEditKaryawan");

        function buttonDisable() {
            $("#btnUpdateKaryawan").prop('disabled', true);
            $("#btnUpdateKaryawan").html(`
            <div class="spinner-border spinner-border-sm text-white me-2" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
            Loading..`);
        }

        form.submit(function(e) {
            buttonDisable();
        });
    });
</script>
