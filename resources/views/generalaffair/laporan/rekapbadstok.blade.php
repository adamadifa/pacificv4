@php
    $asal_bs_data = $cabang
        ->map(function ($d) {
            return (object) [
                'kode_cabang' => $d->kode_cabang,
                'nama_cabang' => textUpperCase($d->nama_cabang),
            ];
        })
        ->prepend((object) ['kode_cabang' => 'GDG', 'nama_cabang' => 'GUDANG']);

    $format_data = [(object) ['kode' => '1', 'nama' => 'Per Bulan'], (object) ['kode' => '2', 'nama' => 'Per Tahun']];

    $bulan_pilihan = collect($list_bulan)->map(function ($item) {
        return (object) $item;
    });

    $tahun_pilihan = [];
    for ($t = $start_year; $t <= date('Y'); $t++) {
        $tahun_pilihan[] = (object) ['tahun' => $t];
    }
@endphp

<form action="{{ route('laporanga.cetakrekapbadstok') }}" id="formLapRekapbadstok" method="POST" target="_blank">
    @csrf
    <x-select label="Asal BS" name="kode_asal_bs" :data="$asal_bs_data" key="kode_cabang" textShow="nama_cabang" select2="select2Kodecabang"
        hideLabel="true" />

    <div class="row">
        <div class="col">
            <x-select label="Format Laporan" name="formatlaporan" :data="$format_data" key="kode" textShow="nama" hideLabel="true" />
        </div>
    </div>
    <div class="row" id="bulan_container">
        <div class="col">
            <x-select label="Bulan" name="bulan" :data="$bulan_pilihan" key="kode_bulan" textShow="nama_bulan" hideLabel="true" />
        </div>
    </div>
    <div class="row">
        <div class="col">
            <x-select label="Tahun" name="tahun" :data="$tahun_pilihan" key="tahun" textShow="tahun" hideLabel="true" />
        </div>
    </div>

    <div class="row">
        <div class="col-lg-10 col-md-10 col-sm-12 pe-1">
            <button type="submit" class="btn btn-primary w-100">
                <i class="ti ti-printer me-1"></i> Cetak
            </button>
        </div>
        <div class="col-lg-2 col-md-2 col-sm-12 ps-0">
            <button type="submit" name="exportButton" class="btn btn-success w-100">
                <i class="ti ti-download"></i>
            </button>
        </div>
    </div>
</form>
