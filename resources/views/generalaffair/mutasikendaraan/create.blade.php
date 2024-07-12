<form action="{{ route('mutasikendaraan.store') }}" method="POST" id="formMutasikendaraan">
    @csrf
    <div class="form-group mb-3">
        <select name="kode_kendaraan" id="kode_kendaraan" class="form-select select2Kendaraan">
            <option value="">Pilih Kendaraan</option>
            @foreach ($kendaraan as $d)
                <option value="{{ $d->kode_kendaraan }}">
                    {{ $d->no_polisi . '  ' . $d->merk . ' ' . $d->tipe }}
                </option>
            @endforeach
        </select>
    </div>
    <x-select label="Cabang Tujuan" name="kode_cabang" :data="$cabang" key="kode_cabang" textShow="nama_cabang" upperCase="true"
        select2="select2Kodecabang" />
    <x-input-with-icon label="Tanggal" name="tanggal" icon="ti ti-calendar" datepicker="flatpickr-date" />
    <x-input-with-icon label="Keterangan" name="keterangan" icon="ti ti-file-description" />
    <div class="form-group mb-3">
        <button class="btn btn-primary w-100" id="btnSimpan"><i class="ti ti-send me-1"></i>Submit</button>
    </div>
</form>
<script>
    $(document).ready(function() {
        const form = $('#formMutasikendaraan');
        const select2Kendaraan = $('.select2Kendaraan');
        if (select2Kendaraan.length) {
            select2Kendaraan.each(function() {
                var $this = $(this);
                $this.wrap('<div class="position-relative"></div>').select2({
                    placeholder: 'Pilih Kendaraan',
                    allowClear: true,
                    dropdownParent: $this.parent()
                });
            });
        }

        const select2Kodecabang = $('.select2Kodecabang');
        if (select2Kodecabang.length) {
            select2Kodecabang.each(function() {
                var $this = $(this);
                $this.wrap('<div class="position-relative"></div>').select2({
                    placeholder: 'Pilih  Cabang Tujuan',
                    allowClear: true,
                    dropdownParent: $this.parent()
                });
            });
        }

        $(".flatpickr-date").flatpickr();

        form.submit(function(e) {
            const kode_kendaraan = form.find("#kode_kendaraan").val();
            const kode_cabang = form.find("#kode_cabang").val();
            const tanggal = form.find("#tanggal").val();
            const keterangan = form.find("#keterangan").val();
            if (kode_kendaraan == "") {
                Swal.fire({
                    title: "Oops!",
                    text: "Kendaraan harus diisi !",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: () => {
                        form.find("#kode_kendaraan").focus();
                    },
                });
                return false;
            } else if (kode_cabang == "") {
                Swal.fire({
                    title: "Oops!",
                    text: "Cabang harus diisi !",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: () => {
                        form.find("#kode_cabang").focus();
                    },
                });
                return false;
            } else if (tanggal == "") {
                Swal.fire({
                    title: "Oops!",
                    text: "Tanggal harus diisi !",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: () => {
                        form.find("#tanggal").focus();
                    },
                });
                return false;
            } else if (keterangan == "") {
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
            }
        });
    });
</script>
