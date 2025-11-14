<form action="{{ route('laporanhrd.cetakketerlambatan') }}" method="POST" target="_blank" id="formKeterlambatan">
    @csrf
    @hasanyrole($roles_access_all_karyawan)
        <div class="form-group mb-3">
            <select name="kode_cabang" id="kode_cabang_keterlambatan" class="form-select select2Kodecabangketerlambatan">
                <option value="">Semua Cabang</option>
                @foreach ($cabang as $d)
                    <option value="{{ $d->kode_cabang }}">{{ textUpperCase($d->nama_cabang) }}</option>
                @endforeach
            </select>
        </div>
    @endrole
    <div class="form-group mb-3">
        <select name="kode_dept" id="kode_dept_keterlambatan" class="form-select select2Kodedeptketerlambatan">
            <option value="">Semua Departemen</option>
        </select>
    </div>
    <div class="form-group mb-3">
        <select name="kode_group" id="kode_group_keterlambatan" class="form-select select2Kodegroupketerlambatan">
            <option value="">Semua Group</option>
        </select>
    </div>
    <div class="row">
        <div class="col-6">
            <div class="form-group mb-3">
                <x-input-with-icon icon="ti ti-calendar" datepicker="flatpickr-date" name="dari_tanggal" />
            </div>
        </div>
        <div class="col-6">
            <div class="form-group mb-3">
                <x-input-with-icon icon="ti ti-calendar" datepicker="flatpickr-date" name="sampai_tanggal" />
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-10 col-md-12 col-sm-12">
            <button type="submit" name="submitButton" class="btn btn-primary w-100" id="submitButtonKeterlambatan">
                <i class="ti ti-printer me-1"></i> Cetak
            </button>
        </div>
        <div class="col-lg-2 col-md-12 col-sm-12">
            <button type="submit" name="exportButton" class="btn btn-success w-100" id="exportButtonKeterlambatan">
                <i class="ti ti-download"></i>
            </button>
        </div>
    </div>
</form>
@push('myscript')
    <script>
        $(function() {
            const select2Kodecabangketerlambatan = $(".select2Kodecabangketerlambatan");
            if (select2Kodecabangketerlambatan.length) {
                select2Kodecabangketerlambatan.each(function() {
                    var $this = $(this);
                    $this.wrap('<div class="position-relative"></div>').select2({
                        placeholder: 'Semua Cabang',
                        allowClear: true,
                        dropdownParent: $this.parent()
                    });
                });
            }

            function getDepartemenKeterlambatan() {
                const kode_cabang = $("#kode_cabang_keterlambatan").val();
                $.ajax({
                    type: 'POST',
                    url: '{{ route('laporanhrd.getdepartemen') }}',
                    data: {
                        _token: '{{ csrf_token() }}',
                        kode_cabang: kode_cabang
                    },
                    cache: false,
                    success: function(res) {
                        $("#kode_dept_keterlambatan").html(res);
                    }
                });
            }

            function getGroupKeterlambatan() {
                const kode_cabang = $("#kode_cabang_keterlambatan").val();
                $.ajax({
                    type: 'POST',
                    url: '{{ route('laporanhrd.getgroup') }}',
                    data: {
                        _token: '{{ csrf_token() }}',
                        kode_cabang: kode_cabang
                    },
                    cache: false,
                    success: function(res) {
                        $("#kode_group_keterlambatan").html(res);
                    }
                });
            }

            getDepartemenKeterlambatan();
            getGroupKeterlambatan();

            $(".flatpickr-date").flatpickr();

            $("#kode_cabang_keterlambatan").change(function(e) {
                e.preventDefault();
                getDepartemenKeterlambatan();
                getGroupKeterlambatan();
            });

            $("#formKeterlambatan").submit(function(e) {
                const dari_tanggal = $(this).find("#dari_tanggal").val();
                const sampai_tanggal = $(this).find("#sampai_tanggal").val();

                if (dari_tanggal == "") {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Oops...',
                        text: 'Dari Tanggal harus diisi!',
                        showConfirmButton: true,
                        didClose: () => {
                            $("#dari_tanggal").focus();
                        }
                    });
                    return false;
                } else if (sampai_tanggal == "") {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Oops...',
                        text: 'Sampai Tanggal harus diisi!',
                        showConfirmButton: true,
                        didClose: () => {
                            $("#sampai_tanggal").focus();
                        }
                    });
                    return false;
                }
            });
        });
    </script>
@endpush

