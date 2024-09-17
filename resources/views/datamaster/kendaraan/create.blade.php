<form action="{{ route('kendaraan.store') }}" id="formcreateKendaraan" method="POST">
    @csrf
    <x-input-with-icon icon="ti ti-barcode" label="Auto" name="kode_kendaraan" disabled="true" />
    <x-input-with-icon icon="ti ti-barcode" label="No. Polisi" name="no_polisi" />
    <x-input-with-icon icon="ti ti-barcode" label="No. STNK" name="no_stnk" />
    <x-input-with-icon icon="ti ti-barcode" label="No. Uji" name="no_uji" />
    <x-input-with-icon icon="ti ti-barcode" label="SIPA" name="sipa" />
    <x-input-with-icon icon="ti ti-file-description" label="Merk" name="merk" />
    <div class="row">
        <div class="col-lg-6 col-md-12 col-sm-12">
            <x-input-with-icon icon="ti ti-file-description" label="Type Kendaraan" name="tipe_kendaraan" />
        </div>
        <div class="col-lg-6 col-md-12 col-sm-12">
            <x-input-with-icon icon="ti ti-file-description" label="Type" name="tipe" />
        </div>
    </div>


    <x-input-with-icon icon="ti ti-file-description" label="No. Rangka" name="no_rangka" />
    <x-input-with-icon icon="ti ti-file-description" label="No. Mesin" name="no_mesin" />
    <div class="row">
        <div class="col-lg-4 col-md-12 col-sm-12">
            <x-input-with-icon icon="ti ti-file-description" label="Tahun" name="tahun_pembuatan" />
        </div>
        <div class="col-lg-8 col-md-12 col-sm-12">
            <x-input-with-icon icon="ti ti-user" label="Atas Nama" name="atas_nama" />
        </div>
    </div>

    <x-input-with-icon icon="ti ti-file-description" label="Alamat" name="alamat" />
    <x-input-with-icon-label icon="ti ti-calendar" label="Jatuh Tempo" name="jatuhtempo_kir" datepicker="flatpickr-date" />
    <x-input-with-icon-label icon="ti ti-calendar" label="Jatuh Tempo Pajak 1 Tahun" name="jatuhtempo_pajak_satutahun" datepicker="flatpickr-date" />
    <x-input-with-icon-label icon="ti ti-calendar" label="Jatuh Tempo Pajak 5 Tahun" name="jatuhtempo_pajak_limatahun" datepicker="flatpickr-date" />
    <x-input-with-icon icon="ti ti-file-description" label="Kapasitas" name="kapasitas" />
    <x-select label="Cabang" name="kode_cabang" :data="$cabang" key="kode_cabang" textShow="nama_cabang" />
    <div class="form-group mb-3">
        <select name="status_aktif_kendaraan" id="status_aktif_kendaraan" class="form-select">
            <option value="">Status</option>
            <option value="1">Aktif</option>
            <option value="0">Non Aktif</option>
        </select>
    </div>


    <div class="form-group">
        <button class="btn btn-primary w-100" type="submit">
            <ion-icon name="send-outline" class="me-1"></ion-icon>
            Submit
        </button>
    </div>
</form>
<script src="{{ asset('assets/vendor/libs/flatpickr/flatpickr.js') }}"></script>
<script src="{{ asset('/assets/vendor/libs/@form-validation/umd/bundle/popular.min.js') }}"></script>
<script src="{{ asset('/assets/vendor/libs/@form-validation/umd/plugin-bootstrap5/index.min.js') }}"></script>
<script src="{{ asset('/assets/vendor/libs/@form-validation/umd/plugin-auto-focus/index.min.js') }}"></script>
<script src="{{ asset('assets/js/pages/kendaraan/create.js') }}"></script>
<script>
    $(function() {
        $(".flatpickr-date").flatpickr();
    });
</script>
