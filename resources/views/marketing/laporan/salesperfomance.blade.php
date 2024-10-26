<form action="{{ route('laporanmarketing.cetaksalesperfomance') }}" method="POST" target="_blank" id="formSalesperfomance">
    @csrf
    @hasanyrole($roles_show_cabang)
        <div class="form-group mb-3">
            <select name="kode_cabang" id="kode_cabang_salesperfomance" class="form-select select2Kodecabangsalseperfomance">
                <option value="">Semua Cabang</option>
                @foreach ($cabang as $d)
                    <option value="{{ $d->kode_cabang }}">{{ textUpperCase($d->nama_cabang) }}</option>
                @endforeach
            </select>
        </div>
    @endrole
    <div class="form-group mb-3">
        @hasanyrole('salesman')
            <input type="hidden" name="kode_salesman" value="{{ auth()->user()->kode_salesman }}">
        @else
            <select name="kode_salesman" id="kode_salesman_salesperfomance" class="select2Kodesalesmansalesperfomance form-select">
            </select>
        @endhasanyrole

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
            <button type="submit" name="submitButton" class="btn btn-primary w-100" id="submitButtonSalesperfomance">
                <i class="ti ti-printer me-1"></i> Cetak
            </button>
        </div>
        <div class="col-lg-2 col-md-12 col-sm-12">
            <button type="submit" name="exportButton" class="btn btn-success w-100" id="exportButtonSalesperfomance">
                <i class="ti ti-download"></i>
            </button>
        </div>
    </div>
</form>
@push('myscript')
    <script>
        $(document).ready(function() {
            const formSalesperfomance = $("#formSalesperfomance");
            const select2Kodecabangsalseperfomance = $(".select2Kodecabangsalseperfomance");
            if (select2Kodecabangsalseperfomance.length) {
                select2Kodecabangsalseperfomance.each(function() {
                    var $this = $(this);
                    $this.wrap('<div class="position-relative"></div>').select2({
                        placeholder: 'Pilih Cabang',
                        allowClear: true,
                        dropdownParent: $this.parent()
                    });
                });
            }

            const select2Kodesalesmansalesperfomance = $(".select2Kodesalesmansalesperfomance");
            if (select2Kodesalesmansalesperfomance.length) {
                select2Kodesalesmansalesperfomance.each(function() {
                    var $this = $(this);
                    $this.wrap('<div class="position-relative"></div>').select2({
                        placeholder: 'Pilih Salesman',
                        allowClear: true,
                        dropdownParent: $this.parent()
                    });
                });
            }



            function getsalesmanbyCabangSalesperfomance() {
                var kode_cabang = formSalesperfomance.find("#kode_cabang_salesperfomance").val();
                //alert(selected);
                $.ajax({
                    type: 'POST',
                    url: '/salesman/getsalesmanbycabang',
                    data: {
                        _token: "{{ csrf_token() }}",
                        kode_cabang: kode_cabang
                    },
                    cache: false,
                    success: function(respond) {
                        console.log(respond);
                        formSalesperfomance.find("#kode_salesman_salesperfomance").html(respond);
                    }
                });
            }



            getsalesmanbyCabangSalesperfomance();
            formSalesperfomance.find("#kode_cabang_salesperfomance").change(function(e) {
                getsalesmanbyCabangSalesperfomance();
            });







            formSalesperfomance.submit(function(e) {

                const kode_cabang = formSalesperfomance.find('#kode_cabang_salesperfomance').val();
                const kode_salesman = formSalesperfomance.find('#kode_salesman_salesperfomance').val();
                const dari = formSalesperfomance.find('#dari').val();
                const sampai = formSalesperfomance.find('#sampai').val();
                const start = new Date(dari);
                const end = new Date(sampai);
                if (kode_cabang == "") {
                    Swal.fire({
                        title: "Oops!",
                        text: "Cabang Harus Diisi !",
                        icon: "warning",
                        showConfirmButton: true,
                        didClose: (e) => {
                            $(this).find("#kode_cabang_salesperfomance").focus();
                        }
                    });
                    return false;
                } else if (kode_salesman == "") {
                    Swal.fire({
                        title: "Oops!",
                        text: "Salesman Harus Diisi !",
                        icon: "warning",
                        showConfirmButton: true,
                        didClose: (e) => {
                            $(this).find("#kode_salesman_salesperfomance").focus();
                        }
                    });
                    return false;
                } else if (dari == "") {
                    Swal.fire({
                        title: "Oops!",
                        text: "Dari Tanggal Harus Diisi !",
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
                        text: "Sampai Tanggal Harus Diisi !",
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
                        text: "Periode Tidak Valid !, Periode Sampai Harus Lebih Akhir dari Periode Dari",
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
