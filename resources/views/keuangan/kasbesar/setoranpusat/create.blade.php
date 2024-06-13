<form action="#">
    <x-input-with-icon label="Tanggal" name="tanggal" icon="ti ti-calendar" datepicker="flatpickr-date" />
    @hasanyrole($roles_show_cabang)
        <x-select label="Cabang" name="kode_cabang" :data="$cabang" key="kode_cabang" textShow="nama_cabang" upperCase="true" />
    @endhasanyrole
    <x-input-with-icon label="Setoran Kertas" name="setoran_kertas" icon="ti ti-moneybag" align="right" money="true" />
    <x-input-with-icon label="Setoran Logam" name="setoran_logam" icon="ti ti-moneybag" align="right" money="true" />
    <x-textarea label="Keterangan" name="keterangan" />
    <div class="form-group mb-3">
        <button class="btn btn-primary w-100" id="btnSimpan"><i class="ti ti-send me-1"></i>Submit</button>
    </div>
</form>
