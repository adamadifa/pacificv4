<form action="{{ route('laporanmarketing.cetakroutingsalesman') }}" method="POST" target="_blank" id="formRoutingsalesman">
    @csrf
    @hasanyrole($roles_show_cabang)
        <div class="form-group mb-3">
            <select name="kode_cabang" id="kode_cabang_routingsalesman" class="form-select select2Kodecabangroutingsalesman">
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
            <select name="kode_salesman" id="kode_salesman_routingsalesman" class="select2Kodesalesmanroutingsalesman form-select">
            </select>
        @endhasanyrole

    </div>
    <div class="form-group mb-3">
        <select name="formatlaporan" id="formatlaporan" class="formatlaporan form-select">
            <option value="1">Detail</option>
            <option value="2">Rekap</option>
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
            <button type="submit" name="submitButton" class="btn btn-primary w-100" id="submitButtonRoutingsalesman">
                <i class="ti ti-printer me-1"></i> Cetak
            </button>
        </div>
        <div class="col-lg-2 col-md-12 col-sm-12">
            <button type="submit" name="exportButton" class="btn btn-success w-100" id="exportButtonRoutingsalesman">
                <i class="ti ti-download"></i>
            </button>
        </div>
    </div>
</form>
@push('myscript')
    <script>
        $(document).ready(function() {
            const formRoutingsalesman = $("#formRoutingsalesman");
            const select2Kodecabangroutingsalesman = $(".select2Kodecabangroutingsalesman");
            if (select2Kodecabangroutingsalesman.length) {
                select2Kodecabangroutingsalesman.each(function() {
                    var $this = $(this);
                    $this.wrap('<div class="position-relative"></div>').select2({
                        placeholder: 'Semua Cabang',
                        allowClear: true,
                        dropdownParent: $this.parent()
                    });
                });
            }

            const select2Kodesalesmanroutingsalesman = $(".select2Kodesalesmanroutingsalesman");
            if (select2Kodesalesmanroutingsalesman.length) {
                select2Kodesalesmanroutingsalesman.each(function() {
                    var $this = $(this);
                    $this.wrap('<div class="position-relative"></div>').select2({
                        placeholder: 'Semua Salesman',
                        allowClear: true,
                        dropdownParent: $this.parent()
                    });
                });
            }

            const select2Kodepelangganroutingsalesman = $(".select2Kodepelangganroutingsalesman");
            if (select2Kodepelangganroutingsalesman.length) {
                select2Kodepelangganroutingsalesman.each(function() {
                    var $this = $(this);
                    $this.wrap('<div class="position-relative"></div>').select2({
                        placeholder: 'Semua Pelanggan',
                        allowClear: true,
                        dropdownParent: $this.parent()
                    });
                });
            }

            function getsalesmanbyCabangRoutingsalesman() {
                var kode_cabang = formRoutingsalesman.find("#kode_cabang_routingsalesman").val();
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
                        formRoutingsalesman.find("#kode_salesman_routingsalesman").html(respond);
                    }
                });
            }


            getsalesmanbyCabangRoutingsalesman();

            formRoutingsalesman.find("#kode_cabang_routingsalesman").change(function(e) {
                getsalesmanbyCabangRoutingsalesman();
            });


            formRoutingsalesman.submit(function(e) {
                const kode_cabang = formRoutingsalesman.find('#kode_cabang_routingsalesman').val();
                const dari = formRoutingsalesman.find('#dari').val();
                const sampai = formRoutingsalesman.find('#sampai').val();
                const start = new Date(dari);
                const end = new Date(sampai);

                if (dari == "") {
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
