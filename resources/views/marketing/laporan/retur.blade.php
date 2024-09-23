<form action="{{ route('laporanmarketing.cetakretur') }}" method="POST" target="_blank" id="formRetur">
    @csrf
    @hasanyrole($roles_show_cabang)
        <div class="form-group mb-3">
            <select name="kode_cabang" id="kode_cabang_retur" class="form-select select2Kodecabangretur">
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
            <select name="kode_salesman" id="kode_salesman_retur" class="select2Kodesalesmanretur form-select">
            </select>
        @endhasanyrole

    </div>
    <div class="form-group mb-3">
        <select name="kode_pelanggan" id="kode_pelanggan_retur" class="select2Kodepelangganretur form-select">
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
            <button type="submit" name="submitButton" class="btn btn-primary w-100" id="submitButtonRetur">
                <i class="ti ti-printer me-1"></i> Cetak
            </button>
        </div>
        <div class="col-lg-2 col-md-12 col-sm-12">
            <button type="submit" name="exportButton" class="btn btn-success w-100" id="exportButtonRetur">
                <i class="ti ti-download"></i>
            </button>
        </div>
    </div>
</form>
@push('myscript')
    <script>
        $(document).ready(function() {
            const formRetur = $("#formRetur");
            const select2Kodecabangretur = $(".select2Kodecabangretur");
            if (select2Kodecabangretur.length) {
                select2Kodecabangretur.each(function() {
                    var $this = $(this);
                    $this.wrap('<div class="position-relative"></div>').select2({
                        placeholder: 'Semua Cabang',
                        allowClear: true,
                        dropdownParent: $this.parent()
                    });
                });
            }

            const select2Kodesalesmanretur = $(".select2Kodesalesmanretur");
            if (select2Kodesalesmanretur.length) {
                select2Kodesalesmanretur.each(function() {
                    var $this = $(this);
                    $this.wrap('<div class="position-relative"></div>').select2({
                        placeholder: 'Semua Salesman',
                        allowClear: true,
                        dropdownParent: $this.parent()
                    });
                });
            }

            const select2Kodepelangganretur = $(".select2Kodepelangganretur");
            if (select2Kodepelangganretur.length) {
                select2Kodepelangganretur.each(function() {
                    var $this = $(this);
                    $this.wrap('<div class="position-relative"></div>').select2({
                        placeholder: 'Semua Pelanggan',
                        allowClear: true,
                        dropdownParent: $this.parent()
                    });
                });
            }

            function getsalesmanbyCabangRetur() {
                var kode_cabang = formRetur.find("#kode_cabang_retur").val();
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
                        formRetur.find("#kode_salesman_retur").html(respond);
                    }
                });
            }

            function getpelangganbySalesmanRetur() {
                var kode_salesman = formRetur.find("#kode_salesman_retur").val();
                var kode_cabang = formRetur.find("#kode_cabang_retur").val();
                //alert(selected);
                $.ajax({
                    type: 'POST',
                    url: '/pelanggan/getpelangganbysalesman',
                    data: {
                        _token: "{{ csrf_token() }}",
                        kode_salesman: kode_salesman,
                        kode_cabang: kode_cabang
                    },
                    cache: false,
                    success: function(respond) {
                        console.log(respond);
                        formRetur.find("#kode_pelanggan_retur").html(respond);
                    }
                });
            }

            getsalesmanbyCabangRetur();
            getpelangganbySalesmanRetur();
            formRetur.find("#kode_cabang_retur").change(function(e) {
                getsalesmanbyCabangRetur();
                getpelangganbySalesmanRetur();
            });

            formRetur.find("#kode_salesman_retur").change(function(e) {
                getpelangganbySalesmanRetur();
            });





            formRetur.submit(function(e) {

                const kode_cabang = formRetur.find('#kode_cabang_retur').val();
                const dari = formRetur.find('#dari').val();
                const sampai = formRetur.find('#sampai').val();
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
