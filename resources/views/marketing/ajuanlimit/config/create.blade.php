@extends('layouts.app')
@section('titlepage', 'Tambah Konfigurasi Approval')

@section('content')
@section('navigasi')
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h4 class="mb-0">Tambah Konfigurasi</h4>
            <small class="text-muted">Buat alur approval baru berdasarkan rentang nominal.</small>
        </div>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0" style="font-size: 13px">
                <li class="breadcrumb-item">
                    <a href="#"><i class="ti ti-folder me-1"></i>Marketing</a>
                </li>
                <li class="breadcrumb-item">
                    <a href="{{ route('ajuanlimitconfig.index') }}">Konfigurasi</a>
                </li>
                <li class="breadcrumb-item active">Tambah</li>
            </ol>
        </nav>
    </div>
@endsection

<div class="row">
    <div class="col-md-8">
        <form action="{{ route('ajuanlimitconfig.store') }}" method="POST">
            @csrf
            <div class="card shadow-sm border mb-4">
                <div class="card-header border-bottom py-3" style="background-color: #002e65;">
                    <h6 class="m-0 fw-bold text-white"><i class="ti ti-currency-dollar me-2"></i>Rentang Nominal</h6>
                </div>
                <div class="card-body mt-3">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Minimal Nominal</label>
                            <input type="text" name="min_limit" class="form-control money" placeholder="0" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Maksimal Nominal</label>
                            <input type="text" name="max_limit" class="form-control money" placeholder="0" required>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card shadow-sm border">
                <div class="card-header border-bottom py-3" style="background-color: #002e65;">
                    <h6 class="m-0 fw-bold text-white"><i class="ti ti-list me-2"></i>Urutan Role Approval</h6>
                </div>
                <div class="card-body mt-3">
                    <div id="role-container">
                        <div class="row mb-3 role-row align-items-end">
                            <div class="col-10">
                                <label class="form-label">Role</label>
                                <select name="roles[]" class="form-select select2">
                                    <option value="">Pilih Role</option>
                                    @foreach ($roles as $role)
                                        <option value="{{ $role->name }}">{{ textUpperCase($role->name) }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-2">
                                <button type="button" class="btn btn-danger btn-remove-role"><i class="ti ti-trash"></i></button>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between mt-4">
                        <button type="button" class="btn btn-outline-primary" id="btn-add-role">
                            <i class="ti ti-plus me-1"></i> Tambah Role
                        </button>
                        <button type="submit" class="btn btn-primary">
                            <i class="ti ti-device-floppy me-1"></i> Simpan Konfigurasi
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <div class="col-md-4">
        <div class="alert alert-info d-flex" role="alert">
            <span class="badge badge-center rounded-pill bg-info border-label-info p-3 me-2"><i class="ti ti-info-circle ti-xs"></i></span>
            <div class="d-flex flex-column ps-1">
                <h6 class="alert-heading d-flex align-items-center fw-bold mb-1">Panduan Pengaturan</h6>
                <span>1. Tentukan rentang nominal (misal: 0 - 5.000.000).</span>
                <span>2. Tambahkan role sesuai urutan approval yang diinginkan.</span>
                <span>3. Role terakhir dalam urutan akan menjadi pemberi approval akhir (Status Approved).</span>
                <span>4. Pastikan rentang nominal tidak tumpang tindih dengan konfigurasi lain.</span>
            </div>
        </div>
    </div>
</div>

{{-- Template for new role row --}}
<template id="role-row-template">
    <div class="row mb-3 role-row align-items-end">
        <div class="col-10">
            <select name="roles[]" class="form-select select2-new">
                <option value="">Pilih Role</option>
                @foreach ($roles as $role)
                    <option value="{{ $role->name }}">{{ textUpperCase($role->name) }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-2">
            <button type="button" class="btn btn-danger btn-remove-role"><i class="ti ti-trash"></i></button>
        </div>
    </div>
</template>

@endsection

@push('myscript')
    <script>
        $(function() {
            function initPlugins(element) {
                $(element).find('.select2, .select2-new').select2({
                    placeholder: 'Pilih Role',
                    allowClear: true,
                    dropdownParent: $(element).closest('.card-body')
                });

                $('.money').maskMoney({
                    prefix: '',
                    thousands: '.',
                    decimal: ',',
                    precision: 0
                });
            }

            $('.role-row').each(function() {
                initPlugins(this);
            });

            // Initialize money for inputs outside role-row
            $('.money').maskMoney({
                prefix: '',
                thousands: '.',
                decimal: ',',
                precision: 0
            });

            $('#btn-add-role').click(function() {
                const template = document.getElementById('role-row-template');
                const clone = template.content.cloneNode(true);
                const $newNode = $(clone);
                $('#role-container').append($newNode);
                initPlugins($('#role-container .role-row').last());
            });

            $(document).on('click', '.btn-remove-role', function() {
                if ($('.role-row').length > 1) {
                    $(this).closest('.role-row').remove();
                } else {
                    $(this).closest('.role-row').find('select').val('').trigger('change');
                }
            });
        });
    </script>
@endpush
