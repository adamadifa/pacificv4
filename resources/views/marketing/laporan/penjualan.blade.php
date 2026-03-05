<form action="{{ route('laporanmarketing.cetakpenjualan') }}" method="POST" target="_blank" id="formPenjualan">
    @csrf
    @hasanyrole($roles_show_cabang)
        <div class="form-group mb-3">
            <x-select label="Pilih Cabang" name="kode_cabang" id="kode_cabang_penjualan" :data="$cabang"
                key="kode_cabang" textShow="nama_cabang" select2="select2Kodecabangpenjualan" upperCase="true"
                hideLabel="true" allOption="true" allOptionLabel="Semua Cabang" />
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
        <x-select label="Jenis Transaksi" name="jenis_transaksi" id="jenis_transaksi" :data="[
            (object) ['kode' => 'T', 'nama' => 'TUNAI'],
            (object) ['kode' => 'K', 'nama' => 'KREDIT'],
        ]" key="kode" textShow="nama"
            hideLabel="true" />
    </div>
    <div class="form-group mb-3" id="formatlaporanoption">
        <x-select label="Format Laporan" name="formatlaporan" id="formatlaporan" :data="[
            (object) ['kode' => '1', 'nama' => 'Standar'],
            (object) ['kode' => '2', 'nama' => 'Format Satu Baris'],
            (object) ['kode' => '3', 'nama' => 'Transaksi PO'],
            (object) ['kode' => '5', 'nama' => 'Perhitungan Komisi'],
        ]" key="kode" textShow="nama"
            hideLabel="true" />
    </div>

    <div class="form-group mb-3">
        <x-select label="Status Penjualan" name="status_penjualan" id="status_penjualan" :data="[
            (object) ['kode' => '1', 'nama' => 'Batal'],
            (object) ['kode' => '2', 'nama' => 'Tanpa Status Batal'],
        ]" key="kode" textShow="nama"
            hideLabel="true" selected="2" />
    </div>
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
