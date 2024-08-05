<form action="{{ route('laporankeuangan.cetaklpu') }}" id="formLPU" target="_blank" method="POST">
    @csrf
    @hasanyrole($roles_show_cabang)
        <x-select label="Pilih Cabang" name="kode_cabang_lpu" :data="$cabang" key="kode_cabang" textShow="nama_cabang" upperCase="true"
            select2="select2Kodecabanglpu" />
    @endrole
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
        <div class="col">
            <div class="form-group mb-3">
                <select name="formatlaporan" id="formatlaporan" class="form-select">
                    <option value="">Format Laporan</option>
                    <option value="1">LHP - Setoran Penjualan</option>
                    <option value="2">LHP - Seoran Pusat</option>
                </select>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-10 col-md-12 col-sm-12">
                <button type="submit" class="btn btn-primary w-100">
                    <i class="ti ti-printer me-1"></i> Cetak
                </button>
            </div>
            <div class="col-lg-2 col-md-12 col-sm-12">
                <button type="submit" name="exportButton" class="btn btn-success w-100">
                    <i class="ti ti-download"></i>
                </button>
            </div>
        </div>
    </div>
</form>

@push('myscript')
    <script>
        $(function() {
            const formLPU = $("#formLPU");
            const select2Kodecabanglpu = $(".select2Kodecabanglpu");
            if (select2Kodecabanglpu.length) {
                select2Kodecabanglpu.each(function() {
                    var $this = $(this);
                    $this.wrap('<div class="position-relative"></div>').select2({
                        placeholder: 'Pilih Cabang',
                        allowClear: true,
                        dropdownParent: $this.parent()
                    });
                });
            }

            formLPU.submit(function(e) {
                const kode_cabang = formLPU.find("#kode_cabang_lpu").val();
                const bulan = formLPU.find("#bulan").val();
                const tahun = formLPU.find("#tahun").val();
                const formatlaporan = formLPU.find("#formatlaporan").val();
                if (kode_cabang == "") {
                    Swal.fire({
                        title: "Oops!",
                        text: 'Cabang Harus Diisi !',
                        icon: "warning",
                        showConfirmButton: true,
                        didClose: (e) => {
                            formLPU.find("#kode_cabang_lpu").focus();
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
                            formLPU.find("#bulan").focus();
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
                            formLPU.find("#tahun").focus();
                        },
                    });
                    return false;
                } else if (formatlaporan == "") {
                    Swal.fire({
                        title: "Oops!",
                        text: 'Format Laporan Harus Diisi !',
                        icon: "warning",
                        showConfirmButton: true,
                        didClose: (e) => {
                            formLPU.find("#formatlaporan").focus();
                        },
                    });
                    return false;
                }
            });
        });
    </script>
@endpush
