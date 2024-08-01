<form action="{{ route('laporanpembelian.cetakrekappembelian') }}" method="POST" id="frmRekapPembelian" target="_blank">
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
        <div class="col">
            <div class="form-group mb-3">
                <select name="kode_jenis_barang" id="kode_jenis_barang" class="form-select">
                    <option value="">Semua Jenis Barang</option>
                    @foreach ($list_jenis_barang as $d)
                        <option value="{{ $d['kode_jenis_barang'] }}">{{ $d['nama_jenis_barang'] }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>
    <div class="row mb-3">
        <div class="col">
            <small class="text-light fw-medium d-block">Urutkan Berdasarkan</small>
            <div class="form-check form-check-inline mt-1">
                <input class="form-check-input" type="radio" name="sortby" id="inlineRadio1" value="supplier" checked>
                <label class="form-check-label" for="inlineRadio1">Supplier</label>
            </div>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="sortby" id="inlineRadio2" value="jenis_barang">
                <label class="form-check-label" for="inlineRadio2">Jenis Barang</label>
            </div>
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
        $(function() {
            const frmRekapPembelian = $('#frmRekapPembelian'); // Sesuaikan dengan id form yang benar
            frmRekapPembelian.submit(function(e) {
                const dari = $(this).find("#dari").val();
                const sampai = $(this).find("#sampai").val();
                var start = new Date(dari);
                var end = new Date(sampai);
                if (dari == "") {
                    Swal.fire({
                        title: "Oops!",
                        text: 'Periode Dari Harus Diisi !',
                        icon: "warning",
                        showConfirmButton: true,
                        didClose: () => {
                            $(this).find("#dari").focus();
                        },
                    });
                    e.preventDefault(); // Mencegah form dari submit
                } else if (sampai == "") {
                    Swal.fire({
                        title: "Oops!",
                        text: 'Periode Sampai Harus Diisi !',
                        icon: "warning",
                        showConfirmButton: true,
                        didClose: () => {
                            $(this).find("#sampai").focus();
                        },
                    });
                    e.preventDefault(); // Mencegah form dari submit
                } else if (start.getTime() > end.getTime()) {
                    Swal.fire({
                        title: "Oops!",
                        text: 'Periode Tidak Valid !, Periode Sampai Harus Lebih Akhir dari Periode Dari',
                        icon: "warning",
                        showConfirmButton: true,
                        didClose: () => {
                            $(this).find("#sampai").focus();
                        },
                    });
                    e.preventDefault(); // Mencegah form dari submit
                }
            });
        });
    </script>
@endpush
