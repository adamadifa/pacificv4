<form action="{{ route('bbm.store') }}" method="post" id="formcreatebbm">
    @csrf

    <x-input-with-icon icon="ti ti-calendar" label="Tanggal" name="tanggal" value="{{ date('Y-m-d') }}"
        datepicker="flatpickr-date" />

    <div class="row">

        <div class="col-lg-4">
            <label>Cabang</label>
            <select name="kode_cabang" class="form-select select2Kodecabangsearch">
                @foreach ($cabang as $d)
                    <option value="{{ $d->kode_cabang }}"
                        {{ request('kode_cabang') == $d->kode_cabang ? 'selected' : '' }}>
                        {{ $d->nama_cabang }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="col-md-4">
            <label>Kendaraan</label>
            <select name="kode_kendaraan" id="kode_kendaraan" class="form-select select2Kendaraan">
                <option value="">Pilih Kendaraan</option>
                @foreach ($kendaraan as $k)
                    <option value="{{ $k->kode_kendaraan }}">
                        {{ $k->kode_kendaraan }} | {{ $k->no_polisi }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="col-md-4">
            <label>Driver</label>
            <select name="kode_driver_helper" id="kode_driver_helper" class="form-select select2Driver">
                <option value="">Pilih Driver</option>
                @foreach ($driver as $d)
                    <option value="{{ $d->kode_driver_helper }}">
                        {{ $d->nama_driver_helper }}
                    </option>
                @endforeach
            </select>
        </div>
    </div>

    <x-input-with-icon icon="ti ti-map-pin" label="Tujuan" name="tujuan" />

    <div class="row">
        <div class="col-md-6">
            <x-input-with-icon icon="ti ti-gauge" label="Kilometer Awal" name="kilometer_awal" align="right"
                numberFormat="true" />
        </div>

        <div class="col-md-6">
            <x-input-with-icon icon="ti ti-gauge" label="Kilometer Akhir" name="kilometer_akhir" align="right"
                numberFormat="true" />
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <x-input-with-icon icon="ti ti-droplet" label="Jumlah Liter" name="jumlah_liter" align="right"
                numberFormat="true" />
        </div>

        <div class="col-md-6">
            <x-input-with-icon icon="ti ti-cash" label="Jumlah Rupiah" name="jumlah_harga" align="right"
                numberFormat="true" />
        </div>
    </div>

    <x-input-with-icon icon="ti ti-file-description" label="Keterangan" name="keterangan" />

    <div class="form-check mt-3 mb-3">
        <input class="form-check-input agreement" type="checkbox" id="checkSave">
        <label class="form-check-label">Yakin Akan Disimpan ?</label>
    </div>

    <div class="form-group" id="saveButton">
        <button class="btn btn-primary w-100" type="submit">
            <ion-icon name="send-outline" class="me-1"></ion-icon>
            Simpan Data
        </button>
    </div>

</form>
<script>
    $(document).ready(function() {

        const form = $("#formcreatebbm");
        form.find("#saveButton").hide();

        form.find('.agreement').change(function() {
            if (this.checked) {
                form.find("#saveButton").show();
            } else {
                form.find("#saveButton").hide();
            }
        });

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


        function formatAngka(angka) {

            let number_string = angka.replace(/[^,\d]/g, '').toString(),
                split = number_string.split(','),
                sisa = split[0].length % 3,
                angka_hasil = split[0].substr(0, sisa),
                ribuan = split[0].substr(sisa).match(/\d{3}/gi);

            if (ribuan) {
                let separator = sisa ? '.' : '';
                angka_hasil += separator + ribuan.join('.');
            }

            return angka_hasil;

        }

        $("#kilometer_awal, #kilometer_akhir, #jumlah_harga").on("keyup", function() {
            $(this).val(formatAngka(this.value));
        });

        $("#formcreatebbm").submit(function() {

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

        $("#jumlah_liter").on("input", function() {

            let value = this.value.replace(/[^0-9.,]/g, '');

            value = value.replace(',', '.');

            this.value = value;

        });

        $("#jumlah_liter").on("blur", function() {

            let val = parseFloat(this.value);

            if (!isNaN(val)) {
                this.value = val.toFixed(2);
            }

        });

        $("#kode_kendaraan").change(function() {

            let kode_kendaraan = $(this).val();

            if (kode_kendaraan != "") {

                $.ajax({
                    url: "/bbm/get-km-terakhir/" + kode_kendaraan,
                    type: "GET",
                    success: function(res) {

                        if (res.status == true) {

                            $("#kilometer_awal")
                                .val(formatAngka(res.km_akhir.toString()))
                                .prop("readonly", true);

                        } else {

                            $("#kilometer_awal")
                                .val("")
                                .prop("readonly", false);

                        }

                    }
                });

            }

        });

        $("#formcreatebbm").submit(function(e) {

            let tanggal = $("input[name='tanggal']").val();
            let kendaraan = $("#kode_kendaraan").val();
            let driver = $("#kode_driver_helper").val();
            let tujuan = $("input[name='tujuan']").val();
            let km_awal = $("#kilometer_awal").val();
            let km_akhir = $("#kilometer_akhir").val();
            let liter = $("#jumlah_liter").val();
            let harga = $("#jumlah_harga").val();

            if (tanggal == "") {
                Swal.fire("Oops!", "Tanggal harus diisi", "warning");
                return false;
            }

            if (kendaraan == "") {
                Swal.fire("Oops!", "Kendaraan harus dipilih", "warning");
                return false;
            }

            if (driver == "") {
                Swal.fire("Oops!", "Driver harus dipilih", "warning");
                return false;
            }

            if (tujuan == "") {
                Swal.fire("Oops!", "Tujuan harus diisi", "warning");
                return false;
            }

            if (km_awal == "") {
                Swal.fire("Oops!", "Kilometer awal harus diisi", "warning");
                return false;
            }

            if (km_akhir == "") {
                Swal.fire("Oops!", "Kilometer akhir harus diisi", "warning");
                return false;
            }

            if (liter == "") {
                Swal.fire("Oops!", "Jumlah liter harus diisi", "warning");
                return false;
            }

            if (harga == "") {
                Swal.fire("Oops!", "Jumlah rupiah harus diisi", "warning");
                return false;
            }

            /* hapus format ribuan */

            $("#kilometer_awal").val($("#kilometer_awal").val().replace(/\./g, ''));
            $("#kilometer_akhir").val($("#kilometer_akhir").val().replace(/\./g, ''));
            $("#jumlah_harga").val($("#jumlah_harga").val().replace(/\./g, ''));


            let kmAwal = parseInt(km_awal.replace(/\./g, ''));
            let kmAkhir = parseInt(km_akhir.replace(/\./g, ''));

            if (kmAkhir < kmAwal) {
                Swal.fire("Oops!", "KM Akhir tidak boleh lebih kecil dari KM Awal", "warning");
                return false;
            }

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
