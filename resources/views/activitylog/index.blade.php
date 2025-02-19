@extends('layouts.app')
@section('titlepage', 'Visit Pelanggan')

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
                        {{-- <form action="{{ route('visitpelanggan.index') }}">

                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12">
                                    <div class="form-group mb-3">
                                        <button class="btn btn-primary w-100"><i class="ti ti-search me-1"></i>Cari
                                            Data</button>
                                    </div>
                                </div>
                            </div>

                        </form> --}}
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <div class="table-responsive mb-2">
                            <table class="table table-bordered ">
                                <thead class="table-dark">
                                    <tr>
                                        <th>Waktu</th>
                                        <th>User</th>
                                        <th>Kategori</th>
                                        <th>Aktivitas</th>
                                        <th>#</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($activity as $d)
                                        <tr>
                                            <td>{{ date('d-m-Y H:i:s', strtotime($d->created_at)) }}</td>
                                            <td>{{ $d->name }}</td>
                                            <td>{{ $d->log_name }}</td>
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
                            {{-- {{ $visit->links() }} --}}
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
    });
</script>
@endpush
