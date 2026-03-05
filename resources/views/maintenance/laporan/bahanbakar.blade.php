@php
    $bulan_pilihan = collect($list_bulan)->map(function ($item) {
        return (object) $item;
    });

    $tahun_pilihan = [];
    for ($t = $start_year; $t <= date('Y'); $t++) {
        $tahun_pilihan[] = (object) ['tahun' => $t];
    }
@endphp

<form action="{{ route('laporanmtc.cetakbahanbakar') }}" method="POST" id="formLapBahanBakar" target="_blank">
    @csrf
    <div class="row">
        <div class="col-12">
            <x-select label="Pilih Barang" name="kode_barang" :data="$barang" key="kode_barang" textShow="nama_barang" showKey="true" hideLabel="true" />
        </div>
    </div>
    <div class="row mt-2">
        <div class="col-12">
            <x-select label="Bulan" name="bulan" :data="$bulan_pilihan" key="kode_bulan" textShow="nama_bulan" hideLabel="true" />
        </div>
    </div>
    <div class="row mt-2">
        <div class="col-12">
            <x-select label="Tahun" name="tahun" id="tahun" :data="$tahun_pilihan" key="tahun" textShow="tahun" hideLabel="true" />
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-10 pe-1">
            <button type="submit" class="btn btn-primary w-100">
                <i class="ti ti-printer me-1"></i> Cetak
            </button>
        </div>
        <div class="col-2 ps-0">
            <button type="submit" name="exportButton" class="btn btn-success w-100">
                <i class="ti ti-download"></i>
            </button>
        </div>
    </div>
</form>
