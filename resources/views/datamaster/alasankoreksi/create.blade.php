<form action="{{ route('alasankoreksi.store') }}" method="POST" id="formAlasankoreksi">
    @csrf
    <x-input-with-icon icon="ti ti-file-description" label="Alasan" name="alasan" />
    <div class="form-group mb-3">
        <label class="form-label">Status Denda</label>
        <select name="status_denda" id="status_denda" class="form-select">
            <option value="">Pilih Status</option>
            <option value="1">Ya</option>
            <option value="0">Tidak</option>
        </select>
    </div>
    <div class="form-group mb-3">
        <button class="btn btn-primary w-100" id="btnSimpan">
            <i class="ti ti-send me-1"></i>
            Simpan
        </button>
    </div>
</form>

<script>
    $(function() {
        $("#formAlasankoreksi").submit(function(e) {
            const alasan = $("#alasan").val();
            const status_denda = $("#status_denda").val();
            if (alasan == "") {
                Swal.fire({
                    title: "Oops!",
                    text: 'Alasan Harus Diisi !',
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: () => {
                        $("#alasan").focus();
                    }
                });
                return false;
            } else if (status_denda == "") {
                Swal.fire({
                    title: "Oops!",
                    text: 'Status Denda Harus Diisi !',
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: () => {
                        $("#status_denda").focus();
                    }
                });
                return false;
            }
        });
    });
</script>
