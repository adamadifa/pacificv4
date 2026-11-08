<form action="{{ route('laporanaccounting.cetakaruskas') }}" method="POST" target="_blank" id="formAruskas">
    @csrf
    <div class="form-group mb-3">
        <select name="bulan" id="bulan_aruskas" class="form-select">
            <option value="">Pilih Bulan</option>
            @foreach ($list_bulan as $d)
                <option value="{{ $d['kode_bulan'] }}" {{ date('m') == $d['kode_bulan'] ? 'selected' : '' }}>
                    {{ $d['nama_bulan'] }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="form-group mb-3">
        <select name="tahun" id="tahun_aruskas" class="form-select">
            <option value="">Pilih Tahun</option>
            @php
                $tahun_mulai = 2024;
                $tahun_skrg = date('Y');
            @endphp
            @for ($t = $tahun_mulai; $t <= $tahun_skrg + 1; $t++)
                <option value="{{ $t }}" {{ $t == $tahun_skrg ? 'selected' : '' }}>{{ $t }}</option>
            @endfor
        </select>
    </div>

    <div class="row mt-3">
        <div class="col-lg-10 col-md-10 col-sm-12 pe-1">
            <button type="submit" name="submitButton" class="btn btn-primary w-100" id="submitButtonAruskas">
                <i class="ti ti-printer me-1"></i> Cetak
            </button>
        </div>
        <div class="col-lg-2 col-md-2 col-sm-12 ps-0">
            <button type="submit" name="exportButton" class="btn btn-success w-100" id="exportButtonAruskas">
                <i class="ti ti-download"></i>
            </button>
        </div>
    </div>
</form>

