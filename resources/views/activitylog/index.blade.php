@extends('layouts.app')
@section('titlepage', 'Log Aktivitas')

@section('content')
@section('navigasi')
    <span>Log Aktivitas</span>
@endsection
<div class="row">
    <div class="col-lg-12 col-sm-12 col-xs-12">
        <div class="card">
            <div class="card-header">

            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-12">
                        <form action="{{ route('activitylog.index') }}">
                            <div class="row">
                                <div class="col-lg-6 col-sm-12 col-md-12">
                                    <x-input-with-icon label="Dari" value="{{ Request('dari') }}" name="dari" icon="ti ti-calendar"
                                        datepicker="flatpickr-date" />
                                </div>
                                <div class="col-lg-6 col-sm-12 col-md-12">
                                    <x-input-with-icon label="Sampai" value="{{ Request('sampai') }}" name="sampai" icon="ti ti-calendar"
                                        datepicker="flatpickr-date" />
                                </div>
                            </div>
                            <div class="row">
                                <div class="col">
                                    <div class="form-group">
                                        <select name="log_name" id="log_name" class="form-select select2LogName">
                                            <option value="">Pilih Kategori</option>
                                            @foreach ($kategori as $d)
                                                <option value="{{ $d->log_name }}" {{ Request('log_name') == $d->log_name ? 'selected' : '' }}>
                                                    {{ textCamelCase($d->log_name) }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="col">
                                    <div class="form-group">
                                        <select name="event" id="event" class="form-select select2Event">
                                            <option value="">Pilih Aktivitas</option>
                                            <option value="create" {{ Request('event') == 'create' ? 'selected' : '' }}>Created</option>
                                            <option value="update" {{ Request('event') == 'update' ? 'selected' : '' }}>Updated</option>
                                            <option value="cancel" {{ Request('event') == 'cancel' ? 'selected' : '' }}>Cancel</option>
                                            <option value="delete" {{ Request('event') == 'delete' ? 'selected' : '' }}>Delete</option>

                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="form-group">
                                    <select name="id_user" id="id_user" class="form-select select2User">
                                        <option value="">Pilih User</option>
                                        @foreach ($users as $d)
                                            <option value="{{ $d->id }}" {{ Request('id_user') == $d->id ? 'selected' : '' }}>
                                                {{ $d->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col">
                                    <x-input-with-icon label="No. Bukti" value="{{ Request('no_bukti') }}" name="no_bukti" icon="ti ti-search" />
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-12 col-sm-12 col-md-12">
                                    <div class="form-group mb-3">
                                        <button class="btn btn-primary w-100" id="btnSearch"><i class="ti ti-search me-1"></i>Cari</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <div class="table-responsive mb-2">
                            <table class="table table-bordered ">
                                <thead class="table-dark">
                                    <tr>
                                        <th>Waktu</th>
                                        <th>Kategori</th>
                                        <th>User</th>
                                        <th>Aksi</th>
                                        <th>Aktivitas</th>
                                        <th>#</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($activity as $d)
                                        @php
                                            if ($d->event == 'create') {
                                                $color = 'success';
                                            } elseif ($d->event == 'update') {
                                                $color = 'info';
                                            } else {
                                                $color = 'danger';
                                            }
                                        @endphp
                                        <tr>
                                            <td>{{ date('d-m-Y H:i:s', strtotime($d->created_at)) }}</td>
                                            <td>{{ textCamelCase($d->log_name) }}</td>
                                            <td>{{ $d->name }}</td>
                                            <td>
                                                <span class="badge bg-{{ $color }}">
                                                    {{ textUpperCase($d->event) }}
                                                </span>
                                            </td>
                                            <td>{{ $d->description }}</td>
                                            <td>
                                                <a href="#" class="showDetail"
                                                    properties="{{ json_encode($d->properties, JSON_PRETTY_PRINT) }}">
                                                    <i class="ti ti-file-description"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div style="float: right;">
                            {{ $activity->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<x-modal-form id="modal" size="" show="loadmodal" title="" />
@endsection
@push('myscript')
<script>
    $(document).ready(function() {
        $(document).on('click', '.showDetail', function() {
            var properties = $(this).attr('properties');
            $('#modal').modal('show');
            $('#modal').find('.modal-title').text('Detail Aktivitas');
            $('#modal').find('#loadmodal').html(`<pre>${properties}</pre>`);
        });

        const select2User = $('.select2User');
        if (select2User.length) {
            select2User.each(function() {
                var $this = $(this);
                $this.wrap('<div class="position-relative"></div>').select2({
                    placeholder: 'Pilih Pengguna',
                    allowClear: true,
                    dropdownParent: $this.parent()
                });
            });
        }
    });
</script>
@endpush
