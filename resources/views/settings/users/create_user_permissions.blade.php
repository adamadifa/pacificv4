@extends('layouts.app')
@section('titlepage', 'Set User Permissions')

@section('content')
@section('navigasi')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h4 class="mb-0">User Permissions</h4>
            <small class="text-muted">Set specific permissions for <strong>{{ ucwords($user->name) }}</strong></small>
        </div>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0" style="font-size: 13px">
                <li class="breadcrumb-item"><a href="{{ route('users.index') }}">Users</a></li>
                <li class="breadcrumb-item active">Set Permissions</li>
            </ol>
        </nav>
    </div>
@endsection

<form action="{{ route('users.storeuserpermission', Crypt::encrypt($user->id)) }}" method="POST">
    @csrf
    
    {{-- Sticky Header for User Info and Save Action --}}
    <div class="card mb-4 shadow-sm border sticky-top" style="top: 80px; z-index: 1000; background: rgba(255, 255, 255, 0.9); backdrop-filter: blur(10px);">
        <div class="card-body py-3 d-flex justify-content-between align-items-center">
            <div class="d-flex align-items-center">
                <div class="avatar avatar-md me-3">
                    <span class="avatar-initial rounded-circle bg-label-primary">
                        {{ substr($user->name, 0, 1) }}
                    </span>
                </div>
                <div>
                    <h5 class="mb-0">{{ ucwords($user->name) }}</h5>
                    <span class="badge bg-label-info">{{ $user->getRoleNames()[0] }}</span>
                </div>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('users.index') }}" class="btn btn-label-secondary">
                    <i class="ti ti-arrow-left me-1"></i> Kembali
                </a>
                <button type="submit" class="btn btn-primary px-4 shadow">
                    <i class="ti ti-device-floppy me-1"></i> Simpan Perubahan
                </button>
            </div>
        </div>
    </div>

    <div class="row g-4">
        @foreach ($permissions as $key => $d)
            <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12">
                <div class="card h-100 shadow-none border overflow-hidden">
                    <div class="card-header d-flex justify-content-between align-items-center py-2 px-3" 
                         style="background-color: #002e65 !important;">
                        <h6 class="mb-0 text-white fw-semibold small">
                            <i class="ti ti-folder-check me-1"></i> {{ $d->group_name }}
                        </h6>
                        <div class="form-check form-check-sm mb-0">
                            <input class="form-check-input select-all-group" type="checkbox" style="cursor: pointer" 
                                   data-group="{{ $d->id_permission_group }}">
                        </div>
                    </div>
                    <div class="card-body p-3">
                        @php
                            $list_permissions = explode(',', $d->permissions);
                        @endphp
                        @foreach ($list_permissions as $p)
                            @php
                                $permission = explode('-', $p);
                                $permission_id = $permission[0];
                                $permission_name = $permission[1];
                                $isInherited = in_array($permission_name, $rolepermissions);
                                $isDirect = in_array($permission_name, $userpermissions);
                                $isChecked = $isInherited || $isDirect;
                            @endphp
                            <div class="permission-item d-flex justify-content-between align-items-center mb-2 px-2 py-1 rounded-2 transition-all hover-bg-light">
                                <div class="form-check mb-0">
                                    <input class="form-check-input permission-checkbox group-{{ $d->id_permission_group }}" 
                                           type="checkbox" name="permission[]" value="{{ $permission_name }}"
                                           id="p-{{ $permission_id }}" {{ $isChecked ? 'checked' : '' }}
                                           style="cursor: pointer">
                                    <label class="form-check-label ms-1 small text-dark" for="p-{{ $permission_id }}" style="cursor: pointer">
                                        {{ str_replace('.', ' ', $permission_name) }}
                                    </label>
                                </div>
                                @if ($isInherited)
                                    <span class="badge bg-label-secondary small-badge" 
                                          data-bs-toggle="tooltip" title="Inherited from Role">
                                        <i class="ti ti-shield-check" style="font-size: 10px"></i>
                                    </span>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    {{-- Bottom Floating FAB for mobile --}}
    <div class="d-lg-none position-fixed bottom-0 end-0 p-4" style="z-index: 1001;">
        <button type="submit" class="btn btn-primary btn-icon rounded-pill shadow-lg p-3">
            <i class="ti ti-device-floppy ti-md"></i>
        </button>
    </div>
</form>

<style>
    .hover-bg-light:hover {
        background-color: rgba(var(--bs-primary-rgb), 0.05) !important;
    }
    .transition-all {
        transition: all 0.2s ease-in-out;
    }
    .small-badge {
        padding: 0.2rem 0.35rem;
        font-size: 0.65rem;
    }
    .sticky-top {
        transition: box-shadow 0.3s ease;
    }
    .card.h-100 {
        transition: transform 0.2s ease, border-color 0.2s ease;
    }
    .card.h-100:hover {
        border-color: rgba(var(--bs-primary-rgb), 0.3) !important;
    }
</style>

@push('myscript')
    <script>
        $(function() {
            // Select All Group
            $('.select-all-group').on('change', function() {
                var groupId = $(this).data('group');
                $('.group-' + groupId).prop('checked', $(this).prop('checked'));
            });

            // Auto-check select all if all items in group are checked
            $('.permission-checkbox').on('change', function() {
                var classes = $(this).attr('class').split(' ');
                var groupClass = classes.filter(c => c.startsWith('group-'))[0];
                var groupId = groupClass.replace('group-', '');
                var total = $('.' + groupClass).length;
                var checked = $('.' + groupClass + ':checked').length;
                $('.select-all-group[data-group="' + groupId + '"]').prop('checked', total === checked);
            });

            // Initialize tooltips
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
            var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl)
            });
        });
    </script>
@endpush
@endsection
