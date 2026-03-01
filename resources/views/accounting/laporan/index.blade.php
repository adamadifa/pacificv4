@extends('layouts.app')
@push('myscript')
    <script>
        $(function() {
            // Function to handle form validation and submission
            function validateForm(formId, fields) {
                $(`#${formId}`).submit(function(e) {
                    let isValid = true;
                    fields.forEach(field => {
                        const value = $(this).find(`#${field.id}`).val();
                        if (value === "") {
                            Swal.fire({
                                title: "Oops!",
                                text: `${field.label} Harus Diisi !`,
                                icon: "warning",
                                showConfirmButton: true,
                                didClose: () => {
                                    $(this).find(`#${field.id}`).focus();
                                },
                            });
                            isValid = false;
                            return false;
                        }
                    });
                    return isValid;
                });
            }

            // Initialize Form Validations
            validateForm('frmRekappersediaan', [
                { id: 'bulan', label: 'Bulan' },
                { id: 'tahun', label: 'Tahun' }
            ]);

            validateForm('frmRekapbj', [
                { id: 'bulan', label: 'Bulan' },
                { id: 'tahun', label: 'Tahun' }
            ]);

            validateForm('formCostratio', [
                { id: 'bulan', label: 'Bulan' },
                { id: 'tahun', label: 'Tahun' }
            ]);

            $(`#formJurnalumum`).submit(function(e) {
                const dari = $(this).find('#dari').val();
                const sampai = $(this).find('#sampai').val();
                const start = new Date(dari);
                const end = new Date(sampai);

                if (dari == "") {
                    Swal.fire({ title: "Oops!", text: "Dari Tanggal Harus Diisi !", icon: "warning", showConfirmButton: true });
                    return false;
                } else if (sampai == "") {
                    Swal.fire({ title: "Oops!", text: "Sampai Tanggal Harus Diisi !", icon: "warning", showConfirmButton: true });
                    return false;
                } else if (start.getTime() > end.getTime()) {
                    Swal.fire({ title: "Oops!", text: "Periode Tidak Valid !", icon: "warning", showConfirmButton: true });
                    return false;
                }
            });

            $(`#formLedger`).submit(function(e) {
                const formatlaporan = $(this).find("#formatlaporan").val();
                const dari = $(this).find("#dari").val();
                const sampai = $(this).find("#sampai").val();
                const tahun = $(this).find("#tahun").val();
                const start = new Date(dari);
                const end = new Date(sampai);
                
                if (formatlaporan == "") {
                    Swal.fire({ title: "Oops!", text: 'Jenis Laporan Harus Diisi !', icon: "warning", showConfirmButton: true });
                    return false;
                } 
                
                if (formatlaporan == '4' || formatlaporan == '5') {
                    if (tahun == "") {
                         Swal.fire({ title: "Oops!", text: 'Tahun Harus Diisi !', icon: "warning", showConfirmButton: true });
                        return false;
                    }
                } else {
                    if (dari == "") {
                        Swal.fire({ title: "Oops!", text: 'Periode Dari Harus Diisi !', icon: "warning", showConfirmButton: true });
                        return false;
                    } else if (sampai == "") {
                        Swal.fire({ title: "Oops!", text: 'Periode Sampai Harus Diisi !', icon: "warning", showConfirmButton: true });
                        return false;
                    } else if (start > end) {
                        Swal.fire({ title: "Oops!", text: 'Periode Tidak Valid !', icon: "warning", showConfirmButton: true });
                        return false;
                    }
                }
            });

            // Initialize Select2
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

            setupSelect2(".select2Kodecabang", 'Semua Cabang');
            setupSelect2(".select2Kodeakundari", 'Semua Akun');
            setupSelect2(".select2Kodeakunsampai", 'Semua Akun');

            // Buku Besar Specific Logic
            const formLedger = $("#formLedger");
            function showCoa() {
                const formatlaporan = formLedger.find("#formatlaporan").val();
                if (formatlaporan == '1') {
                    $("#jenislaporan_container").show();
                    $("#coa").show();
                    $("#formatcetak_container").hide();
                    $("#row_periode").show();
                    $("#row_tahun").hide();
                } else if(formatlaporan == '2' || formatlaporan == '3'){
                    $("#jenislaporan_container").hide();
                    $("#coa").hide();
                    $("#formatcetak_container").show();
                    formLedger.find("#kode_akun_dari").val("").trigger('change');
                    formLedger.find("#kode_akun_sampai").val("").trigger('change');
                    $("#row_periode").show();
                    $("#row_tahun").hide();
                } else if (formatlaporan == '4' || formatlaporan == '5') {
                    $("#jenislaporan_container").hide();
                    $("#coa").hide();
                    $("#formatcetak_container").hide();
                    $("#row_periode").hide();
                    $("#row_tahun").show();
                    formLedger.find("#kode_akun_dari").val("").trigger('change');
                    formLedger.find("#kode_akun_sampai").val("").trigger('change');
                } else {
                    $("#jenislaporan_container").hide();
                    $("#coa").hide();
                    $("#formatcetak_container").hide();
                    $("#row_periode").show();
                    $("#row_tahun").hide();
                }
            }

            showCoa();
            formLedger.find("#formatlaporan").change(function() {
                showCoa();
            });
        });
    </script>
@endpush
@section('titlepage', 'Laporan Accounting')

@section('content')

@section('navigasi')
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h4 class="mb-0">Laporan Accounting</h4>
            <small class="text-muted">Pusat laporan data accounting dan keuangan.</small>
        </div>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0" style="font-size: 13px">
                <li class="breadcrumb-item">
                    <a href="#"><i class="ti ti-folder me-1"></i>Accounting</a>
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
                @can('akt.rekappersediaan')
                    <li class="nav-item" role="presentation">
                        <button type="button" class="nav-link active" role="tab" data-bs-toggle="tab" data-bs-target="#rekappersediaan"
                            aria-controls="rekappersediaan" aria-selected="false" tabindex="-1">
                            <i class="ti ti-box me-2"></i> Rekap Persediaan
                        </button>
                    </li>
                @endcan
                @can('akt.rekapbj')
                    <li class="nav-item" role="presentation">
                        <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#rekapbj" aria-controls="rekapbj"
                            aria-selected="false" tabindex="-1">
                            <i class="ti ti-file-invoice me-2"></i> Rekap BJ
                        </button>
                    </li>
                @endcan
                @can('akt.costratio')
                    <li class="nav-item" role="presentation">
                        <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#costratio" aria-controls="costratio"
                            aria-selected="false" tabindex="-1">
                            <i class="ti ti-chart-pie me-2"></i> Cost Ratio
                        </button>
                    </li>
                @endcan
                @can('akt.jurnalumum')
                    <li class="nav-item" role="presentation">
                        <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#jurnalumum"
                            aria-controls="jurnalumum" aria-selected="false" tabindex="-1">
                            <i class="ti ti-book me-2"></i> Jurnal Umum
                        </button>
                    </li>
                @endcan
                @can('lk.bukubesar')
                    <li class="nav-item" role="presentation">
                        <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#bukubesar"
                            aria-controls="bukubesar" aria-selected="false" tabindex="-1">
                            <i class="ti ti-report-money me-2"></i> Laporan Keuangan
                        </button>
                    </li>
                @endcan
            </ul>
            <div class="tab-content" style="padding: 0 !important; border: none !important; background: transparent !important;">
                @can('akt.rekappersediaan')
                    <div class="tab-pane fade active show" id="rekappersediaan" role="tabpanel">
                        <div class="card shadow-none border">
                            <div class="card-header border-bottom py-3" style="background-color: #002e65; border-radius: 0.375rem 0.375rem 0 0;">
                                <h6 class="m-0 fw-bold text-white"><i class="ti ti-box me-2"></i>Laporan Rekap Persediaan</h6>
                            </div>
                            <div class="card-body pt-4">
                                @include('accounting.laporan.rekappersediaan')
                            </div>
                        </div>
                    </div>
                @endcan
                @can('akt.rekapbj')
                    <div class="tab-pane fade" id="rekapbj" role="tabpanel">
                        <div class="card shadow-none border">
                            <div class="card-header border-bottom py-3" style="background-color: #002e65; border-radius: 0.375rem 0.375rem 0 0;">
                                <h6 class="m-0 fw-bold text-white"><i class="ti ti-file-invoice me-2"></i>Laporan Rekap BJ</h6>
                            </div>
                            <div class="card-body pt-4">
                                @include('accounting.laporan.rekapbj')
                            </div>
                        </div>
                    </div>
                @endcan
                @can('akt.costratio')
                    <div class="tab-pane fade" id="costratio" role="tabpanel">
                        <div class="card shadow-none border">
                            <div class="card-header border-bottom py-3" style="background-color: #002e65; border-radius: 0.375rem 0.375rem 0 0;">
                                <h6 class="m-0 fw-bold text-white"><i class="ti ti-chart-pie me-2"></i>Laporan Cost Ratio</h6>
                            </div>
                            <div class="card-body pt-4">
                                @include('accounting.laporan.costratio')
                            </div>
                        </div>
                    </div>
                @endcan
                @can('akt.jurnalumum')
                    <div class="tab-pane fade" id="jurnalumum" role="tabpanel">
                        <div class="card shadow-none border">
                            <div class="card-header border-bottom py-3" style="background-color: #002e65; border-radius: 0.375rem 0.375rem 0 0;">
                                <h6 class="m-0 fw-bold text-white"><i class="ti ti-book me-2"></i>Laporan Jurnal Umum</h6>
                            </div>
                            <div class="card-body pt-4">
                                @include('accounting.laporan.jurnalumum')
                            </div>
                        </div>
                    </div>
                @endcan
                @can('lk.bukubesar')
                    <div class="tab-pane fade" id="bukubesar" role="tabpanel">
                        <div class="card shadow-none border">
                            <div class="card-header border-bottom py-3" style="background-color: #002e65; border-radius: 0.375rem 0.375rem 0 0;">
                                <h6 class="m-0 fw-bold text-white"><i class="ti ti-report-money me-2"></i>Laporan Keuangan (Buku Besar)</h6>
                            </div>
                            <div class="card-body pt-4">
                                @include('accounting.laporan.lk.bukubesar')
                            </div>
                        </div>
                    </div>
                @endcan
            </div>
        </div>
    </div>
</div>
@endsection
