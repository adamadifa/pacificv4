<form action="{{ route('kesepakatanbersama.store', Crypt::encrypt($kode_penilaian)) }}" method="POST" id="formKesepakatanBersama">
    @csrf
    <x-input-with-icon icon="ti ti-calendar" label="Tanggal" name="tanggal" datepicker="flatpickr-date" />
    <div class="form-group mb-3">
        <button class="btn btn-primary w-100" id="btnSimpan"><i class="ti ti-send me-1"></i>Submit</button>
    </div>
</form>
<script>
    $(function() {

        function buttonDisable() {
            $("#btnSimpan").prop('disabled', true);
            $("#btnSimpan").html(`
            <div class="spinner-border spinner-border-sm text-white me-2" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
            Loading..`);
        }
        $(".flatpickr-date").flatpickr();
        const form = $('#formKesepakatanBersama');

        form.submit(function(e) {
            const tanggal = form.find("#tanggal").val();
            if (tanggal == '') {
                Swal.fire({
                    title: "Oops!",
                    text: "Tanggal harus diisi !",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: (e) => {
                        form.find("#tanggal").focus();
                    },
                });
                return false;
            } else {
                buttonDisable();
            }
        });

    });
</script>
