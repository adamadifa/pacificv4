@extends('layouts.app')
@section('titlepage', 'Laporan General Affair')

@section('content')

@section('navigasi')
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h4 class="mb-0">Laporan General Affair</h4>
            <small class="text-muted">Pusat laporan data General Affair.</small>
        </div>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0" style="font-size: 13px">
                <li class="breadcrumb-item">
                    <a href="#"><i class="ti ti-folder me-1"></i>General Affair</a>
                </li>
                <li class="breadcrumb-item active"><i class="ti ti-report me-1"></i>Laporan</li>
            </ol>
        </nav>
    </div>
@endsection
<div class="row">
    <div class="col-lg-10 col-md-12 col-12">
        <div class="nav-align-left mb-4 shadow-none">
            <ul class="nav nav-tabs" role="tablist">
                @can('ga.servicekendaraan')
                    <li class="nav-item" role="presentation">
                        <button type="button" class="nav-link active" role="tab" data-bs-toggle="tab" data-bs-target="#servicekendaraan"
                            aria-controls="servicekendaraan" aria-selected="false" tabindex="-1">
                            <i class="ti ti-settings me-2"></i> Service Kendaraan
                        </button>
                    </li>
                @endcan
                @can('ga.rekapbadstok')
                    <li class="nav-item" role="presentation">
                        <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#rekapbadstok"
                            aria-controls="rekapbadstok" aria-selected="false" tabindex="-1">
                            <i class="ti ti-package-off me-2"></i> Rekap Bad Stok
                        </button>
                    </li>
                @endcan
            </ul>
            <div class="tab-content" style="padding: 0 !important; border: none !important; background: transparent !important;">
                @can('ga.servicekendaraan')
                    <div class="tab-pane fade active show" id="servicekendaraan" role="tabpanel">
                        <div class="card shadow-none border">
                            <div class="card-header border-bottom py-3" style="background-color: #002e65; border-radius: 0.375rem 0.375rem 0 0;">
                                <h6 class="m-0 fw-bold text-white"><i class="ti ti-settings me-2"></i>Laporan Service Kendaraan</h6>
                            </div>
                            <div class="card-body pt-4">
                                @include('generalaffair.laporan.servicekendaraan')
                            </div>
                        </div>
                    </div>
                @endcan
                @can('ga.rekapbadstok')
                    <div class="tab-pane fade" id="rekapbadstok" role="tabpanel">
                        <div class="card shadow-none border">
                            <div class="card-header border-bottom py-3" style="background-color: #002e65; border-radius: 0.375rem 0.375rem 0 0;">
                                <h6 class="m-0 fw-bold text-white"><i class="ti ti-package-off me-2"></i>Laporan Rekap Bad Stok</h6>
                            </div>
                            <div class="card-body pt-4">
                                @include('generalaffair.laporan.rekapbadstok')
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
        const setupSelect2 = (selector, placeholder) => {
            const $el = $(selector);
            if ($el.length) {
                $el.each(function() {
                    const $this = $(this);
                    $this.wrap('<div class="position-relative"></div>').select2({
                        placeholder: placeholder,
                        allowClear: true,
                        dropdownParent: $this.parent()
                    });
                });
            }
        };

        setupSelect2(".select2Kendaraan", 'Semua Kendaraan');
        setupSelect2(".select2Kodecabang", 'Semua Cabang');

        const formServicekendaraan = $('#formLapServicekendaraan');
        const formRekapbadstok = $('#formLapRekapbadstok');

        formServicekendaraan.submit(function(e) {
            const dari = $(this).find("#dari").val();
            const sampai = $(this).find("#sampai").val();
            const start = new Date(dari);
            const end = new Date(sampai);
            if (dari == "") {
                Swal.fire({
                    title: "Oops!",
                    text: 'Periode Dari Harus Diisi !',
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: () => { $(this).find("#dari").focus(); },
                });
                return false;
            } else if (sampai == "") {
                Swal.fire({
                    title: "Oops!",
                    text: 'Periode Sampai Harus Diisi !',
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: () => { $(this).find("#sampai").focus(); },
                });
                return false;
            } else if (start.getTime() > end.getTime()) {
                Swal.fire({
                    title: "Oops!",
                    text: 'Periode Tidak Valid !, Periode Sampai Harus Lebih Akhir dari Periode Dari',
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: () => { $(this).find("#sampai").focus(); },
                });
                return false;
            }
        });

        function loadRekapbadstokbulan() {
            const formatlaporan = formRekapbadstok.find("#formatlaporan").val();
            if (formatlaporan == '1') {
                formRekapbadstok.find("#bulan_container").show();
            } else {
                formRekapbadstok.find("#bulan_container").hide();
            }
        }

        loadRekapbadstokbulan();
        formRekapbadstok.find("#formatlaporan").change(function() {
            loadRekapbadstokbulan();
        });

        formRekapbadstok.submit(function(e) {
            const bulan = $(this).find("#bulan").val();
            const tahun = $(this).find("#tahun").val();
            const formatlaporan = $(this).find("#formatlaporan").val();
            if (formatlaporan == '1' && bulan == '') {
                Swal.fire({
                    title: "Oops!",
                    text: 'Bulan Harus Diisi !',
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: () => { $(this).find("#bulan").focus(); },
                });
                return false;
            } else if (tahun == '') {
                Swal.fire({
                    title: "Oops!",
                    text: 'Tahun Harus Diisi !',
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: () => { $(this).find("#tahun").focus(); },
                });
                return false;
            }
        });
    });
</script>
@endpush
