<form method="POST" action="{{ route('laporangudanglogistik.cetakbarangkeluar') }}" id="frmLaporanbarangkeluar" target="_blank">
    @csrf
    <div class="row">
        <div class="col">
            <div class="form-group mb-3">
                <select name="kode_jenis_pengeluaran" id="kode_jenis_pengeluaran" class="form-select">
                    <option value="">Semua Jenis Pengeluaran</option>
                    @foreach ($list_jenis_pengeluaran as $d)
                        <option value="{{ $d['kode_jenis_pengeluaran'] }}">
                            {{ $d['jenis_pengeluaran'] }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <x-select label="Cabang" name="kode_cabang" :data="$cabang" key="kode_cabang" select2="Select2Kodecabang" textShow="nama_cabang"
                upperCase="true" />
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="form-group mb-3">
                <select name="kode_kategori" id="kode_kategori" class="form-select">
                    <option value="">Semua Kategori</option>
                    @foreach ($kategori as $d)
                        <option value="{{ $d->kode_kategori }}">
                            {{ $d->nama_kategori }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col">
            <div class="form-group mb-3">
                <select name="kode_barang" id="kode_barang_keluar" class="form-select select2Kodebarangkeluar">
                    <option value="">Semua Barang</option>
                </select>
            </div>

        </div>
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
        $(function() {
            const form = $("#frmLaporanbarangkeluar");

            function getbarangbykategori() {

                var kode_kategori = form.find("#kode_kategori").val();
                //alert(selected);
                $.ajax({
                    type: 'POST',
                    url: '/barangpembelian/getbarangbykategori',
                    data: {
                        _token: "{{ csrf_token() }}",
                        kode_kategori: kode_kategori
                    },
                    cache: false,
                    success: function(respond) {
                        console.log(respond);
                        form.find("#kode_barang_keluar").html(respond);
                    }
                });
            }


            form.find("#kode_kategori").change(function() {
                getbarangbykategori();
            });


            const select2Kodebarangkeluar = $('.select2Kodebarangkeluar');
            if (select2Kodebarangkeluar.length) {
                select2Kodebarangkeluar.each(function() {
                    var $this = $(this);
                    $this.wrap('<div class="position-relative"></div>').select2({
                        // placeholder: 'Semua Barang',
                        dropdownParent: $this.parent(),
                        placeholder: 'Semua Barang',
                        allowClear: true,
                    });
                });
            }

            const Select2Kodecabang = $('.Select2Kodecabang');
            if (Select2Kodecabang.length) {
                Select2Kodecabang.each(function() {
                    var $this = $(this);
                    $this.wrap('<div class="position-relative"></div>').select2({
                        // placeholder: 'Semua Barang',
                        dropdownParent: $this.parent(),
                        placeholder: 'Semua Cabang',
                        allowClear: true,
                    });
                });
            }


            form.submit(function() {
                const dari = $(this).find("#dari").val();
                const sampai = $(this).find("#sampai").val();
                var start = new Date(dari);
                var end = new Date(sampai);
                if (dari == "") {
                    Swal.fire({
                        title: "Oops!",
                        text: 'Periode Dari Harus Diisi !',
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
                        text: 'Periode Sampai Harus Diisi !',
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
                        text: 'Periode Tidak Valid !, Periode Sampai Harus Lebih Akhir dari Periode Dari',
                        icon: "warning",
                        showConfirmButton: true,
                        didClose: (e) => {
                            $(this).find("#sampai").focus();
                        },
                    });
                    return false;
                }
            });
        });
    </script>
@endpush
