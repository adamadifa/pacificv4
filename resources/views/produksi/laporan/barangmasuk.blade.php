<form method="POST" action="{{ route('cetakbarangmasukproduksi') }}" id="frmLaporanbarangmasuk" target="_blank">
    @csrf
    <div class="row">
        <div class="col">
            <x-select label="Semua Barang" name="kode_barang_produksi" :data="$barangproduksi" key="kode_barang_produksi"
                textShow="nama_barang" select2="select2Kodebarangmasuk" showKey="true" upperCase="true" />

        </div>
    </div>
    <div class="row">
        <div class="col-lg-6 col-md-12 col-sm-12">
            <x-input-with-icon icon="ti ti-calendar" label="Dari" name="dari" datepicker="flatpickr-date" />
        </div>
        <div class="col-lg-6 col-md-12 col-sm-12">
            <x-input-with-icon icon="ti ti-calendar" label="Sampai" name="sampai" datepicker="flatpickr-date" />
        </div>
    </div>
    <div class="row">
        <div class="col-lg-10 col-md-12 col-sm-12">
            <button type="submit" name="submitButton" class="btn btn-primary w-100" id="submitButton">
                <i class="ti ti-printer me-1"></i> Cetak
            </button>
        </div>
        <div class="col-lg-2 col-md-12 col-sm-12">
            <button type="submit" name="exportButton" class="btn btn-success w-100" id="exportButton">
                <i class="ti ti-download"></i>
            </button>
        </div>
    </div>
</form>
