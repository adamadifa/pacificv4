<form action="{{ route('izincuti.store') }}" method="POST" id="formIzin" enctype="multipart/form-data">
    @csrf
    <x-input-with-icon icon="ti ti-barcode" label="Auto" name="kode_izin_cuti" disabled="true" />
    <x-select label="Karyawan" name="nik" :data="$karyawan" key="nik" textShow="nama_karyawan" select2="select2Nik" showKey="true" />
    <div class="row">
        <div class="col-lg-6 col-sm-12 col-md-12">
            <x-input-with-icon icon="ti ti-calendar" label="Dari" name="dari" datepicker="flatpickr-date" />
        </div>
        <div class="col-lg-6 col-sm-12 col-md-12">
            <x-input-with-icon icon="ti ti-calendar" label="Sampai" name="sampai" datepicker="flatpickr-date" />
        </div>
    </div>
    <div class="form-group mb-3">
        <select name="kode_cuti" id="kode_cuti" class="form-select">
            <option value="">Jenis Cuti</option>
            @foreach ($jenis_cuti as $d)
                <option value="{{ $d->kode_cuti }}">{{ $d->nama_cuti }} </option>
            @endforeach
        </select>
    </div>

    <div class="form-group mb-3" id="cutikhusus">
        <select name="kode_cuti_khusus" id="kode_cuti_khusus" class="form-select">
            <option value="">Jenis Cuti Khusus</option>
            @foreach ($jenis_cuti_khusus as $d)
                <option value="{{ $d->kode_cuti_khusus }}">{{ $d->nama_cuti_khusus }} </option>
            @endforeach
        </select>
    </div>
    <x-input-with-icon icon="ti ti-sun" label="Jumlah Hari" name="jml_hari" disabled="true" />
    <x-textarea label="Keterangan" name="keterangan" />
    <x-input-file name="doc_cuti" label="doc_cuti" />
    <div class="form-group mb-3">
        <button class="btn btn-primary w-100" id="btnSimpan"><i class="ti ti-send me-1"></i>Submit</button>
    </div>
</form>
<script>
    $(function() {
        const form = $('#formIzin');
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

        $("#dari,#sampai").on("change", function() {
            const dari = form.find("#dari").val();
            const sampai = form.find("#sampai").val();
            $("#jml_hari").val(hitungHari(dari, sampai));
        });

        function buttonDisabled() {
            $("#btnSimpan").prop('disabled', true);
            $("#btnSimpan").html(`
            <div class="spinner-border spinner-border-sm text-white me-2" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
            Loading..`);
        }

        function loadcutikhusus() {
            let kode_cuti = form.find("#kode_cuti").val();
            if (kode_cuti == "C03") {
                $("#cutikhusus").show();
            } else {
                $("#cutikhusus").hide();
            }
        }

        loadcutikhusus();

        $("#kode_cuti").on("change", function() {
            loadcutikhusus();
        });

        form.submit(function(e) {
            const nik = form.find("#nik").val();
            const dari = form.find("#dari").val();
            const sampai = form.find("#sampai").val();
            const keterangan = form.find("#keterangan").val();
            const kode_cuti = form.find("#kode_cuti").val();
            const kode_cuti_khusus = form.find("#kode_cuti_khusus").val();
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
                    text: 'Periode Izin Harus Diisi !',
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: () => {
                        form.find("#dari").focus();
                    }
                });
                return false;
            } else if (sampai < dari) {
                Swal.fire({
                    title: "Oops!",
                    text: 'Periode Izin Harus Sesuai !',
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: () => {
                        form.find("#sampai").focus();
                    }
                });
                return false;
            } else if (kode_cuti == "") {
                Swal.fire({
                    title: "Oops!",
                    text: 'Jenis Cuti Harus Diisi !',
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: () => {
                        form.find("#kode_cuti").focus();
                    }
                });
                return false;
            } else if (kode_cuti == "C03" && kode_cuti_khusus == "") {
                Swal.fire({
                    title: "Oops!",
                    text: 'Jenis Cuti Khusus Harus Diisi !',
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: () => {
                        form.find("#kode_cuti_khusus").focus();
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
