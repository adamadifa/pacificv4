<form action="{{ route('laporanmarketing.cetakdpp') }}" method="POST" target="_blank" id="formDpp">
    @csrf
    @hasanyrole($roles_show_cabang)
        <div class="form-group mb-3">
            <select name="kode_cabang" id="kode_cabang_dpp" class="form-select select2Kodecabangdpp">
                <option value="">Semua Cabang</option>
                @foreach ($cabang as $d)
                    <option value="{{ $d->kode_cabang }}">{{ textUpperCase($d->nama_cabang) }}</option>
                @endforeach
            </select>
        </div>
    @endrole
    <div class="form-group mb-3">
        <select name="kode_salesman" id="kode_salesman_dpp" class="select2Kodesalesmandpp form-select">
        </select>
    </div>
    <div class="form-group mb-3">
        <select name="kode_pelanggan" id="kode_pelanggan_dpp" class="select2Kodepelanggandpp form-select">
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
            <button type="submit" name="submitButton" class="btn btn-primary w-100" id="submitButtonDpp">
                <i class="ti ti-printer me-1"></i> Cetak
            </button>
        </div>
        <div class="col-lg-2 col-md-12 col-sm-12">
            <button type="submit" name="exportButton" class="btn btn-success w-100" id="exportButtonDpp">
                <i class="ti ti-download"></i>
            </button>
        </div>
    </div>
</form>
@push('myscript')
    <script>
        $(document).ready(function() {
            const formDpp = $("#formDpp");
            const select2Kodecabangdpp = $(".select2Kodecabangdpp");
            if (select2Kodecabangdpp.length) {
                select2Kodecabangdpp.each(function() {
                    var $this = $(this);
                    $this.wrap('<div class="position-relative"></div>').select2({
                        placeholder: 'Semua Cabang',
                        allowClear: true,
                        dropdownParent: $this.parent()
                    });
                });
            }

            const select2Kodesalesmandpp = $(".select2Kodesalesmandpp");
            if (select2Kodesalesmandpp.length) {
                select2Kodesalesmandpp.each(function() {
                    var $this = $(this);
                    $this.wrap('<div class="position-relative"></div>').select2({
                        placeholder: 'Semua Salesman',
                        allowClear: true,
                        dropdownParent: $this.parent()
                    });
                });
            }

            const select2Kodepelanggandpp = $(".select2Kodepelanggandpp");
            if (select2Kodepelanggandpp.length) {
                select2Kodepelanggandpp.each(function() {
                    var $this = $(this);
                    $this.wrap('<div class="position-relative"></div>').select2({
                        placeholder: 'Semua Pelanggan',
                        allowClear: true,
                        dropdownParent: $this.parent()
                    });
                });
            }

            function getsalesmandbyCabangDpp() {
                var kode_cabang = formDpp.find("#kode_cabang_dpp").val();
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
                        formDpp.find("#kode_salesman_dpp").html(respond);
                    }
                });
            }

            function getpelangganbySalesmanDpp() {
                var kode_salesman = formDpp.find("#kode_salesman_dpp").val();
                var kode_cabang = formDpp.find("#kode_cabang_dpp").val();
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
                        formDpp.find("#kode_pelanggan_dpp").html(respond);
                    }
                });
            }
            getpelangganbySalesmanDpp();
            getsalesmandbyCabangDpp();
            formDpp.find("#kode_cabang_dpp").change(function(e) {
                getsalesmandbyCabangDpp();
                getpelangganbySalesmanDpp();
            });

            formDpp.find("#kode_salesman_dpp").change(function(e) {
                getpelangganbySalesmanDpp();
            });





            formDpp.submit(function(e) {

                const kode_cabang = formDpp.find('#kode_cabang_dpp').val();
                const dari = formDpp.find('#dari').val();
                const sampai = formDpp.find('#sampai').val();
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
