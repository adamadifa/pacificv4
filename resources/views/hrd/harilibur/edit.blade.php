<form action="{{ route('harilibur.update', Crypt::encrypt($harilibur->kode_libur)) }}" method="POST" id="formHariLibur">
    @csrf
    @method('PUT')
    <x-input-with-icon icon="ti ti-calendar" label="Tanggal" name="tanggal" datepicker="flatpickr-date" value="{{ $harilibur->tanggal }}" />
    @if (in_array($level_user, ['super admin', 'asst. manager hrd', 'spv presensi']))
        <x-select label="Cabang" name="kode_cabang" :data="$cabang" key="kode_cabang" textShow="nama_cabang" select2="select2Kodecabang"
            upperCase="true" :selected="$harilibur->kode_cabang" />
        <div class="row" id="departemen">
            <diiv class="col">
                <x-select label="Departemen" name="kode_dept" :data="$departemen" key="kode_dept" textShow="nama_dept" select2="select2KodeDept"
                    upperCase="true" :selected="$harilibur->kode_dept" />
            </diiv>
        </div>
    @endif

    <x-select label="Kategori Libur" name="kategori" :data="$kategorilibur" key="kode_kategori" upperCase="true" textShow="nama_kategori"
        :selected="$harilibur->kategori" />
    <x-input-with-icon label="Tanggal Yang diganti" name="tanggal_diganti" datepicker="flatpickr-date" icon="ti ti-calendar" :value="$harilibur->tanggal_diganti" />
    <div class="row mt-2">
        <div class="col">
            <div class="alert alert-info">
                <i class="ti ti-info me-1"></i> Kosongkan Jika Tidak Ada Tanggal Libur Yang diganti
            </div>
        </div>
    </div>
    <div class="form-check form-check-inline mt-3">
        <input class="form-check-input" type="checkbox" id="inlineCheckbox1" value="1" name="limajam"
            {{ !empty($harilibur->tanggal_limajam) ? 'checked' : '' }}>
        <label class="form-check-label" for="inlineCheckbox1">5 Jam Kerja UntuK Tanggal Sebelumnya</label>
    </div>
    <div class="row mt-2">
        <div class="col">
            <div class="alert alert-info">
                Checklist Jika Tanggal Sebelum Libur diberlakukan 5 Jam Kerja
            </div>
        </div>
    </div>
    <x-textarea label="Keterangan" name="keterangan" :value="$harilibur->keterangan" />
    <div class="form-group mb-3">
        <button class="btn btn-primary w-100" id="btnSimpan"><i class="ti ti-send me-1"></i>Submit</button>
    </div>
</form>

<script>
    $(document).ready(function() {
        const form = $('#formHariLibur');
        $(".flatpickr-date").flatpickr();
        const select2Kodecabang = $(".select2Kodecabang");

        if (select2Kodecabang.length) {
            select2Kodecabang.each(function() {
                var $this = $(this);
                $this.wrap('<div class="position-relative"></div>').select2({
                    placeholder: 'Pilih Cabang',
                    dropdownParent: $this.parent()
                });
            });
        }

        const select2KodeDept = $('.select2KodeDept');
        if (select2KodeDept.length) {
            select2KodeDept.each(function() {
                var $this = $(this);
                $this.wrap('<div class="position-relative"></div>').select2({
                    placeholder: 'Pilih Departemen',
                    dropdownParent: $this.parent()
                });
            });
        }

        function showdepartemen() {
            const kode_cabang = form.find("#kode_cabang").val();
            if (kode_cabang == "PST") {
                $("#departemen").show();
            } else {
                $("#departemen").hide();
            }
        }

        showdepartemen();

        form.find("#kode_cabang").on("change", function() {
            showdepartemen();
        })

        function buttonDisable() {
            $("#btnSimpan").prop('disabled', true);
            $("#btnSimpan").html(`
            <div class="spinner-border spinner-border-sm text-white me-2" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
            Loading..`);
        }
        form.submit(function(e) {
            //e.preventDefault();
            const tanggal = form.find("#tanggal").val();
            const kode_cabang = form.find("#kode_cabang").val();
            const kode_dept = form.find("#kode_dept").val();
            const kategori = form.find("#kategori").val();
            const tanggal_diganti = form.find("#tanggal_diganti").val();
            const keterangan = form.find("#keterangan").val();

            if (tanggal == "") {
                Swal.fire({
                    title: "Oops!",
                    text: "Tanggal Harus Diisi !",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: (e) => {
                        form.find("#tanggal").focus();
                    },
                });
                return false;
            } else if (kode_cabang == "") {
                Swal.fire({
                    title: "Oops!",
                    text: "Cabang Harus Diisi !",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: (e) => {
                        form.find("#kode_cabang").focus();
                    },
                });
                return false;
            } else if (kode_cabang == "PST" && kode_dept == "") {
                Swal.fire({
                    title: "Oops!",
                    text: "Departemen Harus Diisi !",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: (e) => {
                        form.find("#kode_dept").focus();
                    },
                });
                return false;
            } else if (kategori == "") {
                Swal.fire({
                    title: "Oops!",
                    text: "Kategori Harus Diisi !",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: (e) => {
                        form.find("#kategori").focus();
                    },
                });
                return false;
            } else if (keterangan == "") {
                Swal.fire({
                    title: "Oops!",
                    text: "Keterangan Harus Diisi !",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: (e) => {
                        form.find("#keterangan").focus();
                    },
                });
                return false;
            } else {
                buttonDisable();
            }
        });

    });
</script>
