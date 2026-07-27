<form action="{{ route('kategoridiskon.update', Crypt::encrypt($kategoridiskon->kode_kategori_diskon)) }}"
    id="formeditKategoridiskon" method="POST">
    @csrf
    @method('PUT')
    <x-input-with-icon icon="ti ti-barcode" label="Kode Kategori Diskon" name="kode_kategori_diskon"
        value="{{ $kategoridiskon->kode_kategori_diskon }}" disabled="true" />
    <x-input-with-icon icon="ti ti-file-text" label="Nama Kategori Diskon" name="nama_kategori"
        value="{{ $kategoridiskon->nama_kategori }}" placeholder="E.g. Diskon Khusus A" />
    <div class="form-group">
        <button class="btn btn-primary w-100" type="submit">
            <i class="ti ti-send me-1"></i> Submit
        </button>
    </div>
</form>

<script>
    $(function() {
        $('#formeditKategoridiskon').submit(function(e) {
            var nama = $('input[name="nama_kategori"]').val();

            if (nama === "") {
                Swal.fire({
                    title: 'Peringatan',
                    text: 'Nama Kategori Diskon wajib diisi!',
                    icon: 'warning',
                    showConfirmButton: true
                });
                return false;
            }
        });
    });
</script>
