<form action="{{ route('laporankeuangan.cetakpinjaman') }}" id="formPinjaman" target="_blank" method="POST">
    @csrf
    @hasanyrole($roles_show_cabang_pjp)
        <x-select label="Pilih Cabang" name="kode_cabang_pinjaman" :data="$cabang" key="kode_cabang" textShow="nama_cabang" upperCase="true"
            select2="select2Kodecabangpinjaman" />
        <x-select label="Semua Departemen" name="kode_dept_pinjaman" :data="$departemen" key="kode_dept" textShow="nama_dept" upperCase="true"
            select2="select2Kodedeptpinjaman" />
    @endrole

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
            const formPinjaman = $("#formPinjaman");
            const select2Kodecabangpinjaman = $(".select2Kodecabangpinjaman");
            if (select2Kodecabangpinjaman.length) {
                select2Kodecabangpinjaman.each(function() {
                    var $this = $(this);
                    $this.wrap('<div class="position-relative"></div>').select2({
                        placeholder: 'Semua Cabang',
                        allowClear: true,
                        dropdownParent: $this.parent()
                    });
                });
            }



            formPinjaman.submit(function(e) {
                const kode_cabang = formPinjaman.find('#kode_cabang_pinjaman').val();
                const dari = formPinjaman.find('#dari').val();
                const sampai = formPinjaman.find('#sampai').val();
                const start = new Date(dari);
                const end = new Date(sampai);
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
                        text: 'Periode Sampai Harus Lebih Besar Dari Periode Dari !',
                        icon: "warning",
                        showConfirmButton: true,
                        didClose: (e) => {
                            $(this).find("#sampai").focus();
                        },
                    });
                    return false;
                }
            })
        });
    </script>
@endpush
