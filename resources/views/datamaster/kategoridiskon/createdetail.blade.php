<form action="{{ route('kategoridiskon.storedetail', Crypt::encrypt($kode_kategori_diskon)) }}" id="formcreateDetail" method="POST">
    @csrf
    <x-input-with-icon icon="ti ti-hash" label="Min. Qty" name="min_qty" placeholder="E.g. 1" type="number" />
    <x-input-with-icon icon="ti ti-hash" label="Max. Qty" name="max_qty" placeholder="E.g. 999" type="number" />
    <x-input-with-icon icon="ti ti-discount" label="Diskon Kredit (Rp / Unit)" name="diskon" placeholder="E.g. 500" type="number" />
    <x-input-with-icon icon="ti ti-cash" label="Diskon Tunai (Rp / Unit)" name="diskon_tunai" placeholder="E.g. 100" type="number" />
    
    <div class="form-group">
        <button class="btn btn-primary w-100" type="submit">
            <i class="ti ti-send me-1"></i> Submit
        </button>
    </div>
</form>

<script>
    $(function() {
        $('#formcreateDetail').submit(function(e) {
            var min = parseInt($('input[name="min_qty"]').val());
            var max = parseInt($('input[name="max_qty"]').val());
            var diskon = $('input[name="diskon"]').val();
            var diskon_tunai = $('input[name="diskon_tunai"]').val();

            if (isNaN(min) || isNaN(max) || diskon === "" || diskon_tunai === "") {
                Swal.fire({
                    title: 'Peringatan',
                    text: 'Semua kolom input wajib diisi!',
                    icon: 'warning',
                    showConfirmButton: true
                });
                return false;
            }

            if (min > max) {
                Swal.fire({
                    title: 'Peringatan',
                    text: 'Min Qty tidak boleh lebih besar dari Max Qty!',
                    icon: 'warning',
                    showConfirmButton: true
                });
                return false;
            }
        });
    });
</script>
