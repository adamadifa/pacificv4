<form method="POST" action="{{ route('laporanaccounting.cetakrekappersediaan') }}" id="frmRekappersediaan" target="_blank">
    @csrf
    <div class="form-group mb-3">
        <select name="bulan" id="bulan" class="form-select">
            <option value="">Bulan</option>
            @foreach ($list_bulan as $d)
                <option value="{{ $d['kode_bulan'] }}">{{ $d['nama_bulan'] }}</option>
            @endforeach
        </select>
    </div>
    <div class="form-group mb-3">
        <select name="tahun" id="tahun" class="form-select">
            <option value="">Tahun</option>
            @for ($t = $start_year; $t <= date('Y'); $t++)
                <option value="{{ $t }}">{{ $t }}</option>
            @endfor
        </select>
    </div>
    <div class="row mt-3">
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
