<form action="{{ route('izindinas.update', Crypt::encrypt($izindinas->kode_izin_dinas)) }}" method="POST" id="formIzin" enctype="multipart/form-data">
    @csrf
    @method('PUT')
    <x-input-with-icon icon="ti ti-barcode" label="Auto" name="kode_izin_dinas" disabled="true" value="{{ $izindinas->kode_izin_dinas }}" />
    <x-select label="Karyawan" name="nik" :data="$karyawan" key="nik" textShow="nama_karyawan" select2="select2Nik" showKey="true"
        selected="{{ $izindinas->nik }}" disabled="true" />
    <div class="row">
        <div class="col-lg-6 col-sm-12 col-md-12">
            <x-input-with-icon icon="ti ti-calendar" label="Dari" name="dari" datepicker="flatpickr-date" value="{{ $izindinas->dari }}" />
        </div>
        <div class="col-lg-6 col-sm-12 col-md-12">
            <x-input-with-icon icon="ti ti-calendar" label="Sampai" name="sampai" datepicker="flatpickr-date" value="{{ $izindinas->sampai }}" />
        </div>
    </div>
    <x-input-with-icon icon="ti ti-sun" label="Jumlah Hari" name="jml_hari" disabled="true" />
    <div class="form-group mb-3">
        <select name="kode_cabang_tujuan" id="kode_cabang_tujuan" class="form-select select2Kodecabangtujuan">
            <option value="">Tujuan</option>
            @foreach ($cabang as $d)
                <option {{ $d->kode_cabang == $izindinas->kode_cabang_tujuan ? 'selected' : '' }} value="{{ $d->kode_cabang }}">
                    {{ textUppercase($d->nama_cabang) }}</option>
            @endforeach
        </select>
    </div>
    <x-textarea label="Keterangan" name="keterangan" value="{{ $izindinas->keterangan }}" />

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
        $("#jml_hari").val(hitungHari(form.find("#dari").val(), form.find("#sampai").val()));
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

        form.submit(function(e) {
            const nik = form.find("#nik").val();
            const dari = form.find("#dari").val();
            const sampai = form.find("#sampai").val();
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
