<form action="{{ route('laporanhrd.cetakpresensi') }}" method="POST" target="_blank" id="formPresensi">
    @csrf
    @hasanyrole($roles_access_all_karyawan)
        <div class="form-group mb-3">
            <select name="kode_cabang" id="kode_cabang_presensi" class="form-select select2Kodecabangpresensi">
                <option value="">Semua Cabang</option>
                @foreach ($cabang as $d)
                    <option value="{{ $d->kode_cabang }}">{{ textUpperCase($d->nama_cabang) }}</option>
                @endforeach
            </select>
        </div>
    @endrole
    <div class="form-group mb-3">
        <select name="kode_dept" id="kode_dept_presensi" class="form-select select2Kodedeptpresensi">
            <option value="">Semua Departemen</option>
        </select>
    </div>
    <div class="form-group mb-3">
        <select name="kode_group" id="kode_group_presensi" class="form-select select2Kodegrouppresensi">
            <option value="">Semua Group</option>
        </select>
    </div>
    <div class="form-group mb-3">
        <select name="periode_laporan" id="periode_laporan" class="form-select">
            <option value="">Periode Laporan</option>
            <option value="1" selected>Periode Gaji</option>
            <option value="2">Bulan Berjalan</option>
        </select>
    </div>
    <div class="form-group mb-3">
        <select name="format_laporan" id="format_laporan" class="form-select">
            <option value="">Format Laporan</option>
            <option value="1" selected>Standar</option>
            <option value="2">P/S/M</option>
        </select>
    </div>
    <div class="form-group mb-3">
        <select name="kategori_laporan" id="kategori_laporan" class="form-select">
            <option value="">Kategori Laporan</option>
            <option value="MJ">Manajemen</option>
            <option value="NM">Non Manajemen</option>
        </select>
    </div>
    <div class="row">
        <div class="col">
            <div class="form-group mb-3">
                <select name="bulan" id="bulan" class="form-select">
                    <option value="">Bulan</option>
                    @foreach ($list_bulan as $d)
                        <option {{ date('m') == $d['kode_bulan'] ? 'selected' : '' }} value="{{ $d['kode_bulan'] }}">{{ $d['nama_bulan'] }}</option>
                    @endforeach
                </select>
            </div>
        </div>

    </div>
    <div class="row">
        <div class="co">
            <div class="form-group mb-3">
                <select name="tahun" id="tahun" class="form-select">
                    <option value="">Tahun</option>
                    @for ($t = $start_year; $t <= date('Y'); $t++)
                        <option {{ date('Y') == $t ? 'selected' : '' }} value="{{ $t }}">{{ $t }}</option>
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
            const select2Kodecabangpresensi = $(".select2Kodecabangpresensi");
            if (select2Kodecabangpresensi.length) {
                select2Kodecabangpresensi.each(function() {
                    var $this = $(this);
                    $this.wrap('<div class="position-relative"></div>').select2({
                        placeholder: 'Semua Cabang',
                        allowClear: true,
                        dropdownParent: $this.parent()
                    });
                });
            }

            function getDepartemen() {
                const kode_cabang = $("#kode_cabang_presensi").val();
                $.ajax({
                    type: 'POST',
                    url: '{{ route('laporanhrd.getdepartemen') }}',
                    data: {
                        _token: '{{ csrf_token() }}',
                        kode_cabang: kode_cabang
                    },
                    cache: false,
                    success: function(res) {
                        $("#kode_dept_presensi").html(res);
                    }
                });
            }

            function getGroup() {
                const kode_cabang = $("#kode_cabang_presensi").val();
                $.ajax({
                    type: 'POST',
                    url: '{{ route('laporanhrd.getgroup') }}',
                    data: {
                        _token: '{{ csrf_token() }}',
                        kode_cabang: kode_cabang
                    },
                    cache: false,
                    success: function(res) {
                        $("#kode_group_presensi").html(res);
                    }
                });
            }

            getDepartemen();
            getGroup();

            $("#kode_cabang_presensi").change(function(e) {
                e.preventDefault();
                getDepartemen();
                getGroup();
            });

            $("#formPresensi").submit(function(e) {
                const periode_laporan = $("#periode_laporan").val();
                const bulan = $(this).find("#bulan").val();
                const tahun = $(this).find("#tahun").val();

                if (periode_laporan == "") {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Oops...',
                        text: 'Periode Laporan harus diisi!',
                        showConfirmButton: true,
                        didClose: () => {
                            $("#periode_laporan").focus();
                        }
                    });
                    return false;
                } else if (bulan == "") {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Oops...',
                        text: 'Bulan harus diisi!',
                        showConfirmButton: true,
                        didClose: () => {
                            $("#bulan").focus();
                        }
                    });
                    return false;
                } else if (tahun == "") {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Oops...',
                        text: 'Tahun harus diisi!',
                        showConfirmButton: true,
                        didClose: () => {
                            $("#tahun").focus();
                        }
                    });
                    return false;
                }
            });
        });
    </script>
@endpush
