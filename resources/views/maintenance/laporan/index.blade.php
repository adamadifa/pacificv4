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
            validateForm('formLapBahanBakar', [
                { id: 'kode_barang', label: 'Barang' },
                { id: 'bulan', label: 'Bulan' },
                { id: 'tahun', label: 'Tahun' }
            ]);
        });
    </script>
@endpush
@section('titlepage', 'Laporan Maintenance')

@section('content')

@section('navigasi')
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h4 class="mb-0">Laporan Maintenance</h4>
            <small class="text-muted">Pusat laporan data maintenance.</small>
        </div>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0" style="font-size: 13px">
                <li class="breadcrumb-item">
                    <a href="#"><i class="ti ti-folder me-1"></i>Maintenance</a>
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
                @can('mtc.bahanbakar')
                    <li class="nav-item" role="presentation">
                        <button type="button" class="nav-link active" role="tab" data-bs-toggle="tab" data-bs-target="#bahanbakar"
                            aria-controls="bahanbakar" aria-selected="false" tabindex="-1">
                            <i class="ti ti-gas-station me-2"></i> Bahan Bakar
                        </button>
                    </li>
                @endcan
            </ul>
            <div class="tab-content" style="padding: 0 !important; border: none !important; background: transparent !important;">
                @can('mtc.bahanbakar')
                    <div class="tab-pane fade active show" id="bahanbakar" role="tabpanel">
                        <div class="card shadow-none border">
                            <div class="card-header border-bottom py-3" style="background-color: #002e65; border-radius: 0.375rem 0.375rem 0 0;">
                                <h6 class="m-0 fw-bold text-white"><i class="ti ti-gas-station me-2"></i>Laporan Bahan Bakar</h6>
                            </div>
                            <div class="card-body pt-4">
                                @include('maintenance.laporan.bahanbakar')
                            </div>
                        </div>
                    </div>
                @endcan
            </div>
        </div>
    </div>
</div>
@endsection
