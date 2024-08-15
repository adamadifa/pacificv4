<form action="{{ route('sfa.storeubahfakturbatal', Crypt::encrypt($no_faktur)) }}" id="formUbahFakturBatal" method="POST">
    @csrf
    <div class="form-group mb-3">
        <textarea name="keterangan" id="keterangan" class="form-control" placeholder="Keterangan Faktur Batal" cols="30" rows="10"></textarea>
    </div>
    <div class="form-group mb-3">
        <button class="btn btn-primary w-100" id="btnSimpan"><i class="ti ti-send me-1"></i>Submit</button>
    </div>
</form>
<script>
    $(function() {
        const form = $('#formUbahFakturBatal');

        function buttonDisable() {
            $('#btnSimpan').prop('disabled', true);
            $('#btnSimpan').html(`
            <div class="spinner-border spinner-border-sm text-white me-2" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
            Loading..`);
        }
        form.submit(function(e) {
            const keterangan = form.find("#keterangan").val();
            if (keterangan == "") {
                Swal.fire({
                    title: "Oops!",
                    text: 'Keterangan Harus Diisi !',
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: (e) => {
                        form.find("#keterangan").focus();
                    },
                });

                return false;
            } else {
                buttonDisable();
            }
        });

    });
</script>
