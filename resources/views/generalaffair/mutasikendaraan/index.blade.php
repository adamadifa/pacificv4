@extends('layouts.app')
@section('titlepage', 'Mutasi Kendaraan')

@section('content')
@section('navigasi')
    <span>Mutasi Kendaraan</span>
@endsection
<div class="row">
    <div class="col-lg-10 col-sm-12 col-xs-12">
        <div class="card">
            <div class="card-header">
                @can('mutasikendaraan.create')
                    <a href="#" class="btn btn-primary" id="btnCreate"><i class="fa fa-plus me-2"></i> Input Mutasi
                        Kendaraan</a>
                @endcan
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-12">
                        <form action="{{ route('mutasikendaraan.index') }}">
                            <x-input-with-icon label="Cari No. Polisi" value="{{ Request('no_polisi') }}" name="no_polisi" icon="ti ti-barcode" />
                        </form>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <div class="table-responsive mb-2">
                            <table class="table">
                                <thead class="table-dark">
                                    <tr>
                                        <th>No. Mutasi</th>
                                        <th>No. Polisi</th>
                                        <th>Tgl Mutasi</th>
                                        <th>Asal Cabang</th>
                                        <th>Pindah Ke Cabang</th>
                                        <th>Keterangan</th>
                                        <th>#</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($mutasikendaraan as $k)
                                        <tr>
                                            <td>{{ $k->no_mutasi }}</td>
                                            <td>{{ $k->no_polisi }}</td>
                                            <td>{{ formatIndo($k->tanggal) }}</td>
                                            <td>{{ $k->cabang_asal }}</td>
                                            <td>{{ $k->cabang_tujuan }}</td>
                                            <td>{{ $k->keterangan }}</td>
                                            <td>
                                                @can('mutasikendaraan.delete')
                                                    <form method="POST" name="deleteform" class="deleteform"
                                                        action="{{ route('mutasikendaraan.delete', Crypt::encrypt($k->no_mutasi)) }}">
                                                        @csrf
                                                        @method('DELETE')
                                                        <a href="#" class="delete-confirm me-1">
                                                            <i class="ti ti-trash text-danger"></i>
                                                        </a>
                                                    </form>
                                                @endcan
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div style="float: right;">
                            {{ $mutasikendaraan->links() }}
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
{{-- <script src="{{ asset('assets/js/pages/roles/create.js') }}"></script> --}}
<script>
    $(function() {
        function buttonDisable() {
            $("#btnSimpan").prop('disabled', true);
            $("#btnSimpan").html(`
            <div class="spinner-border spinner-border-sm text-white me-2" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
            Loading..
         `);
        }

        function loading() {
            $("#loadmodal").html(`<div class="sk-wave sk-primary" style="margin:auto">
            <div class="sk-wave-rect"></div>
            <div class="sk-wave-rect"></div>
            <div class="sk-wave-rect"></div>
            <div class="sk-wave-rect"></div>
            <div class="sk-wave-rect"></div>
            </div>`);
        };
        $("#btnCreate").click(function(e) {
            e.preventDefault();
            loading();
            $("#modal").modal("show");
            $(".modal-title").text("Input Mutasi Kendaraan");
            $("#loadmodal").load(`/mutasikendaraan/create`);
        });


    });
</script>
@endpush
