<form action="{{ route('izinkoreksi.update', Crypt::encrypt($izinkoreksi->kode_izin_koreksi)) }}" method="POST" id="formIzin">
    @csrf
    @method('PUT')
    <x-input-with-icon icon="ti ti-barcode" label="Auto" name="kode_izin" disabled="true" value="{{ $izinkoreksi->kode_izin_koreksi }}" />
    <x-select label="Karyawan" name="nik" :data="$karyawan" key="nik" textShow="nama_karyawan" select2="select2Nik" showKey="true"
        selected="{{ $izinkoreksi->nik }}" />
    <div class="row">
        <div class="col">
            <x-input-with-icon icon="ti ti-calendar" label="Tanggal" name="tanggal" datepicker="flatpickr-date" value="{{ $izinkoreksi->tanggal }}" />
        </div>
    </div>
    <div class="form-group mb-3">
        <div class="col">
            <select name="kode_jadwal" id="kode_jadwal" class="form-select select2Kodejadwal">
                <option value="">Pilih Jadwal</option>
            </select>
        </div>
    </div>

    <div class="form-group mb-3">
        <div class="col">
            <select name="kode_jam_kerja" id="kode_jam_kerja" class="form-select">
                <option value="">Pilih Jam Kerja</option>
            </select>
        </div>
    </div>
    <div class="row">
        <div class="col">
            <x-input-with-icon icon="ti ti-clock" label="Jam Masuk" name="jam_masuk" value="{{ date('H:i', strtotime($izinkoreksi->jam_masuk)) }}" />
        </div>
        <div class="col">
            <x-input-with-icon icon="ti ti-clock" label="Jam Pulang" name="jam_pulang"
                value="{{ date('H:i', strtotime($izinkoreksi->jam_pulang)) }}" />
        </div>
    </div>

    <x-textarea label="Keterangan" name="keterangan" value="{{ $izinkoreksi->keterangan }}" />
    <div class="form-group mb-3">
        <button class="btn btn-primary w-100" id="btnSimpan"><i class="ti ti-send me-1"></i>Submit</button>
    </div>
</form>
<script>
    $(function() {
        const form = $('#formIzin');
        $("#jam_masuk").mask("00:00");
        $("#jam_pulang").mask("00:00");
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

        function getpresensi() {
            const nik = form.find("#nik").val();
            const tanggal = form.find("#tanggal").val();
            $.ajax({
                url: "{{ route('izinkoreksi.getpresensi') }}",
                type: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    nik: nik,
                    tanggal: tanggal
                },
                success: function(respond) {
                    $("#jam_masuk").val(respond.jam_in);
                    $("#jam_pulang").val(respond.jam_out);
                }
            });
        }

        form.find("#nik,#tanggal").change(function(e) {
            getpresensi();
        });

        // getpresensi();
        const select2Kodejadwal = $('.select2Kodejadwal');
        if (select2Kodejadwal.length) {
            select2Kodejadwal.each(function() {
                var $this = $(this);
                $this.wrap('<div class="position-relative"></div>').select2({
                    placeholder: 'Pilih Jadwal',
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
            const jam_koreksi = form.find("#jam_koreksi").val();
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
            } else if (jam_koreksi == "") {
                Swal.fire({
                    title: "Oops!",
                    text: 'Jam koreksi Harus Diisi !',
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: () => {
                        form.find("#jam_koreksi").focus();
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


        function getJadwal() {
            let kode_jadwal = "{{ $izinkoreksi->kode_jadwal }}";
            $("#kode_jadwal").load('/getjadwalkerja/' + kode_jadwal);
        }



        function getJamkerja() {
            let kode_jadwal = "{{ $izinkoreksi->kode_jadwal }}";
            let kode_jam_kerja = "{{ $izinkoreksi->kode_jam_kerja }}";
            $("#kode_jam_kerja").load('/getjamkerja/' + kode_jadwal + '/' + kode_jam_kerja);
        }

        getJadwal();
        getJamkerja();

        $("#kode_jadwal").change(function() {
            let kode_jadwal = $(this).val();
            let kode_jam_kerja = 0;
            $("#kode_jam_kerja").load('/getjamkerja/' + kode_jadwal + '/' + kode_jam_kerja);
        });

    });
</script>
