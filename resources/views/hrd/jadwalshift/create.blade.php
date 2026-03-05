<form action="{{ route('jadwalshift.store') }}" method="POST" id="formJadwalShift">
    @csrf

    <x-input-with-icon icon="ti ti-barcode" label="Auto" disabled="true" name="kode_jadwalshift" hideLabel="true" />
    <div class="row">
        <div class="col-lg-6 col-sm-12 col-md-12">
            <x-input-with-icon label="Dari" name="dari" icon="ti ti-calendar" datepicker="flatpickr-date" hideLabel="true" />
        </div>
        <div class="col-lg-6 col-sm-12 col-md-12">
            <x-input-with-icon label="Sampai" name="sampai" icon="ti ti-calendar" datepicker="flatpickr-date" hideLabel="true" />
        </div>
    </div>
    <div class="form-group mb-0">
        <button class="btn btn-primary w-100" id="btnSimpan" type="submit" name="submitButton">
            <ion-icon name="send-outline" class="me-1"></ion-icon>
            Submit
        </button>
    </div>
</form>

<script>
    $(document).ready(function() {
        const form = $("#formJadwalShift");

        $(".flatpickr-date").flatpickr();

        function buttonDisabled() {
            $("#btnSimpan").prop('disabled', true);
            $("#btnSimpan").html(`
            <div class="spinner-border spinner-border-sm text-white me-2" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
            Loading..`);
        }
        form.submit(function(e) {
            const dari = form.find("#dari").val();
            const sampai = form.find("#sampai").val();
            if (dari == '' || sampai == '') {
                Swal.fire({
                    title: "Oops!",
                    text: 'Periode Harus Diisi !',
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: () => {
                        form.find("#dari").focus();
                    }
                });
                return false;
            } else {
                buttonDisabled();
            }
        })
    });
</script>
