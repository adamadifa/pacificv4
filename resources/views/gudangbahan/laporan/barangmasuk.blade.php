<form method="POST" action="{{ route('laporangudangbahan.cetakbarangmasuk') }}" id="frmLaporanbarangmasuk" target="_blank">
    @csrf
    <div class="row">
        <div class="col">
            <x-select label="Semua Barang" name="kode_barang_masuk" :data="$barang" key="kode_barang"
                textShow="nama_barang" select2="select2Kodebarangmasuk" upperCase="true" />

        </div>
    </div>
    <div class="row">
        <div class="col-lg-6 col-md-12 col-sm-12">
            <x-input-with-icon icon="ti ti-calendar" label="Dari" name="dari" datepicker="flatpickr-date" />
        </div>
        <div class="col-lg-6 col-md-12 col-sm-12">
            <x-input-with-icon icon="ti ti-calendar" label="Sampai" name="sampai" datepicker="flatpickr-date" />
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="form-group mb-3">
                <select name="kode_asal_barang" id="kode_asal_barang" class="form-select">
                    <option value="">Semua Asal Barang</option>
                    @foreach ($list_asal_barang as $d)
                        <option value="{{ $d['kode_asal_barang'] }}">
                            {{ $d['asal_barang'] }}</option>
                    @endforeach
                </select>
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
            const select2Kodebarangmasuk = $('.select2Kodebarangmasuk');
            if (select2Kodebarangmasuk.length) {
                select2Kodebarangmasuk.each(function() {
                    var $this = $(this);
                    $this.wrap('<div class="position-relative"></div>').select2({
                        // placeholder: 'Semua Barang',
                        dropdownParent: $this.parent(),
                        placeholder: 'Semua Barang',
                        allowClear: true,
                    });
                });
            }

            $("#frmLaporanbarangmasuk").submit(function() {
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
                        didClose: (e) => {
                            $(this).find("#dari").focus();
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
                            $(this).find("#sampai").focus();
                        },
                    });
                    return false;
                } else if (start.getTime() > end.getTime()) {
                    Swal.fire({
                        title: "Oops!",
                        text: 'Periode Tidak Valid !, Periode Sampai Harus Lebih Akhir dari Periode Dari',
                        icon: "warning",
                        showConfirmButton: true,
                        didClose: (e) => {
                            $(this).find("#sampai").focus();
                        },
                    });
                    return false;
                }
            });
        });
    </script>
@endpush
