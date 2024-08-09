<form action="{{ route('laporankeuangan.cetakkartupiutangkaryawan') }}" id="formKartupiutangkaryawan" target="_blank" method="POST">
    @csrf
    @hasanyrole($roles_show_cabang)
        <x-select label="Pilih Cabang" name="kode_cabang_kartupiutangkaryawan" :data="$cabang" key="kode_cabang" textShow="nama_cabang" upperCase="true"
            select2="select2Kodecabangkartupiutangkaryawan" />
        <x-select label="Semua Departemen" name="kode_dept_kartupiutangkaryawan" :data="$departemen" key="kode_dept" textShow="nama_dept" upperCase="true"
            select2="select2Kodedeptkartupiutangkaryawan" />
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
            const formKartupiutangkaryawan = $("#formKartupiutangkaryawan");
            const select2Kodecabangkartupiutangkaryawan = $(".select2Kodecabangkartupiutangkaryawan");
            if (select2Kodecabangkartupiutangkaryawan.length) {
                select2Kodecabangkartupiutangkaryawan.each(function() {
                    var $this = $(this);
                    $this.wrap('<div class="position-relative"></div>').select2({
                        placeholder: 'Semua Cabang',
                        allowClear: true,
                        dropdownParent: $this.parent()
                    });
                });
            }

            formKartupiutangkaryawan.submit(function(e) {
                const bulan = formKartupiutangkaryawan.find("#bulan").val();
                const tahun = formKartupiutangkaryawan.find("#tahun").val();
                if (bulan == "") {
                    Swal.fire({
                        title: "Oops!",
                        text: 'Bulan Harus Diisi !',
                        icon: "warning",
                        showConfirmButton: true,
                        didClose: (e) => {
                            formKartupiutangkaryawan.find("#bulan").focus();
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
                            formKartupiutangkaryawan.find("#tahun").focus();
                        },
                    });
                    return false;
                }
            });
        });
    </script>
@endpush
