<form action="{{ route('laporanaccounting.cetakbukubesar') }}" id="formLedger" target="_blank" method="POST">
    @csrf
    <div class="form-group mb-3">
        <select name="formatlaporan" id="formatlaporan" class="form-select">
            <option value="">Format Laporan</option>
            <option value="1">Buku Besar</option>
            @if (auth()->user()->hasRole(['super admin', 'direktur', 'gm administrasi']))
                <option value="2">Neraca</option>
                <option value="3">Laba Rugi</option>
                <option value="4">Neraca Perbandingan</option>
                <option value="5">Laba Rugi Perbandingan</option>
                <option value="6">Analisa Rasio Keuangan</option>
            @endif
        </select>
    </div>
    
    <!-- New Option for Buku Besar (Detail / Rekap) -->
    <div class="form-group mb-3" id="jenislaporan_container" style="display: none;">
        <select name="jenis_laporan" id="jenis_laporan" class="form-select">
            <option value="1">Detail</option>
            <option value="2">Rekap</option>
        </select>
    </div>

    <!-- Option for Neraca / Laba Rugi -->
    <div class="form-group mb-3" id="formatcetak_container">
        <select name="formatcetak" id="formatcetak" class="form-select">
            <option value="1">Format Kolom Terpisah</option>
            <option value="2">Format Laporan Keuangan</option>
        </select>
    </div>

    <div class="row" id="coa">
        <div class="col-lg-6 col-sm-12 col-md-12">
            <div class="form-group mb-3">
                <select name="kode_akun_dari" id="kode_akun_dari" class="form-select select2Kodeakundari">
                    <option value="">Semua Akun</option>
                    @foreach ($coa as $d)
                        <option value="{{ $d->kode_akun }}">{{ $d->kode_akun }} {{ truncateText($d->nama_akun) }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="col-lg-6 col-sm-12 col-md-12">
            <div class="form-group mb-3">
                <select name="kode_akun_sampai" id="kode_akun_sampai" class="form-select select2Kodeakunsampai">
                    <option value="">Semua Akun</option>
                    @foreach ($coa as $d)
                        <option value="{{ $d->kode_akun }}">{{ $d->kode_akun }} {{ truncateText($d->nama_akun) }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>

    <div class="row" id="row_periode">
        <div class="col-lg-6 col-md-12 col-sm-12">
            <x-input-with-icon icon="ti ti-calendar" label="Dari" name="dari" datepicker="flatpickr-date" hideLabel="true" />
        </div>
        <div class="col-lg-6 col-md-12 col-sm-12">
            <x-input-with-icon icon="ti ti-calendar" label="Sampai" name="sampai" datepicker="flatpickr-date" hideLabel="true" />
        </div>
    </div>
    <div class="form-group mb-3" id="row_tahun">
        <select name="tahun" id="tahun" class="form-select">
            <option value="">Pilih Tahun</option>
            @php
                $tahun_mulai = 2024;
                $tahun_skrg = date('Y');
            @endphp
            @for ($t = $tahun_mulai; $t <= $tahun_skrg + 1; $t++)
                <option value="{{ $t }}" {{ $t == $tahun_skrg ? 'selected' : '' }}>{{ $t }}
                </option>
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
