<form action="{{ route('laporanmarketing.cetakanalisatransaksi') }}" method="POST" target="_blank" id="formanalisatransaksi">
    @csrf
    @hasanyrole($roles_show_cabang)
        <div class="form-group mb-3">
            <select name="kode_cabang" id="kode_cabang_analisatransaksi" class="form-select select2Kodecabanganalisatransaksi">
                <option value="">Pilih Cabang</option>
                @foreach ($cabang as $d)
                    <option value="{{ $d->kode_cabang }}">{{ textUpperCase($d->nama_cabang) }}</option>
                @endforeach
            </select>
        </div>
    @endrole
    <div class="form-group mb-3">
        <select name="kode_salesman" id="kode_salesman_analisatransaksi" class="select2Kodesalesmananalisatransaksi form-select">
        </select>
    </div>
    <div class="row">
        <div class="col">
            <div class="form-group mb-3">
                <select name="bulan" id="bulan" class="form-select">
                    <option value="">Bulan</option>
                    @foreach ($list_bulan as $d)
                        <option value="{{ $d['kode_bulan'] }}">{{ $d['nama_bulan'] }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col">
            <div class="form-group mb-3">
                <select name="tahun" id="tahun" class="form-select">
                    <option value="">Tahun</option>
                    @for ($t = $start_year; $t <= date('Y'); $t++)
                        <option value="{{ $t }}">{{ $t }}</option>
                    @endfor
                </select>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-10 col-md-12 col-sm-12">
            <button type="submit" name="submitButton" class="btn btn-primary w-100" id="submitButtonAnalisatransaksi">
                <i class="ti ti-printer me-1"></i> Cetak
            </button>
        </div>
        <div class="col-lg-2 col-md-12 col-sm-12">
            <button type="submit" name="exportButton" class="btn btn-success w-100" id="exportButtonAnalisatransaksi">
                <i class="ti ti-download"></i>
            </button>
        </div>
    </div>
</form>
@push('myscript')
    <script>
        $(document).ready(function() {
            const formanalisatransaksi = $("#formanalisatransaksi");
            const select2Kodecabanganalisatransaksi = $(".select2Kodecabanganalisatransaksi");
            if (select2Kodecabanganalisatransaksi.length) {
                select2Kodecabanganalisatransaksi.each(function() {
                    var $this = $(this);
                    $this.wrap('<div class="position-relative"></div>').select2({
                        placeholder: 'Semua Cabang',
                        allowClear: true,
                        dropdownParent: $this.parent()
                    });
                });
            }

            const select2Kodesalesmananalisatransaksi = $(".select2Kodesalesmananalisatransaksi");
            if (select2Kodesalesmananalisatransaksi.length) {
                select2Kodesalesmananalisatransaksi.each(function() {
                    var $this = $(this);
                    $this.wrap('<div class="position-relative"></div>').select2({
                        placeholder: 'Semua Salesman',
                        allowClear: true,
                        dropdownParent: $this.parent()
                    });
                });
            }

            const select2Kodepelanggananalisatransaksi = $(".select2Kodepelanggananalisatransaksi");
            if (select2Kodepelanggananalisatransaksi.length) {
                select2Kodepelanggananalisatransaksi.each(function() {
                    var $this = $(this);
                    $this.wrap('<div class="position-relative"></div>').select2({
                        placeholder: 'Semua Pelanggan',
                        allowClear: true,
                        dropdownParent: $this.parent()
                    });
                });
            }

            function getsalesmandbyCabanganalisatransaksi() {
                var kode_cabang = formanalisatransaksi.find("#kode_cabang_analisatransaksi").val();
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
                        formanalisatransaksi.find("#kode_salesman_analisatransaksi").html(respond);
                    }
                });
            }



            getsalesmandbyCabanganalisatransaksi();
            formanalisatransaksi.find("#kode_cabang_analisatransaksi").change(function(e) {
                getsalesmandbyCabanganalisatransaksi();
            });



            formanalisatransaksi.submit(function(e) {

                const kode_cabang = formanalisatransaksi.find('#kode_cabang_analisatransaksi').val();
                const bulan = formanalisatransaksi.find('#bulan').val();
                const tahun = formanalisatransaksi.find('#tahun').val();
                if (kode_cabang == "") {
                    Swal.fire({
                        title: "Oops!",
                        text: 'Cabang Harus Diisi !',
                        icon: "warning",
                        showConfirmButton: true,
                        didClose: (e) => {
                            formanalisatransaksi.find("#kode_cabang_analisatransaksi").focus();
                        },
                    });
                    return false;
                } else if (bulan == "") {
                    Swal.fire({
                        title: "Oops!",
                        text: 'Bulan Harus Diisi !',
                        icon: "warning",
                        showConfirmButton: true,
                        didClose: (e) => {
                            formanalisatransaksi.find("#bulan").focus();
                        },
                    });
                    return false;
                } else if (tahun == "") {
                    Swal.fire({
                        title: "Oops!",
                        text: 'Tahun Harus Diisi !',
                        icon: "warning",
                        showConfirmButton: true,
                        didClose: (e) => {
                            formanalisatransaksi.find("#tahun").focus();
                        },
                    });
                    return false;
                }
            })
        });
    </script>
@endpush
