@extends('layouts.app')
@section('titlepage', 'Saldo Simpanan')

@section('content')
@section('navigasi')
    <span>Saldo Simpanan</span>
@endsection
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12">
        <div class="nav-align-top nav-tabs-shadow mb-4">
            @include('layouts.navigation_monitoringprogram')
            @include('layouts.navigation_simpanan')
            <div class="tab-content">
                <div class="tab-pane fade active show" id="navs-justified-home" role="tabpanel">
                    <div class="row mt-2">
                        <div class="col-12">
                            <form action="{{ URL::current() }}">
                                @hasanyrole($roles_show_cabang)
                                    <div class="form-group mb-3">
                                        <select name="kode_cabang" id="kode_cabang" class="form-select select2Kodecabang">
                                            <option value="">Semua Cabang</option>
                                            @foreach ($cabang as $d)
                                                <option {{ Request('kode_cabang') == $d->kode_cabang ? 'selected' : '' }} value="{{ $d->kode_cabang }}">
                                                    {{ textUpperCase($d->nama_cabang) }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                @endrole
                                <x-input-with-icon icon="ti ti-user" label="Nama Pelanggan" name="nama_pelanggan"
                                    value="{{ Request('nama_pelanggan') }}" />
                                <div class="row">
                                    <div class="col-lg-12 col-md-12 col-sm-12">
                                        <div class="form-group mb-3">
                                            <button class="btn btn-primary w-100"><i class="ti ti-heart-rate-monitor me-1"></i>Tampilkan
                                                Data</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <div class="table-responsive mb-2">
                                <table id="example" class="display nowrap table  table-bordered" style="width:100%">
                                    <thead class="table-dark">
                                        <tr>
                                            <th>No.</th>
                                            <th>Kode</th>
                                            <th>Nama Pelanggan</th>
                                            <th>Salesman</th>
                                            <th>Wilayah</th>
                                            <th>Jumlah</th>
                                            <th>Dicairkan</th>
                                            <th>Saldo</th>
                                            <th>#</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($saldosimpanan as $d)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $d->kode_pelanggan }}</td>
                                                <td>{{ $d->nama_pelanggan }}</td>
                                                <td>{{ $d->nama_salesman }}</td>
                                                <td>{{ $d->nama_wilayah }}</td>
                                                <td class="text-end">{{ formatAngka($d->total_reward) }}</td>
                                                <td class="text-end">{{ formatAngka($d->total_pencairan) }}</td>
                                                <td class="text-end">
                                                    @php
                                                        $saldo = $d->total_reward - $d->total_pencairan;
                                                    @endphp
                                                    {{ formatAngka($saldo) }}
                                                </td>
                                                <td>
                                                    <div class="d-flex">
                                                        <a href="#" class="me-1 btnShow"
                                                            kode_pelanggan="{{ Crypt::encrypt($d->kode_pelanggan) }}">
                                                            <i class="ti ti-file-description text-primary"></i>
                                                        </a>
                                                        <a href="#" class="me-1 btnCairkan"
                                                            kode_pelanggan = "{{ Crypt::encrypt($d->kode_pelanggan) }}">
                                                            <i class="ti ti-external-link text-success"></i>
                                                        </a>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach

                                    </tbody>
                                </table>
                            </div>
                            <div style="float: right;">
                                {{ $saldosimpanan->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<x-modal-form id="modal" size="" show="loadmodal" title="" />
<x-modal-form id="modalDetailfaktur" size="modal-xl" show="loadmodaldetailfaktur" title="" />
@endsection
@push('myscript')
<script>
    $(function() {
        $(document).on('click', '.btnShow', function(e) {
            e.preventDefault();
            let kode_pelanggan = $(this).attr('kode_pelanggan');
            $("#modal").modal("show");
            $("#modal").find(".modal-title").text('Detail Simpanan');
            $("#modal").find("#loadmodal").load(
                `/monitoringprogram/${kode_pelanggan}/getdetailsimpanan`);
        });

        $(document).on('click', '.btnCairkan', function(e) {
            e.preventDefault();
            let kode_pelanggan = $(this).attr('kode_pelanggan');
            $("#modal").modal("show");
            $("#modal").find(".modal-title").text('Cairkan Simpanan');
            $("#modal").find("#loadmodal").load(
                `/monitoringprogram/${kode_pelanggan}/createpencairansimpanan`);
        });
    });
</script>
@endpush
