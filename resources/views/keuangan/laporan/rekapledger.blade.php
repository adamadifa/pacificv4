<form action="{{ route('laporankeuangan.cetakrekapledger') }}" id="formRekapledger" target="_blank" method="POST">
    @csrf
    <div class="row">
        <div class="col-lg-6 col-md-12 col-sm-12">
            <x-input-with-icon icon="ti ti-calendar" label="Dari" name="dari" datepicker="flatpickr-date" />
        </div>
        <div class="col-lg-6 col-md-12 col-sm-12">
            <x-input-with-icon icon="ti ti-calendar" label="Sampai" name="sampai" datepicker="flatpickr-date" />
        </div>
    </div>
    <div class="row">
        <div class="col-lg-10 col-md-12 col-sm-12">
            <button type="submit" name="submitButton" class="btn btn-primary w-100" id="submitButton">
                <i class="ti ti-printer me-1"></i> Cetak
            </button>
        </div>
        <div class="col-lg-2 col-md-12 col-sm-12">
            <button type="submit" name="exportButton" class="btn btn-success w-100" id="exportButton">
                <i class="ti ti-download"></i>
            </button>
        </div>
    </div>
</form>
@push('myscript')
    <script>
        const formRekapledger = $("#formRekapledger");

        formRekapledger.submit(function(e) {
            const dari = formLedger.find("#dari").val();
            const sampai = formLedger.find("#sampai").val();
            const start = new Date(dari);
            const end = new Date(sampai);
            if (dari == "") {
                Swal.fire({
                    title: "Oops!",
                    text: 'Periode Dari Harus Diisi !',
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: (e) => {
                        formLedger.find("#dari").focus();
                    },
                });
                return false;
            } else if (sampai == "") {
                Swal.fire({
                    title: "Oops!",
                    text: 'Periode Sampai Harus Diisi !',
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: (e) => {
                        formLedger.find("#sampai").focus();
                    },
                });
                return false;
            } else if (start > end) {
                Swal.fire({
                    title: "Oops!",
                    text: 'Periode Tidak Valid !',
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: (e) => {
                        formLedger.find("#sampai").focus();
                    },
                });
                return false;
            }
        });
    </script>
@endpush
