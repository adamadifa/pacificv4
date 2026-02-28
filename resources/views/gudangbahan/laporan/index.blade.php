@extends('layouts.app')
@section('titlepage', 'Laporan Gudang Bahan')

@section('content')

@section('navigasi')
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h4 class="mb-0">Laporan Gudang Bahan</h4>
            <small class="text-muted">Laporan mutasi dan persediaan gudang bahan.</small>
        </div>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0" style="font-size: 13px">
                <li class="breadcrumb-item">
                    <a href="#"><i class="ti ti-folder me-1"></i>Gudang Bahan</a>
                </li>
                <li class="breadcrumb-item active"><i class="ti ti-report me-1"></i>Laporan</li>
            </ol>
        </nav>
    </div>
@endsection

<div class="row">
    <div class="col-lg-8 col-md-10 col-12">
        <div class="nav-align-left nav-tabs-shadow mb-4">
            <ul class="nav nav-tabs" role="tablist">
                @can('gb.barangmasuk')
                    <li class="nav-item" role="presentation">
                        <button type="button" class="nav-link active" role="tab" data-bs-toggle="tab" data-bs-target="#barangmasuk"
                            aria-controls="barangmasuk" aria-selected="false" tabindex="-1">
                            <i class="ti ti-arrow-bar-to-down me-2"></i> Barang Masuk
                        </button>
                    </li>
                @endcan
                @can('gb.barangkeluar')
                    <li class="nav-item" role="presentation">
                        <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#barangkeluar"
                            aria-controls="barangkeluar" aria-selected="false" tabindex="-1">
                            <i class="ti ti-arrow-bar-to-up me-2"></i> Barang Keluar
                        </button>
                    </li>
                @endcan
                @can('gb.persediaan')
                    <li class="nav-item" role="presentation">
                        <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#persediaan"
                            aria-controls="persediaan" aria-selected="false" tabindex="-1">
                            <i class="ti ti-box me-2"></i> Persediaan
                        </button>
                    </li>
                @endcan
                @can('gb.rekappersediaan')
                    <li class="nav-item" role="presentation">
                        <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#rekappersediaan"
                            aria-controls="rekappersediaan" aria-selected="false" tabindex="-1">
                            <i class="ti ti-file-analytics me-2"></i> Rekap Persediaan
                        </button>
                    </li>
                @endcan
                @can('gb.kartugudang')
                    <li class="nav-item" role="presentation">
                        <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#kartugudang"
                            aria-controls="kartugudang" aria-selected="false" tabindex="-1">
                            <i class="ti ti-clipboard-list me-2"></i> Kartu Gudang
                        </button>
                    </li>
                @endcan
            </ul>
            <div class="tab-content" style="padding: 0 !important; border: none !important; background: transparent !important;">
                @can('gb.barangmasuk')
                    <div class="tab-pane fade active show" id="barangmasuk" role="tabpanel">
                        <div class="card shadow-none border">
                            <div class="card-header border-bottom py-3" style="background-color: #002e65; border-radius: 0.375rem 0.375rem 0 0;">
                                <h6 class="m-0 fw-bold text-white"><i class="ti ti-arrow-bar-to-down me-2"></i>Laporan Barang Masuk</h6>
                            </div>
                            <div class="card-body pt-4">
                                @include('gudangbahan.laporan.barangmasuk')
                            </div>
                        </div>
                    </div>
                @endcan
                @can('gb.barangkeluar')
                    <div class="tab-pane fade" id="barangkeluar" role="tabpanel">
                        <div class="card shadow-none border">
                            <div class="card-header border-bottom py-3" style="background-color: #002e65; border-radius: 0.375rem 0.375rem 0 0;">
                                <h6 class="m-0 fw-bold text-white"><i class="ti ti-arrow-bar-to-up me-2"></i>Laporan Barang Keluar</h6>
                            </div>
                            <div class="card-body pt-4">
                                @include('gudangbahan.laporan.barangkeluar')
                            </div>
                        </div>
                    </div>
                @endcan
                @can('gb.persediaan')
                    <div class="tab-pane fade" id="persediaan" role="tabpanel">
                        <div class="card shadow-none border">
                            <div class="card-header border-bottom py-3" style="background-color: #002e65; border-radius: 0.375rem 0.375rem 0 0;">
                                <h6 class="m-0 fw-bold text-white"><i class="ti ti-box me-2"></i>Laporan Persediaan</h6>
                            </div>
                            <div class="card-body pt-4">
                                @include('gudangbahan.laporan.persediaan')
                            </div>
                        </div>
                    </div>
                @endcan

                @can('gb.rekappersediaan')
                    <div class="tab-pane fade" id="rekappersediaan" role="tabpanel">
                        <div class="card shadow-none border">
                            <div class="card-header border-bottom py-3" style="background-color: #002e65; border-radius: 0.375rem 0.375rem 0 0;">
                                <h6 class="m-0 fw-bold text-white"><i class="ti ti-file-analytics me-2"></i>Rekap Persediaan</h6>
                            </div>
                            <div class="card-body pt-4">
                                @include('gudangbahan.laporan.rekappersediaan')
                            </div>
                        </div>
                    </div>
                @endcan

                @can('gb.kartugudang')
                    <div class="tab-pane fade" id="kartugudang" role="tabpanel">
                        <div class="card shadow-none border">
                            <div class="card-header border-bottom py-3" style="background-color: #002e65; border-radius: 0.375rem 0.375rem 0 0;">
                                <h6 class="m-0 fw-bold text-white"><i class="ti ti-clipboard-list me-2"></i>Kartu Gudang</h6>
                            </div>
                            <div class="card-body pt-4">
                                @include('gudangbahan.laporan.kartugudang')
                            </div>
                        </div>
                    </div>
                @endcan
            </div>
        </div>
    </div>
</div>

@endsection

@push('myscript')
<script>
    $(function() {
        // Validation for each form
        function validatePeriode(formId) {
            $(`#${formId}`).submit(function() {
                const dari = $(this).find("#dari").val();
                const sampai = $(this).find("#sampai").val();
                if (dari == "") {
                    Swal.fire({
                        title: "Oops!",
                        text: 'Periode Dari Harus Diisi !',
                        icon: "warning",
                        showConfirmButton: true,
                        didClose: () => $(this).find("#dari").focus(),
                    });
                    return false;
                } else if (sampai == "") {
                    Swal.fire({
                        title: "Oops!",
                        text: 'Periode Sampai Harus Diisi !',
                        icon: "warning",
                        showConfirmButton: true,
                        didClose: () => $(this).find("#sampai").focus(),
                    });
                    return false;
                }
                var start = new Date(dari);
                var end = new Date(sampai);
                if (start.getTime() > end.getTime()) {
                    Swal.fire({
                        title: "Oops!",
                        text: 'Periode Tidak Valid !, Periode Sampai Harus Lebih Akhir dari Periode Dari',
                        icon: "warning",
                        showConfirmButton: true,
                        didClose: () => $(this).find("#sampai").focus(),
                    });
                    return false;
                }
            });
        }

        function validateBulanTahun(formId, checkKategori = false, checkBarang = false, barangId = "") {
            $(`#${formId}`).submit(function() {
                const bulan = $(this).find("#bulan").val();
                const tahun = $(this).find("#tahun").val();
                if (bulan == "") {
                    Swal.fire({ title: "Oops!", text: 'Bulan Harus Diisi !', icon: "warning", showConfirmButton: true, didClose: () => $(this).find("#bulan").focus() });
                    return false;
                } else if (tahun == "") {
                    Swal.fire({ title: "Oops!", text: 'Tahun Harus Diisi !', icon: "warning", showConfirmButton: true, didClose: () => $(this).find("#tahun").focus() });
                    return false;
                }
                if (checkKategori) {
                    const kategori = $(this).find("#kode_kategori").val();
                    if (kategori == "") {
                        Swal.fire({ title: "Oops!", text: 'Kategori Harus Diisi !', icon: "warning", showConfirmButton: true, didClose: () => $(this).find("#kode_kategori").focus() });
                        return false;
                    }
                }
                if (checkBarang) {
                    const barang = $(this).find(`#${barangId}`).val();
                    if (barang == "") {
                        Swal.fire({ title: "Oops!", text: 'Barang Harus Diisi !', icon: "warning", showConfirmButton: true, didClose: () => $(this).find(`#${barangId}`).focus() });
                        return false;
                    }
                }
            });
        }

        validatePeriode("frmLaporanbarangmasuk");
        validatePeriode("frmLaporanbarangkeluar");
        validateBulanTahun("frmPersediaan", true);
        validateBulanTahun("frmRekappersediaan", true);
        validateBulanTahun("frmKartugudang", false, true, "kode_barang_kartugudang");

        // Select2 Initializations
        $(".select2Kodebarangmasuk, .select2Kodebarangkeluar, .select2Kodebarangkartugudang").each(function() {
            $(this).wrap('<div class="position-relative"></div>').select2({
                placeholder: 'Pilih Barang',
                allowClear: true,
                dropdownParent: $(this).parent()
            });
        });
    });
</script>
@endpush
