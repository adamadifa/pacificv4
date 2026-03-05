<form action="{{ route('laporanga.cetakservicekendaraan') }}" method="POST" id="formLapServicekendaraan" target="_blank">
    @csrf
    @php
        $kendaraan_data = $kendaraan->map(function ($d) {
            return (object) [
                'kode_kendaraan' => $d->kode_kendaraan,
                'nama_kendaraan' => $d->no_polisi . ' ' . $d->merek . ' ' . $d->tipe . ' ' . $d->tipe_kendaraan,
            ];
        });
    @endphp
    <x-select label="Semua Kendaraan" name="kode_kendaraan" :data="$kendaraan_data" key="kode_kendaraan" textShow="nama_kendaraan"
        select2="select2Kendaraan" hideLabel="true" allOption="true" allOptionLabel="Semua Kendaraan" />
    <div class="row">
        <div class="col-lg-6 col-md-12 col-sm-12">
            <x-input-with-icon icon="ti ti-calendar" label="Dari" name="dari" datepicker="flatpickr-date" hideLabel="true" />
        </div>
        <div class="col-lg-6 col-md-12 col-sm-12">
            <x-input-with-icon icon="ti ti-calendar" label="Sampai" name="sampai" datepicker="flatpickr-date" hideLabel="true" />
        </div>
    </div>
    <div class="row">
        <div class="col-lg-10 col-md-10 col-sm-12 pe-1">
            <button type="submit" name="submitButton" class="btn btn-primary w-100" id="submitButton">
                <i class="ti ti-printer me-1"></i> Cetak
            </button>
        </div>
        <div class="col-lg-2 col-md-2 col-sm-12 ps-0">
            <button type="submit" name="exportButton" class="btn btn-success w-100" id="exportButton">
                <i class="ti ti-download"></i>
            </button>
        </div>
    </div>
</form>
