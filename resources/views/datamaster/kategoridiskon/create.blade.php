<form action="{{ route('kategoridiskon.store') }}" id="formcreateKategoridiskon" method="POST">
    @csrf
    <x-input-with-icon icon="ti ti-barcode" label="Kode Kategori Diskon (Max 4 Karakter)" name="kode_kategori_diskon" placeholder="E.g. DK01" />
    <x-input-with-icon icon="ti ti-file-text" label="Nama Kategori Diskon" name="nama_kategori" placeholder="E.g. Diskon Khusus A" />
    <div class="form-group">
        <button class="btn btn-primary w-100" type="submit">
            <i class="ti ti-send me-1"></i> Submit
        </button>
    </div>
</form>

<script>
    $(function() {
        $('#formcreateKategoridiskon').submit(function(e) {
            var kode = $('input[name="kode_kategori_diskon"]').val();
            var nama = $('input[name="nama_kategori"]').val();

            if (kode === "" || nama === "") {
                Swal.fire({
                    title: 'Peringatan',
                    text: 'Semua kolom input wajib diisi!',
                    icon: 'warning',
                    showConfirmButton: true
                });
                return false;
            }

            if (kode.length > 4) {
                Swal.fire({
                    title: 'Peringatan',
                    text: 'Kode Kategori Diskon tidak boleh lebih dari 4 karakter!',
                    icon: 'warning',
                    showConfirmButton: true
                });
                return false;
            }
        });
    });
</script>
