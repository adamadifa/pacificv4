<form action="{{ route('penilaiankaryawan.createpenilaian') }}" method="POST" id="formPenilaian">
    @csrf
    <x-input-with-icon icon="ti ti-calendar" label="Tanggal" name="tanggal" datepicker="flatpickr-date" />
    <x-select label="Karyawan" name="nik" :data="$karyawan" key="nik" textShow="nama_karyawan" select2="select2Nik" showKey="true" />
    <x-input-with-icon icon="ti ti-barcode" label="No. Kontrak" name="no_kontrak" readonly="true" />
    <div class="row">
        <div class="col-lg-6 col-md-12 col-sm-12">
            <x-input-with-icon label="Dari" name="dari" icon="ti ti-calendar" disabled="true" />
        </div>
        <div class="col-lg-6 col-md-12 col-sm-12">
            <x-input-with-icon label="Sampai" name="sampai" icon="ti ti-calendar" disabled="true" />
        </div>
    </div>
    <div class="form-group mb-3">
        <button class="btn btn-primary w-100" id="btnSimpan"><i class="ti ti-send me-1"></i>Submit</button>
    </div>
</form>
<script>
    $(document).ready(function() {
        const form = $('#formPenilaian');
        $(".flatpickr-date").flatpickr();
        const select2Nik = $(".select2Nik");
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
        form.submit(function(e) {
            const tanggal = form.find("#tanggal").val();
            const nik = form.find("#nik").val();
            const no_kontrak = form.find("#no_kontrak").val();
            if (tanggal == "") {
                Swal.fire({
                    title: "Oops!",
                    text: "Tanggal harus diisi!",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: (e) => {
                        form.find("#tanggal").focus();
                    },
                });
                return false;
            } else if (nik == "") {
                Swal.fire({
                    title: "Oops!",
                    text: "Karyawan harus diisi!",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: (e) => {
                        form.find("#nik").focus();
                    },
                });
                return false;
            } else if (no_kontrak == "") {
                Swal.fire({
                    title: "Oops!",
                    text: "No. Kontrak Tidak Di Temukan!",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: (e) => {
                        form.find("#no_kontrak").focus();
                    },
                });
                return false;
            }
        });

        form.find("#nik").on("change", function() {
            const nik = form.find("#nik").val();
            $.ajax({
                type: "POST",
                url: "{{ route('kontrakkerja.getlastkontrak') }}",
                data: {
                    _token: "{{ csrf_token() }}",
                    nik: nik
                },
                success: function(response) {
                    console.log(response);
                    form.find("#no_kontrak").val(response.data.no_kontrak);
                    form.find("#dari").val(response.data.dari);
                    form.find("#sampai").val(response.data.sampai);
                }
            });
        })
    });
</script>
