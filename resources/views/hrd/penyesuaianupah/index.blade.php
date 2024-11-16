@extends('layouts.app')
@section('titlepage', 'Penysesuaian Upah')

@section('content')
@section('navigasi')
    <span>Penyesuaian Upah</span>
@endsection
<div class="row">
    <div class="col-lg-5 col-sm-12 col-xs-12">
        <div class="card">
            <div class="card-header">
                @can('penyupah.create')
                    <a href="#" class="btn btn-primary" id="btnCreate"><i class="fa fa-plus me-2"></i> Buat Penyesuaian Upah</a>
                @endcan
            </div>
            <div class="card-body">
                {{-- <div class="row">
                    <div class="col-12">
                        <form action="{{ route('slipgaji.index') }}">
                            <div class="row">
                                <div class="col">
                                    <div class="form-group mb-3">
                                        <button type="submit" class="btn btn-primary w-100"><i class="ti ti-search me-1"></i>Cari Data</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div> --}}
                <div class="row">
                    <div class="col-12">
                        <div class="table-responsive mb-2">
                            <table class="table">
                                <thead class="table-dark">
                                    <tr>
                                        <th>Kode</th>
                                        <th>Bulan</th>
                                        <th>Tahun</th>
                                        <th>#</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($penyupah as $d)
                                        <tr>
                                            <td>{{ $d->kode_gaji }}</td>
                                            <td>{{ $namabulan[$d->bulan] }}</td>
                                            <td>{{ $d->tahun }}</td>
                                            <td>
                                                <div class="d-flex">
                                                    @can('penyupah.edit')
                                                        <a href="{{ route('penyupah.show', Crypt::encrypt($d->kode_gaji)) }}" class="me-1">
                                                            <i class="ti ti-settings text-primary"></i>
                                                        </a>
                                                        <a href="#" class="btnEdit me-1" kode_gaji="{{ Crypt::encrypt($d->kode_gaji) }}">
                                                            <i class="ti ti-edit text-success"></i>
                                                        </a>
                                                    @endcan
                                                    @can('penyupah.delete')
                                                        <form method="POST" name="deleteform" class="deleteform"
                                                            action="{{ route('penyupah.delete', Crypt::encrypt($d->kode_gaji)) }}">
                                                            @csrf
                                                            @method('DELETE')
                                                            <a href="#" class="delete-confirm me-1">
                                                                <i class="ti ti-trash text-danger"></i>
                                                            </a>
                                                        </form>
                                                    @endcan

                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        {{-- <div style="float: right;">
                            {{ $badstok->links() }}
                        </div> --}}
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
    $(function() {
        $("#btnCreate").click(function(e) {
            e.preventDefault();
            $("#modal").modal("show");
            $("#modal").find(".modal-title").text("Buat Penyesuaian Gaji");
            $("#loadmodal").load(`/penyupah/create`);
        });

        $(".btnEdit").click(function(e) {
            e.preventDefault();
            var kode_gaji = $(this).attr("kode_gaji");
            $("#modal").modal("show");
            $("#modal").find(".modal-title").text("Edit Penyesuaian Gaji");
            $("#loadmodal").load(`/penyupah/${kode_gaji}/edit`);
        });
    });
</script>
@endpush
