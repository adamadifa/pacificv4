<form action="{{ route('internalmemo.store') }}" method="POST" id="formCreateIM" enctype="multipart/form-data">
    @csrf

    <x-input-with-icon icon="ti ti-file-text" label="No Internal Memo" name="no_im" required />

    <x-input-with-icon icon="ti ti-pencil" label="Judul Memo" name="judul" required />

    <div class="row">
        <div class="col-lg-4">
            <x-input-with-icon icon="ti ti-calendar" label="Tanggal Memo" name="tanggal_im" value="{{ date('Y-m-d') }}"
                datepicker="flatpickr-date" required />
        </div>
        <div class="col-lg-4">
            <x-input-with-icon icon="ti ti-calendar-time" label="Berlaku Dari" name="berlaku_dari"
                datepicker="flatpickr-date" required />
        </div>
        <div class="col-lg-4">
            <x-input-with-icon icon="ti ti-calendar-off" label="Berlaku Sampai" name="berlaku_sampai"
                datepicker="flatpickr-date" />
        </div>
    </div>

    <div class="form-group mt-2">
        <div class="d-flex justify-content-between align-items-center">
            <label class="form-label mb-0">Tujuan Departemen</label>
            <div class="form-check">
                <input class="form-check-input check-all-dept" type="checkbox" id="checkAllDept">
                <label class="form-check-label" for="checkAllDept">
                    Pilih Semua
                </label>
            </div>
        </div>

        <div class="row mt-1">
            @foreach ($deptList as $d)
                <div class="col-lg-2 col-md-3 col-sm-6">
                    <div class="form-check">
                        <input class="form-check-input tujuan-dept" type="checkbox" name="tujuan[]"
                            value="{{ $d->kode_dept }}" id="dept_{{ $d->kode_dept }}">
                        <label class="form-check-label" for="dept_{{ $d->kode_dept }}">
                            {{ $d->nama_dept }}
                        </label>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <div class="form-group mt-2">
        <div class="d-flex justify-content-between align-items-center">
            <label class="form-label mb-0">Tujuan Cabang</label>
            <div class="form-check">
                <input class="form-check-input check-all-cabang" type="checkbox" id="checkAllCabang">
                <label class="form-check-label" for="checkAllCabang">
                    Pilih Semua
                </label>
            </div>
        </div>

        <div class="row mt-1">
            @foreach ($cabangList as $c)
                <div class="col-lg-2 col-md-3 col-sm-6">
                    <div class="form-check">
                        <input class="form-check-input tujuan-cabang" type="checkbox" name="tujuan_cabang[]"
                            value="{{ $c->kode_cabang }}" id="cabang_{{ $c->kode_cabang }}">
                        <label class="form-check-label" for="cabang_{{ $c->kode_cabang }}">
                            {{ $c->nama_cabang }}
                        </label>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <div class="form-group mt-2">
        <div class="d-flex justify-content-between align-items-center">
            <label class="form-label mb-0">Tujuan Jabatan</label>
            <div class="form-check">
                <input class="form-check-input check-all-jabatan" type="checkbox" id="checkAllJabatan">
                <label class="form-check-label" for="checkAllJabatan">
                    Pilih Semua
                </label>
            </div>
        </div>

        <div class="row mt-1">
            @foreach ($jabatanList as $r)
                <div class="col-lg-2 col-md-3 col-sm-6">
                    <div class="form-check">
                        <input class="form-check-input tujuan-jabatan" type="checkbox" name="tujuan_jabatan[]"
                            value="{{ $r->kode_jabatan }}" id="jabatan_{{ $r->kode_jabatan }}">
                        <label class="form-check-label" for="jabatan_{{ $r->kode_jabatan }}">
                            {{ $r->alias }}
                        </label>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <div class="form-group mt-2">
        <label class="form-label">File Internal Memo (PDF)</label>
        <input type="file" name="file_im" class="form-control" accept="application/pdf">
    </div>

    <x-input-with-icon icon="ti ti-notes" label="Keterangan" name="keterangan" textarea="true" />

    <div class="form-check mt-3 mb-3">
        <input class="form-check-input agreement" type="checkbox" id="agreeIM">
        <label class="form-check-label" for="agreeIM">
            Yakin akan disimpan?
        </label>
    </div>

    <div class="form-group" id="saveButton">
        <button class="btn btn-primary w-100" type="submit">
            <ion-icon name="send-outline" class="me-1"></ion-icon>
            Simpan Internal Memo
        </button>
    </div>
</form>
<script>
    $(function() {

        const formIM = $("#formCreateIM");

        // Date Picker
        $(".flatpickr-date").flatpickr({
            dateFormat: "Y-m-d"
        });

        // Select2 Tujuan
        $('.select2Tujuan').select2({
            placeholder: 'Pilih Tujuan',
            allowClear: true,
            width: '100%'
        });

        // Hide tombol simpan awal
        formIM.find("#saveButton").hide();

        // Agreement checkbox
        formIM.find('.agreement').change(function() {
            if (this.checked) {
                formIM.find("#saveButton").show();
            } else {
                formIM.find("#saveButton").hide();
            }
        });

        // Validasi submit
        formIM.submit(function() {
            if ($("input[name=no_im]").val() === "") {
                Swal.fire("Oops!", "No Internal Memo wajib diisi", "warning");
                return false;
            }

            if ($("input[name=judul]").val() === "") {
                Swal.fire("Oops!", "Judul memo wajib diisi", "warning");
                return false;
            }

            if ($("select[name='tujuan[]']").val().length === 0) {
                Swal.fire("Oops!", "Tujuan memo wajib dipilih", "warning");
                return false;
            }
        });

        $('#checkAllDept').on('change', function() {
            $('.tujuan-dept').prop('checked', this.checked);
        });

        $('.tujuan-dept').on('change', function() {
            $('#checkAllDept').prop(
                'checked',
                $('.tujuan-dept:checked').length === $('.tujuan-dept').length
            );
        });

        // === CABANG ===
        $('#checkAllCabang').on('change', function() {
            $('.tujuan-cabang').prop('checked', this.checked);
        });

        $('.tujuan-cabang').on('change', function() {
            $('#checkAllCabang').prop(
                'checked',
                $('.tujuan-cabang:checked').length === $('.tujuan-cabang').length
            );
        });

        $('#checkAllJabatan').on('change', function() {
            $('.tujuan-jabatan').prop('checked', this.checked);
        });

        // SINKRON STATUS CHECK ALL
        $('.tujuan-jabatan').on('change', function() {
            $('#checkAllJabatan').prop(
                'checked',
                $('.tujuan-jabatan:checked').length === $('.tujuan-jabatan').length
            );
        });

        if ($('.tujuan-jabatan').length === $('.tujuan-jabatan:checked').length) {
            $('#checkAllJabatan').prop('checked', true);
        }

        if ($('.tujuan-dept').length === $('.tujuan-dept:checked').length) {
            $('#checkAllDept').prop('checked', true);
        }

        if ($('.tujuan-cabang').length === $('.tujuan-cabang:checked').length) {
            $('#checkAllCabang').prop('checked', true);
        }

    });
</script>
