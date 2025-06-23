<form action="{{ route('resign.update', Crypt::encrypt($resign->kode_resign)) }}" method="POST" id="formResign">
    @csrf
    @method('PUT')
    <x-input-with-icon icon="ti ti-barcode" label="Auto" name="kode_resign" value="{{ $resign->kode_resign }}" disabled="true" />
    <x-input-with-icon icon="ti ti-calendar" label="Tanggal" name="tanggal" value="{{ $resign->tanggal }}" datepicker="flatpickr-date" />
    <x-select label="Karyawan" name="nik" :data="$karyawan" key="nik" textShow="nama_karyawan" select2="select2Nik" showKey="true"
        selected="{{ $resign->nik }}" />
    <x-select label="Kategori JMK" name="kode_kategori" :data="$kategori_jmk" key="kode_kategori" textShow="nama_kategori"
        selected="{{ $resign->kode_kategori }}" />
    <x-input-with-icon icon="ti ti-file-text" label="Keterangan" name="keterangan" value="{{ $resign->keterangan }}" />
    <div class="form-check mt-2">
        <input class="form-check-input" type="checkbox" name="pjp" id="pjp" value="1" {{ $resign->pjp ? 'checked' : '' }}>
        <label class="form-check-label" for="pjp">
            PJP
        </label>
    </div>
    <div class="form-check">
        <input class="form-check-input" type="checkbox" name="kasbon" id="kasbon" value="1" {{ $resign->kasbon ? 'checked' : '' }}>
        <label class="form-check-label" for="kasbon">
            Kasbon
        </label>
    </div>
    <div class="form-check">
        <input class="form-check-input" type="checkbox" name="piutang_lainnya" id="piutang_lainnya" value="1"
            {{ $resign->piutang_lainnya ? 'checked' : '' }}>
        <label class="form-check-label" for="piutang_lainnya">
            Piutang Lainnya
        </label>
    </div>

    <div class="form-group mb-3 mt-3">
        <button class="btn btn-primary w-100" id="btnSimpan"><i class="ti ti-send me-1"></i>Submit</button>
    </div>
</form>
<script>
    $(function() {
        const form = $('#formResign');;
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

        $(".money").maskMoney();

        function buttonDisable() {
            $("#btnSimpan").prop('disabled', true);
            $("#btnSimpan").html(`
            <div class="spinner-border spinner-border-sm text-white me-2" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
            Loading..`);
        }

        form.submit(function(e) {
            const tanggal = form.find("#tanggal").val();
            const nik = form.find("#nik").val();
            const keterangan = form.find("#keterangan").val();
            if (tanggal == '') {
                Swal.fire({
                    title: "Oops!",
                    text: "Tanggal harus diisi !",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: (e) => {
                        form.find("#tanggal").focus();
                    },
                });
                return false;
            } else if (nik == '') {
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
            } else if (keterangan == '') {
                Swal.fire({
                    title: "Oops!",
                    text: "Keterangan harus diisi !",
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
