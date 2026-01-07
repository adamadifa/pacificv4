<form action="{{ route('laporanhrd.cetakpelanggaran') }}" method="POST" target="_blank" id="formPelanggaran">
    @csrf
    <div class="row">
        <div class="col">
            <div class="form-group mb-3">
                <select name="tahun" id="tahun_pelanggaran" class="form-select">
                    <option value="">Tahun</option>
                    @for ($t = $start_year; $t <= date('Y'); $t++)
                        <option value="{{ $t }}" {{ date('Y') == $t ? 'selected' : '' }}>{{ $t }}</option>
                    @endfor
                </select>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-10 col-md-12 col-sm-12">
            <button type="submit" name="submitButton" class="btn btn-primary w-100" id="submitButtonPelanggaran">
                <i class="ti ti-printer me-1"></i> Cetak
            </button>
        </div>
        <div class="col-lg-2 col-md-12 col-sm-12">
            <button type="submit" name="exportButton" class="btn btn-success w-100" id="exportButtonPelanggaran">
                <i class="ti ti-download"></i>
            </button>
        </div>
    </div>
</form>
@push('myscript')
    <script>
        $(function() {
            $("#formPelanggaran").submit(function(e) {
                const tahun = $(this).find("#tahun_pelanggaran").val();
                if (tahun == "") {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Oops...',
                        text: 'Tahun harus diisi!',
                        showConfirmButton: true,
                        didClose: () => {
                            $("#tahun_pelanggaran").focus();
                        }
                    });
                    return false;
                }
            });
        });
    </script>
@endpush
