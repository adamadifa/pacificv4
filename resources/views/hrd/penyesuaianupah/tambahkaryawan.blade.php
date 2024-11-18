<form action="{{ route('penyupah.storekaryawan', Crypt::encrypt($kode_gaji)) }}" method="POST" id="formTambahKaryawan">
    @csrf
    <div class="form-group mb-3">
        <select name="nik" id="nik" class="form-select select2Nik">
            <option value="">Pilih Karyawan</option>
            @foreach ($karyawan as $d)
                <option value="{{ $d->nik }}">{{ $d->nik }} - {{ $d->nama_karyawan }}</option>
            @endforeach
        </select>
    </div>
    <x-input-with-icon label="Pengurang" name="pengurang" icon="ti ti-minus" align="right" money="true" />
    <x-input-with-icon label="Penambah" name="penambah" icon="ti ti-plus" align="right" money="true" />
    <div class="form-group mb-3">
        <button class="btn btn-primary w-100" id="btnSimpan"><i class="ti ti-send me-1"></i>Submit</button>
    </div>
</form>
<script>
    $(document).ready(function() {
        $(".money").maskMoney();
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

        const form = $("#formTambahKaryawan");

        function buttonDisable() {
            $("#btnSimpan").prop('disabled', true);
            $("#btnSimpan").html(`
            <div class="spinner-border spinner-border-sm text-white me-2" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
            Loading..`);
        }
        form.submit(function(e) {
            let nik = form.find("#nik").val();
            let pengurang = form.find("#pengurang").val();
            let penambah = form.find("#Penambah").val();
            if (nik == "") {
                Swal.fire({
                    title: "Oops!",
                    text: 'Karyawan Harus Diisi !',
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: (e) => {
                        form.find("#nik").focus();
                    },
                });
                return false;
            } else {
                buttonDisable();
            }
        })
    });
</script>
