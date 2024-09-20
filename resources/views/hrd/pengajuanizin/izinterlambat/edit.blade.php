<form action="{{ route('izinterlambat.update', Crypt::encrypt($izinterlambat->kode_izin_terlambat)) }}" method="POST" id="formIzin">
    @csrf
    @method('PUT')
    <x-input-with-icon icon="ti ti-barcode" name="kode_izin" disabled="true" value="{{ $izinterlambat->kode_izin_terlambat }}" />
    <x-select label="Karyawan" name="nik" :data="$karyawan" key="nik" textShow="nama_karyawan" select2="select2Nik" showKey="true"
        selected="{{ $izinterlambat->nik }}" />
    <div class="row">
        <div class="col">
            <x-input-with-icon icon="ti ti-calendar" label="Tanggal" name="tanggal" datepicker="flatpickr-date"
                value="{{ $izinterlambat->tanggal }}" />
        </div>
    </div>
    <div class="row">
        <div class="col">
            <x-input-with-icon icon="ti ti-clock" label="Jam terlambat" name="jam_terlambat"
                value="{{ date('H:i', strtotime($izinterlambat->jam_terlambat)) }}" />
        </div>
    </div>

    <x-textarea label="Keterangan" name="keterangan" value="{{ $izinterlambat->keterangan }}" />
    <div class="form-group mb-3">
        <button class="btn btn-primary w-100" id="btnSimpan"><i class="ti ti-send me-1"></i>Submit</button>
    </div>
</form>
<script>
    $(function() {
        const form = $('#formIzin');
        $("#jam_terlambat").mask("00:00");
        $(".flatpickr-date").flatpickr();
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

        function hitungHari(startDate, endDate) {
            if (startDate && endDate) {
                var start = new Date(startDate);
                var end = new Date(endDate);

                // Tambahkan 1 hari agar penghitungan inklusif
                var timeDifference = end - start + (1000 * 3600 * 24);
                var dayDifference = timeDifference / (1000 * 3600 * 24);

                return dayDifference;
            } else {
                return 0;
            }
        }



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
            const tanggal = form.find("#tanggal").val();
            const jam_terlambat = form.find("#jam_terlambat").val();
            const keterangan = form.find("#keterangan").val();
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
            } else if (tanggal == "") {
                Swal.fire({
                    title: "Oops!",
                    text: 'Tanggal Harus Diisi !',
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: () => {
                        form.find("#tanggal").focus();
                    }
                });
                return false;
            } else if (jam_terlambat == "") {
                Swal.fire({
                    title: "Oops!",
                    text: 'Jam terlambat Harus Diisi !',
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: () => {
                        form.find("#jam_terlambat").focus();
                    }
                });
                return false;
            } else if (keterangan == '') {
                Swal.fire({
                    title: "Oops!",
                    text: 'Keterangan Harus Diisi !',
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: () => {
                        form.find("#keterangan").focus();
                    }
                });
                return false;
            } else {
                buttonDisabled();
            }
        });

    });
</script>
