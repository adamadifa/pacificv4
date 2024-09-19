<form action="{{ route('laporanmarketing.cetakkartupiutang') }}" method="POST" target="_blank" id="formKartupiutang">
    @csrf
    @hasanyrole($roles_show_cabang)
        <div class="form-group mb-3">
            <select name="kode_cabang" id="kode_cabang_kartupiutang" class="form-select select2Kodecabangkartupiutang">
                <option value="">Semua Cabang</option>
                @foreach ($cabang as $d)
                    <option value="{{ $d->kode_cabang }}">{{ textUpperCase($d->nama_cabang) }}</option>
                @endforeach
            </select>
        </div>
    @endrole
    <div class="form-group mb-3">
        <select name="kode_salesman" id="kode_salesman_kartupiutang" class="select2Kodesalesmankartupiutang form-select">
        </select>
    </div>
    <div class="form-group mb-3">
        <select name="kode_pelanggan" id="kode_pelanggan_kartupiutang" class="select2Kodepelanggankartupiutang form-select">
        </select>
    </div>
    <div class="form-group mb-3">
        <select name="formatlaporan" id="formatlaporan" class="form-select">
            <option value="">Format Laporan</option>
            <option value="1">Sudah Jatuh Tempo ( > 30 Hari )</option>
            <option value="2">Belum Jatuh Tempo</option>
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
            const formKartupiutang = $("#formKartupiutang");
            const select2Kodecabangkartupiutang = $(".select2Kodecabangkartupiutang");
            if (select2Kodecabangkartupiutang.length) {
                select2Kodecabangkartupiutang.each(function() {
                    var $this = $(this);
                    $this.wrap('<div class="position-relative"></div>').select2({
                        placeholder: 'Semua Cabang',
                        allowClear: true,
                        dropdownParent: $this.parent()
                    });
                });
            }

            const select2Kodesalesmankartupiutang = $(".select2Kodesalesmankartupiutang");
            if (select2Kodesalesmankartupiutang.length) {
                select2Kodesalesmankartupiutang.each(function() {
                    var $this = $(this);
                    $this.wrap('<div class="position-relative"></div>').select2({
                        placeholder: 'Semua Salesman',
                        allowClear: true,
                        dropdownParent: $this.parent()
                    });
                });
            }

            const select2Kodepelanggankartupiutang = $(".select2Kodepelanggankartupiutang");
            if (select2Kodepelanggankartupiutang.length) {
                select2Kodepelanggankartupiutang.each(function() {
                    var $this = $(this);
                    $this.wrap('<div class="position-relative"></div>').select2({
                        placeholder: 'Semua Pelanggan',
                        allowClear: true,
                        dropdownParent: $this.parent()
                    });
                });
            }

            function getsalesmanbyCabang() {
                var kode_cabang = formKartupiutang.find("#kode_cabang_kartupiutang").val();
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
                        formKartupiutang.find("#kode_salesman_kartupiutang").html(respond);
                    }
                });
            }

            function getpelangganbySalesman() {
                var kode_salesman = formKartupiutang.find("#kode_salesman_kartupiutang").val();
                var kode_cabang = formKartupiutang.find("#kode_cabang_kartupiutang").val();
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
                        formKartupiutang.find("#kode_pelanggan_kartupiutang").html(respond);
                    }
                });
            }
            getpelangganbySalesman();
            getsalesmanbyCabang();
            formKartupiutang.find("#kode_cabang_kartupiutang").change(function(e) {
                getsalesmanbyCabang();

                getpelangganbySalesman();
            });

            formKartupiutang.find("#kode_salesman_kartupiutang").change(function(e) {
                getpelangganbySalesman();
            });




            formKartupiutang.submit(function(e) {
                const formatlaporan = formKartupiutang.find("#formatlaporan").val();
                const kode_cabang = formKartupiutang.find('#kode_cabang_kartupiutang').val();
                const dari = formKartupiutang.find('#dari').val();
                const sampai = formKartupiutang.find('#sampai').val();
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
