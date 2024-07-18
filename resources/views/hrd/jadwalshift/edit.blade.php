<form action="{{ route('jadwalshift.update', Crypt::encrypt($jadwalshift->kode_jadwalshift)) }}" method="POST" id="formJadwalShift">
    @csrf
    @method('PUT')
    <x-input-with-icon icon="ti ti-barcode" label="{{ $jadwalshift->kode_jadwalshift }}" disabled="true" name="kode_jadwalshift" />
    <div class="row">
        <div class="col-lg-6 col-sm-12 col-md-12">
            <x-input-with-icon label="Dari" name="dari" icon="ti ti-calendar" datepicker="flatpickr-date" :value="$jadwalshift->dari" />
        </div>
        <div class="col-lg-6 col-sm-12 col-md-12">
            <x-input-with-icon label="Sampai" name="sampai" icon="ti ti-calendar" datepicker="flatpickr-date" :value="$jadwalshift->sampai" />
        </div>
    </div>
    <div class="form-group mb-3">
        <button class="btn btn-primary w-100" id="btnSimpan"><i class="ti ti-send me-1"></i>Submit</button>
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
