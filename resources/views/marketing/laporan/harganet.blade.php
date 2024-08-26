<form action="{{ route('laporanmarketing.cetakharganet') }}" method="POST" target="_blank" id="formharganet">
    @csrf
    <div class="row">
        <div class="col">
            <div class="form-group mb-3">
                <select name="bulan" id="bulan" class="form-select">
                    <option value="">Bulan</option>
                    @foreach ($list_bulan as $d)
                        <option value="{{ $d['kode_bulan'] }}">{{ $d['nama_bulan'] }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col">
            <div class="form-group mb-3">
                <select name="tahun" id="tahun" class="form-select">
                    <option value="">Tahun</option>
                    @for ($t = $start_year; $t <= date('Y'); $t++)
                        <option value="{{ $t }}">{{ $t }}</option>
                    @endfor
                </select>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-10 col-md-12 col-sm-12">
            <button type="submit" name="submitButton" class="btn btn-primary w-100" id="submitButtonHargaNet">
                <i class="ti ti-printer me-1"></i> Cetak
            </button>
        </div>
        <div class="col-lg-2 col-md-12 col-sm-12">
            <button type="submit" name="exportButton" class="btn btn-success w-100" id="exportButtonHargaNet">
                <i class="ti ti-download"></i>
            </button>
        </div>
    </div>
</form>
@push('myscript')
    <script>
        $(document).ready(function() {
            const formHargaNet = $("#formharganet");
            formHargaNet.submit(function(e) {
                const bulan = formHargaNet.find('#bulan').val();
                const tahun = formHargaNet.find('#tahun').val();
                if (bulan == "") {
                    Swal.fire({
                        title: "Oops!",
                        text: 'Bulan Harus Diisi !',
                        icon: "warning",
                        showConfirmButton: true,
                        didClose: (e) => {
                            formHargaNet.find("#bulan").focus();
                        },
                    });
                    return false;
                } else if (tahun == "") {
                    Swal.fire({
                        title: "Oops!",
                        text: 'Tahun Harus Diisi !',
                        icon: "warning",
                        showConfirmButton: true,
                        didClose: (e) => {
                            formHargaNet.find("#tahun").focus();
                        },
                    });
                    return false;
                }
            });
        });
    </script>
@endpush
