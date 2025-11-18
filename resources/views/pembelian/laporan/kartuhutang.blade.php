<form action="{{ route('laporanpembelian.cetakkartuhutang') }}" method="POST" id="formLapKartuHutang" target="_blank">
    @csrf
    <x-select label="Supplier" name="kode_supplier_kartuhutang" :data="$supplier" key="kode_supplier"
        textShow="nama_supplier" upperCase="true" select2="select2Kodesupplierkartuhutang" />
    <div class="form-group mb-3">
        <select name="jenis_hutang" id="jenis_hutang" class="form-select">
            <option value="">Jenis Hutang</option>
            <option value="2-1200">Hutang Dagang</option>
            <option value="2-1300">Hutang Lainnya</option>
        </select>
    </div>
    <div class="form-group mb-3">
        <select name="formatlaporan" id="formatlaporan" class="form-select">
            <option value="">Jenis Laporan</option>
            <option value="1">Detail Kartu Hutang</option>
            <option value="2">Rekap Kartu Hutang</option>
        </select>
    </div>
    <div class="form-group mb-3">
        <select name="ppn" id="ppn" class="form-select">
            <option value="">PPN / NON PPN</option>
            <option value="1">PPN</option>
            <option value="0">NON PPN</option>
        </select>
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
            const formLapKartuHutang = $('#formLapKartuHutang');
            const select2Kodesupplierkartuhutang = $('.select2Kodesupplierkartuhutang');
            if (select2Kodesupplierkartuhutang.length) {
                select2Kodesupplierkartuhutang.each(function() {
                    var $this = $(this);
                    $this.wrap('<div class="position-relative"></div>').select2({
                        placeholder: 'Semua Supplier',
                        allowClear: true,
                        dropdownParent: $this.parent()
                    });
                });
            }

            formLapKartuHutang.submit(function(e) {
                const jenis_hutang = $(this).find("#jenis_hutang").val();
                const formatlaporan = $(this).find("#formatlaporan").val();
                const dari = $(this).find("#dari").val();
                const sampai = $(this).find("#sampai").val();
                const start = new Date(dari);
                const end = new Date(sampai);
                if (formatlaporan == "") {
                    Swal.fire({
                        title: "Oops!",
                        text: 'Jenis Laporan Harus Diisi !',
                        icon: "warning",
                        showConfirmButton: true,
                        didClose: (e) => {
                            $(this).find("#formatlaporan").focus();
                        },
                    });
                    return false;
                } else if (dari == "") {
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
                        }
                    });
                    return false;
                }
            })
        });
    </script>
@endpush
