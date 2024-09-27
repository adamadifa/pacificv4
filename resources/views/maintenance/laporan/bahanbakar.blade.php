<form action="{{ route('laporanmtc.cetakbahanbakar') }}" method="POST" id="formLapBahanBakar" target="_blank">
    @csrf
    <div class="row">
        <div class="col">
            <div class="form-group mb-3">
                <select name="kode_barang" id="kode_barang" class="form-select">
                    <option value="">Pilih Barang</option>
                    @foreach ($barang as $d)
                        <option value="{{ $d->kode_barang }}">{{ $d->kode_barang }} - {{ $d->nama_barang }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col">
            <div class="form-group mb-3">
                <select name="bulan" id="bulan" class="form-select">
                    <option value="">Bulan</option>
                    @foreach ($list_bulan as $d)
                        <option value="{{ $d['kode_bulan'] }}">{{ $d['nama_bulan'] }}</option>
                    @endforeach
                </select>
            </div>
        </div>

    </div>
    <div class="row">
        <div class="col">
            <div class="form-group mb-3">
                <select name="tahun" id="tahun" class="form-select">
                    <option value="">Tahun</option>
                    @for ($t = $start_year; $t <= date('Y'); $t++)
                        <option value="{{ $t }}">{{ $t }}</option>
                    @endfor
                </select>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-10 col-md-12 col-sm-12">
            <button type="submit" class="btn btn-primary w-100">
                <i class="ti ti-printer me-1"></i> Cetak
            </button>
        </div>
        <div class="col-lg-2 col-md-12 col-sm-12">
            <button type="submit" name="exportButton" class="btn btn-success w-100">
                <i class="ti ti-download"></i>
            </button>
        </div>
    </div>
</form>
