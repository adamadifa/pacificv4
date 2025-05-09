@extends('layouts.app')
@section('titlepage', 'Saldo Awal Kas Kecil')
@section('content')
@section('navigasi')
    <span>Saldo Awal Kas Kecil</span>
@endsection
<div class="row">
    <div class="col-lg-10">
        <div class="nav-align-top nav-tabs-shadow mb-4">
            @include('layouts.navigation_kaskecil')
            <div class="tab-content">
                <div class="tab-pane fade active show" id="navs-justified-home" role="tabpanel">
                    @can('sakaskecil.create')
                        <a href="#" id="btnCreate" class="btn btn-primary"><i class="fa fa-plus me-2"></i>
                            Buat Saldo Awal
                        </a>
                    @endcan
                    <div class="row mt-2">
                        <div class="col-12">
                            <form action="{{ URL::current() }}">
                                <div class="row">
                                    <div class="col-lg-5 col-sm-12 col-md-12">
                                        <div class="form-group mb-3">
                                            <select name="kode_cabang_search" id="kode_cabang_search"
                                                class="form-select select2Kodecabangsearch">
                                                <option value="">Pilih Cabang</option>
                                                @foreach ($cabang as $d)
                                                    <option
                                                        {{ Request('kode_cabang_search') == $d->kode_cabang ? 'selected' : '' }}
                                                        value="{{ $d->kode_cabang }}">{{ $d->nama_cabang }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-sm-12 col-md-12">
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
                                    <div class="col-lg-2 col-sm-12 col-md-12">
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
                                        <button class="btn btn-primary"><i
                                                class="ti ti-icons ti-search me-1"></i></button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <div class="table-responsive mb-2">
                                <table class="table table-striped table-hover table-bordered">
                                    <thead class="table-dark">
                                        <tr>
                                            <th>No</th>
                                            <th>Kode</th>
                                            <th>Tanggal</th>
                                            <th>Bulan</th>
                                            <th>Tahun</th>
                                            <th>Bank</th>
                                            <th>Jumlah</th>
                                            <th>#</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($saldo_awal as $d)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $d->kode_saldo_awal }}</td>
                                                <td>{{ formatIndo($d->tanggal) }}</td>
                                                <td>{{ $nama_bulan[$d->bulan] }}</td>
                                                <td>{{ $d->tahun }}</td>
                                                <td>{{ $d->nama_cabang }}</td>
                                                <td class="text-end fw-bold">{{ formatAngka($d->jumlah) }}</td>
                                                <td>
                                                    @can('sakaskecil.delete')
                                                        <form method="POST" name="deleteform" class="deleteform"
                                                            action="{{ route('sakaskecil.delete', Crypt::encrypt($d->kode_saldo_awal)) }}">
                                                            @csrf
                                                            @method('DELETE')
                                                            <a href="#" class="cancel-confirm me-1">
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
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<x-modal-form id="modal" show="loadmodal" title="" />
@endsection
@push('myscript')
{{-- <script src="{{ asset('assets/js/pages/roles/create.js') }}"></script> --}}
<script>
    $(function() {

        $("#btnCreate").click(function(e) {
            e.preventDefault();
            $("#modal").modal("show");
            $("#modal").find(".modal-title").text("Buat Saldo Awal Kas Kecil");
            $("#loadmodal").load(`/sakaskecil/create`);
        });

        const select2Kodecabangsearch = $('.select2Kodecabangsearch');
        if (select2Kodecabangsearch.length) {
            select2Kodecabangsearch.each(function() {
                var $this = $(this);
                $this.wrap('<div class="position-relative"></div>').select2({
                    placeholder: 'Pilih Cabang',
                    allowClear: true,
                    dropdownParent: $this.parent()
                });
            });
        }
    });
</script>
@endpush
