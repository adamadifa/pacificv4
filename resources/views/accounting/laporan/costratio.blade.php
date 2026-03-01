<form action="{{ route('laporanaccounting.cetakcostratio') }}" method="POST" target="_blank" id="formCostratio">
    @csrf
    @hasanyrole($roles_show_cabang)
        <div class="form-group mb-3">
            <select name="kode_cabang" id="kode_cabang" class="form-select select2Kodecabang">
                <option value="">Semua Cabang</option>
                @foreach ($cabang as $d)
                    <option value="{{ $d->kode_cabang }}">{{ textUpperCase($d->nama_cabang) }}</option>
                @endforeach
            </select>
        </div>
    @endrole

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

    <div class="form-group mb-3">
        <select name="kategori" id="kategori" class="form-select">
            <option value="">Semua Kategori</option>
            <option value="C01">BIAYA PENJUALAN</option>
            <option value="C02">BIAYA OPERASIONAL</option>
            <option value="C03">BIAYA FASILITAS</option>
            <option value="C04">BIAYA TENAGA KERJA</option>
        </select>
    </div>
    <div class="form-group mb-3">
        <select name="formatlaporan" id="formatlaporan" class="form-select">
            <option value="1">Format 1</option>
            <option value="2">Format 2</option>
        </select>
    </div>
    <div class="row mt-3">
        <div class="col-lg-10 col-md-10 col-sm-12 pe-1">
            <button type="submit" name="submitButton" class="btn btn-primary w-100" id="submitButtonDpp">
                <i class="ti ti-printer me-1"></i> Cetak
            </button>
        </div>
        <div class="col-lg-2 col-md-2 col-sm-12 ps-0">
            <button type="submit" name="exportButton" class="btn btn-success w-100" id="exportButtonDpp">
                <i class="ti ti-download"></i>
            </button>
        </div>
    </div>
</form>
