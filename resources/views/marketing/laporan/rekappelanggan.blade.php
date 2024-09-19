<form action="{{ route('laporanmarketing.cetakrekappelanggan') }}" method="POST" target="_blank" id="formrekappelanggan">
    @csrf
    @hasanyrole($roles_show_cabang)
        <div class="form-group mb-3">
            <select name="kode_cabang" id="kode_cabang_rekappelanggan" class="form-select select2Kodecabangrekappelanggan">
                <option value="">Semua Cabang</option>
                @foreach ($cabang as $d)
                    <option value="{{ $d->kode_cabang }}">{{ textUpperCase($d->nama_cabang) }}</option>
                @endforeach
            </select>
        </div>
    @endrole
    <div class="form-group mb-3">
        <select name="kode_salesman" id="kode_salesman_rekappelanggan" class="select2Kodesalesmanrekappelanggan form-select">
        </select>
    </div>
    <div class="form-group mb-3">
        <select name="kode_pelanggan" id="kode_pelanggan_rekappelanggan" class="select2Kodepelangganrekappelanggan form-select">
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
            <button type="submit" name="submitButton" class="btn btn-primary w-100" id="submitButtonrekappelanggan">
                <i class="ti ti-printer me-1"></i> Cetak
            </button>
        </div>
        <div class="col-lg-2 col-md-12 col-sm-12">
            <button type="submit" name="exportButton" class="btn btn-success w-100" id="exportButtonrekappelanggan">
                <i class="ti ti-download"></i>
            </button>
        </div>
    </div>
</form>
@push('myscript')
    <script>
        $(document).ready(function() {
            const formrekappelanggan = $("#formrekappelanggan");
            const select2Kodecabangrekappelanggan = $(".select2Kodecabangrekappelanggan");
            if (select2Kodecabangrekappelanggan.length) {
                select2Kodecabangrekappelanggan.each(function() {
                    var $this = $(this);
                    $this.wrap('<div class="position-relative"></div>').select2({
                        placeholder: 'Semua Cabang',
                        allowClear: true,
                        dropdownParent: $this.parent()
                    });
                });
            }

            const select2Kodesalesmanrekappelanggan = $(".select2Kodesalesmanrekappelanggan");
            if (select2Kodesalesmanrekappelanggan.length) {
                select2Kodesalesmanrekappelanggan.each(function() {
                    var $this = $(this);
                    $this.wrap('<div class="position-relative"></div>').select2({
                        placeholder: 'Semua Salesman',
                        allowClear: true,
                        dropdownParent: $this.parent()
                    });
                });
            }

            const select2Kodepelangganrekappelanggan = $(".select2Kodepelangganrekappelanggan");
            if (select2Kodepelangganrekappelanggan.length) {
                select2Kodepelangganrekappelanggan.each(function() {
                    var $this = $(this);
                    $this.wrap('<div class="position-relative"></div>').select2({
                        placeholder: 'Semua Pelanggan',
                        allowClear: true,
                        dropdownParent: $this.parent()
                    });
                });
            }

            function getsalesmandbyCabangrekappelanggan() {
                var kode_cabang = formrekappelanggan.find("#kode_cabang_rekappelanggan").val();
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
                        formrekappelanggan.find("#kode_salesman_rekappelanggan").html(respond);
                    }
                });
            }

            function getpelangganbySalesmanrekappelanggan() {
                var kode_salesman = formrekappelanggan.find("#kode_salesman_rekappelanggan").val();
                var kode_cabang = formrekappelanggan.find("#kode_cabang_rekappelanggan").val();
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
                        formrekappelanggan.find("#kode_pelanggan_rekappelanggan").html(respond);
                    }
                });
            }
            getpelangganbySalesmanrekappelanggan();
            getsalesmandbyCabangrekappelanggan();
            formrekappelanggan.find("#kode_cabang_rekappelanggan").change(function(e) {
                getsalesmandbyCabangrekappelanggan();
                getpelangganbySalesmanrekappelanggan();
            });

            formrekappelanggan.find("#kode_salesman_rekappelanggan").change(function(e) {
                getpelangganbySalesmanrekappelanggan();
            });





            formrekappelanggan.submit(function(e) {

                const kode_cabang = formrekappelanggan.find('#kode_cabang_rekappelanggan').val();
                const dari = formrekappelanggan.find('#dari').val();
                const sampai = formrekappelanggan.find('#sampai').val();
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
