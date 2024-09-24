<form action="{{ route('laporanmarketing.cetakpenjualan') }}" method="POST" target="_blank" id="formPenjualan">
    @csrf
    @hasanyrole($roles_show_cabang)
        <div class="form-group mb-3">
            <select name="kode_cabang" id="kode_cabang_penjualan" class="form-select select2Kodecabangpenjualan">
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
            <select name="kode_salesman" id="kode_salesman_penjualan" class="select2Kodesalesman form-select">
            </select>
        @endhasanyrole

    </div>
    <div class="form-group mb-3">
        <select name="kode_pelanggan" id="kode_pelanggan_penjualan" class="select2Kodepelanggan form-select">
        </select>
    </div>
    <div class="form-group mb-3">
        <select name="jenis_transaksi" id="jenis_transaksi" class="form-select">
            <option value="">Semua Jenis Transaksi</option>
            <option value="T">TUNAI</option>
            <option value="K">KREDIT</option>
        </select>
    </div>
    <div class="form-group mb-3" id="formatlaporanoption">
        <select name="formatlaporan" id="formatlaporan" class="form-select">
            <option value="">Format Laporan</option>
            <option value="1">Standar</option>
            <option value="2">Format Satu Baris</option>
            <option value="3">Transaksi PO</option>
            {{-- <option value="4">Transaksi PO</option> --}}
            <option value="5">Perhitungan Komisi</option>
        </select>
    </div>

    <div class="form-group mb-3">
        <select name="status_penjualan" id="status_penjualan" class="form-select">
            <option value="">Status Penjualan</option>
            <option value="1">Batal</option>
            <option value="2" selected>Tanpa Status Batal</option>
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
        $(document).ready(function() {
            const formPenjualan = $("#formPenjualan");
            const select2Kodecabangpenjualan = $(".select2Kodecabangpenjualan");
            if (select2Kodecabangpenjualan.length) {
                select2Kodecabangpenjualan.each(function() {
                    var $this = $(this);
                    $this.wrap('<div class="position-relative"></div>').select2({
                        placeholder: 'Semua Cabang',
                        allowClear: true,
                        dropdownParent: $this.parent()
                    });
                });
            }

            const select2Kodesalesman = $(".select2Kodesalesman");
            if (select2Kodesalesman.length) {
                select2Kodesalesman.each(function() {
                    var $this = $(this);
                    $this.wrap('<div class="position-relative"></div>').select2({
                        placeholder: 'Semua Salesman',
                        allowClear: true,
                        dropdownParent: $this.parent()
                    });
                });
            }

            const select2Kodepelanggan = $(".select2Kodepelanggan");
            if (select2Kodepelanggan.length) {
                select2Kodepelanggan.each(function() {
                    var $this = $(this);
                    $this.wrap('<div class="position-relative"></div>').select2({
                        placeholder: 'Semua Pelanggan',
                        allowClear: true,
                        dropdownParent: $this.parent()
                    });
                });
            }

            function getsalesmanbyCabang() {
                var kode_cabang = formPenjualan.find("#kode_cabang_penjualan").val();
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
                        formPenjualan.find("#kode_salesman_penjualan").html(respond);
                    }
                });
            }

            function getpelangganbySalesman() {
                var kode_salesman = formPenjualan.find("#kode_salesman_penjualan").val();
                var kode_cabang = formPenjualan.find("#kode_cabang_penjualan").val();
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
                        formPenjualan.find("#kode_pelanggan_penjualan").html(respond);
                    }
                });
            }

            getsalesmanbyCabang();
            getpelangganbySalesman();
            formPenjualan.find("#kode_cabang_penjualan").change(function(e) {
                getsalesmanbyCabang();
                showformatlaporan();
                getpelangganbySalesman();
            });

            formPenjualan.find("#kode_salesman_penjualan").change(function(e) {
                getpelangganbySalesman();
            });

            function showformatlaporan() {
                const kode_cabang = $("#kode_cabang_penjualan").val();
                if (kode_cabang == "") {
                    formPenjualan.find("#formatlaporanoption").hide();
                    formPenjualan.find("#kode_salesman_penjualan").prop("disabled", true);
                    formPenjualan.find("#kode_pelanggan_penjualan").prop("disabled", true);
                    formPenjualan.find("#jenis_transaksi").prop("disabled", true);
                    $('.select2Kodesalesman').val('').trigger("change");
                    $('.select2Kodepelanggan').val('').trigger("change");
                } else {
                    formPenjualan.find("#formatlaporanoption").show();
                    formPenjualan.find("#kode_salesman_penjualan").prop("disabled", false);
                    formPenjualan.find("#kode_pelanggan_penjualan").prop("disabled", false);
                    formPenjualan.find("#jenis_transaksi").prop("disabled", false);
                }
            }

            showformatlaporan();

            formPenjualan.submit(function(e) {
                const formatlaporan = formPenjualan.find("#formatlaporan").val();
                const kode_cabang = formPenjualan.find('#kode_cabang_penjualan').val();
                const dari = formPenjualan.find('#dari').val();
                const sampai = formPenjualan.find('#sampai').val();
                const start = new Date(dari);
                const end = new Date(sampai);

                if (kode_cabang != "" && formatlaporan == "") {
                    Swal.fire({
                        title: "Oops!",
                        text: "Jenis Laporan Harus Diisi !",
                        icon: "warning",
                        showConfirmButton: true,
                        didClose: (e) => {
                            $(this).find("#formatlaporan").focus();
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
