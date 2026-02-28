<form method="POST" action="{{ route('laporangudangbahan.cetakbarangkeluar') }}" id="frmLaporanbarangkeluar" target="_blank">
    @csrf
    <div class="row">
        <div class="col">
            <x-select label="Semua Barang" name="kode_barang_keluar" :data="$barang" key="kode_barang" textShow="nama_barang"
                select2="select2Kodebarangkeluar" upperCase="true" />

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
        <div class="col-12">
            <div class="form-group mb-3">
                <select name="kode_jenis_pengeluaran" id="kode_jenis_pengeluaran" class="form-select">
                    <option value="">Semua Jenis Pengeluaran</option>
                    @foreach ($list_jenis_pengeluaran as $d)
                        <option value="{{ $d['kode_jenis_pengeluaran'] }}">
                            {{ $d['jenis_pengeluaran'] }}</option>
                    @endforeach
                </select>
            </div>
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
