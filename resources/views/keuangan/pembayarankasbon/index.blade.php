@extends('layouts.app')
@section('titlepage', 'Pembayaran Kasbon')

@section('content')
@section('navigasi')
    <span>Pembayaran Kasbon</span>
@endsection
<div class="row">
    <div class="col-lg-6">
        <div class="nav-align-top nav-tabs-shadow mb-4">
            @include('layouts.navigation_kasbon')
            <div class="tab-content">
                <div class="tab-pane fade active show" id="navs-justified-home" role="tabpanel">
                    @can('pembayarankasbon.create')
                        <a href="#" class="btn btn-primary" id="btnCreate"><i class="fa fa-plus me-2"></i>
                            Input Pembayaran Kasbon
                        </a>
                    @endcan

                    <div class="row mt-2">
                        <div class="col-12">
                            <form action="{{ route('pembayarankasbon.index') }}">
                                <div class="row">
                                    <div class="col-lg-6 col-sm-12 col-md-12">
                                        <div class="form-group mb-3">
                                            <select name="bulan" id="bulan" class="form-select">
                                                <option value="">Bulan</option>
                                                @foreach ($list_bulan as $d)
                                                    <option {{ Request('bulan') == $d['kode_bulan'] ? 'selected' : '' }}
                                                        value="{{ $d['kode_bulan'] }}">{{ $d['nama_bulan'] }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-sm-12 col-md-12">
                                        <div class="form-group mb-3">
                                            <select name="tahun" id="tahun" class="form-select">
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
                                        <button class="btn btn-primary"><i class="ti ti-icons ti-search me-1"></i></button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <div class="table-responsive mb-2">
                                <table class="table  table-bordered">
                                    <thead class="table-dark">
                                        <tr>
                                            <th>Kode</th>
                                            <th>Bulan</th>
                                            <th>Tahun</th>
                                            <th>Jumlah</th>
                                            <th>#</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($historibayar as $d)
                                            <tr>
                                                <td>{{ $d->kode_potongan }}</td>
                                                <td>{{ $namabulan[$d->bulan] }}</td>
                                                <td>{{ $d->tahun }}</td>
                                                <td class="text-end fw-bold">{{ formatRupiah($d->totalpembayaran) }}</td>
                                                <td>
                                                    <div class="d-flex">
                                                        @can('pembayarankasbon.show')
                                                            <div>
                                                                <a href="#" class="btnShow"
                                                                    kode_potongan="{{ Crypt::encrypt($d->kode_potongan) }}">
                                                                    <i class="ti ti-file-description text-info me-1"></i>
                                                                </a>
                                                            </div>
                                                        @endcan
                                                        @can('pembayarankasbon.delete')
                                                            <div>
                                                                <form method="POST" name="deleteform" class="deleteform"
                                                                    action="{{ route('pembayarankasbon.deletegenerate', Crypt::encrypt($d->kode_potongan)) }}">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                    <a href="#" class="delete-confirm ml-1">
                                                                        <i class="ti ti-trash text-danger"></i>
                                                                    </a>
                                                                </form>
                                                            </div>
                                                        @endcan
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
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
    $(function() {

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
            $("#modal").find(".modal-title").text('Generate Pembayaran Kasbon');
            $("#modal").find("#loadmodal").load(`pembayarankasbon/create`);
            $("#modal").find(".modal-dialog").removeClass("modal-xl");
        });


        $(".btnShow").click(function(e) {
            e.preventDefault();
            const kode_potongan = $(this).attr('kode_potongan');
            loading();
            $("#modal").modal("show");
            $("#modal").find(".modal-title").text('Detail Pembayaran PJP');
            $("#modal").find(".modal-dialog").addClass("modal-xl");
            $("#modal").find("#loadmodal").load(`/pembayarankasbon/${kode_potongan}/false/show`);

        });
    });
</script>
@endpush
