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
        <div class="d-flex justify-content-between align-items-center">
            <label class="form-label mb-0">Tujuan Departemen</label>
            <div class="form-check">
                <input class="form-check-input" type="checkbox" id="checkAllDept">
                <label class="form-check-label">Pilih Semua</label>
            </div>
        </div>

        <div class="row mt-1">
            @foreach ($deptList as $d)
                <div class="col-lg-2 col-md-3 col-sm-6">
                    <div class="form-check">
                        <input class="form-check-input tujuan-dept" type="checkbox" name="tujuan[]"
                            value="{{ $d->kode_dept }}" {{ in_array($d->kode_dept, $selectedDept) ? 'checked' : '' }}>
                        <label class="form-check-label">{{ $d->nama_dept }}</label>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    {{-- ================= CABANG ================= --}}
    <div class="form-group mt-2">
        <div class="d-flex justify-content-between align-items-center">
            <label class="form-label mb-0">Tujuan Cabang</label>
            <div class="form-check">
                <input class="form-check-input" type="checkbox" id="checkAllCabang">
                <label class="form-check-label">Pilih Semua</label>
            </div>
        </div>

        <div class="row mt-1">
            @foreach ($cabangList as $c)
                <div class="col-lg-2 col-md-3 col-sm-6">
                    <div class="form-check">
                        <input class="form-check-input tujuan-cabang" type="checkbox" name="tujuan_cabang[]"
                            value="{{ $c->kode_cabang }}"
                            {{ in_array($c->kode_cabang, $selectedCabang) ? 'checked' : '' }}>
                        <label class="form-check-label">{{ $c->nama_cabang }}</label>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    {{-- ================= JABATAN ================= --}}
    <div class="form-group mt-2">
        <div class="d-flex justify-content-between align-items-center">
            <label class="form-label mb-0">Tujuan Jabatan</label>
            <div class="form-check">
                <input class="form-check-input" type="checkbox" id="checkAllJabatan">
                <label class="form-check-label">Pilih Semua</label>
            </div>
        </div>

        <div class="row mt-1">
            @foreach ($jabatanList as $j)
                <div class="col-lg-2 col-md-3 col-sm-6">
                    <div class="form-check">
                        <input class="form-check-input tujuan-jabatan" type="checkbox" name="tujuan_jabatan[]"
                            value="{{ $j->kode_jabatan }}"
                            {{ in_array($j->kode_jabatan, $selectedJabatan) ? 'checked' : '' }}>
                        <label class="form-check-label">{{ $j->alias }}</label>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
    @php
        $departemen = [
            'ADT' => 'AUDIT',
            'AKT' => 'AKUNTING',
            'GAF' => 'GENERAL AFFAIR',
            'GDG' => 'GUDANG',
            'HRD' => 'HRD',
            'KEU' => 'KEUANGAN',
            'MKT' => 'MARKETING',
            'MTC' => 'MAINTENANCE',
            'PDQ' => 'PDQC',
            'PMB' => 'PEMBELIAN',
            'PRD' => 'PRODUKSI',
        ];
    @endphp

    <div class="form-group mt-2">
        <label class="form-check-label">Departemen yang buat</label>
        <select name="kode_dept" class="form-select" required>
            <option value="">- Pilih Departemen -</option>
            @foreach ($departemen as $kode => $nama)
                <option value="{{ $kode }}" {{ $memo->kode_dept == $kode ? 'selected' : '' }}>
                    {{ $nama }}
                </option>
            @endforeach
        </select>
    </div>
    {{-- FILE --}}
    <div class="form-group mt-2">
        <label class="form-label">File Internal Memo (PDF)</label>
        <input type="file" name="file_im" class="form-control" accept="application/pdf">
        @if ($memo->file_im)
            <small class="text-muted">
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
            <ion-icon name="save-outline" class="me-1"></ion-icon>
            Update Internal Memo
        </button>
    </div>
</form>
<script>
    $(function() {

        const formIM = $("#formEditIM");

        // Flatpickr
        $(".flatpickr-date").flatpickr({
            dateFormat: "Y-m-d"
        });

        // ================= CHECK ALL =================
        function syncCheckAll(selector, checkAllId) {
            $(checkAllId).prop(
                'checked',
                $(selector + ':checked').length === $(selector).length
            );
        }

        $('#checkAllDept').on('change', function() {
            $('.tujuan-dept').prop('checked', this.checked);
        });
        $('.tujuan-dept').on('change', function() {
            syncCheckAll('.tujuan-dept', '#checkAllDept');
        });

        $('#checkAllCabang').on('change', function() {
            $('.tujuan-cabang').prop('checked', this.checked);
        });
        $('.tujuan-cabang').on('change', function() {
            syncCheckAll('.tujuan-cabang', '#checkAllCabang');
        });

        $('#checkAllJabatan').on('change', function() {
            $('.tujuan-jabatan').prop('checked', this.checked);
        });
        $('.tujuan-jabatan').on('change', function() {
            syncCheckAll('.tujuan-jabatan', '#checkAllJabatan');
        });

        // Initial sync
        syncCheckAll('.tujuan-dept', '#checkAllDept');
        syncCheckAll('.tujuan-cabang', '#checkAllCabang');
        syncCheckAll('.tujuan-jabatan', '#checkAllJabatan');

        // ================= VALIDASI =================
        formIM.on('submit', function() {

            if ($("input[name=no_im]").val() === "") {
                Swal.fire("Oops!", "No Internal Memo wajib diisi", "warning");
                return false;
            }

            if ($("input[name=judul]").val() === "") {
                Swal.fire("Oops!", "Judul memo wajib diisi", "warning");
                return false;
            }

            if (
                $('.tujuan-dept:checked').length === 0 &&
                $('.tujuan-cabang:checked').length === 0 &&
                $('.tujuan-jabatan:checked').length === 0
            ) {
                Swal.fire("Oops!", "Minimal satu tujuan harus dipilih", "warning");
                return false;
            }

            return true;
        });

    });
</script>
