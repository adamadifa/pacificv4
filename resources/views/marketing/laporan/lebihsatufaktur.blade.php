<form action="{{ route('laporanmarketing.cetaklebihsatufaktur') }}" method="POST" target="_blank" id="formLebihsatufaktur">
    @csrf
    @hasanyrole($roles_show_cabang)
        <div class="form-group mb-3">
            <select name="kode_cabang" id="kode_cabang_lebihsatufaktur" class="form-select select2Kodecabanglebihsatufaktur">
                <option value="">Semua Cabang</option>
                @foreach ($cabang as $d)
                    <option value="{{ $d->kode_cabang }}">{{ textUpperCase($d->nama_cabang) }}</option>
                @endforeach
            </select>
        </div>
    @endrole
    <div class="form-group mb-3">
        <select name="kode_salesman" id="kode_salesman_lebihsatufaktur" class="select2Kodesalesmanlebihsatufaktur form-select">
        </select>
    </div>

    <x-input-with-icon icon="ti ti-calendar" label="Lihat Per tanggal" name="tanggal" datepicker="flatpickr-date" />
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
        $(document).ready(function() {
            const formLebihsatufaktur = $("#formLebihsatufaktur");
            const select2Kodecabanglebihsatufaktur = $(".select2Kodecabanglebihsatufaktur");
            if (select2Kodecabanglebihsatufaktur.length) {
                select2Kodecabanglebihsatufaktur.each(function() {
                    var $this = $(this);
                    $this.wrap('<div class="position-relative"></div>').select2({
                        placeholder: 'Semua Cabang',
                        allowClear: true,
                        dropdownParent: $this.parent()
                    });
                });
            }

            const select2Kodesalesmanlebihsatufaktur = $(".select2Kodesalesmanlebihsatufaktur");
            if (select2Kodesalesmanlebihsatufaktur.length) {
                select2Kodesalesmanlebihsatufaktur.each(function() {
                    var $this = $(this);
                    $this.wrap('<div class="position-relative"></div>').select2({
                        placeholder: 'Semua Salesman',
                        allowClear: true,
                        dropdownParent: $this.parent()
                    });
                });
            }



            function getsalesmanbyCabang() {
                var kode_cabang = formLebihsatufaktur.find("#kode_cabang_lebihsatufaktur").val();
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
                        formLebihsatufaktur.find("#kode_salesman_lebihsatufaktur").html(respond);
                    }
                });
            }



            getsalesmanbyCabang();
            formLebihsatufaktur.find("#kode_cabang_lebihsatufaktur").change(function(e) {
                getsalesmanbyCabang();
            });






            formLebihsatufaktur.submit(function(e) {
                const tanggal = formLebihsatufaktur.find("#tanggal").val();

                if (tanggal == "") {
                    Swal.fire({
                        title: "Oops!",
                        text: " Tanggal Harus Diisi !",
                        icon: "warning",
                        showConfirmButton: true,
                        didClose: (e) => {
                            $(this).find("#tanggal").focus();
                        },
                    });
                    return false;
                }
            })
        });
    </script>
@endpush
