<form action="{{ route('kendaraan.update', Crypt::encrypt($kendaraan->kode_kendaraan)) }}" id="formeditKendaraan" method="POST">
    @csrf
    @method('PUT')
    <x-input-with-icon icon="ti ti-barcode" label="Kode Kendaraan" value="{{ $kendaraan->kode_kendaraan }}" name="kode_kendaraan" disabled="true" />
    <x-input-with-icon icon="ti ti-barcode" label="No. Polisi" name="no_polisi" value="{{ $kendaraan->no_polisi }}" />
    <x-input-with-icon icon="ti ti-barcode" label="No. STNK" name="no_stnk" value="{{ $kendaraan->no_stnk }}" />
    <x-input-with-icon icon="ti ti-barcode" label="No. Uji" name="no_uji" value="{{ $kendaraan->no_uji }}" />
    <x-input-with-icon icon="ti ti-barcode" label="SIPA" name="sipa" value="{{ $kendaraan->sipa }}" />
    <x-input-with-icon icon="ti ti-file-description" label="Merk" name="merk" value="{{ $kendaraan->merek }}" />
    <div class="row">
        <div class="col-lg-6 col-md-12 col-sm-12">
            <x-input-with-icon icon="ti ti-file-description" label="Type Kendaraan" name="tipe_kendaraan" value="{{ $kendaraan->tipe_kendaraan }}" />
        </div>
        <div class="col-lg-6 col-md-12 col-sm-12">
            <x-input-with-icon icon="ti ti-file-description" label="Type" name="tipe" value="{{ $kendaraan->tipe }}" />
        </div>
    </div>


    <x-input-with-icon icon="ti ti-file-description" label="No. Rangka" name="no_rangka" value="{{ $kendaraan->no_rangka }}" />
    <x-input-with-icon icon="ti ti-file-description" label="No. Mesin" name="no_mesin" value="{{ $kendaraan->no_mesin }}" />
    <div class="row">
        <div class="col-lg-4 col-md-12 col-sm-12">
            <x-input-with-icon icon="ti ti-file-description" label="Tahun" name="tahun_pembuatan" value="{{ $kendaraan->tahun_pembuatan }}" />
        </div>
        <div class="col-lg-8 col-md-12 col-sm-12">
            <x-input-with-icon icon="ti ti-user" label="Atas Nama" name="atas_nama" value="{{ $kendaraan->atas_nama }}" />
        </div>
    </div>

    <x-input-with-icon icon="ti ti-file-description" label="Alamat" name="alamat" value="{{ $kendaraan->alamat }}" />
    <x-input-with-icon-label icon="ti ti-calendar" label="Jatuh Tempo" name="jatuhtempo_kir" datepicker="flatpickr-date"
        value="{{ $kendaraan->jatuhtempo_kir }}" />
    <x-input-with-icon-label icon="ti ti-calendar" label="Jatuh Tempo Pajak 1 Tahun" name="jatuhtempo_pajak_satutahun" datepicker="flatpickr-date"
        value="{{ $kendaraan->jatuhtempo_pajak_satutahun }}" />
    <x-input-with-icon-label icon="ti ti-calendar" label="Jatuh Tempo Pajak 5 Tahun" name="jatuhtempo_pajak_limatahun" datepicker="flatpickr-date"
        value="{{ $kendaraan->jatuhtempo_pajak_limatahun }}" />
    <x-input-with-icon icon="ti ti-file-description" label="Kapasitas" name="kapasitas" value="{{ $kendaraan->kapasitas }}" />
    <x-select label="Cabang" name="kode_cabang" :data="$cabang" key="kode_cabang" textShow="nama_cabang"
        selected="{{ $kendaraan->kode_cabang }}" />
    <div class="form-group mb-3">
        <select name="status_aktif_kendaraan" id="status_aktif_kendaraan" class="form-select">
            <option value="">Status</option>
            <option value="1" {{ $kendaraan->status_aktif_kendaraan === '1' ? 'selected' : '' }}>Aktif</option>
            <option value="0" {{ $kendaraan->status_aktif_kendaraan === '0' ? 'selected' : '' }}>Non Aktif
            </option>
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
<script src="{{ asset('assets/js/pages/kendaraan/edit.js') }}"></script>
<script>
    $(function() {
        $(".flatpickr-date").flatpickr();
    });
</script>
