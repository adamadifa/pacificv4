<form action="{{ route('laporanmarketing.cetakrekappenjualan') }}" method="POST" target="_blank" id="formRekappenjualan">
    @csrf
    <div class="form-group mb-3">
        <select name="jenis_laporan" id="jenis_laporan" class="form-select">
            <option value="">Jenis Laporan</option>
            <option value="1">Rekap Penjualan</option>
            <option value="2">Rekap Retur</option>
            <option value="3">Rekap Penjualan Qty</option>
            <option value="4">Rekap Penjualan Produk</option>
            <option value="5">Collect Aup</option>
            <option value="6">Rekap Qty & Netto per Salesman (Multi Tahun)</option>
        </select>
    </div>
    @hasanyrole($roles_show_cabang)
        <div class="form-group mb-3">
            <select name="kode_cabang" id="kode_cabang_rekappenjualan" class="form-select select2Kodecabangrekappenjualan">
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
            <select name="kode_salesman" id="kode_salesman_rekappenjualan" class="select2Kodesalesmanrekappenjualan form-select">
            </select>
        @endhasanyrole

    </div>

    <div class="row" id="tanggalaup">
        <x-input-with-icon icon="ti ti-calendar" label="Lihat Per Tanggal" name="tanggal" datepicker="flatpickr-date" hideLabel="true" />
    </div>
    <div class="row" id="tanggalpenjualan">
        <div class="col-lg-6 col-md-12 col-sm-12">
            <x-input-with-icon icon="ti ti-calendar" label="Dari" name="dari" datepicker="flatpickr-date" hideLabel="true" />
        </div>
        <div class="col-lg-6 col-md-12 col-sm-12">
            <x-input-with-icon icon="ti ti-calendar" label="Sampai" name="sampai" datepicker="flatpickr-date" hideLabel="true" />
        </div>
    </div>
    <div class="form-group mb-3" id="tahunmultitahun" style="display: none;">
        <select name="tahun[]" id="tahun_rekappenjualan" class="select2Tahunrekappenjualan form-select" multiple="multiple" data-placeholder="Pilih Tahun">
            @for ($y = date('Y'); $y >= $start_year; $y--)
                <option value="{{ $y }}">{{ $y }}</option>
            @endfor
        </select>
    </div>
    <div class="row">
        <div class="col-lg-10 col-md-12 col-sm-12">
            <button type="submit" name="submitButton" class="btn btn-primary w-100" id="submitButtonRekappenjualan">
                <i class="ti ti-printer me-1"></i> Cetak
            </button>
        </div>
        <div class="col-lg-2 col-md-12 col-sm-12">
            <button type="submit" name="exportButton" class="btn btn-success w-100" id="exportButtonRekappenjualan">
                <i class="ti ti-download"></i>
            </button>
        </div>
    </div>
</form>
@push('myscript')
    <script>
        $(document).ready(function() {
            const formRekappenjualan = $("#formRekappenjualan");

            function showtanggalaup() {
                const jenis_laporan = formRekappenjualan.find('#jenis_laporan').val();
                if (jenis_laporan == "5") {
                    $("#tanggalaup").show();
                    $("#tanggalpenjualan").hide();
                    $("#tahunmultitahun").hide();
                } else if (jenis_laporan == "6") {
                    $("#tanggalaup").hide();
                    $("#tanggalpenjualan").hide();
                    $("#tahunmultitahun").show();
                } else {
                    $("#tanggalaup").hide();
                    $("#tanggalpenjualan").show();
                    $("#tahunmultitahun").hide();
                }
            }

            showtanggalaup();
            formRekappenjualan.find('#jenis_laporan').on('change', function() {
                showtanggalaup();
            });
            const select2Kodecabangrekappenjualan = $(".select2Kodecabangrekappenjualan");
            if (select2Kodecabangrekappenjualan.length) {
                select2Kodecabangrekappenjualan.each(function() {
                    var $this = $(this);
                    $this.wrap('<div class="position-relative"></div>').select2({
                        placeholder: 'Semua Cabang',
                        allowClear: true,
                        dropdownParent: $this.parent()
                    });
                });
            }

            const select2Kodesalesmanrekappenjualan = $(".select2Kodesalesmanrekappenjualan");
            if (select2Kodesalesmanrekappenjualan.length) {
                select2Kodesalesmanrekappenjualan.each(function() {
                    var $this = $(this);
                    $this.wrap('<div class="position-relative"></div>').select2({
                        placeholder: 'Semua Salesman',
                        allowClear: true,
                        dropdownParent: $this.parent()
                    });
                });
            }

            const select2Tahunrekappenjualan = $(".select2Tahunrekappenjualan");
            if (select2Tahunrekappenjualan.length) {
                select2Tahunrekappenjualan.each(function() {
                    var $this = $(this);
                    $this.wrap('<div class="position-relative"></div>').select2({
                        placeholder: 'Pilih Tahun',
                        allowClear: true,
                        dropdownParent: $this.parent()
                    });
                });
            }

            function getsalesmanbyCabangRekappenjualan() {
                var kode_cabang = formRekappenjualan.find("#kode_cabang_rekappenjualan").val();
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
                        formRekappenjualan.find("#kode_salesman_rekappenjualan").html(respond);
                    }
                });
            }

            getsalesmanbyCabangRekappenjualan();
            formRekappenjualan.find("#kode_cabang_rekappenjualan").change(function(e) {
                getsalesmanbyCabangRekappenjualan();
            });

            formRekappenjualan.submit(function(e) {

                const kode_cabang = formRekappenjualan.find('#kode_cabang_rekappenjualan').val();
                const dari = formRekappenjualan.find('#dari').val();
                const sampai = formRekappenjualan.find('#sampai').val();
                const tanggal = formRekappenjualan.find('#tanggal').val();
                const start = new Date(dari);
                const end = new Date(sampai);
                const jenis_laporan = formRekappenjualan.find('#jenis_laporan').val();

                if (jenis_laporan == "") {
                    Swal.fire({
                        title: "Oops!",
                        text: "Jenis Laporan Harus Diisi !",
                        icon: "warning",
                        showConfirmButton: true,
                        didClose: (e) => {
                            $(this).find("#jenis_laporan").focus();
                        },

                    });
                    return false;

                } else if (dari == "" && jenis_laporan != "5" && jenis_laporan != "6") {
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
                } else if (sampai == "" && jenis_laporan != "5" && jenis_laporan != "6") {
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
                } else if (start.getTime() > end.getTime() && jenis_laporan != "5" && jenis_laporan != "6") {
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
                } else if (jenis_laporan == 5 && tanggal == "") {
                    Swal.fire({
                        title: "Oops!",
                        text: "Tanggal Harus Diisi !",
                        icon: "warning",
                        showConfirmButton: true,
                        didClose: (e) => {
                            $(this).find("#tanggal").focus();
                        },
                    });
                    return false;
                } else if (jenis_laporan == 6 && (!formRekappenjualan.find('#tahun_rekappenjualan').val() || formRekappenjualan.find('#tahun_rekappenjualan').val().length == 0)) {
                    Swal.fire({
                        title: "Oops!",
                        text: "Tahun Harus Diisi !",
                        icon: "warning",
                        showConfirmButton: true,
                        didClose: (e) => {
                            $(this).find("#tahun_rekappenjualan").focus();
                        },
                    });
                    return false;
                }
            })
        });
    </script>
@endpush
