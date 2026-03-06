@extends('layouts.app')
@section('titlepage', 'Edit Konfigurasi Approval')

@section('content')
@section('navigasi')
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h4 class="mb-0">Edit Konfigurasi</h4>
            <small class="text-muted">Ubah alur approval ajuan faktur.</small>
        </div>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0" style="font-size: 13px">
                <li class="breadcrumb-item">
                    <a href="#"><i class="ti ti-folder me-1"></i>Marketing</a>
                </li>
                <li class="breadcrumb-item">
                    <a href="{{ route('ajuanfakturconfig.index') }}">Konfigurasi</a>
                </li>
                <li class="breadcrumb-item active">Edit</li>
            </ol>
        </nav>
    </div>
@endsection

<div class="row">
    <div class="col-md-8">
        <form action="{{ route('ajuanfakturconfig.update', $config->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="card shadow-sm border">
                <div class="card-header border-bottom py-3" style="background-color: #002e65;">
                    <h6 class="m-0 fw-bold text-white"><i class="ti ti-list me-2"></i>Urutan Role Approval</h6>
                </div>
                <div class="card-body mt-3">
                    <div id="role-container">
                        @php
                            $selected_roles = is_string($config->roles) ? json_decode($config->roles) : $config->roles;
                        @endphp
                        @foreach ($selected_roles as $selected_role)
                            <div class="row mb-3 role-row align-items-end">
                                <div class="col-10">
                                    <label class="form-label">Role</label>
                                    <select name="roles[]" class="form-select select2">
                                        <option value="">Pilih Role</option>
                                        @foreach ($roles as $role)
                                            <option value="{{ $role->name }}" {{ $selected_role == $role->name ? 'selected' : '' }}>
                                                {{ textUpperCase($role->name) }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-2">
                                    <button type="button" class="btn btn-danger btn-remove-role"><i class="ti ti-trash"></i></button>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="d-flex justify-content-between mt-4">
                        <button type="button" class="btn btn-outline-primary" id="btn-add-role">
                            <i class="ti ti-plus me-1"></i> Tambah Role
                        </button>
                        <button type="submit" class="btn btn-primary">
                            <i class="ti ti-device-floppy me-1"></i> Perbarui Konfigurasi
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
                <span>1. Tambahkan atau kurangi role sesuai kebutuhan alur approval.</span>
                <span>2. Pastikan urutan role sudah benar dari awal hingga akhir.</span>
                <span>3. Klik Simpan Perubahan untuk menerapkan konfigurasi baru.</span>
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
            }

            $('.role-row').each(function() {
                initPlugins(this);
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
