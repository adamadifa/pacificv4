<form action="{{ route('suratperingatan.update', Crypt::encrypt($sp->no_sp)) }}" method="POST" id="formSuratPeringatan">
    @csrf
    @method('PUT')
    <x-input-with-icon icon="ti ti-barcode" label="Auto" disabled="true" name="no_sp" />
    <x-select label="Karyawan" name="nik" :data="$karyawan" key="nik" textShow="nama_karyawan" select2="select2Nik" showKey="true"
        selected="{{ $sp->nik }}" />
    <div class="row">
        <div class="col-lg-6 col-md-12 col-sm-12">
            <x-input-with-icon label="Dari" name="dari" icon="ti ti-calendar" datepicker="flatpickr-date" value="{{ $sp->dari }}" />
        </div>
        <div class="col-lg-6 col-md-12 col-sm-12">
            <x-input-with-icon label="Sampai" name="sampai" icon="ti ti-calendar" datepicker="flatpickr-date" value="{{ $sp->sampai }}" />
        </div>
    </div>
    <div class="form-group mb-3">
        <select name="jenis_sp" id="jenis_sp" class="form-select">
            <option value="">Kategori</option>
            <option value="ST" {{ $sp->jenis_sp == 'ST' ? 'selected' : '' }}>ST</option>
            <option value="SP1" {{ $sp->jenis_sp == 'SP1' ? 'selected' : '' }}>SP1</option>
            <option value="SP2" {{ $sp->jenis_sp == 'SP2' ? 'selected' : '' }}>SP2</option>
            <option value="SP3" {{ $sp->jenis_sp == 'SP3' ? 'selected' : '' }}>SP3</option>
        </select>
    </div>
    <x-input-with-icon icon="ti ti-description" label="Keterangan" name="keterangan" value="{{ $sp->keterangan }}" />
    <div class="form-group mb-3">
        <button class="btn btn-primary w-100" id="btnSimpan"><i class="ti ti-send me-1"></i>Submit</button>
    </div>
</form>

<script>
    $(function() {

        const form = $('#formSuratPeringatan');
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

        $(".flatpickr-date").flatpickr();

        function buttonDisabled() {
            $("#btnSimpan").prop('disabled', true);
            $("#btnSimpan").html(`
            <div class="spinner-border spinner-border-sm text-white me-2" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
            Loading..`);
        }

        form.submit(function(e) {
            const nik = form.find("#nik").val();
            const dari = form.find("#dari").val();
            const sampai = form.find("#sampai").val();
            const keterangan = form.find("#keterangan").val();
            const jenis_sp = form.find("#jenis_sp").val();
            if (nik == '') {
                Swal.fire({
                    title: "Oops!",
                    text: "Karyawan harus diisi !",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: (e) => {
                        form.find("#nik").focus();
                    },
                });
                return false;
            } else if (dari == '' || sampai == '') {
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
            } else if (jenis_sp == '') {
                Swal.fire({
                    title: "Oops!",
                    text: "Kategori harus diisi !",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: () => {
                        form.find("#jenis_sp").focus();
                    },
                })
                return false;
            } else if (keterangan == '') {
                Swal.fire({
                    title: "Oops!",
                    text: "Keterangan harus diisi !",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: () => {
                        form.find("#keterangan").focus();
                    },
                });
                return false;
            } else {
                buttonDisabled();
            }
        });

    });
</script>
