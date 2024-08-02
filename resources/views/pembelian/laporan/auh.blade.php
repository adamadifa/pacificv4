<form action="{{ route('laporanpembelian.cetakauh') }}" method="POST" id="formLapAUH" target="_blank">
    @csrf
    <x-input-with-icon icon="ti ti-calendar" label="Lihat Per Tanggal" name="tanggal" datepicker="flatpickr-date" />
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
        $(function() {
            $("#formLapAUH").submit(function(e) {
                const tanggal = $(this).find("#tanggal").val();
                if (tanggal == "") {
                    Swal.fire({
                        title: "Oops!",
                        text: "Lihat Per Tanggal Harus Diisi !",
                        icon: "warning",
                        showConfirmButton: true,
                        didClose: (e) => {
                            $(this).find("#tanggal").focus();
                        },
                    });
                    return false;
                }
            })
        });
    </script>
@endpush
