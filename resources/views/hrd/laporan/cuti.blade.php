<form action="{{ route('laporanhrd.cetakcuti') }}" method="POST" target="_blank" id="formGaji">
    @csrf
    @hasanyrole($roles_access_all_karyawan)
        <div class="form-group mb-3">
            <select name="kode_cabang" id="kode_cabang_cuti" class="form-select select2KodecabangCuti">
                <option value="">Semua Cabang</option>
                @foreach ($cabang as $d)
                    <option value="{{ $d->kode_cabang }}">{{ textUpperCase($d->nama_cabang) }}</option>
                @endforeach
            </select>
        </div>
    @endrole
    <div class="form-group mb-3">
        <select name="kode_dept" id="kode_dept_cuti" class="form-select select2KodedeptGaji">
            <option value="">Semua Departemen</option>
        </select>
    </div>
    <div class="form-group mb-3">
        <select name="kode_group" id="kode_group_cuti" class="form-select select2KodegroupGaji">
            <option value="">Semua Group</option>
        </select>
    </div>




    <div class="row">
        <div class="co">
            <div class="form-group mb-3">
                <select name="tahun" id="tahun" class="form-select">
                    <option value="">Tahun</option>
                    @for ($t = $start_year; $t <= date('Y'); $t++)
                        <option value="{{ $t }}" {{ date('Y') == $t ? 'selected' : '' }}>{{ $t }}</option>
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
            const select2KodecabangCuti = $(".select2KodecabangCuti");
            if (select2KodecabangCuti.length) {
                select2KodecabangCuti.each(function() {
                    var $this = $(this);
                    $this.wrap('<div class="position-relative"></div>').select2({
                        placeholder: 'Semua Cabang',
                        allowClear: true,
                        dropdownParent: $this.parent()
                    });
                });
            }

            function getDepartemen() {
                const kode_cabang = $("#kode_cabang_cuti").val();
                $.ajax({
                    type: 'POST',
                    url: '{{ route('laporanhrd.getdepartemen') }}',
                    data: {
                        _token: '{{ csrf_token() }}',
                        kode_cabang: kode_cabang
                    },
                    cache: false,
                    success: function(res) {
                        $("#kode_dept_cuti").html(res);
                    }
                });
            }

            function getGroup() {
                const kode_cabang = $("#kode_cabang_cuti").val();
                $.ajax({
                    type: 'POST',
                    url: '{{ route('laporanhrd.getgroup') }}',
                    data: {
                        _token: '{{ csrf_token() }}',
                        kode_cabang: kode_cabang
                    },
                    cache: false,
                    success: function(res) {
                        $("#kode_group_cuti").html(res);
                    }
                });
            }

            getDepartemen();
            getGroup();

            $("#kode_cabang_cuti").change(function(e) {
                e.preventDefault();
                getDepartemen();
                getGroup();
            });

            $("#formGaji").submit(function(e) {

                const bulan = $(this).find("#bulan").val();
                const tahun = $(this).find("#tahun").val();

                if (bulan == "") {
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
