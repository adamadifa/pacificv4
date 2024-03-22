@extends('layouts.app')
@section('titlepage', 'OMAN Cabang')

@section('content')
@section('navigasi')
    <span>OMAN Cabang</span>
@endsection

<div class="row">
    <div class="col-lg-6">
        <div class="nav-align-top nav-tabs-shadow mb-4">
            <ul class="nav nav-tabs nav-fill" role="tablist">
                <li class="nav-item" role="presentation">
                    <button type="button" class="nav-link active" role="tab" data-bs-toggle="tab"
                        data-bs-target="#navs-justified-home" aria-controls="navs-justified-home" aria-selected="true">
                        <i class="tf-icons ti ti-file-description ti-lg me-1"></i> Oman Cabang

                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button type="button" class="nav-link" role="tab" data-bs-toggle="tab"
                        data-bs-target="#navs-justified-profile" aria-controls="navs-justified-profile"
                        aria-selected="false" tabindex="-1">
                        <i class="tf-icons ti ti-file-description ti-lg me-1"></i> Oman Marketing
                    </button>
                </li>

            </ul>
            <div class="tab-content">
                <div class="tab-pane fade active show" id="navs-justified-home" role="tabpanel">
                    @can('omancabang.create')
                        <a href="{{ route('omancabang.create') }}" class="btn btn-primary"><i class="fa fa-plus me-2"></i>
                            Buat Oman</a>
                    @endcan
                    <div class="row mt-2">
                        <div class="col-12">
                            <form action="{{ route('omancabang.index') }}">
                                <div class="row">
                                    <div class="col-lg-6 col-sm-12 col-md-12">
                                        <div class="form-group mb-3">
                                            <select name="bulan_search" id="bulan_search" class="form-select">
                                                <option value="">Bulan</option>
                                                @foreach ($list_bulan as $d)
                                                    <option
                                                        {{ Request('bulan_search') == $d['kode_bulan'] ? 'selected' : '' }}
                                                        value="{{ $d['kode_bulan'] }}">{{ $d['nama_bulan'] }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-sm-12 col-md-12">
                                        <div class="form-group mb-3">
                                            <select name="tahun_search" id="tahun_search" class="form-select">
                                                <option value="">Tahun</option>
                                                @for ($t = $start_year; $t <= date('Y'); $t++)
                                                    <option
                                                        @if (!empty(Request('tahun_search'))) {{ Request('tahun_search') == $t ? 'selected' : '' }}
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
                                            <th>Kode</th>
                                            <th>Cabang</th>
                                            <th>Bulan</th>
                                            <th>Tahun</th>
                                            <th>#</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($oman_cabang as $d)
                                            <tr>
                                                <td>{{ $d->kode_oman }}</td>
                                                <td>{{ $d->kode_cabang }}</td>
                                                <td>{{ $nama_bulan[$d->bulan] }}</td>
                                                <td>{{ $d->tahun }}</td>
                                                <td>
                                                    <div class="d-flex">
                                                        @can('omancabang.edit')
                                                            <div>
                                                                <a href="#" class="me-2 editOmancabang"
                                                                    kode_oman="{{ Crypt::encrypt($d->kode_oman) }}">
                                                                    <i class="ti ti-edit text-success"></i>
                                                                </a>
                                                            </div>
                                                        @endcan
                                                        @can('omancabang.show')
                                                            <div>
                                                                <a href="#" class="me-2 show"
                                                                    kode_oman="{{ Crypt::encrypt($d->kode_oman) }}">
                                                                    <i class="ti ti-file-description text-info"></i>
                                                                </a>
                                                            </div>
                                                        @endcan

                                                        @can('omancabang.delete')
                                                            <div>
                                                                <form method="POST" name="deleteform" class="deleteform"
                                                                    action="{{ route('omancabang.delete', Crypt::encrypt($d->kode_oman)) }}">
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
                            <div style="float: right;">
                                {{ $oman_cabang->links() }}
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

@endsection
