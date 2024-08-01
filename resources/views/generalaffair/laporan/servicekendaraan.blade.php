<form action="{{ route('laporanga.cetakservicekendaraan') }}" method="POST" id="formLapServicekendaraan" target="_blank">
    @csrf
    <div class="form-group mb-3">
        <select name="kode_kendaraan" id="kode_kendaraan" class="form-select select2Kendaraan">
            <option value="">Semua Kendaraan</option>
            @foreach ($kendaraan as $d)
                <option value="{{ $d->kode_kendaraan }}">{{ $d->no_polisi }} {{ $d->merek }} {{ $d->tipe }} {{ $d->tipe_kendaraan }}</option>
            @endforeach
        </select>
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
