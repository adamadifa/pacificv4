<form action="{{ route('laporankeuangan.cetakpiutangkaryawan') }}" id="formPiutangekskaryawan" target="_blank" method="POST">
    @csrf
    @hasanyrole($roles_show_cabang)
        <x-select label="Semua Cabang" name="kode_cabang_piutangkaryawan" :data="$cabang" key="kode_cabang" textShow="nama_cabang" upperCase="true"
            select2="select2Kodecabangpiutangekskaryawan" hideLabel="true" />
        <x-select label="Semua Departemen" name="kode_dept_piutangkaryawan" :data="$departemen" key="kode_dept" textShow="nama_dept" upperCase="true"
            select2="select2Kodedeptpiutangekskaryawan" hideLabel="true" />
    @endrole

    <input type="hidden" name="status_aktif_piutangkaryawan" value="EK">

    <div class="row">
        <div class="col-lg-6 col-md-12 col-sm-12">
            <x-input-with-icon icon="ti ti-calendar" label="Dari" name="dari" datepicker="flatpickr-date" hideLabel="true" />
        </div>
        <div class="col-lg-6 col-md-12 col-sm-12">
            <x-input-with-icon icon="ti ti-calendar" label="Sampai" name="sampai" datepicker="flatpickr-date" hideLabel="true" />
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
            const formPiutangekskaryawan = $("#formPiutangekskaryawan");
            const select2Kodecabangpiutangekskaryawan = $(".select2Kodecabangpiutangekskaryawan");
            if (select2Kodecabangpiutangekskaryawan.length) {
                select2Kodecabangpiutangekskaryawan.each(function() {
                    var $this = $(this);
                    $this.wrap('<div class="position-relative"></div>').select2({
                        placeholder: 'Semua Cabang',
                        allowClear: true,
                        dropdownParent: $this.parent()
                    });
                });
            }



            formPiutangekskaryawan.submit(function(e) {
                const kode_cabang = formPiutangekskaryawan.find('#kode_cabang_piutangkaryawan').val();
                const dari = formPiutangekskaryawan.find('#dari').val();
                const sampai = formPiutangekskaryawan.find('#sampai').val();
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
