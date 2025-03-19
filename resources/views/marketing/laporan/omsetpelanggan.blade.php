<form action="{{ route('laporanmarketing.cetakomsetpelanggan') }}" method="POST" target="_blank" id="formomsetpelanggan">
    @csrf
    @hasanyrole($roles_show_cabang)
        <div class="form-group mb-3">
            <select name="kode_cabang" id="kode_cabang_omsetpelanggan" class="form-select select2Kodecabangomsetpelanggan">
                <option value="">Semua Cabang</option>
                @foreach ($cabang as $d)
                    <option value="{{ $d->kode_cabang }}">{{ textUpperCase($d->nama_cabang) }}</option>
                @endforeach
            </select>
        </div>
    @endrole
    <div class="form-group mb-3">
        <select name="format_laporan" id="format_laporan" class="form-select">
            <option value="">Pilih Format Laporan</option>
            <option value="1">Rekap</option>
            <option value="2">Tampilkan per Bulan</option>
        </select>
    </div>
    <div class="form-group mb-3">
        <select name="kode_salesman" id="kode_salesman_omsetpelanggan" class="select2Kodesalesmanomsetpelanggan form-select">
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
            <button type="submit" name="submitButton" class="btn btn-primary w-100" id="submitButtonomsetpelanggan">
                <i class="ti ti-printer me-1"></i> Cetak
            </button>
        </div>
        <div class="col-lg-2 col-md-12 col-sm-12">
            <button type="submit" name="exportButton" class="btn btn-success w-100" id="exportButtonomsetpelanggan">
                <i class="ti ti-download"></i>
            </button>
        </div>
    </div>
</form>
@push('myscript')
    <script>
        $(document).ready(function() {
            const formomsetpelanggan = $("#formomsetpelanggan");
            const select2Kodecabangomsetpelanggan = $(".select2Kodecabangomsetpelanggan");
            if (select2Kodecabangomsetpelanggan.length) {
                select2Kodecabangomsetpelanggan.each(function() {
                    var $this = $(this);
                    $this.wrap('<div class="position-relative"></div>').select2({
                        placeholder: 'Semua Cabang',
                        allowClear: true,
                        dropdownParent: $this.parent()
                    });
                });
            }

            const select2Kodesalesmanomsetpelanggan = $(".select2Kodesalesmanomsetpelanggan");
            if (select2Kodesalesmanomsetpelanggan.length) {
                select2Kodesalesmanomsetpelanggan.each(function() {
                    var $this = $(this);
                    $this.wrap('<div class="position-relative"></div>').select2({
                        placeholder: 'Semua Salesman',
                        allowClear: true,
                        dropdownParent: $this.parent()
                    });
                });
            }



            function getsalesmanbyCabangomsetpelanggan() {
                var kode_cabang = formomsetpelanggan.find("#kode_cabang_omsetpelanggan").val();
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
                        formomsetpelanggan.find("#kode_salesman_omsetpelanggan").html(respond);
                    }
                });
            }



            getsalesmanbyCabangomsetpelanggan();
            formomsetpelanggan.find("#kode_cabang_omsetpelanggan").change(function(e) {
                getsalesmanbyCabangomsetpelanggan();
            });







            formomsetpelanggan.submit(function(e) {

                const kode_cabang = formomsetpelanggan.find('#kode_cabang_omsetpelanggan').val();
                const dari = formomsetpelanggan.find('#dari').val();
                const sampai = formomsetpelanggan.find('#sampai').val();
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
