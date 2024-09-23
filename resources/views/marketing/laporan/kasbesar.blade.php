<form action="{{ route('laporanmarketing.cetakkasbesar') }}" method="POST" target="_blank" id="formKasbesar">
    @csrf
    @hasanyrole($roles_show_cabang)
        <div class="form-group mb-3">
            <select name="kode_cabang" id="kode_cabang_kasbesar" class="form-select select2Kodecabangkasbesar">
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
            <select name="kode_salesman" id="kode_salesman_kasbesar" class="select2Kodesalesmankasbesar form-select">
            </select>
        @endhasanyrole

    </div>
    <div class="form-group mb-3">
        <select name="kode_pelanggan" id="kode_pelanggan_kasbesar" class="select2Kodepelanggankasbesar form-select">
        </select>
    </div>
    <div class="form-group mb-3">
        <select name="jenis_bayar" id="jenis_bayar" class="form-select">
            <option value="">Semua Jenis Pembayaran</option>
            <option value="TN">TUNAI</option>
            <option value="TP">TITIPAN</option>
            <option value="TR">TRANSFER</option>
            <option value="GR">GIRO</option>
        </select>
    </div>
    <div class="form-group mb-3" id="formatlaporanoptionkasbesar">
        <select name="formatlaporan" id="formatlaporan_kasbesar" class="form-select">
            <option value="">Format Laporan</option>
            <option value="1">Detail</option>
            <option value="2">Rekap</option>
            <option value="3">LHP</option>
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
            <button type="submit" name="submitButton" class="btn btn-primary w-100" id="submitButtonKasbesar">
                <i class="ti ti-printer me-1"></i> Cetak
            </button>
        </div>
        <div class="col-lg-2 col-md-12 col-sm-12">
            <button type="submit" name="exportButton" class="btn btn-success w-100" id="exportButtonKasbesar">
                <i class="ti ti-download"></i>
            </button>
        </div>
    </div>
</form>
@push('myscript')
    <script>
        $(document).ready(function() {
            const formKasbesar = $("#formKasbesar");
            const select2Kodecabangkasbesar = $(".select2Kodecabangkasbesar");
            if (select2Kodecabangkasbesar.length) {
                select2Kodecabangkasbesar.each(function() {
                    var $this = $(this);
                    $this.wrap('<div class="position-relative"></div>').select2({
                        placeholder: 'Semua Cabang',
                        allowClear: true,
                        dropdownParent: $this.parent()
                    });
                });
            }

            const select2Kodesalesmankasbesar = $(".select2Kodesalesmankasbesar");
            if (select2Kodesalesmankasbesar.length) {
                select2Kodesalesmankasbesar.each(function() {
                    var $this = $(this);
                    $this.wrap('<div class="position-relative"></div>').select2({
                        placeholder: 'Semua Salesman',
                        allowClear: true,
                        dropdownParent: $this.parent()
                    });
                });
            }

            const select2Kodepelanggankasbesar = $(".select2Kodepelanggankasbesar");
            if (select2Kodepelanggankasbesar.length) {
                select2Kodepelanggankasbesar.each(function() {
                    var $this = $(this);
                    $this.wrap('<div class="position-relative"></div>').select2({
                        placeholder: 'Semua Pelanggan',
                        allowClear: true,
                        dropdownParent: $this.parent()
                    });
                });
            }

            function getsalesmanbyCabangKasbesar() {
                var kode_cabang = formKasbesar.find("#kode_cabang_kasbesar").val();
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
                        formKasbesar.find("#kode_salesman_kasbesar").html(respond);
                    }
                });
            }

            function getpelangganbySalesmanKasbesar() {
                var kode_salesman = formKasbesar.find("#kode_salesman_kasbesar").val();
                var kode_cabang = formKasbesar.find("#kode_cabang_kasbesar").val();
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
                        formKasbesar.find("#kode_pelanggan_kasbesar").html(respond);
                    }
                });
            }

            getsalesmanbyCabangKasbesar();
            getpelangganbySalesmanKasbesar();
            formKasbesar.find("#kode_cabang_kasbesar").change(function(e) {
                getsalesmanbyCabangKasbesar();
                showformatlaporanKasbesar();
                getpelangganbySalesmanKasbesar();
            });

            formKasbesar.find("#kode_salesman_kasbesar").change(function(e) {
                getpelangganbySalesmanKasbesar();
            });

            formKasbesar.find("#formatlaporan_kasbesar").change(function(e) {
                showformatlaporanKasbesar();
            })

            function showformatlaporanKasbesar() {
                const kode_cabang = $("#kode_cabang_kasbesar").val();
                const formatlaporan = formKasbesar.find("#formatlaporan_kasbesar").val();
                if (kode_cabang == "" || formatlaporan == "2") {
                    formKasbesar.find("#kode_salesman_kasbesar").prop("disabled", true);
                    formKasbesar.find("#kode_pelanggan_kasbesar").prop("disabled", true);
                    formKasbesar.find("#jenis_transaksi_kasbesar").prop("disabled", true);
                    $('.select2Kodesalesmankasbesar').val('').trigger("change");
                    $('.select2Kodepelanggankasbesar').val('').trigger("change");
                } else {
                    formKasbesar.find("#kode_salesman_kasbesar").prop("disabled", false);
                    formKasbesar.find("#kode_pelanggan_kasbesar").prop("disabled", false);
                    formKasbesar.find("#jenis_transaksi_kasbesar").prop("disabled", false);
                }
            }

            showformatlaporanKasbesar();

            formKasbesar.submit(function(e) {
                const formatlaporan = formKasbesar.find("#formatlaporan_kasbesar").val();
                const kode_cabang = formKasbesar.find('#kode_cabang_kasbesar').val();
                const dari = formKasbesar.find('#dari').val();
                const sampai = formKasbesar.find('#sampai').val();
                const start = new Date(dari);
                const end = new Date(sampai);

                if (formatlaporan == "") {
                    Swal.fire({
                        title: "Oops!",
                        text: "Format Laporan Harus Diisi !",
                        icon: "warning",
                        showConfirmButton: true,
                        didClose: (e) => {
                            $(this).find("#formatlaporan_kasbesar").focus();
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
