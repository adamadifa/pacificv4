<form action="{{ route('laporanpembelian.cetakrekapbahankemasan') }}" method="POST" id="formLapRekapBahanKemasan" target="_blank">
    @csrf
    <x-select label="Pilih Barang" name="kode_barang" :data="$barangbahankemasan" key="kode_barang" textShow="nama_barang" upperCase="true" showKey="true"
        select2="select2Kodebarangbahankemasan" />
    <x-select label="Supplier" name="kode_supplier_rekapbahankemasan" :data="$supplier" key="kode_supplier" textShow="nama_supplier" upperCase="true"
        select2="select2Kodesupplierrekapbahankemasan" />
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
        $(function() {
            const select2Kodesupplierrekapbahankemasan = $('.select2Kodesupplierrekapbahankemasan');
            if (select2Kodesupplierrekapbahankemasan.length) {
                select2Kodesupplierrekapbahankemasan.each(function() {
                    var $this = $(this);
                    $this.wrap('<div class="position-relative"></div>').select2({
                        placeholder: 'Semua Supplier',
                        allowClear: true,
                        dropdownParent: $this.parent()
                    });
                });
            }

            const select2Kodebarangbahankemasan = $('.select2Kodebarangbahankemasan');
            if (select2Kodebarangbahankemasan.length) {
                select2Kodebarangbahankemasan.each(function() {
                    var $this = $(this);
                    $this.wrap('<div class="position-relative"></div>').select2({
                        placeholder: 'Pilih  Barang',
                        allowClear: true,
                        dropdownParent: $this.parent()
                    });
                });
            }

            $("#formLapRekapBahanKemasan").submit(function(e) {
                const kode_barang = $(this).find('#kode_barang').val();
                const dari = $(this).find('#dari').val();
                const sampai = $(this).find('#sampai').val();
                const start = new Date(dari);
                const end = new Date(sampai);
                if (kode_barang == "") {
                    Swal.fire({
                        title: "Oops!",
                        text: 'Barang Harus Diisi !',
                        icon: "warning",
                        showConfirmButton: true,
                        didClose: (e) => {
                            $(this).find('#kode_barang').focus();
                        },
                    })
                    return false;
                } else if (dari == "") {
                    Swal.fire({
                        title: "Oops!",
                        text: 'Periode Dari Harus Diisi !',
                        icon: "warning",
                        showConfirmButton: true,
                        didClose: (e) => {
                            $(this).find('#dari').focus();
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
                            $(this).find('#sampai').focus();
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
                            $(this).find('#sampai').focus();
                        },
                    });
                    return false;
                }
            });
        });
    </script>
@endpush
