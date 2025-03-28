<form action="{{ route('laporangudanglogistik.cetakpersediaan') }}" method="POST" id="frmPersediaan" target="_blank">
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
        <div class="co">
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
        <div class="col-12">
            <div class="form-group mb-3">
                <select name="kode_kategori" id="kode_kategori" class="form-select">
                    <option value="">Semua Kategori</option>
                    @foreach ($kategori as $d)
                        <option value="{{ $d->kode_kategori }}">
                            {{ $d->nama_kategori }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>
    {{-- <div class="row">
        <div class="col-12">
            <div class="form-group mb-3">
                <select name="jenis_laporan" id="jenis_laporan" class="form-select">
                    <option value="">Jenis Laporan</option>
                    <option value="1">Tanpa Harga</option>
                    <option value="2">Dengan Harga</option>
                </select>
            </div>
        </div>
    </div> --}}
    <div class="row">
        <div class="col-lg-10 col-md-12 col-sm-12">
            <button type="submit" class="btn btn-primary w-100">
                <i class="ti ti-printer me-1"></i> Cetak
            </button>
        </div>
        <div class="col-lg-2 col-md-12 col-sm-12">
            <button type="submit" name="exportButton" class="btn btn-success w-100">
                <i class="ti ti-download"></i>
            </button>
        </div>
    </div>
</form>
@push('myscript')
    <script>
        $(function() {
            $("#frmPersediaan").submit(function() {
                const bulan = $(this).find("#bulan").val();
                const tahun = $(this).find("#tahun").val();
                const jenis_laporan = $(this).find("#jenis_laporan").val();

                if (bulan == "") {
                    Swal.fire({
                        title: "Oops!",
                        text: 'Bulan Harus Diisi !',
                        icon: "warning",
                        showConfirmButton: true,
                        didClose: (e) => {
                            $(this).find("#bulan").focus();
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
                            $(this).find("#tahun").focus();
                        },
                    });
                    return false;
                } else if (jenis_laporan == "") {
                    Swal.fire({
                        title: "Oops!",
                        text: 'Jenis Laporan Harus Dipilih !',
                        icon: "warning",
                        showConfirmButton: true,
                        didClose: (e) => {
                            $(this).find("#jenis_laporan").focus();
                        },
                    });
                    return false;
                }
            });
        });
    </script>
@endpush
