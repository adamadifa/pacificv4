<form action="{{ route('sakasbesarkeuangan.store') }}" method="POST" id="formLedger">
    @csrf
   <input type="hidden" name="kode_cabang" value="PST">
    <x-input-with-icon label="Tanggal" name="tanggal" icon="ti ti-calendar" datepicker="flatpickr-date" />

    <x-textarea label="Keterangan" name="keterangan" />
    <x-input-with-icon label="Jumlah" name="jumlah" icon="ti ti-moneybag" align="right" money="true" />
    <div class="form-group">
        <select name="debet_kredit" id="debet_kredit" class="form-select">
            <option value="">Pilih Debet / Kredit</option>
            <option value="D">Debet</option>
            <option value="K">Kredit</option>
        </select>
    </div>
    <div class="form-group" id="saveButton">
        <button class="btn btn-primary w-100" type="submit" id="btnSimpan">
            <ion-icon name="send-outline" class="me-1"></ion-icon>
            Submit
        </button>
    </div>
</form>
<script>
    $(function() {
        const form = $("#formLedger");
        $(".flatpickr-date").flatpickr();
        $(".money").maskMoney();

        const select2Kodecabang = $('.select2Kodecabang');
        if (select2Kodecabang.length) {
            select2Kodecabang.each(function() {
                var $this = $(this);
                $this.wrap('<div class="position-relative"></div>').select2({
                    placeholder: 'Pilih Cabang',
                    allowClear: true,
                    dropdownParent: $this.parent()
                });
            });
        }

        function buttonDisable() {
            $("#btnSimpan").prop('disabled', true);
            $("#btnSimpan").html(`
            <div class="spinner-border spinner-border-sm text-white me-2" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
            Loading..`);
        }

        form.submit(function(e) {
            const kode_cabang = form.find("#kode_cabang").val();
            const tanggal = form.find("#tanggal").val();
            const keterangan = form.find("#keterangan").val();
            const jumlah = form.find("#jumlah").val();
            const debet_kredit = form.find("#debet_kredit").val();
            if (kode_cabang == "") {
                Swal.fire({
                    title: "Oops!",
                    text: "Silahkan Pilih Cabang Terlebih Dahulu !",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: (e) => {
                        form.find("#kode_cabang").focus();
                    },
                });
                return false;
            } else if (tanggal == "") {
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
            } else if (jumlah == "") {
                Swal.fire({
                    title: "Oops!",
                    text: "Jumlah Harus Diisi !",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: (e) => {
                        form.find("#jumlah").focus();
                    },
                });
                return false;
            } else if (debet_kredit == "") {
                Swal.fire({
                    title: "Oops!",
                    text: "Debet / Kredit Harus Diisi !",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: (e) => {
                        form.find("#debet_kredit").focus();
                    },
                });
                return false;
            } else {
                buttonDisable();
            }
        });
    });
</script>
