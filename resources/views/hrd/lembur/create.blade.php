<form action="{{ route('lembur.store') }}" method="POST" id="formLembur">
    @csrf
    <x-input-with-icon icon="ti ti-barcode" label="Auto" disabled="true" name="no_lembur" />
    <x-input-with-icon icon="ti ti-calendar" label="Tanggal" name="tanggal" datepicker="flatpickr-date" />
    <div class="row">
        <div class="col-lg-8 col-sm-12 col-md-12">
            <x-input-with-icon icon="ti ti-calendar" label="Dari" name="dari" datepicker="flatpickr-date" />
        </div>
        <div class="col-lg-4 col-sm-12 col-md-12">
            <x-input-with-icon icon="ti ti-clock" label="Jam Mulai" name="jam_mulai" />
        </div>
    </div>
    <div class="row">
        <div class="col-lg-8 col-sm-12 col-md-12">
            <x-input-with-icon icon="ti ti-calendar" label="Sampai" name="sampai" datepicker="flatpickr-date" />
        </div>
        <div class="col-lg-4 col-sm-12 col-md-12">
            <x-input-with-icon icon="ti ti-clock" label="Jam Selesai" name="jam_selesai" />
        </div>
    </div>
    <div class="form-group mb-3">
        <select name="kategori" id="kategori" class="form-select">
            <option value="">Kategori Lembur</option>
            <option value="1">Lembur Reguler</option>
            <option value="2">Lembur Hari Libur</option>
        </select>
    </div>
    @if (in_array($level_user, ['super admin', 'asst. manager hrd', 'spv presensi']))
        <x-select label="Cabang" name="kode_cabang" :data="$cabang" key="kode_cabang" textShow="nama_cabang" upperCase="true"
            select2="select2KodeCabang" />
        <x-select label="Departemen" name="kode_dept" :data="$departemen" key="kode_dept" textShow="nama_dept" upperCase="true"
            select2="select2KodeDept" />
    @endif

    <div class="form-group mb-3">
        <select name="istirahat" id="istirahat" class="form-select">
            <option value="">Istirahat / Tidak Istirahat</option>
            <option value="1">Istirahat</option>
            <option value="2">Tidak Istirahat</option>
        </select>
    </div>
    <x-textarea label="Keterangan" name="keterangan" />
    <div class="form-group mb-3">
        <button class="btn btn-primary w-100" id="btnSimpan"><i class="ti ti-thumb-up me-1"></i>Submit</button>
    </div>
</form>
<script>
    $(document).ready(function() {
        const form = $("#formLembur");
        $(".flatpickr-date").flatpickr();
        const select2KodeDept = $(".select2KodeDept");
        if (select2KodeDept.length > 0) {
            select2KodeDept.each(function() {
                var $this = $(this);
                $this.wrap('<div class="position-relative"></div>').select2({
                    placeholder: 'Pilih Departemen',
                    dropdownParent: $this.parent()
                });
            });
        }

        const select2KodeCabang = $(".select2KodeCabang");
        if (select2KodeCabang.length > 0) {
            select2KodeCabang.each(function() {
                var $this = $(this);
                $this.wrap('<div class="position-relative"></div>').select2({
                    placeholder: 'Pilih Cabang',
                    dropdownParent: $this.parent()
                });
            });
        }


        $("#jam_mulai,#jam_selesai").mask("99:99");

        function buttonDisabled() {
            $("#btnSimpan").prop('disabled', true);
            $("#btnSimpan").html(`
            <div class="spinner-border spinner-border-sm text-white me-2" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
            Loading..`);
        }
        form.submit(function(e) {
            const tanggal = form.find("#tanggal").val();
            const dari = form.find("#dari").val();
            const jam_mulai = form.find("#jam_mulai").val();
            const sampai = form.find("#sampai").val();
            const jam_selesai = form.find("#jam_selesai").val();
            const kode_dept = form.find("#kode_dept").val();
            const istirahat = form.find("#istirahat").val();
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
            } else if (dari == "") {
                Swal.fire({
                    title: "Oops!",
                    text: "Tanggal Mulai Harus Diisi !",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: (e) => {
                        form.find("#dari").focus();
                    },
                });
                return false;
            } else if (jam_mulai == "") {
                Swal.fire({
                    title: "Oops!",
                    text: "Jam Mulai Harus Diisi !",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: (e) => {
                        form.find("#jam_mulai").focus();
                    },
                });
                return false;
            } else if (sampai == "") {
                Swal.fire({
                    title: "Oops!",
                    text: "Tanggal Selesai Harus Diisi !",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: (e) => {
                        form.find("#sampai").focus();
                    },
                });
                return false;
            } else if (jam_selesai == "") {
                Swal.fire({
                    title: "Oops!",
                    text: "Jam Selesai Harus Diisi !",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: (e) => {
                        form.find("#jam_selesai").focus();
                    },
                });
                return false;
            } else if (kode_dept == "") {
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
            } else if (istirahat == "") {
                Swal.fire({
                    title: "Oops!",
                    text: "Istirahat / Tidak Istirahat Harus Diisi !",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: (e) => {
                        form.find("#istirahat").focus();
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
                buttonDisabled();
            }
        })
    });
</script>
