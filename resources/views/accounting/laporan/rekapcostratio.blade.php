<form action="{{ route('laporanaccounting.cetakrekapcostratio') }}" method="POST" target="_blank" id="formRekapcostratio">
    @csrf
    @hasanyrole($roles_show_cabang)
        <div class="form-group mb-3">
            <select name="kode_cabang" id="kode_cabang_rekapcostratio" class="form-select select2Kodecabangrekapcostratio">
                <option value="">Semua Cabang</option>
                @foreach ($cabang as $d)
                    <option value="{{ $d->kode_cabang }}">{{ textUpperCase($d->nama_cabang) }}</option>
                @endforeach
            </select>
        </div>
    @endrole

    <div class="form-group mb-3">
        <select name="tahun[]" id="tahun_rekapcostratio" class="select2Tahunrekapcostratio form-select" multiple="multiple" data-placeholder="Pilih Tahun">
            @for ($t = date('Y'); $t >= $start_year; $t--)
                <option value="{{ $t }}">{{ $t }}</option>
            @endfor
        </select>
    </div>

    <div class="row mt-3">
        <div class="col-lg-10 col-md-10 col-sm-12 pe-1">
            <button type="submit" name="submitButton" class="btn btn-primary w-100" id="submitButtonRekapcostratio">
                <i class="ti ti-printer me-1"></i> Cetak
            </button>
        </div>
        <div class="col-lg-2 col-md-2 col-sm-12 ps-0">
            <button type="submit" name="exportButton" class="btn btn-success w-100" id="exportButtonRekapcostratio">
                <i class="ti ti-download"></i>
            </button>
        </div>
    </div>
</form>
