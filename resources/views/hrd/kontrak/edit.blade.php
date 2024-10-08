<form action="{{ route('kontrakkerja.update', Crypt::encrypt($kontrak->no_kontrak)) }}" method="POST" id="formKontrak">
    @csrf
    {{-- {{ $kontrak->kode_jabatan }} --}}
    @method('PUT')
    <x-input-with-icon icon="ti ti-barcode" label="Auto" disabled="true" name="no_kontrak" value="{{ $kontrak->no_kontrak }}" disabled="true" />
    <x-input-with-icon icon="ti ti-user" label="Karyawan" disabled="true" name="nik" value="{{ $kontrak->nik }}" disabled="true" />
    <div class="form-group mb-3">
        <select name="kode_perusahaan" id="kode_perusahaan" class="form-select">
            <option value="">Perusahaan</option>
            <option value="MP" {{ $kontrak->kode_perusahaan == 'MP' ? 'selected' : '' }}>Makmur Permata</option>
            <option value="PC" {{ $kontrak->kode_perusahaan == 'PC' ? 'selected' : '' }}>Pacific</option>
        </select>
    </div>
    <x-select label="Kantor" name="kode_cabang" :data="$cabang" key="kode_cabang" textShow="nama_cabang" upperCase="true"
        select2="select2Kodecabang" selected="{{ $kontrak->kode_cabang }}" />
    <x-select label="Departemen" name="kode_dept" :data="$departemen" key="kode_dept" textShow="nama_dept" select2="select2KodeDept" upperCase="true"
        :selected="$kontrak->kode_dept" />
    <x-select label="Jabatan" name="kode_jabatan" :data="$jabatan" key="kode_jabatan" textShow="nama_jabatan" select2="select2KodeJabatan"
        upperCase="true" :selected="$kontrak->kode_jabatan" />
    <div class="row">
        <div class="col-lg-6 col-md-12 col-sm-12">
            <x-input-with-icon label="Tanggal Mulai" name="dari" datepicker="flatpickr-date" icon="ti ti-calendar" :value="$kontrak->dari" />
        </div>
        <div class="col-lg-6 col-md-12 col-sm-12">
            <x-input-with-icon label="Tanggal Selesai" name="sampai" datepicker="flatpickr-date" icon="ti ti-calendar" :value="$kontrak->sampai" />
        </div>
    </div>
    <div class="divider">
        <div class="divider-text">Data Gaji</div>
    </div>

    <x-input-inline-label icon="ti ti-moneybag" label="Gaji Pokok" name="gaji_pokok" value="{{ $gaji != null ? formatRupiah($gaji->gaji_pokok) : 0 }}"
        align="right" money="true" />
    <x-input-inline-label icon="ti ti-moneybag" label="Tunj. Jabatan" name="t_jabatan"
        value="{{ $gaji != null ? formatRupiah($gaji->t_jabatan) : 0 }}" align="right" money="true" />
    <x-input-inline-label icon="ti ti-moneybag" label="Tunj. Masa Kerja" name="t_masakerja"
        value="{{ $gaji != null ? formatRupiah($gaji->t_masakerja) : 0 }}" align="right" money="true" />
    <x-input-inline-label icon="ti ti-moneybag" label="Tunj. Tangg. Jawab" name="t_tanggungjawab"
        value="{{ $gaji != null ? formatRupiah($gaji->t_tanggungjawab) : 0 }}" align="right" money="true" />
    <x-input-inline-label icon="ti ti-moneybag" label="Tunj. Makan" name="t_makan" value="{{ $gaji != null ? formatRupiah($gaji->t_makan) : 0 }}"
        align="right" money="true" />
    <x-input-inline-label icon="ti ti-moneybag" label="Tunj. Istri" name="t_istri" value="{{ $gaji != null ? formatRupiah($gaji->t_istri) : 0 }}"
        align="right" money="true" />
    <x-input-inline-label icon="ti ti-moneybag" label="Tunj. Skill" name="t_skill" value="{{ $gaji != null ? formatRupiah($gaji->t_skill) : 0 }}"
        align="right" money="true" />

    <div class="row">
        <div class="form-group mb-3">
            <button class="btn btn-primary w-100" id="btnSimpan" type="submit">
                <i class="ti ti-send me-1"></i>Submit
            </button>
        </div>
    </div>
</form>

<script>
    $(function() {
        const form = $('#formKontrak');
        $(".money").maskMoney();
        $(".flatpickr-date").flatpickr();

        function getKaryawan(nik) {
            $.ajax({
                url: `/karyawan/${nik}/getkaryawan`,
                type: "GET",
                cache: false,
                success: function(response) {
                    form.find("#kode_cabang").val(response.data.kode_cabang).trigger("change");
                    form.find("#kode_dept").val(response.data.kode_dept).trigger("change");
                    form.find("#kode_jabatan").val(response.data.kode_jabatan).trigger("change");
                    form.find("#kode_perusahaan").val(response.data.kode_perusahaan);
                }
            });
        }
        const select2Nik = $('.select2Nik');
        if (select2Nik.length) {
            select2Nik.each(function() {
                var $this = $(this);
                $this.wrap('<div class="position-relative"></div>').select2({
                    placeholder: 'Pilih Karyawan',
                    allowClear: true,
                    dropdownParent: $this.parent()
                });
            });
        }

        const select2Kodecabang = $('.select2Kodecabang');
        if (select2Kodecabang.length) {
            select2Kodecabang.each(function() {
                var $this = $(this);
                $this.wrap('<div class="position-relative"></div>').select2({
                    placeholder: 'Kantor',
                    allowClear: true,
                    dropdownParent: $this.parent()
                });
            });
        }

        const select2KodeDept = $('.select2KodeDept');
        if (select2KodeDept.length) {
            select2KodeDept.each(function() {
                var $this = $(this);
                $this.wrap('<div class="position-relative"></div>').select2({
                    placeholder: 'Departemen',
                    allowClear: true,
                    dropdownParent: $this.parent()
                });
            });
        }

        const select2KodeJabatan = $('.select2KodeJabatan');
        if (select2KodeJabatan.length) {
            select2KodeJabatan.each(function() {
                var $this = $(this);
                $this.wrap('<div class="position-relative"></div>').select2({
                    placeholder: 'Jabatan',
                    allowClear: true,
                    dropdownParent: $this.parent()
                });
            });
        }


        function buttonDisabled() {
            $("#btnSimpan").prop('disabled', true);
            $("#btnSimpan").html(`
            <div class="spinner-border spinner-border-sm text-white me-2" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
            Loading..`);
        }
        $("#nik").change(function() {
            getKaryawan($(this).val());
        });

        form.submit(function() {
            const nik = form.find("#nik").val();
            const kode_perusahaan = form.find("#kode_perusahaan").val();
            const kode_cabang = form.find("#kode_cabang").val();
            const kode_dept = form.find("#kode_dept").val();
            const kode_jabatan = form.find("#kode_jabatan").val();
            const dari = form.find("#dari").val();
            const sampai = form.find("#sampai").val();
            if (nik == "") {
                Swal.fire({
                    title: "Oops!",
                    text: "Pilih Karyawan Terlebih Dahulu",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: () => {
                        form.find("#nik").focus();
                    }
                });
                return false;
            } else if (kode_perusahaan == "") {
                Swal.fire({
                    title: "Oops!",
                    text: "Pilih Perusahaan Terlebih Dahulu",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: () => {
                        form.find("#kode_perusahaan").focus();
                    }
                });
                return false;
            } else if (kode_cabang == "") {
                Swal.fire({
                    title: "Oops!",
                    text: "Pilih Kantor Terlebih Dahulu",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: () => {
                        form.find("#kode_cabang").focus();
                    }
                });
                return false;
            } else if (kode_dept == "") {
                Swal.fire({
                    title: "Oops!",
                    text: "Pilih Departemen Terlebih Dahulu",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: () => {
                        form.find("#kode_dept").focus();
                    }
                });
                return false;
            } else if (kode_jabatan == "") {
                Swal.fire({
                    title: "Oops!",
                    text: "Pilih Jabatan Terlebih Dahulu",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: () => {
                        form.find("#kode_jabatan").focus();
                    }
                });
                return false;
            } else if (dari == "" || sampai == "") {
                Swal.fire({
                    title: "Oops!",
                    text: 'Periode Kontrak Harus Diisi !',
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: () => {
                        form.find("#dari").focus();
                    }
                });
                return false;
            } else {
                buttonDisabled();
            }
        })
    });
</script>
