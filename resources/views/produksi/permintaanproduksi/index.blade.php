@extends('layouts.app')
@section('titlepage', 'Permintaan Produksi')

@section('content')
@section('navigasi')
    <span>Permintaan Produksi</span>
@endsection
<div class="row">
    <div class="col-lg-8 col-sm-12 col-xs-12">
        <div class="card">
            <div class="card-header">
                @can('permintaanproduksi.create')
                    <a href="#" class="btn btn-primary" id="btnCreate"><i class="fa fa-plus me-2"></i> Buat
                        Permintaan</a>
                @endcan
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-12">
                        <form action="{{ route('permintaanproduksi.index') }}">
                            <div class="row">
                                <div class="col-lg-10 col-sm-12 col-md-12">
                                    <div class="form-group mb-3">
                                        <select name="tahun_search" id="tahun_search" class="form-select">
                                            <option value="">Tahun</option>
                                            @for ($t = $start_year; $t <= date('Y'); $t++)
                                                <option
                                                    @if (!empty(Request('tahun'))) {{ Request('tahun') == $t ? 'selected' : '' }}
                                                    @else
                                                    {{ date('Y') == $t ? 'selected' : '' }} @endif
                                                    value="{{ $t }}">{{ $t }}</option>
                                            @endfor
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-2 col-sm-12 col-md-12">
                                    <button class="btn btn-primary"><i
                                            class="ti ti-icons ti-search me-1"></i>Cari</button>
                                </div>
                            </div>

                        </form>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <div class="table-responsive mb-2">
                            <table class="table">
                                <thead class="table-dark">
                                    <tr>
                                        <th>No. Permintaan</th>
                                        <th>Tanggal</th>
                                        <th>Kode Oman</th>
                                        <th>Bulan</th>
                                        <th>Tahun</th>
                                        <th>Status</th>
                                        <th>#</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($pp as $d)
                                        <tr>
                                            <td>{{ $d->no_permintaan }}</td>
                                            <td>{{ date('d-m-Y', strtotime($d->tanggal)) }}</td>
                                            <td>{{ $d->kode_oman }}</td>
                                            <td>{{ $namabulan[$d->bulan] }}</td>
                                            <td>{{ $d->tahun }}</td>
                                            <td>
                                                @if ($d->status == 1)
                                                    <span class="badge bg-success">
                                                        Sudah Diproses Oleh Produksi
                                                    </span>
                                                @else
                                                    <span class="badge bg-danger">Belum di Proses</span>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="d-flex">
                                                    @can('permintaanproduksi.show')
                                                        <div>
                                                            <a href="#" class="me-2 btnShow"
                                                                no_permintaan="{{ Crypt::encrypt($d->no_permintaan) }}">
                                                                <i class="ti ti-file-description text-info"></i>
                                                            </a>
                                                        </div>
                                                    @endcan

                                                    @can('permintaanproduksi.delete')
                                                        @if ($d->status === '0')
                                                            <div>
                                                                <form method="POST" name="deleteform" class="deleteform"
                                                                    action="{{ route('permintaanproduksi.delete', Crypt::encrypt($d->no_permintaan)) }}">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                    <a href="#" class="delete-confirm ml-1">
                                                                        <i class="ti ti-trash text-danger"></i>
                                                                    </a>
                                                                </form>
                                                            </div>
                                                        @endif
                                                    @endcan
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div style="float: right;">
                            {{-- {{ $produk->links() }} --}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<x-modal-form id="mdlCreate" size="modal-xl" show="loadCreate" title="Buat Permintaan Produksi" />
<x-modal-form id="mdlDetail" size="modal-xl" show="loadDetail" title="Detail" />
@endsection
@push('myscript')
<script>
    $(function() {
        $("#btnCreate").click(function(e) {
            $('#mdlCreate').modal("show");
            $("#loadCreate").load("{{ route('permintaanproduksi.create') }}");
        });

        $(".btnShow").click(function(e) {
            const no_permintaan = $(this).attr('no_permintaan');
            $('#mdlDetail').modal("show");
            $("#loadDetail").load("/permintaanproduksi/" + no_permintaan + "/show");
        });
    });
</script>
@endpush
