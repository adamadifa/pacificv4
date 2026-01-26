<form action="{{ route('internalmemo.update', $memo->id) }}" method="POST" id="formEditIM" enctype="multipart/form-data">
    @csrf
    @method('PUT')

    <x-input-with-icon icon="ti ti-file-text" label="No Internal Memo" name="no_im" value="{{ $memo->no_im }}"
        required />

    <x-input-with-icon icon="ti ti-pencil" label="Judul Memo" name="judul" value="{{ $memo->judul }}" required />

    <div class="row">
        <div class="col-lg-4">
            <x-input-with-icon icon="ti ti-calendar" label="Tanggal Memo" name="tanggal_im"
                value="{{ $memo->tanggal_im }}" datepicker="flatpickr-date" required />
        </div>
        <div class="col-lg-4">
            <x-input-with-icon icon="ti ti-calendar-time" label="Berlaku Dari" name="berlaku_dari"
                value="{{ $memo->berlaku_dari }}" datepicker="flatpickr-date" required />
        </div>
        <div class="col-lg-4">
            <x-input-with-icon icon="ti ti-calendar-off" label="Berlaku Sampai" name="berlaku_sampai"
                value="{{ $memo->berlaku_sampai }}" datepicker="flatpickr-date" />
        </div>
    </div>

    {{-- ================= DEPARTEMEN ================= --}}
    <div class="form-group mt-2">
        <label class="form-label">Tujuan Departemen</label>
        <div class="row mt-1">
            @foreach ($deptList as $d)
                <div class="col-lg-3 col-md-4 col-sm-6">
                    <div class="form-check">
                        <input class="form-check-input tujuan-dept" type="checkbox" name="tujuan[]"
                            value="{{ $d->kode_dept }}" {{ in_array($d->kode_dept, $selectedDept) ? 'checked' : '' }}>
                        <label class="form-check-label">
                            {{ $d->nama_dept }}
                        </label>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    {{-- ================= CABANG ================= --}}
    <div class="form-group mt-2">
        <label class="form-label">Tujuan Cabang</label>
        <div class="row mt-1">
            @foreach ($cabangList as $c)
                <div class="col-lg-3 col-md-4 col-sm-6">
                    <div class="form-check">
                        <input class="form-check-input tujuan-cabang" type="checkbox" name="tujuan_cabang[]"
                            value="{{ $c->kode_cabang }}"
                            {{ in_array($c->kode_cabang, $selectedCabang) ? 'checked' : '' }}>
                        <label class="form-check-label">
                            {{ $c->nama_cabang }}
                        </label>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    {{-- ================= JABATAN ================= --}}
    <div class="form-group mt-2">
        <label class="form-label">Tujuan Jabatan</label>
        <div class="row mt-1">
            @foreach ($jabatanList as $j)
                <div class="col-lg-3 col-md-4 col-sm-6">
                    <div class="form-check">
                        <input class="form-check-input tujuan-jabatan" type="checkbox" name="tujuan_jabatan[]"
                            value="{{ $j->kode_jabatan }}"
                            {{ in_array($j->kode_jabatan, $selectedJabatan) ? 'checked' : '' }}>
                        <label class="form-check-label">
                            {{ $j->alias }}
                        </label>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    {{-- FILE --}}
    <div class="form-group mt-2">
        <label class="form-label">File Internal Memo (PDF)</label>
        <input type="file" name="file_im" class="form-control">
        @if ($memo->file_im)
            <small>
                File lama:
                <a href="{{ asset('storage/internal_memo/' . $memo->file_im) }}" target="_blank">
                    Lihat PDF
                </a>
            </small>
        @endif
    </div>

    <x-input-with-icon icon="ti ti-notes" label="Keterangan" name="keterangan" textarea="true"
        value="{{ $memo->keterangan }}" />

    <div class="form-group mt-3">
        <button class="btn btn-warning w-100" type="submit">
            <ion-icon name="save-outline"></ion-icon>
            Update Internal Memo
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
