<form action="{{ route('bbm.update', $bbm->id) }}" method="post" id="formeditbbm">
    @csrf
    @method('PUT')

    <x-input-with-icon icon="ti ti-calendar" label="Tanggal" name="tanggal" value="{{ $bbm->tanggal }}"
        datepicker="flatpickr-date" />

    <div class="row">
        <div class="col-md-6">
            <label>Kendaraan</label>
            <select name="kode_kendaraan" id="kode_kendaraan" class="form-select select2Kendaraan">
                <option value="">Pilih Kendaraan</option>
                @foreach ($kendaraan as $k)
                    <option value="{{ $k->kode_kendaraan }}"
                        {{ $bbm->kode_kendaraan == $k->kode_kendaraan ? 'selected' : '' }}>
                        {{ $k->kode_kendaraan }} | {{ $k->no_polisi }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="col-md-6">
            <label>Driver</label>
            <select name="kode_driver_helper" id="kode_driver_helper" class="form-select select2Driver">
                <option value="">Pilih Driver</option>
                @foreach ($driver as $d)
                    <option value="{{ $d->kode_driver_helper }}"
                        {{ $bbm->kode_driver_helper == $d->kode_driver_helper ? 'selected' : '' }}>
                        {{ $d->nama_driver_helper }}
                    </option>
                @endforeach
            </select>
        </div>
    </div>

    <x-input-with-icon icon="ti ti-map-pin" label="Tujuan" name="tujuan" value="{{ $bbm->tujuan }}" />

    <div class="row">
        <div class="col-md-6">
            <x-input-with-icon icon="ti ti-gauge" label="Kilometer Awal" name="kilometer_awal"
                value="{{ $bbm->kilometer_awal }}" align="right" numberFormat="true" />
        </div>

        <div class="col-md-6">
            <x-input-with-icon icon="ti ti-gauge" label="Kilometer Akhir" name="kilometer_akhir"
                value="{{ $bbm->kilometer_akhir }}" align="right" numberFormat="true" />
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <x-input-with-icon icon="ti ti-droplet" label="Jumlah Liter" name="jumlah_liter"
                value="{{ $bbm->jumlah_liter }}" align="right" numberFormat="true" />
        </div>

        <div class="col-md-6">
            <x-input-with-icon icon="ti ti-cash" label="Jumlah Rupiah" name="jumlah_harga"
                value="{{ $bbm->jumlah_harga }}" align="right" numberFormat="true" />
        </div>
    </div>

    <x-input-with-icon icon="ti ti-file-description" label="Keterangan" name="keterangan"
        value="{{ $bbm->keterangan }}" />

    <div class="form-check mt-3 mb-3">
        <input class="form-check-input agreement" type="checkbox" id="defaultCheck3">
        <label class="form-check-label">Yakin Akan Disimpan ?</label>
    </div>

    <div class="form-group" id="saveButton">
        <button class="btn btn-primary w-100" type="submit">
            <ion-icon name="send-outline" class="me-1"></ion-icon>
            Update
        </button>
    </div>

</form>
<script>
    $(function() {

        const form = $("#formeditbbm");

        $(".flatpickr-date").flatpickr({
            enable: [{
                from: "{{ $start_periode }}",
                to: "{{ $end_periode }}"
            }]
        });

        // =========================
        // SELECT2
        // =========================
        const select2Driver = form.find('.select2Driver');
        if (select2Driver.length) {
            select2Driver.each(function() {
                var $this = $(this);
                $this.wrap('<div class="position-relative"></div>').select2({
                    placeholder: 'Pilih Driver',
                    allowClear: true,
                    dropdownParent: $this.parent()
                });
            });
        }

        const select2Kendaraan = form.find('.select2Kendaraan');
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

        // =========================
        // FORMAT ANGKA
        // =========================
        function formatAngka(angka) {

            let number_string = angka.replace(/[^,\d]/g, '').toString(),
                split = number_string.split(','),
                sisa = split[0].length % 3,
                hasil = split[0].substr(0, sisa),
                ribuan = split[0].substr(sisa).match(/\d{3}/gi);

            if (ribuan) {
                let separator = sisa ? '.' : '';
                hasil += separator + ribuan.join('.');
            }

            return hasil;

        }

        // FORMAT SAAT LOAD EDIT
        $("#kilometer_awal, #kilometer_akhir, #jumlah_harga").each(function() {

            let val = $(this).val();

            if (val != "") {
                $(this).val(formatAngka(val.toString()));
            }

        });

        // format realtime
        $("#kilometer_awal, #kilometer_akhir, #jumlah_harga").on("keyup", function() {
            $(this).val(formatAngka(this.value));
        });

        // =========================
        // FORMAT LITER
        // =========================
        $("#jumlah_liter").on("input", function() {

            let value = this.value.replace(/[^0-9.,]/g, '');
            value = value.replace('.', ',');

            this.value = value;

        });

        $("#jumlah_liter").on("blur", function() {

            let val = parseFloat(this.value);

            if (!isNaN(val)) {
                this.value = val.toFixed(2);
            }

        });

        // =========================
        // TOMBOL SAVE
        // =========================
        form.find("#saveButton").hide();

        form.find('.agreement').change(function() {
            if (this.checked) {
                form.find("#saveButton").show();
            } else {
                form.find("#saveButton").hide();
            }
        });

        // =========================
        // VALIDASI + NORMALISASI
        // =========================
        form.submit(function() {

            const kendaraan = $("#kode_kendaraan").val();
            const driver = $("#kode_driver_helper").val();
            const km_awal = $("#kilometer_awal").val();
            const km_akhir = $("#kilometer_akhir").val();
            const liter = $("#jumlah_liter").val();

            if (kendaraan == "") {
                Swal.fire("Oops", "Kendaraan harus dipilih", "warning");
                return false;
            }

            if (driver == "") {
                Swal.fire("Oops", "Driver harus dipilih", "warning");
                return false;
            }

            if (km_awal == "" || km_akhir == "") {
                Swal.fire("Oops", "Kilometer harus diisi", "warning");
                return false;
            }

            if (liter == "") {
                Swal.fire("Oops", "Jumlah liter harus diisi", "warning");
                return false;
            }

            // hapus separator ribuan sebelum kirim DB
            $("#kilometer_awal").val(
                $("#kilometer_awal").val().replace(/\./g, '')
            );

            $("#kilometer_akhir").val(
                $("#kilometer_akhir").val().replace(/\./g, '')
            );

            $("#jumlah_harga").val(
                $("#jumlah_harga").val().replace(/\./g, '')
            );

        });

    });
</script>
