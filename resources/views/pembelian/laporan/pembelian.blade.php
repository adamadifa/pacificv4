<form action="{{ route('laporanpembelian.cetakpembelian') }}" method="POST" id="formLapPembelian" target="_blank">
    @csrf
    <x-select label="Supplier" name="kode_supplier" :data="$supplier" key="kode_supplier" textShow="nama_supplier" upperCase="true"
        select2="select2Kodesupplier" />
    <div class="form-group mb-3">
        <select name="kode_asal_pengajuan" id="kode_asal_pengajuan" class="form-select">
            <option value="">Semua Asal Ajuan</option>
            @foreach ($asal_ajuan as $d)
                <option value="{{ $d['kode_group'] }}">
                    {{ $d['nama_group'] }}
                </option>
            @endforeach
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
            const formLapPembelian = $('#formLapPembelian');
            const select2KodeSupplier = $('.select2Kodesupplier');
            if (select2KodeSupplier.length) {
                select2KodeSupplier.each(function() {
                    var $this = $(this);
                    $this.wrap('<div class="position-relative"></div>').select2({
                        placeholder: 'Semua Supplier',
                        allowClear: true,
                        dropdownParent: $this.parent()
                    });
                });
            }

            formLapPembelian.submit(function(e) {
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
