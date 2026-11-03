<form action="{{ route('laporankeuangan.cetakkartupiutangkeuangan') }}" id="formKartupiutangkeuangan" target="_blank" method="POST">
    @csrf
    @hasanyrole($roles_show_cabang_pjp)
        <x-select label="Semua Cabang" name="kode_cabang_kartupiutangkeuangan" :data="$cabang_pjp" key="kode_cabang" textShow="nama_cabang" upperCase="true"
            select2="select2Kodecabangkartupiutangkeuangan" hideLabel="true" />
        <x-select label="Semua Departemen" name="kode_dept_kartupiutangkeuangan" :data="$departemen_pjp" key="kode_dept" textShow="nama_dept" upperCase="true"
            select2="select2Kodedeptkartupiutangkeuangan" hideLabel="true" />
    @endrole

    <div class="row">
        <div class="col">
            <div class="form-group mb-3">
                <select name="bulan" id="bulan_kartupiutangkeuangan" class="form-select">
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
                <select name="tahun" id="tahun_kartupiutangkeuangan" class="form-select">
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
            const formKartupiutangkeuangan = $("#formKartupiutangkeuangan");
            const select2Kodecabangkartupiutangkeuangan = $(".select2Kodecabangkartupiutangkeuangan");
            if (select2Kodecabangkartupiutangkeuangan.length) {
                select2Kodecabangkartupiutangkeuangan.each(function() {
                    var $this = $(this);
                    $this.wrap('<div class="position-relative"></div>').select2({
                        placeholder: 'Semua Cabang',
                        allowClear: true,
                        dropdownParent: $this.parent()
                    });
                });
            }

            formKartupiutangkeuangan.submit(function(e) {
                const bulan = formKartupiutangkeuangan.find("#bulan_kartupiutangkeuangan").val();
                const tahun = formKartupiutangkeuangan.find("#tahun_kartupiutangkeuangan").val();
                if (bulan == "") {
                    Swal.fire({
                        title: "Oops!",
                        text: 'Bulan Harus Diisi !',
                        icon: "warning",
                        showConfirmButton: true,
                        didClose: (e) => {
                            formKartupiutangkeuangan.find("#bulan_kartupiutangkeuangan").focus();
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
                            formKartupiutangkeuangan.find("#tahun_kartupiutangkeuangan").focus();
                        },
                    });
                    return false;
                }
            });
        });
    </script>
@endpush
